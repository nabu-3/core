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

namespace nabu\db\interfaces;

/**
 * Interface to create standard access to Databases of different providers.<br>
 * The classes implementing this interface are placed in the \providers namespace.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\db\interfaces
 */
interface INabuDBConnector
{
    /**
     * Default constructor
     * @param mixed $nb_database Database object, ID or file name to configure connection
     */
    public function __construct($nb_database = null);

    /**
     * Get the name of the driver implemented by the class that implements this interface
     * @return string Driver name
     */
    public function getDriverName();

    /**
     * Get the server info on wich they are connected.
     * If Server info is available then returns an array with Server data depending of each connector.
     * If Server info is not available then returns false.
     * @return array|false
     */
    public function getServerInfo();

    /**
     * Get current charset connection
     * @return string Returns current charset
     */
    public function getCharset();

    /**
     * Set the database charset
     * @param string $charset New charset
     */
    public function setCharset($charset);

    /**
     * Get connection status
     * @return bool Returns true if connected of false if not
     */
    public function isConnected();

    /**
     * Get current hostname
     * @return string Returns current hostname
     */
    public function getHost();

    /**
     * Set the database hostname
     * @param string $host New hostname
     */
    public function setHost($host);

    /**
     * Get error occurred in last connector action
     * @return mixed Return an array with the code and message if an error occurs or false if not
     */
    public function getLastError();

    /**
     * Get error code occurred in last connector action
     * @return mixed Return the code of error
     */
    public function getLastErrorCode();

    /**
     * Get error message occurred in last connector action
     * @return string Return the message of error
     */
    public function getLastErrorMessage();

    /**
     * Get current password of user in database
     * @return string Returns current password
     */
    public function getPassword();

    /**
     * Set database password of user in database
     * @param string $password New password
     */
    public function setPassword($password);

    /**
     * Get current database port
     * @return int Returns current port
     */
    public function getPort();

    /**
     * Set database port
     * @param type $port New database port
     */
    public function setPort($port);

    /**
     * Get current database schema
     * @return string Returns current database schema
     */
    public function getSchema();

    /**
     * Set database schema
     * @param string $schema New database schema
     */
    public function setSchema($schema);

    /**
     * Check the trace query status of connection
     * @return bool Returns true if trace query is enabled or false if not
     */
    public function isTraced();

    /**
     * Set trace query of connection
     * @param bool $trace_query New trace query status
     */
    public function setTrace($trace_query);

    /**
     * Check if connector optimizes leading and trailing spaces.
     * @return bool Returns true if optimization is enabled.
     */
    public function isSpaceOptimizationEnabled();

    /**
     * Set leading and trailing spaces optimization.
     * @param bool $status If true sets the optimization. False disables optimization.
     */
    public function setSpaceOptimization(bool $status);

    /**
     * Get current database user name
     * @return string Returns current database user
     */
    public function getUser();

    /**
     * Set database user name
     * @param string $user New database user name
     */
    public function setUser($user);

    /**
     * Gets a INabuDBSyntaxBuilder object. This object allows to create
     * programmatically a set of sentences, including DDL, to manage objects
     * in the database.
     * @return INabuDBSyntaxBuilder Syntax Builder object if available.
     */
    public function getSyntaxBuilder();

    /**
     * Gets a iNabuDBDescriptor object. This object is created from their serialized file $filename.
     * @return INabuDBDescriptor Returns the descriptor instance.
     * @throws ENabuDBException Raises an exception if the descriptor cannot be created.
     */
    public function getDescriptorFromFile($filename);

    /**
     * Connect to database
     * @return bool Returns true if success or false if not
     */
    public function connect();

    /**
     * Disconnect from database
     * @return bool Returns true if success or false if not
     */
    public function disconnect();

    /**
     * Verify if the connection is alive
     * @return bool Returns true if success of false if not
     */
    public function testConnection();

    /**
     * Build a SQL sentence with a string like nb_vnsprintf like where $sentence
     * contains wildcards that are replaced with
     * @param type $sentence
     * @param type $params
     */
    public function buildSentence($sentence, $params);

    /**
     * Implode an array as an string list parsed and ready to be used in SQL sentences
     * @param array $array Array list of values
     * @param type $glue Separator between items of the array in the resulting string
     * @return string Returns a string with all items in $params array well concatenated using $glue
     */
    public function implodeStringArray($array, $glue);

    /**
     * Implode an array as an integer list parsed and ready to be used in SQL sentences
     * @param array $array Array list of values
     * @param type $glue Separator between items of the array in the resulting string
     * @return string Returns a string with all items in $params array well concatenated using $glue
     */
    public function implodeIntegerArray($array, $glue);

    /**
     * Executes a query in current database and returns the resultset
     * as an {@link nabu\core\interfaces\INabuDBStatement} interface to manipulate it.
     * @param string $sentence Query string to execute
     * @param array $params Query params
     * @param bool $trace If true trace query in error log
     * @return mixed Returns a {@link nabu\core\interfaces\INabuDBStatement} interface
     * to access to results or false if an error occurs or the connection is closed.
     */
    public function getQuery($sentence, $params = null, $trace = false);

    /**
     * Executes a sentence different of SELECT, INSERT, UPDATE, DELETE in current database
     * and returns the number of affected rows if success.
     * @param string $sentence Query string to execute
     * @param array $params Query params
     * @param bool $trace If true trace query in error log
     * @return mixed Returns the number of affected rows if success or false if not
     */
    public function executeSentence($sentence, $params = null, $trace = false);

    /**
     * Executes a insert in current database and returns the number of affected rows
     * if success. In some databases this method is sinomym of executeUpdate.
     * @param string $sentence Query string to execute
     * @param array $params Query params
     * @param bool $trace If true trace query in error log
     * @return mixed Returns the number of affected rows if success or false if not
     */
    public function executeInsert($sentence, $params = null, $trace = false);

    /**
     * Executes a update in current database and returns the number of affected rows
     * if success. In some databases this method is sinomym of executeInsert.
     * @param string $sentence Query string to execute
     * @param array $params Query params
     * @param bool $trace If true trace query in error log
     * @return mixed Returns the number of affected rows if success or false if not
     */
    public function executeUpdate($sentence, $params = null, $trace = false);

    /**
     * Executes a delete in current database and returns the number of affected rows
     * if success. In some databases this method is sinomym of executeInsert.
     * @param string $sentence Query string to execute
     * @param array $params Query params
     * @param bool $trace If true trace query in error log
     * @return mixed Returns the number of affected rows if success or false if not
     */
    public function executeDelete($sentence, $params = null, $trace = false);


    /**
     * Releases all resources of a statement instance
     * @param \nabu\core\interfaces\INabuDBStatement $statement Statement instance to release
     */
    public function releaseStatement($statement);

    /**
     * Returns true if after executing a sentence the $code warning is raised, or false if not.
     * @param mixed $code Code value of the warning to check
     */
    public function checkForWarning($code);

    /**
     * Get first row of a query as a class instance of type $classname
     * @param string $classname Class name with full namespace path
     * @param string $sentence Query string to execute
     * @param array $params Query params
     * @param bool $trace If true trace query in error log
     * @return mixed Returns an object instance of type $classname if success
     * and records found, null if no records, or false if error
     */
    public function getQueryAsObject($classname, $sentence, $params = false, $trace = false);

    /**
     * Get all rows of a query as an array of objects of type $classname
     * If $index_field is ommited or null then returns a normal array else
     * if defined then returns an associative array indexed by field described in $index_field
     * @param string $classname Class name with full namespace path
     * @param string|null $index_field Field name of index in an associative array
     * @param string $sentence Query string to execute
     * @param array $params Query params
     * @param bool $trace If true trace query in error log
     * @return mixed Returns an array (normal or associative) if records found,
     * null if no records found or false if an error occurs.
     */
    public function getQueryAsObjectArray($classname, $index_field, $sentence, $params = null, $trace = false);

    /**
     * Executes a query in current database and returns the resultset as a normal array
     * where values are the $field values in each row.
     * @param string $field Field name for array values
     * @param string $sentence Query string to execute
     * @param array $params Query params
     * @param bool $trace If true trace query in error log
     * @return mixed Returns a normal array if success and records found, null if no records or false if an error occurs
     */
    public function getQueryAsArrayOfSingleField($field, $sentence, $params = false, $trace = false);

    /**
     * Executes a query in current database and returns the resulset as an associative
     * array using the $index_field as array index
     * @param string $index_field Field to get values for index of array
     * @param string $sentence Query string to execute
     * @param array $params Query params
     * @param bool $trace If true trace query in error log
     * @return mixed Returns an associative array if success and records found,
     * null if no records or false if an error occurs
     */
    public function getQueryAsAssoc($index_field, $sentence, $params = false, $trace = false);

    /**
     * Executes a query in current database and returns the resultset as an associative array
     * where values are the $value_field values and indexes are the $index_field values in each row.
     * @param string $index_field Field name for array indexes
     * @param string $value_field Field name for array values
     * @param string $sentence Query string to execute
     * @param array $params Query params
     * @param bool $trace If true trace query in error log
     * @return mixed Returns an associative array if success and records found,
     * null if no records or false if an error occurs
     */
    public function getQueryAsAssocPairedFields($index_field, $value_field, $sentence, $params = false, $trace = false);

    /**
     * Executes a query in current database and returns the $field value of first row.
     * @param string $field Field name to return value.
     * @param string $sentence Query string to execute.
     * @param array $params Query params.
     * @param bool $trace If true trace query in error log.
     * @return mixed Returns the value of queried field if success and records found,
     * null if no records or false if an error occurs
     */
    public function getQueryAsSingleField($field, $sentence, $params = false, $trace = false);

    /**
     * Executes a query in current database and returns the first row as an associative array
     * where the index of array is the field names and the values are the field values.
     * @param string $sentence Query string to execute.
     * @param bool $trace If true trace query in error log.
     * @return mixed Returns an associative array if success and records found,
     * null if no records or false if an error occurs.
     */
    public function getQueryAsSingleRow($sentence, $params = false, $trace = false);

    /**
     * Executes a query in current database and returns the resulset as a normal array
     * @param string $sentence Query string to execute
     * @param array $params Query params
     * @param bool $trace If true trace query in error log
     * @return mixed Returns a normal array if success and records found, null if no records or false if an error occurs
     */
    public function getQueryAsArray($sentence, $params = false, $trace = false);

    /**
     * Get first row of a query as a class instance of type contained in field $subclassing_field.
     * If field is empty, then if $default_subclassing is declared uses this class to create object, else returns null.
     * @param string $subclassing_field Field that contains class name with full namespace path
     * @param string $query Query string to execute
     * @param array $params Query params
     * @param string $subclassing_default Default class name with full namespace path
     * @param bool $trace If true trace query in error log
     * @return mixed Returns an object instance of type contained in field $subclassing_field
     * if success and records found, null if no records, or false if error
     */
    public function getQueryAsObjectWithSubClassing(
        $subclassing_field,
        $query,
        $params = null,
        $subclassing_default = null,
        $trace = false
    );

    /**
     * Get all rows of a query as an array of objects of type contained in field described by $subclassing_field.
     * If field is empty, then if $default_subclassing is declared uses this class to create object else, returns null.
     * @param string|null $index_field Field name of index in an associative array
     * @param string $subclassing_field Field that contains class name with full namespace path
     * @param string $query Query string to execute
     * @param array $params Query params
     * @param string $subclassing_default Default class name with full namespace path
     * @param bool $trace If true trace query in error log
     * @return mixed Returns an array (normal or associative) if records found,
     * null if no records found or false if an error occurs.
     */
    public function getQueryAsObjectArrayWithSubclassing(
        $index_field,
        $subclassing_field,
        $query,
        $params = null,
        $subclassing_default = null,
        $trace = false
    );
    /**
     * Get row count of a table with distinct and where conditions
     * @param string $table Name of table or tables separated by commas
     * @param string|null $distinct Distinct into the count expression in SQL syntax
     * @param string|null $where Where filter in SQL syntax
     * @param array|null $params Query params
     * @param bool $trace If true trace query in error log
     * @return mixed Returns the number of rows if success or false if not
     */
    public function getQueryAsCount($table, $distinct = null, $where = null, $params = null, $trace = false);
    /**
     * Return true if this instance is intended for loggin database that store all logs
     * @return bool
     */
    public function containsLogsDatabase();

    /**
     * Establishes this instance as loggin database to store all logs
     * @param bool $status True if this instance is for loggin or false elsewhere
     */
    public function setAsLogsDatabase($status);










    /**
     * Get ID generated in last insert or replace in an autonumeric field
     * @return mixed Returns the ID if success or false if not
     */
    public function getLastInsertedId();
    /**
     * Get count of affected rows in last command in the database.
     * Normally for INSERT, UPDATE, REPLACE and DELETE commands.
     * @return mixed Returns the count of rows if success or false if not
     */
    public function getAffectedRows();
    /**
     * Begin a database trasaction
     * @param bool $trace If true trace query in error log
     * @return bool Returns true if success or false if not
     */
    public function beginTransaction($trace = false);
    /**
     * Commit an opened database trasaction
     * @param bool $trace If true trace query in error log
     * @return bool Returns true if success or false if not
     */
    public function commitTransaction($trace = false);
    /**
     * Rollback an opened database trasaction
     * @param bool $trace If true trace query in error log
     * @return bool Returns true if success or false if not
     */
    public function rollbackTransaction($trace = false);
}
