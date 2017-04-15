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

namespace nabu\xml;
use SimpleXMLElement;
use nabu\data\CNabuDataObject;
use nabu\data\CNabuDataObjectList;

/**
 * Abstract class to manage Lists as XML branches.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package nabu\xml
 */
abstract class CNabuXMLDataObjectList extends CNabuXMLObject
{
    /** @var CNabuDataObjectList $list List of entities to convert from/to XML. */
    protected $list = null;

    /**
     * Create the XML Child object filled with their Data Object.
     * @param CNabuDataObject $nb_child Child data object.
     * @return CNabuXMLDataObject Returns a XML instance with the child data object instance.
     */
    abstract protected function createXMLChildObject(CNabuDataObject $nb_child) : CNabuXMLDataObject;

    public function __construct(CNabuDataObjectList $list)
    {
        $this->list = $list;
    }
    protected function setAttributes(SimpleXMLElement $element)
    {
        $element->addAttribute('count', $this->list->getSize());
    }

    protected function setChilds(SimpleXMLElement $element)
    {
        $this->list->iterate(function ($key, $nb_child) use ($element) {
            $xml = $this->createXMLChildObject($nb_child);
            $xml->build($element);
            return true;
        });
    }
}
