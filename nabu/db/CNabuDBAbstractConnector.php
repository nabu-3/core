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

namespace nabu\db;

use \nabu\core\CNabuEngine;
use \nabu\core\CNabuObject;
use \nabu\db\interfaces\INabuDBConnector;

/**
 * Abstract base class to manage Database Connectors.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 */
abstract class CNabuDBAbstractConnector extends CNabuObject implements INabuDBConnector
{
    private $error_message = null;
    private $error_code = 0;

    protected $host = null;
    protected $port = null;
    protected $schema = null;
    protected $user = null;
    protected $password = null;
    protected $trace = false;
    protected $charset = null;
    protected $trailing_optimization = false;

    protected $duplicate_queries_trace = false;
    protected $duplicate_queries_list = null;

    protected $contains_logs = false;

    public function __construct($nb_database = null)
    {
        parent::__construct();

        if ($nb_database !== null) {
            if ($nb_database instanceof CNabuDataBase) {
                $this->configureFromNabuDataBaseObject($nb_database);
            } elseif (is_numeric($nb_database)) {
                $nb_database = new \nabu\databases\CNabuDataBase($nb_database);
                if ($nb_database->isFetched()) {
                    $this->configureFromNabuDataBaseObject($nb_database);
                }
            } elseif (is_string($nb_database) && file_exists($nb_database)) {
                $this->configureFromFile($nb_database);
            }
        }
    }

    public function getCharset()
    {
        return $this->charset;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getSchema()
    {
        return $this->schema;
    }

    public function isTraced()
    {
        return $this->trace;
    }

    public function isSpaceOptimizationEnabled()
    {
        return $this->trailing_optimization;
    }

    public function setSpaceOptimization(bool $status)
    {
        $this->trailing_optimization = $status;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    public function setHost($host)
    {
        $this->host = $host;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setPort($port)
    {
        $this->port = $port;
    }

    public function setSchema($schema)
    {
        $this->schema = $schema;
    }

    public function setTrace($trace)
    {
        $this->trace = $trace;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function containsLogsDatabase()
    {
        return $this->contains_logs;
    }

    public function setAsLogsDatabase($status)
    {
        $this->contains_logs = $status;
    }

    public function setDuplicateQueriesTrace($trace)
    {
        $this->duplicate_queries_trace = $trace;
    }

    protected function configureFromNabuDataBaseObject($nb_database)
    {
        if ($nb_database->contains('nb_database_host')) {
            $this->setHost($nb_database->getValue('nb_database_host'));
        }
        if ($nb_database->contains('nb_database_port')) {
            $this->setPort($nb_database->getValue('nb_database_port'));
        }
        if ($nb_database->contains('nb_database_schema')) {
            $this->setSchema($nb_database->getValue('nb_database_schema'));
        }
        if ($nb_database->contains('nb_database_user')) {
            $this->setUser($nb_database->getValue('nb_database_user'));
        }
        if ($nb_database->contains('nb_database_password')) {
            $this->setPassword($nb_database->getValue('nb_database_password'));
        }
    }

    protected function configureFromFile($filename)
    {
        if (file_exists($filename)) {
            include $filename;
            if (isset($db_host) && isset($db_port) && isset($db_user) && isset($db_passwd) && isset($db_schema)) {
                $this->setHost($db_host);
                $this->setPort($db_port);
                $this->setUser($db_user);
                $this->setPassword($db_passwd);
                $this->setSchema($db_schema);
                if (isset($db_trace_query)) {
                    $this->setTrace($db_trace_query);
                }
                if (isset($db_charset)) {
                    $this->setCharset($db_charset);
                }
            }
        }
    }

    public function getLastError()
    {
        if ($this->error_code !== 0 && $this->error_message !== null) {
            return array($this->error_code, $this->error_message);
        } else {
            return false;
        }
    }

    public function getLastErrorCode()
    {
        return $this->error_code;
    }

    public function getLastErrorMessage()
    {
        return $this->error_message;
    }

    protected function clearErrors()
    {
        $this->error_code = 0;
        $this->error_message = null;
    }

    protected function setError($message, $code = 0)
    {

        $this->error_message = $message;
        $this->error_code = $code;
    }

    protected function analyzeDuplicatedQuery($query)
    {
        if (!$this->duplicate_queries_trace) {
            return;
        }

        $query = preg_replace('/^\s*/', '', preg_replace('/\s*$/', '', $query));
        $p = strpos($query, ' ');
        if ($p === false) {
            return;
        }

        $command = strtoupper(substr($query, 0, $p));

        if (count($this->duplicate_queries_list) === 0) {
            $this->duplicate_queries_list = array();
        }

        if (!array_key_exists($command, $this->duplicate_queries_list)) {
            $this->duplicate_queries_list[$command] = array();
        }

        if (!array_key_exists($query, $this->duplicate_queries_list[$command])) {
            $this->duplicate_queries_list[$command] = array($query => 1);
        } else {
            $this->duplicate_queries_list[$command][$query]++;
        }
    }

    public function dumpDuplicatedQueries()
    {
        $nb_engine = CNabuEngine::getEngine();

        $nb_engine->errorLog(print_r($this->duplicate_queries_list, true));
        if ($this->duplicate_queries_trace) {
            if (count($this->duplicate_queries_list) === 0) {
                $nb_engine->traceLog(null, 'No duplicate queries found');
            } else {
                foreach ($this->duplicate_queries_list as $command => $arr) {
                    foreach ($arr as $query => $iter) {
                        $nb_engine->traceLog(null, "$command duplicated $iter times: $query");
                    }
                }
            }
        }
    }
}
