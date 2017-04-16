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
use nabu\xml\site\base\CNabuXMLSiteTargetBase;

/**
 * Class to manage a Site Target instance as an XML branch.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package nabu\xml\site
 */
class CNabuXMLSiteTarget extends CNabuXMLSiteTargetBase
{
    protected function setChilds(SimpleXMLElement $element)
    {
        $scope = $element->addChild('scope');
        $this->putAttributesFromList($scope, array(
            'nb_site_target_zone' => 'zone',
            'nb_site_target_use_http' => 'useHTTP',
            'nb_site_target_use_https' => 'useHTTPS'
        ));
        $request = $element->addChild('request');
        $this->putAttributesFromList($request, array(
            'nb_site_target_url_filter' => 'URLFilter'
        ));
        $response = $element->addChild('response');
        $this->putAttributesFromList($response, array(
            'nb_mimetype_id' => 'mimetype',
            'nb_site_target_output_type' => 'outputType'
        ));
        $appearance = $element->addChild('appearance');
        $this->putAttributesFromList($appearance, array(
            'nb_site_target_script_file' => 'scriptFile',
            'nb_site_target_css_file' => 'cssFile',
            'nb_site_target_css_class' => 'cssClass'
        ));
        $cache = $element->addChild('cache');
        $this->putAttributesFromList($cache, array(
            'nb_site_target_dynamic_cache_control' => 'dynCacheControl',
            'nb_site_target_dynamic_cache_max_age' => 'dynMaxAge'
        ));

        parent::setChilds($element);
    }
}
