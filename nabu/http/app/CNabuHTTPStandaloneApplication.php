<?php

/** @license
 *  Copyright 2019-2011 Rafael Gutierrez Martinez
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

namespace nabu\http\app;

use Exception;
use nabu\core\CNabuEngine;
use nabu\core\exceptions\ENabuCoreException;
use nabu\data\commerce\builtin\CNabuBuiltInCommerce;
use nabu\data\lang\CNabuLanguage;
use nabu\data\lang\builtin\CNabuBuiltInLanguage;
use nabu\data\medioteca\CNabuMedioteca;
use nabu\data\medioteca\builtin\CNabuBuiltInMedioteca;
use nabu\data\security\CNabuRole;
use nabu\data\site\CNabuSite;
use nabu\data\site\CNabuSiteMap;
use nabu\data\site\CNabuSiteTarget;
use nabu\data\site\CNabuSiteStaticContent;
use nabu\data\site\builtin\CNabuBuiltInSiteMap;
use nabu\data\site\builtin\CNabuBuiltInSiteTarget;
use nabu\data\site\builtin\CNabuBuiltInSiteStaticContent;
use nabu\http\app\CNabuStandaloneApplication;
use nabu\http\app\base\CNabuHTTPApplication;

/**
 * Abstract class to implement child classes to manage HTTP Server Applications.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package name
 */
abstract class CNabuHTTPStandaloneApplication extends CNabuHTTPApplication
{
    /**
     * Launch the application represented in an inherited class of this class.
     * @return mixed Returns the value returned internally by the run method, or
     * false if prepareEnvironment fails.
     */
    final public static function launch()
    {
        try {
            $nb_engine = CNabuEngine::getEngine();

            $class_name = get_called_class();
            $instance = new $class_name();
            if (!($instance instanceof CNabuHTTPStandaloneApplication)) {
                throw new ENabuCoreException(ENabuCoreException::ERROR_OPERATION_MODE_NOT_ALLOWED);
            }

            $instance->init();

            $nb_engine->getHTTPServer()
                      ->getSite()
                          ->setSmartyCachePath(NABU_CACHE_FOLDER)
                          ->setSmartyCompilePath(SMARTY_COMPILES_FOLDER)
                          ->setSmartyConfigsPath(SMARTY_CONFIG_FOLDER)
                          ->setSmartyTemplatePath(SMARTY_TEMPLATES_FOLDER)
            ;

            $retval = $instance->prepareEnvironment();

            /*
            $nb_engine->registerApplication($instance);
            $instance->prepareMediotecasManager();
            */

            $nb_engine->getHTTPServer()
                      ->getSite()
                          ->indexURLs()
                          ->indexSiteMaps()
                          ->indexStaticContents();

            if ($retval) {
                $retval = $instance->run();
            }

            $nb_engine->removeApplication();
        } catch (Throwable $re) {
            if (isset($instance)) {
                $instance->displayErrorPage(500, $re);
            }
            throw $re;
        } catch (Exception $re) {
            if (isset($instance)) {
                $instance->displayErrorPage(500, $re);
            }
            throw $re;
        }

        return $retval;
    }

    /**
     * Enables the Smarty Engine.
     */
    public function enableSmarty()
    {
        $this->nb_engine->getHTTPServer()->getSite()->setUseSmarty(CNabuSite::ENABLE_SMARTY);
    }

    /**
     * Enables the Log Tracer of nabu-3 Engine.
     */
    public function enableLogTrace()
    {
        $this->nb_engine->enableLogTrace();
    }

    /**
     * Fast method to add a language to the Site.
     * @param string $ISO639_1 ISO-639/1 code of language.
     * @param string $name Common name of the language
     * @param string $default_country_code Default country for this language
     * @return CNabuLanguage Returns a CNabuLanguage instance
     */
    public function enableLanguage($ISO639_1, $name, $default_country_code, $default_lang = false)
    {
        $nb_language = new CNabuBuiltInLanguage();
        $nb_language->setISO6391($ISO639_1)
                    ->setName($name)
                    ->setDefaultCountryCode($default_country_code)
                    ->setEnabled(CNabuLanguage::LANGUAGE_ENABLED)
        ;

        $nb_site = $this->nb_engine->getHTTPServer()->getSite();
        $nb_site->setLanguage($nb_language);
        if ($default_lang) {
            $nb_site->setDefaultLanguage($nb_language);
        }

        return $nb_language;
    }

    /**
     * Sets the plugin for the entire Site.
     * @param string $plugin_name Full name of the plugin class, including their namespace
     */
    public function setSitePlugin($plugin_name)
    {
        $this->nb_engine->getHTTPServer()->getSite()->setPluginName($plugin_name);
    }

    /**
     * Add a target using Smarty template files
     * @param string $display Path to Smarty Display file.
     * @param type $content Path to Smarty Content file.
     * @return CNabuSiteTarget Returns the created target instance.
     */
    public function addSmartyTarget($display, $content, $default = false)
    {
        $target = new CNabuBuiltInSiteTarget();
        $target->setMIMETypeId('text/html')
               ->setOutputTypeHTML()
               ->setSmartyDisplayFile($display)
               ->setSmartyContentFile($content)
        ;

        return $this->addTarget($target, $default);
    }

    public function addWebService($plugin, $mimetype = 'application/json')
    {
        $target = new CNabuBuiltInSiteTarget();
        $target->setMIMETypeId($mimetype)
               ->setPluginName($plugin)
        ;

        return $this->addTarget($target);
    }

    /**
     * Adds an existing Site Target instance to the list of targets.
     * @param CNabuSiteTarget $nb_site_target The Site Target instance to be added.
     * @return CNabuSiteTarget Returns the instance target passed as param in $nb_site_target.
     * @throws ENabuCoreException Throws an exception if the Site is not initialized.
     */
    protected function addTarget(CNabuSiteTarget $nb_site_target, $default = false)
    {
        $nb_site = $this->nb_engine->getHTTPServer()->getSite();
        if ($nb_site instanceof CNabuSite) {
            return $nb_site->addTarget($nb_site_target, $default);
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_NOT_INSTANTIATED);
        }
    }

    /**
     * Sets the Login Target when private zones are requested.
     * @param CNabuSiteTarget $nb_site_target Site Target to be setted.
     * @return Returns the target setted.
     * @throws ENabuCoreException Throws an exception it the Site is not initialized.
     */
    public function setLoginTarget(CNabuSiteTarget $nb_site_target)
    {
        $nb_site = $this->nb_engine->getHTTPServer()->getSite();
        if ($nb_site instanceof CNabuSite) {
            return $nb_site->setLoginTarget($nb_site_target);
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_NOT_INSTANTIATED);
        }
    }

    /**
     * Adds a new Site Map root node of type URL.
     * @param int $order Order for the node.
     * @param CNabuRole $nb_role Default role for this Site Map. If null, uses the default Role of the Site.
     * @return CNabuBuiltInSiteMap Returns the created instance
     */
    public function addRootSiteMapForURL($order, CNabuRole $nb_role = null)
    {
        if ($nb_role === null) {
            $nb_role = $this->nb_engine->getHTTPServer()->getSite()->getDefaultRole();
        }

        $nb_site_map = new CNabuBuiltInSiteMap();
        $nb_site_map->setOrder($order);

        return $this->addRootSiteMap($nb_site_map);
    }

    /**
     * Adds a new Site Map root node of type Target.
     * @param CNabuSiteTarget $nb_site_target
     * @param int $order Order for the node.
     * @param CNabuRole $nb_role Default role for this Site Map. If null, uses the default Role of the Site.
     * @return CNabuBuiltInSiteMap Returns the created instance
     */
    public function addRootSiteMapForTarget(CNabuSiteTarget $nb_site_target, $order, CNabuRole $nb_role = null)
    {
        if ($nb_role === null) {
            $nb_role = $this->nb_engine->getHTTPServer()->getSite()->getDefaultRole();
        }

        $nb_site_map = new CNabuBuiltInSiteMap();
        $nb_site_map->setSiteTarget($nb_site_target)
            ->setOrder($order)
        ;

        return $this->addRootSiteMap($nb_site_map);
    }

    /**
     * Add an existing Site Map root node.
     * @param CNabuSiteMap $nb_site_map The Site Map node to be added to root nodes.
     * @return CNabuSiteMap Returns the Site Map instance passed as param in $nb_site_map.
     * @throws ENabuCoreException Throws this exception when the Site is not initialized.
     */
    public function addRootSiteMap(CNabuSiteMap $nb_site_map)
    {
        $nb_site = $this->nb_engine->getHTTPServer()->getSite();
        if ($nb_site instanceof CNabuSite) {
            return $nb_site->addSiteMap($nb_site_map);
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_NOT_INSTANTIATED);
        }
    }

    /**
     * Add Static Content to the Site. $content can be a CNabuSiteStaticContent object or a key string.
     * If a key string is provided, then the method creates a instance of CNabuBuiltInSiteStaticContent.
     * @param string|CNabuSiteStaticContent $content The instance or key to add as Static Content.
     * @return CNabuSiteStaticContent Returns the instance passed as param in $content or a new instance if $content
     * is a key string.
     */
    public function addStaticContent($content = false)
    {
        if (!is_string($content) && !($content instanceof CNabuSiteStaticContent)) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE, array($content, '$content'));
        }

        $nb_site = $this->nb_engine->getHTTPServer()->getSite();
        if ($nb_site instanceof CNabuSite) {
            if (is_string($content)) {
                $nb_site_static_content = new CNabuBuiltInSiteStaticContent();
                $nb_site_static_content->setKey($content);
            } else {
                $nb_site_static_content = $content;
            }
            return $nb_site->addStaticContent($nb_site_static_content);
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_NOT_INSTANTIATED);
        }
    }

    public function addCommerce($key)
    {
        $nb_site = $this->nb_engine->getHTTPServer()->getSite();
        if ($nb_site instanceof CNabuSite) {
            $nb_commerce = new CNabuBuiltInCommerce();
            $nb_commerce->setKey($key);
            $nb_commerce->setCustomer($this->nb_engine->getCustomer());
            $nb_site->setCommerce($nb_commerce);

            return $nb_commerce;
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_NOT_INSTANTIATED);
        }
    }

    /**
     * Creates a new BuiltIn Medioteca and adds them to the Mediotecas Manager.
     * @param string $key Key to locate the Medioteca.
     * @return CNabuMedioteca Returns the inserted Medioteca to allow cascade chain methods.
     */
    public function newMedioteca($key)
    {
        $nb_medioteca = new CNabuBuiltInMedioteca();
        $nb_medioteca->setKey($key);

        return $this->addMedioteca($nb_medioteca);
    }
}
