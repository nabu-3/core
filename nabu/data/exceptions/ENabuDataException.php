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

namespace nabu\data\exceptions;
use nabu\core\exceptions\ENabuException;

/**
 * Exception to inform errors in Core processes.
 * @author Rafael Gutiérrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\exceptions
 */
class ENabuDataException extends ENabuException
{
    public function __construct($code, $values = null)
    {
        $this->overloadMessages();

        parent::__construct($this->error_messages[$code], $code, $values);
    }

    /**
     * Override this method in descendant classes to extend the attribute class
     * $error_messages with more messages customized for each DB Engine.
     * Extended error messages can be numbered starting at 0x8001.
     */
    protected function overloadMessages()
    {
    }
}
