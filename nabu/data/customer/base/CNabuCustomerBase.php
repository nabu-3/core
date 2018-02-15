<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2018/02/15 12:53:25 UTC
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

namespace nabu\data\customer\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\core\interfaces\INabuHashed;
use \nabu\core\traits\TNabuHashed;
use \nabu\data\CNabuDataObject;
use \nabu\data\customer\CNabuCustomer;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Customer stored in the storage named nb_customer.
 * @version 3.0.12 Surface
 * @package \nabu\data\customer\base
 */
abstract class CNabuCustomerBase extends CNabuDBInternalObject implements INabuHashed
{
    use TNabuHashed;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_customer An instance of CNabuCustomerBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_customer_id, or a valid ID.
     */
    public function __construct($nb_customer = false)
    {
        if ($nb_customer) {
            $this->transferMixedValue($nb_customer, 'nb_customer_id');
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
        return 'nb_customer';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_customer_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_customer '
                   . "where nb_customer_id=%nb_customer_id\$d "
              )
            : null;
    }

    /**
     * Find an instance identified by nb_customer_hash field.
     * @param string $hash Hash to search
     * @return CNabuDataObject Returns a valid instance if exists or null if not.
     */
    public static function findByHash(string $hash)
    {
        return CNabuCustomer::buildObjectFromSQL(
                'select * '
                . 'from nb_customer '
               . "where nb_customer_hash='%hash\$s'",
                array(
                    'hash' => $hash
                )
        );
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_customer_id' is the index, and each
     * value is an instance of class CNabuCustomerBase.
     * @return mixed Returns and array with all items.
     */
    public static function getAllCustomers()
    {
        return forward_static_call(
                array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_customer_id',
                'select * from nb_customer'
        );
    }

    /**
     * Gets a filtered list of Customer instances represented as an array. Params allows the capability of select a
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
    public static function getFilteredCustomerList($q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $fields_part = nb_prefixFieldList(CNabuCustomerBase::getStorageName(), $fields, false, true, '`');
        $order_part = nb_prefixFieldList(CNabuCustomerBase::getStorageName(), $fields, false, false, '`');
        
        if ($num_items !== 0) {
            $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
        } else {
            $limit_part = false;
        }
        
        $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
            "select " . ($fields_part ? $fields_part . ' ' : '* ')
            . 'from nb_customer '
            . ($order_part ? "order by $order_part " : '')
            . ($limit_part ? "limit $limit_part" : ''),
            array(
            )
        );
        
        return $nb_item_list;
    }

    /**
     * Get Customer Id attribute value
     * @return int Returns the Customer Id value
     */
    public function getId() : int
    {
        return $this->getValue('nb_customer_id');
    }

    /**
     * Sets the Customer Id attribute value.
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
        $this->setValue('nb_customer_id', $id);
        
        return $this;
    }

    /**
     * Get Customer Hash attribute value
     * @return null|string Returns the Customer Hash value
     */
    public function getHash()
    {
        return $this->getValue('nb_customer_hash');
    }

    /**
     * Sets the Customer Hash attribute value.
     * @param string|null $hash New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setHash(string $hash = null) : CNabuDataObject
    {
        $this->setValue('nb_customer_hash', $hash);
        
        return $this;
    }

    /**
     * Get Customer Fiscal Name attribute value
     * @return null|string Returns the Customer Fiscal Name value
     */
    public function getFiscalName()
    {
        return $this->getValue('nb_customer_fiscal_name');
    }

    /**
     * Sets the Customer Fiscal Name attribute value.
     * @param string|null $fiscal_name New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setFiscalName(string $fiscal_name = null) : CNabuDataObject
    {
        $this->setValue('nb_customer_fiscal_name', $fiscal_name);
        
        return $this;
    }

    /**
     * Get Customer Fiscal Number attribute value
     * @return null|string Returns the Customer Fiscal Number value
     */
    public function getFiscalNumber()
    {
        return $this->getValue('nb_customer_fiscal_number');
    }

    /**
     * Sets the Customer Fiscal Number attribute value.
     * @param string|null $fiscal_number New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setFiscalNumber(string $fiscal_number = null) : CNabuDataObject
    {
        $this->setValue('nb_customer_fiscal_number', $fiscal_number);
        
        return $this;
    }

    /**
     * Get Customer Fiscal Address Id attribute value
     * @return null|int Returns the Customer Fiscal Address Id value
     */
    public function getFiscalAddressId()
    {
        return $this->getValue('nb_customer_fiscal_address_id');
    }

    /**
     * Sets the Customer Fiscal Address Id attribute value.
     * @param int|null $fiscal_address_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setFiscalAddressId(int $fiscal_address_id = null) : CNabuDataObject
    {
        $this->setValue('nb_customer_fiscal_address_id', $fiscal_address_id);
        
        return $this;
    }

    /**
     * Get Customer Contact Name attribute value
     * @return null|string Returns the Customer Contact Name value
     */
    public function getContactName()
    {
        return $this->getValue('nb_customer_contact_name');
    }

    /**
     * Sets the Customer Contact Name attribute value.
     * @param string|null $contact_name New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setContactName(string $contact_name = null) : CNabuDataObject
    {
        $this->setValue('nb_customer_contact_name', $contact_name);
        
        return $this;
    }

    /**
     * Get Customer Contact Position attribute value
     * @return null|string Returns the Customer Contact Position value
     */
    public function getContactPosition()
    {
        return $this->getValue('nb_customer_contact_position');
    }

    /**
     * Sets the Customer Contact Position attribute value.
     * @param string|null $contact_position New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setContactPosition(string $contact_position = null) : CNabuDataObject
    {
        $this->setValue('nb_customer_contact_position', $contact_position);
        
        return $this;
    }

    /**
     * Get Customer Contact Telephone attribute value
     * @return null|string Returns the Customer Contact Telephone value
     */
    public function getContactTelephone()
    {
        return $this->getValue('nb_customer_contact_telephone');
    }

    /**
     * Sets the Customer Contact Telephone attribute value.
     * @param string|null $contact_telephone New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setContactTelephone(string $contact_telephone = null) : CNabuDataObject
    {
        $this->setValue('nb_customer_contact_telephone', $contact_telephone);
        
        return $this;
    }

    /**
     * Get Customer Contact Cellular attribute value
     * @return null|string Returns the Customer Contact Cellular value
     */
    public function getContactCellular()
    {
        return $this->getValue('nb_customer_contact_cellular');
    }

    /**
     * Sets the Customer Contact Cellular attribute value.
     * @param string|null $contact_cellular New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setContactCellular(string $contact_cellular = null) : CNabuDataObject
    {
        $this->setValue('nb_customer_contact_cellular', $contact_cellular);
        
        return $this;
    }

    /**
     * Get Customer Contact Email attribute value
     * @return null|string Returns the Customer Contact Email value
     */
    public function getContactEmail()
    {
        return $this->getValue('nb_customer_contact_email');
    }

    /**
     * Sets the Customer Contact Email attribute value.
     * @param string|null $contact_email New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setContactEmail(string $contact_email = null) : CNabuDataObject
    {
        $this->setValue('nb_customer_contact_email', $contact_email);
        
        return $this;
    }

    /**
     * Get Customer Contact Address Id attribute value
     * @return null|int Returns the Customer Contact Address Id value
     */
    public function getContactAddressId()
    {
        return $this->getValue('nb_customer_contact_address_id');
    }

    /**
     * Sets the Customer Contact Address Id attribute value.
     * @param int|null $contact_address_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setContactAddressId(int $contact_address_id = null) : CNabuDataObject
    {
        $this->setValue('nb_customer_contact_address_id', $contact_address_id);
        
        return $this;
    }

    /**
     * Get Customer Address Id attribute value
     * @return null|int Returns the Customer Address Id value
     */
    public function getAddressId()
    {
        return $this->getValue('nb_customer_address_id');
    }

    /**
     * Sets the Customer Address Id attribute value.
     * @param int|null $address_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setAddressId(int $address_id = null) : CNabuDataObject
    {
        $this->setValue('nb_customer_address_id', $address_id);
        
        return $this;
    }

    /**
     * Get Customer Avatar Medioteca Id attribute value
     * @return null|int Returns the Customer Avatar Medioteca Id value
     */
    public function getAvatarMediotecaId()
    {
        return $this->getValue('nb_customer_avatar_medioteca_id');
    }

    /**
     * Sets the Customer Avatar Medioteca Id attribute value.
     * @param int|null $avatar_medioteca_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setAvatarMediotecaId(int $avatar_medioteca_id = null) : CNabuDataObject
    {
        $this->setValue('nb_customer_avatar_medioteca_id', $avatar_medioteca_id);
        
        return $this;
    }
}
