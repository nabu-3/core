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

namespace nabu\cli\app;

use \nabu\cli\app\CNabuCLIApplication;
use \nabu\cli\app\CNabuClusterApp;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 */
class CNabuClusterApp extends CNabuCLIApplication
{
    const ACTION_CREATE_CLUSTER = 'create-cluster';
    
    private $action = false;
    private $params = null;
    
    public function prepareEnvironment()
    {
        global $argc, $argv;
        
        if ($argc < 2) {
            return;
        }
        
        switch ($argv[1]) {
            case 'create':
                $this->prepareEnvironmentCreateCommand();
                break;
        }
    }
    
    private function prepareEnvironmentCreateCommand()
    {
        global $argc, $argv;
        
        if ($argc > 2) {
            switch ($argv[2]) {
                case 'cluster':
                    $this->action = CNabuClusterApp::ACTION_CREATE_CLUSTER;
                    $this->params = $this->prepareParams(
                            array(
                                3 => 'key',
                                4 => 'name'
                            )
                    );
            }
        }
    }
    
    private function prepareParams($list = null)
    {
        global $argc, $argv;
        
        if (count($list) > 0) {
            $params = array();
            foreach ($list as $index => $descriptor) {
                if (is_numeric($index) && $index < $argc && is_string($descriptor)) {
                    $params[$descriptor] = $argv[$index];
                }
            }
        }
        
        return (count($params) > 0 ? $params : null);
    }

    public function run()
    {
        echo "Action: $this->action\n";
        echo "Params: " . print_r($this->params, true) . "\n";
        
        if ($this->action === false) {
            $this->help();
        } else {
            switch ($this->action) {
                case CNabuClusterApp::ACTION_CREATE_CLUSTER:
                    return $this->actionCreateCluster();
            }
        }
    }
    
    private function help()
    {
        echo "\nThis is the nabu-3 Cluster Management Tool.\n";
        echo "With this tool you can manage your cluster from the command line.\n\n";
        
        echo "These are the commands available:\n\n";
        
        echo "help\n";
        echo "    Shows this help\n\n";
        echo "create cluster <key> [name]\n";
        echo "    Creates a cluster entity. At least <key> is mandatory. Optionally you can define\n";
        echo "    a name to identify or describe the cluster.\n";
    }
}
