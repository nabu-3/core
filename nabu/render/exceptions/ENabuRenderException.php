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

namespace nabu\render\exceptions;
use nabu\core\exceptions\ENabuException;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\render\exceptions
 */
class ENabuRenderException extends ENabuException
{
    /** @var int Invalid Render Pool Manager. */
    const ERROR_INVALID_RENDER_POOL_MANAGER                     = 0x0001;
    /** @var int Invalid MIME Type. Requires the MIME Type. */
    const ERROR_INVALID_MIME_TYPE                               = 0x0002;
    /** @var int Invalid vendor and module combination. Requires the vendor key and the module key. */
    const ERROR_INVALID_VENDOR_MODULE_KEYS                      = 0x0003;
    /** @var int Render cannot be instantiated. Requires Render class name. */
    const ERROR_RENDER_CANNOT_BE_INSTANTIATED                   = 0x0004;
    /** @var int Invalid Render Class Name. Requires the Class Name. */
    const ERROR_INVALID_SERVICE_CLASS_NAME                      = 0x0005;
    /** @var int Render already instantiated. Requires the Class Name. */
    const ERROR_RENDER_INSTANCE_ALREADY_REGISTERED              = 0x0006;

    /** @var array List of all error messages defined in this exception. */
    private static $error_messages = array(
        ENabuRenderException::ERROR_INVALID_RENDER_POOL_MANAGER =>
            'Invalid Render Pool Manager.',
        ENabuRenderException::ERROR_INVALID_MIME_TYPE =>
            'Invalid MIME Type %s.',
        ENabuRenderException::ERROR_INVALID_VENDOR_MODULE_KEYS =>
            'Vendor Module Keys are invalid [%s, %s].',
        ENabuRenderException::ERROR_RENDER_CANNOT_BE_INSTANTIATED =>
            'The Render [%s] cannot be instantiated.',
        ENabuRenderException::ERROR_INVALID_SERVICE_CLASS_NAME =>
            'Render Class name is invalid or does not exists.',
        ENabuRenderException::ERROR_RENDER_INSTANCE_ALREADY_REGISTERED =>
            'Render instance of [%s] already registered.'
    );

    public function __construct($code, $values = null)
    {
        parent::__construct(ENabuRenderException::$error_messages[$code], $code, $values);
    }
}
