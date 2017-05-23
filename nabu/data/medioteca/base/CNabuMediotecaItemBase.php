<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/05/23 16:33:48 UTC
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

namespace nabu\data\medioteca\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\core\interfaces\INabuHashed;
use \nabu\core\traits\TNabuHashed;
use \nabu\data\CNabuDataObject;
use \nabu\data\lang\interfaces\INabuTranslated;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslated;
use \nabu\data\medioteca\builtin\CNabuBuiltInMediotecaItemLanguage;
use \nabu\data\medioteca\CNabuMedioteca;
use \nabu\data\medioteca\CNabuMediotecaItem;
use \nabu\data\medioteca\CNabuMediotecaItemLanguage;
use \nabu\data\medioteca\CNabuMediotecaItemLanguageList;
use \nabu\data\medioteca\CNabuMediotecaItemList;
use \nabu\data\medioteca\traits\TNabuMediotecaChild;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Medioteca Item stored in the storage named nb_medioteca_item.
 * @version 3.0.12 Surface
 * @package \nabu\data\medioteca\base
 */
abstract class CNabuMediotecaItemBase extends CNabuDBInternalObject implements INabuTranslated, INabuHashed
{
    use TNabuHashed;
    use TNabuMediotecaChild;
    use TNabuTranslated;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_medioteca_item An instance of CNabuMediotecaItemBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_medioteca_item_id, or a valid ID.
     */
    public function __construct($nb_medioteca_item = false)
    {
        if ($nb_medioteca_item) {
            $this->transferMixedValue($nb_medioteca_item, 'nb_medioteca_item_id');
        }
        
        parent::__construct();
        $this->__translatedConstruct();
        $this->translations_list = new CNabuMediotecaItemLanguageList();
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
        return 'nb_medioteca_item';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_medioteca_item_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_medioteca_item '
                   . "where nb_medioteca_item_id=%nb_medioteca_item_id\$d "
              )
            : null;
    }

    /**
     * Find an instance identified by nb_medioteca_item_hash field.
     * @param string $hash Hash to search
     * @return CNabuDataObject Returns a valid instance if exists or null if not.
     */
    public static function findByHash(string $hash)
    {
        return CNabuMediotecaItem::buildObjectFromSQL(
                'select * '
                . 'from nb_medioteca_item '
               . "where nb_medioteca_item_hash='%hash\$s'",
                array(
                    'hash' => $hash
                )
        );
    }

    /**
     * Find an instance identified by nb_medioteca_item_key field.
     * @param mixed $nb_medioteca Medioteca that owns Medioteca Item
     * @param string $key Key to search
     * @return CNabuMediotecaItem Returns a valid instance if exists or null if not.
     */
    public static function findByKey($nb_medioteca, $key)
    {
        $nb_medioteca_id = nb_getMixedValue($nb_medioteca, 'nb_medioteca_id');
        if (is_numeric($nb_medioteca_id)) {
            $retval = CNabuMediotecaItem::buildObjectFromSQL(
                    'select * '
                    . 'from nb_medioteca_item '
                   . 'where nb_medioteca_id=%medioteca_id$d '
                     . "and nb_medioteca_item_key='%key\$s'",
                    array(
                        'medioteca_id' => $nb_medioteca_id,
                        'key' => $key
                    )
            );
        } else {
            $retval = null;
        }
        
        return $retval;
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_medioteca_item_id' is the index, and
     * each value is an instance of class CNabuMediotecaItemBase.
     * @param CNabuMedioteca $nb_medioteca The CNabuMedioteca instance of the Medioteca that owns the Medioteca Item
     * List.
     * @return mixed Returns and array with all items.
     */
    public static function getAllMediotecaItems(CNabuMedioteca $nb_medioteca)
    {
        $nb_medioteca_id = nb_getMixedValue($nb_medioteca, 'nb_medioteca_id');
        if (is_numeric($nb_medioteca_id)) {
            $retval = forward_static_call(
            array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_medioteca_item_id',
                'select * '
                . 'from nb_medioteca_item '
               . 'where nb_medioteca_id=%medioteca_id$d',
                array(
                    'medioteca_id' => $nb_medioteca_id
                ),
                $nb_medioteca
            );
        } else {
            $retval = new CNabuMediotecaItemList();
        }
        
        return $retval;
    }

    /**
     * Gets a filtered list of Medioteca Item instances represented as an array. Params allows the capability of select
     * a subset of fields, order by concrete fields, or truncate the list by a number of rows starting in an offset.
     * @throws \nabu\core\exceptions\ENabuCoreException Raises an exception if $fields or $order have invalid values.
     * @param mixed $nb_medioteca Medioteca instance, object containing a Medioteca Id field or an Id.
     * @param string $q Query string to filter results using a context index.
     * @param string|array $fields List of fields to put in the results.
     * @param string|array $order List of fields to order the results. Each field can be suffixed with "ASC" or "DESC"
     * to determine the short order
     * @param int $offset Offset of first row in the results having the first row at offset 0.
     * @param int $num_items Number of continue rows to get as maximum in the results.
     * @return array Returns an array with all rows found using the criteria.
     */
    public static function getFilteredMediotecaItemList($nb_medioteca = null, $q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $nb_medioteca_id = nb_getMixedValue($nb_customer, NABU_MEDIOTECA_FIELD_ID);
        if (is_numeric($nb_medioteca_id)) {
            $fields_part = nb_prefixFieldList(CNabuMediotecaItemBase::getStorageName(), $fields, false, true, '`');
            $order_part = nb_prefixFieldList(CNabuMediotecaItemBase::getStorageName(), $fields, false, false, '`');
        
            if ($num_items !== 0) {
                $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
            } else {
                $limit_part = false;
            }
        
            $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
                "select " . ($fields_part ? $fields_part . ' ' : '* ')
                . 'from nb_medioteca_item '
               . 'where ' . NABU_MEDIOTECA_FIELD_ID . '=%medioteca_id$d '
                . ($order_part ? "order by $order_part " : '')
                . ($limit_part ? "limit $limit_part" : ''),
                array(
                    'medioteca_id' => $nb_medioteca_id
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
                $translation instanceof CNabuMediotecaItemLanguage &&
                $translation->matchValue($this, 'nb_medioteca_item_id')
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
            $this->languages_list = CNabuMediotecaItemLanguage::getLanguagesForTranslatedObject($this);
        }
        
        return $this->languages_list;
    }

    /**
     * Gets available translation instances.
     * @param bool $force If true force to reload translations list from storage.
     * @return null|array Return an array of \nabu\data\medioteca\CNabuMediotecaItemLanguage instances if they have
     * translations or null if not.
     */
    public function getTranslations($force = false)
    {
        if (!CNabuEngine::getEngine()->isOperationModeStandalone() &&
            ($this->translations_list->getSize() === 0 || $force)
        ) {
            $this->translations_list = CNabuMediotecaItemLanguage::getTranslationsForTranslatedObject($this);
        }
        
        return $this->translations_list;
    }

    /**
     * Creates a new translation instance. I the translation already exists then replaces ancient translation with this
     * new.
     * @param int|string|CNabuDataObject $nb_language A valid Id or object containing a nb_language_id field to
     * identify the language of new translation.
     * @return CNabuMediotecaItemLanguage Returns the created instance to store translation or null if not valid
     * language was provided.
     */
    public function newTranslation($nb_language)
    {
        $nb_language_id = nb_getMixedValue($nb_language, NABU_LANG_FIELD_ID);
        if (is_numeric($nb_language_id) || nb_isValidGUID($nb_language_id)) {
            $nb_translation = $this->isBuiltIn()
                            ? new CNabuBuiltInMediotecaItemLanguage()
                            : new CNabuMediotecaItemLanguage()
            ;
            $nb_translation->transferValue($this, 'nb_medioteca_item_id');
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
     * Get Medioteca Item Id attribute value
     * @return int Returns the Medioteca Item Id value
     */
    public function getId() : int
    {
        return $this->getValue('nb_medioteca_item_id');
    }

    /**
     * Sets the Medioteca Item Id attribute value.
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
        $this->setValue('nb_medioteca_item_id', $id);
        
        return $this;
    }

    /**
     * Get Medioteca Id attribute value
     * @return null|int Returns the Medioteca Id value
     */
    public function getMediotecaId()
    {
        return $this->getValue('nb_medioteca_id');
    }

    /**
     * Sets the Medioteca Id attribute value.
     * @param null|int $nb_medioteca_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setMediotecaId(int $nb_medioteca_id = null) : CNabuDataObject
    {
        $this->setValue('nb_medioteca_id', $nb_medioteca_id);
        
        return $this;
    }

    /**
     * Get Medioteca Item Hash attribute value
     * @return null|string Returns the Medioteca Item Hash value
     */
    public function getHash()
    {
        return $this->getValue('nb_medioteca_item_hash');
    }

    /**
     * Sets the Medioteca Item Hash attribute value.
     * @param null|string $hash New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setHash(string $hash = null) : CNabuDataObject
    {
        $this->setValue('nb_medioteca_item_hash', $hash);
        
        return $this;
    }

    /**
     * Get Medioteca Item Key attribute value
     * @return null|string Returns the Medioteca Item Key value
     */
    public function getKey()
    {
        return $this->getValue('nb_medioteca_item_key');
    }

    /**
     * Sets the Medioteca Item Key attribute value.
     * @param null|string $key New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setKey(string $key = null) : CNabuDataObject
    {
        $this->setValue('nb_medioteca_item_key', $key);
        
        return $this;
    }

    /**
     * Get Medioteca Item Order attribute value
     * @return int Returns the Medioteca Item Order value
     */
    public function getOrder() : int
    {
        return $this->getValue('nb_medioteca_item_order');
    }

    /**
     * Sets the Medioteca Item Order attribute value.
     * @param int $order New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setOrder(int $order = 1) : CNabuDataObject
    {
        if ($order === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$order")
            );
        }
        $this->setValue('nb_medioteca_item_order', $order);
        
        return $this;
    }

    /**
     * Get Medioteca Item Visible attribute value
     * @return null|string Returns the Medioteca Item Visible value
     */
    public function getVisible()
    {
        return $this->getValue('nb_medioteca_item_visible');
    }

    /**
     * Sets the Medioteca Item Visible attribute value.
     * @param null|string $visible New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setVisible(string $visible = "F") : CNabuDataObject
    {
        $this->setValue('nb_medioteca_item_visible', $visible);
        
        return $this;
    }

    /**
     * Get Medioteca Item Begin Datetime attribute value
     * @return mixed Returns the Medioteca Item Begin Datetime value
     */
    public function getBeginDatetime()
    {
        return $this->getValue('nb_medioteca_item_begin_datetime');
    }

    /**
     * Sets the Medioteca Item Begin Datetime attribute value.
     * @param mixed $begin_datetime New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setBeginDatetime($begin_datetime) : CNabuDataObject
    {
        $this->setValue('nb_medioteca_item_begin_datetime', $begin_datetime);
        
        return $this;
    }

    /**
     * Get Medioteca Item End Datetime attribute value
     * @return mixed Returns the Medioteca Item End Datetime value
     */
    public function getEndDatetime()
    {
        return $this->getValue('nb_medioteca_item_end_datetime');
    }

    /**
     * Sets the Medioteca Item End Datetime attribute value.
     * @param mixed $end_datetime New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setEndDatetime($end_datetime) : CNabuDataObject
    {
        $this->setValue('nb_medioteca_item_end_datetime', $end_datetime);
        
        return $this;
    }

    /**
     * Get Medioteca Item Have Original attribute value
     * @return null|string Returns the Medioteca Item Have Original value
     */
    public function getHaveOriginal()
    {
        return $this->getValue('nb_medioteca_item_have_original');
    }

    /**
     * Sets the Medioteca Item Have Original attribute value.
     * @param null|string $have_original New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setHaveOriginal(string $have_original = "F") : CNabuDataObject
    {
        $this->setValue('nb_medioteca_item_have_original', $have_original);
        
        return $this;
    }

    /**
     * Get Medioteca Item Original Path attribute value
     * @return null|string Returns the Medioteca Item Original Path value
     */
    public function getOriginalPath()
    {
        return $this->getValue('nb_medioteca_item_original_path');
    }

    /**
     * Sets the Medioteca Item Original Path attribute value.
     * @param null|string $original_path New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setOriginalPath(string $original_path = null) : CNabuDataObject
    {
        $this->setValue('nb_medioteca_item_original_path', $original_path);
        
        return $this;
    }

    /**
     * Get Medioteca Item CSS Class attribute value
     * @return null|string Returns the Medioteca Item CSS Class value
     */
    public function getCSSClass()
    {
        return $this->getValue('nb_medioteca_item_css_class');
    }

    /**
     * Sets the Medioteca Item CSS Class attribute value.
     * @param null|string $css_class New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCSSClass(string $css_class = null) : CNabuDataObject
    {
        $this->setValue('nb_medioteca_item_css_class', $css_class);
        
        return $this;
    }

    /**
     * Get Medioteca Item Icon attribute value
     * @return null|string Returns the Medioteca Item Icon value
     */
    public function getIcon()
    {
        return $this->getValue('nb_medioteca_item_icon');
    }

    /**
     * Sets the Medioteca Item Icon attribute value.
     * @param null|string $icon New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setIcon(string $icon = null) : CNabuDataObject
    {
        $this->setValue('nb_medioteca_item_icon', $icon);
        
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
