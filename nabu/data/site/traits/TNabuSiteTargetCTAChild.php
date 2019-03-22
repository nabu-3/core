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

namespace nabu\data\site\traits;
use nabu\data\CNabuDataObject;
use nabu\data\site\CNabuSiteTargetCTA;

/**
 * This trait implements default actions to manage a Site Target CTA child object in nabu-3.
 * You can apply this trait to your own classes to speed up your development, or create your own management.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site\traits;
 */
trait TNabuSiteTargetCTAChild
{
    /**
     * Site Target CTA Instance
     * @var CNabuSiteTargetCTA
     */
    private $nb_site_target_cta = null;

    /**
     * Gets the Site Target CTA instance.
     * @return CNabuSiteTargetCTA|null Returns the Site Target CTA instance if exists or null if not exists.
     */
    public function getSiteTargetCTA()
    {
        return $this->nb_site_target_cta;
    }

    /**
     * Sets the Site Target CTA instance that owns this object and sets the field containing the site id.
     * @param CNabuSiteTargetCTA $nb_site_target_cta Site Target CTA instance to be setted.
     * @param string $field Field name where the site id will be stored.
     * @return mixed Returns $this to allow the cascade chain of setters.
     */
    public function setSiteTargetCTA(CNabuSiteTargetCTA $nb_site_target_cta, $field = NABU_SITE_TARGET_CTA_FIELD_ID)
    {
        $this->nb_site_target_cta = $nb_site_target_cta;
        if ($this instanceof CNabuDataObject) {
            $this->transferValue($nb_site_target_cta, NABU_SITE_TARGET_CTA_FIELD_ID, $field);
        }

        return $this;
    }

}
