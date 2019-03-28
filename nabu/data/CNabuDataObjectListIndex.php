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

namespace nabu\data;
use nabu\core\CNabuObject;
use nabu\core\exceptions\ENabuCoreException;

/**
 * Manages a secondary index in a list of CNabuDataObjectList.
 * By default, this class manages and index based in a single field which could be number or string.
 * To manage other kind of fields or tuples, this class can be overrided to implement aditional features.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data
 */
class CNabuDataObjectListIndex extends CNabuObject
{
    /**
     * List instance that owns this index.
     * @var CNabuDataObjectList
     */
    protected $list = null;
    /**
     * Name of the index.
     * @var string
     */
    protected $name = null;
    /**
     * Field name to be indexed.
     * @var string
     */
    protected $key_field = null;
    /**
     * Field name of field to order the index. When null, ordering uses the same field as index field.
     * @var string
     */
    protected $order_field = null;
    /**
     * Array collection of index nodes.
     * @var array
     */
    protected $index = null;

    /**
     * Creates the instance.
     * @param CNabuDataObjectList $list List parent of this index.
     * @param string $key_field Key field name to build the index.
     * @param string $key_order Field used to order the index.
     * @param string $name Name of the index.
     */
    public function __construct(CNabuDataObjectList $list, $key_field, $key_order = null, $name = null)
    {
        if (!is_string($key_field) || strlen($key_field) === 0) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                array($key_field, print_r($key_field, true))
            );
        }

        $this->list = $list;
        $this->key_field = $key_field;
        $this->order_field = $key_order;
        $this->name = $name;
    }

    /**
     * Gets the name of the index.
     * @return string Returns the name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the field index name.
     * @return string Returns the field index name.
     */
    public function getIndexedFieldName()
    {
        return $this->key_field;
    }

    /**
     * Gets keys of this index as an array.
     * @return array Returns the array of keys.
     */
    public function getKeys()
    {
        //return is_array($this->list) ? array_keys($this->list) : null;
        return $this->index;
    }

    /**
     * Check if the index is empty.
     * @return bool Return true if the index is empty.
     */
    public function isEmpty()
    {
        return !is_array($this->index) || count($this->index) === 0;
    }

    /**
     * Check if the index have pointers.
     * @return bool Return true if the index have pointers.
     */
    public function isFilled()
    {
        return is_array($this->index) && count($this->index) > 0;
    }

    /**
     * Clears the index.
     */
    public function clear()
    {
        $this->index = null;
    }

    public function contains($key)
    {
        return $this->isFilled() && array_key_exists($key, $this->index);
    }

    /**
     * Extract Nodes list for an item in this node.
     * This method can be overrided in child classes to change the extraction method of nodes.
     * @param CNabuDataObject $item Item of which will extract the nodes.
     * @return array Returns an array of found nodes or null when they are not available nodes.
     */
    protected function extractNodes(CNabuDataObject $item)
    {
        $main_index_name = $this->list->getIndexedFieldName();
        if (($item->isValueNumeric($main_index_name) || $item->isValueGUID($main_index_name)) &&
            ($item->isValueString($this->key_field) || $item->isValueNumeric($this->key_field))
        ) {
            $key = $item->getValue($this->key_field);
            $retval = array(
                'key' => $key,
                'pointer' => $item->getValue($main_index_name)
            );
            if ($item->isValueNumeric($this->order_field) || $item->isValueString($this->order_field)) {
                $retval['order'] = $item->getValue($this->order_field);
            }
            $retval = array($key => $retval);
        } else {
            $retval = null;
        }

        return $retval;
    }

    /**
     * Adds a new item to the index.
     * @param CNabuDataObject $item item to be added.
     * @return CNabuDataObject Returns the item added.
     */
    public function addItem(CNabuDataObject $item)
    {
        if (is_array($nodes = $this->extractNodes($item)) && count($nodes) > 0) {
            if ($this->index === null) {
                $this->index = $nodes;
            } else {
                $this->index = array_merge($this->index, $nodes);
            }
        }
    }

    public function getItemPointer($key)
    {
        return ($this->contains($key) ? $this->index[$key] : false);
    }

    public function removeItem($key)
    {
        $retval = false;

        if ($this->contains($key)) {
            unset($this->index[$key]);
            $retval = true;
        }
    }
}
