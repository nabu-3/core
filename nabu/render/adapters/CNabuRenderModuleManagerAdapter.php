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

namespace nabu\render\adapters;
use nabu\core\CNabuEngine;
use nabu\provider\CNabuProviderFactory;
use nabu\provider\base\CNabuProviderModuleManagerAdapter;
use nabu\provider\exceptions\ENabuProviderException;
use nabu\render\descriptors\CNabuRenderInterfaceDescriptor;
use nabu\render\descriptors\CNabuRenderTransformInterfaceDescriptor;
use nabu\render\exceptions\ENabuRenderException;
use nabu\render\interfaces\INabuRenderModule;
use nabu\render\interfaces\INabuRenderInterface;
use nabu\render\interfaces\INabuRenderTransformInterface;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\render\adapters
 */
abstract class CNabuRenderModuleManagerAdapter extends CNabuProviderModuleManagerAdapter
    implements INabuRenderModule
{
    /** @var array Array of Render Interface instances. */
    private $render_interface_list = null;
    /** @var array Array of Render Transform Interface instances. */
    private $render_transform_interface_list = null;

    /**
     * Register a new Render Interface instance.
     * @param INabuRenderInterface $interface Interface instance to be registered.
     * @return bool Returns true if the instance is registered and initiated.
     * @throws ENabuRenderException Raises an exception if $interface is already registered.
     */
    protected function registerRenderInterface(INabuRenderInterface $interface) : bool
    {
        $hash = $interface->getHash();
        if (is_array($this->render_interface_list) && array_key_exists($hash, $this->render_interface_list)) {
            throw new ENabuRenderException(
                ENabuRenderException::ERROR_RENDER_INSTANCE_ALREADY_REGISTERED,
                array(get_class($interface))
            );
        }

        if ($this->render_interface_list === null) {
            $this->render_interface_list = array($hash => $interface);
        } else {
            $this->render_interface_list[$hash] = $interface;
        }

        return $interface->init();
    }

    public function createRenderInterface(string $class_name) : INabuRenderInterface
    {
        $nb_engine = CNabuEngine::getEngine();
        $nb_descriptor = $nb_engine->getProviderInterfaceDescriptor(
            $this->getVendorKey(), $this->getModuleKey(),
            CNabuProviderFactory::INTERFACE_RENDER, $class_name
        );

        if ($nb_descriptor instanceof CNabuRenderInterfaceDescriptor) {
            $fullname = $nb_descriptor->getNamespace() . "\\renders\\$class_name";
            if ($nb_engine->preloadClass($fullname)) {
                $interface = new $fullname($this);
                if ($this->registerRenderInterface($interface)) {
                    return $interface;
                } else {
                    throw new ENabuRenderException(
                        ENabuRenderException::ERROR_RENDER_CANNOT_BE_INSTANTIATED,
                        array($class_name)
                    );
                }
            } else {
                throw new ENabuRenderException(
                    ENabuRenderException::ERROR_INVALID_RENDER_CLASS_NAME, array($class_name)
                );
            }
        } else {
            throw new ENabuProviderException(
                ENabuProviderException::ERROR_INTERFACE_DESCRIPTOR_NOT_FOUND, array($class_name)
            );
        }
    }

    public function releaseRenderInterface(INabuRenderInterface $interface)
    {
        $hash = $interface->getHash();

        if (array_key_exists($hash, $this->render_interface_list)) {
            $interface->finish();
            unset($this->render_interface_list[$hash]);
        }
    }

    /**
     * Register a new Render Transform Interface instance.
     * @param INabuRenderTransformInterface $interface Interface instance to be registered.
     * @return bool Returns true if the instance is registered and initiated.
     * @throws ENabuRenderException Raises an exception if $interface is already registered.
     */
    protected function registerTransformInterface(INabuRenderTransformInterface $interface) : bool
    {
        $hash = $interface->getHash();
        if (is_array($this->render_transform_interface_list) &&
            array_key_exists($hash, $this->render_transform_interface_list)
        ) {
            throw new ENabuRenderException(
                ENabuRenderException::ERROR_RENDER_TRANSFORM_INSTANCE_ALREADY_REGISTERED,
                array(get_class($interface))
            );
        }

        if ($this->render_transform_interface_list === null) {
            $this->render_transform_interface_list = array($hash => $interface);
        } else {
            $this->render_transform_interface_list[$hash] = $interface;
        }

        return $interface->init();
    }

    public function createTransformInterface(string $class_name) : INabuRenderTransformInterface
    {
        $nb_engine = CNabuEngine::getEngine();
        $nb_descriptor = $nb_engine->getProviderInterfaceDescriptor(
            $this->getVendorKey(), $this->getModuleKey(),
            CNabuProviderFactory::INTERFACE_RENDER_TRANSFORM, $class_name
        );

        if ($nb_descriptor instanceof CNabuRenderTransformInterfaceDescriptor) {
            $fullname = $nb_descriptor->getNamespace() . "\\transforms\\$class_name";
            if ($nb_engine->preloadClass($fullname)) {
                $interface = new $fullname($this);
                if ($this->registerTransformInterface($interface)) {
                    return $interface;
                } else {
                    throw new ENabuRenderException(
                        ENabuRenderException::ERROR_RENDER_TRANSFORM_CANNOT_BE_INSTANTIATED,
                        array($class_name)
                    );
                }
            } else {
                throw new ENabuRenderException(
                    ENabuRenderException::ERROR_INVALID_RENDER_TRANSFORM_CLASS_NAME, array($class_name)
                );
            }
        } else {
            throw new ENabuProviderException(
                ENabuProviderException::ERROR_INTERFACE_DESCRIPTOR_NOT_FOUND, array($class_name)
            );
        }
    }

    public function releaseTransformInterface(INabuRenderTransformInterface $interface)
    {
        $hash = $interface->getHash();

        if (array_key_exists($hash, $this->render_transform_interface_list)) {
            $interface->finish();
            unset($this->render_transform_interface_list[$hash]);
        }
    }

}
