<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2019/04/08 06:59:32 UTC
 * ===========================================================================
 * Copyright 2009-2011 Rafael Gutierrez Martinez
 * Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 * Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 * Copyright 2017-2019 nabu-3 Group
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

namespace nabu\data\icontact\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\core\interfaces\INabuHashed;
use \nabu\core\traits\TNabuHashed;
use \nabu\data\CNabuDataObject;
use \nabu\data\icontact\builtin\CNabuBuiltInIContactProspectStatusTypeLanguage;
use \nabu\data\icontact\CNabuIContactProspectStatusType;
use \nabu\data\icontact\CNabuIContactProspectStatusTypeLanguage;
use \nabu\data\icontact\CNabuIContactProspectStatusTypeLanguageList;
use \nabu\data\lang\interfaces\INabuTranslated;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslated;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity iContact Prospect Status Type stored in the storage named
 * nb_icontact_prospect_status_type.
 * @version 3.0.12 Surface
 * @package \nabu\data\icontact\base
 */
abstract class CNabuIContactProspectStatusTypeBase extends CNabuDBInternalObject implements INabuTranslated, INabuHashed
{
    use TNabuHashed;
    use TNabuTranslated;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_icontact_prospect_status_type An instance of CNabuIContactProspectStatusTypeBase or another
     * object descending from \nabu\data\CNabuDataObject which contains a field named
     * nb_icontact_prospect_status_type_id, or a valid ID.
     */
    public function __construct($nb_icontact_prospect_status_type = false)
    {
        if ($nb_icontact_prospect_status_type) {
            $this->transferMixedValue($nb_icontact_prospect_status_type, 'nb_icontact_prospect_status_type_id');
        }
        
        parent::__construct();
        $this->__translatedConstruct();
        $this->translations_list = new CNabuIContactProspectStatusTypeLanguageList();
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
        return 'nb_icontact_prospect_status_type';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_icontact_prospect_status_type_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_icontact_prospect_status_type '
                   . "where nb_icontact_prospect_status_type_id=%nb_icontact_prospect_status_type_id\$d "
              )
            : null;
    }

    /**
     * Find an instance identified by nb_icontact_prospect_status_type_hash field.
     * @param string $hash Hash to search
     * @return CNabuDataObject Returns a valid instance if exists or null if not.
     */
    public static function findByHash(string $hash)
    {
        return CNabuIContactProspectStatusType::buildObjectFromSQL(
                'select * '
                . 'from nb_icontact_prospect_status_type '
               . "where nb_icontact_prospect_status_type_hash='%hash\$s'",
                array(
                    'hash' => $hash
                )
        );
    }

    /**
     * Find an instance identified by nb_icontact_prospect_status_type_key field.
     * @param string $key Key to search
     * @return CNabuIContactProspectStatusType Returns a valid instance if exists or null if not.
     */
    public static function findByKey($key)
    {
        return CNabuIContactProspectStatusType::buildObjectFromSQL(
                'select * '
                . 'from nb_icontact_prospect_status_type '
               . "where nb_icontact_prospect_status_type_key='%key\$s'",
                array(
                    'key' => $key
                )
        );
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_icontact_prospect_status_type_id' is
     * the index, and each value is an instance of class CNabuIContactProspectStatusTypeBase.
     * @return mixed Returns and array with all items.
     */
    public static function getAlliContactProspectStatusTypes()
    {
        return forward_static_call(
                array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_icontact_prospect_status_type_id',
                'select * from nb_icontact_prospect_status_type'
        );
    }

    /**
     * Gets a filtered list of iContact Prospect Status Type instances represented as an array. Params allows the
     * capability of select a subset of fields, order by concrete fields, or truncate the list by a number of rows
     * starting in an offset.
     * @throws \nabu\core\exceptions\ENabuCoreException Raises an exception if $fields or $order have invalid values.
     * @param string $q Query string to filter results using a context index.
     * @param string|array $fields List of fields to put in the results.
     * @param string|array $order List of fields to order the results. Each field can be suffixed with "ASC" or "DESC"
     * to determine the short order
     * @param int $offset Offset of first row in the results having the first row at offset 0.
     * @param int $num_items Number of continue rows to get as maximum in the results.
     * @return array Returns an array with all rows found using the criteria.
     */
    public static function getFilterediContactProspectStatusTypeList($q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $fields_part = nb_prefixFieldList(CNabuIContactProspectStatusTypeBase::getStorageName(), $fields, false, true, '`');
        $order_part = nb_prefixFieldList(CNabuIContactProspectStatusTypeBase::getStorageName(), $fields, false, false, '`');
        
        if ($num_items !== 0) {
            $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
        } else {
            $limit_part = false;
        }
        
        $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
            "select " . ($fields_part ? $fields_part . ' ' : '* ')
            . 'from nb_icontact_prospect_status_type '
            . ($order_part ? "order by $order_part " : '')
            . ($limit_part ? "limit $limit_part" : ''),
            array(
            )
        );
        
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
                $translation instanceof CNabuIContactProspectStatusTypeLanguage &&
                $translation->matchValue($this, 'nb_icontact_prospect_status_type_id')
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
            $this->languages_list = CNabuIContactProspectStatusTypeLanguage::getLanguagesForTranslatedObject($this);
        }
        
        return $this->languages_list;
    }

    /**
     * Gets available translation instances.
     * @param bool $force If true force to reload translations list from storage.
     * @return null|array Return an array of \nabu\data\icontact\CNabuIContactProspectStatusTypeLanguage instances if
     * they have translations or null if not.
     */
    public function getTranslations($force = false)
    {
        if (!CNabuEngine::getEngine()->isOperationModeStandalone() &&
            ($this->translations_list->getSize() === 0 || $force)
        ) {
            $this->translations_list = CNabuIContactProspectStatusTypeLanguage::getTranslationsForTranslatedObject($this);
        }
        
        return $this->translations_list;
    }

    /**
     * Creates a new translation instance. I the translation already exists then replaces ancient translation with this
     * new.
     * @param int|string|CNabuDataObject $nb_language A valid Id or object containing a nb_language_id field to
     * identify the language of new translation.
     * @return CNabuIContactProspectStatusTypeLanguage Returns the created instance to store translation or null if not
     * valid language was provided.
     */
    public function newTranslation($nb_language)
    {
        $nb_language_id = nb_getMixedValue($nb_language, NABU_LANG_FIELD_ID);
        if (is_numeric($nb_language_id) || nb_isValidGUID($nb_language_id)) {
            $nb_translation = $this->isBuiltIn()
                            ? new CNabuBuiltInIContactProspectStatusTypeLanguage()
                            : new CNabuIContactProspectStatusTypeLanguage()
            ;
            $nb_translation->transferValue($this, 'nb_icontact_prospect_status_type_id');
            $nb_translation->transferValue($nb_language, NABU_LANG_FIELD_ID);
            $this->setTranslation($nb_translation);
        } else {
            $nb_translation = null;
        }
        
        return $nb_translation;
    }

    /**
     * Get Icontact Prospect Status Type Id attribute value
     * @return int Returns the Icontact Prospect Status Type Id value
     */
    public function getId() : int
    {
        return $this->getValue('nb_icontact_prospect_status_type_id');
    }

    /**
     * Sets the Icontact Prospect Status Type Id attribute value.
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
        $this->setValue('nb_icontact_prospect_status_type_id', $id);
        
        return $this;
    }

    /**
     * Get Icontact Id attribute value
     * @return null|int Returns the Icontact Id value
     */
    public function getIcontactId()
    {
        return $this->getValue('nb_icontact_id');
    }

    /**
     * Sets the Icontact Id attribute value.
     * @param int|null $nb_icontact_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setIcontactId(int $nb_icontact_id = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_id', $nb_icontact_id);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Status Type Key attribute value
     * @return null|string Returns the Icontact Prospect Status Type Key value
     */
    public function getKey()
    {
        return $this->getValue('nb_icontact_prospect_status_type_key');
    }

    /**
     * Sets the Icontact Prospect Status Type Key attribute value.
     * @param string|null $key New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setKey(string $key = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_status_type_key', $key);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Status Type Hash attribute value
     * @return null|string Returns the Icontact Prospect Status Type Hash value
     */
    public function getHash()
    {
        return $this->getValue('nb_icontact_prospect_status_type_hash');
    }

    /**
     * Sets the Icontact Prospect Status Type Hash attribute value.
     * @param string|null $hash New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setHash(string $hash = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_status_type_hash', $hash);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Status Type Template Acknowledge attribute value
     * @return null|int Returns the Icontact Prospect Status Type Template Acknowledge value
     */
    public function getTemplateAcknowledge()
    {
        return $this->getValue('nb_icontact_prospect_status_type_template_acknowledge');
    }

    /**
     * Sets the Icontact Prospect Status Type Template Acknowledge attribute value.
     * @param int|null $template_acknowledge New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setTemplateAcknowledge(int $template_acknowledge = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_status_type_template_acknowledge', $template_acknowledge);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Status Type Template Arrival attribute value
     * @return null|int Returns the Icontact Prospect Status Type Template Arrival value
     */
    public function getTemplateArrival()
    {
        return $this->getValue('nb_icontact_prospect_status_type_template_arrival');
    }

    /**
     * Sets the Icontact Prospect Status Type Template Arrival attribute value.
     * @param int|null $template_arrival New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setTemplateArrival(int $template_arrival = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_status_type_template_arrival', $template_arrival);
        
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
