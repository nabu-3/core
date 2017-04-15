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
use nabu\xml\lang\CNabuXMLTranslation;

/**
 * Class to manage a Site Language instance as an XML branch.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package nabu\xml\site
 */
class CNabuXMLSiteLanguage extends CNabuXMLTranslation
{
    protected function setAttributes(SimpleXMLElement $element)
    {
        $nb_site = $this->nb_data_object->getTranslatedObject();
        if ($nb_site !== null) {
            $nb_language = $nb_site->getLanguage($this->nb_data_object->getLanguageId());
            $element->addAttribute('lang', $nb_language->getHash());
            $this->putAttributesFromList($element, array(
                'nb_site_lang_enabled' => 'enabled',
                'nb_site_lang_translation_status' => 'status',
                'nb_site_lang_order' => 'order',
                'nb_site_lang_editable' => 'editable'
            ));
        } else {
            error_log("**** No site found for translation: " . $this->nb_data_object->getLanguageId());
        }
    }

    protected function setChilds(SimpleXMLElement $element)
    {
        $element->addChild('name', $this->packCDATA($this->nb_data_object->getName()));
        
        $formats = $element->addChild('formats');
        $fdatetime = $formats->addChild('datetime');
        $this->putAttributesFromList($fdatetime, array(
            'nb_site_lang_short_datetime_format' => 'short',
            'nb_site_lang_middle_datetime_format' => 'middle',
            'nb_site_lang_full_datetime_format' => 'full'
        ));
        $fdate = $formats->addChild('date');
        $this->putAttributesFromList($fdate, array(
            'nb_site_lang_short_date_format' => 'short',
            'nb_site_lang_middle_date_format' => 'middle',
            'nb_site_lang_full_date_format' => 'full'
        ));
        $ftime = $formats->addChild('time');
        $this->putAttributesFromList($ftime, array(
            'nb_site_lang_full_time_format' => 'full'
        ));
    }
}
