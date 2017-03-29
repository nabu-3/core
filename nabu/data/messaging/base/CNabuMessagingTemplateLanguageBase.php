<?php
/* ===========================================================================
 * File generated automatically by Nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/03/29 13:31:07 UTC
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

namespace nabu\data\messaging\base;

use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\lang\CNabuLanguage;
use \nabu\data\lang\CNabuLanguageList;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslation;
use \nabu\data\messaging\CNabuMessagingTemplateLanguage;
use \nabu\data\messaging\CNabuMessagingTemplateLanguageList;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Messaging Template Language stored in the storage named nb_messaging_template_lang.
 * @author Rafael Gutiérrez Martínez <rgutierrez@wiscot.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\messaging\base
 */
abstract class CNabuMessagingTemplateLanguageBase extends CNabuDBInternalObject implements INabuTranslation
{
    use TNabuTranslation;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_messaging_template An instance of CNabuMessagingTemplateLanguageBase or another object
     * descending from \nabu\data\CNabuDataObject which contains a field named nb_messaging_template_id, or a valid ID.
     * @param mixed $nb_language An instance of CNabuMessagingTemplateLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_language_id, or a valid ID.
     */
    public function __construct($nb_messaging_template = false, $nb_language = false)
    {
        if ($nb_messaging_template) {
            $this->transferMixedValue($nb_messaging_template, 'nb_messaging_template_id');
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
        return 'nb_messaging_template_lang';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_messaging_template_id') && $this->isValueNumeric('nb_language_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_messaging_template_lang '
                   . "where nb_messaging_template_id=%nb_messaging_template_id\$d "
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
        $nb_messaging_template_id = nb_getMixedValue($translated, 'nb_messaging_template_id');
        if (is_numeric($nb_messaging_template_id)) {
            $retval = CNabuLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select l.* '
                    . 'from nb_language l, nb_messaging_template t1, nb_messaging_template_lang t2 '
                   . 'where t1.nb_messaging_template_id=t2.nb_messaging_template_id '
                     . 'and t1.nb_messaging_template_id=%nb_messaging_template_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_messaging_template_id' => $nb_messaging_template_id
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
     * @return CNabuMessagingTemplateLanguageList Returns a list of translations. If no translations are available, the
     * list is empty.
     */
    public static function getTranslationsForTranslatedObject($translated)
    {
        $nb_messaging_template_id = nb_getMixedValue($translated, 'nb_messaging_template_id');
        if (is_numeric($nb_messaging_template_id)) {
            $retval = CNabuMessagingTemplateLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select t2.* '
                    . 'from nb_language l, nb_messaging_template t1, nb_messaging_template_lang t2 '
                   . 'where t1.nb_messaging_template_id=t2.nb_messaging_template_id '
                     . 'and t1.nb_messaging_template_id=%nb_messaging_template_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_messaging_template_id' => $nb_messaging_template_id
                    )
            );
            $retval->iterate(
                function ($key, $nb_translation) use($translated) {
                    $nb_translation->setTranslatedObject($translated);
                }
            );
        } else {
            $retval = new CNabuMessagingTemplateLanguageList();
        }
        
        return $retval;
    }

    /**
     * Get Messaging Template Id attribute value
     * @return int Returns the Messaging Template Id value
     */
    public function getMessagingTemplateId()
    {
        return $this->getValue('nb_messaging_template_id');
    }

    /**
     * Sets the Messaging Template Id attribute value
     * @param int $nb_messaging_template_id New value for attribute
     * @return CNabuMessagingTemplateLanguageBase Returns $this
     */
    public function setMessagingTemplateId($nb_messaging_template_id)
    {
        if ($nb_messaging_template_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_messaging_template_id")
            );
        }
        $this->setValue('nb_messaging_template_id', $nb_messaging_template_id);
        
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
     * @return CNabuMessagingTemplateLanguageBase Returns $this
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
     * Get Messaging Template Lang Name attribute value
     * @return null|string Returns the Messaging Template Lang Name value
     */
    public function getName()
    {
        return $this->getValue('nb_messaging_template_lang_name');
    }

    /**
     * Sets the Messaging Template Lang Name attribute value
     * @param null|string $name New value for attribute
     * @return CNabuMessagingTemplateLanguageBase Returns $this
     */
    public function setName($name)
    {
        $this->setValue('nb_messaging_template_lang_name', $name);
        
        return $this;
    }

    /**
     * Get Messaging Template Lang Subject attribute value
     * @return null|string Returns the Messaging Template Lang Subject value
     */
    public function getSubject()
    {
        return $this->getValue('nb_messaging_template_lang_subject');
    }

    /**
     * Sets the Messaging Template Lang Subject attribute value
     * @param null|string $subject New value for attribute
     * @return CNabuMessagingTemplateLanguageBase Returns $this
     */
    public function setSubject($subject)
    {
        $this->setValue('nb_messaging_template_lang_subject', $subject);
        
        return $this;
    }

    /**
     * Get Messaging Template Lang Html attribute value
     * @return null|string Returns the Messaging Template Lang Html value
     */
    public function getHtml()
    {
        return $this->getValue('nb_messaging_template_lang_html');
    }

    /**
     * Sets the Messaging Template Lang Html attribute value
     * @param null|string $html New value for attribute
     * @return CNabuMessagingTemplateLanguageBase Returns $this
     */
    public function setHtml($html)
    {
        $this->setValue('nb_messaging_template_lang_html', $html);
        
        return $this;
    }

    /**
     * Get Messaging Template Lang Text attribute value
     * @return null|string Returns the Messaging Template Lang Text value
     */
    public function getText()
    {
        return $this->getValue('nb_messaging_template_lang_text');
    }

    /**
     * Sets the Messaging Template Lang Text attribute value
     * @param null|string $text New value for attribute
     * @return CNabuMessagingTemplateLanguageBase Returns $this
     */
    public function setText($text)
    {
        $this->setValue('nb_messaging_template_lang_text', $text);
        
        return $this;
    }
}