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

namespace nabu\data\site;
use nabu\core\CNabuEngine;
use nabu\data\site\base\CNabuSiteMapRoleBase;
use nabu\data\site\traits\TNabuSiteRoleMask;
use nabu\data\site\traits\TNabuSiteMapChild;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site
 */
class CNabuSiteMapRole extends CNabuSiteMapRoleBase
{
    use TNabuSiteMapChild;
    use TNabuSiteRoleMask;

    /**
     * Get all roles to be applied to all maps of a Site.
     * @param mixed $nb_site Site to retrieve Site Map Roles.
     * @return CNabuSiteMapRoleList Returns a Site Map Role List with roles. If no roles the list is empty.
     */
    static public function getMapRolesForSite($nb_site)
    {
        $nb_site_id = nb_getMixedValue($nb_site, NABU_SITE_FIELD_ID);
        if (is_numeric($nb_site_id)) {
            $retval = CNabuEngine::getEngine()
                    ->getMainDB()
                    ->getQueryAsObjectArray(
                        '\nabu\data\site\CNabuSiteMapRole', null,
                        'select smr.* '
                        . 'from nb_site_map sm, nb_site_map_role smr '
                       . 'where sm.nb_site_map_id=smr.nb_site_map_id '
                         . 'and sm.nb_site_id=%site_id$d '
                       . 'order by sm.nb_site_map_order, smr.nb_role_id',
                        array(
                            'site_id' => $nb_site_id
                        ), null, true
                    )
            ;
        } else {
            $retval = null;
        }

        return $retval;
    }
}
