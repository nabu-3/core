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

namespace nabu\db\interfaces;

/**
 * Interface to implement a Database Exception Class
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\core\exceptions
 */
interface INabuDBException
{
    /**
     * Force to unify declaration of constructor.
     * @param type $code
     * @param type $sql_code
     * @param type $sql_message
     * @param type $sql_script
     * @param type $values
     * @param type $previous
     */
    public function __construct(
        $code,
        $sql_code = 0,
        $sql_message = null,
        $sql_script = null,
        $values = null,
        $previous = null
    );
    public function getSQLCode();
    public function getSQLScript();
}
