<?php
/* ===========================================================================
 * File generated automatically by Nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/03/08 12:49:14 UTC
 * ===========================================================================
 * Copyright 2009-2011 Rafael Gutierrez Martinez
 * Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 * Copyright 2014-2017 Where Ideas Simply Come True, S.L.
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

namespace nabu\data\messaging\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\messaging\CNabuMessaging;
use \nabu\data\messaging\CNabuMessagingService;
use \nabu\data\messaging\CNabuMessagingServiceList;
use \nabu\data\messaging\traits\TNabuMessagingChild;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Messaging Service stored in the storage named nb_messaging_service.
 * @author Rafael Gutiérrez Martínez <rgutierrez@wiscot.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\messaging\base
 */
abstract class CNabuMessagingServiceBase extends CNabuDBInternalObject
{
    use TNabuMessagingChild;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_messaging_service An instance of CNabuMessagingServiceBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_messaging_service_id, or a valid ID.
     */
    public function __construct($nb_messaging_service = false)
    {
        if ($nb_messaging_service) {
            $this->transferMixedValue($nb_messaging_service, 'nb_messaging_service_id');
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
        return 'nb_messaging_service';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_messaging_service_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_messaging_service '
                   . "where nb_messaging_service_id=%nb_messaging_service_id\$d "
              )
            : null;
    }

    /**
     * Find an instance identified by nb_messaging_service_key field.
     * @param mixed $nb_messaging Messaging that owns Messaging Service
     * @param string $key Key to search
     * @return CNabuMessagingService Returns a valid instance if exists or null if not.
     */
    public static function findByKey($nb_messaging, $key)
    {
        $nb_messaging_id = nb_getMixedValue($nb_messaging, 'nb_messaging_id');
        if (is_numeric($nb_messaging_id)) {
            $retval = CNabuMessagingService::buildObjectFromSQL(
                    'select * '
                    . 'from nb_messaging_service '
                   . 'where nb_messaging_id=%messaging_id$d '
                     . "and nb_messaging_service_key='%key\$s'",
                    array(
                        'messaging_id' => $nb_messaging_id,
                        'key' => $key
                    )
            );
        } else {
            $retval = null;
        }
        
        return $retval;
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_messaging_service_id' is the index, and
     * each value is an instance of class CNabuMessagingServiceBase.
     * @param CNabuMessaging $nb_messaging The CNabuMessaging instance of the Messaging that owns the Messaging Service
     * List
     * @return mixed Returns and array with all items.
     */
    public static function getAllMessagingServices(CNabuMessaging $nb_messaging)
    {
        $nb_messaging_id = nb_getMixedValue($nb_messaging, 'nb_messaging_id');
        if (is_numeric($nb_messaging_id)) {
            $retval = forward_static_call(
            array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_messaging_service_id',
                'select * '
                . 'from nb_messaging_service '
               . 'where nb_messaging_id=%messaging_id$d',
                array(
                    'messaging_id' => $nb_messaging_id
                ),
                $nb_messaging
            );
        } else {
            $retval = new CNabuMessagingServiceList();
        }
        
        return $retval;
    }

    /**
     * Gets a filtered list of Messaging Service instances represented as an array. Params allows the capability of
     * select a subset of fields, order by concrete fields, or truncate the list by a number of rows starting in an
     * offset.
     * @throws \nabu\core\exceptions\ENabuCoreException Raises an exception if $fields or $order have invalid values.
     * @param mixed $nb_messaging Messaging instance, object containing a Messaging Id field or an Id.
     * @param string $q Query string to filter results using a context index.
     * @param string|array $fields List of fields to put in the results.
     * @param string|array $order List of fields to order the results. Each field can be suffixed with "ASC" or "DESC"
     * to determine the short order
     * @param int $offset Offset of first row in the results having the first row at offset 0.
     * @param int $num_items Number of continue rows to get as maximum in the results.
     * @return array Returns an array with all rows found using the criteria.
     */
    public static function getFilteredMessagingServiceList($nb_messaging = null, $q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $nb_messaging_id = nb_getMixedValue($nb_customer, NABU_MESSAGING_FIELD_ID);
        if (is_numeric($nb_messaging_id)) {
            $fields_part = nb_prefixFieldList(CNabuMessagingServiceBase::getStorageName(), $fields, false, true, '`');
            $order_part = nb_prefixFieldList(CNabuMessagingServiceBase::getStorageName(), $fields, false, false, '`');
        
            if ($num_items !== 0) {
                $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
            } else {
                $limit_part = false;
            }
        
            $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
                "select " . ($fields_part ? $fields_part . ' ' : '* ')
                . 'from nb_messaging_service '
               . 'where ' . NABU_MESSAGING_FIELD_ID . '=%messaging_id$d '
                . ($order_part ? "order by $order_part " : '')
                . ($limit_part ? "limit $limit_part" : ''),
                array(
                    'messaging_id' => $nb_messaging_id
                )
            );
        } else {
            $nb_item_list = null;
        }
        
        return $nb_item_list;
    }

    /**
     * Get Messaging Service Id attribute value
     * @return int Returns the Messaging Service Id value
     */
    public function getId()
    {
        return $this->getValue('nb_messaging_service_id');
    }

    /**
     * Sets the Messaging Service Id attribute value
     * @param int $id New value for attribute
     * @return CNabuMessagingServiceBase Returns $this
     */
    public function setId($id)
    {
        if ($id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$id")
            );
        }
        $this->setValue('nb_messaging_service_id', $id);
        
        return $this;
    }

    /**
     * Get Messaging Id attribute value
     * @return int Returns the Messaging Id value
     */
    public function getMessagingId()
    {
        return $this->getValue('nb_messaging_id');
    }

    /**
     * Sets the Messaging Id attribute value
     * @param int $nb_messaging_id New value for attribute
     * @return CNabuMessagingServiceBase Returns $this
     */
    public function setMessagingId($nb_messaging_id)
    {
        if ($nb_messaging_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_messaging_id")
            );
        }
        $this->setValue('nb_messaging_id', $nb_messaging_id);
        
        return $this;
    }

    /**
     * Get Messaging Service Hash attribute value
     * @return null|string Returns the Messaging Service Hash value
     */
    public function getHash()
    {
        return $this->getValue('nb_messaging_service_hash');
    }

    /**
     * Sets the Messaging Service Hash attribute value
     * @param null|string $hash New value for attribute
     * @return CNabuMessagingServiceBase Returns $this
     */
    public function setHash($hash)
    {
        $this->setValue('nb_messaging_service_hash', $hash);
        
        return $this;
    }

    /**
     * Get Messaging Service Key attribute value
     * @return null|string Returns the Messaging Service Key value
     */
    public function getKey()
    {
        return $this->getValue('nb_messaging_service_key');
    }

    /**
     * Sets the Messaging Service Key attribute value
     * @param null|string $key New value for attribute
     * @return CNabuMessagingServiceBase Returns $this
     */
    public function setKey($key)
    {
        $this->setValue('nb_messaging_service_key', $key);
        
        return $this;
    }

    /**
     * Get Messaging Service Provider attribute value
     * @return null|string Returns the Messaging Service Provider value
     */
    public function getProvider()
    {
        return $this->getValue('nb_messaging_service_provider');
    }

    /**
     * Sets the Messaging Service Provider attribute value
     * @param null|string $provider New value for attribute
     * @return CNabuMessagingServiceBase Returns $this
     */
    public function setProvider($provider)
    {
        $this->setValue('nb_messaging_service_provider', $provider);
        
        return $this;
    }

    /**
     * Get Messaging Service Name attribute value
     * @return null|string Returns the Messaging Service Name value
     */
    public function getName()
    {
        return $this->getValue('nb_messaging_service_name');
    }

    /**
     * Sets the Messaging Service Name attribute value
     * @param null|string $name New value for attribute
     * @return CNabuMessagingServiceBase Returns $this
     */
    public function setName($name)
    {
        $this->setValue('nb_messaging_service_name', $name);
        
        return $this;
    }

    /**
     * Get Messaging Service Interface attribute value
     * @return null|string Returns the Messaging Service Interface value
     */
    public function getInterface()
    {
        return $this->getValue('nb_messaging_service_interface');
    }

    /**
     * Sets the Messaging Service Interface attribute value
     * @param null|string $interface New value for attribute
     * @return CNabuMessagingServiceBase Returns $this
     */
    public function setInterface($interface)
    {
        $this->setValue('nb_messaging_service_interface', $interface);
        
        return $this;
    }

    /**
     * Get Messaging Service Attributes attribute value
     * @return null|array Returns the Messaging Service Attributes value
     */
    public function getAttributes()
    {
        return $this->getValueJSONDecoded('nb_messaging_service_attributes');
    }

    /**
     * Sets the Messaging Service Attributes attribute value
     * @param null|string|array $attributes New value for attribute
     * @return CNabuMessagingServiceBase Returns $this
     */
    public function setAttributes($attributes)
    {
        $this->setValueJSONEncoded('nb_messaging_service_attributes', $attributes);
        
        return $this;
    }

    /**
     * Overrides this method to add support to traits and/or attributes.
     * @param int|CNabuDataObject $nb_language Instance or Id of the language to be used.
     * @param bool $dataonly Render only field values and ommit class control flags.
     * @return array Returns a multilevel associative array with all data.
     */
    public function getTreeData($nb_language = null, $dataonly = false)
    {
        $trdata = parent::getTreeData($nb_language, $dataonly);
        
        $trdata['attributes'] = $this->getAttributes();
        
        return $trdata;
    }
}
