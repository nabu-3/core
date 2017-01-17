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

namespace providers\smarty;
use Smarty;
use nabu\core\interfaces\INabuSingleton;
use nabu\http\app\base\CNabuHTTPApplication;
use nabu\http\managers\CNabuHTTPRenderDescriptor;
use nabu\http\managers\base\CNabuHTTPManager;

/**
 * Class to manage Smarty library
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \providers\smarty
 */
class CSmartyManager extends CNabuHTTPManager
{
    /**
     * Collection of Smarty Model instances
     * @var CSmartyModelList
     */
    private $nb_smarty_model_list = null;
    /**
     * Base path of the package
     * @var string
     */
    private $base_path = null;
    /**
     * Current selected model
     * @var CSmartyModel
     */
    private $nb_smarty_current_model = null;

    /**
     * Default constructor. This object is singleton then, more than one instantiation throws a ENabuSingletonException.
     * It is not recommendable to instantiate directly. To do this, call \providers\smarty\CSmartyManager::getManager().
     * @throws ENabuSingletonException
     */
    public function __construct(CNabuHTTPApplication $nb_application)
    {
        parent::__construct($nb_application);

        $this->base_path = dirname(__FILE__);
        $this->nb_smarty_model_list = new CSmartyModelList();
        $this->setKey(SMARTY_MANAGER_KEY);
    }

    /**
     * Register the provider in current application to extend their functionalities.
     * @return boolean Returns true if enable process is succeed.
     */
    public function enableManager()
    {
        $this->nb_application->registerRender(
            (new CNabuHTTPRenderDescriptor())
                ->setKey('HTML')
                ->setClassName('providers\smarty\renders\CSmartyHTTPRender')
        );

        return true;
    }

    /**
     * Gets the base path where the package is installed.
     * @return string Returns the base path.
     */
    public function getBasePath()
    {
        return $this->base_path;
    }

    public function getTemplatesPath()
    {
        return $this->base_path . SMARTY_TEMPLATES_FOLDER;
    }

    public function getPluginsPath()
    {
        return $this->base_path . SMARTY_PLUGINS_FOLDER;
    }

    public function setModel($key, Smarty $smarty)
    {
        if (!($model = $this->nb_smarty_model_list->getItem($key))) {
            $model = new CSmartyModel();
            $model->setKey($key);
            $nb_http_server = $this->nb_application->getHTTPServer();
            $nb_server = $nb_http_server->getServer();
            $nb_site = $nb_http_server->getSite();
            $path = $nb_server->getVirtualHostsPath()
                  . $nb_site->getBasePath()
                  . $nb_site->getSmartyTemplatePath()
                  . SMARTY_MODELS_FOLDER
                  . DIRECTORY_SEPARATOR . $key
            ;
            if ($model->addLocation($path)) {
                $smarty->addTemplateDir($path);
                $model->setMainPath($path);
            }
            $path = $this->base_path . SMARTY_TEMPLATES_FOLDER . SMARTY_MODELS_FOLDER . DIRECTORY_SEPARATOR . $key;
            if ($model->addLocation($path)) {
                $smarty->addTemplateDir($path);
                $model->setMainPath($path);
            }

            $this->nb_smarty_model_list->addItem($model);
        }

        $this->nb_smarty_current_model = $model;
    }

    /**
     * Gets the current Smarty Model instance-
     * @return CSmartyModel Returns current selected Smarty Model if exists or null if not.
     */
    public function getCurrentModel()
    {
        return $this->nb_smarty_current_model;
    }
}
