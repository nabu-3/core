<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2019/04/04 14:15:45 UTC
 * ===========================================================================
 * Copyright 2009-2011 Rafael Gutierrez Martinez
 * Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 * Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 * Copyright 2017-2019 nabu-3 Group
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

namespace nabu\data\cluster\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\CNabuDataObject;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Cluster User stored in the storage named nb_cluster_user.
 * @version 3.0.12 Surface
 * @package \nabu\data\cluster\base
 */
abstract class CNabuClusterUserBase extends CNabuDBInternalObject
{
    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_cluster_user An instance of CNabuClusterUserBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_cluster_user_id, or a valid ID.
     */
    public function __construct($nb_cluster_user = false)
    {
        if ($nb_cluster_user) {
            $this->transferMixedValue($nb_cluster_user, 'nb_cluster_user_id');
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
        return 'nb_cluster_user';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_cluster_user_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_cluster_user '
                   . "where nb_cluster_user_id=%nb_cluster_user_id\$d "
              )
            : null;
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_cluster_user_id' is the index, and each
     * value is an instance of class CNabuClusterUserBase.
     * @return mixed Returns and array with all items.
     */
    public static function getAllClusterUsers()
    {
        return forward_static_call(
                array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_cluster_user_id',
                'select * from nb_cluster_user'
        );
    }

    /**
     * Gets a filtered list of Cluster User instances represented as an array. Params allows the capability of select a
     * subset of fields, order by concrete fields, or truncate the list by a number of rows starting in an offset.
     * @throws \nabu\core\exceptions\ENabuCoreException Raises an exception if $fields or $order have invalid values.
     * @param string $q Query string to filter results using a context index.
     * @param string|array $fields List of fields to put in the results.
     * @param string|array $order List of fields to order the results. Each field can be suffixed with "ASC" or "DESC"
     * to determine the short order
     * @param int $offset Offset of first row in the results having the first row at offset 0.
     * @param int $num_items Number of continue rows to get as maximum in the results.
     * @return array Returns an array with all rows found using the criteria.
     */
    public static function getFilteredClusterUserList($q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $fields_part = nb_prefixFieldList(CNabuClusterUserBase::getStorageName(), $fields, false, true, '`');
        $order_part = nb_prefixFieldList(CNabuClusterUserBase::getStorageName(), $fields, false, false, '`');
        
        if ($num_items !== 0) {
            $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
        } else {
            $limit_part = false;
        }
        
        $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
            "select " . ($fields_part ? $fields_part . ' ' : '* ')
            . 'from nb_cluster_user '
            . ($order_part ? "order by $order_part " : '')
            . ($limit_part ? "limit $limit_part" : ''),
            array(
            )
        );
        
        return $nb_item_list;
    }

    /**
     * Get Cluster User Id attribute value
     * @return int Returns the Cluster User Id value
     */
    public function getId() : int
    {
        return $this->getValue('nb_cluster_user_id');
    }

    /**
     * Sets the Cluster User Id attribute value.
     * @param int $id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setId(int $id) : CNabuDataObject
    {
        if ($id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$id")
            );
        }
        $this->setValue('nb_cluster_user_id', $id);
        
        return $this;
    }

    /**
     * Get Cluster User Group Id attribute value
     * @return int Returns the Cluster User Group Id value
     */
    public function getGroupId() : int
    {
        return $this->getValue('nb_cluster_user_group_id');
    }

    /**
     * Sets the Cluster User Group Id attribute value.
     * @param int $group_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setGroupId(int $group_id) : CNabuDataObject
    {
        if ($group_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$group_id")
            );
        }
        $this->setValue('nb_cluster_user_group_id', $group_id);
        
        return $this;
    }

    /**
     * Get Cluster User OS Id attribute value
     * @return null|int Returns the Cluster User OS Id value
     */
    public function getOSId()
    {
        return $this->getValue('nb_cluster_user_os_id');
    }

    /**
     * Sets the Cluster User OS Id attribute value.
     * @param int|null $os_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setOSId(int $os_id = null) : CNabuDataObject
    {
        $this->setValue('nb_cluster_user_os_id', $os_id);
        
        return $this;
    }

    /**
     * Get Cluster User OS Nick attribute value
     * @return string Returns the Cluster User OS Nick value
     */
    public function getOSNick() : string
    {
        return $this->getValue('nb_cluster_user_os_nick');
    }

    /**
     * Sets the Cluster User OS Nick attribute value.
     * @param string $os_nick New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setOSNick(string $os_nick) : CNabuDataObject
    {
        if ($os_nick === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$os_nick")
            );
        }
        $this->setValue('nb_cluster_user_os_nick', $os_nick);
        
        return $this;
    }
}
