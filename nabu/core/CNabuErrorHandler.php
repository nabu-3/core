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

namespace nabu\core;

/**
 * Class to manage the Error Handler and Error Storage
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\core
 */
class CNabuErrorHandler extends CNabuObject
{
    /** @var string Internal literal */
    private const MESSAGES_STACK = 'messages_stack';
    /** @var string Internal literal */
    private const LINE_CLASS = 'class';
    /** @var string Internal literal */
    private const LINE_FUNCTION = 'function';

    /**
     * Contains the instace of CNabuEngine that owns this handler
     * @var CNabuEngine
     */
    private $nb_engine = null;
    /**
     * Enable/disable trace log
     * @var bool
     */
    private $trace_log_enabled = false;
    /**
     * Cached log to display all error lines together
     * @var array
     */
    private $error_log_buffer = null;
    /**
     * Cached log to display all trace lines together
     * @var array
     */
    private $trace_log_buffer = null;

    public function __construct(CNabuEngine $nb_engine)
    {
        parent::__construct();

        $this->nb_engine = $nb_engine;

        set_error_handler(array($this, 'errorHandler'), E_ALL);
    }

    /**
     * Error handler function. Dumps the error to errors log file and stores internally
     * to send at the end of the dispatch method, to a logs store.
     * @param int $errno Error level
     * @param string $errstr Error message
     * @param string $errfile File name where the error was generated
     * @param string $errline File line where the error was generated
     * @param array $errcontext Matrix as described in {@see http://php.net/manual/en/function.set-error-handler.php}
     * @return bool Returns true if the error is treated and the execution can continue,
     * or false if the execution needs to be finished now.
     */
    public function errorHandler(int $errno, string $errstr, string $errfile, string $errline, array $errcontext)
    {
        $this->dumpStack($errstr, $errno, $errfile, $errline, $errcontext);
        return true;
    }

    /**
     * Analizes and creates a Stack sequence of call from the beginning of the execution
     * until the point of error and log each step in the Engine Log store.
     * @global string $NABU_MESSAGE_TYPE_NAME
     * @param string|null $message Error message
     * @param string|null $type Error type/level
     * @param string|null $errfile File name where the error was generated
     * @param int|null $errline File line where the error was generated
     * @param mixed|null $errcontext Matrix as described in {@see http://php.net/manual/en/function.set-error-handler.php}
     * @param array|null $stack Complete list of stack calls from the beginning of the execution until the point of error
     */
    public function dumpStack(
        string $message = null,
        string $type = null,
        string $errfile = null,
        int $errline = null,
        $errcontext = null,
        array $stack = null
    ) {
        global $NABU_MESSAGE_TYPE_NAME;

        if (($errtype = nb_getArrayValueByKey($type, $NABU_MESSAGE_TYPE_NAME)) === null) {
            $this->nb_engine->errorLog("Unknow error type [$type]: $message in $errfile [line:$errline]");
        } else {
            $this->nb_engine->errorLog("$errtype: $message in $errfile [line:$errline]");
        }

        if ($stack === null) {
            $stack = debug_backtrace(true);
        }
        $count = -1;

        do {
            foreach ($stack as $line) {
                if ($count < 0 &&
                    (!array_key_exists('file', $line) ||
                        !array_key_exists('line', $line) ||
                        $line['file'] !== $errfile ||
                        $line['line'] !== $errline
                    )
                ) {
                    continue;
                } elseif (array_key_exists('file', $line) &&
                          $line['file'] === $errfile && $line['line'] === $errline
                ) {
                    $count++;
                    continue;
                }

                $source = '';

                if (array_key_exists(self::LINE_CLASS, $line)) {
                    $source .= $line[self::LINE_CLASS];
                }
                if (array_key_exists('type', $line)) {
                    $source .= $line['type'];
                }
                if (array_key_exists(self::LINE_FUNCTION, $line)) {
                    $source .= $line[self::LINE_FUNCTION];
                }
                if (array_key_exists(self::LINE_CLASS, $line) &&
                    array_key_exists('type', $line) &&
                    array_key_exists(self::LINE_FUNCTION, $line) &&
                    array_key_exists('args', $line) &&
                    is_array($line['args']) &&
                    count($line['args']) > 0 &&
                    $line[self::LINE_CLASS] == 'nabu\\core\\CNabuPluginManager' &&
                    $line[self::LINE_FUNCTION] == 'invoqueCommand'
                ) {
                    $source .= ' calling command ['.$line['args'][0].']';
                }
                if (array_key_exists('file', $line)) {
                    $source .= ' in '.$line['file'];
                }
                if (array_key_exists('line', $line)) {
                    $source .= " [line:$line[line]]";
                }

                $this->nb_engine->errorLog("#$count     at $source");

                $count++;
            }
            $count++;
        } while ($count < 1);
    }

    /**
     * Traces a error log message in the error log default system and stores it inside
     * the instance to be transmited after to a logs analyzer database.
     * @param string $message Text message to log
     */
    public function errorLog(string $message)
    {
        error_log(">>> " . $message);

        $this->error_log_buffer[] = $message;
    }

    /**
     * Traces a categorized data in the error log default system and stores it inside
     * the instance to be transmited after to a logs analyzer database.
     * @param string|null $key Categorized data key identifier
     * @param mixed|null $message Text message to log
     */
    public function traceLog(string $key = null, $message = null)
    {
        if ($this->trace_log_enabled) {
            if (is_array($message) || is_object($message)) {
                $text = print_r($message, true);
            } elseif ($message === null) {
                $text = "[null]";
            } elseif ($message === false) {
                $text = "[false]";
            } elseif ($message === true) {
                $text = "[true]";
            } else {
                $text = $message;
            }
            error_log("=== " . ($key !== null ? "$key: " : '') . $text);
        }

        if ($key === null) {
            if ($this->trace_log_buffer === null) {
                $this->trace_log_buffer = array();
            }
            if (array_key_exists(self::MESSAGES_STACK, $this->trace_log_buffer)) {
                $this->trace_log_buffer[self::MESSAGES_STACK][] = $message;
            } else {
                $this->trace_log_buffer[self::MESSAGES_STACK] = array($message);
            }
        } else {
            $this->trace_log_buffer[$key] = $message;
        }
    }

    /**
     * Enables trace
     */
    public function enableTracer()
    {
        $this->trace_log_enabled = true;
    }

    /**
     * Disables trace
     */
    public function disableTracer()
    {
        $this->trace_log_enabled = false;
    }
}
