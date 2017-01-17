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

/**
 * Base class for all classes. Implements basic functionalities of classes.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\core
 */
class CNabuObject
{
    const HASH_PREFIX = "#9120@";
    const HASH_GLUE = "9234sdhcfjsd";
    const HASH_POSTFIX = "0123190=";
    
    /**
     * Timestamp of instance creation
     * @var int
     */
    private $timestamp;
    
    /**
     * Hash to identify an instance across the entire collection in your class
     * @var string
     */
    private $hash = false;
    
    /**
     * Default constructor. Assign current timestamp to $timestamp
     */
    public function __construct()
    {
        $this->timestamp = time();
    }

    /**
     * Get creation timestamp
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
    
    /**
     * Check if an instance is of type Built-in.
     * @return boolean Returns true if the instance is of type BuiltIn
     */
    public function isBuiltIn()
    {
        return false;
    }
    
    /**
     * Create a new hash for an instance
     */
    public function createHash()
    {
        srand((int)((double)  microtime(true) * 1E+9));
        $this->hash = md5(
                CNabuObject::HASH_PREFIX
                . get_called_class()
                . CNabuObject::HASH_GLUE
                . rand(0, getrandmax())
                . CNabuObject::HASH_GLUE
                . $this->timestamp
                . CNabuObject::HASH_POSTFIX
        );
        
        return $this->hash;
    }
    
    public function getHash()
    {
        return ($this->hash ? $this->hash : $this->createHash());
    }
}
