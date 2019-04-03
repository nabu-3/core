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

use nabu\core\exceptions\ENabuSingletonException;

/**
 * PHPUnit tests to verify functionality of class @see CNabuEngine
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package tests\nabu\core
 */
class CNabuEngineTest extends TestCase
{
    /**
     * @test __construct
     * @test init
     * @test isInstantiated
     * @test getEngine
     * @runInSeparateProcess
     */
    public function testConstruct()
    {
        $this->assertIsObject($nb_os = new CNabuEngine());
        $this->expectException(ENabuSingletonException::class);
        $nb_os = new CNabuEngine();
    }

    /**
     * @test isInstantiated
     * @test getEngine
     * @runInSeparateProcess
     */
    public function testIsInstantiated()
    {
        $this->assertFalse(CNabuEngine::isInstantiated());
        $nb_engine = CNabuEngine::getEngine();
        $this->assertIsObject($nb_engine);
        $this->expectException(ENabuSingletonException::class);
        $nb_engine_2 = new CNabuEngine();
    }

    /**
     * @test getEngine
     * @runInSeparateProcess
     */
    public function testGetEngine()
    {
        $this->assertIsObject($nb_engine = CNabuEngine::getEngine());
        $this->assertSame($nb_engine, CNabuEngine::getEngine());
    }

    /**
     * @test isCLIEnvironment
     */
    public function testIsCLIEnvironment()
    {
        $nb_engine = CNabuEngine::getEngine();
        $this->assertIsObject($nb_engine);
        $this->assertTrue($nb_engine->isCLIEnvironment());
    }

    /**
     * @test isInstallMode
     * @test setInstallMode
     */
    public function testIsInstallMode()
    {
        $nb_engine = CNabuEngine::getEngine();
        $this->assertFalse($nb_engine->isInstallMode());
        $nb_engine->setInstallMode(true);
        $this->assertTrue($nb_engine->isInstallMode());
        $nb_engine->setInstallMode(false);
        $this->assertFalse($nb_engine->isInstallMode());
    }
}
