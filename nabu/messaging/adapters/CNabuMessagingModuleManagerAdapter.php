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

 /**
  * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
  * @since 3.0.9 Surface
  * @version 3.0.9 Surface
  * @package \nabu\http\managers\base
  */
namespace nabu\messaging\adapters;
use nabu\messaging\exceptions\ENabuMessagingException;
use nabu\messaging\interfaces\INabuMessagingModule;
use nabu\messaging\interfaces\INabuMessagingServiceInterface;
use nabu\messaging\interfaces\INabuMessagingTemplateRenderInterface;
use nabu\provider\base\CNabuProviderModuleManagerAdapter;
use nabu\provider\interfaces\INabuProviderManager;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\http\managers\base
 */
abstract class CNabuMessagingModuleManagerAdapter extends CNabuProviderModuleManagerAdapter
    implements INabuProviderManager, INabuMessagingModule
{
    /** @var array Array of Service Interface instances */
    private $service_interface_list = null;
    /** @var array Array of Template Render Interface instances */
    private $template_render_interface_list = null;

    public function __construct(string $vendor_key, string $module_key)
    {
        parent::__construct($vendor_key, $module_key);
    }

    /**
     * Register a new Messaging Interface instance.
     * @param INabuMessagingServiceInterface $interface Interface instance to be registered.
     * @return bool Returns true if the instance is registered and initiated.
     * @throws ENabuMessagingException Raises an exception if $interface is already registered.
     */
    protected function registerServiceInterface(INabuMessagingServiceInterface $interface)
    {
        $hash = $interface->getHash();
        if (is_array($this->service_interface_list) && array_key_exists($hash, $this->service_interface_list)) {
            throw new ENabuMessagingException(
                ENabuMessagingException::ERROR_SERVICE_INSTANCE_ALREADY_REGISTERED,
                array($hash)
            );
        }

        if ($this->service_interface_list === null) {
            $this->service_interface_list = array($hash => $interface);
        } else {
            $this->service_interface_list[$hash] = $interface;
        }

        return $interface->init();
    }

    public function releaseServiceInterface(INabuMessagingServiceInterface $interface)
    {
        $hash = $interface->getHash();

        if (array_key_exists($hash, $this->service_interface_list)) {
            $interface->finish();
            unset($this->service_interface_list[$hash]);
        }
    }

    /**
     * Register a new Messaging Template Render Interface instance.
     * @param INabuMessagingTemplateRenderInterface $interface Interface instance to be registered.
     * @return bool Returns true if the instance is registered and initiated.
     * @throws ENabuMessagingException Raises an exception if $interface is already registered.
     */
    protected function registerTemplateRenderInterface(INabuMessagingTemplateRenderInterface $interface)
    {
        $hash = $interface->getHash();
        if (is_array($this->template_render_interface_list) &&
            array_key_exists($hash, $this->template_render_interface_list)
        ) {
            throw new ENabuMessagingException(
                ENabuMessagingException::ERROR_TEMPLATE_RENDER_INSTANCE_ALREADY_REGISTERED,
                array($hash)
            );
        }

        if ($this->template_render_interface_list === null) {
            $this->template_render_interface_list = array($hash => $interface);
        } else {
            $this->template_render_interface_list[$hash] = $interface;
        }

        return $interface->init();
    }

    public function releaseTemplateRenderInterface(INabuMessagingTemplateRenderInterface $interface)
    {
        $hash = $interface->getHash();

        if (array_key_exists($hash, $this->template_render_interface_list)) {
            $interface->finish();
            unset($this->template_render_interface_list[$hash]);
        }
    }
}
