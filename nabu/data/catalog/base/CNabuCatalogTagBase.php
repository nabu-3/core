<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2018/02/15 12:54:04 UTC
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
use \nabu\core\interfaces\INabuHashed;
use \nabu\core\traits\TNabuHashed;
use \nabu\data\catalog\builtin\CNabuBuiltInCatalogTagLanguage;
use \nabu\data\catalog\CNabuCatalog;
use \nabu\data\catalog\CNabuCatalogTag;
use \nabu\data\catalog\CNabuCatalogTagLanguage;
use \nabu\data\catalog\CNabuCatalogTagLanguageList;
use \nabu\data\catalog\CNabuCatalogTagList;
use \nabu\data\catalog\traits\TNabuCatalogChild;
use \nabu\data\CNabuDataObject;
use \nabu\data\lang\interfaces\INabuTranslated;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslated;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Catalog Tag stored in the storage named nb_catalog_tag.
 * @version 3.0.12 Surface
 * @package \nabu\data\catalog\base
 */
abstract class CNabuCatalogTagBase extends CNabuDBInternalObject implements INabuTranslated, INabuHashed
{
    use TNabuCatalogChild;
    use TNabuHashed;
    use TNabuTranslated;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_catalog_tag An instance of CNabuCatalogTagBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_catalog_tag_id, or a valid ID.
     */
    public function __construct($nb_catalog_tag = false)
    {
        if ($nb_catalog_tag) {
            $this->transferMixedValue($nb_catalog_tag, 'nb_catalog_tag_id');
        }
        
        parent::__construct();
        $this->__translatedConstruct();
        $this->translations_list = new CNabuCatalogTagLanguageList();
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
     * Overrides delete method to delete translations when delete the this instance.
     * @return bool Returns true if the entity and their translations are deleted.
     */
    public function delete() : bool
    {
        $this->deleteTranslations(true);
        
        return parent::delete();
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
        return 'nb_catalog_tag';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_catalog_tag_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_catalog_tag '
                   . "where nb_catalog_tag_id=%nb_catalog_tag_id\$d "
              )
            : null;
    }

    /**
     * Find an instance identified by nb_catalog_tag_hash field.
     * @param string $hash Hash to search
     * @return CNabuDataObject Returns a valid instance if exists or null if not.
     */
    public static function findByHash(string $hash)
    {
        return CNabuCatalogTag::buildObjectFromSQL(
                'select * '
                . 'from nb_catalog_tag '
               . "where nb_catalog_tag_hash='%hash\$s'",
                array(
                    'hash' => $hash
                )
        );
    }

    /**
     * Find an instance identified by nb_catalog_tag_key field.
     * @param mixed $nb_catalog Catalog that owns Catalog Tag
     * @param string $key Key to search
     * @return CNabuCatalogTag Returns a valid instance if exists or null if not.
     */
    public static function findByKey($nb_catalog, $key)
    {
        $nb_catalog_id = nb_getMixedValue($nb_catalog, 'nb_catalog_id');
        if (is_numeric($nb_catalog_id)) {
            $retval = CNabuCatalogTag::buildObjectFromSQL(
                    'select * '
                    . 'from nb_catalog_tag '
                   . 'where nb_catalog_id=%catalog_id$d '
                     . "and nb_catalog_tag_key='%key\$s'",
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
     * Get all items in the storage as an associative array where the field 'nb_catalog_tag_id' is the index, and each
     * value is an instance of class CNabuCatalogTagBase.
     * @param CNabuCatalog $nb_catalog The CNabuCatalog instance of the Catalog that owns the Catalog Tag List.
     * @return mixed Returns and array with all items.
     */
    public static function getAllCatalogTags(CNabuCatalog $nb_catalog)
    {
        $nb_catalog_id = nb_getMixedValue($nb_catalog, 'nb_catalog_id');
        if (is_numeric($nb_catalog_id)) {
            $retval = forward_static_call(
            array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_catalog_tag_id',
                'select * '
                . 'from nb_catalog_tag '
               . 'where nb_catalog_id=%catalog_id$d',
                array(
                    'catalog_id' => $nb_catalog_id
                ),
                $nb_catalog
            );
        } else {
            $retval = new CNabuCatalogTagList();
        }
        
        return $retval;
    }

    /**
     * Gets a filtered list of Catalog Tag instances represented as an array. Params allows the capability of select a
     * subset of fields, order by concrete fields, or truncate the list by a number of rows starting in an offset.
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
    public static function getFilteredCatalogTagList($nb_catalog, $q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $nb_catalog_id = nb_getMixedValue($nbu_customer, NABU_CATALOG_FIELD_ID);
        if (is_numeric($nb_catalog_id)) {
            $fields_part = nb_prefixFieldList(CNabuCatalogTagBase::getStorageName(), $fields, false, true, '`');
            $order_part = nb_prefixFieldList(CNabuCatalogTagBase::getStorageName(), $fields, false, false, '`');
        
            if ($num_items !== 0) {
                $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
            } else {
                $limit_part = false;
            }
        
            $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
                "select " . ($fields_part ? $fields_part . ' ' : '* ')
                . 'from nb_catalog_tag '
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
                $translation instanceof CNabuCatalogTagLanguage &&
                $translation->matchValue($this, 'nb_catalog_tag_id')
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
            $this->languages_list = CNabuCatalogTagLanguage::getLanguagesForTranslatedObject($this);
        }
        
        return $this->languages_list;
    }

    /**
     * Gets available translation instances.
     * @param bool $force If true force to reload translations list from storage.
     * @return null|array Return an array of \nabu\data\catalog\CNabuCatalogTagLanguage instances if they have
     * translations or null if not.
     */
    public function getTranslations($force = false)
    {
        if (!CNabuEngine::getEngine()->isOperationModeStandalone() &&
            ($this->translations_list->getSize() === 0 || $force)
        ) {
            $this->translations_list = CNabuCatalogTagLanguage::getTranslationsForTranslatedObject($this);
        }
        
        return $this->translations_list;
    }

    /**
     * Creates a new translation instance. I the translation already exists then replaces ancient translation with this
     * new.
     * @param int|string|CNabuDataObject $nb_language A valid Id or object containing a nb_language_id field to
     * identify the language of new translation.
     * @return CNabuCatalogTagLanguage Returns the created instance to store translation or null if not valid language
     * was provided.
     */
    public function newTranslation($nb_language)
    {
        $nb_language_id = nb_getMixedValue($nb_language, NABU_LANG_FIELD_ID);
        if (is_numeric($nb_language_id) || nb_isValidGUID($nb_language_id)) {
            $nb_translation = $this->isBuiltIn()
                            ? new CNabuBuiltInCatalogTagLanguage()
                            : new CNabuCatalogTagLanguage()
            ;
            $nb_translation->transferValue($this, 'nb_catalog_tag_id');
            $nb_translation->transferValue($nb_language, NABU_LANG_FIELD_ID);
            $this->setTranslation($nb_translation);
        } else {
            $nb_translation = null;
        }
        
        return $nb_translation;
    }

    /**
     * Get Catalog Tag Id attribute value
     * @return int Returns the Catalog Tag Id value
     */
    public function getId() : int
    {
        return $this->getValue('nb_catalog_tag_id');
    }

    /**
     * Sets the Catalog Tag Id attribute value.
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
        $this->setValue('nb_catalog_tag_id', $id);
        
        return $this;
    }

    /**
     * Get Catalog Tag Hash attribute value
     * @return null|string Returns the Catalog Tag Hash value
     */
    public function getHash()
    {
        return $this->getValue('nb_catalog_tag_hash');
    }

    /**
     * Sets the Catalog Tag Hash attribute value.
     * @param string|null $hash New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setHash(string $hash = null) : CNabuDataObject
    {
        $this->setValue('nb_catalog_tag_hash', $hash);
        
        return $this;
    }

    /**
     * Get Catalog Id attribute value
     * @return int Returns the Catalog Id value
     */
    public function getCatalogId() : int
    {
        return $this->getValue('nb_catalog_id');
    }

    /**
     * Sets the Catalog Id attribute value.
     * @param int $nb_catalog_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCatalogId(int $nb_catalog_id) : CNabuDataObject
    {
        if ($nb_catalog_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_catalog_id")
            );
        }
        $this->setValue('nb_catalog_id', $nb_catalog_id);
        
        return $this;
    }

    /**
     * Get Catalog Tag Key attribute value
     * @return null|string Returns the Catalog Tag Key value
     */
    public function getKey()
    {
        return $this->getValue('nb_catalog_tag_key');
    }

    /**
     * Sets the Catalog Tag Key attribute value.
     * @param string|null $key New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setKey(string $key = null) : CNabuDataObject
    {
        $this->setValue('nb_catalog_tag_key', $key);
        
        return $this;
    }

    /**
     * Get Catalog Tag CSS Class attribute value
     * @return null|string Returns the Catalog Tag CSS Class value
     */
    public function getCSSClass()
    {
        return $this->getValue('nb_catalog_tag_css_class');
    }

    /**
     * Sets the Catalog Tag CSS Class attribute value.
     * @param string|null $css_class New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCSSClass(string $css_class = null) : CNabuDataObject
    {
        $this->setValue('nb_catalog_tag_css_class', $css_class);
        
        return $this;
    }

    /**
     * Get Catalog Tag Attributes attribute value
     * @return null|array Returns the Catalog Tag Attributes value
     */
    public function getAttributes()
    {
        return $this->getValueJSONDecoded('nb_catalog_tag_attributes');
    }

    /**
     * Sets the Catalog Tag Attributes attribute value.
     * @param string|array|null $attributes New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setAttributes($attributes = null) : CNabuDataObject
    {
        $this->setValueJSONEncoded('nb_catalog_tag_attributes', $attributes);
        
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
