<?php

/*  Copyright 2009-2011 Rafael Gutierrez Martinez
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
use nabu\core\CNabuEngine;
use nabu\core\exceptions\ENabuCoreException;
use nabu\data\commerce\base\CNabuCommerceProductCategoryListBase;
use nabu\data\commerce\traits\TNabuCommerceChild;
use nabu\data\lang\CNabuDataObjectListIndexLanguage;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\lang
 */
class CNabuCommerceProductCategoryList extends CNabuCommerceProductCategoryListBase
{
    use TNabuCommerceChild;

    /**
     * Index categories by Slug
     * @var string
     */
    const INDEX_SLUG = 'slugs';

    /**
     * Commerce parent instance
     * @var CNabuCommerce
     */
    private $nb_commerce = null;

    /**
     * Creates the instance. Receives as parameter the Commerce instance that owns these Product Categories.
     * @param CNabuCommerce|null $nb_commerce Commerce instance.
     */
    public function __construct(CNabuCommerce $nb_commerce = null)
    {
        parent::__construct();

        $this->nb_commerce = $nb_commerce;
    }

    /**
     * Gets parent Commerce instance.
     * @return CNabuCommerce Returns the parent Commerce instance.
     */
    public function getCommerce()
    {
        return $this->nb_commerce;
    }

    /**
     * Sets the parent Commerce instance.
     * @param CNabuCommerce $nb_commerce Commerce instance to be setted.
     * @return CNabuCommerceProductCategoryList Returns $this to grant chained calls.
     */
    public function setCommerce(CNabuCommerce $nb_commerce)
    {
        $this->nb_commerce = $nb_commerce;

        return $this;
    }

    protected function createSecondaryIndexes()
    {
        parent::createSecondaryIndexes();

        $this->addIndex(
            new CNabuDataObjectListIndexLanguage(
                $this, 'nb_commerce_product_category_lang_slug', null, self::INDEX_SLUG
            )
        );
    }

    public function acquireItem($key, $index = false)
    {
        if (!($item = parent::acquireItem($key, $index)) && CNabuEngine::getEngine()->isMainDBAvailable()) {
            switch ($index) {
                case self::INDEX_KEY:
                    /** @todo: Check creation of findByKey method in the Nabu PHP Table Builder class to use parent entities */
                    //$item = CNabuCommerceProductCategory::findByKey($this->nb_commerce, $key);
                    throw new ENabuCoreException(ENabuCoreException::ERROR_FEATURE_NOT_IMPLEMENTED);
                    break;
                case self::INDEX_SLUG:
                    $item = CNabuCommerceProductCategory::findBySlug($this->nb_commerce, $key);
                    break;
            }
        }

        return $item;
    }
}
