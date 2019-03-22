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
use nabu\xml\site\base\CNabuXMLSiteLanguageBase;

/**
 * Class to manage a Site Language instance as an XML branch.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package nabu\xml\site
 */
class CNabuXMLSiteLanguage extends CNabuXMLSiteLanguageBase
{
    protected function setChilds(SimpleXMLElement $element)
    {
        parent::setChilds($element);

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
