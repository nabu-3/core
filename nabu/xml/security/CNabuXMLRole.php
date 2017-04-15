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

namespace nabu\xml\security;
use SimpleXMLElement;
use nabu\xml\lang\CNabuXMLTranslated;
use nabu\xml\lang\CNabuXMLTranslationsList;

/**
 * Class to manage a Role instance as an XML branch.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package nabu\xml\security
 */
class CNabuXMLRole extends CNabuXMLTranslated
{
    protected static function getTagName(): string
    {
        return 'role';
    }

    protected function createXMLTranslationsObject(): CNabuXMLTranslationsList
    {
        return new CNabuXMLRoleLanguageList($this->nb_data_object->getTranslations());
    }

    protected function setAttributes(SimpleXMLElement $element)
    {
        $this->putAttributesFromList($element, array(
            'nb_role_hash' => 'GUID',
            'nb_role_key' => 'key',
            'nb_role_root' => 'root'
        ));
    }
}
