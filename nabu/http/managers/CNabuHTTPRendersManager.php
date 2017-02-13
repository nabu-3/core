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

use nabu\core\exceptions\ENabuCoreException;
use nabu\http\CNabuHTTPResponse;
use nabu\http\app\base\CNabuHTTPApplication;
use nabu\http\managers\CNabuHTTPRenderDescriptor;
use nabu\http\managers\base\CNabuHTTPManager;

/**
 * This class manages renders instantiation and access to interfased methods.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
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
            throw new ENabuCoreException(ENabuCoreException::ERROR_RENDER_NOT_FOUND, array($descriptor_key));
        }
    }
}
