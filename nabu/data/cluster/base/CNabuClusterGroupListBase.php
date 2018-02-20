<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2018/02/20 09:38:49 UTC
 * ===========================================================================
 * Copyright 2009-2011 Rafael Gutierrez Martinez
 * Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 * Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 * Copyright 2017-2018 nabu-3 Group
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace nabu\data\cluster\base;

use \nabu\core\CNabuEngine;
use \nabu\data\cluster\CNabuClusterGroup;
use \nabu\data\CNabuDataObjectList;
use \nabu\data\CNabuDataObjectListIndex;

/**
 * Class to manage a list of Cluster Group instances.
 * @author Rafael Gutiérrez Martínez <rgutierrez@nabu-3.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\cluster\base
 */
abstract class CNabuClusterGroupListBase extends CNabuDataObjectList
{
    /**
     * Index the list using the nb_cluster_group_key field.
     * @var string
     */
    const INDEX_KEY = "keys";

    /**
     * Instantiates the class.
     */
    public function __construct()
    {
        parent::__construct('nb_cluster_group_id');
    }

    /**
     * Creates alternate indexes for this list.
     */
    protected function createSecondaryIndexes()
    {
        $this->addIndex(
            new CNabuDataObjectListIndex($this, 'nb_cluster_group_key', 'nb_cluster_group_key', self::INDEX_KEY)
        );
    }

    /**
     * Acquires an instance of class CNabuClusterGroup from the database.
     * @param string $key Id or reference field in the instance to acquire.
     * @param string $index Secondary index to be used if needed.
     * @return mixed Returns the unserialized instance if exists or false if not.
     */
    public function acquireItem($key, $index = false)
    {
        $retval = false;
        
        if ($index === false && CNabuEngine::getEngine()->isMainDBAvailable()) {
            $item = new CNabuClusterGroup($key);
            if ($item->isFetched()) {
                $retval = $item;
            }
        }
        
        return $retval;
    }
}
