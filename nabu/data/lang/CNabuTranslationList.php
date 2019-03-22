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

namespace nabu\data\lang;

use \nabu\data\CNabuDataObjectList;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\lang
 */
class CNabuTranslationList extends CNabuDataObjectList
{
    public function __construct()
    {
        parent::__construct('nb_language_id');
    }

    /**
     * Do nothing.
     * @param string Id of the instance to unserialize.
     * @param $index Secondary index to be used to acquire the item if needed.
     * @return mixed Always returns false.
     */
    protected function acquireItem($key, $index = false)
    {
        return false;
    }

    protected function createSecondaryIndexes()
    {
    }
}
