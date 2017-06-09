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

use \nabu\data\site\base\CNabuSiteUserBase;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site
 */
class CNabuSiteUser extends CNabuSiteUserBase
{
    /**
     * Upate table to set las access datetime of the user.
     */
    public function logAccess()
    {
        $this->db->executeUpdate(
                "update nb_site_user "
                 . "set nb_site_user_last_login_datetime=now() "
               . "where nb_site_id=%site_id\$d "
                 . "and nb_role_id=%role_id\$d "
                 . "and nb_user_id=%user_id\$d",
                array(
                    'site_id' => $this->getValue('nb_site_id'),
                    'role_id' => $this->getValue('nb_role_id'),
                    'user_id' => $this->getValue('nb_user_id')
                )
        );
    }
}
