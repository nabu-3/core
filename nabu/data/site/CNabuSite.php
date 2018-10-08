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

use nabu\cache\CNabuCacheNull;
use nabu\data\security\CNabuUserList;

use nabu\cache\interfaces\INabuCacheStorage;
use nabu\core\CNabuEngine;
use nabu\core\exceptions\ENabuCoreException;
use nabu\core\exceptions\ENabuSecurityException;
use nabu\data\CNabuDataObject;
use nabu\data\cluster\CNabuServer;
use nabu\data\cluster\CNabuClusterUser;
use nabu\data\commerce\traits\TNabuCommerceChild;
use nabu\data\customer\traits\TNabuCustomerChild;
use nabu\data\lang\CNabuLanguage;
use nabu\data\messaging\CNabuMessaging;
use nabu\data\security\CNabuUser;
use nabu\data\security\CNabuRole;
use nabu\data\security\CNabuRoleList;
use nabu\data\site\base\CNabuSiteBase;
use nabu\messaging\CNabuMessagingFactory;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\site
 */

class CNabuSite extends CNabuSiteBase
{
    use TNabuCustomerChild;
    use TNabuCommerceChild;

    const ENABLE_FRAMEWORK = 'T';
    const DISABLE_FRAMEWORK = 'F';

    const ENABLE_SMARTY = 'T';
    const DISABLE_SMARTY = 'F';

    const SITE_PUBLISHED = 'T';
    const SITE_RETIRED = 'F';

    const ENABLE_HTTP_SUPPORT = 'T';
    const DISABLE_HTTP_SUPPORT = 'F';

    const ENABLE_HTTPS_SUPPORT = 'T';
    const DISABLE_HTTPS_SUPPORT = 'F';

    const DYNAMIC_CACHE_CONTROL_ENABLED = 'T';
    const DYNAMIC_CACHE_CONTROL_DISABLED = 'F';

    /** @var string ZONE_PUBLIC Role is applied to Public Zone only. */
    const ZONE_PUBLIC = 'O';
    /** @var string ZONE_PRIVATE Role is applied to Private Zone only. */
    const ZONE_PRIVATE = 'P';
    /** @var string ZONE_BOTH Role is applied to both zones. */
    const ZONE_BOTH = 'B';
    /** @var string Role is disabled. */
    const ZONE_HIDDEN = 'H';

    /** @var INabuCacheStorage $cache_storage nabu-3 Cache Storage instance of this site. */
    private $cache_storage = null;
    /** @var CNabuSiteMapTree $nb_site_map_tree List of all Site Maps of first level. Other levels are indexed
      * inside their parent level. */
    private $nb_site_map_tree = null;
    /** @var CNabuSiteTargetList $nb_site_target_list List of all targets loaded in the Site. */
    private $nb_site_target_list = null;
    /** @var CNabuSiteStaticContentList $nb_site_static_content_list List of all Static Content loaded in the Site. */
    private $nb_site_static_content_list = null;
    /** @var CNabuLanguage $nb_default_language Default language instance of this Site. */
    private $nb_default_language = null;
    /** @var CNabuSiteAlias $nb_site_main_alias Main Site Alias of this Site. */
    private $nb_site_main_alias = null;
    /** @var CNabuSiteAliasList $nb_site_alias_list Site Alias List of this Site. */
    private $nb_site_alias_list = null;
    /** @var CNabuRoleList $nb_role_list List of Roles availables in this Site. */
    private $nb_role_list = null;
    /** @var CNabuSiteRoleList $nb_site_role_list List of Site Roles availables in this Site. */
    private $nb_site_role_list = null;
    /** @var CNabuRole $nb_default_role Default Role of this Site. */
    private $nb_default_role = null;
    /** @var CNabuClusterUser $nb_cluster_user Cluster User instance of this Site. */
    private $nb_cluster_user = null;
    /** @var CNabuUserList List of Users. This list can be a partial list depending of the method called to fill it. */
    private $nb_user_list = null;

    public function __construct($nb_site = false)
    {
        parent::__construct($nb_site);

        $this->nb_site_alias_list = new CNabuSiteAliasList();
        $this->nb_site_map_tree = new CNabuSiteMapTree($this);
        $this->nb_site_target_list = new CNabuSiteTargetList();
        $this->nb_site_static_content_list = new CNabuSiteStaticContentList($this);
        $this->nb_role_list = new CNabuRoleList();
        $this->nb_site_role_list = new CNabuSiteRoleList();
        $this->nb_user_list = new CNabuUserList();
    }

    /**
     * Find a Site by some of their aliases.
     * @param string $alias Alias DNS name to search.
     * @return CNabuSite|null Returns a valid Site if found or null if not.
     */
    static public function findByAlias(string $alias)
    {
        return CNabuSite::buildObjectFromSQL(
            "select s.*, sa.nb_site_alias_id, dzh.nb_domain_zone_id, dzh.nb_domain_zone_host_id "
            . "from nb_site s, nb_site_alias sa, nb_domain_zone_host dzh, nb_domain_zone dz "
           . "where sa.nb_site_id=s.nb_site_id "
             . "and sa.nb_domain_zone_host_id=dzh.nb_domain_zone_host_id "
             . "and dzh.nb_domain_zone_id=dz.nb_domain_zone_id "
             . "and dzh.nb_domain_zone_host_type in ('A', 'CNAME') "
             . "and concat(dzh.nb_domain_zone_host_name, '.', dz.nb_domain_zone_name)='%server_name\$s'",
            array(
                'server_name' => $alias
            )
        );
    }

    /**
     * Get the Cache Storage instance of this Site instance. If Cache is not instantiated or $force is true,
     * then force to create a new Cache instance.
     * @param bool $force If true forces to create the Cache Storage.
     * @return INabuCacheStorage Returns the Cache Storage instance.
     */
    public function getCacheStorage(bool $force = false)
    {
        if ($this->cache_storage === null || $force) {
            $this->cache_storage = null;
            $cache_handler = $this->getValue('nb_site_cache_handler');
            if ($this->isValueEqualThan('nb_site_use_cache', 'T') && is_string($cache_handler)) {
                $storage = new $cache_handler();
                if ($storage->initStorage()) {
                    $this->cache_storage = $storage;
                }
            }
            if ($this->cache_storage === null) {
                $this->cache_storage = new CNabuCacheNull();
            }
        }

        return $this->cache_storage;
    }

    /**
     * Add a target to a site.
     * @param CNabuSiteTarget $nb_site_target Target to add to site.
     * @param bool $default If true sets the target as the default target for this site.
     * @return CNabuSiteTarget Returns the inserted target.
     */
    public function addTarget(CNabuSiteTarget $nb_site_target, $default = false)
    {
        $nb_site_target->setSite($this);

        if ($default) {
            $this->setDefaultTarget($nb_site_target);
        }

        return $this->nb_site_target_list->addItem($nb_site_target);
    }

    /**
     * Gets a Target item from the list of targets loaded in the Site.
     * @param mixed $nb_site_target An instance that contains a field named 'nb_site_target_id' or an Id.
     * @return CNabuSiteTarget Returns the requested target if exists or null if not.
     */
    public function getTarget($nb_site_target)
    {
        $nb_site_target_id = nb_getMixedValue($nb_site_target, 'nb_site_target_id');

        return (is_numeric($nb_site_target_id) || nb_isValidGUID($nb_site_target_id))
               ? $this->nb_site_target_list->getItem($nb_site_target_id)->setSite($this)
               : null
        ;
    }

    /**
     * Gets a target item from the list of targets using their key.
     * @param string $key Key of Target to looking for.
     * @return CNabuSiteTarget Returns the requested target if exists or null if not.
     */
    public function getTargetByKey($key)
    {
        return $this->nb_site_target_list->getItem($key, CNabuSiteTargetList::INDEX_KEY);
    }

    /**
     * Gets a CTA instance related with this Site instance.
     * @param mixed $nb_site_target_cta A Site Target CTA instance, or a CNabuDataObject instance containing a field
     * named nb_site_target_cta_id or an Id.
     * @return CNabuSiteTargetCTA | null Returns an instance if found or null if not.
     */
    public function getCTA($nb_site_target_cta)
    {
        return CNabuSiteTargetCTA::getCTAOfSite($this, $nb_site_target_cta);
    }

    /**
     * Gets the Target list associated with this instance.
     * @param bool $force If true forces to reload entire list from database.
     * @return CNabuSiteTargetList Returns the list instance containing all associated contents.
     */
    public function getTargets($force = false) : CNabuSiteTargetList
    {
        if (!$this->isBuiltIn() &&
            ($this->nb_site_target_list->isEmpty() || $force)
        ) {
            $this->nb_site_target_list->clear();
            $this->nb_site_target_list->merge(CNabuSiteTarget::getAllSiteTargets($this));
            $this->nb_site_target_list->iterate(function ($key, $nb_site_target) {
                $nb_site_target->setSite($this);
                return true;
            });
        }

        return $this->nb_site_target_list;
    }

    /**
     * Add a static content to a site.
     * @param CNabuSiteStaticContent $nb_site_static_content Static Content to add to site.
     * @return CNabuSiteStaticContent Returns the inserted static content.
     */
    public function addStaticContent(CNabuSiteStaticContent $nb_site_static_content)
    {
        $nb_site_static_content->setSite($this);

        return $this->nb_site_static_content_list->addItem($nb_site_static_content);
    }

    /**
     * Gets a Static Content item from the list of static contents loaded in the Site.
     * @param mixed $nb_site_static_content An instance that contains a field named 'nb_site_static_content_id'
     * or an Id.
     * @return CNabuSiteStaticContent Returns the requested static content if exists or null if not.
     */
    public function getStaticContent($nb_site_static_content)
    {
        $nb_site_static_content_id = nb_getMixedValue($nb_site_static_content, 'nb_site_static_content_id');

        return (is_numeric($nb_site_static_content_id) || nb_isValidGUID($nb_site_static_content_id))
               ? $this->nb_site_static_content_list->getItem($nb_site_static_content_id)
               : null
       ;
    }

    /**
     * Gets a Static Content identified by its key.
     * @param string $key The key to looking for.
     * @return CNabuSiteStaticContent|null Returns a valid CNabuSiteStaticContent instance if $key matches or null
     * elsewhere.
     */
    public function getStaticContentByKey(string $key)
    {
        return $this->nb_site_static_content_list->getItem($key, CNabuSiteStaticContentList::INDEX_KEY);
    }

    /**
     * Gets the Static Content list associated with this instance.
     * @param bool $force If true forces to reload entire list from database.
     * @return CNabuSiteStaticContentList Returns the list instance containing all associated contents.
     */
    public function getStaticContents($force = false) : CNabuSiteStaticContentList
    {
        if (!$this->isBuiltIn() &&
            ($this->nb_site_static_content_list->isEmpty() || $force)
        ) {
            $this->nb_site_static_content_list = CNabuSiteStaticContent::getStaticContentsForSite($this);
            $this->nb_site_static_content_list->sort();
        }

        return $this->nb_site_static_content_list;
    }

    /**
     * Generic creation of a CNabuSiteTargetLink from a link descriptor contained in their fields.
     * Some of these descriptors can be the "Default" Target, or "Page Not Found" Target.
     * @param string $kind Infix fragment of names of fields involved in referred descriptor.
     * @param CNabuSiteRole|null $nb_site_role Role to check variant of the target link.
     * @return CNabuSiteTargetLink Returns the formed link instance.
     */
    private function getReferredTargetLink(string $kind, CNabuSiteRole $nb_site_role = null)
    {
        $field_use_uri = 'nb_site_' . $kind . '_target_use_uri';
        $field_target_id = 'nb_site_' . $kind . '_target_id';
        $field_lang_url = 'nb_site_lang_' . $kind . '_target_url';

        $retval = false;

        if ($nb_site_role instanceof CNabuSiteRole) {
            $nb_link = $nb_site_role->getReferredTargetLink($kind);

            if ($nb_link->isLinkable()) {
                $retval = $nb_link;
            }
        }

        if (!$retval) {
            $retval = ($this->contains($field_use_uri) && $this->contains($field_target_id))
                    ? CNabuSiteTargetLink::buildLinkFromReferredInstance(
                        $this, $this, $field_use_uri, $field_target_id, $field_lang_url
                      )
                    : new CNabuSiteTargetLink()
            ;
        }

        return $retval;
    }

    /**
     * Gets the Default Target Link instance.
     * @return CNabuSiteTargetLink Returns the Site Target Link generated instance.
     */
    public function getDefaultTargetLink()
    {
        return $this->getReferredTargetLink('default');
    }

    /**
     * Gets the Page Not Found Target Link instance.
     * @return CNabuSiteTargetLink Returns the Site Target Link generated instance.
     */
    public function getPageNotFoundTargetLink()
    {
        return $this->getReferredTargetLink('page_not_found');
    }

    /**
     * Gets the Login Target Link instance.
     * @return CNabuSiteTargetLink Returns the Site Target Link generated instance.
     */
    public function getLoginTargetLink()
    {
        return $this->getReferredTargetLink('login');
    }

    /**
     * Gets the Login Redirection Target Link instance.
     * @param CNabuSiteRole $nb_site_role The Site Role entity to discern if target is in the role or applies
     * general configuration.
     * @return CNabuSiteTargetLink Returns the Site Target Link generated instance.
     */
    public function getLoginRedirectionTargetLink(CNabuSiteRole $nb_site_role)
    {
        return $this->getReferredTargetLink('login_redirection', $nb_site_role);
    }

    /**
     * Gets the Logout Redirection Target Link instance.
     * @return CNabuSiteTargetLink Returns the Site Target Link generated instance.
     */
    public function getLogoutRedirectionTargetLink()
    {
        return $this->getReferredTargetLink('logout_redirection');
    }

    /**
     * Get the Max Login Fails Redirection Target Link instance.
     * @return CNabuSiteTargetLink Returns the Site Target Link generated instance.
     */
    public function getLoginMaxFailsTargetLink()
    {
        return $this->getReferredTargetLink('login_max_fails');
    }

    /**
     * Gets the Alias Not Found Target Link instance.
     * @return CNabuSiteTargetLink Returns the Site Target Link generated instance.
     */
    public function getAliasNotFoundTargetLink()
    {
        return $this->getReferredTargetLink('alias_not_found');
    }

    /**
     * Gets the Alias Locked Target Link instance.
     * @return CNabuSiteTargetLink Returns the Site Target Link generated instance.
     */
    public function getAliasLockedTargetLink()
    {
        return $this->getReferredTargetLink('alias_locked');
    }

    /**
     * Gets the Policies Target Link instance.
     * @return CNabuSiteTargetLink Returns the Site Target Link generated instance.
     */
    public function getPoliciesTargetLink()
    {
        return $this->getReferredTargetLink('policies');
    }

    /**
     * Check if Default Target uses a Site Target object.
     * @return bool Returns true if the Default Site Target is a CNabuSiteTarget object.
     */
    public function isDefaultTargetUsingTarget()
    {
        return $this->getDefaultTargetUseURI() === CNabuSiteTargetLink::USE_URI_TRANSLATED &&
               $this->nb_site_target_list->containsKey($this->getDefaultTargetId());
    }

    /**
     * Sets the default Site Target.
     * @param CNabuDataObject $nb_site_target Target instance to be setted.
     * @return CNabuSite Returns the $this instance.
     */
    public function setDefaultTarget(CNabuDataObject $nb_site_target)
    {
        $this->setDefaultTargetUseURI(CNabuSiteTargetLink::USE_URI_TRANSLATED);
        $this->transferValue($nb_site_target, 'nb_site_target_id', 'nb_site_default_target_id');

        return $this;
    }

    /**
     * Sets the login Site Target.
     * @param CNabuDataObject $nb_site_target Target instance to be setted.
     * @return CNabuSite Returns the $this instance.
     */
    public function setLoginTarget(CNabuDataObject $nb_site_target)
    {
        $this->setLoginTargetUseURI(CNabuSiteTargetLink::USE_URI_TRANSLATED);
        $this->transferValue($nb_site_target, 'nb_site_target_id', 'nb_site_login_target_id');

        return $this;
    }

    /**
     * Gets a Site Map instance, optionally looking for it in nested levels.
     * @param mixed $nb_site_map A CNabuDataObject inherited instance containing a field named nb_site_map_id
     * or an ID.
     * @param bool $cascade If true, the requested Site Map is searched exploring nested Site Maps.
     * @return null|CNabuSiteMap If a Site Map is found then returns the instance elsewhere returns null.
     */
    public function getSiteMap($nb_site_map, $cascade = false)
    {
        $retval = null;

        $nb_site_map_id = nb_getMixedValue($nb_site_map, 'nb_site_map_id');
        if (is_numeric($nb_site_map_id) || nb_isValidGUID($nb_site_map_id)) {
            if ($cascade) {
                $retval = $this->nb_site_map_tree->getItem($nb_site_map_id);
            } else{
                $retval = $this->nb_site_map_tree->getItem($nb_site_map_id);
            }
        }

        return $retval;
    }

    /**
     * Add a map to a site
     * @param CNabuSiteMap $nb_site_map Site Map to add to site
     * @return CNabuSiteMap Returns the inserted site map
     */
    public function addSiteMap(CNabuSiteMap $nb_site_map)
    {
        $nb_site_map->transferValue($this, 'nb_site_id');

        return $this->nb_site_map_tree->addItem($nb_site_map);
    }

    public function getSiteMaps($force = false) : CNabuSiteMapTree
    {
        if (!$this->isBuiltIn() &&
            ($this->nb_site_map_tree->isEmpty() || $force)
        ) {
            $this->nb_site_map_tree->populate();
        }

        return $this->nb_site_map_tree;
    }

    public function canonizeSiteMaps(CNabuSiteTarget $nb_site_target)
    {
        $this->nb_site_map_tree->iterate(
            function ($key, $nb_site_map) use ($nb_site_target)
            {
                $nb_site_map->canonize($nb_site_target);

                return true;
            }
        );
    }

    public function isSmartyAvailable() {

        return $this->getUseSmarty() === CNabuSite::ENABLE_SMARTY;
    }

    public function isPublished()
    {
        return (($this->isValueNumeric('nb_site_id') || $this->isValueGUID('nb_site_id')) &&
                $this->isValueEqualThan('nb_site_published', 'T'));
    }

    public function getDefaultLanguage($force = false)
    {
        if ($this->nb_default_language === null || $force) {
            $this->nb_default_language = null;
            if ($this->isValueNumeric('nb_site_default_language_id') || $this->isValueGUID('nb_site_default_language_id')) {
                $this->nb_default_language = $this->getLanguage($this->getDefaultLanguageId());
            }
        }

        return $this->nb_default_language;
    }

    public function setDefaultLanguage($nb_language)
    {
        $this->transferValue($nb_language, 'nb_language_id', 'nb_default_language_id');

        return $this;
    }

    public function setAlias(CNabuSiteAlias $nb_site_alias)
    {
        $nb_site_alias->setSite($this);

        if ($this->getMainAliasId() === $nb_site_alias->getId()) {
            $this->nb_site_main_alias = $nb_site_alias;
        }

        return $this->nb_site_alias_list->addItem($nb_site_alias);
    }

    public function getMainAlias($force = false)
    {
        if ($this->nb_site_main_alias === null || $force) {
            $this->nb_site_main_alias = null;
            if ($this->isValueNumeric('nb_site_main_alias_id') || $this->isValueGUID('nb_site_main_alias_id')) {
                $nb_site_main_alias = $this->nb_site_alias_list->getItem($this->getMainAliasId());
                $this->nb_site_main_alias = (!$nb_site_main_alias ? null : $nb_site_main_alias);
            }
        }

        return $this->nb_site_main_alias;
    }

    public function setMainAlias(CNabuSiteAlias $nb_site_alias)
    {
        $this->nb_site_main_alias = $this->setAlias($nb_site_alias);
        $this->transferValue($nb_site_alias, 'nb_site_alias_id', 'nb_site_main_alias_id');

        return $this->nb_site_alias_list->addItem($nb_site_alias);
    }

    public function getEffectiveSiteId()
    {
        return $this->isValueNumeric('nb_site_delegate_for_role')
               ? $this->getDelegateForRole()
               : $this->getId()
        ;
    }

    public function getUserProfile($nb_user)
    {
        $nb_user_id = nb_getMixedValue($nb_user, 'nb_user_id');
        $nb_site_id = $this->getEffectiveSiteId();

        if (is_numeric($nb_user_id) && is_numeric($nb_site_id)) {
            return CNabuSiteUser::buildObjectFromSQL(
                'select su.* '
                . 'from nb_user u, nb_site_user su, nb_site s, nb_role r '
               . 'where u.nb_user_id=su.nb_user_id '
                 . 'and su.nb_site_id=s.nb_site_id '
                 . 'and su.nb_role_id=r.nb_role_id '
                 . 'and u.nb_customer_id=s.nb_customer_id '
                 . 'and su.nb_user_id=%user_id$d '
                 . 'and su.nb_site_id=%site_id$d',
                array(
                    'user_id' => $nb_user_id,
                    'site_id' => $nb_site_id
                )
            );
        }
    }

    public function getDefaultRole($force = false)
    {
        if ($this->nb_default_role === null || $force) {
            $this->nb_default_role = null;
            if ($this->isValueNumeric('nb_site_default_role_id')) {
                $role = new CNabuRole($this->getValue('nb_site_default_role_id'));
                if ($role->isFetched()) {
                    $this->nb_default_role = $role;
                }
            }
        }

        return $this->nb_default_role;
    }

    public function setDefaultRole(CNabuRole $nb_role)
    {
        if ($nb_role !== null) {
            $this->transferValue($nb_role, 'nb_role_id', 'nb_site_default_role_id');
            $this->nb_default_role = $nb_role;
        }
    }

    public function findTargetByURL($url)
    {
        $site_target = null;

        $target = $this->nb_site_target_list->findURL($url);

        if (is_array($target)) {
            $site_target = $this->nb_site_target_list->getItem($target[0]);
            $site_target_lang = $site_target->getTranslation($target[1]);
            $site_target->transferValue($site_target_lang, 'nb_language_id');
            $site_target->transferValue($site_target_lang, 'nb_site_target_lang_url');
        } elseif (!$this->isBuiltIn()) {
            $site_target = CNabuSiteTarget::findByURL($this, $url);
        }

        return $site_target;
    }

    public function indexURLs()
    {
        $this->nb_site_target_list->sort();

        return $this;
    }

    public function indexSiteMaps()
    {
        $this->nb_site_map_tree->sort();

        return $this;
    }

    public function indexStaticContents()
    {
        $this->nb_site_static_content_list->sort();

        return $this;
    }

    public function enableHTTPSupport()
    {
        return $this->setHTTPSupport(self::ENABLE_HTTP_SUPPORT);
    }

    public function disableHTTPSupport()
    {
        return $this->setHTTPSupport(self::DISABLE_HTTP_SUPPORT);
    }

    public function isHTTPSupportEnabled()
    {
        return $this->getHTTPSupport() === self::ENABLE_HTTP_SUPPORT;
    }

    public function enableHTTPSSupport()
    {
        return $this->setHTTPSSupport(self::ENABLE_HTTPS_SUPPORT);
    }

    public function disableHTTPSSupport()
    {
        return $this->setHTTPSSupport(self::DISABLE_HTTPS_SUPPORT);
    }

    public function isHTTPSSupportEnabled()
    {
        return $this->getHTTPSSupport() === self::ENABLE_HTTPS_SUPPORT;
    }

    public function getClusterUser($force = false)
    {
        if ($this->nb_cluster_user === null || $force) {
            $this->nb_cluster_user = null;
            if ($this->isValueNumeric('nb_cluster_user_id')) {
                $nb_cluster_user = new CNabuClusterUser($this);
                if ($nb_cluster_user->isFetched()) {
                    $this->nb_cluster_user = $nb_cluster_user;
                }
            }
        }

        return $this->nb_cluster_user;
    }

    public function getHostsForUpdate(CNabuServer $nb_server)
    {
        if (!($nb_server->isFetched()) || !$nb_server->isValueNumeric('nb_server_id')) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_METHOD_PARAMETER_IS_EMPTY, array('$nb_server'));
        }

        $vhosts = null;

        if ($this->isValueNumeric('nb_site_id')) {

            $list = $this->db->getQueryAsArray(
                    "SELECT sh.nb_server_id, sh.nb_server_host_id, cgs.nb_cluster_group_id,
                            cgs.nb_cluster_group_service_id, i.nb_ip_id, sa.nb_site_alias_id,
                            sa.nb_site_id, sa.nb_site_alias_parent, dz.nb_domain_zone_id, sa.nb_domain_zone_host_id,
                            dzh.nb_domain_zone_host_name, dz.nb_domain_zone_name, sa.nb_site_alias_type,
                            cgs.nb_cluster_group_service_use_ssl, i.nb_ip_ip, sh.nb_server_host_port
                       FROM nb_site_alias sa, nb_site_alias_service sas, nb_cluster_group_service cgs,
                            nb_server_host sh, nb_domain_zone_host dzh, nb_domain_zone dz, nb_ip i
                      WHERE sa.nb_site_alias_id=sas.nb_site_alias_id
                        AND sas.nb_cluster_group_service_id=cgs.nb_cluster_group_service_id
                        AND cgs.nb_cluster_group_service_id=sh.nb_cluster_group_service_id
                        AND sa.nb_domain_zone_host_id=dzh.nb_domain_zone_host_id
                        AND dzh.nb_domain_zone_id=dz.nb_domain_zone_id
                        AND sh.nb_ip_id=i.nb_ip_id
                        AND sa.nb_site_alias_status='E'
                        AND sh.nb_server_host_status='E'
                        AND sh.nb_server_id=%server_id\$d
                        AND sa.nb_site_id=%site_id\$d
                      ORDER BY sa.nb_site_alias_parent IS NOT NULL",
                    array(
                        'server_id' => $nb_server->getId(),
                        'site_id' => $this->getId()
                    )
            );

            if (count($list) > 0) {
                $vhosts = array();
                foreach($list as $host) {
                    switch ($host['nb_site_alias_type']) {
                        case 'F': {
                            $id = $host['nb_site_alias_id'].'-'.$host['nb_cluster_group_service_id'].'-'.$host['nb_ip_id'];
                            $vhosts[$id] = array('host' => $host);
                            break;
                        }
                        case 'T': {
                            $parent = $host['nb_site_alias_parent'].'-'.$host['nb_cluster_group_service_id'].'-'.$host['nb_ip_id'];
                            if (array_key_exists($parent, $vhosts)) {
                                if (!array_key_exists('aliases', $vhosts[$parent])) {
                                    $vhosts[$parent]['aliases'] = array();
                                }
                                $vhosts[$parent]['aliases'][] = $host;
                            }
                            break;
                        }
                        case 'R': {
                            $parent = $host['nb_site_alias_parent'].'-'.$host['nb_cluster_group_service_id'].'-'.$host['nb_ip_id'];
                            if (array_key_exists($parent, $vhosts)) {
                                if (!array_key_exists('redirections', $vhosts[$parent])) {
                                    $vhosts[$parent]['redirections'] = array();
                                }
                                $vhosts[$parent]['redirections'][] = $host;
                            }
                            break;
                        }
                    }
                }

                if (count($vhosts) === 0) {
                    $vhosts = null;
                }
            }
        }

        return $vhosts;
    }

    public function getSiteMapKeysIndex()
    {
        $this->getSiteMaps();

        return $this->nb_site_map_tree->getIndex(CNabuSiteMapTree::INDEX_KEY);
    }

    public function getTargetKeysIndex()
    {
        $this->getTargets(true);

        return $this->nb_site_target_list->getIndex(CNabuSiteTargetList::INDEX_KEY);
    }

    public function getStaticContentKeysIndex()
    {
        $this->getStaticContents();

        return $this->nb_site_static_content_list->getIndex(CNabuSiteStaticContentList::INDEX_KEY);
    }

    /**
     * Overrides getTreeData method to add translations branch.
     * If $nb_language have a valid value, also adds a translation object
     * with current translation pointed by it.
     * @param int|CNabuDataObject $nb_language Instance or Id of the language to be used.
     * @param bool $dataonly Render only field values and ommit class control flags.
     * @return array Returns a multilevel associative array with all data.
     */
    public function getTreeData($nb_language = null, $dataonly = false)
    {
        $trdata = parent::getTreeData($nb_language, $dataonly);

        $trdata['languages'] = $this->getLanguages();
        $trdata['roles'] = $this->getRoles();
        $trdata['sitemap_keys'] = $this->getSiteMapKeysIndex();
        $trdata['targets'] = $this->nb_site_target_list;
        $trdata['target_keys'] = $this->getTargetKeysIndex();
        $trdata['static_content_keys'] = $this->getStaticContentKeysIndex();

        return $trdata;
    }

    /**
     * Gets the valid Virtual Host path in a Server.
     * @param CNabuServer $nb_server Server instance.
     * @return string Retuns the fully qualified path.
     */
    public function getVirtualHostPath(CNabuServer $nb_server)
    {
        return $nb_server->getVirtualHostsPath() . DIRECTORY_SEPARATOR . $this->getBasePath();
    }

    /**
     * Gets the valid Virtual Host Source path in a Server.
     * @param CNabuServer $nb_server Server instance.
     * @return string Retuns the fully qualified path.
     */
    public function getVirtualHostSourcePath(CNabuServer $nb_server)
    {
        return $nb_server->getVirtualHostsPath()
             . DIRECTORY_SEPARATOR . $this->getBasePath()
             . NABU_SRC_FOLDER
        ;
    }

    /**
     * Gets the valid Virtual Host PHP path in a Server.
     * @param CNabuServer $nb_server Server instance.
     * @return string Retuns the fully qualified path.
     */
    public function getVirtualHostPHPPath(CNabuServer $nb_server)
    {
        return $nb_server->getVirtualHostsPath()
             . DIRECTORY_SEPARATOR . $this->getBasePath()
             . NABU_PHP_FOLDER
        ;
    }

    /**
     * Gets the valid Virtual Library path in a Server.
     * @param CNabuServer $nb_server Server instance.
     * @return string Returns the fully qualified path.
     */
    public function getVirtualLibrariesPath(CNabuServer $nb_server)
    {
        return $nb_server->getVirtualLibrariesPath() . DIRECTORY_SEPARATOR . $this->getBasePath();
    }

    /**
     * Gets the valid Virtual Cache path in a Server.
     * @param CNabuServer $nb_server Server instance.
     * @return string Returns the fully qualified path.
     */
    public function getVirtualCachePath(CNabuServer $nb_server)
    {
        return $nb_server->getVirtualCachePath() . DIRECTORY_SEPARATOR . $this->getBasePath();
    }

    public function getUsedMIMETypes()
    {
        return $this->getDB()->getQueryAsArrayOfSingleField(
            'nb_mimetype_id',
            "SELECT DISTINCT nb_mimetype_id
               FROM nb_site_target
              WHERE nb_site_id=%site_id\$d
              ORDER BY nb_mimetype_id",
            array(
                'site_id' => $this->getId()
            )
        );
    }

    public function refresh(bool $force = false, bool $cascade = false) : bool
    {
        return parent::refresh($force, $cascade) &&
               (!$cascade ||
                    (
                        $this->getSiteMaps($force) &&
                        $this->getStaticContents($force) &&
                        $this->getTargets($force)
                    )
               )
        ;
    }

    /**
     * Gets the API Language instance.
     * @return CNabuLanguage Returns the API Language instance if defined.
     */
    public function getAPILanguage() : CNabuLanguage
    {
        return $this->getLanguage($this->getAPILanguageId());
    }

    /**
     * Gets a list of all Roles availables in this Site.
     * @param bool $force If true, the list is reloaded from the database storage.
     * @return CNabuRoleList Returns the list of roles if any, or an empty list if none.
     */
    public function getRoles(bool $force = false) : CNabuRoleList
    {
        if ($this->nb_role_list->isEmpty() || $force) {
            $this->nb_role_list->fillFromSite($this);
        }

        return $this->nb_role_list;
    }

    /**
     * Gets a list of all Site Roles availables in this Site.
     * @param bool $force If true, the list is reloaded from the database storage.
     * @return CNabuSiteRoleList Returns the list of Site Roles if any, or an empty list if none.
     */
    public function getSiteRoles(bool $force = false) : CNabuSiteRoleList
    {
        if ($this->nb_site_role_list->isEmpty() || $force) {
            $this->nb_site_role_list->clear();
            $this->nb_site_role_list->merge(CNabuSiteRole::getSiteRolesForSite($this));
        }

        return $this->nb_site_role_list;
    }

    /**
     * Gets a Site Role by his Id.
     * @param mixed $nb_site_role A CNabuDataObject containing a field named nb_role_id or a valid Id.
     * @return CNabuSiteRole|bool Returns the Site Role instance if exists or false if not.
     */
    public function getSiteRole($nb_site_role)
    {
        if (is_numeric($nb_role_id = nb_getMixedValue($nb_site_role, NABU_ROLE_FIELD_ID))) {
            $retval = $this->getSiteRoles()->getItem($nb_role_id);
        } else {
            $retval = false;
        }

        return $retval;
    }

    public function synchronizeRoleGrants($nb_source_role, $nb_target_role, bool $create = false)
    {
        if (is_numeric($nb_source_id = nb_getMixedValue($nb_source_role, NABU_ROLE_FIELD_ID)) &&
            is_numeric($nb_target_id = nb_getMixedValue($nb_target_role, NABU_ROLE_FIELD_ID)) &&
            ($nb_source_site_role = $this->getSiteRole($nb_source_id)) instanceof CNabuSiteRole
        ) {
            $nb_target_site_role = $this->getSiteRole($nb_target_id);
            if (!($nb_target_site_role instanceof CNabuSiteRole)) {
                $nb_target_site_role = new CNabuSiteRole();
                $nb_target_site_role->setSite($this);
                $nb_target_site_role->setRoleId($nb_target_id);
            }
            $nb_target_site_role->setLoginRedirectionTargetUseURI($nb_source_site_role->getLoginRedirectionTargetUseURI());
            $nb_target_site_role->setLoginRedirectionTargetId($nb_source_site_role->getLoginRedirectionTargetId());
            $nb_target_site_role->setPoliciesTargetUseURI($nb_source_site_role->getPoliciesTargetUseURI());
            $nb_target_site_role->setPoliciesTargetId($nb_source_site_role->getPoliciesTargetId());
            $nb_target_site_role->setMessagingTemplateNewUser($nb_source_site_role->getMessagingTemplateNewUser());
            $nb_target_site_role->setMessagingTemplateForgotPassword($nb_source_site_role->getMessagingTemplateForgotPassword());
            $nb_target_site_role->setMessagingTemplateNotifyNewUser($nb_source_site_role->getMessagingTemplateNotifyNewUser());
            $nb_target_site_role->setMessagingTemplateRememberNewUser($nb_source_site_role->getMessagingTemplateRememberNewUser());
            $nb_target_site_role->setMessagingTemplateInviteUser($nb_source_site_role->getMessagingTemplateInviteUser());
            $nb_target_site_role->setMessagingTemplateInviteFriend($nb_source_site_role->getMessagingTemplateInviteFriend());
            $nb_target_site_role->setMessagingTemplateNewMessage($nb_source_site_role->getMessagingTemplateNewMessage());
            if ($nb_target_site_role->save()) {
                $this->getDB()->executeDelete(
                    "DELETE FROM nb_site_role_lang
                      WHERE nb_site_id=%site_id\$d
                        AND nb_role_id=%role_id\$d",
                    array(
                        'site_id' => $this->getId(),
                        'role_id' => $nb_target_id
                    )
                );
                $this->getDB()->executeInsert(
                    "INSERT INTO nb_site_role_lang (nb_site_id, nb_role_id, nb_language_id, nb_site_role_lang_login_redirection_target_url,
                                                    nb_site_role_lang_policies_target_url)
                     SELECT nb_site_id, %target_id\$d, nb_language_id, nb_site_role_lang_login_redirection_target_url,
                            nb_site_role_lang_policies_target_url
                       FROM nb_site_role_lang
                      WHERE nb_site_id=%site_id\$d
                        AND nb_role_id=%source_id\$d",
                        array(
                        'site_id' => $this->getId(),
                        'source_id' => $nb_source_id,
                        'target_id' => $nb_target_id
                    ), true
                );
                $this->getDB()->executeDelete(
                    "DELETE FROM nb_site_target_cta_role
                      WHERE nb_role_id=%role_id\$d
                        AND nb_site_target_cta_id in (SELECT stc.nb_site_target_cta_id
                                                        FROM nb_site_target_cta stc, nb_site_target st
                                                       WHERE stc.nb_site_target_id=st.nb_site_target_id
                                                         AND st.nb_site_id=%site_id\$d)",
                    array(
                        'site_id' => $this->getId(),
                        'role_id' => $nb_target_id
                    ), true
                );
                $this->getDB()->executeInsert(
                    "INSERT INTO nb_site_target_cta_role (nb_site_target_cta_id, nb_role_id, nb_site_target_cta_role_zone)
                     SELECT str.nb_site_target_cta_id, %target_id\$d, str.nb_site_target_cta_role_zone
                       FROM nb_site_target_cta stc, nb_site_target_cta_role str, nb_site_target st
                      WHERE stc.nb_site_target_cta_id=str.nb_site_target_cta_id
                        AND stc.nb_site_target_id=st.nb_site_target_id
                        AND st.nb_site_id=%site_id\$d
                        AND str.nb_role_id=%source_id\$d",
                    array(
                        'site_id' => $this->getId(),
                        'source_id' => $nb_source_id,
                        'target_id' => $nb_target_id
                    ), true
                );
                $this->getDB()->executeDelete(
                    "DELETE FROM nb_site_map_role
                      WHERE nb_role_id=%role_id\$d
                        AND nb_site_map_id in (SELECT nb_site_map_id FROM nb_site_map WHERE nb_site_id=%site_id\$d)",
                    array(
                        'site_id' => $this->getId(),
                        'role_id' => $nb_target_id
                    ), true
                );
                $this->getDB()->executeInsert(
                    "INSERT INTO nb_site_map_role (nb_site_map_id, nb_role_id, nb_site_map_role_zone)
                     SELECT smr.nb_site_map_id, %target_id\$d, smr.nb_site_map_role_zone
                       FROM nb_site_map sm, nb_site_map_role smr
                      WHERE sm.nb_site_id=%site_id\$d
                        AND sm.nb_site_map_id=smr.nb_site_map_id
                        AND smr.nb_role_id=%source_id\$d",
                    array(
                        'site_id' => $this->getId(),
                        'source_id' => $nb_source_id,
                        'target_id' => $nb_target_id
                    ), true
                );
                error_log("FIN");
            }
        }
    }

    /**
     * Get the full list of available Users. If $nb_role is defined, then filters the list by the represented Role.
     * @param mixed $nb_role If defined, then will be a CNabuDataObject instance containing a field named nb_role_id
     * or a valid Id.
     * @param bool $force If true forces to reload the list from the database storage.
     * @return CNabuUserList Return a list with found users.
     */
    public function getAvailableUsers($nb_role = null, bool $force = false) : CNabuUserList
    {
        if ($this->nb_user_list->isEmpty() || $force) {
            $this->nb_user_list->clear();
            $this->nb_user_list->merge(CNabuSiteUser::getAvailableUsersForSite($this, $nb_role));
        }

        return $this->nb_user_list;
    }

    /**
     * Get the full list of active Users. If $nb_role is defined, then filters the list by the represented Role.
     * @param mixed $nb_role If defined, then will be a CNabuDataObject instance containing a field named nb_role_id
     * or a valid Id.
     * @param bool $force If true forces to reload the list from the database storage.
     * @return CNabuUserList Return a list with found users.
     */
    public function getActiveUsers($nb_role = null, bool $force = false) : CNabuUserList
    {
        if ($this->nb_user_list->isEmpty() || $force) {
            $this->nb_user_list->clear();
            $this->nb_user_list->merge(CNabuSiteUser::getActiveUsersForSite($this, $nb_role));
        }

        return $this->nb_user_list;
    }

    /*
      __  __                           _
     |  \/  | ___  ___ ___  __ _  __ _(_)_ __   __ _
     | |\/| |/ _ \/ __/ __|/ _` |/ _` | | '_ \ / _` |
     | |  | |  __/\__ \__ \ (_| | (_| | | | | | (_| |
     |_|  |_|\___||___/___/\__,_|\__, |_|_| |_|\__, |
                                 |___/         |___/
    */

    /**
     * Send a New User double opt-in message to validate the account or communitate something to him.
     * @param CNabuUser $nb_user User instance to be notified.
     * @param array|null $params Array of additional parameters to be used. Depending on the Render used, this parameter
     * can be applied or ignored.
     * @return bool Returns true if the notification is sent or false if not.
     */
    public function sendNewUserNotification(CNabuUser $nb_user, array $params = null) : bool
    {
        $retval = false;
        $nb_engine = CNabuEngine::getEngine();

        if (($nb_profile = $this->getUserProfile($nb_user)) instanceof CNabuSiteUser &&
            ($nb_language_id = $nb_profile->getLanguageId())
        ) {
            if (count($params) === 0) {
                $params = array();
            }
            foreach ($nb_user->getTreeData($nb_language_id, true) as $key => $item) {
                if (is_scalar($item)) {
                    $params["target_user_$key"] = $item;
                }
            }

            $nb_site_role = $this->getSiteRole($nb_profile);
            $nb_template_id = $nb_site_role instanceof CNabuSiteRole &&
                              $nb_site_role->isValueNumeric('nb_site_role_email_template_new_user')
                            ? $nb_template_id = $nb_site_role->getMessagingTemplateNewUser()
                            : $this->getMessagingTemplateNewUser()
            ;

            return is_numeric($nb_template_id) &&
                   ($nb_messaging = $this->getMessaging($this->getCustomer())) instanceof CNabuMessaging &&
                   ($nb_messaging_factory = $nb_messaging->getFactory()) instanceof CNabuMessagingFactory &&
                   $nb_messaging_factory->postTemplateMessage($nb_template_id, $nb_language_id, $nb_user, null, null, $params)
            ;
        } else {
            throw new ENabuSecurityException(ENabuSecurityException::ERROR_USER_NOT_ALLOWED, array($nb_user->getId()));
        }
    }

    /**
     * Send a Forgot Password message to change the password.
     * @param CNabuUser $nb_user User instance to be notified.
     * @param array|null $params Array of additional parameters to be used. Depending on the Render used, this parameter
     * can be applied or ignored.
     * @return bool Returns true if the notification is sent or false if not.
     */
    public function sendForgotPassword(CNabuUser $nb_user, array $params = null) : bool
    {
        $retval = false;
        $nb_engine = CNabuEngine::getEngine();

        if (($nb_profile = $this->getUserProfile($nb_user)) instanceof CNabuSiteUser &&
            ($nb_language_id = $nb_profile->getLanguageId())
        ) {
            if (count($params) === 0) {
                $params = array();
            }
            foreach ($nb_user->getTreeData($nb_language_id, true) as $key => $item) {
                if (is_scalar($item)) {
                    $params["target_user_$key"] = $item;
                }
            }

            $nb_site_role = $this->getSiteRole($nb_profile);
            $nb_template_id = $nb_site_role instanceof CNabuSiteRole &&
                              $nb_site_role->isValueNumeric('nb_site_role_email_template_forgot_password')
                            ? $nb_template_id = $nb_site_role->getMessagingTemplateForgotPassword()
                            : $this->getMessagingTemplateForgotPassword()
            ;

            return is_numeric($nb_template_id) &&
                   ($nb_messaging = $this->getMessaging($this->getCustomer())) instanceof CNabuMessaging &&
                   ($nb_messaging_factory = $nb_messaging->getFactory()) instanceof CNabuMessagingFactory &&
                   $nb_messaging_factory->postTemplateMessage($nb_template_id, $nb_language_id, $nb_user, null, null, $params)
            ;
        } else {
            throw new ENabuSecurityException(ENabuSecurityException::ERROR_USER_NOT_ALLOWED, array($nb_user->getId()));
        }
    }
}
