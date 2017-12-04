<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/12/04 11:57:52 UTC
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

namespace nabu\data\security\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\CNabuDataObject;
use \nabu\data\customer\CNabuCustomer;
use \nabu\data\customer\traits\TNabuCustomerChild;
use \nabu\data\lang\CNabuLanguage;
use \nabu\data\lang\CNabuLanguageList;
use \nabu\data\lang\interfaces\INabuTranslated;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslated;
use \nabu\data\security\builtin\CNabuBuiltInUserGroupTypeLanguage;
use \nabu\data\security\CNabuUserGroupType;
use \nabu\data\security\CNabuUserGroupTypeLanguage;
use \nabu\data\security\CNabuUserGroupTypeLanguageList;
use \nabu\data\security\CNabuUserGroupTypeList;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity User Group Type stored in the storage named nb_user_group_type.
 * @version 3.0.12 Surface
 * @package \nabu\data\security\base
 */
abstract class CNabuUserGroupTypeBase extends CNabuDBInternalObject implements INabuTranslated
{
    use TNabuCustomerChild;
    use TNabuTranslated;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_user_group_type An instance of CNabuUserGroupTypeBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_user_group_type_id, or a valid ID.
     */
    public function __construct($nb_user_group_type = false)
    {
        if ($nb_user_group_type) {
            $this->transferMixedValue($nb_user_group_type, 'nb_user_group_type_id');
        }
        
        parent::__construct();
        $this->__translatedConstruct();
        $this->translations_list = new CNabuUserGroupTypeLanguageList();
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
        return 'nb_user_group_type';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_user_group_type_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_user_group_type '
                   . "where nb_user_group_type_id=%nb_user_group_type_id\$d "
              )
            : null;
    }

    /**
     * Find an instance identified by nb_user_group_type_key field.
     * @param mixed $nb_customer Customer that owns User Group Type
     * @param string $key Key to search
     * @return CNabuUserGroupType Returns a valid instance if exists or null if not.
     */
    public static function findByKey($nb_customer, $key)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, 'nb_customer_id');
        if (is_numeric($nb_customer_id)) {
            $retval = CNabuUserGroupType::buildObjectFromSQL(
                    'select * '
                    . 'from nb_user_group_type '
                   . 'where nb_customer_id=%cust_id$d '
                     . "and nb_user_group_type_key='%key\$s'",
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
     * Get all items in the storage as an associative array where the field 'nb_user_group_type_id' is the index, and
     * each value is an instance of class CNabuUserGroupTypeBase.
     * @param CNabuCustomer $nb_customer The CNabuCustomer instance of the Customer that owns the User Group Type List.
     * @return mixed Returns and array with all items.
     */
    public static function getAllUserGroupTypes(CNabuCustomer $nb_customer)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, 'nb_customer_id');
        if (is_numeric($nb_customer_id)) {
            $retval = forward_static_call(
            array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_user_group_type_id',
                'select * '
                . 'from nb_user_group_type '
               . 'where nb_customer_id=%cust_id$d',
                array(
                    'cust_id' => $nb_customer_id
                ),
                $nb_customer
            );
        } else {
            $retval = new CNabuUserGroupTypeList();
        }
        
        return $retval;
    }

    /**
     * Gets a filtered list of User Group Type instances represented as an array. Params allows the capability of
     * select a subset of fields, order by concrete fields, or truncate the list by a number of rows starting in an
     * offset.
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
    public static function getFilteredUserGroupTypeList($nb_customer, $q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, NABU_CUSTOMER_FIELD_ID);
        if (is_numeric($nb_customer_id)) {
            $fields_part = nb_prefixFieldList(CNabuUserGroupTypeBase::getStorageName(), $fields, false, true, '`');
            $order_part = nb_prefixFieldList(CNabuUserGroupTypeBase::getStorageName(), $fields, false, false, '`');
        
            if ($num_items !== 0) {
                $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
            } else {
                $limit_part = false;
            }
        
            $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
                "select " . ($fields_part ? $fields_part . ' ' : '* ')
                . 'from nb_user_group_type '
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
                $translation instanceof CNabuUserGroupTypeLanguage &&
                $translation->matchValue($this, 'nb_user_group_type_id')
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
            $this->languages_list = CNabuUserGroupTypeLanguage::getLanguagesForTranslatedObject($this);
        }
        
        return $this->languages_list;
    }

    /**
     * Gets available translation instances.
     * @param bool $force If true force to reload translations list from storage.
     * @return null|array Return an array of \nabu\data\security\CNabuUserGroupTypeLanguage instances if they have
     * translations or null if not.
     */
    public function getTranslations($force = false)
    {
        if (!CNabuEngine::getEngine()->isOperationModeStandalone() &&
            ($this->translations_list->getSize() === 0 || $force)
        ) {
            $this->translations_list = CNabuUserGroupTypeLanguage::getTranslationsForTranslatedObject($this);
        }
        
        return $this->translations_list;
    }

    /**
     * Creates a new translation instance. I the translation already exists then replaces ancient translation with this
     * new.
     * @param int|string|CNabuDataObject $nb_language A valid Id or object containing a nb_language_id field to
     * identify the language of new translation.
     * @return CNabuUserGroupTypeLanguage Returns the created instance to store translation or null if not valid
     * language was provided.
     */
    public function newTranslation($nb_language)
    {
        $nb_language_id = nb_getMixedValue($nb_language, NABU_LANG_FIELD_ID);
        if (is_numeric($nb_language_id) || nb_isValidGUID($nb_language_id)) {
            $nb_translation = $this->isBuiltIn()
                            ? new CNabuBuiltInUserGroupTypeLanguage()
                            : new CNabuUserGroupTypeLanguage()
            ;
            $nb_translation->transferValue($this, 'nb_user_group_type_id');
            $nb_translation->transferValue($nb_language, NABU_LANG_FIELD_ID);
            $this->setTranslation($nb_translation);
        } else {
            $nb_translation = null;
        }
        
        return $nb_translation;
    }

    /**
     * Get all language instances used along of all User Group Type set of a Customer
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
                        . 'from nb_user_group_type ca, nb_user_group_type_lang cal '
                       . 'where ca.nb_user_group_type_id=cal.nb_user_group_type_id '
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
     * Get User Group Type Id attribute value
     * @return int Returns the User Group Type Id value
     */
    public function getId() : int
    {
        return $this->getValue('nb_user_group_type_id');
    }

    /**
     * Sets the User Group Type Id attribute value.
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
        $this->setValue('nb_user_group_type_id', $id);
        
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
     * Get User Group Type Key attribute value
     * @return null|string Returns the User Group Type Key value
     */
    public function getKey()
    {
        return $this->getValue('nb_user_group_type_key');
    }

    /**
     * Sets the User Group Type Key attribute value.
     * @param string|null $key New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setKey(string $key = null) : CNabuDataObject
    {
        $this->setValue('nb_user_group_type_key', $key);
        
        return $this;
    }

    /**
     * Get User Group Type Name attribute value
     * @return null|string Returns the User Group Type Name value
     */
    public function getName()
    {
        return $this->getValue('nb_user_group_type_name');
    }

    /**
     * Sets the User Group Type Name attribute value.
     * @param string|null $name New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setName(string $name = null) : CNabuDataObject
    {
        $this->setValue('nb_user_group_type_name', $name);
        
        return $this;
    }

    /**
     * Get User Group Type Exclusive attribute value
     * @return string Returns the User Group Type Exclusive value
     */
    public function getExclusive() : string
    {
        return $this->getValue('nb_user_group_type_exclusive');
    }

    /**
     * Sets the User Group Type Exclusive attribute value.
     * @param string $exclusive New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setExclusive(string $exclusive = "F") : CNabuDataObject
    {
        if ($exclusive === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$exclusive")
            );
        }
        $this->setValue('nb_user_group_type_exclusive', $exclusive);
        
        return $this;
    }

    /**
     * Get User Group Type Creator Owner attribute value
     * @return string Returns the User Group Type Creator Owner value
     */
    public function getCreatorOwner() : string
    {
        return $this->getValue('nb_user_group_type_creator_owner');
    }

    /**
     * Sets the User Group Type Creator Owner attribute value.
     * @param string $creator_owner New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCreatorOwner(string $creator_owner = "T") : CNabuDataObject
    {
        if ($creator_owner === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$creator_owner")
            );
        }
        $this->setValue('nb_user_group_type_creator_owner', $creator_owner);
        
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
