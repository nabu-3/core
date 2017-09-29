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

namespace nabu\messaging\interfaces;
use nabu\messaging\exceptions\ENabuMessagingException;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\messaging\interfaces
 */
interface INabuMessagingModule
{
    /**
     * Create a Service Interface to manage a Messaging service.
     * @param string $name Class name to be instantiated.
     * @return INabuMessagingServiceInterface Returns a valid instance if $name is a valid name.
     * @throws ENabuMessagingException Raises an exception if the interface name is invalid.
     */
    public function createServiceInterface(string $name);

    /**
     * This method is called to finish the use of a Service Interface instance.
     * @param INabuMessagingServiceInterface $interface Interface instance to be released.
     * @throws ENabuMessagingException Raises an exception if $interface is not a candidate to be released.
     */
    public function releaseServiceInterface(INabuMessagingServiceInterface $interface);
    /**
     * Create a Template Render Interface to manage a Messaging Template Render.
     * @param string $name Class name to be instantiated.
     * @return INabuMessagingTemplateRenderInterface Returns a valid instance if $name is a valid name.
     * @throws ENabuMessagingException Raises an exception if the interface name is invalid.
     */
    public function createTemplateRenderInterface(string $name);

    /**
     * This method is called to finish the use of a Template Render Interface instance.
     * @param INabuMessagingTemplateRenderInterface $interface Interface instance to be released.
     * @throws ENabuMessagingException Raises an exception if $interface is not a candidate to be released.
     */
    public function releaseTemplateRenderInterface(INabuMessagingTemplateRenderInterface $interface);
}
