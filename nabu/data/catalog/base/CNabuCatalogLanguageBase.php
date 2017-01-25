<?php
/* ===========================================================================
 * File generated automatically by Nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/01/10 17:58:31 UTC
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
use \nabu\data\catalog\CNabuCatalogLanguage;
use \nabu\data\catalog\CNabuCatalogLanguageList;
use \nabu\data\catalog\traits\TNabuCatalogChild;
use \nabu\data\lang\CNabuLanguage;
use \nabu\data\lang\CNabuLanguageList;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslation;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Catalog Language stored in the storage named nb_catalog_lang.
 * @author Rafael Gutiérrez Martínez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\catalog\base
 */
abstract class CNabuCatalogLanguageBase extends CNabuDBInternalObject implements INabuTranslation
{
    use TNabuCatalogChild;
    use TNabuTranslation;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_catalog An instance of CNabuCatalogLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_catalog_id, or a valid ID.
     * @param mixed $nb_language An instance of CNabuCatalogLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_language_id, or a valid ID.
     */
    public function __construct($nb_catalog = false, $nb_language = false)
    {
        if ($nb_catalog) {
            $this->transferMixedValue($nb_catalog, 'nb_catalog_id');
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
        return 'nb_catalog_lang';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_catalog_id') && $this->isValueNumeric('nb_language_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_catalog_lang '
                   . "where nb_catalog_id=%nb_catalog_id\$d "
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
        $nb_catalog_id = nb_getMixedValue(
                $translated,
                'nb_catalog_id',
                '\\nabu\\data\\catalog\\CNabuCatalog'
        );
        if (is_numeric($nb_catalog_id)) {
            $retval = CNabuLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select l.* '
                    . 'from nb_language l, nb_catalog t1, nb_catalog_lang t2 '
                   . 'where t1.nb_catalog_id=t2.nb_catalog_id '
                     . 'and t1.nb_catalog_id=%nb_catalog_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_catalog_id' => $nb_catalog_id
                    )
            );
        } else {
            $retval = new CNabuLanguageList();
        }
        
        return $retval;
    }

    /**
     * Query the storage to retrieve the full list of available translations for $translated and returns an associative
     * array in which each one is of class \nabu\data\catalog\CNabuCatalogLanguage.
     * @param object $translated Translated object to retrieve translations
     * @return false|null|array Returns an associative array indexed by the language Id, null if no languages are
     * available, or false if $translated cannot be identified.
     */
    public static function getTranslationsForTranslatedObject($translated)
    {
        $nb_catalog_id = nb_getMixedValue(
                $translated,
                'nb_catalog_id',
                '\\nabu\\data\\catalog\\CNabuCatalog'
        );
        if (is_numeric($nb_catalog_id)) {
            $retval = CNabuCatalogLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select t2.* '
                    . 'from nb_language l, nb_catalog t1, nb_catalog_lang t2 '
                   . 'where t1.nb_catalog_id=t2.nb_catalog_id '
                     . 'and t1.nb_catalog_id=%nb_catalog_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_catalog_id' => $nb_catalog_id
                    )
            );
            $retval->iterate(
                function ($key, $nb_translation) use($translated) {
                    $nb_translation->setTranslatedObject($translated);
                }
            );
        } else {
            $retval = new CNabuCatalogLanguageList();
        }
        
        return $retval;
    }

    /**
     * Get Catalog Id attribute value
     * @return int Returns the Catalog Id value
     */
    public function getCatalogId()
    {
        return $this->getValue('nb_catalog_id');
    }

    /**
     * Sets the Catalog Id attribute value
     * @param int $nb_catalog_id New value for attribute
     * @return CNabuCatalogLanguageBase Returns $this
     */
    public function setCatalogId($nb_catalog_id)
    {
        if ($nb_catalog_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_catalog_id")
            );
        }
        $this->setValue('nb_catalog_id', $nb_catalog_id);
        
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
     * @return CNabuCatalogLanguageBase Returns $this
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
     * Get Catalog Lang Status attribute value
     * @return string Returns the Catalog Lang Status value
     */
    public function getStatus()
    {
        return $this->getValue('nb_catalog_lang_status');
    }

    /**
     * Sets the Catalog Lang Status attribute value
     * @param string $status New value for attribute
     * @return CNabuCatalogLanguageBase Returns $this
     */
    public function setStatus($status)
    {
        if ($status === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$status")
            );
        }
        $this->setValue('nb_catalog_lang_status', $status);
        
        return $this;
    }

    /**
     * Get Catalog Lang Slug attribute value
     * @return null|string Returns the Catalog Lang Slug value
     */
    public function getSlug()
    {
        return $this->getValue('nb_catalog_lang_slug');
    }

    /**
     * Sets the Catalog Lang Slug attribute value
     * @param null|string $slug New value for attribute
     * @return CNabuCatalogLanguageBase Returns $this
     */
    public function setSlug($slug)
    {
        $this->setValue('nb_catalog_lang_slug', $slug);
        
        return $this;
    }

    /**
     * Get Catalog Lang Image attribute value
     * @return null|string Returns the Catalog Lang Image value
     */
    public function getImage()
    {
        return $this->getValue('nb_catalog_lang_image');
    }

    /**
     * Sets the Catalog Lang Image attribute value
     * @param null|string $image New value for attribute
     * @return CNabuCatalogLanguageBase Returns $this
     */
    public function setImage($image)
    {
        $this->setValue('nb_catalog_lang_image', $image);
        
        return $this;
    }

    /**
     * Get Catalog Lang Title attribute value
     * @return null|string Returns the Catalog Lang Title value
     */
    public function getTitle()
    {
        return $this->getValue('nb_catalog_lang_title');
    }

    /**
     * Sets the Catalog Lang Title attribute value
     * @param null|string $title New value for attribute
     * @return CNabuCatalogLanguageBase Returns $this
     */
    public function setTitle($title)
    {
        $this->setValue('nb_catalog_lang_title', $title);
        
        return $this;
    }

    /**
     * Get Catalog Lang Subtitle attribute value
     * @return null|string Returns the Catalog Lang Subtitle value
     */
    public function getSubtitle()
    {
        return $this->getValue('nb_catalog_lang_subtitle');
    }

    /**
     * Sets the Catalog Lang Subtitle attribute value
     * @param null|string $subtitle New value for attribute
     * @return CNabuCatalogLanguageBase Returns $this
     */
    public function setSubtitle($subtitle)
    {
        $this->setValue('nb_catalog_lang_subtitle', $subtitle);
        
        return $this;
    }

    /**
     * Get Catalog Lang Anchor Text attribute value
     * @return null|string Returns the Catalog Lang Anchor Text value
     */
    public function getAnchorText()
    {
        return $this->getValue('nb_catalog_lang_anchor_text');
    }

    /**
     * Sets the Catalog Lang Anchor Text attribute value
     * @param null|string $anchor_text New value for attribute
     * @return CNabuCatalogLanguageBase Returns $this
     */
    public function setAnchorText($anchor_text)
    {
        $this->setValue('nb_catalog_lang_anchor_text', $anchor_text);
        
        return $this;
    }

    /**
     * Get Catalog Lang Opening attribute value
     * @return null|string Returns the Catalog Lang Opening value
     */
    public function getOpening()
    {
        return $this->getValue('nb_catalog_lang_opening');
    }

    /**
     * Sets the Catalog Lang Opening attribute value
     * @param null|string $opening New value for attribute
     * @return CNabuCatalogLanguageBase Returns $this
     */
    public function setOpening($opening)
    {
        $this->setValue('nb_catalog_lang_opening', $opening);
        
        return $this;
    }

    /**
     * Get Catalog Lang Content attribute value
     * @return null|string Returns the Catalog Lang Content value
     */
    public function getContent()
    {
        return $this->getValue('nb_catalog_lang_content');
    }

    /**
     * Sets the Catalog Lang Content attribute value
     * @param null|string $content New value for attribute
     * @return CNabuCatalogLanguageBase Returns $this
     */
    public function setContent($content)
    {
        $this->setValue('nb_catalog_lang_content', $content);
        
        return $this;
    }

    /**
     * Get Catalog Lang Footer attribute value
     * @return null|string Returns the Catalog Lang Footer value
     */
    public function getFooter()
    {
        return $this->getValue('nb_catalog_lang_footer');
    }

    /**
     * Sets the Catalog Lang Footer attribute value
     * @param null|string $footer New value for attribute
     * @return CNabuCatalogLanguageBase Returns $this
     */
    public function setFooter($footer)
    {
        $this->setValue('nb_catalog_lang_footer', $footer);
        
        return $this;
    }

    /**
     * Get Catalog Lang Aside attribute value
     * @return null|string Returns the Catalog Lang Aside value
     */
    public function getAside()
    {
        return $this->getValue('nb_catalog_lang_aside');
    }

    /**
     * Sets the Catalog Lang Aside attribute value
     * @param null|string $aside New value for attribute
     * @return CNabuCatalogLanguageBase Returns $this
     */
    public function setAside($aside)
    {
        $this->setValue('nb_catalog_lang_aside', $aside);
        
        return $this;
    }

    /**
     * Get Catalog Lang Attributes attribute value
     * @return null|array Returns the Catalog Lang Attributes value
     */
    public function getAttributes()
    {
        return $this->getValueJSONDecoded('nb_catalog_lang_attributes');
    }

    /**
     * Sets the Catalog Lang Attributes attribute value
     * @param null|string|array $attributes New value for attribute
     * @return CNabuCatalogLanguageBase Returns $this
     */
    public function setAttributes($attributes)
    {
        $this->setValueJSONEncoded('nb_catalog_lang_attributes', $attributes);
        
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