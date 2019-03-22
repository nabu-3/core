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

namespace nabu\xml;
use SimpleXMLElement;
use nabu\core\CNabuObject;
use nabu\core\exceptions\ENabuXMLException;
use nabu\data\CNabuDataObject;

/**
 * Abstract class to manage XML elements.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package nabu\xml
 */
abstract class CNabuXMLObject extends CNabuObject
{
    /**
     * Static method to get the Tag Name of this element.
     * @return string Returns the name of the Tag.
     */
    abstract static protected function getTagName() : string;
    /**
     * Abstract method to locate a Data Object.
     * @param SimpleXMLElement $element Element to locate her Data Object.
     * @param CNabuDataObject $data_parent Data Parent object.
     * @return bool Returns true if the Data Object found or false if not.
     */
    abstract protected function locateDataObject(SimpleXMLElement $element, CNabuDataObject $data_parent = null) : bool;
    /**
     * Get attributes from an XML Element passed as parameter.
     * @param SimpleXMLElement $element Element instance to get attributes.
     */
    abstract protected function getAttributes(SimpleXMLElement $element);
    /**
     * Add attributes to XML Element passed as parameter.
     * @param SimpleXMLElement $element Element instance to add attributes.
     */
    abstract protected function setAttributes(SimpleXMLElement $element);
    /**
     * Get childs from an XML Element passed as parameter.
     * @param SimpleXMLElement $element Element instance to get attributes.
     */
    abstract protected function getChilds(SimpleXMLElement $element);
    /**
     * Add child elements to XML Element passed as parameter.
     * @param SimpleXMLElement $element Element instance to add childs.
     */
    abstract protected function setChilds(SimpleXMLElement $element);

    /**
     * Encode structured data in a well formed string as child of $parent Element.
     * @param SimpleXMLElement $parent Parent of this XML node.
     * @return SimpleXMLElement Returns the created XML element.
     */
    public function build(SimpleXMLElement $parent = null) : SimpleXMLElement
    {
        if ($parent === null) {
            $xml = new SimpleXMLElement('<' . get_called_class()::getTagName() . "/>", LIBXML_PARSEHUGE);
        } else {
            $xml = $parent->addChild(get_called_class()::getTagName());
        }
        $this->setAttributes($xml);
        $this->setChilds($xml);

        return $xml;
    }

    /**
     * Parses a XML in a string an extract this structure.
     * @param string $raw XML raw string to be parsed.
     * @throws ENabuXMLException Raises an exception if any error is detected parsing or interpreting the content.
     */
    public function parse(string $raw)
    {
        $root = new SimpleXMLElement($raw);

        $this->collect($root);
    }

    /**
     * Collects data from a XML branch.
     * @param SimpleXMLElement $element Element of XML containing data to be collected.
     * @throws ENabuXMLException Raises an exception if any error is detected collecting the content.
     */
    public function collect(SimpleXMLElement $element)
    {
        if ($element->getName() === get_called_class()::getTagName() && $this->locateDataObject($element)) {
            $this->getAttributes($element);
            $this->getChilds($element);
        } else {
            throw new ENabuXMLException(ENabuXMLException::ERROR_UNEXPECTED_ELEMENT, array($element->getName()));
        }
    }
}
