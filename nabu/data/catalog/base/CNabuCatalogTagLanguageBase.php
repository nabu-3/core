<?php
/* ===========================================================================
 * File generated automatically by Nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/03/23 11:36:11 UTC
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

namespace nabu\data\catalog\base;

use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\catalog\CNabuCatalogTagLanguage;
use \nabu\data\catalog\CNabuCatalogTagLanguageList;
use \nabu\data\lang\CNabuLanguage;
use \nabu\data\lang\CNabuLanguageList;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslation;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Catalog Tag Language stored in the storage named nb_catalog_tag_lang.
 * @author Rafael Gutiérrez Martínez <rgutierrez@wiscot.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\catalog\base
 */
abstract class CNabuCatalogTagLanguageBase extends CNabuDBInternalObject implements INabuTranslation
{
    use TNabuTranslation;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_catalog_tag An instance of CNabuCatalogTagLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_catalog_tag_id, or a valid ID.
     * @param mixed $nb_language An instance of CNabuCatalogTagLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_language_id, or a valid ID.
     */
    public function __construct($nb_catalog_tag = false, $nb_language = false)
    {
        if ($nb_catalog_tag) {
            $this->transferMixedValue($nb_catalog_tag, 'nb_catalog_tag_id');
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
        return 'nb_catalog_tag_lang';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_catalog_tag_id') && $this->isValueNumeric('nb_language_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_catalog_tag_lang '
                   . "where nb_catalog_tag_id=%nb_catalog_tag_id\$d "
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
        $nb_catalog_tag_id = nb_getMixedValue($translated, 'nb_catalog_tag_id');
        if (is_numeric($nb_catalog_tag_id)) {
            $retval = CNabuLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select l.* '
                    . 'from nb_language l, nb_catalog_tag t1, nb_catalog_tag_lang t2 '
                   . 'where t1.nb_catalog_tag_id=t2.nb_catalog_tag_id '
                     . 'and t1.nb_catalog_tag_id=%nb_catalog_tag_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_catalog_tag_id' => $nb_catalog_tag_id
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
     * @return CNabuCatalogTagLanguageList Returns a list of translations. If no translations are available, the list
     * is empty.
     */
    public static function getTranslationsForTranslatedObject($translated)
    {
        $nb_catalog_tag_id = nb_getMixedValue($translated, 'nb_catalog_tag_id');
        if (is_numeric($nb_catalog_tag_id)) {
            $retval = CNabuCatalogTagLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select t2.* '
                    . 'from nb_language l, nb_catalog_tag t1, nb_catalog_tag_lang t2 '
                   . 'where t1.nb_catalog_tag_id=t2.nb_catalog_tag_id '
                     . 'and t1.nb_catalog_tag_id=%nb_catalog_tag_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_catalog_tag_id' => $nb_catalog_tag_id
                    )
            );
            $retval->iterate(
                function ($key, $nb_translation) use($translated) {
                    $nb_translation->setTranslatedObject($translated);
                }
            );
        } else {
            $retval = new CNabuCatalogTagLanguageList();
        }
        
        return $retval;
    }

    /**
     * Get Catalog Tag Id attribute value
     * @return int Returns the Catalog Tag Id value
     */
    public function getCatalogTagId()
    {
        return $this->getValue('nb_catalog_tag_id');
    }

    /**
     * Sets the Catalog Tag Id attribute value
     * @param int $nb_catalog_tag_id New value for attribute
     * @return CNabuCatalogTagLanguageBase Returns $this
     */
    public function setCatalogTagId($nb_catalog_tag_id)
    {
        if ($nb_catalog_tag_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_catalog_tag_id")
            );
        }
        $this->setValue('nb_catalog_tag_id', $nb_catalog_tag_id);
        
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
     * @return CNabuCatalogTagLanguageBase Returns $this
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
     * Get Catalog Tag Lang Slug attribute value
     * @return null|string Returns the Catalog Tag Lang Slug value
     */
    public function getSlug()
    {
        return $this->getValue('nb_catalog_tag_lang_slug');
    }

    /**
     * Sets the Catalog Tag Lang Slug attribute value
     * @param null|string $slug New value for attribute
     * @return CNabuCatalogTagLanguageBase Returns $this
     */
    public function setSlug($slug)
    {
        $this->setValue('nb_catalog_tag_lang_slug', $slug);
        
        return $this;
    }

    /**
     * Get Catalog Tag Lang Title attribute value
     * @return null|string Returns the Catalog Tag Lang Title value
     */
    public function getTitle()
    {
        return $this->getValue('nb_catalog_tag_lang_title');
    }

    /**
     * Sets the Catalog Tag Lang Title attribute value
     * @param null|string $title New value for attribute
     * @return CNabuCatalogTagLanguageBase Returns $this
     */
    public function setTitle($title)
    {
        $this->setValue('nb_catalog_tag_lang_title', $title);
        
        return $this;
    }

    /**
     * Get Catalog Tag Lang Image attribute value
     * @return null|string Returns the Catalog Tag Lang Image value
     */
    public function getImage()
    {
        return $this->getValue('nb_catalog_tag_lang_image');
    }

    /**
     * Sets the Catalog Tag Lang Image attribute value
     * @param null|string $image New value for attribute
     * @return CNabuCatalogTagLanguageBase Returns $this
     */
    public function setImage($image)
    {
        $this->setValue('nb_catalog_tag_lang_image', $image);
        
        return $this;
    }

    /**
     * Get Catalog Tag Lang Alternate attribute value
     * @return null|string Returns the Catalog Tag Lang Alternate value
     */
    public function getAlternate()
    {
        return $this->getValue('nb_catalog_tag_lang_alternate');
    }

    /**
     * Sets the Catalog Tag Lang Alternate attribute value
     * @param null|string $alternate New value for attribute
     * @return CNabuCatalogTagLanguageBase Returns $this
     */
    public function setAlternate($alternate)
    {
        $this->setValue('nb_catalog_tag_lang_alternate', $alternate);
        
        return $this;
    }

    /**
     * Get Catalog Tag Lang Anchor Text attribute value
     * @return null|string Returns the Catalog Tag Lang Anchor Text value
     */
    public function getAnchorText()
    {
        return $this->getValue('nb_catalog_tag_lang_anchor_text');
    }

    /**
     * Sets the Catalog Tag Lang Anchor Text attribute value
     * @param null|string $anchor_text New value for attribute
     * @return CNabuCatalogTagLanguageBase Returns $this
     */
    public function setAnchorText($anchor_text)
    {
        $this->setValue('nb_catalog_tag_lang_anchor_text', $anchor_text);
        
        return $this;
    }
}
