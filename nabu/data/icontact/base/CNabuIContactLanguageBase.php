<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/04/26 08:36:11 UTC
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

namespace nabu\data\icontact\base;

use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\CNabuDataObject;
use \nabu\data\icontact\CNabuIContactLanguage;
use \nabu\data\icontact\CNabuIContactLanguageList;
use \nabu\data\lang\CNabuLanguage;
use \nabu\data\lang\CNabuLanguageList;
use \nabu\data\lang\interfaces\INabuTranslated;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslation;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity iContact Language stored in the storage named nb_icontact_lang.
 * @version 3.0.12 Surface
 * @package \nabu\data\icontact\base
 */
abstract class CNabuIContactLanguageBase extends CNabuDBInternalObject implements INabuTranslation
{
    use TNabuTranslation;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_icontact An instance of CNabuIContactLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_icontact_id, or a valid ID.
     * @param mixed $nb_language An instance of CNabuIContactLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_language_id, or a valid ID.
     */
    public function __construct($nb_icontact = false, $nb_language = false)
    {
        if ($nb_icontact) {
            $this->transferMixedValue($nb_icontact, 'nb_icontact_id');
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
        return 'nb_icontact_lang';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_icontact_id') && $this->isValueNumeric('nb_language_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_icontact_lang '
                   . "where nb_icontact_id=%nb_icontact_id\$d "
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
        $nb_icontact_id = nb_getMixedValue($translated, 'nb_icontact_id');
        if (is_numeric($nb_icontact_id)) {
            $retval = CNabuLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select l.* '
                    . 'from nb_language l, nb_icontact t1, nb_icontact_lang t2 '
                   . 'where t1.nb_icontact_id=t2.nb_icontact_id '
                     . 'and t1.nb_icontact_id=%nb_icontact_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_icontact_id' => $nb_icontact_id
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
     * @return CNabuIContactLanguageList Returns a list of translations. If no translations are available, the list is
     * empty.
     */
    public static function getTranslationsForTranslatedObject($translated)
    {
        $nb_icontact_id = nb_getMixedValue($translated, 'nb_icontact_id');
        if (is_numeric($nb_icontact_id)) {
            $retval = CNabuIContactLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select t2.* '
                    . 'from nb_language l, nb_icontact t1, nb_icontact_lang t2 '
                   . 'where t1.nb_icontact_id=t2.nb_icontact_id '
                     . 'and t1.nb_icontact_id=%nb_icontact_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_icontact_id' => $nb_icontact_id
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
            $retval = new CNabuIContactLanguageList();
        }
        
        return $retval;
    }

    /**
     * Get Icontact Id attribute value
     * @return int Returns the Icontact Id value
     */
    public function getIcontactId() : int
    {
        return $this->getValue('nb_icontact_id');
    }

    /**
     * Sets the Icontact Id attribute value.
     * @param int $nb_icontact_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setIcontactId(int $nb_icontact_id) : CNabuDataObject
    {
        if ($nb_icontact_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_icontact_id")
            );
        }
        $this->setValue('nb_icontact_id', $nb_icontact_id);
        
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
     * Get Icontact Lang Name attribute value
     * @return null|string Returns the Icontact Lang Name value
     */
    public function getName()
    {
        return $this->getValue('nb_icontact_lang_name');
    }

    /**
     * Sets the Icontact Lang Name attribute value.
     * @param null|string $name New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setName(string $name = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_lang_name', $name);
        
        return $this;
    }
}
