<?php
/* ===========================================================================
 * File generated automatically by Nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/03/08 12:48:57 UTC
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

namespace nabu\data\commerce\base;

use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\commerce\CNabuCommerceProductLanguage;
use \nabu\data\commerce\CNabuCommerceProductLanguageList;
use \nabu\data\lang\CNabuLanguage;
use \nabu\data\lang\CNabuLanguageList;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslation;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Commerce Product Language stored in the storage named nb_commerce_product_lang.
 * @author Rafael Gutiérrez Martínez <rgutierrez@wiscot.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\commerce\base
 */
abstract class CNabuCommerceProductLanguageBase extends CNabuDBInternalObject implements INabuTranslation
{
    use TNabuTranslation;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_commerce_product An instance of CNabuCommerceProductLanguageBase or another object descending
     * from \nabu\data\CNabuDataObject which contains a field named nb_commerce_product_id, or a valid ID.
     * @param mixed $nb_language An instance of CNabuCommerceProductLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_language_id, or a valid ID.
     */
    public function __construct($nb_commerce_product = false, $nb_language = false)
    {
        if ($nb_commerce_product) {
            $this->transferMixedValue($nb_commerce_product, 'nb_commerce_product_id');
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
        return 'nb_commerce_product_lang';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_commerce_product_id') && $this->isValueNumeric('nb_language_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_commerce_product_lang '
                   . "where nb_commerce_product_id=%nb_commerce_product_id\$d "
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
        $nb_commerce_product_id = nb_getMixedValue(
                $translated,
                'nb_commerce_product_id',
                '\\nabu\\data\\commerce\\CNabuCommerceProduct'
        );
        if (is_numeric($nb_commerce_product_id)) {
            $retval = CNabuLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select l.* '
                    . 'from nb_language l, nb_commerce_product t1, nb_commerce_product_lang t2 '
                   . 'where t1.nb_commerce_product_id=t2.nb_commerce_product_id '
                     . 'and t1.nb_commerce_product_id=%nb_commerce_product_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_commerce_product_id' => $nb_commerce_product_id
                    )
            );
        } else {
            $retval = new CNabuLanguageList();
        }
        
        return $retval;
    }

    /**
     * Query the storage to retrieve the full list of available translations for $translated and returns an associative
     * array in which each one is of class \nabu\data\commerce\CNabuCommerceProductLanguage.
     * @param object $translated Translated object to retrieve translations
     * @return false|null|array Returns an associative array indexed by the language Id, null if no languages are
     * available, or false if $translated cannot be identified.
     */
    public static function getTranslationsForTranslatedObject($translated)
    {
        $nb_commerce_product_id = nb_getMixedValue(
                $translated,
                'nb_commerce_product_id',
                '\\nabu\\data\\commerce\\CNabuCommerceProduct'
        );
        if (is_numeric($nb_commerce_product_id)) {
            $retval = CNabuCommerceProductLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select t2.* '
                    . 'from nb_language l, nb_commerce_product t1, nb_commerce_product_lang t2 '
                   . 'where t1.nb_commerce_product_id=t2.nb_commerce_product_id '
                     . 'and t1.nb_commerce_product_id=%nb_commerce_product_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_commerce_product_id' => $nb_commerce_product_id
                    )
            );
            $retval->iterate(
                function ($key, $nb_translation) use($translated) {
                    $nb_translation->setTranslatedObject($translated);
                }
            );
        } else {
            $retval = new CNabuCommerceProductLanguageList();
        }
        
        return $retval;
    }

    /**
     * Get Commerce Product Id attribute value
     * @return int Returns the Commerce Product Id value
     */
    public function getCommerceProductId()
    {
        return $this->getValue('nb_commerce_product_id');
    }

    /**
     * Sets the Commerce Product Id attribute value
     * @param int $nb_commerce_product_id New value for attribute
     * @return CNabuCommerceProductLanguageBase Returns $this
     */
    public function setCommerceProductId($nb_commerce_product_id)
    {
        if ($nb_commerce_product_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_commerce_product_id")
            );
        }
        $this->setValue('nb_commerce_product_id', $nb_commerce_product_id);
        
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
     * @return CNabuCommerceProductLanguageBase Returns $this
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
     * Get Commerce Product Lang Name attribute value
     * @return null|string Returns the Commerce Product Lang Name value
     */
    public function getName()
    {
        return $this->getValue('nb_commerce_product_lang_name');
    }

    /**
     * Sets the Commerce Product Lang Name attribute value
     * @param null|string $name New value for attribute
     * @return CNabuCommerceProductLanguageBase Returns $this
     */
    public function setName($name)
    {
        $this->setValue('nb_commerce_product_lang_name', $name);
        
        return $this;
    }

    /**
     * Get Commerce Product Lang Slug attribute value
     * @return null|string Returns the Commerce Product Lang Slug value
     */
    public function getSlug()
    {
        return $this->getValue('nb_commerce_product_lang_slug');
    }

    /**
     * Sets the Commerce Product Lang Slug attribute value
     * @param null|string $slug New value for attribute
     * @return CNabuCommerceProductLanguageBase Returns $this
     */
    public function setSlug($slug)
    {
        $this->setValue('nb_commerce_product_lang_slug', $slug);
        
        return $this;
    }

    /**
     * Get Commerce Product Lang Description attribute value
     * @return null|string Returns the Commerce Product Lang Description value
     */
    public function getDescription()
    {
        return $this->getValue('nb_commerce_product_lang_description');
    }

    /**
     * Sets the Commerce Product Lang Description attribute value
     * @param null|string $description New value for attribute
     * @return CNabuCommerceProductLanguageBase Returns $this
     */
    public function setDescription($description)
    {
        $this->setValue('nb_commerce_product_lang_description', $description);
        
        return $this;
    }
}
