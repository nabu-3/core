<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/04/26 08:35:27 UTC
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

namespace nabu\xml\security\base;

use \nabu\data\CNabuDataObject;
use \nabu\data\security\CNabuRoleLanguage;
use \nabu\xml\lang\CNabuXMLTranslation;
use \SimpleXMLElement;

/**
 * Class to manage the Role Language as a XML branch.
 * @author Rafael Gutiérrez Martínez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\xml\security\base
 */
abstract class CNabuXMLRoleLanguageBase extends CNabuXMLTranslation
{
    /**
     * Instantiates the class. Receives as parameter a qualified CNabuRoleLanguage class.
     * @param CNabuRoleLanguage $nb_role_lang $this->entity_name instance to be managed as XML
     */
    public function __construct(CNabuRoleLanguage $nb_role_lang = null)
    {
        parent::__construct($nb_role_lang);
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
            if (!($this->nb_data_object instanceof CNabuRoleLanguage)) {
                $this->nb_data_object = CNabuRoleLanguage::findByHash($guid);
            } else {
                $this->nb_data_object = null;
            }
        
            if (!($this->nb_data_object instanceof CNabuRoleLanguage)) {
                $this->nb_data_object = new CNabuRoleLanguage();
                $this->nb_data_object->setHash($guid);
            }
            $retval = true;
        }
        
        return $retval;
    }

    /**
     * Set default attributes of Role Language XML Element.
     * @param SimpleXMLElement $element XML Element to set attributes
     */
    protected function setAttributes(SimpleXMLElement $element)
    {
        $nb_parent = $this->nb_data_object->getTranslatedObject();
        if ($nb_parent !== null) {
            $nb_language = $nb_parent->getLanguage($this->nb_data_object->getLanguageId());
            $element->addAttribute('lang', $nb_language->grantHash(true));
        }
    }

    /**
     * Get default childs of Role Language from XML Element as Element > CDATA structure.
     * @param SimpleXMLElement $element XML Element to get childs
     */
    protected function getChilds(SimpleXMLElement $element)
    {
        $this->getChildsAsCDATAFromList($element, array(
            'nb_role_lang_name' => 'name'
        ), false);
    }

    /**
     * Set default childs of Role Language XML Element as Element > CDATA structure.
     * @param SimpleXMLElement $element XML Element to set childs
     */
    protected function setChilds(SimpleXMLElement $element)
    {
        $this->putChildsAsCDATAFromList($element, array(
            'nb_role_lang_name' => 'name'
        ), false);
    }
}