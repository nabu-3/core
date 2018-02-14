<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2018/02/14 15:55:22 UTC
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

namespace nabu\data\catalog\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\CNabuDataObject;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Catalog Item Tag stored in the storage named nb_catalog_item_tag.
 * @version 3.0.12 Surface
 * @package \nabu\data\catalog\base
 */
abstract class CNabuCatalogItemTagBase extends CNabuDBInternalObject
{
    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_catalog_item An instance of CNabuCatalogItemTagBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_catalog_item_id, or a valid ID.
     * @param mixed $nb_catalog_tag An instance of CNabuCatalogItemTagBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_catalog_tag_id, or a valid ID.
     */
    public function __construct($nb_catalog_item = false, $nb_catalog_tag = false)
    {
        if ($nb_catalog_item) {
            $this->transferMixedValue($nb_catalog_item, 'nb_catalog_item_id');
        }
        
        if ($nb_catalog_tag) {
            $this->transferMixedValue($nb_catalog_tag, 'nb_catalog_tag_id');
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
        return 'nb_catalog_item_tag';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_catalog_item_id') && $this->isValueNumeric('nb_catalog_tag_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_catalog_item_tag '
                   . "where nb_catalog_item_id=%nb_catalog_item_id\$d "
                     . "and nb_catalog_tag_id=%nb_catalog_tag_id\$d "
              )
            : null;
    }

    /**
     * Gets a filtered list of Catalog Item Tag instances represented as an array. Params allows the capability of
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
    public static function getFilteredCatalogItemTagList($q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $fields_part = nb_prefixFieldList(CNabuCatalogItemTagBase::getStorageName(), $fields, false, true, '`');
        $order_part = nb_prefixFieldList(CNabuCatalogItemTagBase::getStorageName(), $fields, false, false, '`');
        
        if ($num_items !== 0) {
            $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
        } else {
            $limit_part = false;
        }
        
        $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
            "select " . ($fields_part ? $fields_part . ' ' : '* ')
            . 'from nb_catalog_item_tag '
            . ($order_part ? "order by $order_part " : '')
            . ($limit_part ? "limit $limit_part" : ''),
            array(
            )
        );
        
        return $nb_item_list;
    }

    /**
     * Get Catalog Item Id attribute value
     * @return int Returns the Catalog Item Id value
     */
    public function getCatalogItemId() : int
    {
        return $this->getValue('nb_catalog_item_id');
    }

    /**
     * Sets the Catalog Item Id attribute value.
     * @param int $nb_catalog_item_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCatalogItemId(int $nb_catalog_item_id) : CNabuDataObject
    {
        if ($nb_catalog_item_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_catalog_item_id")
            );
        }
        $this->setValue('nb_catalog_item_id', $nb_catalog_item_id);
        
        return $this;
    }

    /**
     * Get Catalog Tag Id attribute value
     * @return int Returns the Catalog Tag Id value
     */
    public function getCatalogTagId() : int
    {
        return $this->getValue('nb_catalog_tag_id');
    }

    /**
     * Sets the Catalog Tag Id attribute value.
     * @param int $nb_catalog_tag_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCatalogTagId(int $nb_catalog_tag_id) : CNabuDataObject
    {
        if ($nb_catalog_tag_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_catalog_tag_id")
            );
        }
        $this->setValue('nb_catalog_tag_id', $nb_catalog_tag_id);
        
        return $this;
    }
}
