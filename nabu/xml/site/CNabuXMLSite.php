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
use nabu\xml\lang\CNabuXMLTranslated;
use nabu\xml\lang\CNabuXMLTranslationsList;

/**
 * Class to manage a Site instance as an XML branch.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package nabu\xml\site
 */
class CNabuXMLSite extends CNabuXMLTranslated
{
    public function __construct(CNabuSite $nb_site)
    {
        parent::__construct($nb_site);
    }

    protected static function getTagName(): string
    {
        return 'site';
    }

    protected function setAttributes(SimpleXMLElement $element)
    {
        $this->putAttributesFromList(
            $element,
            array(
                'nb_site_hash' => 'id',
                'nb_site_key' => 'key',
                'nb_site_http_support' => 'http_support',
                'nb_site_https_support' => 'https_support'
            )
        );
    }

    protected function setChilds(SimpleXMLElement $element)
    {
        parent::setChilds($element);

        $this->setPages($element);
        $list = new CNabuXMLSiteMapList($this->nb_data_object->getSiteMaps());
        $list->build($element);
    }

    protected function createXMLTranslationsObject(): CNabuXMLTranslationsList
    {
        return new CNabuXMLSiteLanguageList($this->nb_data_object->getTranslations());
    }

    /**
     * Build page pointers for special cases.
     * @param SimpleXMLElement $element Parent element to put pages collection.
     */
    private function setPages(SimpleXMLElement $element)
    {
        $pages = $element->addChild('pages');
        $this->setPage($pages, 'default', 'default');
        $this->setPage($pages, 'page_not_found', 'pageNotFound');
        $this->setPage($pages, 'login', 'login');
        $this->setPage($pages, 'login_redirection', 'loginRedirection');
        $this->setPage($pages, 'logout_redirection', 'logoutRedirection');
        $this->setPage($pages, 'alias_not_found', 'aliasNotFound');
        $this->setPage($pages, 'alias_locked', 'aliasLocked');
    }

    /**
     * Build a page element.
     * @param SimpleXMLElement $element Parent element.
     * @param string $field Field part name in the original data object.
     * @param string $tag_name Tag name of the element.
     */
    private function setPage(SimpleXMLElement $element, string $field, string $tag_name)
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
                $page->addAttribute('useURI', 'U');
                $this->putAttributesFromList($page, array(
                    "nb_site_${field}_error_code" => "errorCode"
                ));
                break;
        }
    }

    /**
     * Grant that a target have a valid hash to identify it.
     * @param int $nb_site_target_id Id of the target to grant their hash.
     * @return string Return the hash.
     */
    private function grantTargetHash(int $nb_site_target_id) : string
    {
        $nb_site_target = $this->nb_data_object->getTarget($nb_site_target_id);
        if (!nb_isValidGUID($retval = $this->nb_data_object->getHash())) {
            $this->nb_data_object->setHash($retval = nb_generateGUID());
            $this->nb_data_object->save();
        }

        return $retval;
    }
}
