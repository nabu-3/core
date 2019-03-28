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

namespace nabu\http;

use nabu\http\descriptors\CNabuHTTPServerInterfaceDescriptor;

use nabu\http\interfaces\INabuHTTPServerInterface;

use nabu\provider\adapters\CNabuProviderInterfaceFactoryAdapter;

use nabu\provider\base\CNabuProviderInterfaceDescriptor;

use nabu\provider\interfaces\INabuProviderManager;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\http
 */
class CNabuHTTPServerFactory extends CNabuProviderInterfaceFactoryAdapter
{
    public function createInterface(INabuProviderManager $nb_manager, CNabuProviderInterfaceDescriptor $nb_descriptor)
    {
        $nb_instance = null;

        if ($nb_descriptor instanceof CNabuHTTPServerInterfaceDescriptor) {
            $nb_instance = $nb_manager->createHTTPServerInterface($nb_descriptor->getClassName());
        }

        return $nb_instance;
    }

    protected function discoverInterface() : bool
    {
        return (parent::discoverInterface() && $this->nb_interface instanceof INabuHTTPServerInterface);
    }

    public function getInterface()
    {
        $this->discoverInterface();

        return parent::getInterface();
    }
}
