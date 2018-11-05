<?php

/*  Copyright 2009-2011 Rafael Gutierrez Martinez
 *  Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 *  Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 *  Copyright 2017 nabu-3 Group
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

namespace nabu\data\site;

use nabu\core\CNabuEngine;
use nabu\core\exceptions\ENabuCoreException;
use nabu\data\security\CNabuRole;
use nabu\data\security\interfaces\INabuRoleMask;
use nabu\data\site\base\CNabuSiteTargetBase;
use nabu\data\site\builtin\CNabuBuiltInSiteTargetCTA;
use nabu\data\site\builtin\CNabuBuiltInSiteTargetSection;
use nabu\data\site\builtin\CNabuBuiltInSiteTargetLanguage;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site
 */
class CNabuSiteTarget extends CNabuSiteTargetBase implements INabuRoleMask
{

    const OUTPUT_TYPE_HTML = 'HTML';
    const OUTPUT_TYPE_JSON = 'JSON';
    const OUTPUT_TYPE_PDF  = 'PDF';

    const URL_TYPE_PATH = 'U';
    const URL_TYPE_REGEXP = 'R';
    const URL_TYPE_LIKE = 'L';

    const HTTP_ENABLED = 'T';
    const HTTP_DISABLED = 'F';

    const HTTPS_ENABLED = 'T';
    const HTTPS_DISABLED = 'F';

    const SMARTY_DEBUGGING_ENABLED = 'T';
    const SMARTY_DEBUGGING_DISABLED = 'F';

    const ZONE_PUBLIC = 'O';
    const ZONE_PRIVATE = 'P';
    const ZONE_ALL = 'B';

    const COMMERCE_ENABLED = 'T';
    const COMMERCE_DISABLED = 'F';

    const DYNAMIC_CACHE_CONTROL_ENABLED = 'T';
    const DYNAMIC_CACHE_CONTROL_DISABLED = 'F';
    const DYNAMIC_CACHE_CONTROL_INHERITED = 'S';

    /**
     * Section instances list of this target.
     * @var CNabuSiteTargetSectionList
     */
    private $nb_site_target_section_list = null;
    /**
     * CTA instances list for this target.
     * @var CNabuSiteTargetCTAList
     */
    private $nb_site_target_cta_list = null;

    /**
     * @deprecated since version 3.0.0 Surface
     * @var string
     */
    private $pdf_filename;

    public function __construct($nb_site_target = false)
    {
        parent::__construct($nb_site_target);

        $this->nb_site_target_section_list = new CNabuSiteTargetSectionList();
        $this->nb_site_target_cta_list = new CNabuSiteTargetCTAList();
    }

    /**
     * Locate a Target by their URL. If the Site is multi-language, the search is performed in all languages.
     * Accessible via getValue method, you can access to additional fields nb_language_id and nb_site_target_lang_url.
     * @param CNabuSite $nb_site Site instance that owns the URL.
     * @param string $target_url The URL to search.
     * @return CNabuSiteTarget|null Returns an instance of CNabuSiteTarget if found or null if not.
     */
    public static function findByURL(CNabuSite $nb_site, string $target_url)
    {
        $retval = null;

        if ($nb_site->isPublicBasePathEnabled()) {
            $check_pbp = CNabuEngine::getEngine()->getMainDB()->getQueryAsSingleField(
                'nb_site_lang_public_base_path',
                "SELECT *
                   FROM nb_site_lang
                  WHERE length(nb_site_lang_public_base_path) > 0 
                    AND instr('%url\$s', 1, nb_site_lang_public_base_lang)=1
                  LIMIT 1"
            );

            if (strlen($check_pbp) > 0) {
                $target_url = substr($target_url, strlen($check_pbp));
            } else {
                $target_url = null;
            }
        }

        if ($target_url !== null) {
            $retval = CNabuSiteTarget::buildObjectFromSQL(
                    "SELECT ca.*, cal.nb_language_id, cal.nb_site_target_lang_url
                       FROM nb_site_target ca, nb_site_target_lang cal, nb_site_lang sl
                      WHERE ca.nb_site_target_id = cal.nb_site_target_id
                        AND ca.nb_site_id=%site_id\$d
                        AND ca.nb_site_id=sl.nb_site_id
                        AND sl.nb_site_lang_enabled='T'
                        AND cal.nb_language_id=sl.nb_language_id
                        AND ca.nb_site_target_url_filter='U'
                        AND cal.nb_site_target_lang_url='%url\$s'
                      LIMIT 1",
                    array('site_id' => $nb_site->getId(), 'url' => $target_url)
                )

                ??

                CNabuSiteTarget::buildObjectFromSQL(
                    "SELECT ca.*, cal.nb_language_id, cal.nb_site_target_lang_url
                       FROM nb_site_target ca, nb_site_target_lang cal, nb_site_lang sl
                      WHERE ca.nb_site_target_id = cal.nb_site_target_id
                        AND ca.nb_site_id=%site_id\$d
                        AND ca.nb_site_id=sl.nb_site_id
                        AND sl.nb_site_lang_enabled='T'
                        AND cal.nb_language_id=sl.nb_language_id
                        AND ca.nb_site_target_url_filter='R'
                        AND length(cal.nb_site_target_lang_url)>0
                        AND '%url\$s' REGEXP cal.nb_site_target_lang_url
                      ORDER BY ca.nb_site_target_order ASC
                      LIMIT 1",
                    array('site_id' => $nb_site->getValue('nb_site_id'), 'url' => $target_url)
                )

                ??

                CNabuSiteTarget::buildObjectFromSQL(
                    "SELECT ca.*, cal.nb_language_id, cal.nb_site_target_lang_url
                       FROM nb_site_target ca, nb_site_target_lang cal, nb_site_lang sl
                      WHERE ca.nb_site_target_id = cal.nb_site_target_id
                        AND ca.nb_site_id=%site_id\$d
                        AND ca.nb_site_id=sl.nb_site_id
                        AND sl.nb_site_lang_enabled='T'
                        AND cal.nb_language_id=sl.nb_language_id
                        AND ca.nb_site_target_url_filter='L'
                        AND length(cal.nb_site_target_lang_url)>0
                        AND '%url\$s' LIKE cal.nb_site_target_lang_url
                      ORDER BY ca.nb_site_target_order ASC
                      LIMIT 1",
                    array('site_id' => $nb_site->getId(), 'url' => $target_url)
                )
            ;
        }

        if ($retval !== null) {
            $retval->setSite($nb_site);
        }

        return $retval;
    }

    public function indexURLs($url_type)
    {
        $static_urls = null;

        if ($this->hasTranslations()) {
            $static_urls = array();
            $this->getTranslations()->iterate(
                function ($key, $translation) use(&$static_urls, $url_type)
                {
                    if ($this->getURLFilter() === $url_type) {
                        $url = $translation->getURL();
                        $static_urls[$url] = array(
                            'key' => $url,
                            'pointer' => array(
                                $this->getId(),
                                $translation->getLanguageId()
                            ),
                            'order' => $this->getOrder()
                        );
                    }
                }
            );
            if (count($static_urls) === 0) {
                $static_urls = null;
            }
        }

        return $static_urls;
    }

    public function setOutputTypeHTML()
    {
        return $this->setOutputType(CNabuSiteTarget::OUTPUT_TYPE_HTML);
    }

    public function setOutputTypeJSON()
    {
        return $this->setOutputType(CNabuSiteTarget::OUTPUT_TYPE_JSON);
    }

    public function enableHTTP()
    {
        return $this->setUseHTTP(self::HTTP_ENABLED);
    }

    public function disableHTTP()
    {
        return $this->setUseHTTP(self::HTTP_DISABLED);
    }

    public function isHTTPEnabled()
    {
        return $this->getUseHTTP() === self::HTTP_ENABLED;
    }

    public function enableHTTPS()
    {
        return $this->setUseHTTPS(self::HTTPS_ENABLED);
    }

    public function disableHTTPS()
    {
        return $this->setUseHTTPS(self::HTTPS_DISABLED);
    }

    public function isHTTPSEnabled()
    {
        return $this->getUseHTTPS() === self::HTTPS_ENABLED;
    }

    public function enableSmartyDebugging()
    {
        return $this->setSmartyDebugging(self::SMARTY_DEBUGGING_ENABLED);
    }

    public function disableSmartyDebugging()
    {
        return $this->setSmartyDebugging(self::SMARTY_DEBUGGING_DISABLED);
    }

    public function isSmartyDebuggingEnabled()
    {
        return $this->getSmartyDebugging() === self::SMARTY_DEBUGGING_ENABLED;
    }

    public function setZoneAsPublic()
    {
        return $this->setZone(self::ZONE_PUBLIC);
    }

    public function setZoneAsPrivate()
    {
        return $this->setZone(self::ZONE_PRIVATE);
    }

    public function setZoneAsAll()
    {
        return $this->setZone(self::ZONE_ALL);
    }

    public function isURLStaticPath()
    {
        return $this->getURLFilter() === self::URL_TYPE_PATH;
    }

    public function isURLLikeExpression()
    {
        return $this->getURLFilter() === self::URL_TYPE_LIKE;
    }

    public function isURLRegExpExpression()
    {
        return $this->getURLFilter() === self::URL_TYPE_REGEXP;
    }

    public function isCommerceEnabled()
    {
        return $this->getUseCommerce() === self::COMMERCE_ENABLED;
    }

    public function getFullyQualifiedURL($nb_language = null, $alwaysfull = true, $params = null)
    {
        $url = false;

        $nb_engine = CNabuEngine::getEngine();
        $nb_application = $nb_engine->getApplication();
        $nb_request = $nb_application->getRequest();
        $nb_http_server = $nb_engine->getHTTPServer();
        $nb_site = $nb_http_server->getSite();
        $nb_site_alias = $nb_site->getMainAlias();
        $target_host = $nb_site_alias->getDNSName();
        $nb_language_id = nb_getMixedValue($nb_language, NABU_LANG_FIELD_ID);
        if (!is_numeric($nb_language_id) && !nb_isValidGUID($nb_language_id)) {
            $nb_language_id = $nb_site->getDefaultLanguageId();
        }

        if ((is_numeric($nb_language_id) || nb_isValidGUID($nb_language_id)) &&
            ($nb_translation = $this->getTranslation($nb_language_id)) &&
            ($url = $nb_translation->getEffectiveURL($params)) !== false
        ) {
            $is_http = $nb_site->isHTTPSupportEnabled() && $this->isHTTPEnabled();
            $is_https = $nb_site->isHTTPSSupportEnabled() && $this->isHTTPSEnabled();
            if ($is_https && ($alwaysfull || !$nb_http_server->isSecureServer())) {
                $url = 'https://' . $target_host . $url;
            } elseif ($is_http && ($alwaysfull || $nb_http_server->isSecureServer())) {
                $url = 'http://' . $target_host . $url;
            }
        }

        return $url;
    }

    public function getCommandsList()
    {
        if ($this->contains('nb_site_target_commands')) {
            $commands = preg_split('/(\\s*,\\s*)/', $this->getCommands());
            return count($commands) > 0 ? $commands : null;
        }

        return null;
    }

    /**
     * @deprecated since version 3.0.0 Surface
     * @param string $filename
     */
    public function setPDFFilename($filename) {
        $this->pdf_filename = $filename;
    }

    /**
     * @deprecated since version 3.0.0 Surface
     * @return string
     */
    public function getPDFFilename() {
        return $this->pdf_filename;
    }

    public function setTargetURLStatic($nb_language, $path)
    {
        return $this->setTargetURL(
                $nb_language,
                CNabuSiteTarget::URL_TYPE_PATH,
                $path
        );
    }

    public function setTargetURLLike($nb_language, $path, $reverse_path)
    {
        return $this->setTargetURL(
                $nb_language,
                CNabuSiteTarget::URL_TYPE_LIKE,
                $path,
                $reverse_path
        );
    }

    public function setTargetURLRegExp($nb_language, $path, $reverse_path)
    {
        return $this->setTargetURL(
                $nb_language,
                CNabuSiteTarget::URL_TYPE_REGEXP,
                $path, $reverse_path
        );
    }

    public function setTargetURL($nb_language, $url_type, $path, $reverse_path = false)
    {
        $url_filter = $this->getURLFilter();
        if ($url_filter === $url_type || !$this->hasTranslations()) {
            $this->setURLFilter($url_type);
            $target_lang = $this->getTranslation($nb_language);
            if (!$target_lang) {
                $target_lang = ($this->isBuiltIn() ? new CNabuBuiltInSiteTargetLanguage() : new CNabuSiteTargetLanguage());
                $target_lang->transferValue($this, 'nb_site_target_id');
                $target_lang->transferValue($nb_language, 'nb_language_id');
                $target_lang->setTranslatedObject($this);
            }
            $target_lang->setURL($path);
            $target_lang->setURLRebuild($reverse_path);
            $this->setTranslation($target_lang);

            return $target_lang;
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_URL_FILTER_INVALID);
        }
    }

    public function addCTATarget($key, CNabuSiteTarget $nb_site_target, $order = 0)
    {
        $target_id = nb_getMixedValue($nb_site_target, 'nb_site_target_id');

        $nb_site_target_cta = ($this->isBuiltIn() ? new CNabuBuiltInSiteTargetCTA() : new CNabuSiteTargetCTA());
        $nb_site_target_cta->setKey($key);
        $nb_site_target_cta->setCTATarget($nb_site_target);
        $nb_site_target_cta->setOrder($order);

        return $this->addCTAObject($nb_site_target_cta);
    }

    public function addCTAURL($key, $order = 0)
    {
        $nb_site_target_cta = ($this->isBuiltIn() ? new CNabuBuiltInSiteTargetCTA() : new CNabuSiteTargetCTA());
        $nb_site_target_cta->setKey($key);
        $nb_site_target_cta->setTargetUseURI(CNabuSiteTargetLink::USE_URI_URL);
        $nb_site_target_cta->setOrder($order);

        return $this->addCTAObject($nb_site_target_cta);
    }

    /**
     * Add a CTA to this Site Target instance.
     * @param CNabuSiteTargetCTA $nb_site_target_cta CTA instance to be added.
     * @return CNabuSiteTargetCTA Returns the CTA added.
     */
    public function addCTAObject(CNabuSiteTargetCTA $nb_site_target_cta)
    {
        $nb_site_target_cta->setSiteTarget($this);

        return $this->nb_site_target_cta_list->addItem($nb_site_target_cta);
    }

    /**
     * Get CTAs assigned to this Site Target instance.
     * @param bool $force If true, the CTA list is refreshed from the database.
     * @return CNabuSiteTargetCTAList Returns the list of CTAs. If none CTA exists, the list is empty.
     */
    public function getCTAs($force = false)
    {
        if ($this->nb_site_target_cta_list->isEmpty() || $force) {
            $this->nb_site_target_cta_list->clear();
            $this->nb_site_target_cta_list->merge(CNabuSiteTargetCTA::getSiteTargetCTAs($this));
            $translations = CNabuSiteTargetCTALanguage::getCTATranslationsForSiteTarget($this);
            if (count($translations) > 0) {
                foreach ($translations as $translation) {
                    $nb_site_target_cta = $this->nb_site_target_cta_list->getItem($translation->getSiteTargetCTAId());
                    if ($nb_site_target_cta instanceof CNabuSiteTargetCTA) {
                        $nb_site_target_cta->setTranslation($translation);
                    }
                }
            }
            $roles = CNabuSiteTargetCTARole::getCTARolesForSiteTarget($this);
            if (count($roles) > 0) {
                foreach ($roles as $role) {
                    $nb_site_target_cta = $this->nb_site_target_cta_list->getItem($role->getSiteTargetCTAId());
                    if ($nb_site_target_cta instanceof CNabuSiteTargetCTA) {
                        $nb_site_target_cta->addRole($role);
                    }
                }
            }
        }

        return $this->nb_site_target_cta_list;
    }

    /**
     * Gets a CTA from the list of CTAs using their key.
     * @param string $key Key of CTA to looking for.
     * @param bool $force If true, forces to reload CTA from database storage.
     * @return CNabuSiteTargetCTA Returns the requested target if exists or null if not.
     */
    public function getCTAByKey($key, $force = false)
    {
        $this->getCTAs($force);

        return $this->nb_site_target_cta_list->getItem($key, CNabuSiteTargetCTAList::INDEX_KEY);
    }

    public function indexCTAs()
    {
        $this->nb_site_target_cta_list->sort();

        return $this;
    }

    public function getCTAKeysIndex()
    {
        return $this->nb_site_target_cta_list->getIndex(CNabuSiteTargetCTAList::INDEX_KEY);
    }

    public function canonizeCTAs()
    {
        $this->nb_site_target_cta_list->iterate(
            function ($key, $nb_site_target_cta)
            {
                if ($nb_site_target_cta->isUsingURIAsTarget() && $nb_site_target_cta->getCTATarget() === null) {
                    $nb_site_target_cta->setCTATarget($this->nb_site->getTarget($nb_site_target_cta->getTargetId()));
                }
                $nb_site_target_cta->canonize();

                return true;
            }
        );
    }

    public function addSection($key, $order = 0)
    {
        $nb_site_target_section = $this->isBuiltIn()
                                ? new CNabuBuiltInSiteTargetSection()
                                : new CNabuSiteTargetSection()
        ;
        $nb_site_target_section->setKey($key);
        $nb_site_target_section->setOrder($order);

        return $this->addSectionObject($nb_site_target_section);
    }

    public function addSectionObject(CNabuSiteTargetSection $nb_site_target_section)
    {
        $nb_site_target_section->setSiteTarget($this);

        return $this->nb_site_target_section_list->addItem($nb_site_target_section);
    }

    public function getSection($nb_site_target_section)
    {
        $nb_site_target_section_id = nb_getMixedValue($nb_site_target_section, 'nb_site_target_section_id');

        return $this->nb_site_target_section_list->getItem($nb_site_target_section_id);
    }

    public function getSections($force = false) {

        if ($this->nb_site_target_section_list->isEmpty() || $force) {
            $this->nb_site_target_section_list->clear();
            $this->nb_site_target_section_list->merge(CNabuSiteTargetSection::getSiteTargetSections($this));
        }

        return $this->nb_site_target_section_list;
    }

    public function indexSections()
    {
        $this->nb_site_target_section_list->sort();

        return $this;
    }

    public function getSectionKeysIndex()
    {
        return $this->nb_site_target_section_list->getIndex(CNabuSiteTargetSectionList::INDEX_KEY);
    }

    /**
     * Overrides getTreeData method to add special branches.
     * If $nb_language have a valid value, also adds a translation object
     * with current translation pointed by it.
     * @param int|object $nb_language Instance or Id of the language to be used.
     * @param bool $dataonly Render only field values and ommit class control flags.
     * @return array Returns a multilevel associative array with all data.
     */
    public function getTreeData($nb_language = null, $dataonly = false)
    {
        $trdata = parent::getTreeData($nb_language, $dataonly);

        $trdata['sections'] = $this->nb_site_target_section_list;
        $trdata['section_keys'] = $this->getSectionKeysIndex();
        $trdata['ctas'] = $this->nb_site_target_cta_list;
        $trdata['cta_keys'] = $this->getCTAKeysIndex();

        return $trdata;
    }

    /**
     * Gets the effective max-age value for Cache-Control HTTP Headers. This method only returns a valid value
     * it the Target is running inside their Application.
     * @return bool|int Returns false if no-cache or the max-age in seconds if cache is applicable.
     * @throws ENabuCoreException Raises an exception it the Site instance is not set.
     */
    public function getDynamicCacheEffectiveMaxAge()
    {
        if (($nb_site = $this->getSite()) === null) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_NOT_FOUND);
        }

        $retval = false;

        if (($nb_application = CNabuEngine::getEngine()->getApplication()) !== null) {
            if (($nb_running_site = $nb_application->getRequest()->getSite()) instanceof CNabuSite &&
                ($nb_running_site->getId() === $nb_site->getId())
            ) {
                if (($nb_security_manager = $nb_application->getSecurityManager()) !== null &&
                    (!$nb_security_manager->isUserLogged() || $nb_security_manager->arePoliciesAccepted())) {
                    $site_cache_control = $nb_site->getDynamicCacheControl();
                    $site_max_age = $nb_site->getDynamicCacheDefaultMaxAge();
                    $target_cache_control = $this->getDynamicCacheControl();
                    $target_max_age = $this->getDynamicCacheMaxAge();

                    if ($target_cache_control === self::DYNAMIC_CACHE_CONTROL_INHERITED &&
                        $site_cache_control === CNabuSite::DYNAMIC_CACHE_CONTROL_ENABLED &&
                        is_numeric($site_max_age) &&
                        $site_max_age > 0
                    ) {
                        $retval = $site_max_age;
                    } elseif ($target_cache_control === self::DYNAMIC_CACHE_CONTROL_ENABLED &&
                              is_numeric($target_max_age) &&
                              $target_max_age > 0
                    ) {
                        $retval = $target_max_age;
                    }
                }
            } else {
                throw new ENabuCoreException(ENabuCoreException::ERROR_SITES_DOES_NOT_MATCH);
            }
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_APPLICATION_REQUIRED);
        }

        return $retval;
    }

    public function applyRoleMask(CNabuRole $nb_role, array $additional = null)
    {
        return $this->nb_site_target_cta_list->applyRoleMask($nb_role, $additional);
    }

    public function refresh(bool $force = false, bool $cascade = false): bool
    {
        return parent::refresh($force, $cascade) &&
            (!$cascade ||
                (
                    $this->getSections($force) &&
                    $this->getCTAs($force)
                )
            )
        ;
    }
}
