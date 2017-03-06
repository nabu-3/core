<?php
/* ===========================================================================
 * File generated automatically by Nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/03/06 22:29:40 UTC
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

namespace nabu\data\security\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\customer\CNabuCustomer;
use \nabu\data\customer\traits\TNabuCustomerChild;
use \nabu\data\security\CNabuUserList;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity User stored in the storage named nb_user.
 * @author Rafael Gutiérrez Martínez <rgutierrez@wiscot.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\security\base
 */
abstract class CNabuUserBase extends CNabuDBInternalObject
{
    use TNabuCustomerChild;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_user An instance of CNabuUserBase or another object descending from \nabu\data\CNabuDataObject
     * which contains a field named nb_user_id, or a valid ID.
     */
    public function __construct($nb_user = false)
    {
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
        return 'nb_user';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_user_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_user '
                   . "where nb_user_id=%nb_user_id\$d "
              )
            : null;
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_user_id' is the index, and each value
     * is an instance of class CNabuUserBase.
     * @param CNabuCustomer $nb_customer The CNabuCustomer instance of the Customer that owns the User List.
     * @return mixed Returns and array with all items.
     */
    public static function getAllUsers(CNabuCustomer $nb_customer)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, 'nb_customer_id');
        if (is_numeric($nb_customer_id)) {
            $retval = forward_static_call(
            array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_user_id',
                'select * '
                . 'from nb_user '
               . 'where nb_customer_id=%cust_id$d',
                array(
                    'cust_id' => $nb_customer_id
                ),
                $nb_customer
            );
        } else {
            $retval = new CNabuUserList();
        }
        
        return $retval;
    }

    /**
     * Gets a filtered list of User instances represented as an array. Params allows the capability of select a subset
     * of fields, order by concrete fields, or truncate the list by a number of rows starting in an offset.
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
    public static function getFilteredUserList($nb_customer, $q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, NABU_CUSTOMER_FIELD_ID);
        if (is_numeric($nb_customer_id)) {
            $fields_part = nb_prefixFieldList(CNabuUserBase::getStorageName(), $fields, false, true, '`');
            $order_part = nb_prefixFieldList(CNabuUserBase::getStorageName(), $fields, false, false, '`');
        
            if ($num_items !== 0) {
                $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
            } else {
                $limit_part = false;
            }
        
            $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
                "select " . ($fields_part ? $fields_part . ' ' : '* ')
                . 'from nb_user '
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
     * Get User Id attribute value
     * @return int Returns the User Id value
     */
    public function getId()
    {
        return $this->getValue('nb_user_id');
    }

    /**
     * Sets the User Id attribute value
     * @param int $id New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setId($id)
    {
        if ($id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$id")
            );
        }
        $this->setValue('nb_user_id', $id);
        
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
     * @return CNabuUserBase Returns $this
     */
    public function setCustomerId($nb_customer_id)
    {
        $this->setValue('nb_customer_id', $nb_customer_id);
        
        return $this;
    }

    /**
     * Get User Prescriber Id attribute value
     * @return null|int Returns the User Prescriber Id value
     */
    public function getPrescriberId()
    {
        return $this->getValue('nb_user_prescriber_id');
    }

    /**
     * Sets the User Prescriber Id attribute value
     * @param null|int $prescriber_id New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setPrescriberId($prescriber_id)
    {
        $this->setValue('nb_user_prescriber_id', $prescriber_id);
        
        return $this;
    }

    /**
     * Get User Medioteca Id attribute value
     * @return null|int Returns the User Medioteca Id value
     */
    public function getMediotecaId()
    {
        return $this->getValue('nb_user_medioteca_id');
    }

    /**
     * Sets the User Medioteca Id attribute value
     * @param null|int $medioteca_id New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setMediotecaId($medioteca_id)
    {
        $this->setValue('nb_user_medioteca_id', $medioteca_id);
        
        return $this;
    }

    /**
     * Get User Medioteca Item Id attribute value
     * @return null|int Returns the User Medioteca Item Id value
     */
    public function getMediotecaItemId()
    {
        return $this->getValue('nb_user_medioteca_item_id');
    }

    /**
     * Sets the User Medioteca Item Id attribute value
     * @param null|int $medioteca_item_id New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setMediotecaItemId($medioteca_item_id)
    {
        $this->setValue('nb_user_medioteca_item_id', $medioteca_item_id);
        
        return $this;
    }

    /**
     * Get User Search Visibility attribute value
     * @return string Returns the User Search Visibility value
     */
    public function getSearchVisibility()
    {
        return $this->getValue('nb_user_search_visibility');
    }

    /**
     * Sets the User Search Visibility attribute value
     * @param string $search_visibility New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setSearchVisibility($search_visibility)
    {
        if ($search_visibility === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$search_visibility")
            );
        }
        $this->setValue('nb_user_search_visibility', $search_visibility);
        
        return $this;
    }

    /**
     * Get User Login attribute value
     * @return string Returns the User Login value
     */
    public function getLogin()
    {
        return $this->getValue('nb_user_login');
    }

    /**
     * Sets the User Login attribute value
     * @param string $login New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setLogin($login)
    {
        if ($login === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$login")
            );
        }
        $this->setValue('nb_user_login', $login);
        
        return $this;
    }

    /**
     * Get User Password attribute value
     * @return string Returns the User Password value
     */
    public function getPassword()
    {
        return $this->getValue('nb_user_passwd');
    }

    /**
     * Sets the User Password attribute value
     * @param string $passwd New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setPassword($passwd)
    {
        if ($passwd === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$passwd")
            );
        }
        $this->setValue('nb_user_passwd', $passwd);
        
        return $this;
    }

    /**
     * Get User Validation Status attribute value
     * @return string Returns the User Validation Status value
     */
    public function getValidationStatus()
    {
        return $this->getValue('nb_user_validation_status');
    }

    /**
     * Sets the User Validation Status attribute value
     * @param string $validation_status New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setValidationStatus($validation_status)
    {
        if ($validation_status === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$validation_status")
            );
        }
        $this->setValue('nb_user_validation_status', $validation_status);
        
        return $this;
    }

    /**
     * Get User Tester attribute value
     * @return string Returns the User Tester value
     */
    public function getTester()
    {
        return $this->getValue('nb_user_tester');
    }

    /**
     * Sets the User Tester attribute value
     * @param string $tester New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setTester($tester)
    {
        if ($tester === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$tester")
            );
        }
        $this->setValue('nb_user_tester', $tester);
        
        return $this;
    }

    /**
     * Get User Creation Datetime attribute value
     * @return mixed Returns the User Creation Datetime value
     */
    public function getCreationDatetime()
    {
        return $this->getValue('nb_user_creation_datetime');
    }

    /**
     * Sets the User Creation Datetime attribute value
     * @param mixed $creation_datetime New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setCreationDatetime($creation_datetime)
    {
        if ($creation_datetime === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$creation_datetime")
            );
        }
        $this->setValue('nb_user_creation_datetime', $creation_datetime);
        
        return $this;
    }

    /**
     * Get User Activation Datetime attribute value
     * @return mixed Returns the User Activation Datetime value
     */
    public function getActivationDatetime()
    {
        return $this->getValue('nb_user_activation_datetime');
    }

    /**
     * Sets the User Activation Datetime attribute value
     * @param mixed $activation_datetime New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setActivationDatetime($activation_datetime)
    {
        $this->setValue('nb_user_activation_datetime', $activation_datetime);
        
        return $this;
    }

    /**
     * Get User Last Update Datetime attribute value
     * @return mixed Returns the User Last Update Datetime value
     */
    public function getLastUpdateDatetime()
    {
        return $this->getValue('nb_user_last_update_datetime');
    }

    /**
     * Sets the User Last Update Datetime attribute value
     * @param mixed $last_update_datetime New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setLastUpdateDatetime($last_update_datetime)
    {
        $this->setValue('nb_user_last_update_datetime', $last_update_datetime);
        
        return $this;
    }

    /**
     * Get User Alive attribute value
     * @return mixed Returns the User Alive value
     */
    public function getAlive()
    {
        return $this->getValue('nb_user_alive');
    }

    /**
     * Sets the User Alive attribute value
     * @param mixed $alive New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setAlive($alive)
    {
        $this->setValue('nb_user_alive', $alive);
        
        return $this;
    }

    /**
     * Get WGEO Country Id attribute value
     * @return null|int Returns the WGEO Country Id value
     */
    public function getWGEOCountryId()
    {
        return $this->getValue('wgeo_country_id');
    }

    /**
     * Sets the WGEO Country Id attribute value
     * @param null|int $wgeo_country_id New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setWGEOCountryId($wgeo_country_id)
    {
        $this->setValue('wgeo_country_id', $wgeo_country_id);
        
        return $this;
    }

    /**
     * Get User Storage Id attribute value
     * @return null|string Returns the User Storage Id value
     */
    public function getStorageId()
    {
        return $this->getValue('nb_user_storage_id');
    }

    /**
     * Sets the User Storage Id attribute value
     * @param null|string $storage_id New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setStorageId($storage_id)
    {
        $this->setValue('nb_user_storage_id', $storage_id);
        
        return $this;
    }

    /**
     * Get User First Name attribute value
     * @return null|string Returns the User First Name value
     */
    public function getFirstName()
    {
        return $this->getValue('nb_user_first_name');
    }

    /**
     * Sets the User First Name attribute value
     * @param null|string $first_name New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setFirstName($first_name)
    {
        $this->setValue('nb_user_first_name', $first_name);
        
        return $this;
    }

    /**
     * Get User Last Name attribute value
     * @return null|string Returns the User Last Name value
     */
    public function getLastName()
    {
        return $this->getValue('nb_user_last_name');
    }

    /**
     * Sets the User Last Name attribute value
     * @param null|string $last_name New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setLastName($last_name)
    {
        $this->setValue('nb_user_last_name', $last_name);
        
        return $this;
    }

    /**
     * Get User Fiscal Number attribute value
     * @return null|string Returns the User Fiscal Number value
     */
    public function getFiscalNumber()
    {
        return $this->getValue('nb_user_fiscal_number');
    }

    /**
     * Sets the User Fiscal Number attribute value
     * @param null|string $fiscal_number New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setFiscalNumber($fiscal_number)
    {
        $this->setValue('nb_user_fiscal_number', $fiscal_number);
        
        return $this;
    }

    /**
     * Get User Birth Date attribute value
     * @return mixed Returns the User Birth Date value
     */
    public function getBirthDate()
    {
        return $this->getValue('nb_user_birth_date');
    }

    /**
     * Sets the User Birth Date attribute value
     * @param mixed $birth_date New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setBirthDate($birth_date)
    {
        $this->setValue('nb_user_birth_date', $birth_date);
        
        return $this;
    }

    /**
     * Get User Address 1 attribute value
     * @return null|string Returns the User Address 1 value
     */
    public function getAddress1()
    {
        return $this->getValue('nb_user_address_1');
    }

    /**
     * Sets the User Address 1 attribute value
     * @param null|string $address_1 New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setAddress1($address_1)
    {
        $this->setValue('nb_user_address_1', $address_1);
        
        return $this;
    }

    /**
     * Get User Address 2 attribute value
     * @return null|string Returns the User Address 2 value
     */
    public function getAddress2()
    {
        return $this->getValue('nb_user_address_2');
    }

    /**
     * Sets the User Address 2 attribute value
     * @param null|string $address_2 New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setAddress2($address_2)
    {
        $this->setValue('nb_user_address_2', $address_2);
        
        return $this;
    }

    /**
     * Get User ZIP Code attribute value
     * @return null|string Returns the User ZIP Code value
     */
    public function getZIPCode()
    {
        return $this->getValue('nb_user_zip_code');
    }

    /**
     * Sets the User ZIP Code attribute value
     * @param null|string $zip_code New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setZIPCode($zip_code)
    {
        $this->setValue('nb_user_zip_code', $zip_code);
        
        return $this;
    }

    /**
     * Get User City Name attribute value
     * @return null|string Returns the User City Name value
     */
    public function getCityName()
    {
        return $this->getValue('nb_user_city_name');
    }

    /**
     * Sets the User City Name attribute value
     * @param null|string $city_name New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setCityName($city_name)
    {
        $this->setValue('nb_user_city_name', $city_name);
        
        return $this;
    }

    /**
     * Get User Province Name attribute value
     * @return null|string Returns the User Province Name value
     */
    public function getProvinceName()
    {
        return $this->getValue('nb_user_province_name');
    }

    /**
     * Sets the User Province Name attribute value
     * @param null|string $province_name New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setProvinceName($province_name)
    {
        $this->setValue('nb_user_province_name', $province_name);
        
        return $this;
    }

    /**
     * Get User Country Name attribute value
     * @return null|string Returns the User Country Name value
     */
    public function getCountryName()
    {
        return $this->getValue('nb_user_country_name');
    }

    /**
     * Sets the User Country Name attribute value
     * @param null|string $country_name New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setCountryName($country_name)
    {
        $this->setValue('nb_user_country_name', $country_name);
        
        return $this;
    }

    /**
     * Get User Telephone Prefix attribute value
     * @return null|string Returns the User Telephone Prefix value
     */
    public function getTelephonePrefix()
    {
        return $this->getValue('nb_user_telephone_prefix');
    }

    /**
     * Sets the User Telephone Prefix attribute value
     * @param null|string $telephone_prefix New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setTelephonePrefix($telephone_prefix)
    {
        $this->setValue('nb_user_telephone_prefix', $telephone_prefix);
        
        return $this;
    }

    /**
     * Get User Telephone attribute value
     * @return null|string Returns the User Telephone value
     */
    public function getTelephone()
    {
        return $this->getValue('nb_user_telephone');
    }

    /**
     * Sets the User Telephone attribute value
     * @param null|string $telephone New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setTelephone($telephone)
    {
        $this->setValue('nb_user_telephone', $telephone);
        
        return $this;
    }

    /**
     * Get User Cellular Prefix attribute value
     * @return null|string Returns the User Cellular Prefix value
     */
    public function getCellularPrefix()
    {
        return $this->getValue('nb_user_cellular_prefix');
    }

    /**
     * Sets the User Cellular Prefix attribute value
     * @param null|string $cellular_prefix New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setCellularPrefix($cellular_prefix)
    {
        $this->setValue('nb_user_cellular_prefix', $cellular_prefix);
        
        return $this;
    }

    /**
     * Get User Cellular attribute value
     * @return null|string Returns the User Cellular value
     */
    public function getCellular()
    {
        return $this->getValue('nb_user_cellular');
    }

    /**
     * Sets the User Cellular attribute value
     * @param null|string $cellular New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setCellular($cellular)
    {
        $this->setValue('nb_user_cellular', $cellular);
        
        return $this;
    }

    /**
     * Get User Fax Prefix attribute value
     * @return null|string Returns the User Fax Prefix value
     */
    public function getFaxPrefix()
    {
        return $this->getValue('nb_user_fax_prefix');
    }

    /**
     * Sets the User Fax Prefix attribute value
     * @param null|string $fax_prefix New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setFaxPrefix($fax_prefix)
    {
        $this->setValue('nb_user_fax_prefix', $fax_prefix);
        
        return $this;
    }

    /**
     * Get User Fax attribute value
     * @return null|string Returns the User Fax value
     */
    public function getFax()
    {
        return $this->getValue('nb_user_fax');
    }

    /**
     * Sets the User Fax attribute value
     * @param null|string $fax New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setFax($fax)
    {
        $this->setValue('nb_user_fax', $fax);
        
        return $this;
    }

    /**
     * Get User Cellular Push Key attribute value
     * @return null|string Returns the User Cellular Push Key value
     */
    public function getCellularPushKey()
    {
        return $this->getValue('nb_user_cellular_push_key');
    }

    /**
     * Sets the User Cellular Push Key attribute value
     * @param null|string $cellular_push_key New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setCellularPushKey($cellular_push_key)
    {
        $this->setValue('nb_user_cellular_push_key', $cellular_push_key);
        
        return $this;
    }

    /**
     * Get User Email attribute value
     * @return string Returns the User Email value
     */
    public function getEmail()
    {
        return $this->getValue('nb_user_email');
    }

    /**
     * Sets the User Email attribute value
     * @param string $email New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setEmail($email)
    {
        if ($email === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$email")
            );
        }
        $this->setValue('nb_user_email', $email);
        
        return $this;
    }

    /**
     * Get User Work Centre attribute value
     * @return null|string Returns the User Work Centre value
     */
    public function getWorkCentre()
    {
        return $this->getValue('nb_user_work_centre');
    }

    /**
     * Sets the User Work Centre attribute value
     * @param null|string $work_centre New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setWorkCentre($work_centre)
    {
        $this->setValue('nb_user_work_centre', $work_centre);
        
        return $this;
    }

    /**
     * Get User Work Position attribute value
     * @return null|string Returns the User Work Position value
     */
    public function getWorkPosition()
    {
        return $this->getValue('nb_user_work_position');
    }

    /**
     * Sets the User Work Position attribute value
     * @param null|string $work_position New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setWorkPosition($work_position)
    {
        $this->setValue('nb_user_work_position', $work_position);
        
        return $this;
    }

    /**
     * Get User Work City attribute value
     * @return null|string Returns the User Work City value
     */
    public function getWorkCity()
    {
        return $this->getValue('nb_user_work_city');
    }

    /**
     * Sets the User Work City attribute value
     * @param null|string $work_city New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setWorkCity($work_city)
    {
        $this->setValue('nb_user_work_city', $work_city);
        
        return $this;
    }

    /**
     * Get User About attribute value
     * @return null|string Returns the User About value
     */
    public function getAbout()
    {
        return $this->getValue('nb_user_about');
    }

    /**
     * Sets the User About attribute value
     * @param null|string $about New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setAbout($about)
    {
        $this->setValue('nb_user_about', $about);
        
        return $this;
    }

    /**
     * Get User New Email attribute value
     * @return null|string Returns the User New Email value
     */
    public function getNewEmail()
    {
        return $this->getValue('nb_user_new_email');
    }

    /**
     * Sets the User New Email attribute value
     * @param null|string $new_email New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setNewEmail($new_email)
    {
        $this->setValue('nb_user_new_email', $new_email);
        
        return $this;
    }

    /**
     * Get User Allow Notification attribute value
     * @return string Returns the User Allow Notification value
     */
    public function getAllowNotification()
    {
        return $this->getValue('nb_user_allow_notification');
    }

    /**
     * Sets the User Allow Notification attribute value
     * @param string $allow_notification New value for attribute
     * @return CNabuUserBase Returns $this
     */
    public function setAllowNotification($allow_notification)
    {
        if ($allow_notification === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$allow_notification")
            );
        }
        $this->setValue('nb_user_allow_notification', $allow_notification);
        
        return $this;
    }
}
