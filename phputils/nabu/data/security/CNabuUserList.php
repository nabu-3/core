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

namespace nabu\data\security;
use nabu\core\CNabuEngine;
use nabu\data\CNabuDataObjectList;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\security
 */
class CNabuUserList extends CNabuDataObjectList
{
    public function __construct()
    {
        parent::__construct('nb_user_id');
    }

    /**
     * Acquires an instance of class CNabuUser from the database.
     * @param string $key Id of the instance to unserialize.
     * @param string $index Secondary index to be used if needed.
     * @return mixed Returns the unserialized instance if exists or false if not.
     */
    protected function acquireItem($key, $index = false)
    {
        $retval = false;

        $nb_engine = CNabuEngine::getEngine();
        if ($nb_engine->isMainDBAvailable()) {
            $item = new CNabuUser($key);
            if ($item->isFetched()) {
                $retval = $item;
            }
        }

        return $retval;
    }
}
