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

namespace nabu\data\site\traits;

use nabu\data\CNabuDataObject;
use nabu\data\site\CNabuSiteMap;

/**
 * This trait implements default actions to manage a Site Target child object in nabu-3.
 * You can apply this trait to your own classes to speed up your development,
 * or create your own management.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site\traits;
 */
trait TNabuSiteMapChild
{
    /**
     * Site Map instance
     * @var CNabuSiteMap
     */
    protected $nb_site_map = null;

    /**
     * Gets the Site Map instance.
     * @return CNabuSiteMap|null Returns the Site Map instance if setted or null if not.
     */
    public function getSiteMap()
    {
        return $this->nb_site_map;
    }

    /**
     * Sets the Site Map instance that owns this object and sets the field containing the site id.
     * @param CNabuSiteMap $nb_site_map Site Map instance to be setted.
     * @param string $field Field name where the site id will be stored.
     * @return mixed Returns $this to allow the cascade chain of setters.
     */
    public function setSiteMap(CNabuSiteMap $nb_site_map, $field = NABU_SITE_MAP_FIELD_ID)
    {
        $this->nb_site_map = $nb_site_map;
        if ($this instanceof CNabuDataObject) {
            $this->transferValue($nb_site_map, NABU_SITE_MAP_FIELD_ID, $field);
        }

        return $this;
    }
}
