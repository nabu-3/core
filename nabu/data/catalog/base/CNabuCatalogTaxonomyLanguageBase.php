<?php
/* ===========================================================================
 * File generated automatically by Nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/03/08 12:49:01 UTC
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
use \nabu\data\catalog\CNabuCatalogTaxonomyLanguage;
use \nabu\data\catalog\CNabuCatalogTaxonomyLanguageList;
use \nabu\data\lang\CNabuLanguage;
use \nabu\data\lang\CNabuLanguageList;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslation;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Catalog Taxonomy Language stored in the storage named nb_catalog_taxonomy_lang.
 * @author Rafael Gutiérrez Martínez <rgutierrez@wiscot.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\catalog\base
 */
abstract class CNabuCatalogTaxonomyLanguageBase extends CNabuDBInternalObject implements INabuTranslation
{
    use TNabuTranslation;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_catalog_taxonomy An instance of CNabuCatalogTaxonomyLanguageBase or another object descending
     * from \nabu\data\CNabuDataObject which contains a field named nb_catalog_taxonomy_id, or a valid ID.
     * @param mixed $nb_language An instance of CNabuCatalogTaxonomyLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_language_id, or a valid ID.
     */
    public function __construct($nb_catalog_taxonomy = false, $nb_language = false)
    {
        if ($nb_catalog_taxonomy) {
            $this->transferMixedValue($nb_catalog_taxonomy, 'nb_catalog_taxonomy_id');
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
        return 'nb_catalog_taxonomy_lang';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_catalog_taxonomy_id') && $this->isValueNumeric('nb_language_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_catalog_taxonomy_lang '
                   . "where nb_catalog_taxonomy_id=%nb_catalog_taxonomy_id\$d "
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
        $nb_catalog_taxonomy_id = nb_getMixedValue(
                $translated,
                'nb_catalog_taxonomy_id',
                '\\nabu\\data\\catalog\\CNabuCatalogTaxonomy'
        );
        if (is_numeric($nb_catalog_taxonomy_id)) {
            $retval = CNabuLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select l.* '
                    . 'from nb_language l, nb_catalog_taxonomy t1, nb_catalog_taxonomy_lang t2 '
                   . 'where t1.nb_catalog_taxonomy_id=t2.nb_catalog_taxonomy_id '
                     . 'and t1.nb_catalog_taxonomy_id=%nb_catalog_taxonomy_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_catalog_taxonomy_id' => $nb_catalog_taxonomy_id
                    )
            );
        } else {
            $retval = new CNabuLanguageList();
        }
        
        return $retval;
    }

    /**
     * Query the storage to retrieve the full list of available translations for $translated and returns an associative
     * array in which each one is of class \nabu\data\catalog\CNabuCatalogTaxonomyLanguage.
     * @param object $translated Translated object to retrieve translations
     * @return false|null|array Returns an associative array indexed by the language Id, null if no languages are
     * available, or false if $translated cannot be identified.
     */
    public static function getTranslationsForTranslatedObject($translated)
    {
        $nb_catalog_taxonomy_id = nb_getMixedValue(
                $translated,
                'nb_catalog_taxonomy_id',
                '\\nabu\\data\\catalog\\CNabuCatalogTaxonomy'
        );
        if (is_numeric($nb_catalog_taxonomy_id)) {
            $retval = CNabuCatalogTaxonomyLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select t2.* '
                    . 'from nb_language l, nb_catalog_taxonomy t1, nb_catalog_taxonomy_lang t2 '
                   . 'where t1.nb_catalog_taxonomy_id=t2.nb_catalog_taxonomy_id '
                     . 'and t1.nb_catalog_taxonomy_id=%nb_catalog_taxonomy_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_catalog_taxonomy_id' => $nb_catalog_taxonomy_id
                    )
            );
            $retval->iterate(
                function ($key, $nb_translation) use($translated) {
                    $nb_translation->setTranslatedObject($translated);
                }
            );
        } else {
            $retval = new CNabuCatalogTaxonomyLanguageList();
        }
        
        return $retval;
    }

    /**
     * Get Catalog Taxonomy Id attribute value
     * @return int Returns the Catalog Taxonomy Id value
     */
    public function getCatalogTaxonomyId()
    {
        return $this->getValue('nb_catalog_taxonomy_id');
    }

    /**
     * Sets the Catalog Taxonomy Id attribute value
     * @param int $nb_catalog_taxonomy_id New value for attribute
     * @return CNabuCatalogTaxonomyLanguageBase Returns $this
     */
    public function setCatalogTaxonomyId($nb_catalog_taxonomy_id)
    {
        if ($nb_catalog_taxonomy_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_catalog_taxonomy_id")
            );
        }
        $this->setValue('nb_catalog_taxonomy_id', $nb_catalog_taxonomy_id);
        
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
     * @return CNabuCatalogTaxonomyLanguageBase Returns $this
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
     * Get Catalog Taxonomy Lang Title attribute value
     * @return null|string Returns the Catalog Taxonomy Lang Title value
     */
    public function getTitle()
    {
        return $this->getValue('nb_catalog_taxonomy_lang_title');
    }

    /**
     * Sets the Catalog Taxonomy Lang Title attribute value
     * @param null|string $title New value for attribute
     * @return CNabuCatalogTaxonomyLanguageBase Returns $this
     */
    public function setTitle($title)
    {
        $this->setValue('nb_catalog_taxonomy_lang_title', $title);
        
        return $this;
    }

    /**
     * Get Catalog Taxonomy Lang Internal Notes attribute value
     * @return null|string Returns the Catalog Taxonomy Lang Internal Notes value
     */
    public function getInternalNotes()
    {
        return $this->getValue('nb_catalog_taxonomy_lang_internal_notes');
    }

    /**
     * Sets the Catalog Taxonomy Lang Internal Notes attribute value
     * @param null|string $internal_notes New value for attribute
     * @return CNabuCatalogTaxonomyLanguageBase Returns $this
     */
    public function setInternalNotes($internal_notes)
    {
        $this->setValue('nb_catalog_taxonomy_lang_internal_notes', $internal_notes);
        
        return $this;
    }
}
