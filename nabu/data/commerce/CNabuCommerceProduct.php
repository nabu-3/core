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

use nabu\data\commerce\base\CNabuCommerceProductBase;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\commerce
 */
class CNabuCommerceProduct extends CNabuCommerceProductBase
{
    /**
     * Get all Products of a Commerce.
     * @param mixed $nb_commerce
     */
    static public function getProductsForCommerce($nb_commerce)
    {
        $nb_commerce_id = nb_getMixedValue($nb_commerce, NABU_COMMERCE_FIELD_ID);
        if (is_numeric($nb_commerce_id)) {
            $retval = CNabuCommerceProduct::buildObjectListFromSQL(
                'nb_commerce_product_id',
                'SELECT *
                   FROM nb_commerce_product
                  WHERE nb_commerce_id=%commerce_id$d',
                array(
                    'commerce_id' => $nb_commerce_id
                )
            );
            if ($nb_commerce instanceof CNabuCommerce) {
                $retval->setCommerce($nb_commerce);
            }
        } else {
            $retval = new CNabuCommerceProductCategoryList($nb_commerce instanceof CNabuCommerce ? $nb_commerce : null);
        }

        return $retval;
    }

    /**
     * Find a product in a Commerce by its SKU.
     * @param mixed $nb_commerce A CNabuDataObject instance containing a field named nb_commerce_id or a valid ID.
     * @param string $sku The SKU to be found.
     * @return CNabuCommerceProduct|false Returns a valid instance if found, or false if not found.
     */
    static public function findBySKU($nb_commerce, string $sku)
    {
        $retval = false;
        $nb_commerce_id = nb_getMixedValue($nb_commerce, NABU_COMMERCE_FIELD_ID);
        if (is_numeric($nb_commerce_id)) {
            $retval = CNabuCommerceProduct::buildObjectFromSQL(
                'SELECT *
                   from nb_commerce_product
                  where nb_commerce_id=%commerce_id$d
                    and nb_commerce_product_sku=\'%sku$s\'
                ',
                array(
                    'commerce_id' => $nb_commerce_id,
                    'sku' => $sku
                )
            );
        }

        return $retval;
    }
}
