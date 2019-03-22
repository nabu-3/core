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

namespace nabu\data\security;
use nabu\data\security\base\CNabuRoleListBase;

/**
 * Class to manage a Role List.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\security
 */
class CNabuRoleList extends CNabuRoleListBase
{
    /**
     * Clear the list and fill it from Roles assigned to a Site.
     * @param mixed $nb_site A Site instance, an object containing a field name nb_site_id or a Site Id.
     * @return int Returns the number of Roles found.
     */
    public function fillFromSite($nb_site) : int
    {
        $this->clear();
        $this->merge(CNabuRole::getRolesForSite($nb_site));
        
        return $this->getSize();
    }
}
