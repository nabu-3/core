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

use nabu\data\CNabuDataObject;
use nabu\data\CNabuDataObjectList;

/**
 * Interface to implement a ORM class
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\db\exceptions
 */
interface INabuDBObject
{
    /**
     * Get the path to the file that contains the JSON storage descriptor.
     * @return string Return the path for the JSON file, including her name.
     */
    public static function getStorageDescriptorPath();

    /**
     * Create the storage for all instances of type that implements this interface.
     * @param INabuDBConnector $connector Database Connector where create the Storage.
     * @return bool Return true if the storage is created of false if already exists.
     */
    public static function createStorage(INabuDBConnector $connector = null);

    /**
     * Returns the script to create the storage. Then script can be a string,
     * an array, an object, or any other kind of value, depending on the kind of database.
     * @return mixed Depending of the kind of database, returns a string,
     * an array or an object containing the creation script.
     */
    public static function getCreationStorageSentence();

    /**
     * Returns the name of the storage.
     * @return string Name of the storage
     */
    public static function getStorageName();

    /**
     * Returns the storage type associated to this storage. The returned value can be
     * a string, an object or any other kind of value depending of the database.
     * @return mixed Returns a value depending of the kind of database.
     */
    public static function getStorageType();

    /**
     * Returns true if object is loaded from database
     */
    public function isFetched();

    /**
     * Returns true if object is new.
     */
    public function isNew();

    /**
     * Returns true if object is deleted.
     */
    public function isDeleted();

    /**
     * Returns true if object is built-in.
     */
    public function isBuiltIn();

    /**
     * Returns the query sentence to load a unique register from the database
     * or null if no conditions allowed to query the database.
     * Basically, this method could to evaluate the availability of valid
     * index fields in the instance, and if success, returns the query string.
     * @return string|null Returns a query sentence string if allowed or null if not.
     */
    public function getSelectRegister();

    /**
     * Executes the $sentence crossing wildcards with $params array. The sentence
     * can be a string for SQL Database models, or an associative array or object
     * for NoSQL and JSON oriented Databases. If $sentence is false,
     * then calls {@see getSelectRegister} and if it returns a valid sentence
     * then uses them to query database.
     * @param string|array|object $sentence Sentence to execute
     * @param array|false $params Associative array with the collection of params to be combined with the sentence
     * @param bool $trace True if they want to trace the sentence in the Engine Log Handler
     * @return bool Returns true if success of false if not.
     */
    public function load($sentence = false, $params = false, $trace = false);

    /**
     * Fetchs a cursor stored in a INabuDBStatement object and fill
     * this instance data and status (fetched, new, deleted).
     * @throws \nabu\core\exceptions\ENabuCoreException if an unrecoverable error occurs
     * @param INabuDBStatement $statement Statement that contains the cursor to fetch
     * @return bool Returns true if success of false if not.
     */
    public function fetch($statement);

    /**
     * Determines the status of the object and prepares internal values for new/fetched/deleted status
     */
    public function fill();

    /**
     * Reconnects the database
     */
    public function relinkDB();

    /**
     * Sets/Changes current connector for this object. Normally, this method is intended for internal use.
     * @param \nabu\db\interfaces\INabuDBConnector $connector Database connector to use with this object
     */
    public function setDB(\nabu\db\interfaces\INabuDBConnector $connector);

    /**
     * Creates an object instance from the database and retuns it as an instance of implemented class
     * @param string|array|object $sentence Sentence to execute
     * @param array|false $params Associative array with the collection of params to be combined with the sentence
     * @param bool $trace True if they want to trace the sentence in the Engine Log Handler
     * @return null|CNabuDataObject Returns the instantiated object if exists or null elsewhere
     */
    public static function buildObjectFromSQL($sentence, $params = null, $trace = false);

    /**
     * Creates an object instance from the database using a field containing their class name.
     * The rules to subclassing are:
     * 1. If $subclassing_field does not exists returns null.
     * 2. If $usbclassing_field contains a class name, then uses it to instantiate the object.
     *    If class is not available the Nabu Engine throws an ENabuCoreException.
     * 3. If $subclassing_field is empty uses $subclassing_default to create the object.
     * 4. If $subclassing_default is null, then uses the same class that implements this interface.
     * @param string $subclassing_field Field that contains the class name to be instantiated
     * @param string|array|object $sentence Sentence to execute
     * @param array|false $params Associative array with the collection of params to be combined with the sentence
     * @param string $subclassing_default If $subclassing_field contains no valid class then uses this as default,
     * and if this is null, then uses the implemented class.
     * @param bool $trace True if they want to trace the sentence in the Engine Log Handler
     * @return null|CNabuDataObject Returns the instantiated object if exists or null elsewhere
     */
    public static function buildSubClassingObjectFromSQL(
        $subclassing_field,
        $sentence,
        $params = null,
        $subclassing_default = null,
        $trace = false
    );

    /**
     * Creates a list of instances from the database and returns it as an associative array indexed by $index_field
     * @param string $index_field Name of field that acts as index of the returned associative array list
     * @param string|array|object $sentence Sentence to execute
     * @param array|false $params Associative array with the collection of params to be combined with the sentence
     * @param null|CNabuDataObject $parent If associated list requires a parent/owner instance then receives it
     * in this parameter.
     * @param bool $trace True if they want to trace the sentence in the Engine Log Handler
     * @return array|null Returns an associative array if objects found or null elsewhere.
     */
    public static function buildObjectListFromSQL(
        $index_field,
        $sentence,
        $params = null,
        CNabuDataObject $parent = null,
        $trace = false
    );

    /**
     * Creates a list of instances subclassing them from the database and returns it
     * as an associative array indexed by $index_field
     * The rules to subclassing are:
     * 1. If $subclassing_field does not exists returns null.
     * 2. If $usbclassing_field contains a class name, then uses it to instantiate the object.
     *    If class is not available the Nabu Engine throws an ENabuCoreException.
     * 3. If $subclassing_field is empty uses $subclassing_default to create the object.
     * 3. If $subclassing_default is null, then uses the same class that implements this interface.
     * @param string $index_field Name of field that acts as index of the returned associative array list
     * @param string $subclassing_field Field that contains the class name to be instantiated
     * @param string|array|object $sentence Sentence to execute
     * @param array|false $params Associative array with the collection of params to be combined with the sentence
     * @param string $subclassing_default If $subclassing_field contains no valid class then uses this as default,
     * and if this is null, then uses the implemented class.
     * @param bool $trace True if they want to trace the sentence in the Engine Log Handler
     * @return CNabuDataObjectList|null Returns an associative array if objects found or null elsewhere.
     */
    public static function buildSubClassingObjectListFromSQL(
        $index_field,
        $subclassing_field,
        $sentence,
        $params = null,
        $subclassing_default = null,
        $trace = false
    );

    /**
     * Creates a tree of instances from the database and returns it as a multilevel associative array
     * indexed by $index_field. If the $sentence contains orphan objects these objects are not indexed
     * in the tree and then discarded.
     * @param string $index_field Name of field that acts as index of the returned associative array list
     * @param string|array|object $sentence Sentence to execute
     * @param array|false $params Associative array with the collection of params to be combined with the sentence
     * @param bool $trace True if they want to trace the sentence in the Engine Log Handler
     * @return array|null Returns a multilevel associative array if objects found or null elsewhere.
     */
    public static function buildObjectTreeFromSQL($index_field, $sentence, $params = null, $trace = false);

    /**
     * Creates a tree of instances subclassing them from the database and returns it as a multilevel
     * associative array indexed by $index_field. If the $sentence contains orphan objects these objects
     * are not indexed in the tree and then discarded.
     * The rules to subclassing are:
     * 1. If $subclassing_field does not exists returns null.
     * 2. If $usbclassing_field contains a class name, then uses it to instantiate the object.
     *    If class is not available the Nabu Engine throws an ENabuCoreException.
     * 3. If $subclassing_field is empty uses $subclassing_default to create the object.
     * 4. If $subclassing_default is null, then uses the same class that implements this interface.
     * @param string $index_field Name of field that acts as index of the returned associative array list
     * @param string $subclassing_field Field that contains the class name to be instantiated
     * @param string|array|object $sentence Sentence to execute
     * @param array|false $params Associative array with the collection of params to be combined with the sentence
     * @param string $subclassing_default If $subclassing_field contains no valid class then uses this as default,
     * and if this is null, then uses the implemented class.
     * @param bool $trace True if they want to trace the sentence in the Engine Log Handler
     * @return array|null Returns a multilevel associative array if objects found or null elsewhere.
     */
    public static function buildSubClassingObjectTreeFromSQL(
        $index_field,
        $subclassing_field,
        $sentence,
        $params = null,
        $subclassing_default = null,
        $trace = false
    );

    /**
     * Build a SQL sentence with a string with bn_vnsprintf where $sentence contains wildcards
     * that are replaced with $params and data object
     * @param string|array|object $sentence Sentence to execute
     * @param array|false $params Associative array with the collection of params to be combined with the sentence
     * @return array|string|object|null Returns the sentence built using the syntax of the database
     * or null if no sentence can be built.
     */
    public function buildSentence($sentence, $params = null);

    /**
     * Converts a string field as a database sentence fragment string assignation equivalent.
     * In standard SQL as the form <code>name='value'</code>.
     * Before the conversion is made, performs an SQL injection cleanup in the field value.
     * @param string $field Field name
     * @param int|false $def_value Default value if field does not exists
     * @param bool $retnull If true sets field value as null when is null or not exists
     * Returns a formatted string or false if not value allowed.
     */
    public function buildStringAssignation($field, $def_value = false, $retnull = true);
    /**
     * Converts a integer field as a database sentence fragment string assignation equivalent.
     * In standard SQL as the form <code>name=value</code>.
     * Before the conversion is made, performs an SQL injection cleanup in the field value.
     * @param string $field Field name
     * @param int|false $def_value Default value if field does not exists
     * @param bool $retnull If true sets field value as null when is null or not exists
     * Returns a formatted string or false if not value allowed.
     */
    public function buildIntegerAssignation($field, $def_value = false, $retnull = true);
    /**
     * Converts a float or double field as a database sentence fragment string assignation equivalent.
     * In standard SQL as the form <code>name=value</code>.
     * Before the conversion is made, performs an SQL injection cleanup in the field value.
     * @param string $field Field name
     * @param int|false $def_value Default value if field does not exists
     * @param bool $retnull If true sets field value as null when is null or not exists
     * Returns a formatted string or false if not value allowed.
     */
    public function buildFloatAssignation($field, $def_value = false, $retnull = true);

    /**
     * Concatenates a list of fragments in SQL mode
     * @param array $list List of string fragments to concatenate
     * @param string $glue Glue fragment to concatenate list
     * @return string Returns the list concatenated with $glue fragment
     */
    public function concatDBFragments($list, $glue = false);

    /**
     * @deprecated since version 3.0.0
     * @param mixed $sentence
     * @param array $params
     * @param bool $trace
     */
    public static function buildTreeDataFromSQL($sentence, $params = null, $trace = false);

    /**
     * @deprecated since version 3.0.0
     * @param string $index_field
     * @param mixed $sentence
     * @param array $params
     * @param bool $trace
     */
    public static function buildTreeDataListFromSQL($index_field, $sentence, $params = null, $trace = false);
}
