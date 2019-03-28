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
use nabu\core\interfaces\INabuHashed;
use nabu\data\CNabuDataObject;

/**
 * Abstract class to manage XML elements.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package nabu\xml
 */
abstract class CNabuXMLDataObject extends CNabuXMLObject
{
    /** @var CNabuDataObject $nb_data_object Data Object instance. */
    protected $nb_data_object = null;

    public function __construct(CNabuDataObject $nb_data_object = null)
    {
        parent::__construct();
        $this->nb_data_object = $nb_data_object;
        if ($nb_data_object instanceof INabuHashed) {
            $nb_data_object->grantHash(true);
        }
    }

    /**
     * Gets the Data Object managed by this XML node.
     * @return CNabuDataObject|null Returns a Data Object instance if exists or null if not.
     */
    public function getDataObject()
    {
        return $this->nb_data_object;
    }

    /**
     * Get a group of attributes listed in an array.
     * @param SimpleXMLElement $element Element instance to get attributes.
     * @param array $attributes Associative array with the list of data fields as keys and the attribute name as value.
     * @param bool $ignore_empty If true, empty values are ignored (null, '')
     * @return int Returns the number of attributes setted.
     */
    protected function getAttributesFromList(
        SimpleXMLElement $element,
        array $attributes,
        bool $ignore_empty = false
    ) : int {
        $count = 0;

        if (count($attributes) > 0) {
            foreach ($attributes as $field => $attr) {
                if (isset($element[$attr])) {
                    $value = (string)$element[$attr];
                    if ($ignore_empty || strlen($value) > 0) {
                        $this->nb_data_object->setValue($field, $value);
                        $count++;
                    }
                }
            }
        }

        return $count;
    }

    /**
     * Set a group of attributes listed in an array.
     * @param SimpleXMLElement $element Element instance to set attributes.
     * @param array $attributes Associative array with the list of data fields as keys and the attribute name as value.
     * @param bool $ignore_empty If true, empty values are ignored (null, '')
     * @return int Returns the number of attributes setted.
     */
    protected function putAttributesFromList(
        SimpleXMLElement $element,
        array $attributes,
        bool $ignore_empty = false
    ) : int {
        $count = 0;

        if (count($attributes) > 0) {
            foreach ($attributes as $field => $attr) {
                if ($this->nb_data_object->contains($field) &&
                    (!$ignore_empty ||
                     !$this->nb_data_object->isValueNull($field) ||
                     !$this->nb_data_object->isValueEmptyString($field)
                    )
                ) {
                    $element->addAttribute($attr, $this->nb_data_object->getValue($field));
                    $count++;
                }
            }
        }

        return $count;
    }

    /**
     * Gets a group of childs listed in an array as Element > CDATA structure.
     * @param SimpleXMLElement $element Element instance to get childs.
     * @param array $childs Associative array with the list of data fields as keys and the element name as value.
     * @param bool $ignore_empty If true, empty values are ignored (null, '')
     * @return int Returns the number of childs setted.
     */
    protected function getChildsAsCDATAFromList(
        SimpleXMLElement $element,
        array $childs,
        bool $ignore_empty = false
    ) : int {
        return 0;
    }

    /**
     * Set a group of childs listed in an array as Element > CDATA structure.
     * @param SimpleXMLElement $element Element instance to set childs.
     * @param array $childs Associative array with the list of data fields as keys and the element name as value.
     * @param bool $ignore_empty If true, empty values are ignored (null, '')
     * @return int Returns the number of childs setted.
     */
    protected function putChildsAsCDATAFromList(
        SimpleXMLElement $element,
        array $childs,
        bool $ignore_empty = false
    ) : int {
        $count = 0;

        if (count($childs) > 0) {
            foreach ($childs as $field => $child) {
                if ($this->nb_data_object->contains($field) &&
                    (!$ignore_empty ||
                     !$this->nb_data_object->isValueNull($field) ||
                     !$this->nb_data_object->isValueEmptyString($field)
                    )
                ) {
                    $element->addChild($child, $this->packCDATA($this->nb_data_object->getValue($field)));
                    $count++;
                }
            }
        }

        return $count;
    }

    /**
     * Transform String to CDATA.
     * @param string $source Source string.
     * @return string Returns packed CDATA string.
     */
    protected function packCDATA(string $source = null) : string
    {
        return ($source === null
                ? '<![CDATA[]]>'
                : "<![CDATA[$source]]>"
        );
    }

    public function collect(SimpleXMLElement $element)
    {
        parent::collect($element);

        error_log('====> ' . get_called_class() . ' <====');
        if ($this->nb_data_object !== null) {
            error_log('====> ' . print_r($this->nb_data_object->getTreeData(null, true), true));
        } else {
            error_log("====> NULL Object found in class " . get_called_class());
        }
    }
}
