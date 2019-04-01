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

use nabu\core\CNabuOS;
use nabu\core\CNabuEngine;

/**
 * PHPUnit tests to verify functionality of class @see CNabuOS
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package tests\nabu\core
 */
class CNabuOSTest extends TestCase
{
    /**
     * Constructor overrided to instantiate Nabu Engine.
     */
    public function __construct()
    {
        parent::__construct();

        //CNabuEngine::getEngine();
    }

    /**
     * @test isInstantiated
     */
    public function testIsInstantiated()
    {
        $this->assertFalse(CNabuOS::isInstantiated());
    }

    /**
     * @test getInstance
     * @test init
     * @depends testIsInstantiated
     * @return CNabuOS Returns the OS instance.
     */
    public function testGetInstance(): CNabuOS
    {
        $nb_os = CNabuOS::getInstance();
        $this->assertIsObject($nb_os);
        $nb_os_2 = CNabuOS::getInstance();
        $this->assertSame($nb_os, $nb_os_2);

        return $nb_os;
    }

    /**
     * @test getOSName
     * @depends testIsInstantiated
     * @param CNabuOS $nb_os OS instance created in previous tests.
     */
    public function testGetOSName(CNabuOS $nb_os)
    {
        $this->assertIsString($nb_os->getOSName());
    }

    /**
     * @test getOSVersion
     * @depends testIsInstantiated
     * @after testGetOSName
     * @param CNabuOS $nb_os OS instance created in previous tests.
     */
    public function testGetOSVersion(CNabuOS $nb_os)
    {
        $this->assertIsString($nb_os->getOSVersion());
    }

    /**
     * @test getOSArchitecture
     * @depends testIsInstantiated
     * @after testGetOSVersion
     * @param CNabuOS $nb_os OS instance created in previous tests.
     */
    public function testGetOSArchitecture(CNabuOS $nb_os)
    {
        $this->assertIsString($nb_os->getOSArchitecture());
    }

    /**
     * @test getPHPVersion
     * @depends testIsInstantiated
     * @after testGetOSArchitecture
     * @param CNabuOS $nb_os OS instance created in previous tests.
     */
    public function testGetPHPVersion(CNabuOS $nb_os)
    {
        $this->assertIsArray($nb_os->getPHPVersion());
    }
}
