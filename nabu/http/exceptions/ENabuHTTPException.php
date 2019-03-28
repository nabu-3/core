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

namespace nabu\http\exceptions;

use nabu\core\exceptions\ENabuException;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\messaging\exceptions
 */
class ENabuHTTPException extends ENabuException
{
    /** @var int X-Frame-Options ALLOW-FROM expects a URL as parameter. */
    const ERROR_X_FRAME_OPTIONS_URL_NOT_FOUND                     = 0x0001;
    /** @var int */
    const ERROR_INVALID_HTTP_SERVER_POOL_MANAGER                  = 0x0002;

    /** @var array List of all error messages defined in this exception. */
    private static $error_messages = array(
        ENabuHTTPException::ERROR_X_FRAME_OPTIONS_URL_NOT_FOUND =>
            'X-Frame-Options ALLOW-FROM expects a URL as parameter but none found.',
        ENabuHTTPException::ERROR_INVALID_HTTP_SERVER_POOL_MANAGER =>
            'Invalid HTTP Server Pool Manager.'
    );

    public function __construct($code, $values = null)
    {
        parent::__construct(self::$error_messages[$code], $code, $values);
    }
}
