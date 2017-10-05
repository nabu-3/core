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

namespace nabu\render\interfaces;
use nabu\render\exceptions\ENabuRenderException;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\render\interfaces
 */
interface INabuRenderModule
{
    /**
     * Create a Render Interface to manage a MIME Type output.
     * @param string $class_name Class name to be instantiated.
     * @return INabuRenderInterface Returns a valid instance if $name is a valid name.
     * @throws ENabuRenderException Raises an exception if the interface name is invalid.
     */
    public function createRenderInterface(string $class_name) : INabuRenderInterface;

    /**
     * This method is called to finish the use of a Render Interface instance.
     * @param INabuRenderInterface $interface Interface instance to be released.
     * @throws ENabuRenderException Raises an exception if $interface is not a candidate to be released.
     */
    public function releaseRenderInterface(INabuRenderInterface $interface);
}
