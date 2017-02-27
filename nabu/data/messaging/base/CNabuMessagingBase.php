<?php
/* ===========================================================================
 * File generated automatically by Nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/02/27 16:28:50 UTC
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
use \nabu\data\customer\CNabuCustomer;
use \nabu\data\customer\traits\TNabuCustomerChild;
use \nabu\data\messaging\CNabuMessaging;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Messaging stored in the storage named nb_messaging.
 * @author Rafael Gutiérrez Martínez <rgutierrez@wiscot.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\messaging\base
 */
abstract class CNabuMessagingBase extends CNabuDBInternalObject
{
    use TNabuCustomerChild;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_messaging An instance of CNabuMessagingBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_messaging_id, or a valid ID.
     */
    public function __construct($nb_messaging = false)
    {
        if ($nb_messaging) {
            $this->transferMixedValue($nb_messaging, 'nb_messaging_id');
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
        return 'nb_messaging';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_messaging_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_messaging '
                   . "where nb_messaging_id=%nb_messaging_id\$d "
              )
            : null;
    }

    /**
     * Find an instance identified by nb_messaging_key field.
     * @param mixed $nb_customer Customer that owns Messaging
     * @param string $key Key to search
     * @return CNabuMessaging Returns a valid instance if exists or null if not.
     */
    public static function findByKey($nb_customer, $key)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, 'nb_customer_id');
        if (is_numeric($nb_customer_id)) {
            $retval = CNabuMessaging::buildObjectFromSQL(
                    'select * '
                    . 'from nb_messaging '
                   . 'where nb_customer_id=%cust_id$d '
                     . "and nb_messaging_key='%key\$s'",
                    array(
                        'cust_id' => $nb_customer_id,
                        'key' => $key
                    )
            );
        } else {
            $retval = null;
        }
        
        return $retval;
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_messaging_id' is the index, and each
     * value is an instance of class CNabuMessagingBase.
     * @param CNabuCustomer $nb_customer The CNabuCustomer instance of the Customer that owns the Messaging List.
     * @return mixed Returns and array with all items.
     */
    public static function getAllMessagings(CNabuCustomer $nb_customer)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, 'nb_customer_id');
        if (is_numeric($nb_customer_id)) {
            $retval = forward_static_call(
            array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_messaging_id',
                'select * '
                . 'from nb_messaging '
               . 'where nb_customer_id=%cust_id$d',
                array(
                    'cust_id' => $nb_customer_id
                ),
                $nb_customer
            );
        } else {
            $retval = null;
        }
        
        return $retval;
    }

    /**
     * Gets a filtered list of Messaging instances represented as an array. Params allows the capability of select a
     * subset of fields, order by concrete fields, or truncate the list by a number of rows starting in an offset.
     * @throws \nabu\core\exceptions\ENabuCoreException Raises an exception if $fields or $order have invalid values.
     * @param mixed $nb_customer Customer instance, object containing a Customer Id field or an Id.
     * @param string $q Query string to filter results using a context index.
     * @param string|array $fields List of fields to put in the results.
     * @param string|array $order List of fields to order the results. Each field can be suffixed with "ASC" or "DESC"
     * to determine the short order
     * @param int $offset Offset of first row in the results having the first row at offset 0.
     * @param int $num_items Number of continue rows to get as maximum in the results.
     * @return array Returns an array with all rows found using the criteria.
     */
    public static function getFilteredMessagingList($nb_customer, $q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, NABU_CUSTOMER_FIELD_ID);
        if (is_numeric($nb_customer_id)) {
            $fields_part = nb_prefixFieldList(CNabuMessagingBase::getStorageName(), $fields, false, true, '`');
            $order_part = nb_prefixFieldList(CNabuMessagingBase::getStorageName(), $fields, false, false, '`');
        
            if ($num_items !== 0) {
                $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
            } else {
                $limit_part = false;
            }
        
            $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
                "select " . ($fields_part ? $fields_part . ' ' : '* ')
                . 'from nb_messaging '
               . 'where ' . NABU_CUSTOMER_FIELD_ID . '=%cust_id$d '
                . ($order_part ? "order by $order_part " : '')
                . ($limit_part ? "limit $limit_part" : ''),
                array(
                    'cust_id' => $nb_customer_id
                )
            );
        } else {
            $nb_item_list = null;
        }
        
        return $nb_item_list;
    }

    /**
     * Get Messaging Id attribute value
     * @return int Returns the Messaging Id value
     */
    public function getId()
    {
        return $this->getValue('nb_messaging_id');
    }

    /**
     * Sets the Messaging Id attribute value
     * @param int $id New value for attribute
     * @return CNabuMessagingBase Returns $this
     */
    public function setId($id)
    {
        if ($id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$id")
            );
        }
        $this->setValue('nb_messaging_id', $id);
        
        return $this;
    }

    /**
     * Get Messaging Hash attribute value
     * @return null|string Returns the Messaging Hash value
     */
    public function getHash()
    {
        return $this->getValue('nb_messaging_hash');
    }

    /**
     * Sets the Messaging Hash attribute value
     * @param null|string $hash New value for attribute
     * @return CNabuMessagingBase Returns $this
     */
    public function setHash($hash)
    {
        $this->setValue('nb_messaging_hash', $hash);
        
        return $this;
    }

    /**
     * Get Customer Id attribute value
     * @return null|int Returns the Customer Id value
     */
    public function getCustomerId()
    {
        return $this->getValue('nb_customer_id');
    }

    /**
     * Sets the Customer Id attribute value
     * @param null|int $nb_customer_id New value for attribute
     * @return CNabuMessagingBase Returns $this
     */
    public function setCustomerId($nb_customer_id)
    {
        $this->setValue('nb_customer_id', $nb_customer_id);
        
        return $this;
    }

    /**
     * Get Smtp Account Id attribute value
     * @return null|int Returns the Smtp Account Id value
     */
    public function getSmtpAccountId()
    {
        return $this->getValue('nb_smtp_account_id');
    }

    /**
     * Sets the Smtp Account Id attribute value
     * @param null|int $nb_smtp_account_id New value for attribute
     * @return CNabuMessagingBase Returns $this
     */
    public function setSmtpAccountId($nb_smtp_account_id)
    {
        $this->setValue('nb_smtp_account_id', $nb_smtp_account_id);
        
        return $this;
    }

    /**
     * Get Messaging Key attribute value
     * @return null|string Returns the Messaging Key value
     */
    public function getKey()
    {
        return $this->getValue('nb_messaging_key');
    }

    /**
     * Sets the Messaging Key attribute value
     * @param null|string $key New value for attribute
     * @return CNabuMessagingBase Returns $this
     */
    public function setKey($key)
    {
        $this->setValue('nb_messaging_key', $key);
        
        return $this;
    }

    /**
     * Get Messaging Name attribute value
     * @return null|string Returns the Messaging Name value
     */
    public function getName()
    {
        return $this->getValue('nb_messaging_name');
    }

    /**
     * Sets the Messaging Name attribute value
     * @param null|string $name New value for attribute
     * @return CNabuMessagingBase Returns $this
     */
    public function setName($name)
    {
        $this->setValue('nb_messaging_name', $name);
        
        return $this;
    }
}
