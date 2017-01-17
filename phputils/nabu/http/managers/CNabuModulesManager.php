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

namespace nabu\http\managers;

use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\module\CNabuModuleMorphList;
use \nabu\http\app\base\CNabuHTTPApplication;
use \nabu\http\managers\base\CNabuHTTPManager;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\http\managers
 */
class CNabuModulesManager extends CNabuHTTPManager
{
    /**
     * Plugins Manager instance
     * @var CNabuHTTPPluginsManager
     */
    private $nb_plugins_manager = null;
    /**
     *
     * @var array CNabuModuleMorphList
     */
    private $nb_module_morph_list = null;
    /**
     * List of commands availables in all slots and widgets mounted in all morphs availables
     * @var array
     */
    private $commands_list = null;

    /**
     * Default constructor.
     * @param CNabuHTTPApplication $nb_application Application instance
     * @param CNabuHTTPPluginsManager $nb_plugins_manager Plugins Manager instance
     * @throws exceptions\ECMSCoreException
     */
    public function __construct(CNabuHTTPApplication $nb_application, CNabuHTTPPluginsManager $nb_plugins_manager)
    {
        parent::__construct($nb_application);

        if ($nb_plugins_manager === null || !($nb_plugins_manager instanceof CNabuHTTPPluginsManager)) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_PLUGINS_MANAGER_REQUIRED);
        }
        $this->nb_plugins_manager = $nb_plugins_manager;
        $this->nb_plugins_manager->setModulesManager($this);
        $this->nb_module_morph_list = new CNabuModuleMorphList();
    }

    /**
     * Register the provider in current application to extend their functionalities.
     * @return boolean Returns true if enable process is succeed.
     */
    protected function enableManager()
    {
        return true;
    }

    public function getMorphList()
    {
        return $this->nb_module_morph_list;
    }

    public function getCommandsList($force = false) {

        if ($this->commands_list === null || $force) {

            $this->commands_list = null;
            $commands = array();

            $this->nb_module_morph_list->iterate(
                function ($key, $morph) use ($commands) {
                    $morph_commands = $morph->getCommandsList();
                    if (is_array($morph_commands)) {
                        $commands = array_merge($commands, $morph_commands);
                    }
                }
            );

            if (count($commands) > 0) {
                $this->commands_list = array_unique($commands);
            }
        }

        return $this->commands_list;
    }

    public function invoqueTrap($nb_request, $nb_response)
    {
        return $this->nb_module_morph_list->iterate(
            function ($key, $morph) use ($nb_request, $nb_response)
            {
                return $morph->invoqueTrap($nb_request, $nb_response);
            }
        ) !== false;
    }

    public function invoquePrepareMorph() {

        return $this->nb_module_morph_list->iterate(
            function ($key, $morph)
            {
                $morph->invoquePrepareMorph();
                return true;
            }
        ) !== false;
    }

    public function invoqueCommand($command) {

        $status = true;

        $this->nb_module_morph_list->iterate(
            function ($key, $morph) use ($status, $command)
            {
                $status = $status && $morph->invoqueCommand($command);
                return true;
            }
        );

        return $status;
    }

    public function invoqueBeforeMorphDisplay() {

        $this->nb_module_morph_list->iterate(
            function ($key, $morph) {
                $morph->invoqueBeforeDisplayMorph();
                return true;
            }
        );

        return true;
    }
}
