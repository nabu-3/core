<?php
/* ===========================================================================
 * File generated automatically by Nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/03/08 12:48:32 UTC
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

namespace nabu\data\domain\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\customer\CNabuCustomer;
use \nabu\data\customer\traits\TNabuCustomerChild;
use \nabu\data\domain\CNabuDomainZoneList;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Domain Zone stored in the storage named nb_domain_zone.
 * @author Rafael Gutiérrez Martínez <rgutierrez@wiscot.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\domain\base
 */
abstract class CNabuDomainZoneBase extends CNabuDBInternalObject
{
    use TNabuCustomerChild;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_domain_zone An instance of CNabuDomainZoneBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_domain_zone_id, or a valid ID.
     */
    public function __construct($nb_domain_zone = false)
    {
        if ($nb_domain_zone) {
            $this->transferMixedValue($nb_domain_zone, 'nb_domain_zone_id');
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
        return 'nb_domain_zone';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_domain_zone_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_domain_zone '
                   . "where nb_domain_zone_id=%nb_domain_zone_id\$d "
              )
            : null;
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_domain_zone_id' is the index, and each
     * value is an instance of class CNabuDomainZoneBase.
     * @param CNabuCustomer $nb_customer The CNabuCustomer instance of the Customer that owns the Domain Zone List.
     * @return mixed Returns and array with all items.
     */
    public static function getAllDomainZones(CNabuCustomer $nb_customer)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, 'nb_customer_id');
        if (is_numeric($nb_customer_id)) {
            $retval = forward_static_call(
            array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_domain_zone_id',
                'select * '
                . 'from nb_domain_zone '
               . 'where nb_customer_id=%cust_id$d',
                array(
                    'cust_id' => $nb_customer_id
                ),
                $nb_customer
            );
        } else {
            $retval = new CNabuDomainZoneList();
        }
        
        return $retval;
    }

    /**
     * Gets a filtered list of Domain Zone instances represented as an array. Params allows the capability of select a
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
    public static function getFilteredDomainZoneList($nb_customer, $q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, NABU_CUSTOMER_FIELD_ID);
        if (is_numeric($nb_customer_id)) {
            $fields_part = nb_prefixFieldList(CNabuDomainZoneBase::getStorageName(), $fields, false, true, '`');
            $order_part = nb_prefixFieldList(CNabuDomainZoneBase::getStorageName(), $fields, false, false, '`');
        
            if ($num_items !== 0) {
                $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
            } else {
                $limit_part = false;
            }
        
            $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
                "select " . ($fields_part ? $fields_part . ' ' : '* ')
                . 'from nb_domain_zone '
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
     * Get Domain Zone Id attribute value
     * @return int Returns the Domain Zone Id value
     */
    public function getId()
    {
        return $this->getValue('nb_domain_zone_id');
    }

    /**
     * Sets the Domain Zone Id attribute value
     * @param int $id New value for attribute
     * @return CNabuDomainZoneBase Returns $this
     */
    public function setId($id)
    {
        if ($id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$id")
            );
        }
        $this->setValue('nb_domain_zone_id', $id);
        
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
     * @return CNabuDomainZoneBase Returns $this
     */
    public function setCustomerId($nb_customer_id)
    {
        $this->setValue('nb_customer_id', $nb_customer_id);
        
        return $this;
    }

    /**
     * Get Domain Zone Name attribute value
     * @return string Returns the Domain Zone Name value
     */
    public function getName()
    {
        return $this->getValue('nb_domain_zone_name');
    }

    /**
     * Sets the Domain Zone Name attribute value
     * @param string $name New value for attribute
     * @return CNabuDomainZoneBase Returns $this
     */
    public function setName($name)
    {
        if ($name === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$name")
            );
        }
        $this->setValue('nb_domain_zone_name', $name);
        
        return $this;
    }

    /**
     * Get Domain Zone Origin attribute value
     * @return mixed Returns the Domain Zone Origin value
     */
    public function getOrigin()
    {
        return $this->getValue('nb_domain_zone_origin');
    }

    /**
     * Sets the Domain Zone Origin attribute value
     * @param mixed $origin New value for attribute
     * @return CNabuDomainZoneBase Returns $this
     */
    public function setOrigin($origin)
    {
        if ($origin === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$origin")
            );
        }
        $this->setValue('nb_domain_zone_origin', $origin);
        
        return $this;
    }

    /**
     * Get Domain Zone Ttl attribute value
     * @return int Returns the Domain Zone Ttl value
     */
    public function getTtl()
    {
        return $this->getValue('nb_domain_zone_ttl');
    }

    /**
     * Sets the Domain Zone Ttl attribute value
     * @param int $ttl New value for attribute
     * @return CNabuDomainZoneBase Returns $this
     */
    public function setTtl($ttl)
    {
        if ($ttl === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$ttl")
            );
        }
        $this->setValue('nb_domain_zone_ttl', $ttl);
        
        return $this;
    }

    /**
     * Get Domain Zone Admin Email attribute value
     * @return string Returns the Domain Zone Admin Email value
     */
    public function getAdminEmail()
    {
        return $this->getValue('nb_domain_zone_admin_email');
    }

    /**
     * Sets the Domain Zone Admin Email attribute value
     * @param string $admin_email New value for attribute
     * @return CNabuDomainZoneBase Returns $this
     */
    public function setAdminEmail($admin_email)
    {
        if ($admin_email === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$admin_email")
            );
        }
        $this->setValue('nb_domain_zone_admin_email', $admin_email);
        
        return $this;
    }

    /**
     * Get Domain Zone Serial attribute value
     * @return int Returns the Domain Zone Serial value
     */
    public function getSerial()
    {
        return $this->getValue('nb_domain_zone_serial');
    }

    /**
     * Sets the Domain Zone Serial attribute value
     * @param int $serial New value for attribute
     * @return CNabuDomainZoneBase Returns $this
     */
    public function setSerial($serial)
    {
        if ($serial === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$serial")
            );
        }
        $this->setValue('nb_domain_zone_serial', $serial);
        
        return $this;
    }

    /**
     * Get Domain Zone Refresh attribute value
     * @return int Returns the Domain Zone Refresh value
     */
    public function getRefresh()
    {
        return $this->getValue('nb_domain_zone_refresh');
    }

    /**
     * Sets the Domain Zone Refresh attribute value
     * @param int $refresh New value for attribute
     * @return CNabuDomainZoneBase Returns $this
     */
    public function setRefresh($refresh)
    {
        if ($refresh === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$refresh")
            );
        }
        $this->setValue('nb_domain_zone_refresh', $refresh);
        
        return $this;
    }

    /**
     * Get Domain Zone Retry attribute value
     * @return int Returns the Domain Zone Retry value
     */
    public function getRetry()
    {
        return $this->getValue('nb_domain_zone_retry');
    }

    /**
     * Sets the Domain Zone Retry attribute value
     * @param int $retry New value for attribute
     * @return CNabuDomainZoneBase Returns $this
     */
    public function setRetry($retry)
    {
        if ($retry === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$retry")
            );
        }
        $this->setValue('nb_domain_zone_retry', $retry);
        
        return $this;
    }

    /**
     * Get Domain Zone Expiry attribute value
     * @return int Returns the Domain Zone Expiry value
     */
    public function getExpiry()
    {
        return $this->getValue('nb_domain_zone_expiry');
    }

    /**
     * Sets the Domain Zone Expiry attribute value
     * @param int $expiry New value for attribute
     * @return CNabuDomainZoneBase Returns $this
     */
    public function setExpiry($expiry)
    {
        if ($expiry === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$expiry")
            );
        }
        $this->setValue('nb_domain_zone_expiry', $expiry);
        
        return $this;
    }

    /**
     * Get Domain Zone Minimum attribute value
     * @return int Returns the Domain Zone Minimum value
     */
    public function getMinimum()
    {
        return $this->getValue('nb_domain_zone_minimum');
    }

    /**
     * Sets the Domain Zone Minimum attribute value
     * @param int $minimum New value for attribute
     * @return CNabuDomainZoneBase Returns $this
     */
    public function setMinimum($minimum)
    {
        if ($minimum === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$minimum")
            );
        }
        $this->setValue('nb_domain_zone_minimum', $minimum);
        
        return $this;
    }

    /**
     * Get Domain Zone Last Update Datetime attribute value
     * @return mixed Returns the Domain Zone Last Update Datetime value
     */
    public function getLastUpdateDatetime()
    {
        return $this->getValue('nb_domain_zone_last_update_datetime');
    }

    /**
     * Sets the Domain Zone Last Update Datetime attribute value
     * @param mixed $last_update_datetime New value for attribute
     * @return CNabuDomainZoneBase Returns $this
     */
    public function setLastUpdateDatetime($last_update_datetime)
    {
        $this->setValue('nb_domain_zone_last_update_datetime', $last_update_datetime);
        
        return $this;
    }

    /**
     * Get Domain Zone Last Upload Datetime attribute value
     * @return mixed Returns the Domain Zone Last Upload Datetime value
     */
    public function getLastUploadDatetime()
    {
        return $this->getValue('nb_domain_zone_last_upload_datetime');
    }

    /**
     * Sets the Domain Zone Last Upload Datetime attribute value
     * @param mixed $last_upload_datetime New value for attribute
     * @return CNabuDomainZoneBase Returns $this
     */
    public function setLastUploadDatetime($last_upload_datetime)
    {
        $this->setValue('nb_domain_zone_last_upload_datetime', $last_upload_datetime);
        
        return $this;
    }

    /**
     * Get Domain Zone Dns 01 attribute value
     * @return string Returns the Domain Zone Dns 01 value
     */
    public function getDns01()
    {
        return $this->getValue('nb_domain_zone_dns_01');
    }

    /**
     * Sets the Domain Zone Dns 01 attribute value
     * @param string $dns_01 New value for attribute
     * @return CNabuDomainZoneBase Returns $this
     */
    public function setDns01($dns_01)
    {
        if ($dns_01 === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$dns_01")
            );
        }
        $this->setValue('nb_domain_zone_dns_01', $dns_01);
        
        return $this;
    }

    /**
     * Get Domain Zone Dns 02 attribute value
     * @return string Returns the Domain Zone Dns 02 value
     */
    public function getDns02()
    {
        return $this->getValue('nb_domain_zone_dns_02');
    }

    /**
     * Sets the Domain Zone Dns 02 attribute value
     * @param string $dns_02 New value for attribute
     * @return CNabuDomainZoneBase Returns $this
     */
    public function setDns02($dns_02)
    {
        if ($dns_02 === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$dns_02")
            );
        }
        $this->setValue('nb_domain_zone_dns_02', $dns_02);
        
        return $this;
    }

    /**
     * Get Domain Zone Dns 03 attribute value
     * @return null|string Returns the Domain Zone Dns 03 value
     */
    public function getDns03()
    {
        return $this->getValue('nb_domain_zone_dns_03');
    }

    /**
     * Sets the Domain Zone Dns 03 attribute value
     * @param null|string $dns_03 New value for attribute
     * @return CNabuDomainZoneBase Returns $this
     */
    public function setDns03($dns_03)
    {
        $this->setValue('nb_domain_zone_dns_03', $dns_03);
        
        return $this;
    }

    /**
     * Get Domain Zone Dns 04 attribute value
     * @return null|string Returns the Domain Zone Dns 04 value
     */
    public function getDns04()
    {
        return $this->getValue('nb_domain_zone_dns_04');
    }

    /**
     * Sets the Domain Zone Dns 04 attribute value
     * @param null|string $dns_04 New value for attribute
     * @return CNabuDomainZoneBase Returns $this
     */
    public function setDns04($dns_04)
    {
        $this->setValue('nb_domain_zone_dns_04', $dns_04);
        
        return $this;
    }

    /**
     * Get Domain Zone Dns 05 attribute value
     * @return null|string Returns the Domain Zone Dns 05 value
     */
    public function getDns05()
    {
        return $this->getValue('nb_domain_zone_dns_05');
    }

    /**
     * Sets the Domain Zone Dns 05 attribute value
     * @param null|string $dns_05 New value for attribute
     * @return CNabuDomainZoneBase Returns $this
     */
    public function setDns05($dns_05)
    {
        $this->setValue('nb_domain_zone_dns_05', $dns_05);
        
        return $this;
    }

    /**
     * Get Domain Zone Dns 06 attribute value
     * @return null|string Returns the Domain Zone Dns 06 value
     */
    public function getDns06()
    {
        return $this->getValue('nb_domain_zone_dns_06');
    }

    /**
     * Sets the Domain Zone Dns 06 attribute value
     * @param null|string $dns_06 New value for attribute
     * @return CNabuDomainZoneBase Returns $this
     */
    public function setDns06($dns_06)
    {
        $this->setValue('nb_domain_zone_dns_06', $dns_06);
        
        return $this;
    }

    /**
     * Get Domain Zone Dns Origin attribute value
     * @return mixed Returns the Domain Zone Dns Origin value
     */
    public function getDnsOrigin()
    {
        return $this->getValue('nb_domain_zone_dns_origin');
    }

    /**
     * Sets the Domain Zone Dns Origin attribute value
     * @param mixed $dns_origin New value for attribute
     * @return CNabuDomainZoneBase Returns $this
     */
    public function setDnsOrigin($dns_origin)
    {
        if ($dns_origin === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$dns_origin")
            );
        }
        $this->setValue('nb_domain_zone_dns_origin', $dns_origin);
        
        return $this;
    }

    /**
     * Get Domain Zone Share All attribute value
     * @return string Returns the Domain Zone Share All value
     */
    public function getShareAll()
    {
        return $this->getValue('nb_domain_zone_share_all');
    }

    /**
     * Sets the Domain Zone Share All attribute value
     * @param string $share_all New value for attribute
     * @return CNabuDomainZoneBase Returns $this
     */
    public function setShareAll($share_all)
    {
        if ($share_all === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$share_all")
            );
        }
        $this->setValue('nb_domain_zone_share_all', $share_all);
        
        return $this;
    }
}
