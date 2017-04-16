<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/04/16 22:45:14 UTC
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

namespace nabu\data\site\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\CNabuDataObject;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Site Alias Host stored in the storage named nb_site_alias_host.
 * @author Rafael Gutiérrez Martínez <rgutierrez@nabu-3.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\site\base
 */
abstract class CNabuSiteAliasHostBase extends CNabuDBInternalObject
{
    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_site_alias An instance of CNabuSiteAliasHostBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_site_alias_id, or a valid ID.
     * @param mixed $nb_server_host An instance of CNabuSiteAliasHostBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_server_host_id, or a valid ID.
     */
    public function __construct($nb_site_alias = false, $nb_server_host = false)
    {
        if ($nb_site_alias) {
            $this->transferMixedValue($nb_site_alias, 'nb_site_alias_id');
        }
        
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
        return 'nb_site_alias_host';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_site_alias_id') && $this->isValueNumeric('nb_server_host_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_site_alias_host '
                   . "where nb_site_alias_id=%nb_site_alias_id\$d "
                     . "and nb_server_host_id=%nb_server_host_id\$d "
              )
            : null;
    }

    /**
     * Gets a filtered list of Site Alias Host instances represented as an array. Params allows the capability of
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
    public static function getFilteredSiteAliasHostList($q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $fields_part = nb_prefixFieldList(CNabuSiteAliasHostBase::getStorageName(), $fields, false, true, '`');
        $order_part = nb_prefixFieldList(CNabuSiteAliasHostBase::getStorageName(), $fields, false, false, '`');
        
        if ($num_items !== 0) {
            $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
        } else {
            $limit_part = false;
        }
        
        $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
            "select " . ($fields_part ? $fields_part . ' ' : '* ')
            . 'from nb_site_alias_host '
            . ($order_part ? "order by $order_part " : '')
            . ($limit_part ? "limit $limit_part" : ''),
            array(
            )
        );
        
        return $nb_item_list;
    }

    /**
     * Get Site Alias Id attribute value
     * @return int Returns the Site Alias Id value
     */
    public function getSiteAliasId() : int
    {
        return $this->getValue('nb_site_alias_id');
    }

    /**
     * Sets the Site Alias Id attribute value.
     * @param int $nb_site_alias_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setSiteAliasId(int $nb_site_alias_id) : CNabuDataObject
    {
        if ($nb_site_alias_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_site_alias_id")
            );
        }
        $this->setValue('nb_site_alias_id', $nb_site_alias_id);
        
        return $this;
    }

    /**
     * Get Server Host Id attribute value
     * @return int Returns the Server Host Id value
     */
    public function getServerHostId() : int
    {
        return $this->getValue('nb_server_host_id');
    }

    /**
     * Sets the Server Host Id attribute value.
     * @param int $nb_server_host_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setServerHostId(int $nb_server_host_id) : CNabuDataObject
    {
        if ($nb_server_host_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_server_host_id")
            );
        }
        $this->setValue('nb_server_host_id', $nb_server_host_id);
        
        return $this;
    }

    /**
     * Get Site Alias Host Status attribute value
     * @return string Returns the Site Alias Host Status value
     */
    public function getStatus() : string
    {
        return $this->getValue('nb_site_alias_host_status');
    }

    /**
     * Sets the Site Alias Host Status attribute value.
     * @param string $status New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setStatus(string $status = "D") : CNabuDataObject
    {
        if ($status === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$status")
            );
        }
        $this->setValue('nb_site_alias_host_status', $status);
        
        return $this;
    }

    /**
     * Get Site Alias Host Last Update Datetime attribute value
     * @return mixed Returns the Site Alias Host Last Update Datetime value
     */
    public function getLastUpdateDatetime()
    {
        return $this->getValue('nb_site_alias_host_last_update_datetime');
    }

    /**
     * Sets the Site Alias Host Last Update Datetime attribute value.
     * @param mixed $last_update_datetime New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setLastUpdateDatetime($last_update_datetime) : CNabuDataObject
    {
        $this->setValue('nb_site_alias_host_last_update_datetime', $last_update_datetime);
        
        return $this;
    }

    /**
     * Get Site Alias Host Last Update Error Code attribute value
     * @return int Returns the Site Alias Host Last Update Error Code value
     */
    public function getLastUpdateErrorCode() : int
    {
        return $this->getValue('nb_site_alias_host_last_update_error_code');
    }

    /**
     * Sets the Site Alias Host Last Update Error Code attribute value.
     * @param int $last_update_error_code New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setLastUpdateErrorCode(int $last_update_error_code = 0) : CNabuDataObject
    {
        if ($last_update_error_code === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$last_update_error_code")
            );
        }
        $this->setValue('nb_site_alias_host_last_update_error_code', $last_update_error_code);
        
        return $this;
    }
}
