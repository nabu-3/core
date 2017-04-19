<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/04/19 12:55:32 UTC
 * ===========================================================================
 * Copyright 2009-2011 Rafael Gutierrez Martinez
 * Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 * Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 * Copyright 2017 nabu-3 Group
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

use \nabu\data\site\CNabuSiteStaticContent;
use \nabu\xml\lang\CNabuXMLTranslated;
use \nabu\xml\lang\CNabuXMLTranslationsList;
use \nabu\xml\site\CNabuXMLSiteStaticContentLanguageList;
use \SimpleXMLElement;

/**
 * Class to manage the Site Static Content as a XML branch.
 * @author Rafael Gutiérrez Martínez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\xml\site\base
 */
abstract class CNabuXMLSiteStaticContentBase extends CNabuXMLTranslated
{
    /**
     * Instantiates the class. Receives as parameter a qualified CNabuSiteStaticContent class.
     * @param CNabuSiteStaticContent $nb_site_static_content $this->entity_name instance to be managed as XML
     */
    public function __construct(CNabuSiteStaticContent $nb_site_static_content)
    {
        parent::__construct($nb_site_static_content);
    }

    /**
     * Static method to get the Tag name of this XML Element.
     * @return string Return the Tag name.
     */
    protected static function getTagName() : string
    {
        return 'staticContent';
    }

    /**
     * Creates the XML instance to manage the translations list of this Element.
     * @return CNabuXMLTranslationsList Returns the created instance.
     */
    protected function createXMLTranslationsObject() : CNabuXMLTranslationsList
    {
        return new CNabuXMLSiteStaticContentLanguageList($this->nb_data_object->getTranslations());
    }

    /**
     * Set default attributes of Site Static Content XML Element.
     * @param SimpleXMLElement $element XML Element to set attributes
     */
    protected function setAttributes(SimpleXMLElement $element)
    {
        $this->putAttributesFromList($element, array(
            'nb_site_static_content_key' => 'key',
            'nb_site_static_content_type' => 'type',
            'nb_site_static_content_use_alternative' => 'alternative'
        ), false);
    }

    /**
     * Set default childs of Site Static Content XML Element as Element > CDATA structure.
     * @param SimpleXMLElement $element XML Element to set childs
     */
    protected function setChilds(SimpleXMLElement $element)
    {
        parent::setChilds($element);
        
        $this->putChildsAsCDATAFromList($element, array(
            'nb_site_static_content_notes' => 'notes'
        ), false);
    }
}