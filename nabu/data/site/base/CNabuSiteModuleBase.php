<?php
/* ===========================================================================
 * File generated automatically by Nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/02/27 16:28:13 UTC
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
use \nabu\data\site\traits\TNabuSiteChild;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Site Module stored in the storage named nb_site_module.
 * @author Rafael Gutiérrez Martínez <rgutierrez@wiscot.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\site\base
 */
abstract class CNabuSiteModuleBase extends CNabuDBInternalObject
{
    use TNabuSiteChild;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_site An instance of CNabuSiteModuleBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_site_id, or a valid ID.
     * @param mixed $nb_site_module An instance of CNabuSiteModuleBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_site_module_id, or a valid ID.
     */
    public function __construct($nb_site = false, $nb_site_module = false)
    {
        if ($nb_site) {
            $this->transferMixedValue($nb_site, 'nb_site_id');
        }
        
        if ($nb_site_module) {
            $this->transferMixedValue($nb_site_module, 'nb_site_module_id');
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
        return 'nb_site_module';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_site_id') && $this-isValueString('nb_site_module_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_site_module '
                   . "where nb_site_id=%nb_site_id\$d "
                     . "and nb_site_module_id='%nb_site_module_id\$s' "
              )
            : null;
    }

    /**
     * Gets a filtered list of Site Module instances represented as an array. Params allows the capability of select a
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
    public static function getFilteredSiteModuleList($q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $fields_part = nb_prefixFieldList(CNabuSiteModuleBase::getStorageName(), $fields, false, true, '`');
        $order_part = nb_prefixFieldList(CNabuSiteModuleBase::getStorageName(), $fields, false, false, '`');
        
        if ($num_items !== 0) {
            $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
        } else {
            $limit_part = false;
        }
        
        $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
            "select " . ($fields_part ? $fields_part . ' ' : '* ')
            . 'from nb_site_module '
            . ($order_part ? "order by $order_part " : '')
            . ($limit_part ? "limit $limit_part" : ''),
            array(
            )
        );
        
        return $nb_item_list;
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
     * @return CNabuSiteModuleBase Returns $this
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
     * Get Site Module Id attribute value
     * @return string Returns the Site Module Id value
     */
    public function getId()
    {
        return $this->getValue('nb_site_module_id');
    }

    /**
     * Sets the Site Module Id attribute value
     * @param string $id New value for attribute
     * @return CNabuSiteModuleBase Returns $this
     */
    public function setId($id)
    {
        if ($id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$id")
            );
        }
        $this->setValue('nb_site_module_id', $id);
        
        return $this;
    }

    /**
     * Get Site Module Status attribute value
     * @return string Returns the Site Module Status value
     */
    public function getStatus()
    {
        return $this->getValue('nb_site_module_status');
    }

    /**
     * Sets the Site Module Status attribute value
     * @param string $status New value for attribute
     * @return CNabuSiteModuleBase Returns $this
     */
    public function setStatus($status)
    {
        if ($status === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$status")
            );
        }
        $this->setValue('nb_site_module_status', $status);
        
        return $this;
    }

    /**
     * Get Site Module Error Reporting attribute value
     * @return int Returns the Site Module Error Reporting value
     */
    public function getErrorReporting()
    {
        return $this->getValue('nb_site_module_error_reporting');
    }

    /**
     * Sets the Site Module Error Reporting attribute value
     * @param int $error_reporting New value for attribute
     * @return CNabuSiteModuleBase Returns $this
     */
    public function setErrorReporting($error_reporting)
    {
        if ($error_reporting === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$error_reporting")
            );
        }
        $this->setValue('nb_site_module_error_reporting', $error_reporting);
        
        return $this;
    }

    /**
     * Get Site Module Debugging attribute value
     * @return string Returns the Site Module Debugging value
     */
    public function getDebugging()
    {
        return $this->getValue('nb_site_module_debugging');
    }

    /**
     * Sets the Site Module Debugging attribute value
     * @param string $debugging New value for attribute
     * @return CNabuSiteModuleBase Returns $this
     */
    public function setDebugging($debugging)
    {
        if ($debugging === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$debugging")
            );
        }
        $this->setValue('nb_site_module_debugging', $debugging);
        
        return $this;
    }

    /**
     * Get Site Module Attributes attribute value
     * @return null|array Returns the Site Module Attributes value
     */
    public function getAttributes()
    {
        return $this->getValueJSONDecoded('nb_site_module_attributes');
    }

    /**
     * Sets the Site Module Attributes attribute value
     * @param null|string|array $attributes New value for attribute
     * @return CNabuSiteModuleBase Returns $this
     */
    public function setAttributes($attributes)
    {
        $this->setValueJSONEncoded('nb_site_module_attributes', $attributes);
        
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
