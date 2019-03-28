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

use \nabu\data\commerce\base\CNabuCommerceProductCategoryBase;
use nabu\data\commerce\traits\TNabuCommerceChild;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\commerce
 */
class CNabuCommerceProductCategory extends CNabuCommerceProductCategoryBase
{
    use TNabuCommerceChild;

    public function indexSlugs()
    {
        $slugs = null;

        if ($this->hasTranslations()) {
            $slugs = array();
            $this->getTranslations()->iterate(
                function ($key, $translation) use(&$slugs)
                {
                    $slug = $translation->getSlug();
                    $slugs[$slug] = array(
                        'key' => $slug,
                        'pointer' => array(
                            $this->getId(),
                            $translation->getLanguageId()
                        )
                    );
                }
            );
            if (count($slugs) === 0) {
                $slugs = null;
            }
        }

        return $slugs;
    }

    static public function getCategoriesForCommerce($nb_commerce)
    {
        $nb_commerce_id = nb_getMixedValue($nb_commerce, NABU_COMMERCE_FIELD_ID);
        if (is_numeric($nb_commerce_id)) {
            $retval = CNabuCommerceProductCategory::buildObjectListFromSQL(
                'nb_commerce_product_category_id',
                'select * '
                . 'from nb_commerce_product_category '
                . 'where nb_commerce_id=%commerce_id$d '
                . 'order by nb_commerce_product_category_order asc',
                array(
                    'commerce_id' => $nb_commerce_id
                )
            );
            $retval->setCommerce($nb_commerce);
        } else {
            $retval = new CNabuCommerceProductCategoryList($nb_commerce);
        }

        return $retval;
    }

    static public function findBySlug($nb_commerce, $slug)
    {
        $retval = null;

        $nb_commerce_id = nb_getMixedValue($nb_commerce, NABU_COMMERCE_FIELD_ID);
        if (is_numeric($nb_commerce_id) && is_string($slug)) {
            $retval = CNabuCommerceProductCategory::buildObjectFromSQL(
                'select cpc.* '
                . 'from nb_commerce_product_category cpc, nb_commerce_product_category_lang cpcl '
                . 'where cpc.nb_commerce_id=%commerce_id$d '
                . 'and cpc.nb_commerce_product_category_id=cpcl.nb_commerce_product_category_id '
                . "and cpcl.nb_commerce_product_category_lang_slug='%slug\$s'",
                array(
                    'commerce_id' => $nb_commerce_id,
                    'slug' => $slug
                )
            );
        }

        return $retval;
    }
}
