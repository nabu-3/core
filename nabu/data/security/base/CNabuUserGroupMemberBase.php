<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2018/02/20 16:43:41 UTC
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

namespace nabu\data\security\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\CNabuDataObject;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity User Group Member stored in the storage named nb_user_group_member.
 * @version 3.0.12 Surface
 * @package \nabu\data\security\base
 */
abstract class CNabuUserGroupMemberBase extends CNabuDBInternalObject
{
    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_user_group An instance of CNabuUserGroupMemberBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_user_group_id, or a valid ID.
     * @param mixed $nb_user An instance of CNabuUserGroupMemberBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_user_id, or a valid ID.
     */
    public function __construct($nb_user_group = false, $nb_user = false)
    {
        if ($nb_user_group) {
            $this->transferMixedValue($nb_user_group, 'nb_user_group_id');
        }
        
        if ($nb_user) {
            $this->transferMixedValue($nb_user, 'nb_user_id');
        }
        
        parent::__construct();
    }

    /**
     * Get the file name and path where is stored the descriptor in JSON format.
     * @return string Return the file name with the full path
     */
    public static function getStorageDescriptorPath()
    {
        return preg_replace('/.php$/', '.json', __FILE__);
    }

    /**
     * Get the table name represented by this class
     * @return string Return the table name
     */
    public static function getStorageName()
    {
        return 'nb_user_group_member';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_user_group_id') && $this->isValueNumeric('nb_user_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_user_group_member '
                   . "where nb_user_group_id=%nb_user_group_id\$d "
                     . "and nb_user_id=%nb_user_id\$d "
              )
            : null;
    }

    /**
     * Gets a filtered list of User Group Member instances represented as an array. Params allows the capability of
     * select a subset of fields, order by concrete fields, or truncate the list by a number of rows starting in an
     * offset.
     * @throws \nabu\core\exceptions\ENabuCoreException Raises an exception if $fields or $order have invalid values.
     * @param string $q Query string to filter results using a context index.
     * @param string|array $fields List of fields to put in the results.
     * @param string|array $order List of fields to order the results. Each field can be suffixed with "ASC" or "DESC"
     * to determine the short order
     * @param int $offset Offset of first row in the results having the first row at offset 0.
     * @param int $num_items Number of continue rows to get as maximum in the results.
     * @return array Returns an array with all rows found using the criteria.
     */
    public static function getFilteredUserGroupMemberList($q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $fields_part = nb_prefixFieldList(CNabuUserGroupMemberBase::getStorageName(), $fields, false, true, '`');
        $order_part = nb_prefixFieldList(CNabuUserGroupMemberBase::getStorageName(), $fields, false, false, '`');
        
        if ($num_items !== 0) {
            $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
        } else {
            $limit_part = false;
        }
        
        $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
            "select " . ($fields_part ? $fields_part . ' ' : '* ')
            . 'from nb_user_group_member '
            . ($order_part ? "order by $order_part " : '')
            . ($limit_part ? "limit $limit_part" : ''),
            array(
            )
        );
        
        return $nb_item_list;
    }

    /**
     * Get User Group Id attribute value
     * @return int Returns the User Group Id value
     */
    public function getUserGroupId() : int
    {
        return $this->getValue('nb_user_group_id');
    }

    /**
     * Sets the User Group Id attribute value.
     * @param int $nb_user_group_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setUserGroupId(int $nb_user_group_id) : CNabuDataObject
    {
        if ($nb_user_group_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_user_group_id")
            );
        }
        $this->setValue('nb_user_group_id', $nb_user_group_id);
        
        return $this;
    }

    /**
     * Get User Id attribute value
     * @return int Returns the User Id value
     */
    public function getUserId() : int
    {
        return $this->getValue('nb_user_id');
    }

    /**
     * Sets the User Id attribute value.
     * @param int $nb_user_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setUserId(int $nb_user_id) : CNabuDataObject
    {
        if ($nb_user_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_user_id")
            );
        }
        $this->setValue('nb_user_id', $nb_user_id);
        
        return $this;
    }

    /**
     * Get User Group Member Creation Datetime attribute value
     * @return mixed Returns the User Group Member Creation Datetime value
     */
    public function getCreationDatetime()
    {
        return $this->getValue('nb_user_group_member_creation_datetime');
    }

    /**
     * Sets the User Group Member Creation Datetime attribute value.
     * @param mixed $creation_datetime New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCreationDatetime($creation_datetime) : CNabuDataObject
    {
        if ($creation_datetime === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$creation_datetime")
            );
        }
        $this->setValue('nb_user_group_member_creation_datetime', $creation_datetime);
        
        return $this;
    }

    /**
     * Get User Group Member Invitation Datetime attribute value
     * @return mixed Returns the User Group Member Invitation Datetime value
     */
    public function getInvitationDatetime()
    {
        return $this->getValue('nb_user_group_member_invitation_datetime');
    }

    /**
     * Sets the User Group Member Invitation Datetime attribute value.
     * @param mixed $invitation_datetime New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setInvitationDatetime($invitation_datetime) : CNabuDataObject
    {
        $this->setValue('nb_user_group_member_invitation_datetime', $invitation_datetime);
        
        return $this;
    }

    /**
     * Get User Group Member Accept Datetime attribute value
     * @return mixed Returns the User Group Member Accept Datetime value
     */
    public function getAcceptDatetime()
    {
        return $this->getValue('nb_user_group_member_accept_datetime');
    }

    /**
     * Sets the User Group Member Accept Datetime attribute value.
     * @param mixed $accept_datetime New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setAcceptDatetime($accept_datetime) : CNabuDataObject
    {
        $this->setValue('nb_user_group_member_accept_datetime', $accept_datetime);
        
        return $this;
    }

    /**
     * Get User Group Member Disabled Datetime attribute value
     * @return mixed Returns the User Group Member Disabled Datetime value
     */
    public function getDisabledDatetime()
    {
        return $this->getValue('nb_user_group_member_disabled_datetime');
    }

    /**
     * Sets the User Group Member Disabled Datetime attribute value.
     * @param mixed $disabled_datetime New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setDisabledDatetime($disabled_datetime) : CNabuDataObject
    {
        $this->setValue('nb_user_group_member_disabled_datetime', $disabled_datetime);
        
        return $this;
    }

    /**
     * Get User Group Member New attribute value
     * @return string Returns the User Group Member New value
     */
    public function getNew() : string
    {
        return $this->getValue('nb_user_group_member_new');
    }

    /**
     * Sets the User Group Member New attribute value.
     * @param string $new New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setNew(string $new = "T") : CNabuDataObject
    {
        if ($new === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$new")
            );
        }
        $this->setValue('nb_user_group_member_new', $new);
        
        return $this;
    }

    /**
     * Get User Group Member Admin attribute value
     * @return string Returns the User Group Member Admin value
     */
    public function getAdmin() : string
    {
        return $this->getValue('nb_user_group_member_admin');
    }

    /**
     * Sets the User Group Member Admin attribute value.
     * @param string $admin New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setAdmin(string $admin = "F") : CNabuDataObject
    {
        if ($admin === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$admin")
            );
        }
        $this->setValue('nb_user_group_member_admin', $admin);
        
        return $this;
    }

    /**
     * Get User Group Member Status attribute value
     * @return string Returns the User Group Member Status value
     */
    public function getStatus() : string
    {
        return $this->getValue('nb_user_group_member_status');
    }

    /**
     * Sets the User Group Member Status attribute value.
     * @param string $status New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setStatus(string $status = "D") : CNabuDataObject
    {
        if ($status === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$status")
            );
        }
        $this->setValue('nb_user_group_member_status', $status);
        
        return $this;
    }
}
