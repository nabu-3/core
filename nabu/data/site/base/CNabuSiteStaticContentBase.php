<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2018/02/14 15:55:09 UTC
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

namespace nabu\data\site\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\core\interfaces\INabuHashed;
use \nabu\core\traits\TNabuHashed;
use \nabu\data\CNabuDataObject;
use \nabu\data\lang\interfaces\INabuTranslated;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslated;
use \nabu\data\site\builtin\CNabuBuiltInSiteStaticContentLanguage;
use \nabu\data\site\CNabuSite;
use \nabu\data\site\CNabuSiteStaticContent;
use \nabu\data\site\CNabuSiteStaticContentLanguage;
use \nabu\data\site\CNabuSiteStaticContentLanguageList;
use \nabu\data\site\CNabuSiteStaticContentList;
use \nabu\data\site\traits\TNabuSiteChild;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Site Static Content stored in the storage named nb_site_static_content.
 * @version 3.0.12 Surface
 * @package \nabu\data\site\base
 */
abstract class CNabuSiteStaticContentBase extends CNabuDBInternalObject implements INabuTranslated, INabuHashed
{
    use TNabuHashed;
    use TNabuSiteChild;
    use TNabuTranslated;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_site_static_content An instance of CNabuSiteStaticContentBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_site_static_content_id, or a valid ID.
     */
    public function __construct($nb_site_static_content = false)
    {
        if ($nb_site_static_content) {
            $this->transferMixedValue($nb_site_static_content, 'nb_site_static_content_id');
        }
        
        parent::__construct();
        $this->__translatedConstruct();
        $this->translations_list = new CNabuSiteStaticContentLanguageList();
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
        return 'nb_site_static_content';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_site_static_content_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_site_static_content '
                   . "where nb_site_static_content_id=%nb_site_static_content_id\$d "
              )
            : null;
    }

    /**
     * Find an instance identified by nb_site_static_content_hash field.
     * @param string $hash Hash to search
     * @return CNabuDataObject Returns a valid instance if exists or null if not.
     */
    public static function findByHash(string $hash)
    {
        return CNabuSiteStaticContent::buildObjectFromSQL(
                'select * '
                . 'from nb_site_static_content '
               . "where nb_site_static_content_hash='%hash\$s'",
                array(
                    'hash' => $hash
                )
        );
    }

    /**
     * Find an instance identified by nb_site_static_content_key field.
     * @param mixed $nb_site Site that owns Site Static Content
     * @param string $key Key to search
     * @return CNabuSiteStaticContent Returns a valid instance if exists or null if not.
     */
    public static function findByKey($nb_site, $key)
    {
        $nb_site_id = nb_getMixedValue($nb_site, 'nb_site_id');
        if (is_numeric($nb_site_id)) {
            $retval = CNabuSiteStaticContent::buildObjectFromSQL(
                    'select * '
                    . 'from nb_site_static_content '
                   . 'where nb_site_id=%site_id$d '
                     . "and nb_site_static_content_key='%key\$s'",
                    array(
                        'site_id' => $nb_site_id,
                        'key' => $key
                    )
            );
        } else {
            $retval = null;
        }
        
        return $retval;
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_site_static_content_id' is the index,
     * and each value is an instance of class CNabuSiteStaticContentBase.
     * @param CNabuSite $nb_site The CNabuSite instance of the Site that owns the Site Static Content List.
     * @return mixed Returns and array with all items.
     */
    public static function getAllSiteStaticContents(CNabuSite $nb_site)
    {
        $nb_site_id = nb_getMixedValue($nb_site, 'nb_site_id');
        if (is_numeric($nb_site_id)) {
            $retval = forward_static_call(
            array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_site_static_content_id',
                'select * '
                . 'from nb_site_static_content '
               . 'where nb_site_id=%site_id$d',
                array(
                    'site_id' => $nb_site_id
                ),
                $nb_site
            );
        } else {
            $retval = new CNabuSiteStaticContentList();
        }
        
        return $retval;
    }

    /**
     * Gets a filtered list of Site Static Content instances represented as an array. Params allows the capability of
     * select a subset of fields, order by concrete fields, or truncate the list by a number of rows starting in an
     * offset.
     * @throws \nabu\core\exceptions\ENabuCoreException Raises an exception if $fields or $order have invalid values.
     * @param mixed $nb_site Site instance, object containing a Site Id field or an Id.
     * @param string $q Query string to filter results using a context index.
     * @param string|array $fields List of fields to put in the results.
     * @param string|array $order List of fields to order the results. Each field can be suffixed with "ASC" or "DESC"
     * to determine the short order
     * @param int $offset Offset of first row in the results having the first row at offset 0.
     * @param int $num_items Number of continue rows to get as maximum in the results.
     * @return array Returns an array with all rows found using the criteria.
     */
    public static function getFilteredSiteStaticContentList($nb_site, $q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $nb_site_id = nb_getMixedValue($nb_customer, NABU_SITE_FIELD_ID);
        if (is_numeric($nb_site_id)) {
            $fields_part = nb_prefixFieldList(CNabuSiteStaticContentBase::getStorageName(), $fields, false, true, '`');
            $order_part = nb_prefixFieldList(CNabuSiteStaticContentBase::getStorageName(), $fields, false, false, '`');
        
            if ($num_items !== 0) {
                $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
            } else {
                $limit_part = false;
            }
        
            $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
                "select " . ($fields_part ? $fields_part . ' ' : '* ')
                . 'from nb_site_static_content '
               . 'where ' . NABU_SITE_FIELD_ID . '=%site_id$d '
                . ($order_part ? "order by $order_part " : '')
                . ($limit_part ? "limit $limit_part" : ''),
                array(
                    'site_id' => $nb_site_id
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
                $translation instanceof CNabuSiteStaticContentLanguage &&
                $translation->matchValue($this, 'nb_site_static_content_id')
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
            $this->languages_list = CNabuSiteStaticContentLanguage::getLanguagesForTranslatedObject($this);
        }
        
        return $this->languages_list;
    }

    /**
     * Gets available translation instances.
     * @param bool $force If true force to reload translations list from storage.
     * @return null|array Return an array of \nabu\data\site\CNabuSiteStaticContentLanguage instances if they have
     * translations or null if not.
     */
    public function getTranslations($force = false)
    {
        if (!CNabuEngine::getEngine()->isOperationModeStandalone() &&
            ($this->translations_list->getSize() === 0 || $force)
        ) {
            $this->translations_list = CNabuSiteStaticContentLanguage::getTranslationsForTranslatedObject($this);
        }
        
        return $this->translations_list;
    }

    /**
     * Creates a new translation instance. I the translation already exists then replaces ancient translation with this
     * new.
     * @param int|string|CNabuDataObject $nb_language A valid Id or object containing a nb_language_id field to
     * identify the language of new translation.
     * @return CNabuSiteStaticContentLanguage Returns the created instance to store translation or null if not valid
     * language was provided.
     */
    public function newTranslation($nb_language)
    {
        $nb_language_id = nb_getMixedValue($nb_language, NABU_LANG_FIELD_ID);
        if (is_numeric($nb_language_id) || nb_isValidGUID($nb_language_id)) {
            $nb_translation = $this->isBuiltIn()
                            ? new CNabuBuiltInSiteStaticContentLanguage()
                            : new CNabuSiteStaticContentLanguage()
            ;
            $nb_translation->transferValue($this, 'nb_site_static_content_id');
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
     * Get Site Static Content Id attribute value
     * @return int Returns the Site Static Content Id value
     */
    public function getId() : int
    {
        return $this->getValue('nb_site_static_content_id');
    }

    /**
     * Sets the Site Static Content Id attribute value.
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
        $this->setValue('nb_site_static_content_id', $id);
        
        return $this;
    }

    /**
     * Get Site Id attribute value
     * @return int Returns the Site Id value
     */
    public function getSiteId() : int
    {
        return $this->getValue('nb_site_id');
    }

    /**
     * Sets the Site Id attribute value.
     * @param int $nb_site_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setSiteId(int $nb_site_id) : CNabuDataObject
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
     * Get Site Static Content Hash attribute value
     * @return null|string Returns the Site Static Content Hash value
     */
    public function getHash()
    {
        return $this->getValue('nb_site_static_content_hash');
    }

    /**
     * Sets the Site Static Content Hash attribute value.
     * @param string|null $hash New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setHash(string $hash = null) : CNabuDataObject
    {
        $this->setValue('nb_site_static_content_hash', $hash);
        
        return $this;
    }

    /**
     * Get Site Static Content Key attribute value
     * @return null|string Returns the Site Static Content Key value
     */
    public function getKey()
    {
        return $this->getValue('nb_site_static_content_key');
    }

    /**
     * Sets the Site Static Content Key attribute value.
     * @param string|null $key New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setKey(string $key = null) : CNabuDataObject
    {
        $this->setValue('nb_site_static_content_key', $key);
        
        return $this;
    }

    /**
     * Get Site Static Content Type attribute value
     * @return null|string Returns the Site Static Content Type value
     */
    public function getType()
    {
        return $this->getValue('nb_site_static_content_type');
    }

    /**
     * Sets the Site Static Content Type attribute value.
     * @param string|null $type New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setType(string $type = "P") : CNabuDataObject
    {
        $this->setValue('nb_site_static_content_type', $type);
        
        return $this;
    }

    /**
     * Get Site Static Content Use Alternative attribute value
     * @return null|string Returns the Site Static Content Use Alternative value
     */
    public function getUseAlternative()
    {
        return $this->getValue('nb_site_static_content_use_alternative');
    }

    /**
     * Sets the Site Static Content Use Alternative attribute value.
     * @param string|null $use_alternative New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setUseAlternative(string $use_alternative = "D") : CNabuDataObject
    {
        $this->setValue('nb_site_static_content_use_alternative', $use_alternative);
        
        return $this;
    }

    /**
     * Get Site Static Content Notes attribute value
     * @return null|string Returns the Site Static Content Notes value
     */
    public function getNotes()
    {
        return $this->getValue('nb_site_static_content_notes');
    }

    /**
     * Sets the Site Static Content Notes attribute value.
     * @param string|null $notes New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setNotes(string $notes = null) : CNabuDataObject
    {
        $this->setValue('nb_site_static_content_notes', $notes);
        
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
