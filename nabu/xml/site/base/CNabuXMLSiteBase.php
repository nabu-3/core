<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2019/10/10 11:55:53 UTC
 * ===========================================================================
 * Copyright 2009-2011 Rafael Gutierrez Martinez
 * Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 * Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 * Copyright 2017-2019 nabu-3 Group
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace nabu\xml\site\base;

use \nabu\data\CNabuDataObject;
use \nabu\data\site\CNabuSite;
use \nabu\xml\lang\CNabuXMLTranslated;
use \nabu\xml\lang\CNabuXMLTranslationsList;
use \nabu\xml\site\CNabuXMLSiteLanguageList;
use \SimpleXMLElement;

/**
 * Class to manage the Site as a XML branch.
 * @author Rafael Gutiérrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\xml\site\base
 */
abstract class CNabuXMLSiteBase extends CNabuXMLTranslated
{
    /**
     * Instantiates the class. Receives as parameter a qualified CNabuSite class.
     * @param CNabuSite $nb_site $this->entity_name instance to be managed as XML
     */
    public function __construct(CNabuSite $nb_site = null)
    {
        parent::__construct($nb_site);
    }

    /**
     * Static method to get the Tag name of this XML Element.
     * @return string Return the Tag name.
     */
    protected static function getTagName() : string
    {
        return 'site';
    }

    /**
     * Creates the XML instance to manage the translations list of this Element.
     * @return CNabuXMLTranslationsList Returns the created instance.
     */
    protected function createXMLTranslationsObject() : CNabuXMLTranslationsList
    {
        return new CNabuXMLSiteLanguageList($this->nb_data_object->getTranslations());
    }

    /**
     * Locate a Data Object.
     * @param SimpleXMLElement $element Element to locate the Data Object.
     * @param CNabuDataObject $data_parent Data Parent object.
     * @return bool Returns true if the Data Object found or false if not.
     */
    protected function locateDataObject(SimpleXMLElement $element, CNabuDataObject $data_parent = null) : bool
    {
        $retval = false;
        
        if (isset($element['GUID'])) {
            $guid = (string)$element['GUID'];
            if (!($this->nb_data_object instanceof CNabuSite)) {
                $this->nb_data_object = CNabuSite::findByHash($guid);
            } else {
                $this->nb_data_object = null;
            }
        
            if (!($this->nb_data_object instanceof CNabuSite)) {
                $this->nb_data_object = new CNabuSite();
                $this->nb_data_object->setHash($guid);
            }
            $retval = true;
        }
        
        return $retval;
    }

    /**
     * Get default attributes of Site from XML Element.
     * @param SimpleXMLElement $element XML Element to get attributes
     */
    protected function getAttributes(SimpleXMLElement $element)
    {
        $this->getAttributesFromList($element, array(
            'nb_site_key' => 'key',
            'nb_site_http_support' => 'http_support',
            'nb_site_https_support' => 'https_support'
        ), false);
    }

    /**
     * Set default attributes of Site in XML Element.
     * @param SimpleXMLElement $element XML Element to set attributes
     */
    protected function setAttributes(SimpleXMLElement $element)
    {
        $element->addAttribute('GUID', $this->nb_data_object->grantHash(true));
        $this->putAttributesFromList($element, array(
            'nb_site_key' => 'key',
            'nb_site_http_support' => 'http_support',
            'nb_site_https_support' => 'https_support'
        ), false);
    }

    /**
     * Get default childs of Site from XML Element as Element > CDATA structure.
     * @param SimpleXMLElement $element XML Element to get childs
     */
    protected function getChilds(SimpleXMLElement $element)
    {
        parent::getChilds($element);
    }

    /**
     * Set default childs of Site XML Element as Element > CDATA structure.
     * @param SimpleXMLElement $element XML Element to set childs
     */
    protected function setChilds(SimpleXMLElement $element)
    {
        parent::setChilds($element);
    }
}
