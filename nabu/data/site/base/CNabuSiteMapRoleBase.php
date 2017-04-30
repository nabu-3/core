<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/04/30 15:16:39 UTC
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
 * Class to manage the entity Site Map Role stored in the storage named nb_site_map_role.
 * @version 3.0.12 Surface
 * @package \nabu\data\site\base
 */
abstract class CNabuSiteMapRoleBase extends CNabuDBInternalObject
{
    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_site_map An instance of CNabuSiteMapRoleBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_site_map_id, or a valid ID.
     * @param mixed $nb_role An instance of CNabuSiteMapRoleBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_role_id, or a valid ID.
     */
    public function __construct($nb_site_map = false, $nb_role = false)
    {
        if ($nb_site_map) {
            $this->transferMixedValue($nb_site_map, 'nb_site_map_id');
        }
        
        if ($nb_role) {
            $this->transferMixedValue($nb_role, 'nb_role_id');
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
        return 'nb_site_map_role';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_site_map_id') && $this->isValueNumeric('nb_role_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_site_map_role '
                   . "where nb_site_map_id=%nb_site_map_id\$d "
                     . "and nb_role_id=%nb_role_id\$d "
              )
            : null;
    }

    /**
     * Gets a filtered list of Site Map Role instances represented as an array. Params allows the capability of select
     * a subset of fields, order by concrete fields, or truncate the list by a number of rows starting in an offset.
     * @throws \nabu\core\exceptions\ENabuCoreException Raises an exception if $fields or $order have invalid values.
     * @param string $q Query string to filter results using a context index.
     * @param string|array $fields List of fields to put in the results.
     * @param string|array $order List of fields to order the results. Each field can be suffixed with "ASC" or "DESC"
     * to determine the short order
     * @param int $offset Offset of first row in the results having the first row at offset 0.
     * @param int $num_items Number of continue rows to get as maximum in the results.
     * @return array Returns an array with all rows found using the criteria.
     */
    public static function getFilteredSiteMapRoleList($q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $fields_part = nb_prefixFieldList(CNabuSiteMapRoleBase::getStorageName(), $fields, false, true, '`');
        $order_part = nb_prefixFieldList(CNabuSiteMapRoleBase::getStorageName(), $fields, false, false, '`');
        
        if ($num_items !== 0) {
            $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
        } else {
            $limit_part = false;
        }
        
        $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
            "select " . ($fields_part ? $fields_part . ' ' : '* ')
            . 'from nb_site_map_role '
            . ($order_part ? "order by $order_part " : '')
            . ($limit_part ? "limit $limit_part" : ''),
            array(
            )
        );
        
        return $nb_item_list;
    }

    /**
     * Get Site Map Id attribute value
     * @return int Returns the Site Map Id value
     */
    public function getSiteMapId() : int
    {
        return $this->getValue('nb_site_map_id');
    }

    /**
     * Sets the Site Map Id attribute value.
     * @param int $nb_site_map_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setSiteMapId(int $nb_site_map_id = 0) : CNabuDataObject
    {
        if ($nb_site_map_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_site_map_id")
            );
        }
        $this->setValue('nb_site_map_id', $nb_site_map_id);
        
        return $this;
    }

    /**
     * Get Role Id attribute value
     * @return int Returns the Role Id value
     */
    public function getRoleId() : int
    {
        return $this->getValue('nb_role_id');
    }

    /**
     * Sets the Role Id attribute value.
     * @param int $nb_role_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setRoleId(int $nb_role_id = 0) : CNabuDataObject
    {
        if ($nb_role_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_role_id")
            );
        }
        $this->setValue('nb_role_id', $nb_role_id);
        
        return $this;
    }

    /**
     * Get Site Map Role Zone attribute value
     * @return string Returns the Site Map Role Zone value
     */
    public function getZone() : string
    {
        return $this->getValue('nb_site_map_role_zone');
    }

    /**
     * Sets the Site Map Role Zone attribute value.
     * @param string $zone New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setZone(string $zone = "B") : CNabuDataObject
    {
        if ($zone === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$zone")
            );
        }
        $this->setValue('nb_site_map_role_zone', $zone);
        
        return $this;
    }
}
