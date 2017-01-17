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
use nabu\data\catalog\base\CNabuCatalogItemListBase;
use nabu\data\catalog\traits\TNabuCatalogChild;
use nabu\data\lang\interfaces\INabuTranslated;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\catalog
 */
class CNabuCatalogItemList extends CNabuCatalogItemListBase
{
    use TNabuCatalogChild;

    public function __construct(CNabuCatalog $nb_catalog = null)
    {
        parent::__construct();

        if ($nb_catalog !== null) {
            $this->setCatalog($nb_catalog);
        }
    }

    /**
     * Populates the catalog with all Items.
     * @param int $deep If 0 all items are retrieved, else if great of 0, then retrieves only the first $deep levels.
     */
    public function populate(int $deep = 0)
    {
        $nb_catalog = $this->getCatalog();
        $this->merge(CNabuCatalogItem::getItemsForCatalog($nb_catalog, $deep));
        $translations = CNabuCatalogItem::getItemTranslationsForCatalog($nb_catalog, $deep);
        $translations->iterate(
            function ($key, $translation)
            {
                $item = $this->getItem($translation->getCatalogItemId());
                if ($item instanceof INabuTranslated) {
                    $item->setTranslation($translation);
                }
                return true;
            }
        );
    }
}
