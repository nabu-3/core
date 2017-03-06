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
use nabu\core\exceptions\ENabuSingletonException;
use nabu\core\interfaces\INabuSingleton;
use nabu\core\interfaces\INabuApplication;
use nabu\provider\base\CNabuProviderInterfaceDescriptor;
use nabu\provider\base\CNabuProviderInterfaceDescriptorList;
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
    /** @var int INTERFACE_MESSAGING_ACCOUNT Messaging Account Interface identificator. */
    const INTERFACE_MESSAGING_ACCOUNT       = 0x0001;

    /** @var CNabuProviderFactory $nb_provider_factory Contains the singleton instance of class. */
    private static $nb_provider_factory = null;
    /** @var CNabuProviderManagerList $nb_manager_list List of all registered managers. */
    private $nb_manager_list = null;
    /** @var array $nb_interface_list Index of interfaces. */
    private $nb_interface_list = null;

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
        $this->nb_interface_list = array(
            self::INTERFACE_MESSAGING_ACCOUNT => new CNabuProviderInterfaceDescriptorList()
        );
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
     * Init the factory.
     */
    private function init()
    {
        CNabuEngine::getEngine()->traceLog("Provider Factory", NABU_PROVIDERS_PATH);
    }

    /**
     * Scan providers folder to detect installed provider modules.
     */
    public function scanProvidersFolder()
    {
        $folders = array();
        $this->scanVendorFolders($folders);

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

    /**
     * Scan vendor folders to find modules.
     * @param array &$folders Array passed by reference to be filled with modules found.
     */
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

    /**
     * Scan module folders to prepare init mechanism.
     * @param string $basedir Base directory to looking for module folders.
     * @param array &$folders Array passed by reference to be filled with modules found.
     */
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

    /**
     * Scan module root files to locate the init file.
     * @param string $basedir Base directory to looking for module files.
     * @param array &$folders Array passed by reference to be filled with modules found.
     */
    private function scanModuleFiles($basedir, &$folders)
    {
        if (is_dir($basedir) && ($h = opendir($basedir))) {
            while (($filename = readdir($h))) {
                $phpfile = $basedir . DIRECTORY_SEPARATOR . $filename;
                if (preg_match('/^init_(.+)\.php$/', $filename) && file_exists($phpfile) && filesize($phpfile) > 0) {
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
    public function addManager(INabuProviderManager $nb_manager)
    {
        $vendor_key = $nb_manager->getVendorKey();
        if (nb_isValidKey($vendor_key)) {
            $module_key = $nb_manager->getModuleKey();
            if (!nb_isValidKey($module_key)) {
                throw new ENabuProviderException(
                    ENabuProviderException::ERROR_MODULE_KEY_NOT_VALID,
                    array($module_key)
                );
            }
        } else {
            throw new ENabuProviderException(
                ENabuProviderException::ERROR_VENDOR_KEY_NOT_VALID,
                array($vendor_key)
            );
        }

        $this->nb_manager_list->addItem($nb_manager);

        if (!$nb_manager->enableManager()) {
            throw new ENabuProviderException(ENabuProviderException::ERROR_PROVIDER_MANAGER_FAIL, array($manager_class));
        }

        $nb_application = CNabuEngine::getEngine()->getApplication();
        if ($nb_application instanceof INabuApplication) {
            $nb_manager->registerApplication($nb_application);
        }

        return $nb_manager;
    }

    /**
     * Gets a Manager instance. This method is intended normally to be used internally and inside provider modules.
     * @param string $vendor_key Vendor Key to identify the Manager.
     * @param string $module_key Module Key to identify the Manager.
     * @return INabuProviderManager Returns the Manager instance if exists or false if not.
     * @throws ENabuProviderException Raises an exception if $vendor_key or $module_key have invalid values.
     */
    public function getManager(string $vendor_key, string $module_key)
    {

        if (nb_isValidKey($vendor_key) && nb_isValidKey($module_key)) {
            return $this->nb_manager_list->getItem("$vendor_key:$module_key");
        } else {
            throw new ENabuProviderException(ENabuProviderException::ERROR_INVALID_KEYS);
        }
    }

    /**
     * Adds an interface to the interfaces list.
     * @param CNabuProviderInterfaceDescriptor $nb_descriptor Descriptor instance of the interface.
     * @return bool Returns true if the interface is registered.
     */
    public function addInterface(CNabuProviderInterfaceDescriptor $nb_descriptor)
    {
        $interface_type = $nb_descriptor->getType();

        if (!array_key_exists($interface_type, $this->nb_interface_list)) {
            throw new ENabuProviderException(
                ENabuProviderException::ERROR_INTERFACE_TYPE_NOT_EXISTS,
                array(print_r($interface_type, true))
            );
        }

        $this->nb_interface_list[$interface_type]->addItem($nb_descriptor);

        return true;
    }

    /**
     * Gets the interfaces descriptor list of a type of interfaces.
     * @param int $interface_type Interface type to be retrieved.
     * @return CNabuProviderInterfaceDescriptorList The list of available interfaces.
     */
    public function getInterfaceDescriptors(int $interface_type)
    {
        if (!array_key_exists($interface_type, $this->nb_interface_list)) {
            throw new ENabuProviderException(
                ENabuProviderException::ERROR_INTERFACE_TYPE_NOT_EXISTS,
                array(print_r($interface_type, true))
            );
        }

        return $this->nb_interface_list[$interface_type];
    }

    /**
     * Register the application to connect all available managers with it.
     * @param INabuApplication $nb_application Application instance to be registered.
     */
    public function registerApplication(INabuApplication $nb_application)
    {
        $this->nb_manager_list->iterate(
            function($key, $manager) use ($nb_application)
            {
                $manager->registerApplication($nb_application);
                return true;
            }
        );
    }
}
