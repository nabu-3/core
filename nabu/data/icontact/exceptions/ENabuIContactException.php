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

namespace nabu\data\icontact\exceptions;
use nabu\data\exceptions\ENabuDataException;

/**
 * Exception to inform errors in Core processes.
 * @author Rafael Gutiérrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\exceptions
 */
class ENabuIContactException extends ENabuDataException
{
    /* Query error messages */
    /** @var int The main folder of an iContact product cannot be created. Requires the path folder. */
    const ERROR_MAIN_PATH_CANNOT_BE_CREATED = 0x8001;
    /** @var int iContact needs to be saved or fetched before add attachments. */
    const ERROR_ICONTACT_NOT_FETCHED        = 0x8002;

    protected function overloadMessages()
    {
        /* IContact error messages */
        $this->error_messages[ENabuIContactException::ERROR_MAIN_PATH_CANNOT_BE_CREATED] =
            'The main folder %s for this iContact cannot be created.';
        $this->error_messages[ENabuIContactException::ERROR_ICONTACT_NOT_FETCHED] =
            'iContact needs to be saved or fetched before add attachments.';
    }
}
