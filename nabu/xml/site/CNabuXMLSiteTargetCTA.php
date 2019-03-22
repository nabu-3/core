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
use nabu\core\exceptions\ENabuCoreException;
use nabu\data\site\CNabuSiteTarget;
use nabu\xml\site\base\CNabuXMLSiteTargetCTABase;

/**
 * Class to manage a Site Target CTA instance as an XML branch.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package nabu\xml\site
 */
class CNabuXMLSiteTargetCTA extends CNabuXMLSiteTargetCTABase
{
    protected function setChilds(SimpleXMLElement $element)
    {
        parent::setChilds($element);

        $link = $element->addChild('link');
        switch ($this->nb_data_object->getTargetUseURI()) {
            case 'T':
                $nb_target = $this->nb_data_object->getCTATarget();
                if ($nb_target instanceof CNabuSiteTarget) {
                    $this->putAttributesFromList($link, array(
                        'nb_site_target_cta_target_use_uri' => 'useURI'
                    ));
                    $link->addAttribute('target', ($nb_target !== null ? $nb_target->grantHash(true) : ''));
                }
                break;
            case 'U':
                $this->putAttributesFromList($link, array(
                    'nb_site_target_cta_target_use_uri' => 'useURI'
                ));
                throw new ENabuCoreException(ENabuCoreException::ERROR_FEATURE_NOT_IMPLEMENTED);
                break;
        }

        $security = $element->addChild('security');
        $xml_roles = new CNabuXMLSiteTargetCTARoleList($this->nb_data_object->getRoles());
        $xml_roles->build($security);
    }
}
