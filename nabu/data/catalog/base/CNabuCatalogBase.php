<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/08/17 10:03:48 UTC
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

namespace nabu\data\catalog\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\core\interfaces\INabuHashed;
use \nabu\core\traits\TNabuHashed;
use \nabu\data\catalog\builtin\CNabuBuiltInCatalogLanguage;
use \nabu\data\catalog\CNabuCatalog;
use \nabu\data\catalog\CNabuCatalogLanguage;
use \nabu\data\catalog\CNabuCatalogLanguageList;
use \nabu\data\catalog\CNabuCatalogList;
use \nabu\data\CNabuDataObject;
use \nabu\data\customer\CNabuCustomer;
use \nabu\data\customer\traits\TNabuCustomerChild;
use \nabu\data\lang\CNabuLanguage;
use \nabu\data\lang\CNabuLanguageList;
use \nabu\data\lang\interfaces\INabuTranslated;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslated;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Catalog stored in the storage named nb_catalog.
 * @version 3.0.12 Surface
 * @package \nabu\data\catalog\base
 */
abstract class CNabuCatalogBase extends CNabuDBInternalObject implements INabuTranslated, INabuHashed
{
    use TNabuCustomerChild;
    use TNabuHashed;
    use TNabuTranslated;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_catalog An instance of CNabuCatalogBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_catalog_id, or a valid ID.
     */
    public function __construct($nb_catalog = false)
    {
        if ($nb_catalog) {
            $this->transferMixedValue($nb_catalog, 'nb_catalog_id');
        }
        
        parent::__construct();
        $this->__translatedConstruct();
        $this->translations_list = new CNabuCatalogLanguageList();
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
        return 'nb_catalog';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_catalog_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_catalog '
                   . "where nb_catalog_id=%nb_catalog_id\$d "
              )
            : null;
    }

    /**
     * Find an instance identified by nb_catalog_hash field.
     * @param string $hash Hash to search
     * @return CNabuDataObject Returns a valid instance if exists or null if not.
     */
    public static function findByHash(string $hash)
    {
        return CNabuCatalog::buildObjectFromSQL(
                'select * '
                . 'from nb_catalog '
               . "where nb_catalog_hash='%hash\$s'",
                array(
                    'hash' => $hash
                )
        );
    }

    /**
     * Find an instance identified by nb_catalog_key field.
     * @param mixed $nb_customer Customer that owns Catalog
     * @param string $key Key to search
     * @return CNabuCatalog Returns a valid instance if exists or null if not.
     */
    public static function findByKey($nb_customer, $key)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, 'nb_customer_id');
        if (is_numeric($nb_customer_id)) {
            $retval = CNabuCatalog::buildObjectFromSQL(
                    'select * '
                    . 'from nb_catalog '
                   . 'where nb_customer_id=%cust_id$d '
                     . "and nb_catalog_key='%key\$s'",
                    array(
                        'cust_id' => $nb_customer_id,
                        'key' => $key
                    )
            );
        } else {
            $retval = null;
        }
        
        return $retval;
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_catalog_id' is the index, and each
     * value is an instance of class CNabuCatalogBase.
     * @param CNabuCustomer $nb_customer The CNabuCustomer instance of the Customer that owns the Catalog List.
     * @return mixed Returns and array with all items.
     */
    public static function getAllCatalogs(CNabuCustomer $nb_customer)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, 'nb_customer_id');
        if (is_numeric($nb_customer_id)) {
            $retval = forward_static_call(
            array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_catalog_id',
                'select * '
                . 'from nb_catalog '
               . 'where nb_customer_id=%cust_id$d',
                array(
                    'cust_id' => $nb_customer_id
                ),
                $nb_customer
            );
        } else {
            $retval = new CNabuCatalogList();
        }
        
        return $retval;
    }

    /**
     * Gets a filtered list of Catalog instances represented as an array. Params allows the capability of select a
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
    public static function getFilteredCatalogList($nb_customer, $q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, NABU_CUSTOMER_FIELD_ID);
        if (is_numeric($nb_customer_id)) {
            $fields_part = nb_prefixFieldList(CNabuCatalogBase::getStorageName(), $fields, false, true, '`');
            $order_part = nb_prefixFieldList(CNabuCatalogBase::getStorageName(), $fields, false, false, '`');
        
            if ($num_items !== 0) {
                $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
            } else {
                $limit_part = false;
            }
        
            $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
                "select " . ($fields_part ? $fields_part . ' ' : '* ')
                . 'from nb_catalog '
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
     * Check if the instance passed as parameter $translation is a valid child translation for this object
     * @param INabuTranslation $translation Translation instance to check
     * @return bool Return true if a valid object is passed as instance or false elsewhere
     */
    protected function checkForValidTranslationInstance($translation)
    {
        return ($translation !== null &&
                $translation instanceof CNabuCatalogLanguage &&
                $translation->matchValue($this, 'nb_catalog_id')
        );
    }

    /**
     * Get all language instances corresponding to available translations.
     * @param bool $force If true force to reload languages list from storage.
     * @return null|array Return an array of \nabu\data\lang\CNabuLanguage instances if they have translations or null
     * if not.
     */
    public function getLanguages($force = false)
    {
        if (!CNabuEngine::getEngine()->isOperationModeStandalone() &&
            ($this->languages_list->getSize() === 0 || $force)
        ) {
            $this->languages_list = CNabuCatalogLanguage::getLanguagesForTranslatedObject($this);
        }
        
        return $this->languages_list;
    }

    /**
     * Gets available translation instances.
     * @param bool $force If true force to reload translations list from storage.
     * @return null|array Return an array of \nabu\data\catalog\CNabuCatalogLanguage instances if they have
     * translations or null if not.
     */
    public function getTranslations($force = false)
    {
        if (!CNabuEngine::getEngine()->isOperationModeStandalone() &&
            ($this->translations_list->getSize() === 0 || $force)
        ) {
            $this->translations_list = CNabuCatalogLanguage::getTranslationsForTranslatedObject($this);
        }
        
        return $this->translations_list;
    }

    /**
     * Creates a new translation instance. I the translation already exists then replaces ancient translation with this
     * new.
     * @param int|string|CNabuDataObject $nb_language A valid Id or object containing a nb_language_id field to
     * identify the language of new translation.
     * @return CNabuCatalogLanguage Returns the created instance to store translation or null if not valid language was
     * provided.
     */
    public function newTranslation($nb_language)
    {
        $nb_language_id = nb_getMixedValue($nb_language, NABU_LANG_FIELD_ID);
        if (is_numeric($nb_language_id) || nb_isValidGUID($nb_language_id)) {
            $nb_translation = $this->isBuiltIn()
                            ? new CNabuBuiltInCatalogLanguage()
                            : new CNabuCatalogLanguage()
            ;
            $nb_translation->transferValue($this, 'nb_catalog_id');
            $nb_translation->transferValue($nb_language, NABU_LANG_FIELD_ID);
            $this->setTranslation($nb_translation);
        } else {
            $nb_translation = null;
        }
        
        return $nb_translation;
    }

    /**
     * Get all language instances used along of all Catalog set of a Customer
     * @param mixed $nb_customer A CNabuDataObject instance containing a field named nb_customer_id or a Customer ID
     * @return CNabuLanguageList Returns the list of language instances used.
     */
    public static function getCustomerUsedLanguages($nb_customer)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, NABU_CUSTOMER_FIELD_ID);
        if (is_numeric($nb_customer_id)) {
            $nb_language_list = CNabuLanguage::buildObjectListFromSQL(
                'nb_language_id',
                'select l.* '
                . 'from nb_language l, '
                     . '(select distinct nb_language_id '
                        . 'from nb_catalog ca, nb_catalog_lang cal '
                       . 'where ca.nb_catalog_id=cal.nb_catalog_id '
                         . 'and ca.nb_customer_id=%cust_id$d) as lid '
               . 'where l.nb_language_id=lid.nb_language_id',
                array('cust_id' => $nb_customer_id)
            );
        } else {
            $nb_language_list = new CNabuLanguageList();
        }
        
        return $nb_language_list;
    }

    /**
     * Overrides refresh method to add translations branch to refresh.
     * @param bool $force Forces to reload entities from the database storage.
     * @param bool $cascade Forces to reload child entities from the database storage.
     * @return bool Returns true if transations are empty or refreshed.
     */
    public function refresh(bool $force = false, bool $cascade = false) : bool
    {
        return parent::refresh($force, $cascade) && $this->appendTranslatedRefresh($force);
    }

    /**
     * Get Catalog Id attribute value
     * @return int Returns the Catalog Id value
     */
    public function getId() : int
    {
        return $this->getValue('nb_catalog_id');
    }

    /**
     * Sets the Catalog Id attribute value.
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
        $this->setValue('nb_catalog_id', $id);
        
        return $this;
    }

    /**
     * Get Catalog Hash attribute value
     * @return null|string Returns the Catalog Hash value
     */
    public function getHash()
    {
        return $this->getValue('nb_catalog_hash');
    }

    /**
     * Sets the Catalog Hash attribute value.
     * @param null|string $hash New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setHash(string $hash = null) : CNabuDataObject
    {
        $this->setValue('nb_catalog_hash', $hash);
        
        return $this;
    }

    /**
     * Get Customer Id attribute value
     * @return int Returns the Customer Id value
     */
    public function getCustomerId() : int
    {
        return $this->getValue('nb_customer_id');
    }

    /**
     * Sets the Customer Id attribute value.
     * @param int $nb_customer_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCustomerId(int $nb_customer_id) : CNabuDataObject
    {
        if ($nb_customer_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_customer_id")
            );
        }
        $this->setValue('nb_customer_id', $nb_customer_id);
        
        return $this;
    }

    /**
     * Get Catalog Default Language Id attribute value
     * @return null|int Returns the Catalog Default Language Id value
     */
    public function getDefaultLanguageId()
    {
        return $this->getValue('nb_catalog_default_language_id');
    }

    /**
     * Sets the Catalog Default Language Id attribute value.
     * @param null|int $default_language_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setDefaultLanguageId(int $default_language_id = null) : CNabuDataObject
    {
        $this->setValue('nb_catalog_default_language_id', $default_language_id);
        
        return $this;
    }

    /**
     * Get Catalog Key attribute value
     * @return null|string Returns the Catalog Key value
     */
    public function getKey()
    {
        return $this->getValue('nb_catalog_key');
    }

    /**
     * Sets the Catalog Key attribute value.
     * @param null|string $key New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setKey(string $key = null) : CNabuDataObject
    {
        $this->setValue('nb_catalog_key', $key);
        
        return $this;
    }

    /**
     * Get Catalog Attributes attribute value
     * @return null|array Returns the Catalog Attributes value
     */
    public function getAttributes()
    {
        return $this->getValueJSONDecoded('nb_catalog_attributes');
    }

    /**
     * Sets the Catalog Attributes attribute value.
     * @param null|string|array $attributes New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setAttributes(string $attributes = null) : CNabuDataObject
    {
        $this->setValueJSONEncoded('nb_catalog_attributes', $attributes);
        
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
        $trdata = $this->appendTranslatedTreeData($trdata, $nb_language, $dataonly);
        
        return $trdata;
    }
}
