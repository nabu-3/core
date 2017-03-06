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

namespace nabu\provider\exceptions;
use nabu\core\exceptions\ENabuException;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @since 3.0.9 Surface
 * @version 3.0.9 Surface
 * @package \nabu\core\interfaces
 */
class ENabuProviderException extends ENabuException
{
    /** @var int Vendor key is not valid. Requires the vendor key. */
    const ERROR_VENDOR_KEY_NOT_VALID            = 0x0001;
    /** @var int Manager key is not valid. Requires the manager key. */
    const ERROR_MODULE_KEY_NOT_VALID            = 0x0002;
    /** @var int Manager already exists. Requires vendor and manager keys. */
    const ERROR_MANAGER_ALREADY_EXISTS          = 0x0003;
    /** @var int Error executing init procedure for manager. Requires installation path. */
    const ERROR_MANAGER_NOT_INITIALIZED         = 0x0004;
    /** @var int Invalid vendor or module keys or not defined. */
    const ERROR_INVALID_KEYS                    = 0x0005;
    /** @var int Provider Factory not instantiated. */
    const ERROR_PROVIDER_FACTORY_NOT_AVAILABLE  = 0x0006;
    /** @var int Provider Manager fails when instantiate it. Requires complex key. */
    const ERROR_PROVIDER_MANAGER_FAIL           = 0x0007;
    /** @var int Provider Interface type does not exists. Requires interface type. */
    const ERROR_INTERFACE_TYPE_NOT_EXISTS       = 0x0008;

    /**
     * List of all error messages defined in this exception.
     * @var array
     */
    protected $error_messages = array(
        ENabuProviderException::ERROR_VENDOR_KEY_NOT_VALID =>
            'Vendor Key [%s] is not valid',
        ENabuProviderException::ERROR_MODULE_KEY_NOT_VALID =>
            'Manager Key [%s] is not valid',
        ENabuProviderException::ERROR_MANAGER_ALREADY_EXISTS =>
            'Manager instance [%s:%s] already exists',
        ENabuProviderException::ERROR_MANAGER_NOT_INITIALIZED =>
            'There was an error initializing the manager in %s',
        ENabuProviderException::ERROR_INVALID_KEYS =>
            'Invalid vendor or module keys, or not defined.',
        ENabuProviderException::ERROR_PROVIDER_FACTORY_NOT_AVAILABLE =>
            'Nabu Provider Factory is not available.',
        ENabuProviderException::ERROR_PROVIDER_MANAGER_FAIL,
            'Nabu Provider [%s] fails when instantiate it.',
        ENabuProviderException::ERROR_INTERFACE_TYPE_NOT_EXISTS =>
            'Provider Interface type [%s] does not exists.'
    );
}
