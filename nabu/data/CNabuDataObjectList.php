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
use nabu\data\CNabuDataObject;

/**
 * Manages a list of instances which they are derived form CNabuDataObject
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data
 */
abstract class CNabuDataObjectList extends CNabuObject
{
    /**
     * Associative array containing all objects in the list.
     * @var array
     */
    private $list = null;
    /**
     * Main index field to index all objects in the primary list.
     * @var string
     */
    private $index_field = false;
    /**
     * Multilevel associative array which includes all defined secondary indexes.
     * @var array
     */
    private $secondary_indexes = false;
    /**
     * Owner of this list.
     * @var CNabuDataObject
     */
    private $nb_owner = null;

    /**
     * Creates secondary indexes if needed.
     */
    abstract protected function createSecondaryIndexes();
    /**
     * This abstract method is called internally by getItem() or findByIndex() when the item does not exists
     * in $list collection. All classes extending this class needs to implement this method.
     * If we do not want to acquire/retrieve an object, only return false as result.
     * @param string $key Id of the item to be acquired.
     * @param string $index Secondary index to be used if needed.
     * @return mixed Returns a CNabuDataObject instance if acquired or false if not.
     */
    abstract protected function acquireItem($key, $index = false);

    /**
     * Creates the instance and initiates the secondary indexes list
     * @param string $index_field Field index to be used for main indexation.
     * @throws ENabuCoreException Raises an exception if $index_field is not valid.
     */
    public function __construct($index_field)
    {
        parent::__construct();

        if (!is_string($index_field)) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                array('$index_field', print_r($index_field, true))
            );
        }

        $this->index_field = $index_field;

        $this->createSecondaryIndexes();
    }

    /**
     * Gets the owner of this list.
     * @return CNabuDataObject If setted, then returns the owner instance of this list, else returns null.
     */
    public function getOwner()
    {
        return $this->nb_owner;
    }

    /**
     * Sets the owner of this list.
     * @param CNabuDataObject|null $nb_owner The Owner instance to be setted or null to remove owner.
     * @return CNabuDataObjectList Returns the Self pointer to grant chained setters calls.
     */
    public function setOwner(CNabuDataObject $nb_owner = null)
    {
        $this->nb_owner = $nb_owner;

        return $nb_owner;
    }

    /**
     * Gets the main Indexed Field Name.
     * @return string Returns the name of indexed field.
     */
    public function getIndexedFieldName()
    {
        return $this->index_field;
    }

    /**
     * Sets the main Indexed Field Name.
     * @param string $index_field The field name to act as main Indexed Field Name.
     * @return CNabuDataObjectList Returns the self instance to grant cascade setters.
     */
    public function setIndexedFieldName(string $index_field)
    {
        $this->index_field = $index_field;

        return $this;
    }

    /**
     * Gets the size (number of items) in the list.
     * @return int Returns the number of items.
     */
    public function getSize()
    {
        return is_array($this->list) ? count($this->list) : 0;
    }

    /**
     * Check if the list is empty.
     * @return bool Returns true if the list is empty or false if not.
     */
    public function isEmpty()
    {
        return !is_array($this->list) || count($this->list) === 0;
    }

    /**
     * Check if the list if filled.
     * @return bool Returns true if the list contains at least one item of false if is empty.
     */
    public function isFilled()
    {
        return is_array($this->list) && count($this->list) > 0;
    }

    /**
     * Empty the list and reset all indexes.
     */
    public function clear()
    {
        $this->list = null;
        if (is_array($this->secondary_indexes)) {
            foreach ($this->secondary_indexes as $index) {
                $index->clear();
            }
        }
    }

    /**
     * Adds a new index to have alternate indexes of this list.
     * @param CNabuDataObjectListIndex $index Instance to manage this index.
     * @return CNabuDataObjectListIndex Returns the $index added.
     */
    public function addIndex(CNabuDataObjectListIndex $index)
    {
        $name = $index->getName();

        if (is_array($this->secondary_indexes)) {
            $this->secondary_indexes[$name] = $index;
        } else {
            $this->secondary_indexes = array($name => $index);
        }

        return $index;
    }

    /**
     * Gets a index instance of this list.
     * @param string $index Index indentifier to looking for.
     * @return CNabuDataObjectListIndex Returns the selected index if exists or null if not.
     * @throws ENabuCoreException Raises an exception if $index is not valid.
     */
    public function getIndex($index)
    {
        if (is_string($index) &&
            is_array($this->secondary_indexes) &&
            array_key_exists($index, $this->secondary_indexes)
        ) {
            return $this->secondary_indexes[$index];
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_INVALID_INDEX, array(print_r($index, true)));
        }
    }

    /**
     * Gets keys of this index as an array.
     * @param string $index Alternate index to get keys.
     * @return array Returns the array of keys.
     */
    public function getKeys($index = false)
    {
        $retval = null;

        if ($index === false) {
            $retval = is_array($this->list) ? array_keys($this->list) : null;
        } elseif (is_string($index) &&
            is_array($this->secondary_indexes) &&
            array_key_exists($index, $this->secondary_indexes)
        ) {
            $retval = $this->secondary_indexes[$index]->getKeys();
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_INVALID_INDEX, array(print_r($index, true)));
        }

        return $retval;
    }

    /**
     * Adds a new item to the list.
     * @param CNabuDataObject $item Object item to be added.
     * @return CNabuDataObject Returns the inserted object or false if something happens.
     */
    public function addItem(CNabuDataObject $item)
    {
        $retval = false;

        if ($this->index_field !== false) {
            if ($item->contains($this->index_field)) {
                if (is_array($this->list)) {
                    $this->list[$item->getValue($this->index_field)] = $item;
                    $retval = $item;
                } else {
                    $this->list = array(
                        $item->getValue($this->index_field) => $item
                    );
                    $retval = $item;
                }
            }
        } else {
            if (is_array($this->list)) {
                $this->list[] = $item;
                $retval = $item;
            } else {
                $this->list = array($item);
                $retval = $item;
            }
        }

        $this->indexItem($item);

        return $retval;
    }

    /**
     * Puts an item in all indexes.
     * @param CNabuDataObject $item Item to be indexed.
     */
    protected function indexItem(CNabuDataObject $item)
    {
        if (is_array($this->secondary_indexes)) {
            foreach ($this->secondary_indexes as $index) {
                $index->addItem($item);
            }
        }
    }

    /**
     * Removes and item in all indexes.
     * @param CNabuDataObject $item Item to be removed.
     */
    protected function removeItemIndex(CNabuDataObject $item)
    {
        if (is_array($this->secondary_indexes)) {
            foreach ($this->secondary_indexes as $index) {
                $index->removeItem($item->getValue($this->index_field));
            }
        }
    }

    /**
     * Gets an item from the collection indexed by $key. If the list does not contain the item, calls internally
     * the abstract method acquireItem() to retrieve the item from the storage.
     * @param string $key Id of searched instance.
     * @param string $index If false then uses the main index and elsewhere specifies an alternate index identifier.
     * @return mixed Returns the instance indexed by $key in selected index or false if not exists.
     */
    public function getItem($key, $index = false)
    {
        $retval = false;
        if ($index) {
            if (!is_string($index)) {
                throw new ENabuCoreException(
                    ENabuCoreException::ERROR_UNEXPECTED_PARAM_CLASS_TYPE,
                    array('$index', get_class($index))
                );
            } elseif (!is_array($this->secondary_indexes) || !array_key_exists($index, $this->secondary_indexes)) {
                throw new ENabuCoreException(ENabuCoreException::ERROR_INVALID_INDEX, array($index));
            }
        }

        if ($this->isFilled()) {
            if ($index === false && array_key_exists($key, $this->list)) {
                $retval = $this->list[$key];
            } elseif (is_string($index)) {
                if (is_array($this->secondary_indexes) &&
                    array_key_exists($index, $this->secondary_indexes)
                ) {
                    $list_index = $this->secondary_indexes[$index];
                    if (($pointer = $list_index->getItemPointer($key)) &&
                        array_key_exists('pointer', $pointer) &&
                        $this->containsKey($pointer['pointer'])
                    ) {
                        $retval = $this->list[$pointer['pointer']];
                    }
                } else {
                    throw new ENabuCoreException(ENabuCoreException::ERROR_INVALID_INDEX, array($index));
                }
            }
        }

        if ($retval === false) {
            $retval = $this->acquireItem($key, $index);
            if ($retval instanceof CNabuDataObject) {
                $this->setItem($retval);
            } else {
                $retval = false;
            }
        }

        return $retval;
    }

    /**
     * Gets all items of the list as an array.
     * @return array Returns the array of items or null if the list is empty.
     */
    public function getItems()
    {
        return $this->list;
    }

    /**
     * Synonymous method of addItem but returns $this to grant chained actions with the list entity.
     * @param CNabuDataObject $item The item to be setted.
     * @return CNabuDataObjectList Returns the $this object.
     */
    public function setItem(CNabuDataObject $item)
    {
        $this->addItem($item);

        return $this;
    }

    /**
     * Removes an item from the list.
     * @param mixed $item A CNabuDataObject containing a field matching the main index field name
     * or a scalar variable containing the Id to be removed.
     * @return CNabuDataObject|bool Returns the removed item if exists or false if not.
     */
    public function removeItem($item)
    {
        $nb_index_id = nb_getMixedValue($item, $this->index_field);
        if ((is_numeric($nb_index_id) || nb_isValidGUID($nb_index_id)) && $this->containsKey($nb_index_id)) {
            unset($this->list[$nb_index_id]);
            $this->removeItemIndex($item);
        }
    }

    /**
     * Iterates the list, and executes $callback for each item in the list.
     * The callback function is of type function($key, $item). To continue iterating each item, callback function
     * returns true. If we need to break the iteration process, then function needs to return false.
     * @param callable $callback Callback function to execute in each iteration.
     * @return int Returns the number of items iterated.
     */
    public function iterate($callback)
    {
        $retval = 0;

        if (is_array($this->list) && count($this->list) > 0) {
            foreach ($this->list as $key => $value) {
                if (!$callback($key, $value)) {
                    $retval = false;
                    break;
                } else {
                    $retval++;
                }
            }
        }

        return $retval;
    }

    /**
     * Merges $list items in this list.
     * @param CNabuDataObjectList $list List to be merged.
     * @return int Retuns the count of items merged.
     */
    public function merge(CNabuDataObjectList $list)
    {
        $count = 0;

        if ($list->isFilled()) {
            $list->iterate(
                function ($key, $item) use ($count)
                {
                    if (!$this->containsKey($key)) {
                        $this->addItem($item);
                        $count++;
                    }

                    return true;
                }
            );
        } else {
            $this->list = $list->list;
            $this->secondary_indexes = $list->secondary_indexes;
            $count = is_array($list->list) ? count($list->list) : 0;
        }

        return $count;
    }

    /**
     * Merges items included in an array.
     * @param array|null $array Array of items to merge.
     * @return int Returns the count of items merged.
     */
    public function mergeArray(array $array = null)
    {
        $count = 0;

        if (is_array($array)) {
            if (count($array) > 0) {
                foreach ($array as $key => $item) {
                    if (!$this->containsKey($key)) {
                        $this->addItem($item);
                        $count++;
                    }
                }
            }
        } elseif ($array !== null) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_METHOD_PARAMETER_NOT_VALID,
                array('$array', print_r($array, true))
            );
        }

        return $count;
    }










    public function sort()
    {

    }

    public function containsKey($key)
    {
        return is_array($this->list) && is_scalar($key) && array_key_exists($key, $this->list);
    }

    public function findByRegExpr($name, $key)
    {
        $retval = null;
        if ($this->secondaryIndexDataAvailable($name)) {
            foreach ($this->secondary_indexes[$name] as $regexp => $descriptor) {
                if (preg_match("/$regexp/", $key)) {
                    $retval = $descriptor['pointer'];
                    break;
                }
            }
        }

        return $retval;
    }
}
