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

namespace nabu\core\exceptions;

/**
 * Exception to inform XML errors.
 * @author Rafael Gutiérrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\core\exceptions
 */
class ENabuXMLException extends ENabuException
{
    /** @var int Only one Root Element is allowed. */
    const ERROR_ONLY_ONE_ROOT                           = 0x0001;
    /** @var int Unexpected Element. Requires element name. */
    const ERROR_UNEXPECTED_ELEMENT                      = 0x0002;

    /** @var array $error_messages Error array messages list. */
    private static $error_messages = array(
        ENabuXMLException::ERROR_ONLY_ONE_ROOT =>
            'Only one Root Element is allowed.',
        ENabuXMLException::ERROR_UNEXPECTED_ELEMENT =>
            'Unexpected Element %s'
    );

    public function __construct($code, $values = null)
    {
        parent::__construct(ENabuXMLException::$error_messages[$code], $code, $values);
    }
}
