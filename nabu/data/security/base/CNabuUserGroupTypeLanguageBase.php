<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2018/03/07 10:44:12 UTC
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

namespace nabu\data\security\base;

use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\CNabuDataObject;
use \nabu\data\lang\CNabuLanguage;
use \nabu\data\lang\CNabuLanguageList;
use \nabu\data\lang\interfaces\INabuTranslated;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslation;
use \nabu\data\security\CNabuUserGroupTypeLanguage;
use \nabu\data\security\CNabuUserGroupTypeLanguageList;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity User Group Type Language stored in the storage named nb_user_group_type_lang.
 * @version 3.0.12 Surface
 * @package \nabu\data\security\base
 */
abstract class CNabuUserGroupTypeLanguageBase extends CNabuDBInternalObject implements INabuTranslation
{
    use TNabuTranslation;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_user_group_type An instance of CNabuUserGroupTypeLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_user_group_type_id, or a valid ID.
     * @param mixed $nb_language An instance of CNabuUserGroupTypeLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_language_id, or a valid ID.
     */
    public function __construct($nb_user_group_type = false, $nb_language = false)
    {
        if ($nb_user_group_type) {
            $this->transferMixedValue($nb_user_group_type, 'nb_user_group_type_id');
        }
        
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
        return 'nb_user_group_type_lang';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_user_group_type_id') && $this->isValueNumeric('nb_language_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_user_group_type_lang '
                   . "where nb_user_group_type_id=%nb_user_group_type_id\$d "
                     . "and nb_language_id=%nb_language_id\$d "
              )
            : null;
    }

    /**
     * Query the storage to retrieve the full list of available languages (those that correspond to existent
     * translations) for $translated and returns a list with all languages.
     * @param mixed $translated Translated object or Id to retrieve languages.
     * @return CNabuLanguageList Returns a list of languages. If no languages are available, the list is empty.
     */
    public static function getLanguagesForTranslatedObject($translated)
    {
        $nb_user_group_type_id = nb_getMixedValue($translated, 'nb_user_group_type_id');
        if (is_numeric($nb_user_group_type_id)) {
            $retval = CNabuLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select l.* '
                    . 'from nb_language l, nb_user_group_type t1, nb_user_group_type_lang t2 '
                   . 'where t1.nb_user_group_type_id=t2.nb_user_group_type_id '
                     . 'and t1.nb_user_group_type_id=%nb_user_group_type_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_user_group_type_id' => $nb_user_group_type_id
                    )
            );
        } else {
            $retval = new CNabuLanguageList();
        }
        
        return $retval;
    }

    /**
     * Query the storage to retrieve the full list of available translations for $translated and returns a list with
     * all translations.
     * @param mixed $translated Translated object or Id to retrieve translations.
     * @return CNabuUserGroupTypeLanguageList Returns a list of translations. If no translations are available, the
     * list is empty.
     */
    public static function getTranslationsForTranslatedObject($translated)
    {
        $nb_user_group_type_id = nb_getMixedValue($translated, 'nb_user_group_type_id');
        if (is_numeric($nb_user_group_type_id)) {
            $retval = CNabuUserGroupTypeLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select t2.* '
                    . 'from nb_language l, nb_user_group_type t1, nb_user_group_type_lang t2 '
                   . 'where t1.nb_user_group_type_id=t2.nb_user_group_type_id '
                     . 'and t1.nb_user_group_type_id=%nb_user_group_type_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_user_group_type_id' => $nb_user_group_type_id
                    )
            );
            if ($translated instanceof INabuTranslated) {
                $retval->iterate(
                    function ($key, $nb_translation) use($translated) {
                        $nb_translation->setTranslatedObject($translated);
                        return true;
                    }
                );
            }
        } else {
            $retval = new CNabuUserGroupTypeLanguageList();
        }
        
        return $retval;
    }

    /**
     * Get User Group Type Id attribute value
     * @return int Returns the User Group Type Id value
     */
    public function getUserGroupTypeId() : int
    {
        return $this->getValue('nb_user_group_type_id');
    }

    /**
     * Sets the User Group Type Id attribute value.
     * @param int $nb_user_group_type_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setUserGroupTypeId(int $nb_user_group_type_id) : CNabuDataObject
    {
        if ($nb_user_group_type_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_user_group_type_id")
            );
        }
        $this->setValue('nb_user_group_type_id', $nb_user_group_type_id);
        
        return $this;
    }

    /**
     * Get Language Id attribute value
     * @return int Returns the Language Id value
     */
    public function getLanguageId() : int
    {
        return $this->getValue('nb_language_id');
    }

    /**
     * Sets the Language Id attribute value.
     * @param int $nb_language_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setLanguageId(int $nb_language_id) : CNabuDataObject
    {
        if ($nb_language_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_language_id")
            );
        }
        $this->setValue('nb_language_id', $nb_language_id);
        
        return $this;
    }

    /**
     * Get User Group Type Lang Name attribute value
     * @return null|string Returns the User Group Type Lang Name value
     */
    public function getName()
    {
        return $this->getValue('nb_user_group_type_lang_name');
    }

    /**
     * Sets the User Group Type Lang Name attribute value.
     * @param string|null $name New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setName(string $name = null) : CNabuDataObject
    {
        $this->setValue('nb_user_group_type_lang_name', $name);
        
        return $this;
    }
}
