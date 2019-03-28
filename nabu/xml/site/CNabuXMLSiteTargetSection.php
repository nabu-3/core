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

namespace nabu\xml\site;
use SimpleXMLElement;
use nabu\data\site\CNabuSiteTargetCTA;
use nabu\xml\site\base\CNabuXMLSiteTargetSectionBase;

/**
 * Class to manage a Site Target Section instance as an XML branch.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package nabu\xml\site
 */
class CNabuXMLSiteTargetSection extends CNabuXMLSiteTargetSectionBase
{
    protected function setAttributes(SimpleXMLElement $element)
    {
        parent::setAttributes($element);

        if ($this->nb_data_object->isValueNumeric('nb_site_target_cta_id')) {
            $target = $this->nb_data_object->getSiteTarget();
            $cta = $target->getCTA($this->nb_site_target->getCTAId());
            if ($cta instanceof CNabuSiteTargetCTA) {
                $element->addAttribute('cta', $cta->grantHash(true));
            } else {
                $element->addAttribute('cta', '');
            }
        } else {
            $element->addAttribute('cta', '');
        }
    }
}
