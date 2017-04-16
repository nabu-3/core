<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/04/16 22:45:06 UTC
 * ===========================================================================
 * Copyright 2009-2011 Rafael Gutierrez Martinez
 * Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 * Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 * Copyright 2017 nabu-3 Group
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
use \nabu\data\cluster\CNabuServer;
use \nabu\data\CNabuDataObject;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Server stored in the storage named nb_server.
 * @author Rafael Gutiérrez Martínez <rgutierrez@nabu-3.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\cluster\base
 */
abstract class CNabuServerBase extends CNabuDBInternalObject
{
    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_server An instance of CNabuServerBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_server_id, or a valid ID.
     */
    public function __construct($nb_server = false)
    {
        if ($nb_server) {
            $this->transferMixedValue($nb_server, 'nb_server_id');
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
        return 'nb_server';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_server_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_server '
                   . "where nb_server_id=%nb_server_id\$d "
              )
            : null;
    }

    /**
     * Find an instance identified by nb_server_key field.
     * @param string $key Key to search
     * @return CNabuServer Returns a valid instance if exists or null if not.
     */
    public static function findByKey($key)
    {
        return CNabuServer::buildObjectFromSQL(
                'select * '
                . 'from nb_server '
               . "where nb_server_key='%key\$s'",
                array(
                    'key' => $key
                )
        );
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_server_id' is the index, and each value
     * is an instance of class CNabuServerBase.
     * @return mixed Returns and array with all items.
     */
    public static function getAllServers()
    {
        return forward_static_call(
                array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_server_id',
                'select * from nb_server'
        );
    }

    /**
     * Gets a filtered list of Server instances represented as an array. Params allows the capability of select a
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
    public static function getFilteredServerList($q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $fields_part = nb_prefixFieldList(CNabuServerBase::getStorageName(), $fields, false, true, '`');
        $order_part = nb_prefixFieldList(CNabuServerBase::getStorageName(), $fields, false, false, '`');
        
        if ($num_items !== 0) {
            $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
        } else {
            $limit_part = false;
        }
        
        $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
            "select " . ($fields_part ? $fields_part . ' ' : '* ')
            . 'from nb_server '
            . ($order_part ? "order by $order_part " : '')
            . ($limit_part ? "limit $limit_part" : ''),
            array(
            )
        );
        
        return $nb_item_list;
    }

    /**
     * Get Server Id attribute value
     * @return int Returns the Server Id value
     */
    public function getId() : int
    {
        return $this->getValue('nb_server_id');
    }

    /**
     * Sets the Server Id attribute value.
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
        $this->setValue('nb_server_id', $id);
        
        return $this;
    }

    /**
     * Get Server Admin User Id attribute value
     * @return null|int Returns the Server Admin User Id value
     */
    public function getAdminUserId()
    {
        return $this->getValue('nb_server_admin_user_id');
    }

    /**
     * Sets the Server Admin User Id attribute value.
     * @param null|int $admin_user_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setAdminUserId(int $admin_user_id = null) : CNabuDataObject
    {
        $this->setValue('nb_server_admin_user_id', $admin_user_id);
        
        return $this;
    }

    /**
     * Get Cluster User Group Id attribute value
     * @return null|int Returns the Cluster User Group Id value
     */
    public function getClusterUserGroupId()
    {
        return $this->getValue('nb_cluster_user_group_id');
    }

    /**
     * Sets the Cluster User Group Id attribute value.
     * @param null|int $nb_cluster_user_group_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setClusterUserGroupId(int $nb_cluster_user_group_id = null) : CNabuDataObject
    {
        $this->setValue('nb_cluster_user_group_id', $nb_cluster_user_group_id);
        
        return $this;
    }

    /**
     * Get Server Key attribute value
     * @return null|string Returns the Server Key value
     */
    public function getKey()
    {
        return $this->getValue('nb_server_key');
    }

    /**
     * Sets the Server Key attribute value.
     * @param null|string $key New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setKey(string $key = null) : CNabuDataObject
    {
        $this->setValue('nb_server_key', $key);
        
        return $this;
    }

    /**
     * Get Server Name attribute value
     * @return null|string Returns the Server Name value
     */
    public function getName()
    {
        return $this->getValue('nb_server_name');
    }

    /**
     * Sets the Server Name attribute value.
     * @param null|string $name New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setName(string $name = null) : CNabuDataObject
    {
        $this->setValue('nb_server_name', $name);
        
        return $this;
    }

    /**
     * Get Server Notes attribute value
     * @return null|string Returns the Server Notes value
     */
    public function getNotes()
    {
        return $this->getValue('nb_server_notes');
    }

    /**
     * Sets the Server Notes attribute value.
     * @param null|string $notes New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setNotes(string $notes = null) : CNabuDataObject
    {
        $this->setValue('nb_server_notes', $notes);
        
        return $this;
    }
}
