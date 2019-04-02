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

use nabu\core\exceptions\ENabuSingletonException;

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
     * @test __construct
     * @test init
     * @test isInstantiated
     * @test getOS
     * @runInSeparateProcess
     */
    public function testConstructReset()
    {
        $this->assertIsObject($nb_os = new CNabuOS());
        $this->assertIsString($nb_os->getOSName());
        $this->expectException(ENabuSingletonException::class);
        $nb_os = new CNabuOS();
    }

    /**
     * @test isInstantiated
     * @depends testConstructReset
     */
    public function testIsInstantiated()
    {
        $this->assertFalse(CNabuOS::isInstantiated());
    }

    /**
     * @test __construct
     * @test getOS
     * @test init
     * @depends testIsInstantiated
     * @return CNabuOS Returns the OS instance.
     */
    public function testGetOS(): CNabuOS
    {
        $nb_os = CNabuOS::getOS();
        $this->assertIsObject($nb_os);
        $nb_os_2 = CNabuOS::getOS();
        $this->assertSame($nb_os, $nb_os_2);

        return $nb_os;
    }

    /**
     * @test __construct
     * @depends testIsInstantiated
     */
    public function testConstruct()
    {
        $this->expectException(ENabuSingletonException::class);
        $nb_os_3 = new CNabuOS();
    }

    /**
     * @test getOSName
     * @depends testGetOS
     * @param CNabuOS $nb_os OS instance created in previous tests.
     * @return CNabuOS Returns the OS instance.
     */
    public function testGetOSName(CNabuOS $nb_os): CNabuOS
    {
        $name = $nb_os->getOSName();
        $this->assertIsString($name);
        $this->assertRegExp('/^(Darwin|Linux|Windows)$/', $name);

        return $nb_os;
    }

    /**
     * @test getOSVersion
     * @depends testGetOS
     * @param CNabuOS $nb_os OS instance created in previous tests.
     * @return CNabuOS Returns the OS instance.
     */
    public function testGetOSVersion(CNabuOS $nb_os): CNabuOS
    {
        $this->assertIsString($nb_os->getOSVersion());

        return $nb_os;
    }

    /**
     * @test getOSArchitecture
     * @depends testGetOS
     * @param CNabuOS $nb_os OS instance created in previous tests.
     * @return CNabuOS Returns the OS instance.
     */
    public function testGetOSArchitecture(CNabuOS $nb_os): CNabuOS
    {
        $this->assertIsString($nb_os->getOSArchitecture());

        return $nb_os;
    }

    /**
     * @test getPHPVersion
     * @depends testGetOS
     * @param CNabuOS $nb_os OS instance created in previous tests.
     */
    public function testGetPHPVersion(CNabuOS $nb_os)
    {
        $this->assertIsArray($nb_os->getPHPVersion());

        return $nb_os;
    }
}
