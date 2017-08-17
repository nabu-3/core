<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/08/17 12:16:51 UTC
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

namespace nabu\data\project\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\core\interfaces\INabuHashed;
use \nabu\core\traits\TNabuHashed;
use \nabu\data\CNabuDataObject;
use \nabu\data\lang\interfaces\INabuTranslated;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslated;
use \nabu\data\project\builtin\CNabuBuiltInProjectVersionLanguage;
use \nabu\data\project\CNabuProject;
use \nabu\data\project\CNabuProjectVersion;
use \nabu\data\project\CNabuProjectVersionLanguage;
use \nabu\data\project\CNabuProjectVersionLanguageList;
use \nabu\data\project\CNabuProjectVersionList;
use \nabu\db\CNabuDBInternalObject;
use nabu\data\project\traits\TNabuProjectChild;

/**
 * Class to manage the entity Project Version stored in the storage named nb_project_version.
 * @version 3.0.12 Surface
 * @package \nabu\data\project\base
 */
abstract class CNabuProjectVersionBase extends CNabuDBInternalObject implements INabuTranslated, INabuHashed
{
    use TNabuHashed;
    use TNabuProjectChild;
    use TNabuTranslated;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_project_version An instance of CNabuProjectVersionBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_project_version_id, or a valid ID.
     */
    public function __construct($nb_project_version = false)
    {
        if ($nb_project_version) {
            $this->transferMixedValue($nb_project_version, 'nb_project_version_id');
        }
        
        parent::__construct();
        $this->__translatedConstruct();
        $this->translations_list = new CNabuProjectVersionLanguageList();
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
        return 'nb_project_version';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_project_version_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_project_version '
                   . "where nb_project_version_id=%nb_project_version_id\$d "
              )
            : null;
    }

    /**
     * Find an instance identified by nb_project_version_hash field.
     * @param string $hash Hash to search
     * @return CNabuDataObject Returns a valid instance if exists or null if not.
     */
    public static function findByHash(string $hash)
    {
        return CNabuProjectVersion::buildObjectFromSQL(
                'select * '
                . 'from nb_project_version '
               . "where nb_project_version_hash='%hash\$s'",
                array(
                    'hash' => $hash
                )
        );
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_project_version_id' is the index, and
     * each value is an instance of class CNabuProjectVersionBase.
     * @param CNabuProject $nb_project The CNabuProject instance that owns the Project Version List
     * @return mixed Returns and array with all items.
     */
    public static function getAllProjectVersions(CNabuProject $nb_project)
    {
        $nb_project_id = nb_getMixedValue($nb_project, 'nb_project_id');
        if (is_numeric($nb_project_id)) {
            $retval = forward_static_call(
            array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_project_version_id',
                'select * '
                . 'from nb_project_version '
               . 'where nb_project_id=%project_id$d',
                array(
                    'project_id' => $nb_project_id
                ),
                $nb_project
            );
        } else {
            $retval = new CNabuProjectVersionList();
        }
        
        return $retval;
    }

    /**
     * Gets a filtered list of Project Version instances represented as an array. Params allows the capability of
     * select a subset of fields, order by concrete fields, or truncate the list by a number of rows starting in an
     * offset.
     * @throws \nabu\core\exceptions\ENabuCoreException Raises an exception if $fields or $order have invalid values.
     * @param mixed $nb_project Project instance, object containing a Project Id field or an Id.
     * @param string $q Query string to filter results using a context index.
     * @param string|array $fields List of fields to put in the results.
     * @param string|array $order List of fields to order the results. Each field can be suffixed with "ASC" or "DESC"
     * to determine the short order
     * @param int $offset Offset of first row in the results having the first row at offset 0.
     * @param int $num_items Number of continue rows to get as maximum in the results.
     * @return array Returns an array with all rows found using the criteria.
     */
    public static function getFilteredProjectVersionList($nb_project = null, $q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $nb_project_id = nb_getMixedValue($nb_customer, NABU_PROJECT_FIELD_ID);
        if (is_numeric($nb_project_id)) {
            $fields_part = nb_prefixFieldList(CNabuProjectVersionBase::getStorageName(), $fields, false, true, '`');
            $order_part = nb_prefixFieldList(CNabuProjectVersionBase::getStorageName(), $fields, false, false, '`');
        
            if ($num_items !== 0) {
                $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
            } else {
                $limit_part = false;
            }
        
            $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
                "select " . ($fields_part ? $fields_part . ' ' : '* ')
                . 'from nb_project_version '
               . 'where ' . NABU_PROJECT_FIELD_ID . '=%project_id$d '
                . ($order_part ? "order by $order_part " : '')
                . ($limit_part ? "limit $limit_part" : ''),
                array(
                    'project_id' => $nb_project_id
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
                $translation instanceof CNabuProjectVersionLanguage &&
                $translation->matchValue($this, 'nb_project_version_id')
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
            $this->languages_list = CNabuProjectVersionLanguage::getLanguagesForTranslatedObject($this);
        }
        
        return $this->languages_list;
    }

    /**
     * Gets available translation instances.
     * @param bool $force If true force to reload translations list from storage.
     * @return null|array Return an array of \nabu\data\project\CNabuProjectVersionLanguage instances if they have
     * translations or null if not.
     */
    public function getTranslations($force = false)
    {
        if (!CNabuEngine::getEngine()->isOperationModeStandalone() &&
            ($this->translations_list->getSize() === 0 || $force)
        ) {
            $this->translations_list = CNabuProjectVersionLanguage::getTranslationsForTranslatedObject($this);
        }
        
        return $this->translations_list;
    }

    /**
     * Creates a new translation instance. I the translation already exists then replaces ancient translation with this
     * new.
     * @param int|string|CNabuDataObject $nb_language A valid Id or object containing a nb_language_id field to
     * identify the language of new translation.
     * @return CNabuProjectVersionLanguage Returns the created instance to store translation or null if not valid
     * language was provided.
     */
    public function newTranslation($nb_language)
    {
        $nb_language_id = nb_getMixedValue($nb_language, NABU_LANG_FIELD_ID);
        if (is_numeric($nb_language_id) || nb_isValidGUID($nb_language_id)) {
            $nb_translation = $this->isBuiltIn()
                            ? new CNabuBuiltInProjectVersionLanguage()
                            : new CNabuProjectVersionLanguage()
            ;
            $nb_translation->transferValue($this, 'nb_project_version_id');
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
     * Get Project Version Id attribute value
     * @return int Returns the Project Version Id value
     */
    public function getId() : int
    {
        return $this->getValue('nb_project_version_id');
    }

    /**
     * Sets the Project Version Id attribute value.
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
        $this->setValue('nb_project_version_id', $id);
        
        return $this;
    }

    /**
     * Get Project Id attribute value
     * @return int Returns the Project Id value
     */
    public function getProjectId() : int
    {
        return $this->getValue('nb_project_id');
    }

    /**
     * Sets the Project Id attribute value.
     * @param int $nb_project_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setProjectId(int $nb_project_id) : CNabuDataObject
    {
        if ($nb_project_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_project_id")
            );
        }
        $this->setValue('nb_project_id', $nb_project_id);
        
        return $this;
    }

    /**
     * Get Project Version Hash attribute value
     * @return null|string Returns the Project Version Hash value
     */
    public function getHash()
    {
        return $this->getValue('nb_project_version_hash');
    }

    /**
     * Sets the Project Version Hash attribute value.
     * @param null|string $hash New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setHash(string $hash = null) : CNabuDataObject
    {
        $this->setValue('nb_project_version_hash', $hash);
        
        return $this;
    }

    /**
     * Get Project Version Code attribute value
     * @return null|string Returns the Project Version Code value
     */
    public function getCode()
    {
        return $this->getValue('nb_project_version_code');
    }

    /**
     * Sets the Project Version Code attribute value.
     * @param null|string $code New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCode(string $code = null) : CNabuDataObject
    {
        $this->setValue('nb_project_version_code', $code);
        
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
