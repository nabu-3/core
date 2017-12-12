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

use nabu\core\CNabuEngine;
use nabu\http\CNabuHTTPResponse;
use nabu\http\app\base\CNabuHTTPApplication;
use nabu\http\managers\CNabuHTTPRenderDescriptor;
use nabu\http\managers\base\CNabuHTTPManager;
use nabu\provider\CNabuProviderFactory;
use nabu\render\CNabuRenderTransformFactory;
use nabu\render\descriptors\CNabuRenderTransformInterfaceDescriptor;
use nabu\render\exceptions\ENabuRenderException;
use nabu\render\managers\CNabuRenderPoolManager;

/**
 * This class manages renders instantiation and access to interfased methods.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.9 Surface
 * @package \nabu\http\managers
 */
final class CNabuHTTPRendersManager extends CNabuHTTPManager
{
    /**
     * Collection of class renders available
     * @var CNabuHTTPRenderList
     */
    private $nb_http_render_list = null;

    public function __construct(CNabuHTTPApplication $nb_application)
    {
        parent::__construct($nb_application);

        $this->nb_http_render_list = new CNabuHTTPRenderList();
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

    public function registerRender(CNabuHTTPRenderDescriptor $descriptor)
    {
        $this->nb_http_render_list->addItem($descriptor);
    }

    public function setResponseRender(CNabuHTTPResponse $nb_response, $descriptor_key)
    {
        $nb_descriptor = $this->nb_http_render_list->getItem($descriptor_key);

        if ($nb_descriptor instanceof CNabuHTTPRenderDescriptor) {
            $nb_response->setRender($nb_descriptor->createRender($this->nb_application));
        } else {
            throw new ENabuRenderException(ENabuRenderException::ERROR_RENDER_NOT_FOUND, array($descriptor_key));
        }

        return $this;
    }

    /**
     * Sets the Response Render Transform Interface using their Interface Name.
     * @param CNabuHTTPResponse $nb_response HTTP Response instance.
     * @param string|null $interface_key Response Render Interface Key to search.
     * @return CNabuHTTPRendersManager Returns self pointer to grant setters chain.
     */
    public function setResponseTransform(CNabuHTTPResponse $nb_response, string $interface_key = null)
    {
        if ($interface_key === null) {
            $nb_response->setTransformFactory(null);
        } else {
            $nb_engine = CNabuEngine::getEngine();
            $nb_descriptor = $nb_engine->getProviderInterfaceDescriptorByKey(
                CNabuProviderFactory::INTERFACE_RENDER_TRANSFORM,
                $interface_key
            );

            if ($nb_descriptor instanceof CNabuRenderTransformInterfaceDescriptor &&
                ($nb_pool_manager = $nb_engine->getRenderPoolManager()) instanceof CNabuRenderPoolManager &&
                ($nb_transform_factory = $nb_pool_manager->getTransformFactory($nb_descriptor)) instanceof CNabuRenderTransformFactory
            ) {
                $nb_response->setTransformFactory($nb_transform_factory);
            } else {
                throw new ENabuRenderException(
                    ENabuRenderException::ERROR_RENDER_TRANSFORM_NOT_FOUND,
                    array($interface_key)
                );
            }
        }

        return $this;
    }
}
