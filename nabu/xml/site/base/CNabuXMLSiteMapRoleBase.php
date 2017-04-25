<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/04/25 15:22:12 UTC
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

use \nabu\data\site\CNabuSiteMapRole;
use \nabu\xml\CNabuXMLDataObject;
use \SimpleXMLElement;

/**
 * Class to manage the Site Map Role as a XML branch.
 * @author Rafael Gutiérrez Martínez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\xml\site\base
 */
abstract class CNabuXMLSiteMapRoleBase extends CNabuXMLDataObject
{
    /**
     * Instantiates the class. Receives as parameter a qualified CNabuSiteMapRole class.
     * @param CNabuSiteMapRole $nb_site_map_role $this->entity_name instance to be managed as XML
     */
    public function __construct(CNabuSiteMapRole $nb_site_map_role)
    {
        parent::__construct($nb_site_map_role);
    }

    /**
     * Static method to get the Tag name of this XML Element.
     * @return string Return the Tag name.
     */
    protected static function getTagName() : string
    {
        return 'role';
    }

    /**
     * Set default attributes of Site Map Role XML Element.
     * @param SimpleXMLElement $element XML Element to set attributes
     */
    protected function setAttributes(SimpleXMLElement $element)
    {
        $this->putAttributesFromList($element, array(
            'nb_site_map_role_zone' => 'zone'
        ), false);
    }

    /**
     * Set default childs of Site Map Role XML Element as Element > CDATA structure.
     * @param SimpleXMLElement $element XML Element to set childs
     */
    protected function setChilds(SimpleXMLElement $element)
    {
    }
}
