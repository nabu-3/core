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

/**
 * Interface to handle an statement from a database query and manage resulset
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\core\exceptions
 */
interface INabuDBStatement
{
    /**
     * Default constructor
     * @param mixed Resultset Database object, ID or file name to configure connection
     */
    public function __construct($connector, $resultset);
    /**
     * Get number of rows available in statement
     * @return int Returns the number of rows
     */
    public function getRowsCount();
    /**
     * Fetch statement cursor and returns next register as a normal array.
     * The field values are ordered into array in the same order that in the query.
     * @return array Array of field values or false if error or no more registers to fetch
     */
    public function fetchAsArray();
    /**
     * Fetch statement cursor and returns next register as a associative array.
     * The field values are ordered into array in the same order that in the query.
     * @return array Array of field values indexes by field name or false if error or no more registers to fetch
     */
    public function fetchAsAssoc();
    /**
     * Get a row of a query as a class instance of type $classname.
     * @param string $classname Class name with full namespace path.
     * @return mixed Returns an object instance of type $classname if success and records found,
     * null if no records, or false if error.
     */
    public function fetchAsObject($classname);
    /**
     * Frees statement and closes opened cursor.
     * @return bool True if success of false if not
     */
    public function release();
}
