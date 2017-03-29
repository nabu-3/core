<?php
/* ===========================================================================
 * File generated automatically by Nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/03/29 13:31:03 UTC
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

namespace nabu\data\medioteca\base;

use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\lang\CNabuLanguage;
use \nabu\data\lang\CNabuLanguageList;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslation;
use \nabu\data\medioteca\CNabuMediotecaItemLanguage;
use \nabu\data\medioteca\CNabuMediotecaItemLanguageList;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Medioteca Item Language stored in the storage named nb_medioteca_item_lang.
 * @author Rafael Gutiérrez Martínez <rgutierrez@wiscot.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\medioteca\base
 */
abstract class CNabuMediotecaItemLanguageBase extends CNabuDBInternalObject implements INabuTranslation
{
    use TNabuTranslation;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_medioteca_item An instance of CNabuMediotecaItemLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_medioteca_item_id, or a valid ID.
     * @param mixed $nb_language An instance of CNabuMediotecaItemLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_language_id, or a valid ID.
     */
    public function __construct($nb_medioteca_item = false, $nb_language = false)
    {
        if ($nb_medioteca_item) {
            $this->transferMixedValue($nb_medioteca_item, 'nb_medioteca_item_id');
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
        return 'nb_medioteca_item_lang';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_medioteca_item_id') && $this->isValueNumeric('nb_language_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_medioteca_item_lang '
                   . "where nb_medioteca_item_id=%nb_medioteca_item_id\$d "
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
        $nb_medioteca_item_id = nb_getMixedValue($translated, 'nb_medioteca_item_id');
        if (is_numeric($nb_medioteca_item_id)) {
            $retval = CNabuLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select l.* '
                    . 'from nb_language l, nb_medioteca_item t1, nb_medioteca_item_lang t2 '
                   . 'where t1.nb_medioteca_item_id=t2.nb_medioteca_item_id '
                     . 'and t1.nb_medioteca_item_id=%nb_medioteca_item_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_medioteca_item_id' => $nb_medioteca_item_id
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
     * @return CNabuMediotecaItemLanguageList Returns a list of translations. If no translations are available, the
     * list is empty.
     */
    public static function getTranslationsForTranslatedObject($translated)
    {
        $nb_medioteca_item_id = nb_getMixedValue($translated, 'nb_medioteca_item_id');
        if (is_numeric($nb_medioteca_item_id)) {
            $retval = CNabuMediotecaItemLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select t2.* '
                    . 'from nb_language l, nb_medioteca_item t1, nb_medioteca_item_lang t2 '
                   . 'where t1.nb_medioteca_item_id=t2.nb_medioteca_item_id '
                     . 'and t1.nb_medioteca_item_id=%nb_medioteca_item_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_medioteca_item_id' => $nb_medioteca_item_id
                    )
            );
            $retval->iterate(
                function ($key, $nb_translation) use($translated) {
                    $nb_translation->setTranslatedObject($translated);
                }
            );
        } else {
            $retval = new CNabuMediotecaItemLanguageList();
        }
        
        return $retval;
    }

    /**
     * Get Medioteca Item Id attribute value
     * @return int Returns the Medioteca Item Id value
     */
    public function getMediotecaItemId()
    {
        return $this->getValue('nb_medioteca_item_id');
    }

    /**
     * Sets the Medioteca Item Id attribute value
     * @param int $nb_medioteca_item_id New value for attribute
     * @return CNabuMediotecaItemLanguageBase Returns $this
     */
    public function setMediotecaItemId($nb_medioteca_item_id)
    {
        if ($nb_medioteca_item_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_medioteca_item_id")
            );
        }
        $this->setValue('nb_medioteca_item_id', $nb_medioteca_item_id);
        
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
     * @return CNabuMediotecaItemLanguageBase Returns $this
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
     * Get Medioteca Item Lang Last Update Datetime attribute value
     * @return mixed Returns the Medioteca Item Lang Last Update Datetime value
     */
    public function getLastUpdateDatetime()
    {
        return $this->getValue('nb_medioteca_item_lang_last_update_datetime');
    }

    /**
     * Sets the Medioteca Item Lang Last Update Datetime attribute value
     * @param mixed $last_update_datetime New value for attribute
     * @return CNabuMediotecaItemLanguageBase Returns $this
     */
    public function setLastUpdateDatetime($last_update_datetime)
    {
        $this->setValue('nb_medioteca_item_lang_last_update_datetime', $last_update_datetime);
        
        return $this;
    }

    /**
     * Get Medioteca Item Lang Type attribute value
     * @return mixed Returns the Medioteca Item Lang Type value
     */
    public function getType()
    {
        return $this->getValue('nb_medioteca_item_lang_type');
    }

    /**
     * Sets the Medioteca Item Lang Type attribute value
     * @param mixed $type New value for attribute
     * @return CNabuMediotecaItemLanguageBase Returns $this
     */
    public function setType($type)
    {
        if ($type === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$type")
            );
        }
        $this->setValue('nb_medioteca_item_lang_type', $type);
        
        return $this;
    }

    /**
     * Get Medioteca Item Lang Have Public attribute value
     * @return mixed Returns the Medioteca Item Lang Have Public value
     */
    public function getHavePublic()
    {
        return $this->getValue('nb_medioteca_item_lang_have_public');
    }

    /**
     * Sets the Medioteca Item Lang Have Public attribute value
     * @param mixed $have_public New value for attribute
     * @return CNabuMediotecaItemLanguageBase Returns $this
     */
    public function setHavePublic($have_public)
    {
        if ($have_public === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$have_public")
            );
        }
        $this->setValue('nb_medioteca_item_lang_have_public', $have_public);
        
        return $this;
    }

    /**
     * Get Medioteca Item Lang Mime Type attribute value
     * @return null|string Returns the Medioteca Item Lang Mime Type value
     */
    public function getMimeType()
    {
        return $this->getValue('nb_medioteca_item_lang_mime_type');
    }

    /**
     * Sets the Medioteca Item Lang Mime Type attribute value
     * @param null|string $mime_type New value for attribute
     * @return CNabuMediotecaItemLanguageBase Returns $this
     */
    public function setMimeType($mime_type)
    {
        $this->setValue('nb_medioteca_item_lang_mime_type', $mime_type);
        
        return $this;
    }

    /**
     * Get Medioteca Item Lang Title attribute value
     * @return null|string Returns the Medioteca Item Lang Title value
     */
    public function getTitle()
    {
        return $this->getValue('nb_medioteca_item_lang_title');
    }

    /**
     * Sets the Medioteca Item Lang Title attribute value
     * @param null|string $title New value for attribute
     * @return CNabuMediotecaItemLanguageBase Returns $this
     */
    public function setTitle($title)
    {
        $this->setValue('nb_medioteca_item_lang_title', $title);
        
        return $this;
    }

    /**
     * Get Medioteca Item Lang Subtitle attribute value
     * @return null|string Returns the Medioteca Item Lang Subtitle value
     */
    public function getSubtitle()
    {
        return $this->getValue('nb_medioteca_item_lang_subtitle');
    }

    /**
     * Sets the Medioteca Item Lang Subtitle attribute value
     * @param null|string $subtitle New value for attribute
     * @return CNabuMediotecaItemLanguageBase Returns $this
     */
    public function setSubtitle($subtitle)
    {
        $this->setValue('nb_medioteca_item_lang_subtitle', $subtitle);
        
        return $this;
    }

    /**
     * Get Medioteca Item Lang Opening attribute value
     * @return null|string Returns the Medioteca Item Lang Opening value
     */
    public function getOpening()
    {
        return $this->getValue('nb_medioteca_item_lang_opening');
    }

    /**
     * Sets the Medioteca Item Lang Opening attribute value
     * @param null|string $opening New value for attribute
     * @return CNabuMediotecaItemLanguageBase Returns $this
     */
    public function setOpening($opening)
    {
        $this->setValue('nb_medioteca_item_lang_opening', $opening);
        
        return $this;
    }

    /**
     * Get Medioteca Item Lang Content attribute value
     * @return null|string Returns the Medioteca Item Lang Content value
     */
    public function getContent()
    {
        return $this->getValue('nb_medioteca_item_lang_content');
    }

    /**
     * Sets the Medioteca Item Lang Content attribute value
     * @param null|string $content New value for attribute
     * @return CNabuMediotecaItemLanguageBase Returns $this
     */
    public function setContent($content)
    {
        $this->setValue('nb_medioteca_item_lang_content', $content);
        
        return $this;
    }

    /**
     * Get Medioteca Item Lang Footer attribute value
     * @return null|string Returns the Medioteca Item Lang Footer value
     */
    public function getFooter()
    {
        return $this->getValue('nb_medioteca_item_lang_footer');
    }

    /**
     * Sets the Medioteca Item Lang Footer attribute value
     * @param null|string $footer New value for attribute
     * @return CNabuMediotecaItemLanguageBase Returns $this
     */
    public function setFooter($footer)
    {
        $this->setValue('nb_medioteca_item_lang_footer', $footer);
        
        return $this;
    }

    /**
     * Get Medioteca Item Lang URL attribute value
     * @return null|string Returns the Medioteca Item Lang URL value
     */
    public function getURL()
    {
        return $this->getValue('nb_medioteca_item_lang_url');
    }

    /**
     * Sets the Medioteca Item Lang URL attribute value
     * @param null|string $url New value for attribute
     * @return CNabuMediotecaItemLanguageBase Returns $this
     */
    public function setURL($url)
    {
        $this->setValue('nb_medioteca_item_lang_url', $url);
        
        return $this;
    }

    /**
     * Get Medioteca Item Lang Html Object attribute value
     * @return null|string Returns the Medioteca Item Lang Html Object value
     */
    public function getHtmlObject()
    {
        return $this->getValue('nb_medioteca_item_lang_html_object');
    }

    /**
     * Sets the Medioteca Item Lang Html Object attribute value
     * @param null|string $html_object New value for attribute
     * @return CNabuMediotecaItemLanguageBase Returns $this
     */
    public function setHtmlObject($html_object)
    {
        $this->setValue('nb_medioteca_item_lang_html_object', $html_object);
        
        return $this;
    }

    /**
     * Get Medioteca Item Lang Public Path attribute value
     * @return null|string Returns the Medioteca Item Lang Public Path value
     */
    public function getPublicPath()
    {
        return $this->getValue('nb_medioteca_item_lang_public_path');
    }

    /**
     * Sets the Medioteca Item Lang Public Path attribute value
     * @param null|string $public_path New value for attribute
     * @return CNabuMediotecaItemLanguageBase Returns $this
     */
    public function setPublicPath($public_path)
    {
        $this->setValue('nb_medioteca_item_lang_public_path', $public_path);
        
        return $this;
    }

    /**
     * Get Medioteca Item Lang Original Path attribute value
     * @return null|string Returns the Medioteca Item Lang Original Path value
     */
    public function getOriginalPath()
    {
        return $this->getValue('nb_medioteca_item_lang_original_path');
    }

    /**
     * Sets the Medioteca Item Lang Original Path attribute value
     * @param null|string $original_path New value for attribute
     * @return CNabuMediotecaItemLanguageBase Returns $this
     */
    public function setOriginalPath($original_path)
    {
        $this->setValue('nb_medioteca_item_lang_original_path', $original_path);
        
        return $this;
    }
}
