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

namespace nabu\data\catalog\exceptions;
use nabu\data\exceptions\ENabuDataException;

/**
 * Exception to inform errors in Core processes.
 * @author Rafael Gutiérrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\exceptions
 */
class ENabuCatalogException extends ENabuDataException
{
    /* Query error messages */
    /** @var int The requested Item is not included in the catalog. Requires the Item id. */
    const ERROR_ITEM_NOT_INCLUDED_IN_CATALOG = 0x8001;

    protected function overloadMessages()
    {
        /* Catalog error messages */
        $this->error_messages[ENabuCatalogException::ERROR_ITEM_NOT_INCLUDED_IN_CATALOG] =
            'The requested Item [%s] is not included in the catalog.';
    }
}
