<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/06/16 12:45:07 UTC
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

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\CNabuDataObject;
use \nabu\data\commerce\builtin\CNabuBuiltInCommerceProductLanguage;
use \nabu\data\commerce\CNabuCommerce;
use \nabu\data\commerce\CNabuCommerceProductLanguage;
use \nabu\data\commerce\CNabuCommerceProductLanguageList;
use \nabu\data\commerce\CNabuCommerceProductList;
use \nabu\data\commerce\traits\TNabuCommerceChild;
use \nabu\data\lang\interfaces\INabuTranslated;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslated;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Commerce Product stored in the storage named nb_commerce_product.
 * @version 3.0.12 Surface
 * @package \nabu\data\commerce\base
 */
abstract class CNabuCommerceProductBase extends CNabuDBInternalObject implements INabuTranslated
{
    use TNabuCommerceChild;
    use TNabuTranslated;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_commerce_product An instance of CNabuCommerceProductBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_commerce_product_id, or a valid ID.
     */
    public function __construct($nb_commerce_product = false)
    {
        if ($nb_commerce_product) {
            $this->transferMixedValue($nb_commerce_product, 'nb_commerce_product_id');
        }
        
        parent::__construct();
        $this->__translatedConstruct();
        $this->translations_list = new CNabuCommerceProductLanguageList();
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
        return 'nb_commerce_product';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_commerce_product_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_commerce_product '
                   . "where nb_commerce_product_id=%nb_commerce_product_id\$d "
              )
            : null;
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_commerce_product_id' is the index, and
     * each value is an instance of class CNabuCommerceProductBase.
     * @param CNabuCommerce $nb_commerce The CNabuCommerce instance of the Commerce that owns the Commerce Product List.
     * @return mixed Returns and array with all items.
     */
    public static function getAllCommerceProducts(CNabuCommerce $nb_commerce)
    {
        $nb_commerce_id = nb_getMixedValue($nb_commerce, 'nb_commerce_id');
        if (is_numeric($nb_commerce_id)) {
            $retval = forward_static_call(
            array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_commerce_product_id',
                'select * '
                . 'from nb_commerce_product '
               . 'where nb_commerce_id=%commerce_id$d',
                array(
                    'commerce_id' => $nb_commerce_id
                ),
                $nb_commerce
            );
        } else {
            $retval = new CNabuCommerceProductList();
        }
        
        return $retval;
    }

    /**
     * Gets a filtered list of Commerce Product instances represented as an array. Params allows the capability of
     * select a subset of fields, order by concrete fields, or truncate the list by a number of rows starting in an
     * offset.
     * @throws \nabu\core\exceptions\ENabuCoreException Raises an exception if $fields or $order have invalid values.
     * @param string $q Query string to filter results using a context index.
     * @param string|array $fields List of fields to put in the results.
     * @param string|array $order List of fields to order the results. Each field can be suffixed with "ASC" or "DESC"
     * to determine the short order
     * @param int $offset Offset of first row in the results having the first row at offset 0.
     * @param int $num_items Number of continue rows to get as maximum in the results.
     * @return array Returns an array with all rows found using the criteria.
     */
    public static function getFilteredCommerceProductList($q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $nb_commerce_id = nb_getMixedValue($nb_customer, NABU_COMMERCE_FIELD_ID);
        if (is_numeric($nb_commerce_id)) {
            $fields_part = nb_prefixFieldList(CNabuCommerceProductBase::getStorageName(), $fields, false, true, '`');
            $order_part = nb_prefixFieldList(CNabuCommerceProductBase::getStorageName(), $fields, false, false, '`');
        
            if ($num_items !== 0) {
                $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
            } else {
                $limit_part = false;
            }
        
            $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
                "select " . ($fields_part ? $fields_part . ' ' : '* ')
                . 'from nb_commerce_product '
               . 'where ' . NABU_COMMERCE_FIELD_ID . '=%commerce_id$d '
                . ($order_part ? "order by $order_part " : '')
                . ($limit_part ? "limit $limit_part" : ''),
                array(
                    'commerce_id' => $nb_commerce_id
                )
            );
        } else {
            $nb_item_list = null;
        }
        
        return $nb_item_list;
    }

    /**
     * Check if the instance passed as parameter $translation is a valid child translation for this object
     * @param INabuTranslation $translation Translation instance to check
     * @return bool Return true if a valid object is passed as instance or false elsewhere
     */
    protected function checkForValidTranslationInstance($translation)
    {
        return ($translation !== null &&
                $translation instanceof CNabuCommerceProductLanguage &&
                $translation->matchValue($this, 'nb_commerce_product_id')
        );
    }

    /**
     * Get all language instances corresponding to available translations.
     * @param bool $force If true force to reload languages list from storage.
     * @return null|array Return an array of \nabu\data\lang\CNabuLanguage instances if they have translations or null
     * if not.
     */
    public function getLanguages($force = false)
    {
        if (!CNabuEngine::getEngine()->isOperationModeStandalone() &&
            ($this->languages_list->getSize() === 0 || $force)
        ) {
            $this->languages_list = CNabuCommerceProductLanguage::getLanguagesForTranslatedObject($this);
        }
        
        return $this->languages_list;
    }

    /**
     * Gets available translation instances.
     * @param bool $force If true force to reload translations list from storage.
     * @return null|array Return an array of \nabu\data\commerce\CNabuCommerceProductLanguage instances if they have
     * translations or null if not.
     */
    public function getTranslations($force = false)
    {
        if (!CNabuEngine::getEngine()->isOperationModeStandalone() &&
            ($this->translations_list->getSize() === 0 || $force)
        ) {
            $this->translations_list = CNabuCommerceProductLanguage::getTranslationsForTranslatedObject($this);
        }
        
        return $this->translations_list;
    }

    /**
     * Creates a new translation instance. I the translation already exists then replaces ancient translation with this
     * new.
     * @param int|string|CNabuDataObject $nb_language A valid Id or object containing a nb_language_id field to
     * identify the language of new translation.
     * @return CNabuCommerceProductLanguage Returns the created instance to store translation or null if not valid
     * language was provided.
     */
    public function newTranslation($nb_language)
    {
        $nb_language_id = nb_getMixedValue($nb_language, NABU_LANG_FIELD_ID);
        if (is_numeric($nb_language_id) || nb_isValidGUID($nb_language_id)) {
            $nb_translation = $this->isBuiltIn()
                            ? new CNabuBuiltInCommerceProductLanguage()
                            : new CNabuCommerceProductLanguage()
            ;
            $nb_translation->transferValue($this, 'nb_commerce_product_id');
            $nb_translation->transferValue($nb_language, NABU_LANG_FIELD_ID);
            $this->setTranslation($nb_translation);
        } else {
            $nb_translation = null;
        }
        
        return $nb_translation;
    }

    /**
     * Overrides refresh method to add translations branch to refresh.
     * @param bool $force Forces to reload entities from the database storage.
     * @param bool $cascade Forces to reload child entities from the database storage.
     * @return bool Returns true if transations are empty or refreshed.
     */
    public function refresh(bool $force = false, bool $cascade = false) : bool
    {
        return parent::refresh($force, $cascade) && $this->appendTranslatedRefresh($force);
    }

    /**
     * Get Commerce Product Id attribute value
     * @return int Returns the Commerce Product Id value
     */
    public function getId() : int
    {
        return $this->getValue('nb_commerce_product_id');
    }

    /**
     * Sets the Commerce Product Id attribute value.
     * @param int $id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setId(int $id) : CNabuDataObject
    {
        if ($id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$id")
            );
        }
        $this->setValue('nb_commerce_product_id', $id);
        
        return $this;
    }

    /**
     * Get Commerce Product Category Id attribute value
     * @return null|int Returns the Commerce Product Category Id value
     */
    public function getCategoryId()
    {
        return $this->getValue('nb_commerce_product_category_id');
    }

    /**
     * Sets the Commerce Product Category Id attribute value.
     * @param null|int $category_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCategoryId(int $category_id = null) : CNabuDataObject
    {
        $this->setValue('nb_commerce_product_category_id', $category_id);
        
        return $this;
    }

    /**
     * Get Commerce Id attribute value
     * @return int Returns the Commerce Id value
     */
    public function getCommerceId() : int
    {
        return $this->getValue('nb_commerce_id');
    }

    /**
     * Sets the Commerce Id attribute value.
     * @param int $nb_commerce_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCommerceId(int $nb_commerce_id = 0) : CNabuDataObject
    {
        if ($nb_commerce_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_commerce_id")
            );
        }
        $this->setValue('nb_commerce_id', $nb_commerce_id);
        
        return $this;
    }

    /**
     * Get Commerce Tax Id attribute value
     * @return null|int Returns the Commerce Tax Id value
     */
    public function getCommerceTaxId()
    {
        return $this->getValue('nb_commerce_tax_id');
    }

    /**
     * Sets the Commerce Tax Id attribute value.
     * @param null|int $nb_commerce_tax_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCommerceTaxId(int $nb_commerce_tax_id = null) : CNabuDataObject
    {
        $this->setValue('nb_commerce_tax_id', $nb_commerce_tax_id);
        
        return $this;
    }

    /**
     * Get Provider Id attribute value
     * @return null|int Returns the Provider Id value
     */
    public function getProviderId()
    {
        return $this->getValue('nb_provider_id');
    }

    /**
     * Sets the Provider Id attribute value.
     * @param null|int $nb_provider_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setProviderId(int $nb_provider_id = null) : CNabuDataObject
    {
        $this->setValue('nb_provider_id', $nb_provider_id);
        
        return $this;
    }

    /**
     * Get Medioteca Id attribute value
     * @return null|int Returns the Medioteca Id value
     */
    public function getMediotecaId()
    {
        return $this->getValue('nb_medioteca_id');
    }

    /**
     * Sets the Medioteca Id attribute value.
     * @param null|int $nb_medioteca_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setMediotecaId(int $nb_medioteca_id = null) : CNabuDataObject
    {
        $this->setValue('nb_medioteca_id', $nb_medioteca_id);
        
        return $this;
    }

    /**
     * Get Medioteca Item Id attribute value
     * @return null|int Returns the Medioteca Item Id value
     */
    public function getMediotecaItemId()
    {
        return $this->getValue('nb_medioteca_item_id');
    }

    /**
     * Sets the Medioteca Item Id attribute value.
     * @param null|int $nb_medioteca_item_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setMediotecaItemId(int $nb_medioteca_item_id = null) : CNabuDataObject
    {
        $this->setValue('nb_medioteca_item_id', $nb_medioteca_item_id);
        
        return $this;
    }

    /**
     * Get Commerce Product Type attribute value
     * @return string Returns the Commerce Product Type value
     */
    public function getType() : string
    {
        return $this->getValue('nb_commerce_product_type');
    }

    /**
     * Sets the Commerce Product Type attribute value.
     * @param string $type New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setType(string $type = "P") : CNabuDataObject
    {
        if ($type === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$type")
            );
        }
        $this->setValue('nb_commerce_product_type', $type);
        
        return $this;
    }

    /**
     * Get Commerce Product SKU attribute value
     * @return null|string Returns the Commerce Product SKU value
     */
    public function getSKU()
    {
        return $this->getValue('nb_commerce_product_sku');
    }

    /**
     * Sets the Commerce Product SKU attribute value.
     * @param null|string $sku New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setSKU(string $sku = null) : CNabuDataObject
    {
        $this->setValue('nb_commerce_product_sku', $sku);
        
        return $this;
    }

    /**
     * Get Commerce Product Units Range attribute value
     * @return null|string Returns the Commerce Product Units Range value
     */
    public function getUnitsRange()
    {
        return $this->getValue('nb_commerce_product_units_range');
    }

    /**
     * Sets the Commerce Product Units Range attribute value.
     * @param null|string $units_range New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setUnitsRange(string $units_range = null) : CNabuDataObject
    {
        $this->setValue('nb_commerce_product_units_range', $units_range);
        
        return $this;
    }

    /**
     * Get Commerce Product Cost Amount attribute value
     * @return mixed Returns the Commerce Product Cost Amount value
     */
    public function getCostAmount()
    {
        return $this->getValue('nb_commerce_product_cost_amount');
    }

    /**
     * Sets the Commerce Product Cost Amount attribute value.
     * @param mixed $cost_amount New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCostAmount($cost_amount) : CNabuDataObject
    {
        $this->setValue('nb_commerce_product_cost_amount', $cost_amount);
        
        return $this;
    }

    /**
     * Get Commerce Product Cost Money attribute value
     * @return null|string Returns the Commerce Product Cost Money value
     */
    public function getCostMoney()
    {
        return $this->getValue('nb_commerce_product_cost_money');
    }

    /**
     * Sets the Commerce Product Cost Money attribute value.
     * @param null|string $cost_money New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCostMoney(string $cost_money = null) : CNabuDataObject
    {
        $this->setValue('nb_commerce_product_cost_money', $cost_money);
        
        return $this;
    }

    /**
     * Get Commerce Product Price Amount attribute value
     * @return mixed Returns the Commerce Product Price Amount value
     */
    public function getPriceAmount()
    {
        return $this->getValue('nb_commerce_product_price_amount');
    }

    /**
     * Sets the Commerce Product Price Amount attribute value.
     * @param mixed $price_amount New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setPriceAmount($price_amount) : CNabuDataObject
    {
        $this->setValue('nb_commerce_product_price_amount', $price_amount);
        
        return $this;
    }

    /**
     * Get Commerce Product Price Money attribute value
     * @return null|string Returns the Commerce Product Price Money value
     */
    public function getPriceMoney()
    {
        return $this->getValue('nb_commerce_product_price_money');
    }

    /**
     * Sets the Commerce Product Price Money attribute value.
     * @param null|string $price_money New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setPriceMoney(string $price_money = null) : CNabuDataObject
    {
        $this->setValue('nb_commerce_product_price_money', $price_money);
        
        return $this;
    }

    /**
     * Get Commerce Product Provider Reference attribute value
     * @return null|string Returns the Commerce Product Provider Reference value
     */
    public function getProviderReference()
    {
        return $this->getValue('nb_commerce_product_provider_reference');
    }

    /**
     * Sets the Commerce Product Provider Reference attribute value.
     * @param null|string $provider_reference New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setProviderReference(string $provider_reference = null) : CNabuDataObject
    {
        $this->setValue('nb_commerce_product_provider_reference', $provider_reference);
        
        return $this;
    }

    /**
     * Get Commerce Product Single Unit Suffix attribute value
     * @return null|string Returns the Commerce Product Single Unit Suffix value
     */
    public function getSingleUnitSuffix()
    {
        return $this->getValue('nb_commerce_product_single_unit_suffix');
    }

    /**
     * Sets the Commerce Product Single Unit Suffix attribute value.
     * @param null|string $single_unit_suffix New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setSingleUnitSuffix(string $single_unit_suffix = null) : CNabuDataObject
    {
        $this->setValue('nb_commerce_product_single_unit_suffix', $single_unit_suffix);
        
        return $this;
    }

    /**
     * Get Commerce Product Multi Unit Suffix attribute value
     * @return null|string Returns the Commerce Product Multi Unit Suffix value
     */
    public function getMultiUnitSuffix()
    {
        return $this->getValue('nb_commerce_product_multi_unit_suffix');
    }

    /**
     * Sets the Commerce Product Multi Unit Suffix attribute value.
     * @param null|string $multi_unit_suffix New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setMultiUnitSuffix(string $multi_unit_suffix = null) : CNabuDataObject
    {
        $this->setValue('nb_commerce_product_multi_unit_suffix', $multi_unit_suffix);
        
        return $this;
    }

    /**
     * Get Commerce Product Tax Percentage attribute value
     * @return mixed Returns the Commerce Product Tax Percentage value
     */
    public function getTaxPercentage()
    {
        return $this->getValue('nb_commerce_product_tax_percentage');
    }

    /**
     * Sets the Commerce Product Tax Percentage attribute value.
     * @param mixed $tax_percentage New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setTaxPercentage($tax_percentage) : CNabuDataObject
    {
        if ($tax_percentage === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$tax_percentage")
            );
        }
        $this->setValue('nb_commerce_product_tax_percentage', $tax_percentage);
        
        return $this;
    }

    /**
     * Get Commerce Product Tax Amount attribute value
     * @return mixed Returns the Commerce Product Tax Amount value
     */
    public function getTaxAmount()
    {
        return $this->getValue('nb_commerce_product_tax_amount');
    }

    /**
     * Sets the Commerce Product Tax Amount attribute value.
     * @param mixed $tax_amount New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setTaxAmount($tax_amount) : CNabuDataObject
    {
        if ($tax_amount === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$tax_amount")
            );
        }
        $this->setValue('nb_commerce_product_tax_amount', $tax_amount);
        
        return $this;
    }

    /**
     * Get Commerce Product Price With Tax attribute value
     * @return mixed Returns the Commerce Product Price With Tax value
     */
    public function getPriceWithTax()
    {
        return $this->getValue('nb_commerce_product_price_with_tax');
    }

    /**
     * Sets the Commerce Product Price With Tax attribute value.
     * @param mixed $price_with_tax New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setPriceWithTax($price_with_tax) : CNabuDataObject
    {
        if ($price_with_tax === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$price_with_tax")
            );
        }
        $this->setValue('nb_commerce_product_price_with_tax', $price_with_tax);
        
        return $this;
    }

    /**
     * Get Commerce Product Is Upselling attribute value
     * @return string Returns the Commerce Product Is Upselling value
     */
    public function getIsUpselling() : string
    {
        return $this->getValue('nb_commerce_product_is_upselling');
    }

    /**
     * Sets the Commerce Product Is Upselling attribute value.
     * @param string $is_upselling New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setIsUpselling(string $is_upselling = "F") : CNabuDataObject
    {
        if ($is_upselling === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$is_upselling")
            );
        }
        $this->setValue('nb_commerce_product_is_upselling', $is_upselling);
        
        return $this;
    }

    /**
     * Get Commerce Product Sold Individually attribute value
     * @return string Returns the Commerce Product Sold Individually value
     */
    public function getSoldIndividually() : string
    {
        return $this->getValue('nb_commerce_product_sold_individually');
    }

    /**
     * Sets the Commerce Product Sold Individually attribute value.
     * @param string $sold_individually New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setSoldIndividually(string $sold_individually = "T") : CNabuDataObject
    {
        if ($sold_individually === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$sold_individually")
            );
        }
        $this->setValue('nb_commerce_product_sold_individually', $sold_individually);
        
        return $this;
    }

    /**
     * Get Commerce Product Is Virtual attribute value
     * @return string Returns the Commerce Product Is Virtual value
     */
    public function getIsVirtual() : string
    {
        return $this->getValue('nb_commerce_product_is_virtual');
    }

    /**
     * Sets the Commerce Product Is Virtual attribute value.
     * @param string $is_virtual New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setIsVirtual(string $is_virtual = "F") : CNabuDataObject
    {
        if ($is_virtual === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$is_virtual")
            );
        }
        $this->setValue('nb_commerce_product_is_virtual', $is_virtual);
        
        return $this;
    }

    /**
     * Get Commerce Product Time To Live attribute value
     * @return null|string Returns the Commerce Product Time To Live value
     */
    public function getTimeToLive()
    {
        return $this->getValue('nb_commerce_product_time_to_live');
    }

    /**
     * Sets the Commerce Product Time To Live attribute value.
     * @param null|string $time_to_live New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setTimeToLive(string $time_to_live = null) : CNabuDataObject
    {
        $this->setValue('nb_commerce_product_time_to_live', $time_to_live);
        
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
        
        $trdata = $this->appendTranslatedTreeData($trdata, $nb_language, $dataonly);
        
        return $trdata;
    }
}
