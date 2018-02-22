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

namespace nabu\http\managers;

use Exception;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\module\CNabuModuleMorph;
use \nabu\data\site\CNabuSite;
use \nabu\data\site\CNabuSiteTarget;
use \nabu\http\app\base\CNabuHTTPApplication;
use \nabu\http\CNabuHTTPRequest;
use \nabu\http\CNabuHTTPResponse;
use \nabu\http\interfaces\INabuHTTPSitePlugin;
use \nabu\http\interfaces\INabuHTTPSiteTargetPlugin;
use \nabu\http\managers\base\CNabuHTTPManager;
use \nabu\http\managers\CNabuModulesManager;
use nabu\data\security\CNabuUser;
use nabu\data\security\CNabuRole;
use nabu\data\site\CNabuSiteUser;

/**
 * This class manages plugins instantiation and access to interfased methods.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\http\managers
 */
final class CNabuHTTPPluginsManager extends CNabuHTTPManager
{
    /** @var INabuHTTPSitePlugin Plugin of Site. */
    private $site_plugin = null;
    /** @var INabuHTTPSiteTargetPlugin Plugin of Site Target. */
    private $site_target_plugin = null;
    /** @var CNabuModulesManager Modules Manager instance. */
    private $nb_modules_manager = null;

    /**
     * Default constructor.
     */
    public function __construct(CNabuHTTPApplication $nb_application)
    {
        parent::__construct($nb_application);
    }

    public function getVendorKey()
    {
        return 'nabu-3';
    }

    /**
     * Register the provider in current application to extend their functionalities.
     * @return bool Returns true if enable process is succeed.
     */
    public function enableManager()
    {
        return true;
    }

    public function setModulesManager(CNabuModulesManager $nb_modules_manager = null)
    {
        $this->nb_modules_manager = $nb_modules_manager;
    }

    public function prepareForSite($nb_request, $nb_response)
    {
        if (!($nb_request instanceof CNabuHTTPRequest)) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_CLASS_TYPE,
                array(get_class($nb_request), '$nb_request')
            );
        }

        if (!($nb_response instanceof CNabuHTTPResponse)) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_CLASS_TYPE,
                array(get_class($nb_response), '$nb_response'));
        }

        $nb_site = $nb_request->getSite();
        if (!($nb_site instanceof CNabuSite)) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_NOT_FOUND);
        }

        if ($this->site_plugin !== null) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_PLUGIN_ALREADY_ASSIGNED);
        }

        if ($nb_site->contains('nb_site_plugin_name') && strlen($class_name = $nb_site->getValue('nb_site_plugin_name')) > 0) {
            try {
                $this->site_plugin = new $class_name();
                if (!($this->site_plugin instanceof INabuHTTPSitePlugin)) {
                    throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_PLUGIN_NOT_VALID, array($class_name));
                }
                $this->invoqueTrap($nb_request, $nb_response);
            } catch(Exception $ex) {
                throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_PLUGIN_NOT_VALID, array($class_name));
            }
        }
    }

    public function prepareForSiteTarget(CNabuHTTPRequest $nb_request, CNabuHTTPResponse $nb_response)
    {
        $nb_site = $nb_request->getSite();
        if (!($nb_site instanceof CNabuSite)) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_NOT_FOUND);
        }

        $nb_site_target = $nb_request->getSiteTarget();
        if (!($nb_site_target instanceof CNabuSiteTarget)) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_TARGET_NOT_FOUND);
        }

        if ($this->site_target_plugin !== null) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_TARGET_PLUGIN_ALREADY_ASSIGNED);
        }

        if ($this->site_plugin !== null && !$this->site_plugin->trap($nb_request, $nb_response)) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_PLUGIN_INIT_ERROR);
        }

        if ($nb_site_target->contains('nb_site_target_plugin_name') &&
            strlen($class_name = $nb_site_target->getPluginName()) > 0
        ) {
            try {
                $this->site_target_plugin = new $class_name();
                if (!($this->site_target_plugin instanceof INabuHTTPSiteTargetPlugin)) {
                    throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_TARGET_PLUGIN_NOT_VALID, $class_name);
                }
                $this->invoqueTrap($nb_request, $nb_response);
            } catch(Exception $ex) {
                throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_TARGET_PLUGIN_NOT_VALID, $class_name);
            }
        }
    }

    public function pluginRedirectToPage($redirect, $klass = null, $method = null)
    {
        $nb_request = $this->nb_application->getRequest();
        if ($nb_request === null) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_REQUEST_NOT_FOUND);
        }

        $nb_response = $this->nb_application->getResponse();
        if ($nb_response === null) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_RESPONSE_NOT_FOUND);
        }

        $nb_site = $nb_request->getSite();
        if ($nb_site === null) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_NOT_FOUND);
        }

        if ($redirect === true) {
            return true;
        } else if ($redirect === false) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_PLUGIN_REDIRECTION_NOT_ALLOWED);
        } else if ($redirect instanceof CNabuSiteTarget) {
            if ($redirect->isValueNumeric('nb_language_id')) {
                $nb_language_id = $redirect->getValue('nb_language_id');
            } else {
                $nb_language_id = $nb_site->getValue('nb_site_default_language_id');
            }
            $nb_response->movedPermanentlyRedirect($redirect, $nb_language_id, null);
        } else if (is_string($redirect)) {
            $nb_response->movedPermanentlyRedirect($redirect, null, null);
        } else {
            if ($klass !== null && $method !== null) {
                throw new ENabuCoreException(ENabuCoreException::ERROR_PLUGIN_COMMAND_INVALID_RETURN_VALUE, array($method, $klass));
            } else if ($klass !== null || $method !== null) {
                throw new ENabuCoreException(ENabuCoreException::ERROR_PLUGIN_COMMAND_MISMATCH);
            } else {
                throw new ENabuCoreException(ENabuCoreException::ERROR_PLUGIN_REDIRECTION_NOT_ALLOWED);
            }
        }
    }

    public function invoqueTrap($nb_request, $nb_response)
    {
        if ($this->site_plugin instanceof INabuHTTPSitePlugin &&
            !($status = $this->site_plugin->trap($nb_request, $nb_response))
        ) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_PLUGIN_INIT_ERROR);
        }

        if ($this->site_target_plugin instanceof INabuHTTPSiteTargetPlugin &&
            !($status = $this->site_target_plugin->trap($nb_request, $nb_response))
        ) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_TARGET_PLUGIN_INIT_ERROR);
        }

        if ($this->nb_modules_manager instanceof CNabuModulesManager &&
            !($status = $this->nb_modules_manager->invoqueTrap($nb_request, $nb_response))
        ) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_MODULES_MANAGER_INIT_ERROR);
        }
    }

    public function invoquePrepareSite()
    {
        return $this->pluginRedirectToPage(
                $this->site_plugin !== null
                    ? $this->site_plugin->prepareSite()
                    : true
        );
    }

    public function invoqueRedirectRoot()
    {
        return $this->pluginRedirectToPage(
                $this->site_plugin !== null
                    ? $this->site_plugin->redirectRoot()
                    : true
        );
    }

    public function invoqueValidateCORSOrigin($origin) {

        return $this->pluginRedirectToPage(
                $this->site_plugin !== null
                    ? $this->site_plugin->validateCORSOrigin($origin)
                    : true
        );
    }

    public function invoquePrepareSiteTarget()
    {
        return  $this->pluginRedirectToPage(
                    $this->site_plugin !== null
                        ? $this->site_plugin->prepareTarget()
                        : true) &&
                $this->pluginRedirectToPage(
                    $this->site_target_plugin !== null
                        ? $this->site_target_plugin->prepareTarget()
                        : true);
    }

    public function invoquePrepareModulesMorphs()
    {
        $morph_site_list = (
                $this->site_plugin !== null && method_exists($this->site_plugin, 'prepareModulesMorphs')
                ? call_user_func(array($this->site_plugin, 'prepareModulesMorphs'))
                : null
        );

        if ($morph_site_list instanceof CNabuModuleMorph) {
            $morph_site_list = array($morph_site_list);
        } elseif ($morph_site_list === false) {
            return false;
        }

        $morph_site_target_list = (
                $this->site_target_plugin !== null && method_exists($this->site_target_plugin, 'prepareModulesMorphs')
                ? call_user_func(array($this->site_target_plugin, 'prepareModulesMorphs'))
                : null
        );

        if ($morph_site_target_list instanceof CNabuModuleMorph) {
            $morph_site_target_list = array($morph_site_target_list);
        } elseif ($morph_site_target_list === false) {
            return false;
        }

        if (is_array($morph_site_list)) {
            if (is_array($morph_site_target_list)) {
                $morph_list = array_merge($morph_site_list, $morph_site_target_list);
            } else {
                $morph_list = $morph_site_list;
            }
        } elseif (is_array($morph_site_target_list)) {
            $morph_list = $morph_site_target_list;
        } else {
            return true;
        }

        if (is_array($morph_list) && count($morph_list) > 0) {

            reset ($morph_list);
            while (list($key, $morph) = each($morph_list)) {
                if (!($morph instanceof CNabuModuleMorph)) {
                    return false;
                }
            }
            reset ($morph_list);
            while (list($key, $morph) = each($morph_list)) {
                $this->nb_modules_manager->setModuleMorph($morph);
            }
        }

        return true;
    }

    public function invoqueMethod($nb_request, $method)
    {
        if ($nb_request !== null) {
            $func = 'method' . $method;
            return  $this->pluginRedirectToPage(
                        $this->site_plugin !== null && method_exists($this->site_plugin, $func)
                            ? call_user_func(array($this->site_plugin, $func))
                            : true,
                        get_class($this->site_plugin),
                        $func
                    ) &&
                    $this->pluginRedirectToPage(
                        $this->site_target_plugin !== null && method_exists($this->site_target_plugin, $func)
                            ? call_user_func(array($this->site_target_plugin, $func))
                            : true,
                        get_class($this->site_target_plugin),
                        $func
                    ) &&
                    $this->nb_modules_manager->invoqueCommand($func);
        }

        return true;
    }

    public function invoqueCommand($nb_request, $command)
    {
        if ($nb_request !== null && $nb_request->hasREQUESTField($command)) {
            $func = 'command'.$command;
            return  $this->pluginRedirectToPage(
                        $this->site_plugin !== null && method_exists($this->site_plugin, $func)
                            ? call_user_func(array($this->site_plugin, $func))
                            : true,
                        get_class($this->site_plugin),
                        $func
                    ) &&
                    $this->pluginRedirectToPage(
                        $this->site_target_plugin !== null && method_exists($this->site_target_plugin, $func)
                            ? call_user_func(array($this->site_target_plugin, $func))
                            : true,
                        get_class($this->site_target_plugin),
                        $func
                    ) && $this->nb_modules_manager->invoqueCommand($func);
        }

        return true;
    }

    public function invoqueBeforeDisplayTarget()
    {
        return  $this->pluginRedirectToPage(
                    $this->site_plugin !== null
                        ? $this->site_plugin->beforeDisplayTarget()
                        : true) &&
                $this->pluginRedirectToPage(
                    $this->site_target_plugin !== null
                        ? $this->site_target_plugin->beforeDisplayTarget()
                        :true)
        ;
    }

    public function invoqueBeforeLogout() {

        return $this->pluginRedirectToPage(
               $this->site_plugin !== null
               ? $this->site_plugin->beforeLogout()
               : true
        );
    }

    public function invoqueAfterLogin(CNabuUser $cms_user, CNabuRole $cms_role, CNabuSiteUser $cms_site_user) {

        return $this->site_plugin !== null
               ? $this->site_plugin->afterLogin($cms_user, $cms_role, $cms_site_user)
               : true
        ;
    }
}
