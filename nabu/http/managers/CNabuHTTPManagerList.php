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

namespace nabu\http\managers;
use nabu\data\CNabuDataObjectList;

/**
 * This class manages renders instantiation and access to interfased methods.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\http\managers
 */
class CNabuHTTPManagerList extends CNabuDataObjectList
{
    public function __construct()
    {
        parent::__construct('nb_http_manager_key');
    }

    /**
     * Always returns false.
     * @param string $key Id of the instance to unserialize.
     * @param string $index Secondary index to be used if needed.
     * @return bool Always returns false.
     */
    protected function acquireItem($key, $index = false)
    {
        return false;
    }

    protected function createSecondaryIndexes()
    {
    }
}
