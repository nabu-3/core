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

use \nabu\data\CNabuDataObject;
use \nabu\core\CNabuEngine;
use \nabu\db\CNabuDBInternalObject;
use \nabu\db\CNabuDBObject;
use nabu\core\exceptions\ENabuCoreException;
use nabu\data\CNabuDataObjectList;

/**
 * Abstract Class to implement default management for Nabu core tables in MySQL.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 */
abstract class CNabuDBInternalObject extends CNabuDBObject
{
    public function __construct()
    {
        parent::__construct($this->isBuiltIn() ? null : CNabuEngine::getEngine()->getMainDB());
    }

    public static function getStorageType()
    {
        return CNabuDBInternalObject::DB_TYPE_TABLE;
    }

    public static function getCreationStorageSentence()
    {
        $filename = forward_static_call(array(get_called_class(), 'getStorageDescriptorPath'));

        if (file_exists($filename) && $filename === realpath($filename)) {
            $descriptor = json_decode(file_get_contents($filename), true);
            if (is_array($descriptor)) {
                $db_syntax = CNabuEngine::getEngine()->getMainDB()->getSyntaxBuilder();
                if ($db_syntax !== null) {
                    return $db_syntax->buildStorageCreationSentence($descriptor);
                }
            }
        }

        return null;
    }

    public function relinkDB()
    {
        $this->setDB(CNabuEngine::getEngine()->getMainDB());
    }

    public static function buildObjectFromSQL($sentence, $params = null, $trace = false)
    {
        return CNabuEngine::getEngine()
               ->getMainDB()
               ->getQueryAsObject('\\'.get_called_class(), $sentence, $params, $trace);
    }

    public static function buildSubClassingObjectFromSQL(
        $subclassing_field,
        $sentence,
        $params = null,
        $subclassing_default = null,
        $trace = false
    ) {
        if ($subclassing_default === null) {
            $subclassing_default = '\\'.get_called_class();
        }
        return CNabuEngine::getEngine()
               ->getMainDB()
               ->getQueryAsObjectWithSubClassing($subclassing_field, $sentence, $params, $subclassing_default, $trace);
    }

    public static function buildObjectListFromSQL(
        $index_field,
        $sentence,
        $params = null,
        CNabuDataObject $parent = null,
        $trace = false
    ) : CNabuDataObjectList
    {
        $called_class = get_called_class();
        $list_class = $called_class . 'List';
        $nb_engine = CNabuEngine::getEngine();

        if ($nb_engine->preloadClass($list_class)) {
            if ($parent === null) {
                $list = new $list_class();
            } else {
                $list = new $list_class($parent);
            }
            $list->mergeArray(
                CNabuEngine::getEngine()
                    ->getMainDB()
                    ->getQueryAsObjectArray('\\'.get_called_class(), $index_field, $sentence, $params, $trace)
            );

            return $list;
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_CLASS_NOT_FOUND, array($list_class));
        }
    }

    public static function buildSubClassingObjectListFromSQL(
        $index_field,
        $subclassing_field,
        $sentence,
        $params = null,
        $subclassing_default = null,
        $trace = false
    ) {
        if ($subclassing_default === null) {
            $subclassing_default = '\\'.get_called_class();
        }

        return CNabuEngine::getEngine()
               ->getMainDB()
               ->getQueryAsObjectArrayWithSubclassing(
                   $index_field,
                   $subclassing_field,
                   $sentence,
                   $params,
                   $subclassing_default,
                   $trace
               );
    }

    public static function buildObjectTreeFromSQL($index_field, $sentence, $params = null, $trace = false)
    {
        return nb_populateTreeFromObjectList(
            CNabuEngine::getEngine()
            ->getMainDB()
            ->getQueryAsObjectArray('\\'.get_called_class(), $index_field, $sentence, $params, $trace));
    }

    public static function buildSubClassingObjectTreeFromSQL(
        $index_field,
        $subclassing_field,
        $sentence,
        $params = null,
        $subclassing_default = null,
        $trace = false
    ) {
        if ($subclassing_default === null) {
            $subclassing_default = '\\'.get_called_class();
        }

        $list = CNabuEngine::getEngine()
                ->getMainDB()
                ->getQueryAsObjectArrayWithSubclassing(
                    $index_field,
                    $subclassing_field,
                    $sentence,
                    $params,
                    $subclassing_default,
                    $trace
                );

        return nb_populateTreeFromObjectList($list);
    }

    public static function buildTreeDataFromSQL($sentence, $params = null, $trace = false)
    {
        $tree = CNabuEngine::getEngine()
                ->getMainDB()
                ->getQueryAsObject('\\'.get_called_class(), $sentence, $params, $trace);

        if ($tree) {
            return $tree->getTreeData();
        }

        return false;
    }

    public static function buildTreeDataListFromSQL($index_field, $sentence, $params = null, $trace = false)
    {
        $list = CNabuEngine::getEngine()
                ->getMainDB()
                ->getQueryAsObjectArray('\\'.get_called_class(), $index_field, $sentence, $params, $trace);

        if ($list) {
            $tree = array();
            foreach ($list as $key => $value) {
                if ($value instanceof CNabuDataObject) {
                    $tree[$key] = $value->getTreeData();
                }
            }
            if (count($tree) > 0) {
                return $tree;
            }
        }

        return false;
    }
}
