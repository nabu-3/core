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

namespace nabu\provider\exceptions;
use nabu\core\exceptions\ENabuException;
use nabu\core\exceptions\ENabuCoreException;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
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
    /** @var int Descriptor not found for interface. Requires interface name. */
    const ERROR_INTERFACE_DESCRIPTOR_NOT_FOUND  = 0x0009;
    /** @var int Interface already registered. Requires interface name. */
    const ERROR_INTERFACE_ALREADY_REGISTERED    = 0x000a;
    /** @var int Interface not setted. */
    const ERROR_INTERFACE_NOT_SETTED            = 0x000b;

    /**
     * List of all error messages defined in this exception.
     * @var array
     */
    protected static $error_messages = array(
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
            'Provider Interface type [%s] does not exists.',
        ENabuProviderException::ERROR_INTERFACE_DESCRIPTOR_NOT_FOUND =>
            'The Interface Descriptor [%s] is not found.',
        ENabuProviderException::ERROR_INTERFACE_ALREADY_REGISTERED =>
            'Interface [%s] already registered.',
        ENabuProviderException::ERROR_INTERFACE_NOT_SETTED =>
            'Interface not setted.'
    );

    public function __construct($code, $values = null)
    {
        if (array_key_exists($code, ENabuProviderException::$error_messages)) {
            parent::__construct(ENabuProviderException::$error_messages[$code], $code, $values);
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_UNEXPECTED_EXCEPTION_CODE, array($code));
        }
    }
}
