<?php
/* ===========================================================================
 * File generated automatically by Nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/01/10 17:58:36 UTC
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
use \nabu\data\catalog\CNabuCatalogItemLanguage;
use \nabu\data\catalog\CNabuCatalogItemLanguageList;
use \nabu\data\lang\CNabuLanguage;
use \nabu\data\lang\CNabuLanguageList;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslation;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Catalog Item Language stored in the storage named nb_catalog_item_lang.
 * @author Rafael Gutiérrez Martínez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\catalog\base
 */
abstract class CNabuCatalogItemLanguageBase extends CNabuDBInternalObject implements INabuTranslation
{
    use TNabuTranslation;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_catalog_item An instance of CNabuCatalogItemLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_catalog_item_id, or a valid ID.
     * @param mixed $nb_language An instance of CNabuCatalogItemLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_language_id, or a valid ID.
     */
    public function __construct($nb_catalog_item = false, $nb_language = false)
    {
        if ($nb_catalog_item) {
            $this->transferMixedValue($nb_catalog_item, 'nb_catalog_item_id');
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
        return 'nb_catalog_item_lang';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_catalog_item_id') && $this->isValueNumeric('nb_language_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_catalog_item_lang '
                   . "where nb_catalog_item_id=%nb_catalog_item_id\$d "
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
        $nb_catalog_item_id = nb_getMixedValue(
                $translated,
                'nb_catalog_item_id',
                '\\nabu\\data\\catalog\\CNabuCatalogItem'
        );
        if (is_numeric($nb_catalog_item_id)) {
            $retval = CNabuLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select l.* '
                    . 'from nb_language l, nb_catalog_item t1, nb_catalog_item_lang t2 '
                   . 'where t1.nb_catalog_item_id=t2.nb_catalog_item_id '
                     . 'and t1.nb_catalog_item_id=%nb_catalog_item_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_catalog_item_id' => $nb_catalog_item_id
                    )
            );
        } else {
            $retval = new CNabuLanguageList();
        }
        
        return $retval;
    }

    /**
     * Query the storage to retrieve the full list of available translations for $translated and returns an associative
     * array in which each one is of class \nabu\data\catalog\CNabuCatalogItemLanguage.
     * @param object $translated Translated object to retrieve translations
     * @return false|null|array Returns an associative array indexed by the language Id, null if no languages are
     * available, or false if $translated cannot be identified.
     */
    public static function getTranslationsForTranslatedObject($translated)
    {
        $nb_catalog_item_id = nb_getMixedValue(
                $translated,
                'nb_catalog_item_id',
                '\\nabu\\data\\catalog\\CNabuCatalogItem'
        );
        if (is_numeric($nb_catalog_item_id)) {
            $retval = CNabuCatalogItemLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select t2.* '
                    . 'from nb_language l, nb_catalog_item t1, nb_catalog_item_lang t2 '
                   . 'where t1.nb_catalog_item_id=t2.nb_catalog_item_id '
                     . 'and t1.nb_catalog_item_id=%nb_catalog_item_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_catalog_item_id' => $nb_catalog_item_id
                    )
            );
            $retval->iterate(
                function ($key, $nb_translation) use($translated) {
                    $nb_translation->setTranslatedObject($translated);
                }
            );
        } else {
            $retval = new CNabuCatalogItemLanguageList();
        }
        
        return $retval;
    }

    /**
     * Get Catalog Item Id attribute value
     * @return int Returns the Catalog Item Id value
     */
    public function getCatalogItemId()
    {
        return $this->getValue('nb_catalog_item_id');
    }

    /**
     * Sets the Catalog Item Id attribute value
     * @param int $nb_catalog_item_id New value for attribute
     * @return CNabuCatalogItemLanguageBase Returns $this
     */
    public function setCatalogItemId($nb_catalog_item_id)
    {
        if ($nb_catalog_item_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_catalog_item_id")
            );
        }
        $this->setValue('nb_catalog_item_id', $nb_catalog_item_id);
        
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
     * @return CNabuCatalogItemLanguageBase Returns $this
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
     * Get Catalog Item Lang Slug attribute value
     * @return null|string Returns the Catalog Item Lang Slug value
     */
    public function getSlug()
    {
        return $this->getValue('nb_catalog_item_lang_slug');
    }

    /**
     * Sets the Catalog Item Lang Slug attribute value
     * @param null|string $slug New value for attribute
     * @return CNabuCatalogItemLanguageBase Returns $this
     */
    public function setSlug($slug)
    {
        $this->setValue('nb_catalog_item_lang_slug', $slug);
        
        return $this;
    }

    /**
     * Get Catalog Item Lang SKU attribute value
     * @return null|string Returns the Catalog Item Lang SKU value
     */
    public function getSKU()
    {
        return $this->getValue('nb_catalog_item_lang_sku');
    }

    /**
     * Sets the Catalog Item Lang SKU attribute value
     * @param null|string $sku New value for attribute
     * @return CNabuCatalogItemLanguageBase Returns $this
     */
    public function setSKU($sku)
    {
        $this->setValue('nb_catalog_item_lang_sku', $sku);
        
        return $this;
    }

    /**
     * Get Catalog Item Lang Image attribute value
     * @return null|string Returns the Catalog Item Lang Image value
     */
    public function getImage()
    {
        return $this->getValue('nb_catalog_item_lang_image');
    }

    /**
     * Sets the Catalog Item Lang Image attribute value
     * @param null|string $image New value for attribute
     * @return CNabuCatalogItemLanguageBase Returns $this
     */
    public function setImage($image)
    {
        $this->setValue('nb_catalog_item_lang_image', $image);
        
        return $this;
    }

    /**
     * Get Catalog Item Lang Title attribute value
     * @return null|string Returns the Catalog Item Lang Title value
     */
    public function getTitle()
    {
        return $this->getValue('nb_catalog_item_lang_title');
    }

    /**
     * Sets the Catalog Item Lang Title attribute value
     * @param null|string $title New value for attribute
     * @return CNabuCatalogItemLanguageBase Returns $this
     */
    public function setTitle($title)
    {
        $this->setValue('nb_catalog_item_lang_title', $title);
        
        return $this;
    }

    /**
     * Get Catalog Item Lang Subtitle attribute value
     * @return null|string Returns the Catalog Item Lang Subtitle value
     */
    public function getSubtitle()
    {
        return $this->getValue('nb_catalog_item_lang_subtitle');
    }

    /**
     * Sets the Catalog Item Lang Subtitle attribute value
     * @param null|string $subtitle New value for attribute
     * @return CNabuCatalogItemLanguageBase Returns $this
     */
    public function setSubtitle($subtitle)
    {
        $this->setValue('nb_catalog_item_lang_subtitle', $subtitle);
        
        return $this;
    }

    /**
     * Get Catalog Item Lang Opening attribute value
     * @return null|string Returns the Catalog Item Lang Opening value
     */
    public function getOpening()
    {
        return $this->getValue('nb_catalog_item_lang_opening');
    }

    /**
     * Sets the Catalog Item Lang Opening attribute value
     * @param null|string $opening New value for attribute
     * @return CNabuCatalogItemLanguageBase Returns $this
     */
    public function setOpening($opening)
    {
        $this->setValue('nb_catalog_item_lang_opening', $opening);
        
        return $this;
    }

    /**
     * Get Catalog Item Lang Content attribute value
     * @return null|string Returns the Catalog Item Lang Content value
     */
    public function getContent()
    {
        return $this->getValue('nb_catalog_item_lang_content');
    }

    /**
     * Sets the Catalog Item Lang Content attribute value
     * @param null|string $content New value for attribute
     * @return CNabuCatalogItemLanguageBase Returns $this
     */
    public function setContent($content)
    {
        $this->setValue('nb_catalog_item_lang_content', $content);
        
        return $this;
    }

    /**
     * Get Catalog Item Lang Footer attribute value
     * @return null|string Returns the Catalog Item Lang Footer value
     */
    public function getFooter()
    {
        return $this->getValue('nb_catalog_item_lang_footer');
    }

    /**
     * Sets the Catalog Item Lang Footer attribute value
     * @param null|string $footer New value for attribute
     * @return CNabuCatalogItemLanguageBase Returns $this
     */
    public function setFooter($footer)
    {
        $this->setValue('nb_catalog_item_lang_footer', $footer);
        
        return $this;
    }

    /**
     * Get Catalog Item Lang Aside attribute value
     * @return null|string Returns the Catalog Item Lang Aside value
     */
    public function getAside()
    {
        return $this->getValue('nb_catalog_item_lang_aside');
    }

    /**
     * Sets the Catalog Item Lang Aside attribute value
     * @param null|string $aside New value for attribute
     * @return CNabuCatalogItemLanguageBase Returns $this
     */
    public function setAside($aside)
    {
        $this->setValue('nb_catalog_item_lang_aside', $aside);
        
        return $this;
    }

    /**
     * Get Catalog Item Lang Attributes attribute value
     * @return null|array Returns the Catalog Item Lang Attributes value
     */
    public function getAttributes()
    {
        return $this->getValueJSONDecoded('nb_catalog_item_lang_attributes');
    }

    /**
     * Sets the Catalog Item Lang Attributes attribute value
     * @param null|string|array $attributes New value for attribute
     * @return CNabuCatalogItemLanguageBase Returns $this
     */
    public function setAttributes($attributes)
    {
        $this->setValueJSONEncoded('nb_catalog_item_lang_attributes', $attributes);
        
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