<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/05/23 16:32:45 UTC
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
use \nabu\data\CNabuDataObject;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity ServerHost stored in the storage named nb_server_host.
 * @version 3.0.12 Surface
 * @package \nabu\data\cluster\base
 */
abstract class CNabuServerHostBase extends CNabuDBInternalObject
{
    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_server_host An instance of CNabuServerHostBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_server_host_id, or a valid ID.
     */
    public function __construct($nb_server_host = false)
    {
        if ($nb_server_host) {
            $this->transferMixedValue($nb_server_host, 'nb_server_host_id');
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
        return 'nb_server_host';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_server_host_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_server_host '
                   . "where nb_server_host_id=%nb_server_host_id\$d "
              )
            : null;
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_server_host_id' is the index, and each
     * value is an instance of class CNabuServerHostBase.
     * @return mixed Returns and array with all items.
     */
    public static function getAllServerHosts()
    {
        return forward_static_call(
                array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_server_host_id',
                'select * from nb_server_host'
        );
    }

    /**
     * Gets a filtered list of ServerHost instances represented as an array. Params allows the capability of select a
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
    public static function getFilteredServerHostList($q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $fields_part = nb_prefixFieldList(CNabuServerHostBase::getStorageName(), $fields, false, true, '`');
        $order_part = nb_prefixFieldList(CNabuServerHostBase::getStorageName(), $fields, false, false, '`');
        
        if ($num_items !== 0) {
            $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
        } else {
            $limit_part = false;
        }
        
        $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
            "select " . ($fields_part ? $fields_part . ' ' : '* ')
            . 'from nb_server_host '
            . ($order_part ? "order by $order_part " : '')
            . ($limit_part ? "limit $limit_part" : ''),
            array(
            )
        );
        
        return $nb_item_list;
    }

    /**
     * Get Server Host Id attribute value
     * @return int Returns the Server Host Id value
     */
    public function getId() : int
    {
        return $this->getValue('nb_server_host_id');
    }

    /**
     * Sets the Server Host Id attribute value.
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
        $this->setValue('nb_server_host_id', $id);
        
        return $this;
    }

    /**
     * Get Server Id attribute value
     * @return int Returns the Server Id value
     */
    public function getServerId() : int
    {
        return $this->getValue('nb_server_id');
    }

    /**
     * Sets the Server Id attribute value.
     * @param int $nb_server_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setServerId(int $nb_server_id) : CNabuDataObject
    {
        if ($nb_server_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_server_id")
            );
        }
        $this->setValue('nb_server_id', $nb_server_id);
        
        return $this;
    }

    /**
     * Get IP Id attribute value
     * @return int Returns the IP Id value
     */
    public function getIPId() : int
    {
        return $this->getValue('nb_ip_id');
    }

    /**
     * Sets the IP Id attribute value.
     * @param int $nb_ip_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setIPId(int $nb_ip_id) : CNabuDataObject
    {
        if ($nb_ip_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_ip_id")
            );
        }
        $this->setValue('nb_ip_id', $nb_ip_id);
        
        return $this;
    }

    /**
     * Get Cluster Group Id attribute value
     * @return int Returns the Cluster Group Id value
     */
    public function getClusterGroupId() : int
    {
        return $this->getValue('nb_cluster_group_id');
    }

    /**
     * Sets the Cluster Group Id attribute value.
     * @param int $nb_cluster_group_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setClusterGroupId(int $nb_cluster_group_id) : CNabuDataObject
    {
        if ($nb_cluster_group_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_cluster_group_id")
            );
        }
        $this->setValue('nb_cluster_group_id', $nb_cluster_group_id);
        
        return $this;
    }

    /**
     * Get Cluster Group Service Id attribute value
     * @return int Returns the Cluster Group Service Id value
     */
    public function getClusterGroupServiceId() : int
    {
        return $this->getValue('nb_cluster_group_service_id');
    }

    /**
     * Sets the Cluster Group Service Id attribute value.
     * @param int $nb_cluster_group_service_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setClusterGroupServiceId(int $nb_cluster_group_service_id) : CNabuDataObject
    {
        if ($nb_cluster_group_service_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_cluster_group_service_id")
            );
        }
        $this->setValue('nb_cluster_group_service_id', $nb_cluster_group_service_id);
        
        return $this;
    }

    /**
     * Get Server Host Port attribute value
     * @return int Returns the Server Host Port value
     */
    public function getPort() : int
    {
        return $this->getValue('nb_server_host_port');
    }

    /**
     * Sets the Server Host Port attribute value.
     * @param int $port New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setPort(int $port = 80) : CNabuDataObject
    {
        if ($port === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$port")
            );
        }
        $this->setValue('nb_server_host_port', $port);
        
        return $this;
    }

    /**
     * Get Server Host Name attribute value
     * @return null|string Returns the Server Host Name value
     */
    public function getName()
    {
        return $this->getValue('nb_server_host_name');
    }

    /**
     * Sets the Server Host Name attribute value.
     * @param null|string $name New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setName(string $name = null) : CNabuDataObject
    {
        $this->setValue('nb_server_host_name', $name);
        
        return $this;
    }

    /**
     * Get Server Host Notes attribute value
     * @return null|string Returns the Server Host Notes value
     */
    public function getNotes()
    {
        return $this->getValue('nb_server_host_notes');
    }

    /**
     * Sets the Server Host Notes attribute value.
     * @param null|string $notes New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setNotes(string $notes = null) : CNabuDataObject
    {
        $this->setValue('nb_server_host_notes', $notes);
        
        return $this;
    }

    /**
     * Get Server Host Status attribute value
     * @return mixed Returns the Server Host Status value
     */
    public function getStatus()
    {
        return $this->getValue('nb_server_host_status');
    }

    /**
     * Sets the Server Host Status attribute value.
     * @param mixed $status New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setStatus($status) : CNabuDataObject
    {
        $this->setValue('nb_server_host_status', $status);
        
        return $this;
    }
}
