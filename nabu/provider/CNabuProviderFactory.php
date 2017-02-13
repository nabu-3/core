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

namespace nabu\provider;
use Exception;
use nabu\core\CNabuEngine;
use nabu\core\CNabuObject;
use nabu\core\base\CNabuAbstractApplication;
use nabu\core\exceptions\ENabuSingletonException;
use nabu\core\interfaces\INabuSingleton;
use nabu\provider\exceptions\ENabuProviderException;
use nabu\provider\interfaces\INabuProviderManager;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @since 3.0.9 Surface
 * @version 3.0.9 Surface
 * @package \nabu\core\interfaces
 */
class CNabuProviderFactory extends CNabuObject implements INabuSingleton
{
    /**
     * Contains the singleton instance of class.
     * @var CNabuProviderFactory
     */
    private static $nb_provider_factory = null;
    /**
     * List of all registered managers.
     * @var INabuProviderManager
     */
    private $nb_manager_list = null;
    /**
     * Index of vendors and managers.
     * @var array
     */
    private $manager_hierarchy = null;

    /**
     * Default constructor for singleton instance.
     * @throws ENabuSingletonException If the constructor is called more than one times, then raises an exception.
     */
    public function __construct()
    {
        if (CNabuProviderFactory::$nb_provider_factory !== null) {
            throw new ENabuSingletonException('Provider Factory already instantiated');
        }

        parent::__construct();

        CNabuProviderFactory::$nb_provider_factory = $this;

        $this->nb_manager_list = new CNabuProviderManagerList();
    }

    /**
     * If singleton instance does not exists then instantiate it.
     * @return CNabuProviderFactory Returns singleton instance
     * @throws ENabuSingletonException
     */
    public static function getFactory()
    {
        if (CNabuProviderFactory::$nb_provider_factory === null) {
            CNabuProviderFactory::$nb_provider_factory = new CNabuProviderFactory();
            CNabuProviderFactory::$nb_provider_factory->init();
        }

        return CNabuProviderFactory::$nb_provider_factory;
    }

    /**
     * Check if the singleton instance is instantiated.
     * @return bool Return true if singleton class is instantiated.
     */
    public static function isInstantiated()
    {
        return is_object(CNabuProviderFactory::$nb_provider_factory);
    }

    /**
     * Check if a vendor key is valid and set up the manager hierarchy to contain it.
     * @param string $vendor_key Vendor key to be checked.
     * @return bool Returns true if the vendor key is valid and the hierarchy setted.
     */
    private function prepareVendorKey(string $vendor_key)
    {
        $retval = false;

        if (nb_isValidKey($vendor_key)) {
            if (!is_array($this->manager_hierarchy)) {
                $this->manager_hierarchy = array($vendor_key => array());
            } elseif (!array_key_exists($vendor_key, $this->manager_hierarchy)) {
                $this->manager_hierarchy[$vendor_key] = array();
            }
            $retval = true;
        }

        return $retval;
    }

    /**
     * Init the factory.
     */
    private function init()
    {
        CNabuEngine::getEngine()->traceLog("Provider Factory", NABU_PROVIDERS_PATH);

        $folders = array();
        $this->scanVendorFolders($folders);
        error_log(print_r($folders, true));

        if (count($folders) > 0) {
            foreach ($folders as $provider) {
                try {
                    nb_requireOnceIsolated($provider, true, true);
                } catch (Exception $ex) {
                    throw new ENabuProviderException(
                        ENabuProviderException::ERROR_MANAGER_NOT_INITIALIZED,
                        array($provider)
                    );
                }
            }
        }
    }

    private function scanVendorFolders(&$folders)
    {
        $basedir = realpath(NABU_PROVIDERS_PATH);
        if (is_dir($basedir) && ($h = opendir($basedir))) {
            while (($folder = readdir($h))) {
                if ($folder !== '.' && $folder !== '..') {
                    $this->scanModuleFolders($basedir . DIRECTORY_SEPARATOR . $folder, $folders);
                }
            }
            closedir($h);
        }
    }

    private function scanModuleFolders($basedir, &$folders)
    {
        if (is_dir($basedir) && ($h = opendir($basedir))) {
            while (($folder = readdir($h))) {
                if ($folder !== '.' && $folder !== '..') {
                    $this->scanModuleFiles($basedir . DIRECTORY_SEPARATOR . $folder, $folders);
                }
            }
            closedir($h);
        }
    }

    private function scanModuleFiles($basedir, &$folders)
    {
        if (is_dir($basedir) && ($h = opendir($basedir))) {
            while (($filename = readdir($h))) {
                $phpfile = $basedir . DIRECTORY_SEPARATOR . $filename;
                if (preg_match('/^init_(.+)\.php$/', $filename) && file_exists($phpfile) && filesize($phpfile) > 0) {
                    error_log($phpfile);
                    $folders[] = $phpfile;
                }
            }
            closedir($h);
        }
    }

    /**
     * Adds a manager to the managers list.
     * @param INabuProviderManager $manager Manager to be added.
     * @return INabuProviderManager Returns the added manager if success or false if not.
     */
    public function addManager(INabuProviderManager $manager)
    {
        $vendor_key = $manager->getVendorKey();
        if ($this->prepareVendor($vendor_key)) {
            $manager_key = $manager->getManagerKey();
            if (nb_isValidKey($manager_key)) {
                if (array_key_exists($manager_key, $this->manager_hierarchy[$vendor_key])) {
                    throw new ENabuProviderException(
                        ENabuProviderException::ERROR_MANAGER_ALREADY_EXISTS,
                        array($vendor_key, $manager_key)
                    );
                } else {
                    $this->manager_hierarchy[$vendor_key][$manager_key] = $manager;
                }
            } else {
                throw new ENabuProviderException(
                    ENabuProviderException::ERROR_MANAGER_KEY_NOT_VALID,
                    array($manager_key)
                );
            }
        } else {
            throw new ENabuProviderException(
                ENabuProviderException::ERROR_VENDOR_KEY_NOT_VALID,
                array($vendor_key)
            );
        }
    }

    public function registerApplication(CNabuAbstractApplication $nb_application)
    {

    }
}
