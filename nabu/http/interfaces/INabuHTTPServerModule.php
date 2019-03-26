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

namespace nabu\http\interfaces;

use nabu\http\exceptions\ENabuHTTPException;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\http\interfaces
 */
interface INabuHTTPServerModule
{
    /**
     * Create a HTTP Server Interface to manage a HTTP Server.
     * @param string $class_name Class name to be instantiated.
     * @return INabuHTTPServerInterface Returns a valid instance if $name is a valid name.
     * @throws ENabuHTTPException Raises an exception if the interface name is invalid.
     */
    public function createHTTPServerInterface(string $class_name) : INabuHTTPServerInterface;

    /**
     * This method is called to finish the use of a Render Interface instance.
     * @param INabuHTTPServerInterface $interface Interface instance to be released.
     * @throws ENabuHTTPException Raises an exception if $interface is not a candidate to be released.
     */
    public function releaseHTTPServerInterface(INabuHTTPServerInterface $interface);
}
