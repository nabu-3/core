<?php

/*  Copyright 2009-2011 Rafael Gutierrez Martinez
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

namespace nabu\db;
use nabu\sdk\builders\json\CNabuJSONBuilder;
use nabu\core\CNabuObject;
use nabu\core\exceptions\ENabuCoreException;
use nabu\db\exceptions\ENabuDBException;
use nabu\db\interfaces\INabuDBConnector;
use nabu\db\interfaces\INabuDBDescriptor;

/**
 * Abstract base class to manage Database Storage Descriptors.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 */
abstract class CNabuDBAbstractDescriptor extends CNabuObject implements INabuDBDescriptor
{
    /**
     * Database Connector instance
     * @var INabuDBConnector
     */
    protected $nb_connector = null;
    /**
     * Storage Descriptor array
     * @var array
     */
    protected $storage_descriptor = false;
    /**
     * Primary Constraint in the Descriptor
     * @var array
     */
    protected $primary_constraint = null;

    /**
     * Validate the syntax of a descriptor according to the database driver implementation.
     * @param array $storage_descriptor Storage descriptor as array to validate.
     * If not provided (null) then uses the local storage descriptor.
     * @return bool Returns true if the descriptor syntax is valid or false if not.
     */
    abstract protected function validateDescriptor($storage_descriptor = null);
    /**
     * Creates a document identifier filter when no data is provided.
     * This method is called internally by the interface method buildDocumentIdentifierFilter.
     * @return mixed Returns the proper filter to be returned by buildDocumentIdentifierFilter interface method.
     * @throws ENabuDBException Raises an exception if this operation cannot be done.
     */
    abstract protected function buildDocumentIdentifierFilterDefault();
    /**
     * Creates a document identifier filter when data is provided.
     * This method is called internally by the interface method buildDocumentIdentifierFilter.
     * @param array $data Data provided to fill the filter with final values.
     * @return mixed Returns the proper filter to be returned by buildDocumentIdentifierFilter interface method.
     * @throws ENabuDBException Raises an exception if this operation cannot be done.
     */
    abstract protected function buildDocumentIdentifierFilterData($data);

    /**
     * Creates the instance. If $storage_descriptor is provided, then builds the descriptor internally
     * and checks their syntax.
     * @param INabuDBConnector $nb_connector Database connector.
     * @param array $storage_descriptor Array representing the Storage Descriptor internal data to be parsed
     * and applied.
     * @throws ENabuDBException Raises an exception if the $storage_descriptor data is not valid.
     */
    public function __construct(INabuDBConnector $nb_connector, array $storage_descriptor = null)
    {
        $this->nb_connector = $nb_connector;

        if (is_array($storage_descriptor)) {
            if (!$this->validateDescriptor($storage_descriptor)) {
                throw new ENabuDBException(ENabuDBException::ERROR_STORAGE_DESCRIPTOR_SYNTAX_ERROR);
            }

            $this->storage_descriptor = $storage_descriptor;
            $this->getPrimaryConstraint();
        }
    }

    public static function createFromFile(INabuDBConnector $connector, $filename)
    {
        if (!file_exists($filename)) {
            throw new ENabuDBException(
                ENabuDBException::ERROR_STORAGE_DESCRIPTOR_NOT_FOUND,
                0, null, null, array($filename));
        }

        if (!($descriptor = json_decode(file_get_contents($filename), true))) {
            throw new ENabuDBException(
                ENabuDBException::ERROR_STORAGE_DESCRIPTOR_PARSE_ERROR,
                0, null, null, array($filename));
        } else {
            $class_name = get_called_class();
            return new $class_name($connector, $descriptor);
        }
    }

    public function exportToFile($filename)
    {
        $json_builder = new CNabuJSONBuilder($this->storage_descriptor);
        $json_builder->create();
        $json_builder->exportToFile($filename);
    }

    public function getStorageName()
    {
        return is_array($this->storage_descriptor) && array_key_exists('name', $this->storage_descriptor)
               ? $this->storage_descriptor['name']
               : null
        ;
    }

    public function hasPrimaryConstraint()
    {
        return is_array($this->primary_constraint) &&
            array_key_exists('fields', $this->primary_constraint) &&
            count($this->primary_constraint['fields']) > 0
        ;
    }

    public function getPrimaryConstraintSize()
    {
        return $this->hasPrimaryConstraint() ? count($this->primary_constraint['fields']) : 0;
    }

    public function hasPrimaryConstraintField($field_name, $position = false)
    {
        if (!is_string($field_name)) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                array('$field_name', print_r($field_name, true))
            );
        }

        $retval = $this->getPrimaryConstraintSize() >= $position &&
                  array_key_exists($field_name, $this->primary_constraint['fields']) &&
                  is_array($this->primary_constraint['fields'][$field_name])
        ;

        if ($retval && is_numeric($position)) {
            $retval = array_key_exists('ordinal', $this->primary_constraint['fields'][$field_name]) &&
                      $this->primary_constraint['fields'][$field_name]['ordinal'] == $position
            ;
        }

        return $retval;
    }

    public function getPrimaryConstraintFieldNames()
    {
        $retval = false;

        if ($this->hasPrimaryConstraint()) {
            $retval = array_keys($this->primary_constraint['fields']);
        };

        return $retval;
    }

    public function getPrimaryConstraintAutoIncrementName()
    {
        $retval = false;

        if ($this->getPrimaryConstraint() !== null) {
            foreach ($this->primary_constraint['fields'] as $name => $constraint) {
                $field = $this->getField($name);
                if (array_key_exists('extra', $field) && $field['extra'] === 'auto_increment') {
                    $retval = $name;
                    break;
                }
            }
        }

        return $retval;
    }

    public function hasSecondaryConstraints()
    {
        return is_array($this->storage_descriptor) &&
            array_key_exists('constraints', $this->storage_descriptor) &&
            is_array($this->storage_descriptor['constraints']) &&
            (count($this->storage_descriptor['constraints']) - (is_array($this->primary_constraint) ? 1 : 0)) > 0
        ;
    }

    public function getSecondaryConstraintNames()
    {
        if (is_array($this->storage_descriptor) &&
            array_key_exists('constraints', $this->storage_descriptor) &&
            count($this->storage_descriptor['constraints']) > 0
        ) {
            $names = array_keys($this->storage_descriptor['constraints']);
            if ($this->hasPrimaryConstraint()) {
                $names = array_diff($names, array($this->primary_constraint['name']));
            }
        } else {
            $names = null;
        }

        return $names;
    }

    public function hasSecondaryConstraintWithFields(array $fields, $partial = false)
    {
        $retval = false;

        if ($this->hasSecondaryConstraints()) {
            foreach ($this->storage_descriptor['constraints'] as $constraint) {
                if ((!array_key_exists('primary', $constraint) || !$constraint['primary']) &&
                    array_key_exists('fields', $constraint) &&
                    is_array($constraint['fields']) &&
                    count($constraint['fields']) > 0
                ) {
                    $constraint_keys = array_keys($constraint['fields']);
                    $co_fields = count($fields);
                    $co_const = count($constraint_keys);
                    if (($partial && $co_const >= $co_fields) || (!$partial && $co_const === $co_fields)) {
                        $diff = array_diff($fields, $constraint_keys);
                        $count = count($diff);
                        if (($partial && $co_fields + $count === $co_const) || (!$partial && $count === 0)) {
                            $retval = true;
                            break;
                        }
                    }
                }
            }
        }

        return $retval;
    }

    public function hasFields()
    {
        return is_array($this->storage_descriptor) &&
            array_key_exists('fields', $this->storage_descriptor) &&
            count($this->storage_descriptor['fields']) > 0;
    }

    public function hasField($field_name)
    {
        return $this->hasFields() &&
            array_key_exists($field_name, $this->storage_descriptor['fields']) &&
            is_array($this->storage_descriptor['fields'][$field_name])
        ;
    }

    public function getFieldNames()
    {
        if ($this->hasFields()) {
            return array_keys($this->storage_descriptor['fields']);
        } else {
            throw new ENabuDBException(ENabuDBException::ERROR_STORAGE_DESCRIPTOR_SYNTAX_ERROR);
        }
    }

    public function getField($field)
    {
        if ($this->hasFields() &&
            array_key_exists($field, $this->storage_descriptor['fields']) &&
            is_array($this->storage_descriptor['fields'][$field])
        ) {
            return $this->storage_descriptor['fields'][$field];
        } else {
            throw new ENabuDBException(
                ENabuDBException::ERROR_STORAGE_DESCRIPTOR_FIELD_NOT_FOUND,
                0, null, null, array($field)
            );
        }
    }

    public function buildDocumentIdentifierFilter(array $data = null)
    {
        if ($data === null) {
            return $this->buildDocumentIdentifierFilterDefault();
        } else {
            return $this->buildDocumentIdentifierFilterData($data);
        }
    }

    /**
     * Gets the Primary Constraint array fragment of the descriptor.
     * This method is protected to prevent to expose the array to public accesses.
     * @param bool $force If true, forces to update the Primary Constraint from the Storage main descriptor.
     * @return array Returns the array fragment that describes the Primary Constraint.
     */
    protected function getPrimaryConstraint($force = false)
    {
        if ($this->primary_constraint === null || $force) {
            $this->primary_constraint = null;
            if (array_key_exists('constraints', $this->storage_descriptor)) {
                foreach ($this->storage_descriptor['constraints'] as $constraint) {
                    if (is_array($constraint) &&
                        array_key_exists('primary', $constraint) &&
                        $constraint['primary'] === true
                    ) {
                        $this->primary_constraint = $constraint;
                        break;
                    }
                }
            }
        }

        return $this->primary_constraint;
    }
}
