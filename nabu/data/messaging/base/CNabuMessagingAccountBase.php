<?php
/* ===========================================================================
 * File generated automatically by Nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/02/28 18:07:32 UTC
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
use \nabu\data\messaging\CNabuMessagingAccount;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Messaging Account stored in the storage named nb_messaging_account.
 * @author Rafael Gutiérrez Martínez <rgutierrez@wiscot.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\messaging\base
 */
abstract class CNabuMessagingAccountBase extends CNabuDBInternalObject
{
    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_messaging_account An instance of CNabuMessagingAccountBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_messaging_account_id, or a valid ID.
     */
    public function __construct($nb_messaging_account = false)
    {
        if ($nb_messaging_account) {
            $this->transferMixedValue($nb_messaging_account, 'nb_messaging_account_id');
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
        return 'nb_messaging_account';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_messaging_account_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_messaging_account '
                   . "where nb_messaging_account_id=%nb_messaging_account_id\$d "
              )
            : null;
    }

    /**
     * Find an instance identified by nb_messaging_account_key field.
     * @param string $key Key to search
     * @return CNabuMessagingAccount Returns a valid instance if exists or null if not.
     */
    public static function findByKey($key)
    {
        return CNabuMessagingAccount::buildObjectFromSQL(
                'select * '
                . 'from nb_messaging_account '
               . "where nb_messaging_account_key='%key\$s'",
                array(
                    'key' => $key
                )
        );
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_messaging_account_id' is the index, and
     * each value is an instance of class CNabuMessagingAccountBase.
     * @return mixed Returns and array with all items.
     */
    public static function getAllMessagingAccounts()
    {
        return forward_static_call(
                array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_messaging_account_id',
                'select * from nb_messaging_account'
        );
    }

    /**
     * Gets a filtered list of Messaging Account instances represented as an array. Params allows the capability of
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
    public static function getFilteredMessagingAccountList($q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $fields_part = nb_prefixFieldList(CNabuMessagingAccountBase::getStorageName(), $fields, false, true, '`');
        $order_part = nb_prefixFieldList(CNabuMessagingAccountBase::getStorageName(), $fields, false, false, '`');
        
        if ($num_items !== 0) {
            $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
        } else {
            $limit_part = false;
        }
        
        $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
            "select " . ($fields_part ? $fields_part . ' ' : '* ')
            . 'from nb_messaging_account '
            . ($order_part ? "order by $order_part " : '')
            . ($limit_part ? "limit $limit_part" : ''),
            array(
            )
        );
        
        return $nb_item_list;
    }

    /**
     * Get Messaging Account Id attribute value
     * @return int Returns the Messaging Account Id value
     */
    public function getId()
    {
        return $this->getValue('nb_messaging_account_id');
    }

    /**
     * Sets the Messaging Account Id attribute value
     * @param int $id New value for attribute
     * @return CNabuMessagingAccountBase Returns $this
     */
    public function setId($id)
    {
        if ($id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$id")
            );
        }
        $this->setValue('nb_messaging_account_id', $id);
        
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
     * @return CNabuMessagingAccountBase Returns $this
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
     * Get Messaging Account Hash attribute value
     * @return null|string Returns the Messaging Account Hash value
     */
    public function getHash()
    {
        return $this->getValue('nb_messaging_account_hash');
    }

    /**
     * Sets the Messaging Account Hash attribute value
     * @param null|string $hash New value for attribute
     * @return CNabuMessagingAccountBase Returns $this
     */
    public function setHash($hash)
    {
        $this->setValue('nb_messaging_account_hash', $hash);
        
        return $this;
    }

    /**
     * Get Messaging Account Key attribute value
     * @return null|string Returns the Messaging Account Key value
     */
    public function getKey()
    {
        return $this->getValue('nb_messaging_account_key');
    }

    /**
     * Sets the Messaging Account Key attribute value
     * @param null|string $key New value for attribute
     * @return CNabuMessagingAccountBase Returns $this
     */
    public function setKey($key)
    {
        $this->setValue('nb_messaging_account_key', $key);
        
        return $this;
    }

    /**
     * Get Messaging Account Attributes attribute value
     * @return null|array Returns the Messaging Account Attributes value
     */
    public function getAttributes()
    {
        return $this->getValueJSONDecoded('nb_messaging_account_attributes');
    }

    /**
     * Sets the Messaging Account Attributes attribute value
     * @param null|string|array $attributes New value for attribute
     * @return CNabuMessagingAccountBase Returns $this
     */
    public function setAttributes($attributes)
    {
        $this->setValueJSONEncoded('nb_messaging_account_attributes', $attributes);
        
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
