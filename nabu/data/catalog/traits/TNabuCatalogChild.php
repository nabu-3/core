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

namespace nabu\data\catalog\traits;

use nabu\data\CNabuDataObject;
use nabu\data\catalog\CNabuCatalog;

/**
 * This trait implements default actions to manage a Catalog child object in nabu-3.
 * You can apply this trait to your own classes to speed up your development,
 * or create your own management.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\catalog\traits;
 */
trait TNabuCatalogChild
{
    /**
     * Catalog instance that owns this object.
     * @var CNabuCatalog
     */
    private $nb_catalog = null;

    /**
     * Gets the Catalog instance that owns this object.
     * @return CNabuCatalog Returns the catalog instance if exists or null if not.
     */
    public function getCatalog($force = false)
    {
        if ($this->nb_catalog === null || $force) {
            $this->nb_catalog = null;
            if ($this instanceof CNabuDataObject &&
                !$this->isBuiltIn()
                && $this->isValueNumeric(NABU_CATALOG_FIELD_ID)
            ) {
                $nb_catalog = new CNabuCatalog($this->getValue(NABU_CATALOG_FIELD_ID));
                if ($nb_catalog->isFetched()) {
                    $this->nb_catalog = $nb_catalog;
                }
            }
        }

        return $this->nb_catalog;
    }

    /**
     * Sets the Catalog instance that onws this object.
     * @param CNabuCatalog $nb_catalog The Catalog instance to be setted.
     * @return mixed Return $this to grant cascade chain.
     */
    public function setCatalog(CNabuCatalog $nb_catalog)
    {
        $this->nb_catalog = $nb_catalog;
        if ($this instanceof CNabuDataObject && $nb_catalog->contains(NABU_CATALOG_FIELD_ID)) {
            $this->transferValue($nb_catalog, NABU_CATALOG_FIELD_ID);
        }

        return $this;
    }
}
