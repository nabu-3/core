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

namespace nabu\http\adapters;

use \nabu\core\CNabuEngine;
use \nabu\data\customer\CNabuCustomer;
use \nabu\data\lang\CNabuLanguage;
use \nabu\data\security\CNabuRole;
use \nabu\data\security\CNabuUser;
use \nabu\data\site\CNabuSite;
use \nabu\data\site\CNabuSiteAlias;
use \nabu\data\site\CNabuSiteTarget;
use \nabu\http\CNabuHTTPRequest;
use \nabu\http\CNabuHTTPResponse;
use \nabu\http\CNabuHTTPSession;
use \nabu\http\interfaces\INabuHTTPSitePlugin;

/**
 * Abstract adapter to easy implementation of Site plugins
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\http\adapters
 */
abstract class CNabuHTTPSitePluginAdapter implements INabuHTTPSitePlugin
{
    /**
     *
     * @var CNabuEngine
     */
    protected $nb_engine = null;
    /**
     * Application instance
     * @var CNabuHTTPApplication
     */
    protected $nb_application = null;
    /**
     *
     * @var \nabu\core\CNabuHTTPSession
     */
    protected $nb_session = null;
    /**
     *
     * @var CNabuCustomer
     */
    protected $nb_customer = null;
    /**
     *
     * @var CNabuCustomer
     */
    protected $nb_work_customer = null;
    /**
     *
     * @var CNabuHTTPRequest
     */
    protected $nb_request = null;
    /**
     *
     * @var CNabuHTTPResponse
     */
    protected $nb_response = null;
    /**
     *
     * @var CNabuSite
     */
    protected $nb_site = null;
    /**
     *
     * @var CNabuSiteAlias
     */
    protected $nb_site_alias = null;
    /**
     *
     * @var CNabuRole
     */
    protected $nb_site_alias_role = null;
    /**
     * Commerce instance
     * @var \nabu\commerce\CNabuCommerce
     */
    protected $nb_commerce = null;
    /**
     *
     * @var CNabuSiteTarget
     */
    protected $nb_site_target = null;
    /**
     *
     * @var CNabuLanguage
     */
    protected $nb_language = null;
    /**
     *
     * @var CNabuUser
     */
    protected $nb_user = null;
    /**
     *
     * @var CNabuRole
     */
    protected $nb_role = null;
    /**
     *
     * @var \nabu\security\CNabuUserRole
     */
    protected $nb_site_user = null;

    /**
     * This method is called just after the instantiation of plugin and after the instantiation
     * of a target plugin to set/refresh request and response objects.
     * After called from the Engine, all protected properties of this class are setted/refreshed
     * with proper values.
     * @param CNabuHTTPRequest $nb_request Request instance
     * @param CNabuHTTPResponse $nb_response Response instance
     * @return boolean Return true if trap successfully or false if not
     */
    public function trap($nb_request, $nb_response)
    {
        if (($this->nb_request = $nb_request) === null ||
            ($this->nb_response = $nb_response) === null ||
            ($this->nb_engine = CNabuEngine::getEngine()) === null ||
            ($this->nb_application = $this->nb_engine->getApplication()) === null ||
            ($this->nb_session = CNabuHTTPSession::getSession()) === null ||
            ($this->nb_customer = $this->nb_engine->getCustomer()) === null ||
            ($this->nb_site = $this->nb_request->getSite()) === null
        ) {
            $retval = false;
        } else {
            $this->nb_work_customer = $this->nb_application->getSecurityManager()->getWorkCustomer();
            $this->nb_site_target = $this->nb_request->getSiteTarget();
            $this->nb_language = $this->nb_request->getLanguage();
            $this->nb_user = $this->nb_application->getSecurityManager()->getUser();
            $this->nb_role = $this->nb_application->getSecurityManager()->getRole();
            $this->nb_site_user = $this->nb_application->getSecurityManager()->getSiteUser();
            $this->nb_commerce = $this->nb_request->getCommerce();
            $this->nb_site_alias = $this->nb_request->getSiteAlias();
            $this->nb_site_alias_role = $this->nb_request->getSiteAliasRole();

            $retval = true;
        }

        return $retval;
    }
}
