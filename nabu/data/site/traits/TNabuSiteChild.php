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

namespace nabu\data\site\traits;

use \nabu\data\site\CNabuSite;
use nabu\data\CNabuDataObject;
use nabu\data\customer\CNabuCustomer;

/**
 * This trait implements default actions to manage a Site child object in nabu-3.
 * You can apply this trait to your own classes to speed up your development,
 * or create your own management.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site\traits;
 */
trait TNabuSiteChild
{
    /**
     * Site instance
     * @var CNabuSite
     */
    protected $nb_site = null;

    /**
     * Gets the Site instance.
     * @param CNabuCustomer|null $nb_customer If you want to get the Site in safe mode, you need to pass this parameter
     * to grant the Customer that owns the Site where the Site Map is placed. In any other case, the Site could not be
     * retrieved.
     * @param bool $force If true, then tries to reload Site instance from their storage.
     * @return CNabuSite|null Returns the Site instance if setted or null if not.
     */
    public function getSite(CNabuCustomer $nb_customer = null, $force = false)
    {
        if ($nb_customer !== null && ($this->nb_site === null || $force)) {
            $this->nb_site = null;
            if ($this instanceof CNabuDataObject && $this->contains(NABU_SITE_FIELD_ID)) {
                $this->nb_site = $nb_customer->getSite($this);
            }
        }

        return $this->nb_site;
    }

    /**
     * Sets the Site instance that owns this object and sets the field containing the site id.
     * @param CNabuSite|null $nb_site Site instance to be setted.
     * @param string $field Field name where the site id will be stored.
     * @return CNabuDataObject Returns $this to allow the cascade chain of setters.
     */
    public function setSite(CNabuSite $nb_site = null, $field = NABU_SITE_FIELD_ID)
    {
        $this->nb_site = $nb_site;
        if ($this instanceof CNabuDataObject) {
            $this->transferValue($nb_site, $field);
        }

        return $this;
    }
}
