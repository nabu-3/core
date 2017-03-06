<?php
/* ===========================================================================
 * File generated automatically by Nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/03/05 23:04:15 UTC
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

namespace nabu\data\site\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\site\CNabuSite;
use \nabu\data\site\traits\TNabuSiteChild;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Site Alias stored in the storage named nb_site_alias.
 * @author Rafael Gutiérrez Martínez <rgutierrez@wiscot.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\site\base
 */
abstract class CNabuSiteAliasBase extends CNabuDBInternalObject
{
    use TNabuSiteChild;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_site_alias An instance of CNabuSiteAliasBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_site_alias_id, or a valid ID.
     */
    public function __construct($nb_site_alias = false)
    {
        if ($nb_site_alias) {
            $this->transferMixedValue($nb_site_alias, 'nb_site_alias_id');
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
        return 'nb_site_alias';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_site_alias_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_site_alias '
                   . "where nb_site_alias_id=%nb_site_alias_id\$d "
              )
            : null;
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_site_alias_id' is the index, and each
     * value is an instance of class CNabuSiteAliasBase.
     * @param CNabuSite $nb_site The CNabuSite instance of the Site that owns the Site Alias List.
     * @return mixed Returns and array with all items.
     */
    public static function getAllSiteAliass(CNabuSite $nb_site)
    {
        $nb_site_id = nb_getMixedValue($nb_site, 'nb_site_id');
        if (is_numeric($nb_site_id)) {
            $retval = forward_static_call(
            array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_site_alias_id',
                'select * '
                . 'from nb_site_alias '
               . 'where nb_site_id=%site_id$d',
                array(
                    'site_id' => $nb_site_id
                ),
                $nb_site
            );
        } else {
            $retval = null;
        }
        
        return $retval;
    }

    /**
     * Gets a filtered list of Site Alias instances represented as an array. Params allows the capability of select a
     * subset of fields, order by concrete fields, or truncate the list by a number of rows starting in an offset.
     * @throws \nabu\core\exceptions\ENabuCoreException Raises an exception if $fields or $order have invalid values.
     * @param mixed $nb_site Site instance, object containing a Site Id field or an Id.
     * @param string $q Query string to filter results using a context index.
     * @param string|array $fields List of fields to put in the results.
     * @param string|array $order List of fields to order the results. Each field can be suffixed with "ASC" or "DESC"
     * to determine the short order
     * @param int $offset Offset of first row in the results having the first row at offset 0.
     * @param int $num_items Number of continue rows to get as maximum in the results.
     * @return array Returns an array with all rows found using the criteria.
     */
    public static function getFilteredSiteAliasList($nb_site, $q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $nb_site_id = nb_getMixedValue($nb_customer, NABU_SITE_FIELD_ID);
        if (is_numeric($nb_site_id)) {
            $fields_part = nb_prefixFieldList(CNabuSiteAliasBase::getStorageName(), $fields, false, true, '`');
            $order_part = nb_prefixFieldList(CNabuSiteAliasBase::getStorageName(), $fields, false, false, '`');
        
            if ($num_items !== 0) {
                $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
            } else {
                $limit_part = false;
            }
        
            $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
                "select " . ($fields_part ? $fields_part . ' ' : '* ')
                . 'from nb_site_alias '
               . 'where ' . NABU_SITE_FIELD_ID . '=%site_id$d '
                . ($order_part ? "order by $order_part " : '')
                . ($limit_part ? "limit $limit_part" : ''),
                array(
                    'site_id' => $nb_site_id
                )
            );
        } else {
            $nb_item_list = null;
        }
        
        return $nb_item_list;
    }

    /**
     * Get Site Alias Id attribute value
     * @return int Returns the Site Alias Id value
     */
    public function getId()
    {
        return $this->getValue('nb_site_alias_id');
    }

    /**
     * Sets the Site Alias Id attribute value
     * @param int $id New value for attribute
     * @return CNabuSiteAliasBase Returns $this
     */
    public function setId($id)
    {
        if ($id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$id")
            );
        }
        $this->setValue('nb_site_alias_id', $id);
        
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
     * Sets the User Id attribute value
     * @param null|int $nb_user_id New value for attribute
     * @return CNabuSiteAliasBase Returns $this
     */
    public function setUserId($nb_user_id)
    {
        $this->setValue('nb_user_id', $nb_user_id);
        
        return $this;
    }

    /**
     * Get Site Id attribute value
     * @return int Returns the Site Id value
     */
    public function getSiteId()
    {
        return $this->getValue('nb_site_id');
    }

    /**
     * Sets the Site Id attribute value
     * @param int $nb_site_id New value for attribute
     * @return CNabuSiteAliasBase Returns $this
     */
    public function setSiteId($nb_site_id)
    {
        if ($nb_site_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_site_id")
            );
        }
        $this->setValue('nb_site_id', $nb_site_id);
        
        return $this;
    }

    /**
     * Get Domain Zone Host Id attribute value
     * @return int Returns the Domain Zone Host Id value
     */
    public function getDomainZoneHostId()
    {
        return $this->getValue('nb_domain_zone_host_id');
    }

    /**
     * Sets the Domain Zone Host Id attribute value
     * @param int $nb_domain_zone_host_id New value for attribute
     * @return CNabuSiteAliasBase Returns $this
     */
    public function setDomainZoneHostId($nb_domain_zone_host_id)
    {
        if ($nb_domain_zone_host_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_domain_zone_host_id")
            );
        }
        $this->setValue('nb_domain_zone_host_id', $nb_domain_zone_host_id);
        
        return $this;
    }

    /**
     * Get Cluster Group Service Id attribute value
     * @return null|int Returns the Cluster Group Service Id value
     */
    public function getClusterGroupServiceId()
    {
        return $this->getValue('nb_cluster_group_service_id');
    }

    /**
     * Sets the Cluster Group Service Id attribute value
     * @param null|int $nb_cluster_group_service_id New value for attribute
     * @return CNabuSiteAliasBase Returns $this
     */
    public function setClusterGroupServiceId($nb_cluster_group_service_id)
    {
        $this->setValue('nb_cluster_group_service_id', $nb_cluster_group_service_id);
        
        return $this;
    }

    /**
     * Get Site Alias Type attribute value
     * @return string Returns the Site Alias Type value
     */
    public function getType()
    {
        return $this->getValue('nb_site_alias_type');
    }

    /**
     * Sets the Site Alias Type attribute value
     * @param string $type New value for attribute
     * @return CNabuSiteAliasBase Returns $this
     */
    public function setType($type)
    {
        if ($type === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$type")
            );
        }
        $this->setValue('nb_site_alias_type', $type);
        
        return $this;
    }

    /**
     * Get Site Alias Status attribute value
     * @return string Returns the Site Alias Status value
     */
    public function getStatus()
    {
        return $this->getValue('nb_site_alias_status');
    }

    /**
     * Sets the Site Alias Status attribute value
     * @param string $status New value for attribute
     * @return CNabuSiteAliasBase Returns $this
     */
    public function setStatus($status)
    {
        if ($status === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$status")
            );
        }
        $this->setValue('nb_site_alias_status', $status);
        
        return $this;
    }

    /**
     * Get Site Alias Parent attribute value
     * @return null|int Returns the Site Alias Parent value
     */
    public function getParent()
    {
        return $this->getValue('nb_site_alias_parent');
    }

    /**
     * Sets the Site Alias Parent attribute value
     * @param null|int $parent New value for attribute
     * @return CNabuSiteAliasBase Returns $this
     */
    public function setParent($parent)
    {
        $this->setValue('nb_site_alias_parent', $parent);
        
        return $this;
    }

    /**
     * Get Site Alias Storage Id attribute value
     * @return null|string Returns the Site Alias Storage Id value
     */
    public function getStorageId()
    {
        return $this->getValue('nb_site_alias_storage_id');
    }

    /**
     * Sets the Site Alias Storage Id attribute value
     * @param null|string $storage_id New value for attribute
     * @return CNabuSiteAliasBase Returns $this
     */
    public function setStorageId($storage_id)
    {
        $this->setValue('nb_site_alias_storage_id', $storage_id);
        
        return $this;
    }
}
