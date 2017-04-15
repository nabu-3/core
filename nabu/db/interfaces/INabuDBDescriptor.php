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

namespace nabu\db\interfaces;

use nabu\db\exceptions\ENabuDBException;

/**
 * Interface to create standard access to Database Descriptors of different providers.
 * Classes implementing this interface are placed in the \providers namespace.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\db\interfaces
 */
interface INabuDBDescriptor
{
    /**
     * Creates a Descriptor stored in a file.
     * @param INabuDBConnector $nb_connector Database connector driver.
     * @param string $filename A valid filename including path to load the Descriptor.
     * @return INabuDBDescriptor Returns a new Descriptor instance if file exists and is valid.
     * @throws ENabuDBException Raises a ENabuDBException if the file is invalid.
     */
    public static function createFromFile(INabuDBConnector $nb_connector, $filename);
    /**
     * Save or serializes the descriptor to a file.
     * @param string $filename File name to serialize the descriptor.
     */
    public function exportToFile($filename);
    /**
     * Gets the Storage Name.
     * @return string If exists, then returns the storage name else returns null.
     */
    public function getStorageName();
    /**
     * Check if the Storage have fields.
     * @return bool Returns true if table contains fields.
     */
    public function hasFields();
    /**
     * Check if the Storage contains a specific field.
     * @param string $field_name Field name to verify.
     * @return bool Returns true if the field exists.
     */
    public function hasField($field_name);
    /**
     * Get Field names in the Descriptor.
     * @return array Returns all names as an array.
     */
    public function getFieldNames();
    /**
     * Gets a Field structure in the Descriptor.
     * @param string $field Field name to get their description.
     * @return array Returns an array containing the field description.
     * @throws ENabuDBException Raises an exception if the field requested does not exists.
     */
    public function getField($field);
    /**
     * Check if the Storage have a Primary Constraint.
     * @return bool Returns true if it exists.
     */
    public function hasPrimaryConstraint();
    /**
     * Gets the size of Primary Constraint.
     * @return int Returns the number of fields involved in Primary Constraint.
     */
    public function getPrimaryConstraintSize();
    /**
     * Check if a field exists in the Primary Constraint. Optionally, can specify in which position.
     * @param string $field_name Name of field to check.
     * @param int $position Position to verify.
     * @return bool Returns true if $field_name exists in the Primary Constraint.
     */
    public function hasPrimaryConstraintField($field_name, $position = false);
    /**
     * Gets field names involved in the Primary Constraint.
     * @return array Returns all names as an array.
     */
    public function getPrimaryConstraintFieldNames();
    /**
     * Gets the Primary Constraint autoincrement field name.
     * @return string Returns the field name.
     */
    public function getPrimaryConstraintAutoIncrementName();
    /**
     * Check if the Storage have Secondary Constraints.
     * @return bool Returns true if they exists.
     */
    public function hasSecondaryConstraints();
    /**
     * Gets the Primary Constraint structure of the Descriptor.
     * @param bool $force If true forces to get the primary constraint from the descriptor. If false returns last
     * Primary Constraint found.
     * @return array Returns the array structure that represents the Primary Constraint in the Descriptor.
     */
    //public function getPrimaryConstraint($force = false);
    /**
     * Gets an array with all index names.
     * @return array Returns the array with existing names or null if none exists.
     */
    public function getSecondaryConstraintNames();
    /**
     * Check if exists a Secondary Constraint that match a list of fields.
     * @param array $fields Array of field names to match.
     * @param bool $partial If true, then evaluate if selected constraint is a super set of $fields.
     * @return bool Return true if they have a Secondary Constraint that matches.
     */
    public function hasSecondaryConstraintWithFields(array $fields, $partial = false);
    /**
     * Builds a filter to identify as univoque a document or row in the storage.
     * If $data if provided with an associative array, then it is used to fill the returned filter with values.
     * If $data is null or not provided then, if the database driver implementation allows this option, returns
     * a generic filter with replacement positions.
     * @param array $data Data to be used to create the filter.
     * @return mixed Retuns a filter structure (an array or a string or any other kind of data) according to the
     * database driver syntax.
     * @throws ENabuDBException Raises an exception if the descriptor is not valid or a combination of $data values
     * are not valid.
     */
    public function buildDocumentIdentifierFilter(array $data = null);
}
