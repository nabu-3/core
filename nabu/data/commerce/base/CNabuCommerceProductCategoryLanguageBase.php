<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/10/06 08:00:19 UTC
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

namespace nabu\data\commerce\base;

use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\CNabuDataObject;
use \nabu\data\commerce\CNabuCommerceProductCategoryLanguage;
use \nabu\data\commerce\CNabuCommerceProductCategoryLanguageList;
use \nabu\data\lang\CNabuLanguage;
use \nabu\data\lang\CNabuLanguageList;
use \nabu\data\lang\interfaces\INabuTranslated;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslation;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Commerce Product Category Language stored in the storage named
 * nb_commerce_product_category_lang.
 * @version 3.0.12 Surface
 * @package \nabu\data\commerce\base
 */
abstract class CNabuCommerceProductCategoryLanguageBase extends CNabuDBInternalObject implements INabuTranslation
{
    use TNabuTranslation;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_commerce_product_category An instance of CNabuCommerceProductCategoryLanguageBase or another
     * object descending from \nabu\data\CNabuDataObject which contains a field named nb_commerce_product_category_id,
     * or a valid ID.
     * @param mixed $nb_language An instance of CNabuCommerceProductCategoryLanguageBase or another object descending
     * from \nabu\data\CNabuDataObject which contains a field named nb_language_id, or a valid ID.
     */
    public function __construct($nb_commerce_product_category = false, $nb_language = false)
    {
        if ($nb_commerce_product_category) {
            $this->transferMixedValue($nb_commerce_product_category, 'nb_commerce_product_category_id');
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
        return 'nb_commerce_product_category_lang';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_commerce_product_category_id') && $this->isValueNumeric('nb_language_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_commerce_product_category_lang '
                   . "where nb_commerce_product_category_id=%nb_commerce_product_category_id\$d "
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
        $nb_commerce_product_category_id = nb_getMixedValue($translated, 'nb_commerce_product_category_id');
        if (is_numeric($nb_commerce_product_category_id)) {
            $retval = CNabuLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select l.* '
                    . 'from nb_language l, nb_commerce_product_category t1, nb_commerce_product_category_lang t2 '
                   . 'where t1.nb_commerce_product_category_id=t2.nb_commerce_product_category_id '
                     . 'and t1.nb_commerce_product_category_id=%nb_commerce_product_category_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_commerce_product_category_id' => $nb_commerce_product_category_id
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
     * @return CNabuCommerceProductCategoryLanguageList Returns a list of translations. If no translations are
     * available, the list is empty.
     */
    public static function getTranslationsForTranslatedObject($translated)
    {
        $nb_commerce_product_category_id = nb_getMixedValue($translated, 'nb_commerce_product_category_id');
        if (is_numeric($nb_commerce_product_category_id)) {
            $retval = CNabuCommerceProductCategoryLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select t2.* '
                    . 'from nb_language l, nb_commerce_product_category t1, nb_commerce_product_category_lang t2 '
                   . 'where t1.nb_commerce_product_category_id=t2.nb_commerce_product_category_id '
                     . 'and t1.nb_commerce_product_category_id=%nb_commerce_product_category_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_commerce_product_category_id' => $nb_commerce_product_category_id
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
            $retval = new CNabuCommerceProductCategoryLanguageList();
        }
        
        return $retval;
    }

    /**
     * Get Commerce Product Category Id attribute value
     * @return int Returns the Commerce Product Category Id value
     */
    public function getCommerceProductCategoryId() : int
    {
        return $this->getValue('nb_commerce_product_category_id');
    }

    /**
     * Sets the Commerce Product Category Id attribute value.
     * @param int $nb_commerce_product_category_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCommerceProductCategoryId(int $nb_commerce_product_category_id) : CNabuDataObject
    {
        if ($nb_commerce_product_category_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_commerce_product_category_id")
            );
        }
        $this->setValue('nb_commerce_product_category_id', $nb_commerce_product_category_id);
        
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
     * Get Commerce Product Category Lang Title attribute value
     * @return null|string Returns the Commerce Product Category Lang Title value
     */
    public function getTitle()
    {
        return $this->getValue('nb_commerce_product_category_lang_title');
    }

    /**
     * Sets the Commerce Product Category Lang Title attribute value.
     * @param string|null $title New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setTitle(string $title = null) : CNabuDataObject
    {
        $this->setValue('nb_commerce_product_category_lang_title', $title);
        
        return $this;
    }

    /**
     * Get Commerce Product Category Lang Slug attribute value
     * @return null|string Returns the Commerce Product Category Lang Slug value
     */
    public function getSlug()
    {
        return $this->getValue('nb_commerce_product_category_lang_slug');
    }

    /**
     * Sets the Commerce Product Category Lang Slug attribute value.
     * @param string|null $slug New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setSlug(string $slug = null) : CNabuDataObject
    {
        $this->setValue('nb_commerce_product_category_lang_slug', $slug);
        
        return $this;
    }

    /**
     * Get Commerce Product Category Lang Attributes attribute value
     * @return null|array Returns the Commerce Product Category Lang Attributes value
     */
    public function getAttributes()
    {
        return $this->getValueJSONDecoded('nb_commerce_product_category_lang_attributes');
    }

    /**
     * Sets the Commerce Product Category Lang Attributes attribute value.
     * @param string|array|null $attributes New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setAttributes($attributes = null) : CNabuDataObject
    {
        $this->setValueJSONEncoded('nb_commerce_product_category_lang_attributes', $attributes);
        
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
