<?php

/*  Copyright 2009-2011 Rafael Gutierrez Martinez
 *  Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 *  Copyright 2014-2016 Where Ideas Simply Come True, S.L.
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
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\commerce
 */
class CNabuCommerce extends CNabuCommerceBase
{
    use TNabuCustomerChild;

    /**
     * Product categories list
     * @var CNabuCommerceProductCategoryList
     */
    private $nb_commerce_product_category_list = null;

    public function __construct($nb_commerce = false)
    {
        parent::__construct($nb_commerce);

        $this->nb_commerce_product_category_list = new CNabuCommerceProductCategoryList($this);
    }

    public function sortAll()
    {
        $this->nb_commerce_product_category_list->sort();
    }

    public function newProductCategory($key, $order)
    {
        $nb_commerce_product_category = $this->isBuiltIn()
                                      ? new CNabuBuiltInCommerceProductCategory()
                                      : new CNabuCommerceProductCategory()
        ;

        $nb_commerce_product_category->setKey($key);
        $nb_commerce_product_category->setOrder($order);

        return $this->addProductCategoryObject($nb_commerce_product_category);
    }

    public function addProductCategoryObject(CNabuCommerceProductCategory $nb_commerce_product_category)
    {
        $nb_commerce_product_category->setCommerce($this);

        return $this->nb_commerce_product_category_list->addItem($nb_commerce_product_category);
    }

    public function getProductCategory($nb_commerce_product_category_id)
    {
        return $this->nb_commerce_product_category_list->getItem($nb_commerce_product_category_id);
    }

    public function getProductCategories($force = false)
    {
        if ($this->nb_commerce_product_category_list->isEmpty() || $force) {
            $this->nb_commerce_product_category_list->merge(
                CNabuCommerceProductCategory::getCategoriesForCommerce($this)
            );
        }
        return $this->nb_commerce_product_category_list;
    }

    public function findProductCategoryBySlug($slug)
    {
        return $this->nb_commerce_product_category_list->getItem(
            $slug, CNabuCommerceProductCategoryList::INDEX_SLUG
        );
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

        $trdata['categories'] = $this->nb_commerce_product_category_list;

        return $trdata;
    }

    /**
     * Overrides refresh method to add commerce subentities to be refreshed.
     * @return bool Returns true if transations are empty or refreshed.
     */
    public function refresh()
    {
        return parent::refresh() && $this->getProductCategories();
    }
}
