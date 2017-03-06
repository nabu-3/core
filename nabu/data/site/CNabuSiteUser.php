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
use nabu\core\exceptions\ENabuCoreException;
use nabu\data\security\CNabuRole;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site
 */
class CNabuSiteUser extends CNabuSiteUserBase
{
    /**
     * Role instance
     * @var CNabuRole
     */
    //private $nb_role = null;

    /*
    public function getRole($force = false)
    {
        if ($this->nb_role === null || $force) {
            $this->nb_role = null;
            if ($this->isValueNumeric('nb_role_id')) {
                $nb_role = new CNabuRole($this);
                if ($nb_role->isFetched()) {
                    $this->nb_role = $nb_role;
                }
            }
        }

        return $this->nb_role;
    }

    public function setRole(CNabuRole $nb_role)
    {
        if ($this->isValueNumeric('nb_role_id') && $this->matchValue($nb_role, 'nb_role_id')) {
            $this->nb_role = $nb_role;
        } elseif (!$this->contains('nb_role_id')) {
            $this->nb_role = $nb_role;
            $this->transferValue($nb_role, 'nb_role_id');
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_ROLE_NOT_VALID);
        }

        return $this;
    }
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
