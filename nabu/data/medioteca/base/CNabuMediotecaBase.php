<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2019/10/10 11:56:19 UTC
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

namespace nabu\data\medioteca\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\core\interfaces\INabuHashed;
use \nabu\core\traits\TNabuHashed;
use \nabu\data\CNabuDataObject;
use \nabu\data\customer\CNabuCustomer;
use \nabu\data\customer\traits\TNabuCustomerChild;
use \nabu\data\lang\CNabuLanguage;
use \nabu\data\lang\CNabuLanguageList;
use \nabu\data\lang\interfaces\INabuTranslated;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslated;
use \nabu\data\medioteca\builtin\CNabuBuiltInMediotecaLanguage;
use \nabu\data\medioteca\CNabuMedioteca;
use \nabu\data\medioteca\CNabuMediotecaLanguage;
use \nabu\data\medioteca\CNabuMediotecaLanguageList;
use \nabu\data\medioteca\CNabuMediotecaList;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Medioteca stored in the storage named nb_medioteca.
 * @author Rafael Gutiérrez <rgutierrez@nabu-3.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\medioteca\base
 */
abstract class CNabuMediotecaBase extends CNabuDBInternalObject implements INabuTranslated, INabuHashed
{
    use TNabuCustomerChild;
    use TNabuHashed;
    use TNabuTranslated;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_medioteca An instance of CNabuMediotecaBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_medioteca_id, or a valid ID.
     */
    public function __construct($nb_medioteca = false)
    {
        if ($nb_medioteca) {
            $this->transferMixedValue($nb_medioteca, 'nb_medioteca_id');
        }
        
        parent::__construct();
        $this->__translatedConstruct();
        $this->translations_list = new CNabuMediotecaLanguageList();
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
        return 'nb_medioteca';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_medioteca_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_medioteca '
                   . "where nb_medioteca_id=%nb_medioteca_id\$d "
              )
            : null;
    }

    /**
     * Find an instance identified by nb_medioteca_hash field.
     * @param string $hash Hash to search
     * @return CNabuDataObject Returns a valid instance if exists or null if not.
     */
    public static function findByHash(string $hash)
    {
        return CNabuMedioteca::buildObjectFromSQL(
                'select * '
                . 'from nb_medioteca '
               . "where nb_medioteca_hash='%hash\$s'",
                array(
                    'hash' => $hash
                )
        );
    }

    /**
     * Find an instance identified by nb_medioteca_key field.
     * @param mixed $nb_customer Customer that owns Medioteca
     * @param string $key Key to search
     * @return CNabuMedioteca Returns a valid instance if exists or null if not.
     */
    public static function findByKey($nb_customer, $key)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, 'nb_customer_id');
        if (is_numeric($nb_customer_id)) {
            $retval = CNabuMedioteca::buildObjectFromSQL(
                    'select * '
                    . 'from nb_medioteca '
                   . 'where nb_customer_id=%cust_id$d '
                     . "and nb_medioteca_key='%key\$s'",
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
     * Get all items in the storage as an associative array where the field 'nb_medioteca_id' is the index, and each
     * value is an instance of class CNabuMediotecaBase.
     * @param CNabuCustomer $nb_customer The CNabuCustomer instance of the Customer that owns the Medioteca List.
     * @return mixed Returns and array with all items.
     */
    public static function getAllMediotecas(CNabuCustomer $nb_customer)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, 'nb_customer_id');
        if (is_numeric($nb_customer_id)) {
            $retval = forward_static_call(
            array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_medioteca_id',
                'select * '
                . 'from nb_medioteca '
               . 'where nb_customer_id=%cust_id$d',
                array(
                    'cust_id' => $nb_customer_id
                ),
                $nb_customer
            );
        } else {
            $retval = new CNabuMediotecaList();
        }
        
        return $retval;
    }

    /**
     * Gets a filtered list of Medioteca instances represented as an array. Params allows the capability of select a
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
    public static function getFilteredMediotecaList($nb_customer, $q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, NABU_CUSTOMER_FIELD_ID);
        if (is_numeric($nb_customer_id)) {
            $fields_part = nb_prefixFieldList(CNabuMediotecaBase::getStorageName(), $fields, false, true, '`');
            $order_part = nb_prefixFieldList(CNabuMediotecaBase::getStorageName(), $fields, false, false, '`');
        
            if ($num_items !== 0) {
                $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
            } else {
                $limit_part = false;
            }
        
            $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
                "select " . ($fields_part ? $fields_part . ' ' : '* ')
                . 'from nb_medioteca '
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
                $translation instanceof CNabuMediotecaLanguage &&
                $translation->matchValue($this, 'nb_medioteca_id')
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
            $this->languages_list = CNabuMediotecaLanguage::getLanguagesForTranslatedObject($this);
        }
        
        return $this->languages_list;
    }

    /**
     * Gets available translation instances.
     * @param bool $force If true force to reload translations list from storage.
     * @return null|array Return an array of \nabu\data\medioteca\CNabuMediotecaLanguage instances if they have
     * translations or null if not.
     */
    public function getTranslations($force = false)
    {
        if (!CNabuEngine::getEngine()->isOperationModeStandalone() &&
            ($this->translations_list->getSize() === 0 || $force)
        ) {
            $this->translations_list = CNabuMediotecaLanguage::getTranslationsForTranslatedObject($this);
        }
        
        return $this->translations_list;
    }

    /**
     * Creates a new translation instance. I the translation already exists then replaces ancient translation with this
     * new.
     * @param int|string|CNabuDataObject $nb_language A valid Id or object containing a nb_language_id field to
     * identify the language of new translation.
     * @return CNabuMediotecaLanguage Returns the created instance to store translation or null if not valid language
     * was provided.
     */
    public function newTranslation($nb_language)
    {
        $nb_language_id = nb_getMixedValue($nb_language, NABU_LANG_FIELD_ID);
        if (is_numeric($nb_language_id) || nb_isValidGUID($nb_language_id)) {
            $nb_translation = $this->isBuiltIn()
                            ? new CNabuBuiltInMediotecaLanguage()
                            : new CNabuMediotecaLanguage()
            ;
            $nb_translation->transferValue($this, 'nb_medioteca_id');
            $nb_translation->transferValue($nb_language, NABU_LANG_FIELD_ID);
            $this->setTranslation($nb_translation);
        } else {
            $nb_translation = null;
        }
        
        return $nb_translation;
    }

    /**
     * Get all language instances used along of all Medioteca set of a Customer
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
                        . 'from nb_medioteca ca, nb_medioteca_lang cal '
                       . 'where ca.nb_medioteca_id=cal.nb_medioteca_id '
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
     * Get Medioteca Id attribute value
     * @return int Returns the Medioteca Id value
     */
    public function getId() : int
    {
        return $this->getValue('nb_medioteca_id');
    }

    /**
     * Sets the Medioteca Id attribute value.
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
        $this->setValue('nb_medioteca_id', $id);
        
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
     * Get Medioteca Type Id attribute value
     * @return null|int Returns the Medioteca Type Id value
     */
    public function getTypeId()
    {
        return $this->getValue('nb_medioteca_type_id');
    }

    /**
     * Sets the Medioteca Type Id attribute value.
     * @param int|null $type_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setTypeId(int $type_id = null) : CNabuDataObject
    {
        $this->setValue('nb_medioteca_type_id', $type_id);
        
        return $this;
    }

    /**
     * Get Medioteca Hash attribute value
     * @return null|string Returns the Medioteca Hash value
     */
    public function getHash()
    {
        return $this->getValue('nb_medioteca_hash');
    }

    /**
     * Sets the Medioteca Hash attribute value.
     * @param string|null $hash New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setHash(string $hash = null) : CNabuDataObject
    {
        $this->setValue('nb_medioteca_hash', $hash);
        
        return $this;
    }

    /**
     * Get Medioteca Key attribute value
     * @return null|string Returns the Medioteca Key value
     */
    public function getKey()
    {
        return $this->getValue('nb_medioteca_key');
    }

    /**
     * Sets the Medioteca Key attribute value.
     * @param string|null $key New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setKey(string $key = null) : CNabuDataObject
    {
        $this->setValue('nb_medioteca_key', $key);
        
        return $this;
    }

    /**
     * Get User Id attribute value
     * @return null|int Returns the User Id value
     */
    public function getUserId()
    {
        return $this->getValue('nb_user_id');
    }

    /**
     * Sets the User Id attribute value.
     * @param int|null $nb_user_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setUserId(int $nb_user_id = null) : CNabuDataObject
    {
        $this->setValue('nb_user_id', $nb_user_id);
        
        return $this;
    }

    /**
     * Get Medioteca Default Language Id attribute value
     * @return null|int Returns the Medioteca Default Language Id value
     */
    public function getDefaultLanguageId()
    {
        return $this->getValue('nb_medioteca_default_language_id');
    }

    /**
     * Sets the Medioteca Default Language Id attribute value.
     * @param int|null $default_language_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setDefaultLanguageId(int $default_language_id = null) : CNabuDataObject
    {
        $this->setValue('nb_medioteca_default_language_id', $default_language_id);
        
        return $this;
    }

    /**
     * Get Medioteca Base Path attribute value
     * @return null|string Returns the Medioteca Base Path value
     */
    public function getBasePath()
    {
        return $this->getValue('nb_medioteca_base_path');
    }

    /**
     * Sets the Medioteca Base Path attribute value.
     * @param string|null $base_path New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setBasePath(string $base_path = null) : CNabuDataObject
    {
        $this->setValue('nb_medioteca_base_path', $base_path);
        
        return $this;
    }

    /**
     * Get Medioteca Internal Path attribute value
     * @return null|string Returns the Medioteca Internal Path value
     */
    public function getInternalPath()
    {
        return $this->getValue('nb_medioteca_internal_path');
    }

    /**
     * Sets the Medioteca Internal Path attribute value.
     * @param string|null $internal_path New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setInternalPath(string $internal_path = null) : CNabuDataObject
    {
        $this->setValue('nb_medioteca_internal_path', $internal_path);
        
        return $this;
    }

    /**
     * Get Medioteca Notes attribute value
     * @return null|string Returns the Medioteca Notes value
     */
    public function getNotes()
    {
        return $this->getValue('nb_medioteca_notes');
    }

    /**
     * Sets the Medioteca Notes attribute value.
     * @param string|null $notes New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setNotes(string $notes = null) : CNabuDataObject
    {
        $this->setValue('nb_medioteca_notes', $notes);
        
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
