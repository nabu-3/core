<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2019/10/10 11:56:08 UTC
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
use \nabu\data\site\CNabuSiteStaticContentList;
use \nabu\xml\CNabuXMLDataObject;
use \nabu\xml\CNabuXMLDataObjectList;
use \nabu\xml\site\CNabuXMLSiteStaticContent;
use \SimpleXMLElement;

/**
 * Class to manage the Site Static Content List as a XML branch.
 * @author Rafael Gutiérrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\xml\site\base
 */
abstract class CNabuXMLSiteStaticContentListBase extends CNabuXMLDataObjectList
{
    /**
     * Instantiates the class. Receives as parameter a qualified CNabuSiteStaticContentList class.
     * @param CNabuSiteStaticContentList $nb_site_static_content $this->entity_name instance to be managed as XML
     */
    public function __construct(CNabuSiteStaticContentList $nb_site_static_content)
    {
        parent::__construct($nb_site_static_content);
    }

    /**
     * Static method to get the Tag name of this XML Element.
     * @return string Return the Tag name.
     */
    protected static function getTagName() : string
    {
        return 'staticContents';
    }

    /**
     * Create the XML Child object filled with their Data Object.
     * @param CNabuDataObject $nb_child Child data object.
     * @return CNabuXMLDataObject Returns a XML instance with the child data object instance.
     */
    protected function createXMLChildObject(CNabuDataObject $nb_child = null) : CNabuXMLDataObject
    {
        return new CNabuXMLSiteStaticContent($nb_child);
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
            if (!($this->nb_data_object instanceof CNabuSiteStaticContentList)) {
                $this->nb_data_object = CNabuSiteStaticContentList::findByHash($guid);
            } else {
                $this->nb_data_object = null;
            }
        
            if (!($this->nb_data_object instanceof CNabuSiteStaticContentList)) {
                $this->nb_data_object = new CNabuSiteStaticContentList();
                $this->nb_data_object->setHash($guid);
            }
            $retval = true;
        }
        
        return $retval;
    }
}
