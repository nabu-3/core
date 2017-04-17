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
use nabu\data\site\CNabuSite;
use nabu\data\site\CNabuSiteTarget;
use nabu\xml\site\base\CNabuXMLSiteMapBase;

/**
 * Class to manage a Site instance as an XML branch.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package nabu\xml\site
 */
class CNabuXMLSiteMap extends CNabuXMLSiteMapBase
{
    /**
     * @inheritDoc
     */
    protected function setChilds(SimpleXMLElement $element)
    {
        parent::setChilds($element);
        $this->setTarget($element);
        $this->setSecurity($element);
        $this->setMapChilds($element);
    }

    /**
     * Set the target branch.
     * @param SimpleXMLElement $element Parent element to anidate branch.
     */
    private function setTarget(SimpleXMLElement $element)
    {
        $target = $element->addChild('target');
        switch ($this->nb_data_object->getUseURI()) {
            case 'T':
                if (($nb_site = $this->nb_data_object->getSite()) instanceof CNabuSite &&
                    ($nb_site_target = $nb_site->getTarget($this->nb_data_object->getSiteTargetId())) instanceof CNabuSiteTarget
                ) {
                    $target->addAttribute('useURI', 'T');
                    $target->addAttribute('target', $nb_site_target->getHash());
                } else {
                    error_log("Target not exists");
                }
                break;
            case 'U':
                $translations = $this->nb_data_object->getTranslations();
                $urls = array();
                $translations->iterate(function ($lang, $nb_translation) use (&$urls) {
                    $urls[$lang] = array(
                        'url' => $nb_translation->getURL(),
                        'match' => $nb_translation->getMatchURLFragment()
                    );
                    return true;
                });
                if (count($urls) > 0) {
                    $target->addAttribute('useURI', 'U');
                    foreach ($urls as $lang => $url) {
                        if (strlen($url) > 0) {
                            $nb_language = $this->nb_data_object->getLanguage($lang);
                            $address = $target->addChild('url');
                            $address->addAttribute('lang', $nb_language->getHash());
                            $address->addAttribute('url', $url['url']);
                            $address->addAttribute('match', $turl['match']);
                        }
                    }
                }
                break;
        }
    }

    /**
     * Set the security branch.
     * @param SimpleXMLElement $element Parent element to anidate branch.
     */
    private function setSecurity(SimpleXMLElement $element)
    {
        $security = $element->addChild('security');

        $xml_roles = new CNabuXMLSiteMapRoleList($this->nb_data_object->getRoles());
        $xml_roles->build($security);
    }

    /**
     * Set the child maps of this node.
     * @param SimpleXMLElement $element Parent element to anidate branch.
     */
    private function setMapChilds(SimpleXMLElement $element)
    {
        $nb_map_list = $this->nb_data_object->getChilds();
        if ($nb_map_list->getSize() > 0) {
            $xml_childs = new CNabuXMLSiteMapList($nb_map_list);
            $xml_childs->build($element);
        }
    }
}
