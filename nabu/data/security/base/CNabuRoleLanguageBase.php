<?php
/* ===========================================================================
 * File generated automatically by Nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/01/10 17:58:02 UTC
 * ===========================================================================
 * Copyright 2009-2011 Rafael Gutierrez Martinez
 * Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 * Copyright 2014-2017 Where Ideas Simply Come True, S.L.
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
use \nabu\data\lang\CNabuLanguage;
use \nabu\data\lang\CNabuLanguageList;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslation;
use \nabu\data\security\CNabuRoleLanguage;
use \nabu\data\security\CNabuRoleLanguageList;
use \nabu\data\security\traits\TNabuRoleChild;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Role Language stored in the storage named nb_role_lang.
 * @author Rafael Gutiérrez Martínez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\security\base
 */
abstract class CNabuRoleLanguageBase extends CNabuDBInternalObject implements INabuTranslation
{
    use TNabuRoleChild;
    use TNabuTranslation;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_role An instance of CNabuRoleLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_role_id, or a valid ID.
     * @param mixed $nb_language An instance of CNabuRoleLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_language_id, or a valid ID.
     */
    public function __construct($nb_role = false, $nb_language = false)
    {
        if ($nb_role) {
            $this->transferMixedValue($nb_role, 'nb_role_id');
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
        return 'nb_role_lang';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_role_id') && $this->isValueNumeric('nb_language_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_role_lang '
                   . "where nb_role_id=%nb_role_id\$d "
                     . "and nb_language_id=%nb_language_id\$d "
              )
            : null;
    }

    /**
     * Query the storage to retrieve the full list of available languages (those that correspond to existent
     * translations) for $translated and returns an associative array in which each one is of class
     * \nabu\data\lang\CNabuLanguage.
     * @param object $translated Translated object to retrieve languages
     * @return false|null|array Returns an associative array indexed by the language Id, null if no languages are
     * available, or false if $translated cannot be identified.
     */
    public static function getLanguagesForTranslatedObject($translated)
    {
        $nb_role_id = nb_getMixedValue(
                $translated,
                'nb_role_id',
                '\\nabu\\data\\security\\CNabuRole'
        );
        if (is_numeric($nb_role_id)) {
            $retval = CNabuLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select l.* '
                    . 'from nb_language l, nb_role t1, nb_role_lang t2 '
                   . 'where t1.nb_role_id=t2.nb_role_id '
                     . 'and t1.nb_role_id=%nb_role_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_role_id' => $nb_role_id
                    )
            );
        } else {
            $retval = new CNabuLanguageList();
        }
        
        return $retval;
    }

    /**
     * Query the storage to retrieve the full list of available translations for $translated and returns an associative
     * array in which each one is of class \nabu\data\security\CNabuRoleLanguage.
     * @param object $translated Translated object to retrieve translations
     * @return false|null|array Returns an associative array indexed by the language Id, null if no languages are
     * available, or false if $translated cannot be identified.
     */
    public static function getTranslationsForTranslatedObject($translated)
    {
        $nb_role_id = nb_getMixedValue(
                $translated,
                'nb_role_id',
                '\\nabu\\data\\security\\CNabuRole'
        );
        if (is_numeric($nb_role_id)) {
            $retval = CNabuRoleLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select t2.* '
                    . 'from nb_language l, nb_role t1, nb_role_lang t2 '
                   . 'where t1.nb_role_id=t2.nb_role_id '
                     . 'and t1.nb_role_id=%nb_role_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_role_id' => $nb_role_id
                    )
            );
            $retval->iterate(
                function ($key, $nb_translation) use($translated) {
                    $nb_translation->setTranslatedObject($translated);
                }
            );
        } else {
            $retval = new CNabuRoleLanguageList();
        }
        
        return $retval;
    }

    /**
     * Get Role Id attribute value
     * @return int Returns the Role Id value
     */
    public function getRoleId()
    {
        return $this->getValue('nb_role_id');
    }

    /**
     * Sets the Role Id attribute value
     * @param int $nb_role_id New value for attribute
     * @return CNabuRoleLanguageBase Returns $this
     */
    public function setRoleId($nb_role_id)
    {
        if ($nb_role_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_role_id")
            );
        }
        $this->setValue('nb_role_id', $nb_role_id);
        
        return $this;
    }

    /**
     * Get Language Id attribute value
     * @return int Returns the Language Id value
     */
    public function getLanguageId()
    {
        return $this->getValue('nb_language_id');
    }

    /**
     * Sets the Language Id attribute value
     * @param int $nb_language_id New value for attribute
     * @return CNabuRoleLanguageBase Returns $this
     */
    public function setLanguageId($nb_language_id)
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
     * Get Role Lang Name attribute value
     * @return null|string Returns the Role Lang Name value
     */
    public function getName()
    {
        return $this->getValue('nb_role_lang_name');
    }

    /**
     * Sets the Role Lang Name attribute value
     * @param null|string $name New value for attribute
     * @return CNabuRoleLanguageBase Returns $this
     */
    public function setName($name)
    {
        $this->setValue('nb_role_lang_name', $name);
        
        return $this;
    }
}