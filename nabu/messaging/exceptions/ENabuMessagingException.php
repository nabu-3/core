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
    /** @var int Invalid Messaging Pool Manager. */
    const ERROR_INVALID_MESSAGING_POOL_MANAGER                  = 0x0001;
    /** @var int Invalid address box. Requires the address box Id. */
    const ERROR_INVALID_ADDRESS_BOX                             = 0x0002;
    /** @var int Target address boxes are not defined. */
    const ERROR_TARGET_ADDRESS_BOXES_NOT_DEFINED                = 0x0003;

    /** @var int Service Interface cannot be instantiated. Requires the Service Interface Name. */
    const ERROR_SERVICE_CANNOT_BE_INSTANTIATED                  = 0x1001;
    /** @var int Invalid Service Interface Instance. */
    const ERROR_INVALID_SERVICE_INSTANCE                        = 0x1001;
    /** @var int Invalid Service Interface Class Name. Requires the Service Interface Class Name. */
    const ERROR_INVALID_SERVICE_CLASS_NAME                      = 0x1002;
    /** @var int Service Interface already registered. Requires the Serice Interface Hash. */
    const ERROR_SERVICE_INSTANCE_ALREADY_REGISTERED             = 0x1003;
    /** @var int Service is required. */
    const ERROR_SERVICE_REQUIRED                                = 0x1004;

    /** @var int Template Render Interface cannot be instantiated. Requires the Template Render Interface Name. */
    const ERROR_TEMPLATE_RENDER_CANNOT_BE_INSTANTIATED          = 0x2001;
    /** @var int Invalid Template Render Interface Instance. */
    const ERROR_INVALID_TEMPLATE_RENDER_INSTANCE                = 0x2002;
    /** @var int Invalid Template Render Interface Class Name. Requires the Service Template Render Class Name. */
    const ERROR_INVALID_TEMPLATE_RENDER_CLASS_NAME              = 0X2003;
    /** @var int Template Render Interface cannot be instantiated. Requires the Template Render Interface Name. */
    const ERROR_TEMPLATE_RENDER_INSTANCE_ALREADY_REGISTERED     = 0x2004;
    /** @var int Invalid Messaging Template. */
    const ERROR_INVALID_TEMPLATE                                = 0x2005;
    /** @var int A Template is required. */
    const ERROR_TEMPLATE_REQUIRED                               = 0x2006;
    /** @var int Template not allowed. Require Template Id. */
    const ERROR_TEMPLATE_NOT_ALLOWED                            = 0x2007;

    /**
     * List of all error messages defined in this exception.
     * @var array
     */
    private static $error_messages = array(
        ENabuMessagingException::ERROR_INVALID_MESSAGING_POOL_MANAGER =>
            'Invalid Messaging Pool Manager.',
        ENabuMessagingException::ERROR_INVALID_ADDRESS_BOX =>
            'The address box [%s] is invalid',
        ENabuMessagingException::ERROR_TARGET_ADDRESS_BOXES_NOT_DEFINED =>
            'At least one qualified target address box is required.',

        ENabuMessagingException::ERROR_SERVICE_CANNOT_BE_INSTANTIATED =>
            'Service Interface [%s] cannot be instantiated.',
        ENabuMessagingException::ERROR_INVALID_SERVICE_INSTANCE =>
            'Invalid Service Interface Instance.',
        ENabuMessagingException::ERROR_INVALID_SERVICE_CLASS_NAME =>
            'Invalid Service Interface class name [%s].',
        ENabuMessagingException::ERROR_SERVICE_INSTANCE_ALREADY_REGISTERED =>
            'Service Interface instance [%s] already registered.',
        ENabuMessagingException::ERROR_SERVICE_REQUIRED =>
            'A Service intance is required to perform this operation.',

        ENabuMessagingException::ERROR_TEMPLATE_RENDER_CANNOT_BE_INSTANTIATED =>
            'Template Render Interface [%s] cannot be instantiated.',
        ENabuMessagingException::ERROR_INVALID_TEMPLATE_RENDER_INSTANCE =>
            'Invalid Template Render Interface Instance.',
        ENabuMessagingException::ERROR_INVALID_TEMPLATE_RENDER_CLASS_NAME =>
            'Invalid Template Render Interface class name [%s].',
        ENabuMessagingException::ERROR_TEMPLATE_RENDER_INSTANCE_ALREADY_REGISTERED =>
            'Template Render Interface instance [%s] already registered.',
        ENabuMessagingException::ERROR_INVALID_TEMPLATE =>
            'The designated Template is not valid.',
        ENabuMessagingException::ERROR_TEMPLATE_NOT_ALLOWED =>
            'Template is not allowed under this Messaging instance.',
        ENabuMessagingException::ERROR_TEMPLATE_REQUIRED =>
            'A Template instance is required to perform this operation.',
        ENabuMessagingException::ERROR_TEMPLATE_NOT_ALLOWED =>
            'The Template [%s] is not allowed in this context.'
    );

    public function __construct($code, $values = null)
    {
        parent::__construct(ENabuMessagingException::$error_messages[$code], $code, $values);
    }
}
