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

namespace nabu\xml\site;
use SimpleXMLElement;
use nabu\data\security\CNabuRole;
use nabu\data\site\CNabuSite;
use nabu\data\site\CNabuSiteTarget;
use nabu\data\site\CNabuSiteTargetCTA;
use nabu\xml\site\base\CNabuXMLSiteTargetCTARoleBase;

/**
 * Class to manage a Site Target CTA Role instance as an XML branch.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package nabu\xml\site
 */
class CNabuXMLSiteTargetCTARole extends CNabuXMLSiteTargetCTARoleBase
{
    protected function setAttributes(SimpleXMLElement $element)
    {
        parent::setAttributes($element);

        if (($nb_cta = $this->nb_data_object->getSiteTargetCTA()) instanceof CNabuSiteTargetCTA &&
            ($nb_site_target = $nb_cta->getSiteTarget()) instanceof CNabuSiteTarget &&
            ($nb_site = $nb_site_target->getSite()) instanceof CNabuSite &&
            ($nb_role_list = $nb_site->getRoles())->getSize() > 0 &&
            ($nb_role = $nb_role_list->getItem($this->nb_data_object->getRoleId())) instanceof CNabuRole
        ) {
            $element->addAttribute('role', $nb_role->getHash());
        }
    }
}
