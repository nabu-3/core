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
use nabu\core\CNabuObject;

/**
 * Abstract class to manage XML elements.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
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
     * Add attributes to XML Element passed as parameter.
     * @param SimpleXMLElement $element Element instance to add attributes.
     */
    abstract protected function setAttributes(SimpleXMLElement $element);
    /**
     * Add child elements to XML Element passed as parameter.
     * @param SimpleXMLElement $element Element instance to add childs.
     */
    abstract protected function setChilds(SimpleXMLElement $element);

    /**
     * Encode structured data in a well formed string as child of $parent Element.
     * @param SimpleXMLElement $parent Parent of this XML node.
     */
    public function build(SimpleXMLElement $parent)
    {
        $xml = $parent->addChild(get_called_class()::getTagName());
        $this->setAttributes($xml);
        $this->setChilds($xml);
    }
}
