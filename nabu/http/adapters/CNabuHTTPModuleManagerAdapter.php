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

namespace nabu\http\adapters;

use nabu\core\CNabuEngine;

use nabu\http\descriptors\CNabuHTTPServerInterfaceDescriptor;

use nabu\http\exceptions\ENabuHTTPException;

use nabu\http\interfaces\INabuHTTPServerInterface;
use nabu\http\interfaces\INabuHTTPServerModule;

use nabu\provider\CNabuProviderFactory;

use nabu\provider\base\CNabuProviderModuleManagerAdapter;

use nabu\provider\exceptions\ENabuProviderException;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 0.0.1
 * @version 0.0.1
 * @package \providers\nabu\pdf
 */
abstract class CNabuHTTPModuleManagerAdapter extends CNabuProviderModuleManagerAdapter implements INabuHTTPServerModule
{
    /** @var array Array of Interface instances. */
    private $http_server_interface_list = null;

    /**
     * Register a new HTTP Server Interface instance.
     * @param INabuHTTPServerInterface $interface Interface instance to be registered.
     * @return bool Returns true if the instance is registered and initiated.
     * @throws ENabuHTTPException Raises an exception if $interface is already registered.
     */
    protected function registerHTTPServerInterface(INabuHTTPServerInterface $interface) : bool
    {
        $hash = $interface->getHash();
        if (is_array($this->http_server_interface_list) && array_key_exists($hash, $this->http_server_interface_list)) {
            throw new ENabuHTTPException(
                ENabuHTTPException::ERROR_HTTP_SERVER_INSTANCE_ALREADY_REGISTERED,
                array(get_class($interface))
            );
        }

        if ($this->http_server_interface_list === null) {
            $this->http_server_interface_list = array($hash => $interface);
        } else {
            $this->http_server_interface_list[$hash] = $interface;
        }

        return $interface->init();
    }

    public function createHTTPServerInterface(string $class_name) : INabuHTTPServerInterface
    {
        $nb_engine = CNabuEngine::getEngine();
        $nb_descriptor = $nb_engine->getProviderInterfaceDescriptor(
            $this->getVendorKey(),
            $this->getModuleKey(),
            CNabuProviderFactory::INTERFACE_HTTP_SERVER_SERVICE,
            $class_name
        );

        if ($nb_descriptor instanceof CNabuHTTPServerInterfaceDescriptor) {
            $fullname = $nb_descriptor->getNamespace() . "\\servers\\$class_name";
            if ($nb_engine->preloadClass($fullname)) {
                $interface = new $fullname($this);
                if ($this->registerHTTPServerInterface($interface)) {
                    return $interface;
                } else {
                    throw new ENabuHTTPException(
                        ENabuHTTPException::ERROR_HTTP_SERVER_CANNOT_BE_INSTANTIATED,
                        array($class_name)
                    );
                }
            } else {
                throw new ENabuHTTPException(
                    ENabuHTTPException::ERROR_INVALID_HTTP_SERVER_CLASS_NAME,
                    array($class_name)
                );
            }
        } else {
            throw new ENabuProviderException(
                ENabuProviderException::ERROR_INTERFACE_DESCRIPTOR_NOT_FOUND,
                array($class_name)
            );
        }
    }

    public function releaseHTTPServerInterface(INabuHTTPServerInterface $interface)
    {
        $hash = $interface->getHash();

        if (array_key_exists($hash, $this->http_server_interface_list)) {
            $interface->finish();
            unset($this->http_server_interface_list[$hash]);
        }
    }
}
