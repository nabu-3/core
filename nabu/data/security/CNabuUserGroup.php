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

namespace nabu\data\security;
use nabu\data\customer\CNabuCustomer;
use nabu\data\security\base\CNabuUserGroupBase;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\security
 */
class CNabuUserGroup extends CNabuUserGroupBase
{
    /** @var CNabuUser $nb_user User owner instance of this group. */
    private $nb_user = null;
    /** @var CNabuUserGroupMemberList $nb_user_group_member_list List of members. */
    private $nb_user_group_member_list = null;

    public function __construct($nb_user_group = false)
    {
        parent::__construct($nb_user_group);

        $this->nb_user_group_member_list = new CNabuUserGroupMemberList();
    }

    /**
     * Get all User Groups instances of a Type, where a User is member (without consider his status).
     * @param mixed $nb_customer A CNabuDataObject instance containing a field named nb_customer_id or a valid Id.
     * @param mixed $nb_user_group_type A CNabuDataObject instance containing a field named nb_user_group_type_id
     * or a valid Id.
     * @param mixed $nb_user_group_member A CNabuDataObject instance containing a field named nb_user_id or a valid Id.
     * @return CNabuUserGroupList Returns a list with all User Group instances found.
     */
    public static function getGroupsWithMember($nb_customer, $nb_user_group_type, $nb_user_group_member) : CNabuUserGroupList
    {
        if (is_numeric($nb_customer_id = nb_getMixedValue($nb_customer, NABU_CUSTOMER_FIELD_ID)) &&
            is_numeric($nb_user_group_type_id = nb_getMixedValue($nb_user_group_type, NABU_USER_GROUP_TYPE_FIELD_ID)) &&
            is_numeric($nb_user_id = nb_getMixedValue($nb_user_group_member, NABU_USER_FIELD_ID))
        ) {
            $retval = CNabuUserGroup::buildObjectListFromSQL(
                'nb_user_group_id',
                'SELECT ug.*
                   FROM nb_customer c, nb_user_group_type ugt, nb_user_group ug, nb_user_group_member ugm
                  WHERE c.nb_customer_id=ug.nb_customer_id
                    AND c.nb_customer_id=ugt.nb_customer_id
                    AND ug.nb_user_group_id=ugm.nb_user_group_id
                    AND c.nb_customer_id=%cust_id$d
                    AND ugt.nb_user_group_type_id=%type_id$d
                    AND ugm.nb_user_id=%user_id$d',
                    array(
                        'cust_id' => $nb_customer_id,
                        'type_id' => $nb_user_group_type_id,
                        'user_id' => $nb_user_id
                    ),
                ($nb_user_group_member instanceof CNabuUser ? $nb_user : null)
            );
        } else {
            $retval = new CNabuUserGroupList();
        }

        return $retval;
    }

    public function getOwner(bool $force = false)
    {
        if ($this->nb_user === null || $this->getUserId() !== $this->nb_user->getId() || $force) {
            $this->nb_user = null;
            if (($nb_customer = $this->getCustomer()) instanceof CNabuCustomer) {
                $this->nb_user = $nb_customer->getUser($this->getUserId());
            }
        }

        return $this->nb_user;
    }

    /**
     * Returns the full list of members in the group.
     * @param bool $force If true, then force to reload from database the full list.
     * @return CNabuUserGroupMemberList Return a list with members found.
     */
    public function getMembers($force = false)
    {
        if ($this->nb_user_group_member_list->isEmpty() || $force) {
            $this->nb_user_group_member_list->clear();
            if ($this->isValueNumeric('nb_user_group_id')) {
                $this->nb_user_group_member_list->merge(CNabuUserGroupMember::getMembersOfGroup($this));
            }
        }

        return $this->nb_user_group_member_list;
    }

    public function expandMembersAsUsers()
    {
        $this->nb_user_group_member_list->expandMembersAsUsers();
    }

    /**
     * Returns the full list of admin members in the group.
     * @param bool $force If true, then force to reload from database the full list.
     * @return CNabuUserGroupMemberList Return an list with all members found.
     */
    public function getAdminMembers($force = false) : CNabuUserGroupMemberList
    {
        $this->getMembers($force);
        $nb_admin_list = new CNabuUserGroupMemberList();

        $this->nb_user_group_member_list->iterate(
            function($key, CNabuUserGroupMember $nb_user_group_member) use ($nb_admin_list)
            {
                if ($nb_user_group_member->getAdmin() === 'T' && $nb_user_group_member->getStatus() === 'E') {
                    $nb_admin_list->addItem($nb_user_group_member);
                }
                return true;
            }
        );

        return $nb_admin_list;
    }

    public function getTreeData($nb_language = null, $dataonly = false)
    {
        $trdata = parent::getTreeData($nb_language, $dataonly);

        $trdata['owner'] = $this->getOwner();
        $trdata['members'] = $this->getMembers();

        return $trdata;
    }

    public function refresh(bool $force = false, bool $cascade = false): bool
    {
        return parent::refresh($force, $cascade) &&
               (!$cascade ||
                    (
                        $this->getOwner($force) !== false &&
                        $this->getMembers($force) !== false
                    )
               )
        ;
    }

    public function delete() : bool
    {
        $this->getMembers(true)->iterate(
            function($key, CNabuUserGroupMember $nb_member) {
                $nb_member->delete();
                return true;
            }
        );

        return parent::delete();
    }
}
