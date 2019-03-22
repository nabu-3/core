<?php

/** @license
 *  Copyright 2019-2011 Rafael Gutierrez Martinez
 *  Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 *  Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 *  Copyright 2017 nabu-3 Group
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

namespace nabu\data\commerce;

use \nabu\data\commerce\base\CNabuCommerceBase;
use \nabu\data\customer\traits\TNabuCustomerChild;
use nabu\data\commerce\CNabuCommerceProductCategory;
use nabu\data\commerce\builtin\CNabuBuiltInCommerceProductCategory;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\commerce
 */
class CNabuCommerce extends CNabuCommerceBase
{
    use TNabuCustomerChild;

    /** @var CNabuCommerceProductCategoryList Product categories list */
    private $nb_commerce_product_category_list = null;
    /** @var CNabuCommerceProductList Product list */
    private $nb_commerce_product_list = null;

    public function __construct($nb_commerce = false)
    {
        parent::__construct($nb_commerce);

        $this->nb_commerce_product_category_list = new CNabuCommerceProductCategoryList($this);
        $this->nb_commerce_product_list = new CNabuCommerceProductList($this);
    }

    /**
     * Sort all lists (Categories and Products) using sorting criteria of each list defined in their classes.
     */
    public function sortAll()
    {
        $this->nb_commerce_product_category_list->sort();
        $this->nb_commerce_product_list->sort();
    }

    /**
     * Creates a new Product Category instance.
     * @param string|null $key Key to identify the Category.
     * @param int $order Order of the Category.
     * @return CNabuCommerceProductCategory Returns a valid instance.
     */
    public function newProductCategory(string $key = null, int $order = 1) : CNabuCommerceProductCategory
    {
        $nb_commerce_product_category = $this->isBuiltIn()
                                      ? new CNabuBuiltInCommerceProductCategory()
                                      : new CNabuCommerceProductCategory()
        ;

        $nb_commerce_product_category->setKey($key);
        $nb_commerce_product_category->setOrder($order);

        return $this->addProductCategoryObject($nb_commerce_product_category);
    }

    /**
     * Adds a Product Category object to this Commerce.
     * @param CNabuCommerceProductCategory $nb_commerce_product_category A Product Category object to be added.
     * @return CNabuCommerceProductCategory|false Returns the inserted object nor false if is not inserted.
     */
    public function addProductCategoryObject(CNabuCommerceProductCategory $nb_commerce_product_category)
    {
        $nb_commerce_product_category->setCommerce($this);

        return $this->nb_commerce_product_category_list->addItem($nb_commerce_product_category);
    }

    /**
     * Gets a Product Category by Id.
     * @param mixed $nb_commerce_product_category A CNabuDataObject containing a field named nb_commerce_product_category_id
     * or a valid ID.
     * @return CNabuCommerceProductCategory|false Returns a valid instance if exists or false if not.
     */
    public function getProductCategory($nb_commerce_product_category)
    {
        $nb_category = false;
        $nb_category_id = nb_getMixedValue($nb_commerce_product_category, NABU_COMMERCE_PRODUCT_CATEGORY_FIELD_ID);
        if (is_numeric($nb_category_id)) {
            $nb_category = $this->nb_commerce_product_category_list->getItem($nb_category_id);
        }

        return $nb_category;
    }

    /**
     * Gets the full list of all Product Categories in this Commerce instance.
     * @param bool $force If true, forces to reload list form the database storage.
     * @return CNabuCommerceProductCategoryList Returns a Product Category List with all Categories.
     */
    public function getProductCategories(bool $force = false) : CNabuCommerceProductCategoryList
    {
        if ($this->nb_commerce_product_category_list->isEmpty() || $force) {
            $this->nb_commerce_product_category_list->clear();
            $this->nb_commerce_product_category_list->merge(
                CNabuCommerceProductCategory::getCategoriesForCommerce($this)
            );
        }

        return $this->nb_commerce_product_category_list;
    }

    /**
     * Find a Product Category using their key.
     * @param string $key Key of Product Category to find.
     * @return CNabuCommerceProductCategory|false Returns the Product Category instance if exists or false if not.
     */
    public function findProductCategoryByKey(string $key)
    {
        return $this->nb_commerce_product_category_list->getItem(
            $key, CNabuCommerceProductCategoryList::INDEX_KEY
        );
    }

    /**
     * Find a Product Category using their slug.
     * @param string $slug Slug of Product Category to find.
     * @return CNabuCommerceProductCategory|false Returns the Product Category instance if exists or false if not.
     */
    public function findProductCategoryBySlug($slug)
    {
        return $this->nb_commerce_product_category_list->getItem(
            $slug, CNabuCommerceProductCategoryList::INDEX_SLUG
        );
    }

    /**
     * Gets the full list of all Products in this Commerce instance.
     * @param bool $force If true, forces to reload list from the database storage.
     * @return CNabuCommerceProductList Returns a Product List with all Categories.
     */
    public function getProducts(bool $force = false) : CNabuCommerceProductList
    {
        if ($this->nb_commerce_product_list->isEmpty() || $force) {
            $this->nb_commerce_product_list->clear();
            $this->nb_commerce_product_list->merge(
                CNabuCommerceProduct::getProductsForCommerce($this)
            );
        }

        return $this->nb_commerce_product_list;
    }

    /**
     * Gets a Product by Id.
     * @param mixed $nb_commerce_product A CNabuDataObject containing a field named nb_commerce_product_id or a valid ID.
     * @return CNabuCommerceProduct|false Returns a valid instance if exists or false if not.
     */
    public function getProduct($nb_commerce_product)
    {
        $nb_product = false;
        $nb_product_id = nb_getMixedValue($nb_commerce_product, NABU_COMMERCE_PRODUCT_FIELD_ID);
        if (is_numeric($nb_product_id)) {
            $nb_product = $this->nb_commerce_product_list->getItem($nb_product_id);
        }

        return $nb_product;
    }

    /**
     * Find a Product using their slug.
     * @param string $slug Slug of Product to find.
     * @param mixed|null $nb_language Language to search the Slug.
     * @return CNabuCommerceProduct|false Returns the Product instance if exists or false if not.
     */
    public function findProductBySlug(string $slug, $nb_language = null)
    {
        $retval = false;
        $nb_language_id = nb_getMixedValue($nb_language, NABU_LANG_FIELD_ID);

        $this->getProducts()->iterate(
            function ($key, CNabuCommerceProduct $nb_product)
                 use (&$retval, $slug, $nb_language_id)
            {
                if (is_numeric($nb_language_id) &&
                    ($nb_translation = $nb_product->getTranslation($nb_language_id)) instanceof CNabuCommerceProductLanguage &&
                    $nb_translation->getSlug() === $slug
                ) {
                    $retval = $nb_product;
                } else {
                    $nb_product->getTranslations()->iterate(
                        function ($key, CNabuCommerceProductLanguage $nb_translation)
                             use (&$retval, $nb_product, $slug)
                        {
                            if ($nb_translation->getSlug() === $slug) {
                                $retval = $nb_product;
                            }
                            return !$retval;
                        }
                    );
                }
                return !$retval;
            }
        );

        return $retval;
    }

    /**
     * Find a product by its SKU.
     * @param string $sku SKU to looking for the Product.
     * @return CNabuCommerceProduct|false Returns the Product instance that matches with the SKU or false if none found.
     */
    public function findProductBySKU(string $sku)
    {
        return $this->nb_commerce_product_list->getItem($sku, CNabuCommerceProductList::INDEX_SKU);
    }

    /**
     * Overrides getTreeData method to add products and categories branches.
     * If $nb_language have a valid value, also adds a translation object
     * with current translation pointed by it.
     * @param int|object $nb_language Instance or Id of the language to be used.
     * @param bool $dataonly Render only field values and ommit class control flags.
     * @return array Returns a multilevel associative array with all data.
     */
    public function getTreeData($nb_language = null, $dataonly = false)
    {
        $trdata = parent::getTreeData($nb_language, $dataonly);

        $trdata['languages'] = $this->getLanguages();
        $trdata['categories'] = $this->nb_commerce_product_category_list;
        $trdata['products'] = $this->nb_commerce_product_list;

        return $trdata;
    }

    /**
     * Overrides refresh method to allow commerce subentities to be refreshed.
     * @param bool $force Forces to reload entities from the database storage.
     * @param bool $cascade Forces to reload child entities from the database storage.
     * @return bool Returns true if transations are empty or refreshed.
     */
    public function refresh(bool $force = false, bool $cascade = false) : bool
    {
        return parent::refresh($force, $cascade) &&
               (!$cascade || (
                   $this->getProductCategories($force) &&
                   $this->getProducts($force)
               ))
        ;
    }
}
