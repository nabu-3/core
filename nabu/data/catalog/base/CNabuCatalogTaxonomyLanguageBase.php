<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2019/10/10 11:56:15 UTC
 * ===========================================================================
 * Copyright 2009-2011 Rafael Gutierrez Martinez
 * Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 * Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 * Copyright 2017-2019 nabu-3 Group
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
use \nabu\data\CNabuDataObject;
use \nabu\data\lang\CNabuLanguage;
use \nabu\data\lang\CNabuLanguageList;
use \nabu\data\lang\interfaces\INabuTranslated;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslation;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Catalog Taxonomy Language stored in the storage named nb_catalog_taxonomy_lang.
 * @author Rafael Gutiérrez <rgutierrez@nabu-3.com>
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
     * translations) for $translated and returns a list with all languages.
     * @param mixed $translated Translated object or Id to retrieve languages.
     * @return CNabuLanguageList Returns a list of languages. If no languages are available, the list is empty.
     */
    public static function getLanguagesForTranslatedObject($translated)
    {
        $nb_catalog_taxonomy_id = nb_getMixedValue($translated, 'nb_catalog_taxonomy_id');
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
     * Query the storage to retrieve the full list of available translations for $translated and returns a list with
     * all translations.
     * @param mixed $translated Translated object or Id to retrieve translations.
     * @return CNabuCatalogTaxonomyLanguageList Returns a list of translations. If no translations are available, the
     * list is empty.
     */
    public static function getTranslationsForTranslatedObject($translated)
    {
        $nb_catalog_taxonomy_id = nb_getMixedValue($translated, 'nb_catalog_taxonomy_id');
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
            if ($translated instanceof INabuTranslated) {
                $retval->iterate(
                    function ($key, $nb_translation) use($translated) {
                        $nb_translation->setTranslatedObject($translated);
                        return true;
                    }
                );
            }
        } else {
            $retval = new CNabuCatalogTaxonomyLanguageList();
        }
        
        return $retval;
    }

    /**
     * Get Catalog Taxonomy Id attribute value
     * @return int Returns the Catalog Taxonomy Id value
     */
    public function getCatalogTaxonomyId() : int
    {
        return $this->getValue('nb_catalog_taxonomy_id');
    }

    /**
     * Sets the Catalog Taxonomy Id attribute value.
     * @param int $nb_catalog_taxonomy_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCatalogTaxonomyId(int $nb_catalog_taxonomy_id) : CNabuDataObject
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
     * Get Catalog Taxonomy Lang Slug attribute value
     * @return null|string Returns the Catalog Taxonomy Lang Slug value
     */
    public function getSlug()
    {
        return $this->getValue('nb_catalog_taxonomy_lang_slug');
    }

    /**
     * Sets the Catalog Taxonomy Lang Slug attribute value.
     * @param string|null $slug New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setSlug(string $slug = null) : CNabuDataObject
    {
        $this->setValue('nb_catalog_taxonomy_lang_slug', $slug);
        
        return $this;
    }

    /**
     * Get Catalog Taxonomy Lang Image attribute value
     * @return null|string Returns the Catalog Taxonomy Lang Image value
     */
    public function getImage()
    {
        return $this->getValue('nb_catalog_taxonomy_lang_image');
    }

    /**
     * Sets the Catalog Taxonomy Lang Image attribute value.
     * @param string|null $image New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setImage(string $image = null) : CNabuDataObject
    {
        $this->setValue('nb_catalog_taxonomy_lang_image', $image);
        
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
     * Sets the Catalog Taxonomy Lang Title attribute value.
     * @param string|null $title New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setTitle(string $title = null) : CNabuDataObject
    {
        $this->setValue('nb_catalog_taxonomy_lang_title', $title);
        
        return $this;
    }

    /**
     * Get Catalog Taxonomy Lang Subtitle attribute value
     * @return null|string Returns the Catalog Taxonomy Lang Subtitle value
     */
    public function getSubtitle()
    {
        return $this->getValue('nb_catalog_taxonomy_lang_subtitle');
    }

    /**
     * Sets the Catalog Taxonomy Lang Subtitle attribute value.
     * @param string|null $subtitle New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setSubtitle(string $subtitle = null) : CNabuDataObject
    {
        $this->setValue('nb_catalog_taxonomy_lang_subtitle', $subtitle);
        
        return $this;
    }

    /**
     * Get Catalog Taxonomy Lang Anchor Text attribute value
     * @return null|string Returns the Catalog Taxonomy Lang Anchor Text value
     */
    public function getAnchorText()
    {
        return $this->getValue('nb_catalog_taxonomy_lang_anchor_text');
    }

    /**
     * Sets the Catalog Taxonomy Lang Anchor Text attribute value.
     * @param string|null $anchor_text New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setAnchorText(string $anchor_text = null) : CNabuDataObject
    {
        $this->setValue('nb_catalog_taxonomy_lang_anchor_text', $anchor_text);
        
        return $this;
    }

    /**
     * Get Catalog Taxonomy Lang Opening attribute value
     * @return null|string Returns the Catalog Taxonomy Lang Opening value
     */
    public function getOpening()
    {
        return $this->getValue('nb_catalog_taxonomy_lang_opening');
    }

    /**
     * Sets the Catalog Taxonomy Lang Opening attribute value.
     * @param string|null $opening New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setOpening(string $opening = null) : CNabuDataObject
    {
        $this->setValue('nb_catalog_taxonomy_lang_opening', $opening);
        
        return $this;
    }

    /**
     * Get Catalog Taxonomy Lang Content attribute value
     * @return null|string Returns the Catalog Taxonomy Lang Content value
     */
    public function getContent()
    {
        return $this->getValue('nb_catalog_taxonomy_lang_content');
    }

    /**
     * Sets the Catalog Taxonomy Lang Content attribute value.
     * @param string|null $content New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setContent(string $content = null) : CNabuDataObject
    {
        $this->setValue('nb_catalog_taxonomy_lang_content', $content);
        
        return $this;
    }

    /**
     * Get Catalog Taxonomy Lang Footer attribute value
     * @return null|string Returns the Catalog Taxonomy Lang Footer value
     */
    public function getFooter()
    {
        return $this->getValue('nb_catalog_taxonomy_lang_footer');
    }

    /**
     * Sets the Catalog Taxonomy Lang Footer attribute value.
     * @param string|null $footer New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setFooter(string $footer = null) : CNabuDataObject
    {
        $this->setValue('nb_catalog_taxonomy_lang_footer', $footer);
        
        return $this;
    }

    /**
     * Get Catalog Taxonomy Lang Aside attribute value
     * @return null|string Returns the Catalog Taxonomy Lang Aside value
     */
    public function getAside()
    {
        return $this->getValue('nb_catalog_taxonomy_lang_aside');
    }

    /**
     * Sets the Catalog Taxonomy Lang Aside attribute value.
     * @param string|null $aside New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setAside(string $aside = null) : CNabuDataObject
    {
        $this->setValue('nb_catalog_taxonomy_lang_aside', $aside);
        
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
     * Sets the Catalog Taxonomy Lang Internal Notes attribute value.
     * @param string|null $internal_notes New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setInternalNotes(string $internal_notes = null) : CNabuDataObject
    {
        $this->setValue('nb_catalog_taxonomy_lang_internal_notes', $internal_notes);
        
        return $this;
    }

    /**
     * Get Catalog Taxonomy Lang Attributes attribute value
     * @return null|array Returns the Catalog Taxonomy Lang Attributes value
     */
    public function getAttributes()
    {
        return $this->getValueJSONDecoded('nb_catalog_taxonomy_lang_attributes');
    }

    /**
     * Sets the Catalog Taxonomy Lang Attributes attribute value.
     * @param string|array|null $attributes New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setAttributes($attributes = null) : CNabuDataObject
    {
        $this->setValueJSONEncoded('nb_catalog_taxonomy_lang_attributes', $attributes);
        
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
        
        $trdata['attributes'] = $this->getAttributes();
        
        return $trdata;
    }
}
