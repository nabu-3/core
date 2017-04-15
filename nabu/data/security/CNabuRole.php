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

use \nabu\data\security\base\CNabuRoleBase;
use nabu\data\site\CNabuSite;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\security
 */
class CNabuRole extends CNabuRoleBase
{
    /**
     * Check if the Role is root.
     * @return bool Returns true if is root.
     */
    public function isRoot() : bool
    {
        return $this->isValueEqualThan('nb_role_root', 'T');
    }

    /**
     * Get the Role List of Roles assigned to a Site.
     * @param mixed $nb_site A Site instance, an object containing a field name nb_site_id or a Site Id.
     * @return CNabuRoleList Returns the list of roles found or an empty list if none.
     */
    static public function getRolesForSite($nb_site) : CNabuRoleList
    {
        $nb_site_id = nb_getMixedValue($nb_site, NABU_SITE_FIELD_ID);

        if (is_numeric($nb_site_id)) {
            $retval = self::buildObjectListFromSQL(
                'nb_role_id',
                'select r.* '
                . 'from nb_role r, nb_site_role sr, nb_site s '
               . 'where r.nb_role_id=sr.nb_role_id '
                 . 'and sr.nb_site_id=s.nb_site_id '
                 . 'and s.nb_site_id=%site_id$d',
                array(
                    'site_id' => $nb_site_id
                ),
                ($nb_site instanceof CNabuSite ? $nb_site : null)
            );
        } else {
            $retval = new CNabuRoleList();
        }

        return $retval;
    }
}
