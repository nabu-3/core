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

/**
 * Abstract class to manage XML elements.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package nabu\xml
 */
abstract class CNabuXMLDataObject extends CNabuXMLObject
{
    /** @var CNabuDataObject $nb_data_object Data Object instance. */
    protected $nb_data_object = null;

    public function __construct(CNabuDataObject $nb_data_object)
    {
        parent::__construct();
        $this->nb_data_object = $nb_data_object;
    }

    /**
     * Set a group of attributes listed in an array.
     * @param SimpleXMLElement $element Element instance to set attributes.
     * @param array $attributes Associative array with the list of data fields as keys and the attribute name as value.
     * @return int Returns the number of attributes setted.
     */
    protected function putAttributesFromList(SimpleXMLElement $element, array $attributes) : int
    {
        $count = 0;

        if (count($attributes) > 0) {
            foreach ($attributes as $field => $attr) {
                if ($this->nb_data_object->contains($field)) {
                    $element->addAttribute($attr, $this->nb_data_object->getValue($field));
                    $count++;
                }
            }
        }

        return $count;
    }
}
