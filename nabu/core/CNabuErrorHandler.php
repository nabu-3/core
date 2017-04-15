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

namespace nabu\core;

use nabu\core\exceptions\ENabuCoreException;

/**
 * Class to manage the Error Handler and Error Storage
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\core
 */
class CNabuErrorHandler extends CNabuObject
{
    /**
     * Contains the instace of CNabuEngine that owns this handler
     * @var type CNabuEngine
     */
    private $nb_engine = null;
    /**
     * Enable/disable trace log
     * @var boolean
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

    public function __construct($nb_engine)
    {
        if (!($nb_engine instanceof CNabuEngine)) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_UNEXPECTED_PARAM_CLASS_TYPE,
                    array(get_class($nb_engine), '$nb_engine')
            );
        }

        parent::__construct();

        $this->nb_engine = $nb_engine;

        set_error_handler(array($this, 'errorHandler'), E_ALL);
    }

    /**
     * Error handler function. Dumps the error to errors log file and stores internally
     * to send at the end of the dispatch method, to a logs store.
     * @param type $errno Error level
     * @param type $errstr Error message
     * @param type $errfile File name where the error was generated
     * @param type $errline File line where the error was generated
     * @param type $errcontext Matrix as described in {@see http://php.net/manual/en/function.set-error-handler.php}
     * @return boolean Returns true if the error is treated and the execution can continue,
     * or false if the execution needs to be finished now.
     */
    public function errorHandler($errno, $errstr, $errfile, $errline, $errcontext)
    {
        $this->dumpStack($errstr, $errno, $errfile, $errline, $errcontext);
        return true;
    }

    /**
     * Analizes and creates a Stack sequence of call from the beginning of the execution
     * until the point of error and log each step in the Engine Log store.
     * @global type $NABU_MESSAGE_TYPE_NAME
     * @param type $message Error message
     * @param type $type Error type/level
     * @param type $errfile File name where the error was generated
     * @param type $errline File line where the error was generated
     * @param type $errcontext Matrix as described in {@see http://php.net/manual/en/function.set-error-handler.php}
     * @param type $stack Complete list of stack calls from the beginning of the execution until the point of error
     */
    public function dumpStack(
        $message = null,
        $type = null,
        $errfile = null,
        $errline = null,
        $errcontext = null,
        $stack = null
    ) {
        global $NABU_MESSAGE_TYPE_NAME;

        $nb_engine = CNabuEngine::getEngine();

        if (($errtype = nb_getArrayValueByKey($type, $NABU_MESSAGE_TYPE_NAME)) === null) {
            $nb_engine->errorLog("Unknow error type [$type]: $message in $errfile [line:$errline]");
        } else {
            $nb_engine->errorLog("$errtype: $message in $errfile [line:$errline]");
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

                if (array_key_exists('class', $line)) {
                    $source .= $line['class'];
                }
                if (array_key_exists('type', $line)) {
                    $source .= $line['type'];
                }
                if (array_key_exists('function', $line)) {
                    $source .= $line['function'];
                }
                if (array_key_exists('class', $line) &&
                    array_key_exists('type', $line) &&
                    array_key_exists('function', $line) &&
                    array_key_exists('args', $line) &&
                    count($line['args'] > 0) &&
                    $line['class'] == 'nabu\\core\\CNabuPluginManager' &&
                    $line['function'] == 'invoqueCommand'
                ) {
                    $source .= ' calling command ['.$line['args'][0].']';
                }
                if (array_key_exists('file', $line)) {
                    $source .= ' in '.$line['file'];
                }
                if (array_key_exists('line', $line)) {
                    $source .= " [line:$line[line]]";
                }

                $nb_engine->errorLog("#$count     at $source");

                $count++;
            }
            $count++;
        } while ($count < 1);
    }

    /**
     * Traces a error log message in the error log default system and stores it inside
     * the instance to be transmited after to a logs analyzer database.
     * @param type $message Text message to log
     */
    public function errorLog($message)
    {
        error_log(">>> " . $message);

        $this->error_log_buffer[] = $message;
    }

    /**
     * Traces a categorized data in the error log default system and stores it inside
     * the instance to be transmited after to a logs analyzer database.
     * @param type $key Categorized data key identifier
     * @param type $message Text message to log
     */
    public function traceLog($key, $message)
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
            error_log("=== " . ($key !== null ? "$key: " : '') . (is_string($message) ? $message : print_r($message, true)));
        }

        if ($key === null) {
            if ($this->trace_log_buffer === null) {
                $this->trace_log_buffer = array();
            }
            if (array_key_exists('messages_stack', $this->trace_log_buffer)) {
                $this->trace_log_buffer['messages_stack'][] = $message;
            } else {
                $this->trace_log_buffer['messages_stack'] = array($message);
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
