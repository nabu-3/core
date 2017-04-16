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

namespace nabu\db\exceptions;

use Exception;
use nabu\core\exceptions\ENabuException;
use nabu\db\interfaces\INabuDBException;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.12 Surface
 * @package \nabu\db\exceptions
 */

class ENabuDBException extends ENabuException implements INabuDBException
{

    /* General error messages */
    const ERROR_NOT_CONNECTED                       = 0x0001;
    const ERROR_CREATION_SCRIPT_NOT_PRESENT         = 0x0003;
    const ERROR_DATABASE_TYPE_NOT_ALLOWED           = 0x0004;

    /* Operation error messages */
    const ERROR_QUERY_EXECUTION                     = 0x1001;

    const ERROR_STORAGE_DESCRIPTOR_NOT_FOUND        = 0x1002;
    const ERROR_STORAGE_DESCRIPTOR_NOT_AVAILABLE    = 0x1003;
    const ERROR_STORAGE_DESCRIPTOR_PARSE_ERROR      = 0x1004;
    const ERROR_STORAGE_DESCRIPTOR_SYNTAX_ERROR     = 0x1005;
    const ERROR_STORAGE_DESCRIPTOR_FIELD_NOT_FOUND  = 0x1006;

    const ERROR_PRIMARY_CONSTRAINT_NOT_EXISTS       = 0x1007;

    const ERROR_ATTEMPT_TO_WRITE_WITHOUT_STORAGE    = 0x1008;

    const ERROR_ATTEMPT_TO_DELETE_WITHOUT_STORAGE   = 0x1009;
    const ERROR_ATTEMPT_TO_DELETE_WITH_EMPTY_WHERE  = 0x100a;

    protected $error_messages = array(
        /* General error messages */
        ENabuDBException::ERROR_NOT_CONNECTED =>
            'Database connection not stablished',
        ENabuDBException::ERROR_CREATION_SCRIPT_NOT_PRESENT =>
            'Creation script not present for storage [%s]',
        ENabuDBException::ERROR_DATABASE_TYPE_NOT_ALLOWED =>
            'Database type not allowed',

        /* Operation error messages */
        ENabuDBException::ERROR_QUERY_EXECUTION =>
            'Database execution error: %s',

        ENabuDBException::ERROR_STORAGE_DESCRIPTOR_NOT_FOUND =>
            'Declared descriptor path [%s] does not contains a valid descriptor storage file',
        ENabuDBException::ERROR_STORAGE_DESCRIPTOR_NOT_AVAILABLE =>
            'This operation needs to have a storage descriptor but it is not available',
        ENabuDBException::ERROR_STORAGE_DESCRIPTOR_PARSE_ERROR =>
            'The storage descriptor has an invalid format and cannot be parsed',
        ENabuDBException::ERROR_STORAGE_DESCRIPTOR_SYNTAX_ERROR =>
            'The storage descriptor has a syntax error and is not valid',
        ENabuDBException::ERROR_STORAGE_DESCRIPTOR_FIELD_NOT_FOUND =>
            'The field [%s] does not exists in the storage descriptor',

        ENabuDBException::ERROR_PRIMARY_CONSTRAINT_NOT_EXISTS =>
            'Primary constraint does not exists for storage [%s]',

        ENabuDBException::ERROR_ATTEMPT_TO_WRITE_WITHOUT_STORAGE =>
            'An attempt to write a register in a storage was performed without storage name',

        ENabuDBException::ERROR_ATTEMPT_TO_DELETE_WITHOUT_STORAGE =>
            'An attempt to delete a register in a storage was performed without storage name',
        ENabuDBException::ERROR_ATTEMPT_TO_DELETE_WITH_EMPTY_WHERE =>
            'Where clause is empty and cannot delete a single row'
    );

    /**
     * Code of database error that raises the exception.
     * @var int|object
     */
    private $sql_code;
    /**
     * Message ov error provided by the database when the exception is raised.
     * @var string
     */
    private $sql_message;
    /**
     * Database sentence that raises the exception. Depending of the kind of error, this attribute can be empty.
     * @var object
     */
    private $sql_script;

    /**
     * Constructor
     * @param int $code Nabu code of the exception raised
     * @param int $sql_code Database code of the exception raised
     * @param string $sql_message Database message of the exception raised
     * @param string $sql_script Sentence that raises the exception
     * @param array $values Parameters to mix with the exception message
     * @param Exception $previous Previous exception to concatenate both
     */
    public function __construct(
        int $code,
        int $sql_code = 0,
        string $sql_message = null,
        string $sql_script = null,
        array $values = null,
        Exception $previous = null
    ) {
        $this->sql_code = $sql_code;
        $this->sql_message = $sql_message;
        $this->sql_script = $sql_script;

        if ($code === ENabuDBException::ERROR_QUERY_EXECUTION && $sql_message !== null) {
            if (count($values) === 0) {
                $values = array($sql_message);
            } else {
                $values = array_unshift($values, $sql_message);
            }
        }

        parent::__construct($this->error_messages[$code], $code, $values, $previous);
    }

    /**
     * Get the database error code that raises the exception.
     * @return int Returns the Database Code
     */
    public function getSQLCode() : int
    {
        return $this->sql_code;
    }

    /**
     * Get the database error message that raises the exception.
     * @return string Database error message
     */
    public function getSQLMessage()
    {
        return $this->sql_message;
    }

    /**
     * Return the object sentence that raises the exception.
     * @return mixed Returns the object sentence that was raised the exception.
     */
    public function getSQLScript()
    {
        return $this->sql_script;
    }

    /**
     * Override this method in descendant classes to extend the attribute
     * $error_messages with more messages customized for each DB Engine.
     * Extended error messages can be numbered starting at 0x8001.
     */
    protected function overloadMessages()
    {
    }
}
