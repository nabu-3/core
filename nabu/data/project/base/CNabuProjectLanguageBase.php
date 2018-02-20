<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2018/02/20 16:44:59 UTC
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

namespace nabu\data\project\base;

use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\CNabuDataObject;
use \nabu\data\lang\CNabuLanguage;
use \nabu\data\lang\CNabuLanguageList;
use \nabu\data\lang\interfaces\INabuTranslated;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslation;
use \nabu\data\project\CNabuProjectLanguage;
use \nabu\data\project\CNabuProjectLanguageList;
use \nabu\db\CNabuDBInternalObject;
use nabu\data\project\traits\TNabuProjectChild;

/**
 * Class to manage the entity Project Language stored in the storage named nb_project_lang.
 * @version 3.0.12 Surface
 * @package \nabu\data\project\base
 */
abstract class CNabuProjectLanguageBase extends CNabuDBInternalObject implements INabuTranslation
{
    use TNabuProjectChild;
    use TNabuTranslation;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_project An instance of CNabuProjectLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_project_id, or a valid ID.
     * @param mixed $nb_language An instance of CNabuProjectLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_language_id, or a valid ID.
     */
    public function __construct($nb_project = false, $nb_language = false)
    {
        if ($nb_project) {
            $this->transferMixedValue($nb_project, 'nb_project_id');
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
        return 'nb_project_lang';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_project_id') && $this->isValueNumeric('nb_language_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_project_lang '
                   . "where nb_project_id=%nb_project_id\$d "
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
        $nb_project_id = nb_getMixedValue($translated, 'nb_project_id');
        if (is_numeric($nb_project_id)) {
            $retval = CNabuLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select l.* '
                    . 'from nb_language l, nb_project t1, nb_project_lang t2 '
                   . 'where t1.nb_project_id=t2.nb_project_id '
                     . 'and t1.nb_project_id=%nb_project_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_project_id' => $nb_project_id
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
     * @return CNabuProjectLanguageList Returns a list of translations. If no translations are available, the list is
     * empty.
     */
    public static function getTranslationsForTranslatedObject($translated)
    {
        $nb_project_id = nb_getMixedValue($translated, 'nb_project_id');
        if (is_numeric($nb_project_id)) {
            $retval = CNabuProjectLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select t2.* '
                    . 'from nb_language l, nb_project t1, nb_project_lang t2 '
                   . 'where t1.nb_project_id=t2.nb_project_id '
                     . 'and t1.nb_project_id=%nb_project_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_project_id' => $nb_project_id
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
            $retval = new CNabuProjectLanguageList();
        }
        
        return $retval;
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
     * Get Project Lang Title attribute value
     * @return null|string Returns the Project Lang Title value
     */
    public function getTitle()
    {
        return $this->getValue('nb_project_lang_title');
    }

    /**
     * Sets the Project Lang Title attribute value.
     * @param string|null $title New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setTitle(string $title = null) : CNabuDataObject
    {
        $this->setValue('nb_project_lang_title', $title);
        
        return $this;
    }

    /**
     * Get Project Lang Subtitle attribute value
     * @return null|string Returns the Project Lang Subtitle value
     */
    public function getSubtitle()
    {
        return $this->getValue('nb_project_lang_subtitle');
    }

    /**
     * Sets the Project Lang Subtitle attribute value.
     * @param string|null $subtitle New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setSubtitle(string $subtitle = null) : CNabuDataObject
    {
        $this->setValue('nb_project_lang_subtitle', $subtitle);
        
        return $this;
    }

    /**
     * Get Project Lang Opening attribute value
     * @return null|string Returns the Project Lang Opening value
     */
    public function getOpening()
    {
        return $this->getValue('nb_project_lang_opening');
    }

    /**
     * Sets the Project Lang Opening attribute value.
     * @param string|null $opening New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setOpening(string $opening = null) : CNabuDataObject
    {
        $this->setValue('nb_project_lang_opening', $opening);
        
        return $this;
    }
}
