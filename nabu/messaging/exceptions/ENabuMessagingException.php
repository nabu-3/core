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

namespace nabu\messaging\exceptions;
use nabu\core\exceptions\ENabuException;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\messaging\exceptions
 */
class ENabuMessagingException extends ENabuException
{
    /** @var int Service Interface cannot be instantiated. Requires the Service Interface Name. */
    const ERROR_SERVICE_CANNOT_BE_INSTANTIATED                  = 0x0001;

    /** @var int Invalid Service Interface Instance. */
    const ERROR_INVALID_SERVICE_INSTANCE                        = 0x1001;
    /** @var int Invalid Service Interface Class Name. Requires the Service Interface Class Name. */
    const ERROR_INVALID_SERVICE_CLASS_NAME                      = 0x1002;
    /** @var int Service Interface already registered. Requires the Serice Interface Hash. */
    const ERROR_SERVICE_INSTANCE_ALREADY_REGISTERED             = 0x1003;

    /** @var int Invalid Template Render Interface Instance. */
    const ERROR_INVALID_TEMPLATE_RENDER_INSTANCE                = 0x2001;
    /** @var int Invalid Template Render Interface Class Name. Requires the Service Template Render Class Name. */
    const ERROR_INVALID_TEMPLATE_RENDER_CLASS_NAME              = 0X2002;
    /** @var int Template Render Interface cannot be instantiated. Requires the Template Render Interface Name. */
    const ERROR_TEMPLATE_RENDER_INSTANCE_ALREADY_REGISTERED     = 0x2003;

    /**
     * List of all error messages defined in this exception.
     * @var array
     */
    protected $error_messages = array(
        ENabuMessagingException::ERROR_SERVICE_CANNOT_BE_INSTANTIATED =>
            'Service Interface [%s] cannot be instantiated.',

        ENabuMessagingException::ERROR_INVALID_SERVICE_INSTANCE =>
            'Invalid Service Interface Instance',
        ENabuMessagingException::ERROR_INVALID_SERVICE_CLASS_NAME =>
            'Invalid Service Interface class name [%s]',
        ENabuMessagingException::ERROR_SERVICE_INSTANCE_ALREADY_REGISTERED =>
            'Service Interface instance [%s] already registered.',

        ENabuMessagingException::ERROR_INVALID_TEMPLATE_RENDER_INSTANCE =>
            'Invalid Template Render Interface Instance',
        ENabuMessagingException::ERROR_INVALID_TEMPLATE_RENDER_CLASS_NAME =>
            'Invalid Template Render Interface class name [%s]',
        ENabuMessagingException::ERROR_TEMPLATE_RENDER_INSTANCE_ALREADY_REGISTERED =>
            'Template Render Interface instance [%s] already registered.',
    );
}
