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

namespace nabu\data\module;

use \nabu\data\CNabuDataObjectList;
use nabu\core\CNabuEngine;

/**
 * Class to manage lists of Module Morphs.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\module
 */
class CNabuModuleMorphList extends CNabuDataObjectList
{
    public function __construct()
    {
        parent::__construct('nb_module_morph_id');
    }
    
    /**
     * Acquires an instance of class CNabuModuleMorph from the database.
     * @param string $key Id of the instance to unserialize.
     * @param string $index Secondary index to be used if needed.
     * @return mixed Returns the unserialized instance if exists or false if not.
     */
    protected function acquireItem($key, $index = false)
    {
        $retval = false;

        $nb_engine = CNabuEngine::getEngine();
        if ($nb_engine->isMainDBAvailable()) {
            $item = new CNabuModuleMorph($key);
            if ($item->isFetched()) {
                $retval = $item;
            }
        }

        return $retval;
    }

    protected function createSecondaryIndexes()
    {
    }
}
