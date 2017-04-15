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

namespace nabu\data\cluster;

use \nabu\data\cluster\base\CNabuClusterUserBase;

/**
 * Class to extend management of table nb_cluster_user where all Cluster Users are stored.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 */
class CNabuClusterUser extends CNabuClusterUserBase
{
    /**
     *
     * @var CNabuClusterUserGroup
     */
    private $nb_cluster_user_group = null;
    
    public function getGroup($force = false)
    {
        if ($this->nb_cluster_user_group === null || $force) {
            $this->nb_cluster_user_group = null;
            if ($this->isValueNumeric('nb_cluster_user_group_id')) {
                $nb_cluster_user_group = new CNabuClusterUserGroup($this);
                if ($nb_cluster_user_group->isFetched()) {
                    $this->nb_cluster_user_group = $nb_cluster_user_group;
                }
            }
        }
        
        return $this->nb_cluster_user_group;
    }
}
