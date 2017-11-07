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

namespace nabu\db\interfaces;
use Exception;

/**
 * Interface to implement a Database Exception Class
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.12 Surface
 * @package \nabu\db\interfaces
 */
interface INabuDBException
{
    /**
     * Force to unify declaration of constructor.
     * @param int $code
     * @param int $sql_code
     * @param string $sql_message
     * @param string $sql_script
     * @param array $values
     * @param Exception $previous
     */
    public function __construct(
        int $code,
        int $sql_code = 0,
        string $sql_message = null,
        string $sql_script = null,
        array $values = null,
        Exception $previous = null
    );

    /**
     * Gets the SQL Code of the exception.
     * @return int Returns the SQL Code.
     */
    public function getSQLCode() : int;

    /**
     * Gets the SQL Script of the exception.
     * @return mixed Returns the SQL script.
     */
    public function getSQLScript();
}
