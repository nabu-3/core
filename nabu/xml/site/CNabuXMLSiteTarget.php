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
        parent::setChilds($element);

        $this->setScope($element);
        $this->setRequest($element);
        $this->setResponse($element);
        $this->setSEO($element);
        $this->setAppearance($element);
        $this->setCache($element);
        $this->setModules($element);
        $this->setApps($element);

        $sections = new CNabuXMLSiteTargetSectionList($this->nb_data_object->getSections(true));
        $sections->build($element);

        $ctas = new CNabuXMLSiteTargetCTAList($this->nb_data_object->getCTAs(true));
        $ctas->build($element);
    }

    private function setScope(SimpleXMLElement $element)
    {
        $scope = $element->addChild('scope');
        $this->putAttributesFromList($scope, array(
            'nb_site_target_zone' => 'zone',
            'nb_site_target_use_http' => 'useHTTP',
            'nb_site_target_use_https' => 'useHTTPS'
        ));
    }

    private function setRequest(SimpleXMLElement $element)
    {
        $request = $element->addChild('request');
        $this->putAttributesFromList($request, array(
            'nb_site_target_url_filter' => 'URLFilter',
            'nb_site_target_commands' => 'commands'
        ));
    }

    private function setResponse(SimpleXMLElement $element)
    {
        $response = $element->addChild('response');
        $this->putAttributesFromList($response, array(
            'nb_mimetype_id' => 'mimetype',
            'nb_site_target_output_type' => 'outputType'
        ));
    }

    private function setSEO(SimpleXMLElement $element)
    {
        $seo = $element->addChild('seo');
        $this->putAttributesFromList($seo, array(
            'nb_site_target_meta_robots' => 'robots'
        ));
        $metas = $seo->addChild('metas');
        $translations = $this->nb_data_object->getTranslations();
        $translations->iterate(function ($key, $nb_translation) use($metas) {
            $nb_language = $this->nb_data_object->getLanguage($key);
            $meta = $metas->addChild('meta');
            $meta->addAttribute('lang', $nb_language->getHash());
            $meta->addChild('title', $this->packCDATA($nb_translation->getHeadTitle()));
            $meta->addChild('description', $this->packCDATA($nb_translation->getMetaDescription()));
            $meta->addChild('keywords', $this->packCDATA($nb_translation->getMetaKeywords()));
            return true;
        });
    }

    private function setAppearance(SimpleXMLElement $element)
    {
        $appearance = $element->addChild('appearance');
        $appearance->addChild('script')->addAttribute('file', $this->nb_data_object->getScriptFile());
        $this->putAttributesFromList($appearance->addChild('css'), array(
            'nb_site_target_css_file' => 'file',
            'nb_site_target_css_class' => 'class'
        ));
        $appearance->addChild('icon')->addAttribute('ref', $this->nb_data_object->getIcon());
    }

    private function setCache(SimpleXMLElement $element)
    {
        $cache = $element->addChild('cache');
        $this->putAttributesFromList($cache, array(
            'nb_site_target_dynamic_cache_control' => 'dynCacheControl',
            'nb_site_target_dynamic_cache_max_age' => 'dynMaxAge'
        ));
    }

    private function setModules(SimpleXMLElement $element)
    {
        $modules = $element->addChild('modules');
        $smarty = $modules->addChild('module');
        $smarty->addAttribute('provider', 'Smarty');
        $smarty->addAttribute('module', 'Smarty');
        $smarty->addAttribute('status', $this->nb_data_object->getSmartyDebugging());
        $smarty->addChild('display')->addAttribute('file', $this->nb_data_object->getSmartyDisplayFile());
        $smarty->addChild('content')->addAttribute('file', $this->nb_data_object->getSmartyContentFile());
    }

    private function setApps(SimpleXMLElement $element)
    {
        $apps = $element->addChild('apps');
        $this->setAttributes($apps, array(
            'nb_site_target_use_apps' => 'useApps',
            'nb_site_target_apps_slot' => 'slot'
        ));
    }
}
