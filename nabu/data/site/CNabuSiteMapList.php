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

namespace nabu\data\site;

use nabu\data\CNabuDataObject;
use nabu\data\security\interfaces\INabuRoleMask;
use nabu\data\security\traits\TNabuRoleMaskList;
use nabu\data\site\base\CNabuSiteMapListBase;
use nabu\data\site\traits\TNabuSiteChild;

/**
 * Class to manage Site Map lists.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site
 */
class CNabuSiteMapList extends CNabuSiteMapListBase implements INabuRoleMask
{
    use TNabuSiteChild;
    use TNabuRoleMaskList;

    public function __construct(CNabuSite $nb_site = null)
    {
        parent::__construct();

        if ($nb_site !== null) {
            $this->setSite($nb_site);
        }
    }

    /**
     * Populates the Site Map with all Items.
     * @param int $deep If 0 all items are retrieved, else if great than 0, then retrieves only the first $deep levels.
     */
    public function populate(int $deep = 0)
    {
        $nb_site = $this->getSite();
        $this->merge(CNabuSiteMap::getMapsForSite($nb_site));
        $translations = CNabuSiteMapLanguage::getMapTranslationsForSite($nb_site);
        if (is_array($translations) && count($translations) > 0) {
            foreach ($translations as $translation) {
                $item = $this->getItem($translation->getSiteMapId());
                $item->setTranslation($translation);
            }
        }
        $roles = CNabuSiteMapRole::getMapRolesForSite($nb_site);
        if (is_array($roles) && count($roles) > 0) {
            foreach ($roles as $role) {
                $item = $this->getItem($role->getSiteMapId());
                $item->addRole($role);
            }
        }
    }

    public function addItem(CNabuDataObject $item)
    {
        $value = parent::addItem($item);
        if ($value instanceof CNabuSiteMap && ($nb_site = $this->getSite()) !== null) {
            $value->setSite($this->getSite());
        }

        return $value;
    }
}
