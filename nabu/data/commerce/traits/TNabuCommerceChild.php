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

namespace nabu\data\commerce\traits;

use nabu\data\commerce\CNabuCommerce;

/**
 * This trait implements default actions to manage a Commerce child object in Nabu 3.
 * A lot of methods defined in the interface \nabu\customer\interfaces\INabuCommerceChild
 * are implemented here with a default behavior.
 * You can apply this trait to your own classes to speed up your development,
 * or create your own management.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\commerce\traits;
 */
trait TNabuCommerceChild
{
    /**
     * Commerce instance that owns this object.
     * @var CNabuCommerce
     */
    private $nb_commerce = null;

    /**
     * Gets the Commerce instance that owns this object.
     * @return CNabuCommerce Returns the commerce instance if exists or null if not.
     */
    public function getCommerce($force = false)
    {
        if ($this->nb_commerce === null || $force) {
            $this->nb_commerce = null;
            if (!$this->isBuiltIn() && $this->isValueNumeric(NABU_COMMERCE_FIELD_ID)) {
                $nb_commerce = new CNabuCommerce($this->getValue(NABU_COMMERCE_FIELD_ID));
                if ($nb_commerce->isFetched()) {
                    $this->nb_commerce = $nb_commerce;
                }
            }
        }

        return $this->nb_commerce;
    }

    /**
     * Sets the Commerce instance that onws this object.
     * @param CNabuCommerce $nb_commerce The Commerce instance to be setted.
     * @return mixed Return $this to grant cascade chain.
     */
    public function setCommerce(CNabuCommerce $nb_commerce)
    {
        $this->nb_commerce = $nb_commerce;
        $this->transferValue($nb_commerce, NABU_COMMERCE_FIELD_ID);

        return $this;
    }
}
