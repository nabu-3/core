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
}
