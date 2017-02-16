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

namespace nabu\http\app\base;

use nabu\core\base\CNabuAbstractApplication;
use nabu\core\exceptions\ENabuCoreException;
use nabu\data\CNabuDataObject;
use nabu\data\medioteca\CNabuMedioteca;
use nabu\data\site\CNabuSiteTarget;
use nabu\http\CNabuHTTPSession;
use nabu\http\CNabuHTTPRequest;
use nabu\http\CNabuHTTPResponse;
use nabu\http\exceptions\ENabuRedirectionException;
use nabu\http\interfaces\INabuHTTPServer;
use nabu\http\interfaces\INabuHTTPResponseRender;
use nabu\http\managers\CNabuModulesManager;
use nabu\http\managers\CNabuHTTPManagerList;
use nabu\http\managers\CNabuMediotecasManager;
use nabu\http\managers\CNabuHTTPRendersManager;
use nabu\http\managers\CNabuHTTPPluginsManager;
use nabu\http\managers\CNabuHTTPSecurityManager;
use nabu\http\managers\CNabuHTTPRenderDescriptor;
use nabu\http\managers\base\CNabuHTTPManager;

/**
 * Abstract base class to implement Web based applications.
 * This class have different extended classes to create Standalone, Stored
 * or Clustered applications.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\http\base
 */
abstract class CNabuHTTPApplication extends CNabuAbstractApplication
{
    /**
     * nabu-3 running HTTP Server instance
     * @var INabuHTTPServer
     */
    private $nb_http_server = null;
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
     * Renders Manager instance
     * @var CNabuHTTPRendersManager
     */
    private $nb_http_renders_manager = null;
    /**
     * Mediotecas Manager instance
     * @var CNabuMediotecasManager
     */
    private $nb_mediotecas_manager = null;
    /**
     * Contains the session instance
     * @var CNabuHTTPSession
     */
    private $nb_session = null;
    /**
     * Request instance
     * @var CNabuHTTPRequest
     */
    private $nb_request = null;
    /**
     * Response instance
     * @var CNabuHTTPResponse
     */
    private $nb_response = null;

    /**
     * Initializes the instance and register them in the Engine.
     */
    protected function init()
    {
        $this->prepareHTTPManagers();
        $this->prepareHTTPRendersManager();
        $this->prepareMediotecasManager();

        $this->nb_engine->registerApplication($this);
    }

    /**
     * Gets a pre-registered Manager instance, normally of a provider package.
     * @param string $key Key identifier of the manager.
     * @return CNabuHTTPManager Returns the requested manager identified by $key if exists or null if not.
     */
    public function getManager($key)
    {
        return $this->nb_http_manager_list->getItem($key);
    }

    /**
     * Register a HTTP Render to be used by this application when the response is built.
     * @param CNabuHTTPRenderDescriptor $descriptor Descriptor containing information about the Render to be joined.
     * @throws ENabuCoreException Raises an exception if the $descriptor contains not valid values.
     */
    public function registerRender(CNabuHTTPRenderDescriptor $descriptor)
    {
        $this->nb_http_renders_manager->registerRender($descriptor);
    }

    /**
     * Executes the Application runtime.
     * @return bool Return true if success
     */
    final public function run()
    {
        $this->nb_http_server = $this->nb_engine->getHTTPServer();
        if (!($this->nb_http_server instanceof INabuHTTPServer)) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_HTTP_SERVER_NOT_FOUND);
        }

        $this->prepareSecurityManager();
        $this->preparePluginsManager();
        $this->prepareModulesManager();

        $this->nb_session = CNabuHTTPSession::getSession();
        $this->nb_engine->traceLog("Cookies", $_COOKIE);

        // If the request is not allowed then treat it as a redirection or an error
        try {
            if ($this->prepareRequest()) {
                $method = $this->nb_request->getMethod();
                $this->prepareCookies();
                $this->prepareLocale();

                if ($this->validateCORSOrigin()) {
                    if ($method !== 'OPTIONS') {
                        $this->prepareModules();
                    }

                    if ($this->prepareResponse() &&
                        $this->processMethods() &&
                        $this->processCommands())
                    {
                        $this->prepareHeaders();
                        if ($method !== 'OPTIONS' && $method !== 'HEAD') {
                            $this->buildResponse();
                        }
                    }
                }
            } else {
                nb_displayErrorPage($this->nb_response->getHTTPResponseCode());
            }
        } catch (ENabuRedirectionException $re) {
            $this->nb_engine->traceLog("Redirection", "Via exception");
            $this->prepareCookies();
            $this->buildRedirection($re->getHTTPResponseCode(), $re->getLocation());
        }

        return true;
    }

    public function getSession()
    {
        return $this->nb_session;
    }

    public function getRequest()
    {
        return $this->nb_request;
    }

    public function getResponse()
    {
        return $this->nb_response;
    }

    /**
     * Gets the Nabu HTTP Server active instance.
     * @return INabuHTTPServer Returns the Nabu HTTP Server instance or null if not setted.
     */
    public function getHTTPServer()
    {
        return $this->nb_http_server;
    }

    public function getRole()
    {
        return $this->nb_security_manager !== null ? $this->nb_security_manager->getRole() : null;
    }

    public function getUser()
    {
        return $this->nb_security_manager !== null ? $this->nb_security_manager->getUser() : null;
    }

    /**
     * Gets current Plugins Manager instance of this application
     * @return CNabuHTTPPluginsManager Returns the active instance
     */
    public function getPluginsManager()
    {
        return $this->nb_plugins_manager;
    }

    /**
     * Gets current Security Manager instance of this application
     * @return CNabuHTTPSecurityManager Returns the active instance
     */
    public function getSecurityManager()
    {
        return $this->nb_security_manager;
    }

    public function getModulesManager()
    {
        return $this->nb_modules_manager;
    }

    public function getMediotecasManager()
    {
        return $this->nb_mediotecas_manager;
    }

    private function prepareSecurityManager()
    {
        $this->nb_security_manager = new CNabuHTTPSecurityManager($this);
    }

    private function preparePluginsManager()
    {
        $this->nb_plugins_manager = new CNabuHTTPPluginsManager($this);
    }

    private function prepareModulesManager()
    {
        $this->nb_modules_manager = new CNabuModulesManager($this, $this->nb_plugins_manager);
    }

    private function prepareHTTPManagers()
    {
        $this->nb_http_manager_list = new CNabuHTTPManagerList();
    }

    private function prepareHTTPRendersManager()
    {
        $this->nb_http_renders_manager = new CNabuHTTPRendersManager($this);
        $this->nb_http_renders_manager->registerRender(
            (new CNabuHTTPRenderDescriptor())
                ->setKey('JSON')
                ->setClassName('nabu\http\renders\CNabuHTTPResponseJSONRender')
        );
        $this->nb_http_renders_manager->registerRender(
            (new CNabuHTTPRenderDescriptor())
                ->setKey('IMG')
                ->setClassName('nabu\http\renders\CNabuHTTPResponseImageRender')
        );
        $this->nb_http_renders_manager->registerRender(
            (new CNabuHTTPRenderDescriptor())
                ->setKey('FILE')
                ->setClassName('nabu\http\renders\CNabuHTTPResponseFileRender')
        );
    }

    protected function prepareMediotecasManager()
    {
        $this->nb_mediotecas_manager = new CNabuMediotecasManager($this);
    }

    private function prepareCookies()
    {
        $this->nb_session->refreshCookies($this->nb_request->getSite());
    }

    public function prepareRequest()
    {
        $this->nb_request = new CNabuHTTPRequest(
            $this,
            $this->nb_security_manager,
            $this->nb_plugins_manager,
            $this->nb_modules_manager
        );
        $this->nb_response = $this->nb_request->getResponse();
        $this->nb_security_manager->initSecurity();
        $this->nb_request->prepareHeaders();
        $this->nb_request->prepareBody();
        $this->nb_request->locateSite();
        $this->nb_request->locateSiteAlias();

        $retval = false;

        if ($this->nb_request->locateSiteTarget()) {
            $this->nb_request->filterURI($this->nb_request->getPageURI());
            $this->nb_request->locateCommerce();
            $this->nb_plugins_manager->prepareForSiteTarget($this->nb_request, $this->nb_response);
            $retval = true;
        }

        return $retval;
    }

    private function prepareLocale() {

        $nb_language = $this->nb_request->getLanguage();
        if ($nb_language !== null) {
            $this->nb_engine->traceLog("Locale", $nb_language->getDefaultCountryCode());
            setlocale(
                LC_TIME | LC_COLLATE | LC_MONETARY | LC_NUMERIC | LC_CTYPE,
                $nb_language->getDefaultCountryCode()
            );
        }
    }

    public function prepareModules() {

        $this->nb_request->locateModules();

        return true;
    }

    private function prepareResponse()
    {
        if (($nb_commerce = $this->nb_request->getSite()->getCommerce()) !== null) {
            $nb_commerce->sortAll();
        }

        $nb_site_target = $this->nb_request->getSiteTarget();
        if ($nb_site_target instanceof CNabuDataObject &&
            !$nb_site_target->isEmptyValue('nb_mimetype_id') &&
            $this->nb_response->getMimetype() === null) {
            $this->nb_response->setMimetype($this->nb_request->getSiteTarget()->getMimetypeId());
        }

        if ($this->nb_request->getMethod() !== 'OPTIONS') {
            if ($nb_site_target instanceof CNabuSiteTarget) {
                $this->nb_http_renders_manager->setResponseRender(
                    $this->nb_response, $nb_site_target->getOutputType()
                );
            }

            return
                $this->nb_plugins_manager->invoquePrepareSite($this->nb_request, $this->nb_response) &&
                $this->nb_plugins_manager->invoquePrepareSiteTarget($this->nb_request, $this->nb_response) &&
                $this->nb_modules_manager->invoquePrepareMorph();
        } else {
            return true;
        }
    }

    private function prepareHeaders() {

        global $NABU_HTTP_CODES;

        $http_code = $this->nb_response->getHTTPResponseCode();
        header("HTTP/1.1 $http_code ".$NABU_HTTP_CODES[$http_code]);
        $nb_language = $this->nb_request->getLanguage();
        if ($nb_language !== null) {
            $this->nb_response->setHeader('Content-Language', $nb_language->getISO6391());
        }

        $mimetype = $this->nb_response->getMimetype();
        $this->nb_engine->traceLog("Mimetype", $mimetype);
        if (strlen($mimetype) > 0 && $mimetype != -1) {
            $this->nb_response->setHeader('Content-Type', "$mimetype; charset=UTF-8");
        }

        $this->nb_response->buildHeaders();
    }

    private function processMethods() {

        $request_method = strtoupper($this->nb_request->getMethod());

        if (!$this->nb_plugins_manager->invoqueMethod($this->nb_request, $request_method)) {
            return false;
        }

        return true;
    }

    private function processCommands() {

        if ($this->nb_request->getMethod() !== 'OPTIONS') {
            $nb_site_target = $this->nb_request->getSiteTarget();
            if ($nb_site_target !== null) {
                $art_commands = $nb_site_target->getCommandsList();
                $modules_commands = $this->nb_modules_manager->getCommandsList();
                $commands = array_merge(
                    ($art_commands === null ? array() : $art_commands),
                    ($modules_commands === null ? array() : $modules_commands)
                );
                if ($commands !== null) {
                    foreach ($commands as $command) {
                        $this->nb_plugins_manager->invoqueCommand($this->nb_request, $command);
                    }
                }
            }
        }

        return true;
    }

    private function validateCORSOrigin() {

        $retval = true;

        $origin = $this->nb_http_server->getOrigin();
        if (is_string($origin) && strlen($origin) > 0) {
            $retval = $this->nb_plugins_manager->invoqueValidateCORSOrigin($origin);
        }

        return $retval;
    }

    private function buildRedirection($code, $location)
    {
        global $NABU_HTTP_CODES;

        //throw new ENabuCoreException(ENabuCoreException::ERROR_METHOD_NOT_IMPLEMENTED, __METHOD__);
        $this->nb_engine->traceLog("Redirect to", $location);
        header("HTTP/1.1 $code " . $NABU_HTTP_CODES[$code]);
        header("Location: $location");
        echo "Redirecting to... $location";
    }

    private function buildResponse()
    {
        $render = $this->nb_response->getRender();

        if (($nb_site = $this->nb_http_server->getSite()) !== null) {
            $this->nb_security_manager->applyRoleMask($nb_site->getSiteMaps());
            $nb_site->indexURLs()
                    ->indexSiteMaps()
                    ->indexStaticContents()
            ;
        }

        if (($nb_site_target = $this->nb_request->getSiteTarget()) !== null) {
            $nb_site_target->getSections();
            $nb_site_target->indexSections();
            $nb_site_target->getCTAs();
            $nb_site_target->canonizeCTAs();
            $nb_site_target->indexCTAs();
            $this->nb_security_manager->applyRoleMask($nb_site_target);
            $nb_site->canonizeSiteMaps($nb_site_target);
        }

        if (($nb_commerce = $this->nb_request->getCommerce()) !== null) {
            $nb_commerce->sortAll();
        }

        if ($render instanceof INabuHTTPResponseRender) {
            $render->setRequest($this->nb_request);
            $render->setResponse($this->nb_response);
            if ($this->nb_plugins_manager->invoqueBeforeDisplayTarget($this->nb_request, $this->nb_response) &&
                $this->nb_modules_manager->invoqueBeforeMorphDisplay($this->nb_request, $this->nb_response)
               )
            {
                /** @todo Pending to revise in a near future */
                /*
                $this->nb_apps_manager->render();
                 */
                $render->render();
            }
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_RENDER_NOT_SET);
        }
    }

    public function addMedioteca(CNabuMedioteca $nb_medioteca)
    {
        $nb_medioteca->setCustomer($this->nb_engine->getCustomer());
        $this->nb_mediotecas_manager->addMedioteca($nb_medioteca);

        return $nb_medioteca;
    }
}
