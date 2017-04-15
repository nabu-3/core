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
use nabu\xml\CNabuXMLDataObject;

/**
 * Class to manage a Site instance as an XML branch.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package nabu\xml\site
 */
class CNabuXMLSiteMap extends CNabuXMLDataObject
{
    public function __construct(CNabuSiteMap $nb_site_map)
    {
        parent::__construct($nb_site_map);
    }

    protected static function getTagName(): string
    {
        return 'map';
    }

    protected function setAttributes(SimpleXMLElement $element)
    {
        $this->putAttributesFromList(
            $element,
            array(
                'nb_site_map_hash' => 'id',
                'nb_site_map_key' => 'key'
            )
        );
    }

    protected function setChilds(SimpleXMLElement $element)
    {
        if ($this->nb_data_object->getChilds()->getSize() > 0) {
            $list = new CNabuXMLSiteMapList($this->nb_data_object->getChilds());
            $list->build($element);
        }
    }
}
