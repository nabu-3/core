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
use nabu\data\security\base\CNabuUserGroupMemberBase;
use nabu\data\security\traits\TNabuUserChild;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\security
 */
class CNabuUserGroupMember extends CNabuUserGroupMemberBase
{
    use TNabuUserChild;

    /**
     * Gets all Members of a Group.
     * @param mixed $nb_user_group A valid ID, an object containing a field called nb_user_group_id or a User Group instance.
     * @return CNabuUserGroupMemberList Returns a list with available members.
     */
    public static function getMembersOfGroup($nb_user_group) : CNabuUserGroupMemberList
    {
        if (is_numeric($nb_user_group_id = nb_getMixedValue($nb_user_group, 'nb_user_group_id'))) {
            $retval = CNabuUserGroupMember::buildObjectListFromSQL(
                'nb_user_id',
                "select ugm.* "
                . "from nb_user_group_member ugm, nb_user_group ug "
               . "where ugm.nb_user_group_id=ug.nb_user_group_id "
                 . "and ugm.nb_user_group_id=%group_id\$d",
                array(
                    'group_id' => $nb_user_group_id
                )
            );
        } else {
            $retval = new CNabuUserGroupMemberList();
        }

        return $retval;
    }

    public function getTreeData($nb_language = null, $dataonly = false)
    {
        $trdata = parent::getTreeData($nb_language, $dataonly);

        $trdata['user'] = $this->getUser();

        return $trdata;
    }
}
