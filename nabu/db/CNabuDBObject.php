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

use \nabu\data\CNabuDataObject;
use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\db\exceptions\ENabuDBException;
use \nabu\db\interfaces\INabuDBConnector;
use \nabu\db\interfaces\INabuDBObject;
use \nabu\db\interfaces\INabuDBStatement;
use \providers\mysql\CMySQLConnector;
use nabu\db\interfaces\INabuDBDescriptor;

/**
 * Abstract Class to implement default management for tables of MySQL.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 */
abstract class CNabuDBObject extends CNabuDataObject implements INabuDBObject
{
    const DB_TYPE_TABLE = 'TABLE';
    const DB_TYPE_VIEW = 'VIEW';

    /**
     * Database connector
     * @var INabuDBConnector
     */
    protected $db = null;
    /**
     * @var bool True if the instance is new
     */
    protected $isNew = false;
    /**
     * @var bool True if the instance if fetched from the database
     */
    protected $isFetched = false;
    /**
     * @var bool True if the instance has been deleted
     */
    protected $isDeleted = false;
    /**
     * Descriptor of the storage
     * @var INabuDBDescriptor
     */
    protected $storage_descriptor = null;
    /**
     * Where primary filter sequence. This variable is emptied each time that we call the fill method.
     * @var string
     */
    private $where_primary_filter = false;

    public function __construct(INabuDBConnector $db = null)
    {
        if ($db === null && !$this->isBuiltIn() && !CNabuEngine::getEngine()->isOperationModeStandalone()) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_CONSTRUCTOR_PARAMETER_IS_EMPTY, '$db');
        }

        parent::__construct();

        $this->db = $db;
        $this->load();
    }

    public function setDB(INabuDBConnector $connector)
    {
        if ($connector === null) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_METHOD_PARAMETER_IS_EMPTY,
                array('setDB', '$connector')
            );
        } elseif (!($connector instanceof CMySQLConnector)) {
            throw new ENabuDBException(
                ENabuDBException::ERROR_DATABASE_TYPE_NOT_ALLOWED,
                array($connector->getDriverName())
            );
        }

        $this->db = $connector;

        if ($this->storage_descriptor !== null) {
            $this->getDescriptor(true);
        }
    }

    public static function createStorage(INabuDBConnector $connector = null)
    {
        if (CNabuEngine::getEngine()->isInstallMode()) {
            $script = forward_static_call(array(get_called_class(), 'getCreationStorageSentence'));
            if (strlen($script) > 0) {
                if (!$connector) {
                    $nb_engine = CNabuEngine::getEngine();
                    $connector = $nb_engine->getMainDatabase();
                }
                if ($connector instanceof INabuDBConnector && $connector->isConnected()) {
                    $connector->executeSentence($script);
                    return !$connector->checkForWarning(CMySQLConnector::WARNING_TABLE_ALREADY_EXISTS);
                } else {
                    throw new ENabuCoreException(ENabuCoreException::ERROR_INSTALL_DB_NOT_FOUND);
                }
            } else {
                throw new ENabuDBException(ENabuDBException::ERROR_CREATION_SCRIPT_NOT_PRESENT);
            }
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_INSTALL_MODE_REQUIRED);
        }
    }

    /**
     * Gets the Storage Descriptor. When calls this method the first time, an internal INabuDBDescriptor object
     * is created and returned next times.
     * @param bool $force If true the descriptor is recreated from scratch.
     * @return INabuDBDescriptor Returns the available descriptor instance.
     * @throws ENabuDBException Raises an exception if something happens when load the descriptor
     * from their serialized file.
     */
    public function getDescriptor($force = false)
    {
        if (!($this->storage_descriptor instanceof INabuDBDescriptor) || $force) {
            $this->storage_descriptor = null;
            $this->storage_descriptor = $this->db->getDescriptorFromFile($this->getStorageDescriptorPath());
        }

        return $this->storage_descriptor;
    }

    public function isFetched()
    {
        return $this->isFetched;
    }

    public function isNew()
    {
        return $this->isNew;
    }

    public function isDeleted()
    {
        return $this->isDeleted;
    }

    public function buildSentence($sentence, $params = null)
    {
        $retval = '';

        if (count($this->data) === 0) {
            if (count($params) === 0) {
                $retval = $sentence;
            } else {
                $retval = $this->db->buildSentence($sentence, $params);
            }
        } else {
            if (count($params) === 0) {
                $retval = $this->db->buildSentence($sentence, $this->data);
            } else {
                $retval = $this->db->buildSentence($sentence, array_merge($this->data, $params));
            }
        }

        return $retval;
    }

    protected function getWherePrimaryFilter($force = false)
    {
        if (!$this->where_primary_filter || $force) {
            $this->where_primary_filter = false;
            if (!$this->getDescriptor()) {
                throw new ENabuDBException(ENabuDBException::ERROR_STORAGE_DESCRIPTOR_NOT_AVAILABLE);
            } else {
                $this->where_primary_filter = $this->storage_descriptor->buildDocumentIdentifierFilter($this->data);
            }
        }

        return $this->where_primary_filter;
    }

    public function implodeIntegerArray($array, $glue)
    {
        return $this->db->implodeIntegerArray($array, $glue);
    }

    public function implodeStringArray($array, $glue)
    {
        return $this->db->implodeStringArray($array, $glue);
    }

    public function reset()
    {
        parent::reset();
        $this->fill();
    }

    public function fill()
    {
        $this->isFetched = is_array($this->data);
        $this->isNew = !$this->isFetched;
        $this->isDeleted = false;
        $this->where_primary_filter = false;
        return $this->isFetched;
    }

    public function load($sentence = false, $params = false, $trace = false)
    {
        if (!$sentence) {
            $sentence = $this->getSelectRegister();
        } else {
            if (count($this->data) > 0) {
                if (count($params) > 0) {
                    $preserve = array_merge($this->data, $params);
                } else {
                    $preserve = $this->data;
                }
            } else {
                if (count($params) > 0) {
                    $preserve = $params;
                } else {
                    $preserve = null;
                }
            }
            $sentence = $this->buildSentence($sentence, $preserve);
        }

        $this->reset();

        if ($sentence && strlen($sentence) > 0) {
            $statement = $this->db->getQuery($sentence, null, $trace);
            $this->fetch($statement);
            $statement->release();
        }

        return $this->fill();
    }

    public function loadForUpdate($query = false, $params = false, $trace = false)
    {
        $this->reset();

        if (!$query) {
            $query = $this->getSelectRegister();
        }

        if (strlen($query) > 0) {
            return $this->load("$query for update", $params, $trace);
        } else {
            return false;
        }
    }

    public function fetch($statement)
    {
        $this->reset();

        if ($statement instanceof INabuDBStatement) {
            $this->data = $statement->fetchAsAssoc();
        } elseif (is_array($statement)) {
            $this->data = $statement;
        } else {
            $this->data = mysql_fetch_assoc($statement);
        }

        $this->push();

        return $this->fill();
    }

    public function save($trace = false)
    {
        $table = $this->getStorageName();

        if (!is_string($table)) {
            throw new ENabuDBException(ENabuDBException::ERROR_ATTEMPT_TO_WRITE_WITHOUT_STORAGE);
        }

        if ($this->getDescriptor() === null) {
            throw new ENabuDBException(ENabuDBException::ERROR_STORAGE_DESCRIPTOR_NOT_AVAILABLE);
        }

        $retval = false;

        if (!$this->isEmpty()) {
            $fields = $this->storage_descriptor->getFieldNames();
            $keys = $this->storage_descriptor->getPrimaryConstraintFieldNames();
            $ai_key = $this->storage_descriptor->getPrimaryConstraintAutoIncrementName();

            if ($keys !== false && $this->isFetched()) {
                $fields = array_diff($fields, $keys);
            } elseif ($ai_key !== false && $this->isNew()) {
                $fields = array_diff($fields, array($ai_key));
            }

            $set_chain = '';

            if (count($fields) > 0) {
                foreach ($fields as $field_name) {
                    if ($this->isValueModified($field_name)) {
                        $field = $this->storage_descriptor->getField($field_name);
                        $value = $this->storage_descriptor->buildFieldValue($field, $this->getValue($field_name));
                        if ($value !== false && strlen($value) > 0) {
                            $set_chain .= (strlen($set_chain) > 0 ? ', ' : '') . "$field_name=$value";
                        }
                    }
                }
            }

            if (strlen($set_chain) > 0) {
                if ($this->isFetched) {
                    $where = $this->storage_descriptor->buildDocumentIdentifierFilter($this->data);
                    $retval = (
                        $this->db->executeUpdate("update $table set $set_chain where $where", null, $trace) !== false
                    );
                    $this->push();
                } else {
                    $retval = (
                        $this->db->executeInsert("insert into $table set $set_chain", null, $trace) !== false
                    );
                    if ($retval) {
                        if ($ai_key !== null) {
                            $this->setValue($ai_key, $this->db->getLastInsertedId());
                        }
                        $this->fill();
                        $retval = $this->load();
                    }
                }
            }
        }

        return $retval;
    }

    public function delete()
    {
        $table = $this->getStorageName();

        if (!is_string($table)) {
            throw new ENabuDBException(ENabuDBException::ERROR_ATTEMPT_TO_DELETE_WITHOUT_STORAGE);
        }

        if ($this->getDescriptor() === null) {
            throw new ENabuDBException(ENabuDBException::ERROR_STORAGE_DESCRIPTOR_NOT_AVAILABLE);
        }

        if (!$this->isDeleted()) {
            if ($this->getWherePrimaryFilter() === false || !is_string($this->where_primary_filter)) {
                throw new ENabuDBException(ENabuDBException::ERROR_ATTEMPT_TO_DELETE_WITH_EMPTY_WHERE);
            }
            $this->db->executeDelete("delete from $table where $this->where_primary_filter", null, true);
            $this->isFetched = false;
            $this->isNew = false;
            $this->isDeleted = true;
        }

        return $this->isDeleted;
    }

    public function buildStringAssignation($field, $def_value = false, $retnull = true)
    {
        if ($this->contains($field)) {
            if ($this->isValueNull($field)) {
                return ($retnull ? "$field=null" : false);
            } else {
                return $this->buildSentence("$field='%$field\$s'");
            }
        } elseif ($def_value !== false) {
            if ($def_value === null) {
                return "$field=null";
            } else {
                return $this->buildSentence("$field='%value\$s'", array('value' => $def_value));
            }
        } elseif ($retnull) {
            return "$field=null";
        } else {
            return false;
        }
    }

    public function buildIntegerAssignation($field, $def_value = false, $retnull = true)
    {
        if ($this->contains($field)) {
            if ($this->isValueEmpty($field)) {
                return ($retnull ? "$field=null" : false);
            } elseif ($this->isValueNumeric($field)) {
                return $this->buildSentence("$field=%$field\$d");
            } else {
                throw new ENabuCoreException(
                    ENabuCoreException::ERROR_INVALID_FIELD_CONVERSION_TO_INTEGER,
                    array($field, $this->getValue($field))
                );
            }
        } elseif ($def_value !== false) {
            if ($def_value === null) {
                return "$field=null";
            } else {
                return $this->buildSentence("$field=%d", array($def_value));
            }
        } elseif ($retnull) {
            return "$field=null";
        } else {
            return false;
        }
    }

    public function buildFloatAssignation($field, $def_value = false, $retnull = true)
    {
        if ($this->contains($field)) {
            if ($this->isValueEmpty($field)) {
                return ($retnull ? "$field=null" : false);
            } elseif ($this->isValueFloat($field)) {
                $num = str_replace(',', '.', $this->getValue($field));
                return $this->buildSentence("$field=%val\$s", array('val' => $num));
            } else {
                $value = $this->getValue($field);
                if ($value === null) {
                    $value = '<null>';
                } elseif ($value === false) {
                    $value = '<false>';
                }
                throw new ENabuCoreException(
                    ENabuCoreException::ERROR_INVALID_FIELD_CONVERSION_TO_FLOAT,
                    array($field, $value)
                );
            }
        } elseif ($def_value !== false) {
            if ($def_value === null) {
                return "$field=null";
            } elseif (is_numeric($def_value)) {
                return $this->buildSentence("$field=%F", array($def_value));
            } else {
                throw new ENabuCoreException(ENabuCoreException::ERROR_INVALID_FLOAT_NUMBER, array($def_value));
            }
        } elseif ($retnull) {
            return "$field=null";
        } else {
            return false;
        }
    }

    public function concatDBFragments($list, $glue = false)
    {
        if (count($list) > 0) {
            $concat = '';
            foreach ($list as $fragment) {
                if ($fragment !== false && strlen($fragment) > 0) {
                    $concat .= (strlen($concat) > 0 ? $glue : '').$fragment;
                }
            }
            return (strlen($concat) > 0 ? $concat : false);
        }

        return false;
    }

    public function getTreeData($nb_language = null, $dataonly = false)
    {
        $trdata = parent::getTreeData($nb_language, $dataonly);
        $storage_name = $this->getStorageName() . '_';
        $lsn = strlen($storage_name);

        if (is_array($trdata)) {
            $tree = array();
            foreach ($trdata as $key => $value) {
                if (nb_strStartsWith($key, $storage_name)) {
                    $key = substr($key, $lsn);
                } elseif (nb_strStartsWith($key, 'nb_')) {
                    $key = substr($key, 3);
                }
                $tree[$key] = $value;
            }
            $trdata = (count($tree) > 0 ? $tree : null);
        }

        return $trdata;
    }
}
