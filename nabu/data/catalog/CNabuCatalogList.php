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

namespace nabu\data\catalog;
use nabu\core\CNabuEngine;
use nabu\data\catalog\base\CNabuCatalogListBase;
use nabu\data\customer\CNabuCustomer;
use nabu\data\customer\traits\TNabuCustomerChild;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\catalog
 */
class CNabuCatalogList extends CNabuCatalogListBase
{
    use TNabuCustomerChild;

    /**
     * Creates the instance. Receives as parameter the Customer instance that owns this Catalog List.
     * @param CNabuCustomer $nb_customer Customer owner instance.
     */
    public function __construct(CNabuCustomer $nb_customer = null)
    {
        parent::__construct();

        $this->setCustomer($nb_customer);
    }

    public function acquireItem($key, $index = false)
    {
        if (!($item = parent::acquireItem($key, $index)) && CNabuEngine::getEngine()->isMainDBAvailable()) {
            switch ($index) {
                case self::INDEX_KEY:
                    $item = CNabuCatalog::findByKey($this->nb_customer, $key);
                    break;
            }
        }

        if (is_object($item)) {
            $item->setCustomer($this->getCustomer());
        }
        
        return $item;
    }

}
