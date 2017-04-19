<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/04/19 12:55:19 UTC
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

use \nabu\data\site\CNabuSiteTarget;
use \nabu\xml\lang\CNabuXMLTranslated;
use \nabu\xml\lang\CNabuXMLTranslationsList;
use \nabu\xml\site\CNabuXMLSiteTargetLanguageList;
use \SimpleXMLElement;

/**
 * Class to manage the Site Target as a XML branch.
 * @author Rafael Gutiérrez Martínez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\xml\site\base
 */
abstract class CNabuXMLSiteTargetBase extends CNabuXMLTranslated
{
    /**
     * Instantiates the class. Receives as parameter a qualified CNabuSiteTarget class.
     * @param CNabuSiteTarget $nb_site_target $this->entity_name instance to be managed as XML
     */
    public function __construct(CNabuSiteTarget $nb_site_target)
    {
        parent::__construct($nb_site_target);
    }

    /**
     * Static method to get the Tag name of this XML Element.
     * @return string Return the Tag name.
     */
    protected static function getTagName() : string
    {
        return 'target';
    }

    /**
     * Creates the XML instance to manage the translations list of this Element.
     * @return CNabuXMLTranslationsList Returns the created instance.
     */
    protected function createXMLTranslationsObject() : CNabuXMLTranslationsList
    {
        return new CNabuXMLSiteTargetLanguageList($this->nb_data_object->getTranslations());
    }

    /**
     * Set default attributes of Site Target XML Element.
     * @param SimpleXMLElement $element XML Element to set attributes
     */
    protected function setAttributes(SimpleXMLElement $element)
    {
        $element->addAttribute('GUID', $this->nb_data_object->grantHash(true));
        $this->putAttributesFromList($element, array(
            'nb_site_target_key' => 'key',
            'nb_site_target_order' => 'order',
            'nb_site_target_begin_date' => 'beginDate',
            'nb_site_target_plugin_name' => 'plugin',
            'nb_site_target_php_trace' => 'trace',
            'nb_site_target_use_commerce' => 'useCommerce'
        ), false);
    }

    /**
     * Set default childs of Site Target XML Element as Element > CDATA structure.
     * @param SimpleXMLElement $element XML Element to set childs
     */
    protected function setChilds(SimpleXMLElement $element)
    {
        parent::setChilds($element);
        
        $this->putChildsAsCDATAFromList($element, array(
            'nb_site_target_attributes' => 'attributes'
        ), false);
    }
}