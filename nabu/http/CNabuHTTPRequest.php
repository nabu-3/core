<?php

/*  Copyright 2009-2011 Rafael Gutierrez Martinez
 *  Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 *  Copyright 2014-2016 Where Ideas Simply Come True, S.L.
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

namespace nabu\http;

use nabu\core\CNabuObject;
use nabu\core\CNabuEngine;
use nabu\core\exceptions\ENabuCoreException;
use nabu\data\CNabuDataObject;
use nabu\data\commerce\CNabuCommerce;
use nabu\data\lang\CNabuLanguage;
use nabu\data\security\CNabuRole;
use nabu\data\site\CNabuSite;
use nabu\data\site\CNabuSiteAlias;
use nabu\data\site\CNabuSiteTarget;
use nabu\http\CNabuHTTPResponse;
use nabu\http\app\base\CNabuHTTPApplication;
use nabu\http\exceptions\ENabuRedirectionException;
use nabu\http\managers\CNabuModulesManager;
use nabu\http\managers\CNabuHTTPPluginsManager;
use nabu\http\managers\CNabuHTTPSecurityManager;

/**
 * This class represents an URI request.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\http
 */
class CNabuHTTPRequest extends CNabuObject
{
    const METHOD_GET = 'GET';
    const METHOD_PUT = 'PUT';
    const METHOD_POST = 'POST';
    const METHOD_DELETE = 'DELETE';
    const METHOD_HEAD = 'HEAD';
    const METHOD_OPTIONS = 'OPTIONS';

    /**
     * Page path found in the URI
     * @var string
     */
    private $page_uri;
    /**
     * nabu-3 HTTP Application which owns this Request instance
     * @var CNabuHTTPApplication
     */
    private $nb_application = null;
    /**
     * Security Manager instance
     * @var CNabuHTTPSecurityManager
     */
    private $nb_security_manager = null;
    /**
     * Plugins Manager instance
     * @var CNabuHTTPPluginsManager
     */
    private $nb_plugins_manager = null;
    /**
     * Modules Manager instance
     * @var CNabuModulesManager
     */
    private $nb_modules_manager = null;
    /**
     * Contains the response instance of request
     * @var CNabuHTTPResponse
     */
    private $nb_response = null;
    /**
     * Contains the site instance for requested URI
     * @var CNabuSite
     */
    private $nb_site;
    /**
     * Contains the site alias instance for requested URI
     * @var CNabuSiteAlias
     */
    private $nb_site_alias;
    /**
     * If the site alias is disabled, then this variable contains true and $nb_site_alias contains the default for the site
     * @var bool
     */
    private $nb_site_alias_force_default;
    /**
     * Contains the role instance for the owner of alias on requested URI
     * @var CNabuRole
     */
    private $nb_site_alias_role;
    /**
     * Contains the Site Target instance for requested URI
     * @var CNabuSiteTarget
     */
    private $nb_site_target;
    /**
     * Contains the Commerce instance of requested URI if needed
     * @var CNabuCommerce
     */
    private $nb_commerce;
    /**
     * Contains the language instance for requested URI
     * @var CNabuLanguage
     */
    private $nb_language;
    /**
     * Contains the user remote IP for requested URI
     * @var string
     */
    private $user_remote_ip;
    /**
     * Contains the UserAgent string for requested URI
     * @var string
     */
    private $user_agent;
    /**
     * Contains an array with the accepted mimetypes by the remote host
     * @var array|null Valid mime types accepted by remote client for requested URI
     */
    private $accept_mimetypes;
    /**
     * Contains an array with the accepted languages by the remote host
     * @var array|null Valid language codes accepted by remote client for requested URI
     */
    private $accept_languages;
    /**
     * Contains the mimetype of the body sent.
     * @var string
     */
    private $request_content_type;
    /**
     * Contains the length of the body sent.
     * @var int
     */
    private $request_content_length;
    /**
     * Contains a string representing valid method used to make this request
     * @var string Valid method used for call
     */
    private $method;
    /**
     * Contains the active zone in the request
     * @var string|null Zone ('O' = Opened/Public, 'P' = Private)
     */
    private $zone;
    /**
     * Associative array to store internally the request variables
     * @var array
     */
    private $variables;
    /**
     * URL instance of referer for requested URI
     * @var \nabu\core\utils\CNabuURL Referer instance for request
     */
    private $referer;
    /**
     * List of all fragments extracted from a URL when $nb_site_target is of type RegExp.
     * @var array List of URL fragments extracted
     */
    private $regex_url_fragments;
    /**
     * For compatibility with IE8 and IE9 XDomainRequest (XDR) connections, stores parsed POST body
     * @var array
     */
    private $xdr_post = null;

    /**
     * Default constructor.
     * @param CNabuHTTPApplication $nb_application Application that owns this instance
     * @param CNabuHTTPSecurityManager $nb_security_manager nabu-3 HTTP Security Manager used
     * @param CNabuHTTPPluginsManager $nb_plugins_manager nabu-3 HTTP Plugins Manager used
     * @param CNabuModulesManager $nb_modules_manager nabu-3 HTTP Modules Manager used
     * @throws ENabuCoreException Throws an exception if application or managers are not valid.
     */
    public function __construct(
        CNabuHTTPApplication $nb_application,
        CNabuHTTPSecurityManager $nb_security_manager,
        CNabuHTTPPluginsManager $nb_plugins_manager,
        CNabuModulesManager $nb_modules_manager
    ) {
        parent::__construct();

        $this->nb_application = $nb_application;
        $this->nb_security_manager = $nb_security_manager;
        $this->nb_plugins_manager = $nb_plugins_manager;
        $this->nb_modules_manager = $nb_modules_manager;

        $this->init();
    }

    /**
     * Initializes the instance.
     * Reset all internal variables to init values.
     */
    private function init()
    {
        $this->nb_response = new CNabuHTTPResponse($this->nb_plugins_manager, $this);

        $this->nb_site = null;
        $this->nb_site_alias = null;
        $this->nb_site_target = null;
        $this->nb_language = null;

        $this->user_remote_ip = null;
        $this->user_agent = null;
        $this->accept_mimetypes = null;
        $this->accept_languages = null;
        $this->method = null;

        $this->secure = false;
        $this->zone = null;

        $this->page_uri = null;

        $this->variables = array();

        // This block is intended for XDR IE8 & IE9 compatibility
        /*
        if (count($_POST) === 0) {
            if (isset($HTTP_RAW_POST_DATA)) {
                parse_str($HTTP_RAW_POST_DATA, $this->xdr_post);
            } else {
                parse_str(file_get_contents('php://input'), $this->xdr_post);
            }
        }
        */
    }

    /**
     * Gets the HTTP Response object.
     * @return CNabuHTTPResponse Returns an instance to nabu-3 response object.
     */
    public function getResponse()
    {
        return $this->nb_response;
    }

    /**
     * Gets the HTTP Requested method.
     * @return string Returns the HTTP Method name.
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Gets the HTTP Request path.
     * @return string Page URI requested without query string.
     */
    public function getPageURI()
    {
        return $this->page_uri;
    }

    /**
     * Gets the active language for this request.
     * @return CNabuLanguage Returns the active language
     */
    public function getLanguage()
    {
        return $this->nb_language;
    }

    /**
     * Get the current Site instance
     * @return CNabuSite Returns the instance of current Site or null if no site is selected.
     */
    public function getSite()
    {
        return $this->nb_site;
    }

    /**
     * Get site alias instance of this request
     * @return CNabuSiteAlias The requested site alias instance or null if not exists
     */
    public function getSiteAlias()
    {
        return $this->nb_site_alias;
    }

    /**
     * Gets the role of current owner of Site Alias.
     * This method is used when the Site have Alias which are owned by different users.
     * @return CNabuRole The role of the owner for the requested site alias instance or null if not exists
     */
    public function getSiteAliasRole()
    {
        return $this->nb_site_alias_role;
    }

    /**
     * Get the current Site Target intance
     * @return CNabuSiteTarget Returns the instance of current Site Target or null elsewhere.
     */
    public function getSiteTarget()
    {
        return $this->nb_site_target;
    }

    /**
     * Get the current Commerce instance if any
     * @return CNabuCommerce Returns the intance of current Commerce or null elsewhere.
     */
    public function getCommerce()
    {
        return $this->nb_commerce;
    }

    public function locateSite()
    {
        $nb_engine = CNabuEngine::getEngine();
        $nb_http_server = $nb_engine->getHTTPServer();
        $nb_server = $nb_http_server->getServer();
        $this->nb_site_alias_force_default = false;
        $this->nb_site = $nb_http_server->getSite();
        $this->nb_plugins_manager->prepareForSite($this, $this->nb_response);
    }

    public function locateSiteAlias()
    {
        $nb_engine = CNabuEngine::getEngine();
        $nb_http_server = $nb_engine->getHTTPServer();
        $nb_server_valid = $nb_http_server->isServerValid();
        $nb_server = $nb_http_server->getServer();

        if (!$nb_server_valid || $this->nb_site->isBuiltIn()) {
            $this->nb_site_alias = $this->nb_site->getMainAlias();
            $this->nb_site_alias_force_default = !$this->nb_site->isBuiltIn();
        } else {
            $this->nb_site_alias = $nb_http_server->getSiteAlias();
            if ($this->nb_site_alias->isValueEqualThan('nb_site_alias_status', 'D')) {
                $this->nb_site_alias = $this->nb_site->getMainAlias();
                $this->nb_site_alias_force_default = true;
            }
        }

        $this->nb_site_alias_role = $this->nb_site_alias->getOwnerRole();
        if ($this->nb_site_alias_role === null) {
            if ($this->nb_security_manager->isUserLogged()) {
                $this->nb_site_alias_role = $this->nb_security_manager->getRole();
            }
        }
        if ($this->nb_site_alias_role !== null) {
            $nb_engine->traceLog("Alias Role", $this->nb_site_alias_role->getKey());
        }
    }

    public function locateSiteTarget()
    {
        $nb_engine = CNabuEngine::getEngine();

        if ($nb_engine->getHTTPServer()->isServerValid()) {
            if (!$this->nb_site_alias_force_default) {
                $nb_engine->traceLog('Site Status', 'Enabled');
                if ($this->identifyRequest() && $this->checkSecurity()) {
                    if ($this->method === 'OPTIONS') {
                        return true;
                    } elseif ($this->nb_security_manager->validateZone($this->nb_site_target)) {
                        return true;
                    } else {
                        return $this->rezone();
                    }
                }
            } else {
                $nb_engine->traceLog('Site Status', 'Disabled');
                switch ($this->nb_site->getAliasLockedUseURI()) {
                    case 'F':
                        if ($this->nb_site->isValueString('nb_site_alias_locked_url')) {
                            new ENabuRedirectionException(307, $this->nb_site->getValue('nb_site_alias_locked_url'));
                        }
                        break;
                    case 'T':
                        if ($this->nb_site->isValueNumeric('nb_site_alias_locked_site_target_id') &&
                            $this->identifyRequest($this->nb_site->getValue('nb_site_alias_locked_site_target_id')) &&
                            $this->checkSecurity()
                        ) {
                            if ($this->method === 'OPTIONS') {
                                return true;
                            } elseif ($this->nb_security_manager->validateZone($this->nb_site_target)) {
                                return true;
                            } else {
                                return $this->rezone();
                            }
                        }
                        break;
                }
            }
        } else {
            $nb_engine->traceLog('Site Status', 'Not found');
            switch ($this->nb_site->getAliasNotFoundUseURI()) {
                case 'F':
                    if ($this->nb_site->isValueString('nb_site_alias_not_found_url')) {
                        new ENabuRedirectionException(307, $this->nb_site->getAliasNotFoundURL());
                    }
                    break;
                case 'T':
                    if ($this->nb_site->isValueNumeric('nb_site_alias_not_found_site_target_id') &&
                        $this->identifyRequest($this->nb_site->getAliasNotFoundSiteTargetId()) &&
                        $this->checkSecurity()) {
                        if ($this->method === 'OPTIONS') {
                            return true;
                        } elseif ($this->nb_security_manager->validateZone($this->nb_site_target)) {
                            return true;
                        } else {
                            return $this->rezone();
                        }
                    }
                    break;
            }
        }

        return false;
    }

    public function locateCommerce()
    {
        if ($this->nb_site !== null && $this->nb_site_target !== null && $this->nb_site_target->isCommerceEnabled()) {
            $this->nb_commerce = $this->nb_site->getCommerce();
        }

        return true;
    }

    public function locateModules() {

        /** @todo This method is commented temporally and pending to revise in a near future */
        /*
        if (($this->cms_article instanceof \cms\sites\CCMSArticle) && $this->cms_article->isValueEqualThan('cms_article_use_apps', 'T')) {

            $slots = null;

            if ($this->cms_site instanceof \cms\sites\CCMSSite) {
                $slots_site = $this->cms_site->getValueAsList('cms_site_apps_slot');
            } else {
                $slots_site = null;
            }

            $slots_art = $this->cms_article->getValueAsList('cms_article_apps_slot');

            if (count($slots_site) > 0) {
                if (count($slots_art) > 0) {
                    $slots = array_merge($slots_site, $slots_art);
                } else {
                    $slots = $slots_site;
                }
            } elseif (count($slots_art) > 0) {
                $slots = $slots_art;
            }

            if (count($slots) > 0) {
                $slots = array_unique($slots);
                $this->cms_apps_manager->prepareAppsForSlots($slots);
            }
        }

        $this->cms_plugins_manager->invoquePrepareAppMorphs();
        $this->cms_apps_manager->locatePlugins();
        $this->cms_apps_manager->enumeratei18n($this->cms_language);
        $this->cms_apps_manager->enumerateScripts();
        $this->cms_apps_manager->enumerateStyles();
        $this->cms_plugins_manager->invoqueTrap($this, $this->cms_response);
        $this->cms_apps_manager->enableChilds();
         */
    }

    public function prepareHeaders()
    {
        $nb_engine = CNabuEngine::getEngine();
        $nb_http_server = $nb_engine->getHTTPServer();


        $this->user_remote_ip = $nb_http_server->locateRemoteAddress();
        if ($this->user_remote_ip) {
            $nb_engine->traceLog("Remote IP", $this->user_remote_ip);
        }

        $this->accept_mimetypes = $nb_http_server->getAcceptedMimetypes();
        if (count($this->accept_mimetypes) > 0) {
            $nb_engine->traceLog("Accept", $this->accept_mimetypes);
        }

        $this->accept_languages = $nb_http_server->getAcceptedLanguages();
        if (count($this->accept_languages) > 0) {
            $nb_engine->traceLog("Accept-Language", $this->accept_languages);
        }

        $this->request_content_type = $nb_http_server->getContentType();
        $this->request_content_length = $nb_http_server->getContentLength();

        $this->method = $nb_http_server->getRequestMethod();
        if (strlen($this->method) > 0) {
            $nb_engine->traceLog("Request Method", $this->method);
        }

        $this->referer = $nb_http_server->getReferer();
        if ($this->referer !== null) {
            $nb_engine->traceLog("Referer", $this->referer->getURL());
        }

        if (count($_GET) > 0) {
            $nb_engine->traceLog("GET params", $_GET);
        }
        if (count($_POST) > 0) {
            $nb_engine->traceLog("POST params", $_POST);
        }
        if (count($_FILES) > 0) {
            $nb_engine->traceLog("FILES params", $_FILES);
        }
    }

    public function prepareBody()
    {
        $nb_engine = CNabuEngine::getEngine();
        if ($this->request_content_length > 0) {
            $raw = file_get_contents('php://input');
            if ($this->request_content_type === 'application/json') {
                $this->xdr_post = json_decode($raw, true);
            } else {
                parse_str($raw, $this->xdr_post);
            }
            if (count($this->xdr_post) > 0) {
                $nb_engine->traceLog("XDR params", $this->xdr_post);
            }
        }
    }

    /**
     * If request have a body of any type, then this method returns the content. If contentType is application/json
     * then the body is parsed as JSON and returned as a JSON array, else parsed as str and returned as an associative
     * array of fields.
     * @return mixed Retunrs the XDR Body.
     */
    public function getBody()
    {
        return $this->xdr_post;
    }

    /**
     * Check the request to identify and extract the query and page identifiers
     * @param mixed $target Target reference
     * @return int Returns true if query and page identifiers found or false elsewhere
     */
    private function identifyRequest($target = false)
    {
        $retval = false;

        $nb_engine = CNabuEngine::getEngine();

        $this->page_uri = filter_input(INPUT_GET, NABU_PATH_PARAM, FILTER_SANITIZE_STRING, FILTER_FLAG_EMPTY_STRING_NULL);
        if (!is_string($this->page_uri) || strlen($this->page_uri) === 0) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_PAGE_URI_NOT_FOUND);
        }
        $nb_engine->traceLog("Page requested", $this->page_uri);

        if ($target) {
            $temp_target = CNabuSiteTarget::findByURL($this->nb_site, $this->page_uri);
            if ($temp_target !== null && $temp_target->isValueNumeric('nb_language_id')) {
                $nb_language = new CNabuLanguage($temp_target);
                if ($nb_language->isFetched()) {
                    $this->nb_language = $nb_language;
                } else {
                    $this->nb_language = $this->nb_site->getDefaultLanguage();
                }
            }

            if (!($target instanceof CNabuSiteTarget) &&
                is_numeric($nb_site_target_id = nb_getMixedValue($target, 'nb_site_target_id'))
            ) {
                $target = new CNabuSiteTarget($nb_site_target_id);
                if ($target->isFetched()) {
                    $this->nb_site_target = $target;
                }
            } else {
                $this->nb_site_target = $target;
            }
            if ($this->nb_site_target instanceof CNabuSiteTarget &&
                $this->nb_language === null && $this->nb_site_target->isValueNumeric('nb_language_id')) {
                $this->nb_language = new CNabuLanguage($this->nb_site_target->getValue('nb_language_id'));
                if ($this->nb_language->isNew()) {
                    $this->nb_language = null;
                }
            }
            if ($this->nb_language === null) {
                $this->nb_language = $this->nb_site->getDefaultLanguage();
            }
            if ($this->nb_site_target !== null &&
                $this->nb_language !== null &&
                $this->nb_site_target->hasTranslation($this->nb_language)
            ) {
                $this->nb_response->setHTTPResponseCode(200);
                $nb_engine->traceLog('Page Status', 'Found');

                $retval = true;
            }
        } elseif ($this->findTargetByURL($this->page_uri)) {

            $nb_engine->traceLog("Page ID", $this->nb_site_target->getId());

            if ($this->nb_language === null) {
                throw new ENabuCoreException(ENabuCoreException::ERROR_LANGUAGE_NOT_FOUND_FOR_PAGE_URI);
            }
            $nb_engine->traceLog("Page language", $this->nb_language->getISO6391());
            $nb_engine->traceLog('Page Status', 'Found');

            $retval = true;
        }

        $nb_engine->traceLog('Page Status', 'Not found');
        if (!$retval && $this->nb_response->getHTTPResponseCode() === 0) {
            $this->nb_response->setHTTPResponseCode(404);
        }

        return $retval;
    }

    public function checkSecurity()
    {
        if (!$this->nb_security_manager->validateVisibility($this->nb_site, $this->nb_site_target)) {
            $this->nb_response->movedPermanentlyRedirect($this->nb_site_target, $this->nb_language);
        }

        return true;
    }

    private function findTargetByURL($url)
    {
        $retval = false;

        $nb_engine = CNabuEngine::getEngine();
        $this->nb_language = null;
        $this->nb_site_target = $this->nb_site->findTargetByURL($url);

        if ($this->nb_site_target === null && $url === '/') {
            $nb_engine->traceLog(null, "Root requested via Site Plugin");
            if ($this->nb_plugins_manager->invoqueRedirectRoot()) {
                $this->rezoneRootByLanguage();
                return false;
            }
        } elseif ($this->nb_site_target === null) {
            $this->nb_response->setHTTPResponseCode((int)$this->nb_site->getPageNotFoundErrorCode());
            switch($this->nb_site->getValue('nb_site_page_not_found_use_site_target')) {
                case 'T': // Uses site_target
                {
                    $aux_target = new CNabuSiteTarget($this->nb_site->getValue('nb_site_page_not_found_site_target_id'));
                    if ($aux_target->isFetched()) {
                        $aux_target->setValue('nb_language_id', $this->nb_site->getValue('nb_site_default_language_id'));
                        $this->nb_site_target = $aux_target;
                    } else {
                        $this->nb_response->setHTTPResponseCode(404);
                    }
                    break;
                }
                case 'F': // Uses URL
                {
                    $target = $this->nb_site->getValue('nb_site_page_not_found_url');
                    $this->nb_response->setHTTPResponseCode(404);
                    if (strlen($target) > 0) {
                        /** @todo Este método no existe. ¿Realmente se pasa por aquí? */
                        $this->setLocation($target);
                    }
                    break;
                }
                default:
                    $this->nb_response->setHTTPResponseCode(404);
            }
        } else {
            $this->nb_response->setHTTPResponseCode(200);
        }

        if ($this->nb_site_target !== null &&
            ($this->nb_site_target->isValueNumeric('nb_language_id') ||
             $this->nb_site_target->isValueGUID('nb_language_id')
            )
        ) {
            $this->nb_language = $this->nb_site->getLanguage($this->nb_site_target);
        }

        return ($this->nb_site_target !== null);
    }

    public function rezone()
    {
        $nb_engine = CNabuEngine::getEngine();
        $nb_engine->traceLog("Zone", "rezone");

        $login_target = $this->nb_site->getLoginTargetLink();
        $page_not_found_target = $this->nb_site->getPageNotFoundTargetLink();

        if ($login_target->isLinkable()) {
            if ($login_target->matchTarget($this->nb_site_target)) {
                $this->nb_site_target = null;
                $this->nb_response->setHTTPResponseCode(404);
            } else {
                $this->nb_response->temporaryRedirect($login_target->getBestQualifiedURL($this->nb_language));
            }
        } elseif ($page_not_found_target->isLinkable()) {
            if ($page_not_found_target->isTranslatedTarget()) {
                $this->nb_site_target = $page_not_found_target->getTranslatedObject();
                $this->nb_response->setHTTPResponseCode(404);
            } else {
                $this->nb_site_target = null;
                $this->nb_response->temporaryRedirect(
                    $page_not_found_target->getBestQualifiedURL($this->nb_language)
                );
            }
        } else {
            $this->nb_site_target = null;
            $this->nb_response->setHTTPResponseCode(404);
        }

        return false;
    }

    private function rezoneRootByLanguage()
    {
        $nb_engine = CNabuEngine::getEngine();
        $default_target = $this->nb_site->getDefaultTargetLink();

        if ($default_target->isLinkable() &&
            ($url = $default_target->getBestQualifiedURL($this->accept_languages)) !== false
        ) {
            $this->nb_site_target = null;
            $this->nb_response->temporaryRedirect($url);
        } else {
            $nb_engine->errorLog("Unable to rezone by Root Language");
            $this->nb_response->setHTTPResponseCode(404);
        }
    }

    public function filterURI($uri)
    {
        /** @todo RGM: Controlar si $nb_site_target está a null y por qué llegamos hasta aquí teniendo un valor null cuando debería salir por otro camino */
        if ($this->nb_site_target !== null &&
            $this->nb_site_target->contains('nb_site_target_lang_url') &&
            $this->nb_site_target->contains('nb_site_target_url_filter') &&
            $this->nb_site_target->getValue('nb_site_target_url_filter') === 'R')
        {
            $parts = array();
            $num = preg_match('/'.$this->nb_site_target->getValue('nb_site_target_lang_url').'/', $uri, $parts);
            if ($num > 0 && count($parts) > 0) {
                $this->regex_url_fragments = $parts;
            }
        }
    }

    public function getRegExprURLFragments()
    {
        return $this->regex_url_fragments;
    }

    public function hasGETField($field_name)
    {
        return array_key_exists($field_name, $_GET);
    }

    public function hasPOSTField($field_name)
    {
        return array_key_exists($field_name, $_POST) ||
               (is_array($this->xdr_post) && array_key_exists($field_name, $this->xdr_post));
    }

    public function getPOSTFieldNames()
    {
        return is_array($this->xdr_post)
               ? array_keys($this->xdr_post)
               : (count($_POST) > 0 ? array_keys($_POST) : null)
        ;
    }

    public function hasREQUESTField($field_name)
    {
        return array_key_exists($field_name, $_REQUEST) ||
               (is_array($this->xdr_post) && array_key_exists($field_name, $this->xdr_post));
    }

    public function getGETField($field_name)
    {
        return filter_input(INPUT_GET, $field_name);
    }

    public function getPOSTField($field_name)
    {
        return is_array($this->xdr_post)
               ? (array_key_exists($field_name, $this->xdr_post) ? $this->xdr_post[$field_name] : null)
               : filter_input(INPUT_POST, $field_name)
        ;
    }

    public function getREQUESTField($field_name)
    {
        return is_array($this->xdr_post)
               ? (array_key_exists($field_name, $this->xdr_post) ? $this->xdr_post[$field_name] : null)
               : filter_input(INPUT_REQUEST, $field_name)
        ;
    }

    public function setGETField($field_name, $field_value)
    {
        $_GET[$field_value] = $field_value;

        return $this;
    }

    public function setPOSTField($field_name, $field_value)
    {
        if ($this->xdr_post !== null) {
            $this->xdr_post[$field_name] = $field_value;
        } else {
            $this->xdr_post = array($field_name => $field_value);
        }
        if ($_POST !== null) {
            $_POST[$field_name] = $field_value;
        } else {
            $_POST = array($field_name => $field_value);
        }

        return $this;
    }

    public function setREQUESTField($field_name, $field_value)
    {
        $_REQUEST[$field_name] = $field_value;

        return $this;
    }

    /**
     * Get all index key values of parameters containing arrays.
     * @param array $fields List of fields to be observed. If empty (null) then scan all fields.
     * @return array Returns an array containing all available fields without duplicates or null if none index is found.
     */
    public function getCombinedPostIndexes(array $fields = null)
    {
        if ($fields === null) {
            $fields = $this->getPOSTFieldNames();
        }

        $keys = array();

        if (count($fields) > 0) {
            if (is_array($this->xdr_post)) {
                foreach ($fields as $field) {
                    if (array_key_exists($field, $this->xdr_post) && is_array($this->xdr_post[$field])) {
                        $keys = array_merge($keys, array_keys($this->xdr_post[$field]));
                    }
                }
            } elseif (is_array($_POST)) {
                foreach ($fields as $field) {
                    if (array_key_exists($field, $_POST) && is_array($_POST[$field])) {
                        $keys = array_merge($keys, array_keys($_POST[$field]));
                    }
                }
            }
            $keys = array_unique($keys);
        }

        return count($keys) > 0 ? $keys : null;
    }

    public function updateObjectFromPost(
        CNabuDataObject $object,
        array $fields,
        array $def_values = null,
        array $null_values = null,
        string $index = null
    ) {
        $total = 0;

        foreach($fields as $key=>$value) {
            if ($this->hasPOSTField($key)) {
                $final = $this->getPOSTField($key);
                if ($index !== null && is_array($final)) {
                    if (array_key_exists($index, $final)) {
                        $final = $final[$index];
                    } else {
                        unset($final);
                    }
                }
            } else if ($def_values && count($def_values) > 0 && array_key_exists($key, $def_values)) {
                $final = $def_values[$key];
            }
            if (isset($final)) {
                if ($null_values && count($null_values) > 0 && array_key_exists($key, $null_values) && $final == $null_values[$key]) {
                    $final = null;
                }
                $object->setValue($value, $final);
                $total++;
                unset($final);
            }
        }

        return $total;
    }
}
