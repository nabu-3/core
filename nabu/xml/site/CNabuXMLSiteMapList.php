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
use nabu\data\site\CNabuSiteMap;
use nabu\xml\CNabuXMLDataObjectList;

/**
 * Class to manage Site Map List as an XML branch.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package nabu\xml\site
 */
class CNabuXMLSiteMapList extends CNabuXMLDataObjectList
{
    protected static function getTagName(): string
    {
        return 'SiteMaps';
    }

    protected function setAttributes(SimpleXMLElement $element)
    {
        $element->addAttribute('count', $this->list->getSize());
    }

    protected function setChilds(SimpleXMLElement $element)
    {
        $this->list->iterate(function ($key, CNabuSiteMap $nb_site_map) use ($element) {
            $nb_site_map->refresh(true, true);
            if (!nb_isValidGUID($nb_site_map->getHash())) {
                $nb_site_map->setHash(nb_generateGUID());
                $nb_site_map->save();
            }
            $xml_site_map = new CNabuXMLSiteMap($nb_site_map);
            $xml_site_map->build($element);
            return true;
        });
    }
}
