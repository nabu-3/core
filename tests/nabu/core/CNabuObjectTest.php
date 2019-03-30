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

namespace tests\nabu\core;

use PHPUnit\Framework\TestCase;

use nabu\core\CNabuEngine;
use nabu\core\CNabuObject;

require_once "common.php";

class CNabuObjectTest extends TestCase
{
    /**
     * Constructor overrided to instantiate Nabu Engine.
     */
    public function __construct()
    {
        parent::__construct();

        CNabuEngine::getEngine();
    }

    /**
     * @test getTimestamp
     */
    public function testGetTimestamp()
    {
        $start_time = time();
        $nb_object = new CNabuObject();
        $current_time = $nb_object->getTimestamp();
        $max_time = time();

        $this->assertGreaterThanOrEqual($start_time, $current_time);
        $this->assertLessThanOrEqual($max_time, $current_time);
    }

    /**
     * @test isBuiltIn
     */
    public function testIsBuiltIn()
    {
        $nb_object = new CNabuObject();

        $this->assertFalse($nb_object->isBuiltIn());
    }

    /**
     * @test createHash
     * @test getHash
     * @test ::nb_isValidGUID
     */
    public function testCreateAndGetHash_1()
    {
        $nb_object = new CNabuObject();

        $hash = $nb_object->createHash();
        $this->assertTrue(nb_isValidGUID($hash));

        $hash = $nb_object->getHash();
        $this->assertTrue(nb_isValidGUID($hash));
    }

    /**
     * @test getHash
     * @test ::nb_isValidGUID
     */
    public function testCreateAndGetHash_2()
    {
        $nb_object = new CNabuObject();

        $hash = $nb_object->getHash();
        $this->assertTrue(nb_isValidGUID($hash));
    }
}
