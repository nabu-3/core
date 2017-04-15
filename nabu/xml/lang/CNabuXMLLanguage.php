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

namespace nabu\xml\lang;
use SimpleXMLElement;
use nabu\xml\CNabuXMLDataObject;

/**
 * Class to manage a Language instance as an XML branch.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package nabu\xml\lang
 */
class CNabuXMLLanguage extends CNabuXMLDataObject
{
    protected static function getTagName(): string
    {
        return 'language';
    }

    protected function setAttributes(SimpleXMLElement $element)
    {
        $this->putAttributesFromList($element, array(
            'nb_language_hash' => 'GUID',
            'nb_language_type' => 'type',
            'nb_language_enabled' => 'enabled',
            'nb_language_ISO639_1' => 'ISO639v1',
            'nb_language_ISO639_2' => 'ISO639v2',
            'nb_language_is_api' => 'isAPI',
            'nb_language_default_country_code' => 'defaultCountryCode',
            'nb_language_flag_url' => 'flagURL'
        ), true);
    }

    protected function setChilds(SimpleXMLElement $element)
    {
        $element->addChild('name', $this->nb_data_object->getName());
    }
}
