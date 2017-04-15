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

use nabu\core\CNabuObject;
use nabu\core\exceptions\ENabuSingletonException;
use nabu\core\interfaces\INabuSingleton;

/**
 * This class gets all information about the running OS
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.12 Surface
 * @package nabu\core
 */
class CNabuOS extends CNabuObject implements INabuSingleton
{
    /**
     * Contains the singleton instance of class.
     * @var CNabuOS
     */
    private static $nb_os = null;
    /**
     * OS name
     * @var string
     */
    private $os_name = null;
    /**
     * OS version
     * @var string
     */
    private $os_version = null;
    /**
     * OS architecture
     * @var string
     */
    private $os_architecture = null;
    /**
     * PHP version
     * @var string
     */
    private $php_version = null;

    /**
     * Default constructor. Controls if is already instantiated and throws an exception.
     * @throws ENabuSingletonException Throws this exception if this class
     * is already instantiated.
     */
    public function __construct()
    {
        if (CNabuOS::$nb_os !== null) {
            throw new ENabuSingletonException("OS already instantiated");
        }

        parent::__construct();
    }

    /**
     * Get the singleton instance of CNabuOS.
     * @return CNabuOS Returns the singleton instance of CNabuOS
     */
    public static function getOS()
    {
        if (CNabuOS::$nb_os === null) {
            CNabuOS::$nb_os = new CNabuOS();
            CNabuOS::$nb_os->init();
        }

        return CNabuOS::$nb_os;
    }

    /**
     * Check if the class is instantiated.
     * @return bool Returns true if this singleton class is instantiated.
     */
    public static function isInstantiated() : bool
    {
        return (CNabuOS::$nb_os !== null);
    }

    /**
     * Initiates the singleton instance and gater the OS information.
     */
    private function init()
    {
        $this->os_name = php_uname('s');
        $this->os_version = php_uname('r');
        $this->os_architecture = php_uname('m');
        $this->php_version = preg_split('/\\./', phpversion());
    }

    public function getOSName()
    {
        return $this->os_name;
    }

    public function getOSVersion()
    {
        return $this->os_version;
    }

    public function getOSArchitecture()
    {
        return $this->os_architecture;
    }

    public function getPHPVersion()
    {
        return $this->php_version;
    }
}
