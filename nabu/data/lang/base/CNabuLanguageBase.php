<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/06/09 12:39:15 UTC
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

namespace nabu\data\lang\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\core\interfaces\INabuHashed;
use \nabu\core\traits\TNabuHashed;
use \nabu\data\CNabuDataObject;
use \nabu\data\lang\CNabuLanguage;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Language stored in the storage named nb_language.
 * @version 3.0.12 Surface
 * @package \nabu\data\lang\base
 */
abstract class CNabuLanguageBase extends CNabuDBInternalObject implements INabuHashed
{
    use TNabuHashed;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_language An instance of CNabuLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_language_id, or a valid ID.
     */
    public function __construct($nb_language = false)
    {
        if ($nb_language) {
            $this->transferMixedValue($nb_language, 'nb_language_id');
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
        return 'nb_language';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_language_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_language '
                   . "where nb_language_id=%nb_language_id\$d "
              )
            : null;
    }

    /**
     * Find an instance identified by nb_language_hash field.
     * @param string $hash Hash to search
     * @return CNabuDataObject Returns a valid instance if exists or null if not.
     */
    public static function findByHash(string $hash)
    {
        return CNabuLanguage::buildObjectFromSQL(
                'select * '
                . 'from nb_language '
               . "where nb_language_hash='%hash\$s'",
                array(
                    'hash' => $hash
                )
        );
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_language_id' is the index, and each
     * value is an instance of class CNabuLanguageBase.
     * @return mixed Returns and array with all items.
     */
    public static function getAllLanguages()
    {
        return forward_static_call(
                array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_language_id',
                'select * from nb_language'
        );
    }

    /**
     * Gets a filtered list of Language instances represented as an array. Params allows the capability of select a
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
    public static function getFilteredLanguageList($q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $fields_part = nb_prefixFieldList(CNabuLanguageBase::getStorageName(), $fields, false, true, '`');
        $order_part = nb_prefixFieldList(CNabuLanguageBase::getStorageName(), $fields, false, false, '`');
        
        if ($num_items !== 0) {
            $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
        } else {
            $limit_part = false;
        }
        
        $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
            "select " . ($fields_part ? $fields_part . ' ' : '* ')
            . 'from nb_language '
            . ($order_part ? "order by $order_part " : '')
            . ($limit_part ? "limit $limit_part" : ''),
            array(
            )
        );
        
        return $nb_item_list;
    }

    /**
     * Get Language Id attribute value
     * @return int Returns the Language Id value
     */
    public function getId() : int
    {
        return $this->getValue('nb_language_id');
    }

    /**
     * Sets the Language Id attribute value.
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
        $this->setValue('nb_language_id', $id);
        
        return $this;
    }

    /**
     * Get Language Hash attribute value
     * @return null|string Returns the Language Hash value
     */
    public function getHash()
    {
        return $this->getValue('nb_language_hash');
    }

    /**
     * Sets the Language Hash attribute value.
     * @param null|string $hash New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setHash(string $hash = null) : CNabuDataObject
    {
        $this->setValue('nb_language_hash', $hash);
        
        return $this;
    }

    /**
     * Get Language Type attribute value
     * @return string Returns the Language Type value
     */
    public function getType() : string
    {
        return $this->getValue('nb_language_type');
    }

    /**
     * Sets the Language Type attribute value.
     * @param string $type New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setType(string $type = "C") : CNabuDataObject
    {
        if ($type === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$type")
            );
        }
        $this->setValue('nb_language_type', $type);
        
        return $this;
    }

    /**
     * Get Language Enabled attribute value
     * @return null|string Returns the Language Enabled value
     */
    public function getEnabled()
    {
        return $this->getValue('nb_language_enabled');
    }

    /**
     * Sets the Language Enabled attribute value.
     * @param null|string $enabled New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setEnabled(string $enabled = "F") : CNabuDataObject
    {
        $this->setValue('nb_language_enabled', $enabled);
        
        return $this;
    }

    /**
     * Get Language ISO639 1 attribute value
     * @return mixed Returns the Language ISO639 1 value
     */
    public function getISO6391()
    {
        return $this->getValue('nb_language_ISO639_1');
    }

    /**
     * Sets the Language ISO639 1 attribute value.
     * @param mixed $ISO639_1 New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setISO6391($ISO639_1) : CNabuDataObject
    {
        $this->setValue('nb_language_ISO639_1', $ISO639_1);
        
        return $this;
    }

    /**
     * Get Language ISO639 2 attribute value
     * @return mixed Returns the Language ISO639 2 value
     */
    public function getISO6392()
    {
        return $this->getValue('nb_language_ISO639_2');
    }

    /**
     * Sets the Language ISO639 2 attribute value.
     * @param mixed $ISO639_2 New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setISO6392($ISO639_2) : CNabuDataObject
    {
        $this->setValue('nb_language_ISO639_2', $ISO639_2);
        
        return $this;
    }

    /**
     * Get Language Is Api attribute value
     * @return string Returns the Language Is Api value
     */
    public function getIsApi() : string
    {
        return $this->getValue('nb_language_is_api');
    }

    /**
     * Sets the Language Is Api attribute value.
     * @param string $is_api New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setIsApi(string $is_api = "F") : CNabuDataObject
    {
        if ($is_api === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$is_api")
            );
        }
        $this->setValue('nb_language_is_api', $is_api);
        
        return $this;
    }

    /**
     * Get Language Default Country Code attribute value
     * @return null|string Returns the Language Default Country Code value
     */
    public function getDefaultCountryCode()
    {
        return $this->getValue('nb_language_default_country_code');
    }

    /**
     * Sets the Language Default Country Code attribute value.
     * @param null|string $default_country_code New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setDefaultCountryCode(string $default_country_code = null) : CNabuDataObject
    {
        $this->setValue('nb_language_default_country_code', $default_country_code);
        
        return $this;
    }

    /**
     * Get WGEO Language Id attribute value
     * @return null|string Returns the WGEO Language Id value
     */
    public function getWGEOLanguageId()
    {
        return $this->getValue('wgeo_language_id');
    }

    /**
     * Sets the WGEO Language Id attribute value.
     * @param null|string $wgeo_language_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setWGEOLanguageId(string $wgeo_language_id = null) : CNabuDataObject
    {
        $this->setValue('wgeo_language_id', $wgeo_language_id);
        
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
     * Sets the WGEO Country Id attribute value.
     * @param null|int $wgeo_country_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setWGEOCountryId(int $wgeo_country_id = null) : CNabuDataObject
    {
        $this->setValue('wgeo_country_id', $wgeo_country_id);
        
        return $this;
    }

    /**
     * Get Language Name attribute value
     * @return string Returns the Language Name value
     */
    public function getName() : string
    {
        return $this->getValue('nb_language_name');
    }

    /**
     * Sets the Language Name attribute value.
     * @param string $name New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setName(string $name) : CNabuDataObject
    {
        if ($name === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$name")
            );
        }
        $this->setValue('nb_language_name', $name);
        
        return $this;
    }

    /**
     * Get Language Flag URL attribute value
     * @return null|string Returns the Language Flag URL value
     */
    public function getFlagURL()
    {
        return $this->getValue('nb_language_flag_url');
    }

    /**
     * Sets the Language Flag URL attribute value.
     * @param null|string $flag_url New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setFlagURL(string $flag_url = null) : CNabuDataObject
    {
        $this->setValue('nb_language_flag_url', $flag_url);
        
        return $this;
    }
}
