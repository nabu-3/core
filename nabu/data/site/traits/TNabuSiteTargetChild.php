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

use nabu\data\CNabuDataObject;
use nabu\data\site\CNabuSiteTarget;

/**
 * This trait implements default actions to manage a Site Target child object in nabu-3.
 * You can apply this trait to your own classes to speed up your development,
 * or create your own management.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site\traits;
 */
trait TNabuSiteTargetChild
{
    /**
     * Site instance
     * @var CNabuSiteTarget
     */
    protected $nb_site_target = null;

    /**
     * Gets the Site Target instance.
     * @return CNabuSiteTarget|null Returns the Site instance if setted or null if not.
     */
    public function getSiteTarget()
    {
        return $this->nb_site_target;
    }

    /**
     * Sets the Site Target instance that owns this object and sets the field containing the site id.
     * @param CNabuSiteTarget|null $nb_site_target Site instance to be setted or null to be unsetted.
     * @param string $field Field name where the Site Target Id will be stored.
     * @return mixed Returns $this to allow the cascade chain of setters.
     */
    public function setSiteTarget(CNabuSiteTarget $nb_site_target = null, $field = NABU_SITE_TARGET_FIELD_ID)
    {
        $this->nb_site_target = $nb_site_target;
        if ($this instanceof CNabuDataObject) {
            if ($nb_site_target instanceof CNabuSiteTarget) {
                $this->transferValue($nb_site_target, NABU_SITE_TARGET_FIELD_ID, $field);
            } else {
                $this->setValue($field, null);
            }
        }

        return $this;
    }
}
