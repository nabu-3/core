<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/04/16 23:21:56 UTC
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
use \nabu\data\catalog\builtin\CNabuBuiltInCatalogTaxonomyLanguage;
use \nabu\data\catalog\CNabuCatalog;
use \nabu\data\catalog\CNabuCatalogTaxonomy;
use \nabu\data\catalog\CNabuCatalogTaxonomyLanguage;
use \nabu\data\catalog\CNabuCatalogTaxonomyLanguageList;
use \nabu\data\catalog\CNabuCatalogTaxonomyList;
use \nabu\data\catalog\traits\TNabuCatalogChild;
use \nabu\data\CNabuDataObject;
use \nabu\data\lang\interfaces\INabuTranslated;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslated;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Catalog Taxonomy stored in the storage named nb_catalog_taxonomy.
 * @author Rafael Gutiérrez Martínez <rgutierrez@nabu-3.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\catalog\base
 */
abstract class CNabuCatalogTaxonomyBase extends CNabuDBInternalObject implements INabuTranslated
{
    use TNabuCatalogChild;
    use TNabuTranslated;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_catalog_taxonomy An instance of CNabuCatalogTaxonomyBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_catalog_taxonomy_id, or a valid ID.
     */
    public function __construct($nb_catalog_taxonomy = false)
    {
        if ($nb_catalog_taxonomy) {
            $this->transferMixedValue($nb_catalog_taxonomy, 'nb_catalog_taxonomy_id');
        }
        
        parent::__construct();
        $this->__translatedConstruct();
        $this->translations_list = new CNabuCatalogTaxonomyLanguageList();
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
        return 'nb_catalog_taxonomy';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_catalog_taxonomy_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_catalog_taxonomy '
                   . "where nb_catalog_taxonomy_id=%nb_catalog_taxonomy_id\$d "
              )
            : null;
    }

    /**
     * Find an instance identified by nb_catalog_taxonomy_key field.
     * @param mixed $nb_catalog Catalog that owns Catalog Taxonomy
     * @param string $key Key to search
     * @return CNabuCatalogTaxonomy Returns a valid instance if exists or null if not.
     */
    public static function findByKey($nb_catalog, $key)
    {
        $nb_catalog_id = nb_getMixedValue($nb_catalog, 'nb_catalog_id');
        if (is_numeric($nb_catalog_id)) {
            $retval = CNabuCatalogTaxonomy::buildObjectFromSQL(
                    'select * '
                    . 'from nb_catalog_taxonomy '
                   . 'where nb_catalog_id=%catalog_id$d '
                     . "and nb_catalog_taxonomy_key='%key\$s'",
                    array(
                        'catalog_id' => $nb_catalog_id,
                        'key' => $key
                    )
            );
        } else {
            $retval = null;
        }
        
        return $retval;
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_catalog_taxonomy_id' is the index, and
     * each value is an instance of class CNabuCatalogTaxonomyBase.
     * @param CNabuCatalog $nb_catalog The CNabuCatalog instance of the Catalog that owns the Catalog Taxonomy List.
     * @return mixed Returns and array with all items.
     */
    public static function getAllCatalogTaxonomies(CNabuCatalog $nb_catalog)
    {
        $nb_catalog_id = nb_getMixedValue($nb_catalog, 'nb_catalog_id');
        if (is_numeric($nb_catalog_id)) {
            $retval = forward_static_call(
            array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_catalog_taxonomy_id',
                'select * '
                . 'from nb_catalog_taxonomy '
               . 'where nb_catalog_id=%catalog_id$d',
                array(
                    'catalog_id' => $nb_catalog_id
                ),
                $nb_catalog
            );
        } else {
            $retval = new CNabuCatalogTaxonomyList();
        }
        
        return $retval;
    }

    /**
     * Gets a filtered list of Catalog Taxonomy instances represented as an array. Params allows the capability of
     * select a subset of fields, order by concrete fields, or truncate the list by a number of rows starting in an
     * offset.
     * @throws \nabu\core\exceptions\ENabuCoreException Raises an exception if $fields or $order have invalid values.
     * @param mixed $nb_catalog Catalog instance, object containing a Catalog Id field or an Id.
     * @param string $q Query string to filter results using a context index.
     * @param string|array $fields List of fields to put in the results.
     * @param string|array $order List of fields to order the results. Each field can be suffixed with "ASC" or "DESC"
     * to determine the short order
     * @param int $offset Offset of first row in the results having the first row at offset 0.
     * @param int $num_items Number of continue rows to get as maximum in the results.
     * @return array Returns an array with all rows found using the criteria.
     */
    public static function getFilteredCatalogTaxonomyList($nb_catalog, $q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $nb_catalog_id = nb_getMixedValue($nbu_customer, NABU_CATALOG_FIELD_ID);
        if (is_numeric($nb_catalog_id)) {
            $fields_part = nb_prefixFieldList(CNabuCatalogTaxonomyBase::getStorageName(), $fields, false, true, '`');
            $order_part = nb_prefixFieldList(CNabuCatalogTaxonomyBase::getStorageName(), $fields, false, false, '`');
        
            if ($num_items !== 0) {
                $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
            } else {
                $limit_part = false;
            }
        
            $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
                "select " . ($fields_part ? $fields_part . ' ' : '* ')
                . 'from nb_catalog_taxonomy '
               . 'where ' . NABU_CATALOG_FIELD_ID . '=%catalog_id$d '
                . ($order_part ? "order by $order_part " : '')
                . ($limit_part ? "limit $limit_part" : ''),
                array(
                    'catalog_id' => $nb_catalog_id
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
                $translation instanceof CNabuCatalogTaxonomyLanguage &&
                $translation->matchValue($this, 'nb_catalog_taxonomy_id')
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
            $this->languages_list = CNabuCatalogTaxonomyLanguage::getLanguagesForTranslatedObject($this);
        }
        
        return $this->languages_list;
    }

    /**
     * Gets available translation instances.
     * @param bool $force If true force to reload translations list from storage.
     * @return null|array Return an array of \nabu\data\catalog\CNabuCatalogTaxonomyLanguage instances if they have
     * translations or null if not.
     */
    public function getTranslations($force = false)
    {
        if (!CNabuEngine::getEngine()->isOperationModeStandalone() &&
            ($this->translations_list->getSize() === 0 || $force)
        ) {
            $this->translations_list = CNabuCatalogTaxonomyLanguage::getTranslationsForTranslatedObject($this);
        }
        
        return $this->translations_list;
    }

    /**
     * Creates a new translation instance. I the translation already exists then replaces ancient translation with this
     * new.
     * @param int|string|CNabuDataObject $nb_language A valid Id or object containing a nb_language_id field to
     * identify the language of new translation.
     * @return CNabuCatalogTaxonomyLanguage Returns the created instance to store translation or null if not valid
     * language was provided.
     */
    public function newTranslation($nb_language)
    {
        $nb_language_id = nb_getMixedValue($nb_language, NABU_LANG_FIELD_ID);
        if (is_numeric($nb_language_id) || nb_isValidGUID($nb_language_id)) {
            $nb_translation = $this->isBuiltIn()
                            ? new CNabuBuiltInCatalogTaxonomyLanguage()
                            : new CNabuCatalogTaxonomyLanguage()
            ;
            $nb_translation->transferValue($this, 'nb_catalog_taxonomy_id');
            $nb_translation->transferValue($nb_language, NABU_LANG_FIELD_ID);
            $this->setTranslation($nb_translation);
        } else {
            $nb_translation = null;
        }
        
        return $nb_translation;
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
     * Get Catalog Taxonomy Id attribute value
     * @return int Returns the Catalog Taxonomy Id value
     */
    public function getId() : int
    {
        return $this->getValue('nb_catalog_taxonomy_id');
    }

    /**
     * Sets the Catalog Taxonomy Id attribute value.
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
        $this->setValue('nb_catalog_taxonomy_id', $id);
        
        return $this;
    }

    /**
     * Get Catalog Id attribute value
     * @return null|int Returns the Catalog Id value
     */
    public function getCatalogId()
    {
        return $this->getValue('nb_catalog_id');
    }

    /**
     * Sets the Catalog Id attribute value.
     * @param null|int $nb_catalog_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCatalogId(int $nb_catalog_id = null) : CNabuDataObject
    {
        $this->setValue('nb_catalog_id', $nb_catalog_id);
        
        return $this;
    }

    /**
     * Get Catalog Taxonomy Key attribute value
     * @return null|string Returns the Catalog Taxonomy Key value
     */
    public function getKey()
    {
        return $this->getValue('nb_catalog_taxonomy_key');
    }

    /**
     * Sets the Catalog Taxonomy Key attribute value.
     * @param null|string $key New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setKey(string $key = null) : CNabuDataObject
    {
        $this->setValue('nb_catalog_taxonomy_key', $key);
        
        return $this;
    }

    /**
     * Get Catalog Taxonomy Level attribute value
     * @return int Returns the Catalog Taxonomy Level value
     */
    public function getLevel() : int
    {
        return $this->getValue('nb_catalog_taxonomy_level');
    }

    /**
     * Sets the Catalog Taxonomy Level attribute value.
     * @param int $level New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setLevel(int $level = 1) : CNabuDataObject
    {
        if ($level === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$level")
            );
        }
        $this->setValue('nb_catalog_taxonomy_level', $level);
        
        return $this;
    }

    /**
     * Get Catalog Taxonomy Scope attribute value
     * @return string Returns the Catalog Taxonomy Scope value
     */
    public function getScope() : string
    {
        return $this->getValue('nb_catalog_taxonomy_scope');
    }

    /**
     * Sets the Catalog Taxonomy Scope attribute value.
     * @param string $scope New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setScope(string $scope = "O") : CNabuDataObject
    {
        if ($scope === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$scope")
            );
        }
        $this->setValue('nb_catalog_taxonomy_scope', $scope);
        
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
        
        $trdata = $this->appendTranslatedTreeData($trdata, $nb_language, $dataonly);
        
        return $trdata;
    }
}
