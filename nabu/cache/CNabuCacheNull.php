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

namespace nabu\cache;

use \nabu\cache\CNabuCacheNullContainer;
use \nabu\cache\interfaces\INabuCacheStorage;
use \nabu\core\CNabuObject;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package name
 */
class CNabuCacheNull extends CNabuObject implements INabuCacheStorage
{
    public function initStorage()
    {
        return true;
    }

    public function createContainerId($type, $index, $id, $params)
    {
        $id = "$type:$index:$id";

        if (count($params) > 0) {
            foreach($params as $key => $value) {
                if ($value === null) {
                    $id .= "|$key=null";
                } elseif ($value === false) {
                    $id .= "|$key=false";
                } else if ($value === true) {
                    $id .= "|$key=true";
                } else {
                    $id .= "|$key=$value";
                }
            }
        }
        return $id;
    }

    public function createContainer(string $key, $source = false, bool $prevent_callable = false)
    {
        return new CNabuCacheNullContainer($key, $source, $prevent_callable);
    }

    public function getContainer($key)
    {
        return null;
    }

    public function releaseContainer($key)
    {
        return true;
    }
}
