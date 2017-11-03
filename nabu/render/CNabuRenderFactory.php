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

namespace nabu\render;
use nabu\core\CNabuEngine;
use nabu\data\CNabuDataObject;
use nabu\provider\CNabuProviderFactory;
use nabu\render\descriptors\CNabuRenderInterfaceDescriptor;
use nabu\render\exceptions\ENabuRenderException;
use nabu\render\interfaces\INabuRenderInterface;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\render
 */
class CNabuRenderFactory extends CNabuDataObject
{
    /** @var string The MIME Type assigned to this Factory. */
    private $mimetype;

    /** @var string The Vendor Key identifier to force interfaces on it. */
    private $vendor_key;

    /** @var string The Module Key identifier to force interfaces on it. */
    private $module_key;

    /** @var INabuRenderInterface Valid Render Interface. */
    private $nb_render_interface = null;

    /**
     * Initializes the Factory.
     * @param string $mimetype MIME Type to be attended by this Factory.
     * @param string|null $vendor_key Optional vendor key to be used.
     * @param string|null $module_key Optional module key to be used.
     * @throws ENabuRenderException Raises an exception if MIME Type is not valid.
     */
    public function __construct(string $mimetype, string $vendor_key = null, string $module_key = null)
    {
        if (!nb_isMIMEType($mimetype)) {
            throw new ENabuRenderException(ENabuRenderException::ERROR_INVALID_MIME_TYPE, array($mimetype));
        }
        parent::__construct();

        $this->mimetype = $mimetype;

        if (($vendor_key === null && $module_key !== null) ||
            ($vendor_key !== null && !nb_isValidKey($vendor_key)) ||
            ($module_key !== null && !nb_isValidKey($module_key))
        ) {
            throw new ENabuRenderException(
                ENabuRenderException::ERROR_INVALID_VENDOR_MODULE_KEYS,
                array($vendor_key, $module_key)
            );
        }

        $this->vendor_key = $vendor_key;
        $this->module_key = $module_key;
    }

    /**
     * Gets current Vendor Key.
     * @return string Returns current Vendor Key.
     */
    public function getVendorKey(): string
    {
        return $this->vendor_key;
    }

    /**
     * Sets a Vendor Key.
     * @param string $vendor_key New Vendor Key to be setted.
     * @return static Returns self instance to grant Cascade setters.
     */
    public function setVendorKey(string $vendor_key)
    {
        $this->vendor_key = $vendor_key;
        return $this;
    }

    /**
     * Gets current Module Key.
     * @return string Returns current Module Key.
     */
    public function getModuleKey(): string
    {
        return $this->module_key;
    }

    /**
     * Sets the Module Key.
     * @param string $module_key New Module Key to be setted.
     * @return static Returns self instance to grant Cascade setters.
     */
    public function setModuleKey(string $module_key)
    {
        $this->module_key = $module_key;
        return $this;
    }

    /**
     * Discover the Render Interface.
     * @return bool If Service is mapped then returns true.
     * @throws ENabuRenderException Raises an exception if the designated Render is not valid or applicable.
     */
    private function discoverRenderInterface() : bool
    {
        $nb_engine = CNabuEngine::getEngine();

        if (!($this->nb_render_interface instanceof INabuRenderInterface)) {
            $nb_engine
                ->getProvidersInterfaceDescriptors(CNabuProviderFactory::INTERFACE_RENDER)
                ->iterate(
                    function($key, CNabuRenderInterfaceDescriptor $descriptor)
                    {
                        if ($descriptor->getMIMEType() === $this->mimetype) {
                            if ($this->vendor_key !== null) {
                                $nb_manager = $descriptor->getManager();
                                if ($this->vendor_key === $nb_manager->getVendorKey() &&
                                    ($this->module_key === null || $this->module_key === $nb_manager->getModuleKey())
                                ) {
                                    $this->nb_render_interface = $nb_manager->createRenderInterface($descriptor->getName());
                                }
                            } else {
                                $this->nb_render_interface = $nb_manager->createRenderInterface($descriptor->getName());
                            }
                        }
                    }
                )
            ;
        }

        return ($this->nb_render_interface instanceof INabuRenderInterface);

    }

    /**
     * Render a string using the default Render of this factory.
     * @param string $string String to be rended.
     * @param array|null $params Optiona parameters to be passed to the render.
     */
    public function buildStringAsHTTPResponse(string $string, array $params = null)
    {
        if ($this->discoverRenderInterface()) {
            $this->nb_render_interface->init();
        }
    }
}
