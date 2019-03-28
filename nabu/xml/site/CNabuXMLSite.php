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
use nabu\data\lang\CNabuLanguage;
use nabu\xml\site\base\CNabuXMLSiteBase;

/**
 * Class to manage a Site instance as an XML branch.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package nabu\xml\site
 */
class CNabuXMLSite extends CNabuXMLSiteBase
{
    protected function setAttributes(SimpleXMLElement $element)
    {
        parent::setAttributes($element);

        $nb_language = $this->nb_data_object->getDefaultLanguage();
        if ($nb_language instanceof CNabuLanguage) {
            $element->addAttribute('defaultLanguage', $nb_language->getHash());
        }

        $nb_language = $this->nb_data_object->getAPILanguage();
        if ($nb_language instanceof CNabuLanguage) {
            $element->addAttribute('APILanguage', $nb_language->getHash());
        }
    }

    protected function setChilds(SimpleXMLElement $element)
    {
        parent::setChilds($element);

        $this->setRedirections($element);
        $this->setTargets($element);
        $this->setMaps($element);
        $this->setStaticContents($element);
    }

    /**
     * Build redirection pointers for special cases.
     * @param SimpleXMLElement $element Parent element to put pages collection.
     */
    private function setRedirections(SimpleXMLElement $element)
    {
        $pages = $element->addChild('redirections');
        $this->setRedirection($pages, 'default', 'default');
        $this->setRedirection($pages, 'page_not_found', 'pageNotFound');
        $this->setRedirection($pages, 'login', 'login');
        $this->setRedirection($pages, 'login_redirection', 'loginRedirection');
        $this->setRedirection($pages, 'logout_redirection', 'logoutRedirection');
        $this->setRedirection($pages, 'alias_not_found', 'aliasNotFound');
        $this->setRedirection($pages, 'alias_locked', 'aliasLocked');
    }

    /**
     * Build a redirection element.
     * @param SimpleXMLElement $element Parent element.
     * @param string $field Field part name in the original data object.
     * @param string $tag_name Tag name of the element.
     */
    private function setRedirection(SimpleXMLElement $element, string $field, string $tag_name)
    {
        $page = $element->addChild($tag_name);
        switch ($this->nb_data_object->getValue("nb_site_${field}_target_use_uri")) {
            case 'T':
                $id = $this->nb_data_object->getValue("nb_site_${field}_target_id");
                if (is_numeric($id) && is_string($hash = $this->grantTargetHash($id))) {
                    $page->addAttribute('useURI', 'T');
                    $page->addAttribute('id', $hash);
                    $this->putAttributesFromList($page, array(
                        "nb_site_${field}_error_code" => "errorCode"
                    ));
                }
                break;
            case 'U':
                $translations = $this->nb_data_object->getTranslations();
                $urls = array();
                $translations->iterate(function($lang, $nb_translation) use(&$urls, $field) {
                    $urls[$lang] = $nb_translation->getValue("nb_site_lang_${field}_target_url");
                    return true;
                });
                if (count($urls) > 0) {
                    $page->addAttribute('useURI', 'U');
                    foreach ($urls as $lang => $url) {
                        if (strlen($url) > 0) {
                            $nb_language = $this->nb_data_object->getLanguage($lang);
                            $address = $page->addChild('url');
                            $address->addAttribute('lang', $nb_language->getHash());
                            $address->addAttribute('url', $url);
                        }
                    }
                }
                $this->putAttributesFromList($page, array(
                    "nb_site_${field}_error_code" => "errorCode"
                ), true);
                break;
        }
    }

    /**
     * Build Target branch.
     * @param SimpleXMLElement $parent Parent XML Element to insert targets.
     */
    private function setTargets(SimpleXMLElement $parent)
    {
        $xml_targets = new CNabuXMLSiteTargetList($this->nb_data_object->getTargets(true));
        $xml_targets->build($parent);
    }

    /**
     * Build Map branch.
     * @param SimpleXMLElement $parent Parent XML Element to insert targets.
     */
    private function setMaps(SimpleXMLElement $parent)
    {
        $xml_targets = new CNabuXMLSiteMapList($this->nb_data_object->getSiteMaps(true));
        $xml_targets->build($parent);
    }

    /**
     * Build Static Content branch.
     * @param SimpleXMLElement $parent Parent XML Element to insert targets.
     */
    private function setStaticContents(SimpleXMLElement $parent)
    {
        $xml_statics = new CNabuXMLSiteStaticContentList($this->nb_data_object->getStaticContents(true));
        $xml_statics->build($parent);
    }

    /**
     * Grant that a target have a valid hash to identify it.
     * @param int $nb_site_target_id Id of the target to grant their hash.
     * @return string Return the hash.
     */
    private function grantTargetHash(int $nb_site_target_id) : string
    {
        $nb_site_target = $this->nb_data_object->getTarget($nb_site_target_id);
        return $nb_site_target->isFetched() ? $nb_site_target->grantHash(true) : null;
    }
}
