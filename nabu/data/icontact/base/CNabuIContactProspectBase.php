<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2018/01/18 10:51:30 UTC
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

namespace nabu\data\icontact\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\CNabuDataObject;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity iContact Prospect stored in the storage named nb_icontact_prospect.
 * @version 3.0.12 Surface
 * @package \nabu\data\icontact\base
 */
abstract class CNabuIContactProspectBase extends CNabuDBInternalObject
{
    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_icontact_prospect An instance of CNabuIContactProspectBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_icontact_prospect_id, or a valid ID.
     */
    public function __construct($nb_icontact_prospect = false)
    {
        if ($nb_icontact_prospect) {
            $this->transferMixedValue($nb_icontact_prospect, 'nb_icontact_prospect_id');
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
        return 'nb_icontact_prospect';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_icontact_prospect_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_icontact_prospect '
                   . "where nb_icontact_prospect_id=%nb_icontact_prospect_id\$d "
              )
            : null;
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_icontact_prospect_id' is the index, and
     * each value is an instance of class CNabuIContactProspectBase.
     * @return mixed Returns and array with all items.
     */
    public static function getAlliContactProspects()
    {
        return forward_static_call(
                array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_icontact_prospect_id',
                'select * from nb_icontact_prospect'
        );
    }

    /**
     * Gets a filtered list of iContact Prospect instances represented as an array. Params allows the capability of
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
    public static function getFilterediContactProspectList($q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $fields_part = nb_prefixFieldList(CNabuIContactProspectBase::getStorageName(), $fields, false, true, '`');
        $order_part = nb_prefixFieldList(CNabuIContactProspectBase::getStorageName(), $fields, false, false, '`');
        
        if ($num_items !== 0) {
            $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
        } else {
            $limit_part = false;
        }
        
        $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
            "select " . ($fields_part ? $fields_part . ' ' : '* ')
            . 'from nb_icontact_prospect '
            . ($order_part ? "order by $order_part " : '')
            . ($limit_part ? "limit $limit_part" : ''),
            array(
            )
        );
        
        return $nb_item_list;
    }

    /**
     * Get Icontact Prospect Id attribute value
     * @return int Returns the Icontact Prospect Id value
     */
    public function getId() : int
    {
        return $this->getValue('nb_icontact_prospect_id');
    }

    /**
     * Sets the Icontact Prospect Id attribute value.
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
        $this->setValue('nb_icontact_prospect_id', $id);
        
        return $this;
    }

    /**
     * Get Icontact Id attribute value
     * @return int Returns the Icontact Id value
     */
    public function getIcontactId() : int
    {
        return $this->getValue('nb_icontact_id');
    }

    /**
     * Sets the Icontact Id attribute value.
     * @param int $nb_icontact_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setIcontactId(int $nb_icontact_id) : CNabuDataObject
    {
        if ($nb_icontact_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_icontact_id")
            );
        }
        $this->setValue('nb_icontact_id', $nb_icontact_id);
        
        return $this;
    }

    /**
     * Get User Id attribute value
     * @return null|int Returns the User Id value
     */
    public function getUserId()
    {
        return $this->getValue('nb_user_id');
    }

    /**
     * Sets the User Id attribute value.
     * @param int|null $nb_user_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setUserId(int $nb_user_id = null) : CNabuDataObject
    {
        $this->setValue('nb_user_id', $nb_user_id);
        
        return $this;
    }

    /**
     * Get Language Id attribute value
     * @return null|int Returns the Language Id value
     */
    public function getLanguageId()
    {
        return $this->getValue('nb_language_id');
    }

    /**
     * Sets the Language Id attribute value.
     * @param int|null $nb_language_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setLanguageId(int $nb_language_id = null) : CNabuDataObject
    {
        $this->setValue('nb_language_id', $nb_language_id);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Email Hash attribute value
     * @return null|string Returns the Icontact Prospect Email Hash value
     */
    public function getEmailHash()
    {
        return $this->getValue('nb_icontact_prospect_email_hash');
    }

    /**
     * Sets the Icontact Prospect Email Hash attribute value.
     * @param string|null $email_hash New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setEmailHash(string $email_hash = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_email_hash', $email_hash);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Status Id attribute value
     * @return null|int Returns the Icontact Prospect Status Id value
     */
    public function getStatusId()
    {
        return $this->getValue('nb_icontact_prospect_status_id');
    }

    /**
     * Sets the Icontact Prospect Status Id attribute value.
     * @param int|null $status_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setStatusId(int $status_id = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_status_id', $status_id);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Creation Datetime attribute value
     * @return mixed Returns the Icontact Prospect Creation Datetime value
     */
    public function getCreationDatetime()
    {
        return $this->getValue('nb_icontact_prospect_creation_datetime');
    }

    /**
     * Sets the Icontact Prospect Creation Datetime attribute value.
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
        $this->setValue('nb_icontact_prospect_creation_datetime', $creation_datetime);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Last Update Datetime attribute value
     * @return mixed Returns the Icontact Prospect Last Update Datetime value
     */
    public function getLastUpdateDatetime()
    {
        return $this->getValue('nb_icontact_prospect_last_update_datetime');
    }

    /**
     * Sets the Icontact Prospect Last Update Datetime attribute value.
     * @param mixed $last_update_datetime New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setLastUpdateDatetime($last_update_datetime) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_last_update_datetime', $last_update_datetime);
        
        return $this;
    }

    /**
     * Get Icontact Prospect First Name attribute value
     * @return null|string Returns the Icontact Prospect First Name value
     */
    public function getFirstName()
    {
        return $this->getValue('nb_icontact_prospect_first_name');
    }

    /**
     * Sets the Icontact Prospect First Name attribute value.
     * @param string|null $first_name New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setFirstName(string $first_name = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_first_name', $first_name);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Last Name attribute value
     * @return null|string Returns the Icontact Prospect Last Name value
     */
    public function getLastName()
    {
        return $this->getValue('nb_icontact_prospect_last_name');
    }

    /**
     * Sets the Icontact Prospect Last Name attribute value.
     * @param string|null $last_name New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setLastName(string $last_name = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_last_name', $last_name);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Fiscal Number attribute value
     * @return null|string Returns the Icontact Prospect Fiscal Number value
     */
    public function getFiscalNumber()
    {
        return $this->getValue('nb_icontact_prospect_fiscal_number');
    }

    /**
     * Sets the Icontact Prospect Fiscal Number attribute value.
     * @param string|null $fiscal_number New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setFiscalNumber(string $fiscal_number = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_fiscal_number', $fiscal_number);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Address 1 attribute value
     * @return null|string Returns the Icontact Prospect Address 1 value
     */
    public function getAddress1()
    {
        return $this->getValue('nb_icontact_prospect_address_1');
    }

    /**
     * Sets the Icontact Prospect Address 1 attribute value.
     * @param string|null $address_1 New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setAddress1(string $address_1 = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_address_1', $address_1);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Address 2 attribute value
     * @return null|string Returns the Icontact Prospect Address 2 value
     */
    public function getAddress2()
    {
        return $this->getValue('nb_icontact_prospect_address_2');
    }

    /**
     * Sets the Icontact Prospect Address 2 attribute value.
     * @param string|null $address_2 New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setAddress2(string $address_2 = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_address_2', $address_2);
        
        return $this;
    }

    /**
     * Get Icontact Prospect ZIP Code attribute value
     * @return null|string Returns the Icontact Prospect ZIP Code value
     */
    public function getZIPCode()
    {
        return $this->getValue('nb_icontact_prospect_zip_code');
    }

    /**
     * Sets the Icontact Prospect ZIP Code attribute value.
     * @param string|null $zip_code New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setZIPCode(string $zip_code = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_zip_code', $zip_code);
        
        return $this;
    }

    /**
     * Get Icontact Prospect City Name attribute value
     * @return null|string Returns the Icontact Prospect City Name value
     */
    public function getCityName()
    {
        return $this->getValue('nb_icontact_prospect_city_name');
    }

    /**
     * Sets the Icontact Prospect City Name attribute value.
     * @param string|null $city_name New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCityName(string $city_name = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_city_name', $city_name);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Province Name attribute value
     * @return null|string Returns the Icontact Prospect Province Name value
     */
    public function getProvinceName()
    {
        return $this->getValue('nb_icontact_prospect_province_name');
    }

    /**
     * Sets the Icontact Prospect Province Name attribute value.
     * @param string|null $province_name New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setProvinceName(string $province_name = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_province_name', $province_name);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Country Name attribute value
     * @return null|string Returns the Icontact Prospect Country Name value
     */
    public function getCountryName()
    {
        return $this->getValue('nb_icontact_prospect_country_name');
    }

    /**
     * Sets the Icontact Prospect Country Name attribute value.
     * @param string|null $country_name New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCountryName(string $country_name = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_country_name', $country_name);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Enterprise attribute value
     * @return null|string Returns the Icontact Prospect Enterprise value
     */
    public function getEnterprise()
    {
        return $this->getValue('nb_icontact_prospect_enterprise');
    }

    /**
     * Sets the Icontact Prospect Enterprise attribute value.
     * @param string|null $enterprise New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setEnterprise(string $enterprise = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_enterprise', $enterprise);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Phone attribute value
     * @return null|string Returns the Icontact Prospect Phone value
     */
    public function getPhone()
    {
        return $this->getValue('nb_icontact_prospect_phone');
    }

    /**
     * Sets the Icontact Prospect Phone attribute value.
     * @param string|null $phone New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setPhone(string $phone = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_phone', $phone);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Telephone Prefix attribute value
     * @return null|string Returns the Icontact Prospect Telephone Prefix value
     */
    public function getTelephonePrefix()
    {
        return $this->getValue('nb_icontact_prospect_telephone_prefix');
    }

    /**
     * Sets the Icontact Prospect Telephone Prefix attribute value.
     * @param string|null $telephone_prefix New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setTelephonePrefix(string $telephone_prefix = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_telephone_prefix', $telephone_prefix);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Cellular attribute value
     * @return null|string Returns the Icontact Prospect Cellular value
     */
    public function getCellular()
    {
        return $this->getValue('nb_icontact_prospect_cellular');
    }

    /**
     * Sets the Icontact Prospect Cellular attribute value.
     * @param string|null $cellular New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCellular(string $cellular = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_cellular', $cellular);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Cellular Prefix attribute value
     * @return null|string Returns the Icontact Prospect Cellular Prefix value
     */
    public function getCellularPrefix()
    {
        return $this->getValue('nb_icontact_prospect_cellular_prefix');
    }

    /**
     * Sets the Icontact Prospect Cellular Prefix attribute value.
     * @param string|null $cellular_prefix New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCellularPrefix(string $cellular_prefix = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_cellular_prefix', $cellular_prefix);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Fax attribute value
     * @return null|string Returns the Icontact Prospect Fax value
     */
    public function getFax()
    {
        return $this->getValue('nb_icontact_prospect_fax');
    }

    /**
     * Sets the Icontact Prospect Fax attribute value.
     * @param string|null $fax New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setFax(string $fax = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_fax', $fax);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Email attribute value
     * @return null|string Returns the Icontact Prospect Email value
     */
    public function getEmail()
    {
        return $this->getValue('nb_icontact_prospect_email');
    }

    /**
     * Sets the Icontact Prospect Email attribute value.
     * @param string|null $email New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setEmail(string $email = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_email', $email);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Subject attribute value
     * @return null|string Returns the Icontact Prospect Subject value
     */
    public function getSubject()
    {
        return $this->getValue('nb_icontact_prospect_subject');
    }

    /**
     * Sets the Icontact Prospect Subject attribute value.
     * @param string|null $subject New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setSubject(string $subject = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_subject', $subject);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Notes attribute value
     * @return null|string Returns the Icontact Prospect Notes value
     */
    public function getNotes()
    {
        return $this->getValue('nb_icontact_prospect_notes');
    }

    /**
     * Sets the Icontact Prospect Notes attribute value.
     * @param string|null $notes New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setNotes(string $notes = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_notes', $notes);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Attributes attribute value
     * @return null|array Returns the Icontact Prospect Attributes value
     */
    public function getAttributes()
    {
        return $this->getValueJSONDecoded('nb_icontact_prospect_attributes');
    }

    /**
     * Sets the Icontact Prospect Attributes attribute value.
     * @param string|array|null $attributes New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setAttributes($attributes = null) : CNabuDataObject
    {
        $this->setValueJSONEncoded('nb_icontact_prospect_attributes', $attributes);
        
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
