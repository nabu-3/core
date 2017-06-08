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
use Exception;
use nabu\core\CNabuOS;
use nabu\core\CNabuObject;
use nabu\core\CNabuEngine;
use nabu\core\CNabuErrorHandler;
use nabu\core\exceptions\ENabuCoreException;
use nabu\core\exceptions\ENabuSingletonException;
use nabu\core\interfaces\INabuSingleton;
use nabu\core\interfaces\INabuApplication;
use nabu\data\cluster\builtin\CNabuBuiltInServer;
use nabu\data\cluster\builtin\CNabuBuiltInServerHost;
use nabu\data\customer\CNabuCustomer;
use nabu\data\customer\builtin\CNabuBuiltInCustomer;
use nabu\data\domain\builtin\CNabuBuiltInDomainZone;
use nabu\data\domain\builtin\CNabuBuiltInDomainZoneHost;
use nabu\data\site\builtin\CNabuBuiltInSite;
use nabu\data\site\builtin\CNabuBuiltInSiteAlias;
use nabu\db\exceptions\ENabuDBException;
use nabu\db\interfaces\INabuDBConnector;
use nabu\http\interfaces\INabuHTTPServer;
use nabu\messaging\exceptions\ENabuMessagingException;
use nabu\messaging\managers\CNabuMessagingPoolManager;
use nabu\provider\CNabuProviderFactory;
use nabu\provider\base\CNabuProviderInterfaceDescriptor;
use nabu\provider\base\CNabuProviderInterfaceDescriptorList;
use nabu\provider\exceptions\ENabuProviderException;
use nabu\provider\interfaces\INabuProviderManager;
use providers\apache\httpd\CApacheHTTPServer;
use providers\mysql\driver\CMySQLConnector;

/**
 * CNabuEngine class governs core functionalities of Nabu: the Engine.
 * The Framework calls methods in this class to respond to remote hosts calls and create content as response.
 * The Engine manages the request and response objects, the Plugin Manager,
 * the Applications Manager and default database connectors.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.11 Surface
 * @package \nabu\core
 */
final class CNabuEngine extends CNabuObject implements INabuSingleton
{
    /** @var int Engine Standalone mode. */
    const ENGINE_MODE_STANDALONE = 0x0001;
    /** @var int Engine Hosted mode. */
    const ENGINE_MODE_HOSTED = 0x0002;
    /** @var int Engine Clustered mode. */
    const ENGINE_MODE_CLUSTERED = 0x0003;
    /** @var int Engine CLI mode. */
    const ENGINE_MODE_CLI = 0x0004;

    /** @var CNabuEngine Contains the singleton instance of class. */
    private static $nb_engine = null;
    /** @var bool Flag to indicate if the environment is in install mode or in normal mode.
     * A true value represents that Engine is in install mode and sensible features
     * are not considered in execution time, like database connections.
     */
    private static $install_mode = false;
    /** @var int Flag specifying the operation mode between Standalone, Stored, Clustered. */
    private static $operation_mode = CNabuEngine::ENGINE_MODE_STANDALONE;
    /** @var int Starting microtime to dispatch an engine request */
    private $microtime_start = null;
    /** @var int Ending microtime dispatching an engine request */
    private $microtime_end = null;
    /** @var int Microtime execution elapsed dispatching an engine request */
    private $microtime_elapsed = null;
    /** @var CNabuErrorHandler Contains a CNabuErrorHandler instance to manage error handling */
    private $nb_error_handler = null;
    /** @var CNabuOS Nabu OS instance that describes the running OS */
    private $nb_os = null;
    /** @var array Folders list availables in the Framework */
    private $framework_folders = null;
    /** @var INabuDBConnector Connector to main database of Nabu */
    private $main_database = null;
    /** @var CNabuCustomer Customer instance */
    private $nb_customer = null;
    /** @var INabuHTTPServer Web server instance */
    private $nb_http_server = null;
    /** @var INabuApplication If the Engine was requested to run an Application here is the instance */
    private $nb_application = null;
    /** @var CNabuProviderFactory Provider managers factory */
    private $nb_provider_factory = null;
    /** @var CNabuMessagingPoolManager If the Engine was requested to run a Messaging pool here is the instance */
    private $nb_messaging_pool_manager = null;

    /**
     * Default constructor. This object is singleton then, more than one instantiation throws a ENabuSingletonException.
     * It is not recommendable to instantiate directly. To do this, call {@see \nabu\core\CNabuEngine::getEngine()}
     * @throws ENabuSingletonException
     */
    public function __construct()
    {
        if (CNabuEngine::$nb_engine != null) {
            throw new ENabuSingletonException("Engine already instantiated");
        }

        parent::__construct();
    }

    /**
     * If singleton instance not exists then instantiate it.
     * @return CNabuEngine Returns singleton instance
     * @throws ENabuSingletonException
     */
    public static function getEngine()
    {
        if (CNabuEngine::$nb_engine === null) {
            CNabuEngine::$nb_engine = new CNabuEngine();
            CNabuEngine::$nb_engine->init();
        }

        return CNabuEngine::$nb_engine;
    }

    /**
     * Check if the Nabu Engine is instantiated or not
     * @return bool Return true if the Engine is instantiated
     */
    public static function isInstantiated() : bool
    {
        return (CNabuEngine::$nb_engine !== null);
    }

    /**
     * Check if the Nabu Engine runs over CLI environment
     * @return bool Return true if the environment is CLI
     */
    public static function isCLIEnvironment()
    {
        return php_sapi_name() === 'cli';
    }

    /**
     * Check if we are in install mode.
     * @return bool Return true if we are in install mode
     */
    public static function isInstallMode()
    {
        return CNabuEngine::$install_mode;
    }

    /**
     * If we are in PHP CLI mode, then switch the Engine on/off in install mode.
     * In we are not in PHP CLI mode, this method does not have effect.
     * @param bool $mode If true then install mode is setted.
     * @throws ENabuCoreException
     */
    public static function setInstallMode($mode)
    {
        if (CNabuEngine::isCLIEnvironment()) {
            if ($mode === true) {
                CNabuEngine::$install_mode = true;
            } else {
                if (CNabuEngine::$install_mode &&
                    CNabuEngine::$nb_engine !== null &&
                    CNabuEngine::$nb_engine->main_database === null
                ) {
                    CNabuEngine::$nb_engine->registerMainDatabase();
                }
                CNabuEngine::$install_mode = false;
            }
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_INSTALL_MODE_LOCKED);
        }
    }

    /**
     * Set the operation mode of the Engine when it works as web server.
     * Valid modes are:
     * - Standalone: the server does not use database nor cluster or virtual hosts
     * - Stored: the server uses database but not cluster or virtual hosts
     * - Clustered: the server uses database and/or cluster and/or virtual hosts
     * @param int $mode A valid mode represented by the constants
     * ENGINE_MODE_STANDALONE, ENGINE_MODE_STORED and ENGINE_MODE_CLUSTERED
     * @throws ENabuCoreException Throws this exception if an invalid mode is passed
     * or if the engine is not instantiated.
     */
    public static function setOperationMode($mode)
    {
        if ($mode !== CNabuEngine::ENGINE_MODE_STANDALONE &&
            $mode !== CNabuEngine::ENGINE_MODE_HOSTED &&
            $mode !== CNabuEngine::ENGINE_MODE_CLUSTERED &&
            $mode !== CNabuEngine::ENGINE_MODE_CLI
        ) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_OPERATION_MODE_NOT_ALLOWED);
        }

        if (CNabuEngine::$nb_engine !== null) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_OPERATION_MODE_CANNOT_BE_MODIFIED);
        }

        CNabuEngine::$operation_mode = $mode;
    }

    /**
     * Alias method to call setOperationMode without params.
     */
    public static function setOperationModeStandalone()
    {
        CNabuEngine::setOperationMode(CNabuEngine::ENGINE_MODE_STANDALONE);
    }

    /**
     * Alias method to call setOperationMode without params
     */
    public static function setOperationModeHosted()
    {
        CNabuEngine::setOperationMode(CNabuEngine::ENGINE_MODE_HOSTED);
    }

    /**
     * Alias method to call setOperationMode without params
     */
    public static function setOperationModeClustered()
    {
        CNabuEngine::setOperationMode(CNabuEngine::ENGINE_MODE_CLUSTERED);
    }

    /**
     * Alias method to call setOperationMode without params
     */
    public static function setOperationModeCLI()
    {
        CNabuEngine::setOperationMode(CNabuEngine::ENGINE_MODE_CLI);
    }

    /**
     * Checks if the Operation Mode is Standalone
     * @return bool Returns true if yes or false if not.
     */
    public static function isOperationModeStandalone()
    {
        return CNabuEngine::$nb_engine !== null && CNabuEngine::$operation_mode === CNabuEngine::ENGINE_MODE_STANDALONE;
    }

    /**
     * Checks if the Operation Mode is Stored
     * @return bool Returns true if yes or false if not.
     */
    public static function isOperationModeStored()
    {
        return CNabuEngine::$nb_engine !== null && CNabuEngine::$operation_mode === CNabuEngine::ENGINE_MODE_HOSTED;
    }

    /**
     * Checks if the Operation Mode is Clustered
     * @return bool Returns true if yes or false if not.
     */
    public static function isOperationModeClustered()
    {
        return CNabuEngine::$nb_engine !== null && CNabuEngine::$operation_mode === CNabuEngine::ENGINE_MODE_CLUSTERED;
    }

    /**
     * Checks if the Operation Mode is CLI
     * @return bool Returns true if yes or false if not.
     */
    public static function isOperationModeCLI()
    {
        return CNabuEngine::$nb_engine !== null && CNabuEngine::$operation_mode === CNabuEngine::ENGINE_MODE_CLI;
    }

    /**
     * Set the running application.
     * @param INabuApplication $nb_application
     * @throws ENabuCoreException Throws an exception if another application is already setted
     */
    public function registerApplication(INabuApplication $nb_application)
    {
        if ($this->nb_application !== null) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_RUNNING_APPLICATION);
        }

        $this->nb_application = $nb_application;
        $this->nb_provider_factory->registerApplication($this->nb_application);
    }

    /**
     * Removes running application if exists.
     */
    public function removeApplication()
    {
        $this->nb_application = null;
    }

    /**
     * Gets current application instance.
     * @return INabuApplication Returns current application instance of null if no application is running.
     */
    public function getApplication()
    {
        return $this->nb_application;
    }

    /**
     * Gets current Messaging Pool Manager instance. If not defined, then creates one for the current Customer.
     * @param bool $force Forces to set a new Messaging Pool Manager Instance.
     * @return CNabuMessagingPoolManager Returns the Messaging Pool Manager.
     * @throws ENabuMessagingException Raises an exception if Customer instance is not available or not matches with
     * the existing Messaging Pool Manager.
     */
    public function getMessagingPoolManager(bool $force = false) : CNabuMessagingPoolManager
    {
        if ($this->nb_customer instanceof CNabuCustomer) {
            if ($this->nb_messaging_pool_manager === null || $force) {
                if ($this->nb_messaging_pool_manager instanceof CNabuMessagingPoolManager) {
                    $this->nb_messaging_pool_manager->finish();
                }
                $this->nb_messaging_pool_manager = new CNabuMessagingPoolManager($this->nb_customer);
                $this->nb_messaging_pool_manager->init();
            } elseif (
                ($nb_customer = $this->nb_messaging_pool_manager->getCustomer()) instanceof CNabuCustomer &&
                $this->nb_customer->getId() === $nb_customer->getId()
            ) {
                $retval = $this->nb_messaging_pool_manager;
            } else {
                throw new ENabuMessagingException(ENabuMessagingException::ERROR_INVALID_MESSAGING_POOL_MANAGER);
            }
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_CUSTOMER_NOT_FOUND);
        }

        return $this->nb_messaging_pool_manager;
    }

    /**
     * Autoload method to load object declarations (classes, interfaces and traits) used in Nabu.
     * @param string $class_name Name with full path of the class, interface or trait required to load.
     * @param bool $throwable If yes, throws an exception when the class cannot be found.
     * @return bool If success returns true else if class not found and $throwable is false, then returns false.
     * @throws ENabuCoreException
     */
    public function autoLoadClasses($class_name, $throwable = true)
    {
        $retval = false;
        $class_filename = $this->class2Filename($class_name);

        if ($this->nb_http_server !== null) {
            $nb_server = $this->nb_http_server->getServer();
            $nb_site = $this->nb_http_server->getSite();

            if ($nb_site !== null) {
                $base = $nb_site->getVirtualHostPHPPath($nb_server);
                if (is_dir($base)) {
                    $filename = $base . $class_filename;
                    $retval = $this->requireOnce($filename, $class_name);
                }
            }

            if (!$retval && $nb_server !== null) {
                $phputils = $nb_server->getFrameworkPath();
                $filename = $this->class2Filename($class_name, false);
                $file = $phputils . $filename;

                if ($this->requireOnce($file, $class_name) ||
                    $this->requireOnce($file.'.php', $class_name) ||
                    $this->requireOnce(strtolower($file), $class_name) ||
                    $this->requireOnce(strtolower($file).'.php', $class_name)
                ) {
                    $retval = true;
                }
            }
        }

        if (!$retval && $throwable) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_CLASS_NOT_FOUND, array($class_name));
        }

        return $retval;
    }

    /**
     * Convert a class name with namespace path to file name with namespace
     * translated into File System path. The returned string start with
     * the directory separator to safe prepend a route before it.
     * @param string $class_name Class name with namespace path
     * @param string $php_suffix File suffix to append to file name or false if not needed.
     * @return string Class filename with path containing full namespace route.
     */
    private function class2Filename($class_name, $php_suffix = '.php')
    {
        return DIRECTORY_SEPARATOR
             . str_replace('\\', DIRECTORY_SEPARATOR, $class_name)
             . ($php_suffix === false ? '' : $php_suffix);
    }

    /**
     * Fail safe require_once call. Checks if the filename exists before require it.
     * If file exists then load it with require_once primitive and returns true
     * if as the result of the require_once primitive, the class/interface/trait
     * is defined, or false if not.
     * @param string $filename File name to require_once where the $class_name is defined.
     * @param string $class_name Name of class/interface/trait with full namespace.
     * @param bool $trace Trace in the Nabu Engine log the object requested.
     * @return bool Return true if, as result of the require_once primitive,
     * the entity declaration is loaded, or false if not.
     */
    private function requireOnce($filename, $class_name, $trace = false)
    {
        $retval = false;

        if (strlen($filename) > 0 && realpath($filename) === $filename) {
            if ($trace || (defined('NABU_TRACE_AUTOLOAD') && NABU_TRACE_AUTOLOAD === true)) {
                $this->traceLog("Autoload Class", "$class_name in $filename");
            }

            require_once $filename;
            $retval = class_exists($class_name) | interface_exists($class_name) | trait_exists($class_name);
        }

        return $retval;
    }

    /**
     * Grant if a class/interface/trait declaration exists.
     * Normally, to grant that the declaration exists, the required object is searched
     * in all deployment folders availables, and their declaration PHP script loaded.
     * If the object is defined then returns true, else returns false.
     * @param string $class_name Full name including namespace of the class to preload.
     * @return bool Return true if the class is available or false if not.
     */
    public function preloadClass($class_name)
    {
        $retval = false;

        if (class_exists($class_name, false) ||
            interface_exists($class_name, false) ||
            trait_exists($class_name, false)
        ) {
            $retval = true;
        } else {
            try {
                nb_autoLoadClasses($class_name);
                $retval = class_exists($class_name, false) ||
                       interface_exists($class_name, false) ||
                       trait_exists($class_name, false)
                ;
            } catch (ENabuCoreException $ex) {
                if ($ex->getCode() !== ENabuCoreException::ERROR_CLASS_NOT_FOUND) {
                    throw $ex;
                }
            }
        }

        return $retval;
    }

    /**
     * Gets the Nabu running OS singleton instance of class CNabuOS
     * @return CNabuOS Return the CNabuOS singleton instance
     */
    public function getOS()
    {
        return $this->nb_os;
    }

    /**
     * Gets the nabu-3 instance of the running HTTP Server
     * @return INabuHTTPServer The instance of HTTP Server of null if no instance
     */
    public function getHTTPServer()
    {
        if (!$this->isCLIEnvironment()) {
            return $this->nb_http_server;
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_METHOD_NOT_AVAILABLE, array(__METHOD__));
        }
    }

    /**
     * Gets current Customer active in the Engine: the owner of the process.
     * @return CNabuCustomer Returns the current CNabuCustomer instance
     */
    public function getCustomer()
    {
        return $this->nb_customer;
    }

    /**
     * Set current nabu-3 Customer owning the Engine process.
     * If $nb_customer contains only the Id or is an instance of different class than CNabuCustomer,
     * then instantiates a new CNabuCustomer instance and validates it.
     * @param mixed $nb_customer Customer instance or Id to be setted.
     */
    public function setCustomer($nb_customer)
    {
        if ($nb_customer instanceof CNabuCustomer && $nb_customer->isFetched()) {
            $this->nb_customer = $nb_customer;
        } else {
            $customer = new CNabuCustomer($nb_customer);
            if ($customer->isFetched()) {
                $this->nb_customer = $customer;
            } else {
                $this->nb_customer = null;
            }
        }
    }

    /**
     * Initializes the instance.
     * Register handlers.
     * Register main database.
     */
    private function init()
    {
        $this->microtime_start = microtime(true);
        $this->nb_os = CNabuOS::getOS();

        $this->registerHandlers();
        $this->registerProviderFactory();
    }

    /**
     * Ends the instance.
     * Takes the elapsed time between init and finish calls and closes connections to external sources.
     */
    private function finish()
    {
        $this->microtime_end = microtime(true);
        $this->microtime_elapsed = $this->microtime_end - $this->microtime_start;
        $this->traceLog("Execution time", sprintf("%.3f ms", $this->microtime_elapsed * 1000));

        if ($this->main_database !== null) {
            $this->main_database->disconnect();
        }
    }

    /**
     * Register handlers for all PHP hooks
     */
    private function registerHandlers()
    {
        $this->nb_error_handler = new CNabuErrorHandler($this);
    }

    /**
     * Register the Provider Factory
     */
    private function registerProviderFactory()
    {
        $this->nb_provider_factory = CNabuProviderFactory::getFactory();
        $this->nb_provider_factory->scanProvidersFolder();
    }

    /**
     * If database configuration file does not exists then creates a default one.
     * Instantiate the object descriptor for main database. Assumes in this version
     * that the only database available for main storage is MySQL.
     */
    private function registerMainDatabase()
    {
        $filename = NABU_ETC_PATH.DIRECTORY_SEPARATOR.NABU_DB_DEFAULT_FILENAME_CONFIG;
        if (!file_exists($filename)) {
            if (($file = fopen($filename, "w")) !== false) {
                fwrite($file,
                        "<?php\n".
                        "\$db_user = \"nabu-3\";\n".
                        "\$db_passwd = \"nabu-3\";\n".
                        "\$db_host = \"localhost:3306\";\n".
                        "\$db_schema = \"nabu-3\";\n".
                        "\$db_trace_query = false;\n".
                        "?>\n"
                        );
                fclose($file);
            }
        }

        $this->main_database = new CMySQLConnector($filename);
        if ($this->main_database->connect()) {
            if (!$this->main_database->testConnection()) {
                //throw new exceptions\ENabuCoreException(exceptions\ENabuCoreException::ERROR_M)
                $this->errorLog("Database Test error: ".$this->main_database->getLastErrorMessage());
            }
        } else {
            throw new ENabuDBException(ENabuDBException::ERROR_NOT_CONNECTED);
        }
    }

    /**
     * Get main database connection.
     * @return INabuDBConnector Returns the database connector instance if active or null if not
     */
    public function getMainDB()
    {
        if ($this->main_database === null &&
            ($this->isOperationModeCLI() || $this->isOperationModeStandalone())
        ) {
            $this->registerMainDatabase();
        }
        return $this->main_database;
    }

    /**
     * Database connector to main Nabu database schema
     * This method is allowed only when Engine is in install mode
     * @param INabuDBConnector $connector Connector of database
     */
    public function setMainDB(INabuDBConnector $connector)
    {
        if ($this->isInstallMode()) {
            if ($this->main_database === null) {
                $this->main_database = $connector;
            } else {
                throw new ENabuCoreException(ENabuCoreException::ERROR_MAIN_DB_ALREADY_EXISTS);
            }
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_INSTALL_MODE_REQUIRED);
        }
    }

    /**
     * Check if nabu-3 main database is available.
     * @return bool Returns true if available or false if not.
     */
    public function isMainDBAvailable()
    {
        return $this->main_database !== null && $this->main_database->isConnected();
    }

    /**
     * If a Error Handler is defined, then propagates the error log to the handler elsewhere do nothing.
     * @param string $message Text message to log
     */
    public function errorLog($message)
    {
        if ($this->nb_error_handler) {
            $this->nb_error_handler->errorLog($message);
        }
    }

    /**
     * Enables the Log Tracer to trace data in log storages.
     * @return CNabuEngine Returns the $this pointer to grant chain setters.
     */
    public function enableLogTrace()
    {
        if ($this->nb_error_handler) {
            $this->nb_error_handler->enableTracer();
        }

        return $this;
    }

    /**
     * If a Error Handler is defined, then propagates the trace log to the handler elsewhere do nothing.
     * @param string $key Categorized data key identifier
     * @param string $message Text message to log
     */
    public function traceLog($key, $message)
    {
        if ($this->nb_error_handler) {
            $this->nb_error_handler->traceLog($key, $message);
        }
    }

    /**
     * Trigger and error using the standard PHP Error chain.
     * @param string $error_message Error message to be triggered.
     * @param string $error_type Error type to be triggered.
     */
    public function triggerError($error_message = null, $error_type = null) {

        if ($error_message !== null && $error_type !== null) {
            trigger_error($error_message, $error_type);
        }
    }

    /**
     * Executes an Application instance in the Nabu Engine environment.
     * @param string $class_name
     * @return mixed Returned value from run method of the instance
     */
    public function runApplication($class_name)
    {
        $this->traceLog('Request timestamp', $this->microtime_start);

        try {
            switch (CNabuEngine::$operation_mode) {
                case CNabuEngine::ENGINE_MODE_STANDALONE:
                    $this->locateHTTPServer();
                    $this->createBuiltInServer();
                    $this->createBuiltInSite();
                    break;
                case CNabuEngine::ENGINE_MODE_HOSTED:
                    $this->registerMainDatabase();
                    $this->locateHTTPServer();
                    $this->createBuiltInServer();
                    $this->locateRunningConfiguration();
                    break;
                case CNabuEngine::ENGINE_MODE_CLUSTERED:
                    $this->registerMainDatabase();
                    $this->locateHTTPServer();
                    $this->locateRunningConfiguration();
                    break;
            }

            $retval = forward_static_call(array($class_name, 'launch'));
        } catch (Exception $e) {
            $this->nb_error_handler->dumpStack(
                $e->getMessage(), E_CORE_ERROR, $e->getFile(), $e->getLine(), null, $e->getTrace()
            );
            if ($this->isOperationModeStandalone() ||
                $this->isOperationModeStored() ||
                $this->isOperationModeClustered()
            ) {
                nb_displayErrorPage(500, $e);
            }
            $retval = false;
        }

        if (CNabuEngine::isInstantiated()) {
            $nb_engine = CNabuEngine::getEngine();
            $nb_engine->finish();
            /** @todo Implement CNabuEngine Dump Popup Trace action */
            // $nb_engine->dumpPopupTrace();
        }

        return $retval;
    }

    /**
     * Try to detect the running HTTP Server attached to this process and returns their nabu-3 manager.
     * @return INabuHTTPServer Returns an instance of the HTTP Server Engine
     * @throws ENabuCoreException Throws an exception if Nabu Engine is not running in a HTTP Server mode.
     */
    private function locateHTTPServer()
    {
        if (CNabuEngine::isCLIEnvironment()) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_METHOD_NOT_AVAILABLE, array(__METHOD__));
        } elseif ($this->nb_http_server === null) {
            $this->nb_http_server = new CApacheHTTPServer();
        }

        return $this->nb_http_server;
    }

    /**
     * Create the BuiltIn Server.
     * If operation mode is setted to Standalone or Hosted, the BuiltIn Server is required due to the application
     * instance cannot run in more than one server.
     * @return CNabuBuiltInServer Returns the BuiltIn Server instance created and filled with their default values.
     */
    private function createBuiltInServer()
    {
        $nb_server = new CNabuBuiltInServer();
        $nb_server_host = new CNabuBuiltInServerHost();

        $host_path = $this->nb_http_server->getHostBaseDirectory();
        $server_path = dirname($host_path);

        $nb_server->setBasePath(NABU_BASE_PATH);
        $nb_server->setFrameworkPath(NABU_PHPUTILS_PATH);
        $nb_server->setVirtualHostsPath($server_path);

        $this->nb_http_server->setServer($nb_server);
        $this->nb_http_server->setServerHost($nb_server_host);
    }

    /**
     * Create the BuiltIn Site.
     * If operation mode is setted to Standalone, the BuiltIn Site is required due to the HTTP Server cannot execute
     * more than one application instance.
     * @return CNabuBuiltInSite Returns the BuiltIn Site instance created and filled with their default values.
     */
    private function createBuiltInSite()
    {
        $this->nb_customer = new CNabuBuiltInCustomer();
        $nb_site = new CNabuBuiltInSite();
        $nb_site_alias = new CNabuBuiltInSiteAlias();
        $nb_domain_zone = new CNabuBuiltInDomainZone();
        $nb_domain_zone_host = new CNabuBuiltInDomainZoneHost();

        $server_name = $this->nb_http_server->getServerName();
        $sname_parts = preg_split('/\\./', $server_name, 2);
        if (count($sname_parts) === 2) {
            list($host, $domain) = $sname_parts;
        } elseif (count($sname_parts) === 1) {
            $host = $sname_parts[0];
            $domain = '';
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_DOMAIN_ZONE_NOT_FOUND);
        }

        $nb_domain_zone->setName($domain);
        $nb_domain_zone_host->setName($host);
        $nb_domain_zone_host->setDomainZone($nb_domain_zone);

        $host_path = $this->nb_http_server->getHostBaseDirectory();
        $site_path = basename($host_path);

        $nb_site->setBasePath(DIRECTORY_SEPARATOR . $site_path);
        $nb_site->setPHPUtilsPath(NABU_PHPUTILS_FOLDER);
        $nb_site->setClassesPath(NABU_CLASSES_FOLDER);
        $nb_site->setPluginsPath(NABU_PLUGINS_FOLDER);
        $nb_site->setMainAlias($nb_site_alias);

        $nb_site_alias->setDomainZoneHost($nb_domain_zone_host);

        $this->nb_http_server->setSite($nb_site);
        $this->nb_http_server->setSiteAlias($nb_site_alias);
    }

    /**
     * Locates the running configuration in the HTTP Server.
     */
    private function locateRunningConfiguration()
    {
        $this->nb_http_server->locateRunningConfiguration();
    }

    /**
     * Gets the list of PHP root folders in the framework for include purposes.
     * @return array Returns an string array of all folder paths available in the framework.
     */
    public function getPHPIncludeFolders()
    {
        if (!is_array($this->framework_folders)) {
            $this->framework_folders = array();

            if (is_dir(NABU_SRC_PATH)) {
                $this->framework_folders[] = realpath(NABU_SRC_PATH);
            }
            if (is_dir(NABU_SDK_PATH)) {
                $this->framework_folders[] = realpath(NABU_SDK_PATH);
            }
            if (is_dir(NABU_PUB_PATH)) {
                $this->framework_folders[] = realpath(NABU_PUB_PATH);
            }
            if (is_dir(NABU_LIB_PATH)) {
                $this->framework_folders[] = realpath(NABU_LIB_PATH);
            }
            if (count($this->framework_folders) === 0) {
                $this->framework_folders = null;
            }
        }

        return $this->framework_folders;
    }

    /**
     * Register a Manager to be used.
     * @param INabuProviderManager $nb_manager Manager instance to be registered.
     * @return INabuProviderManager Returns the registered manager instance if success.
     * @throws ENabuCoreException Raises an exception if the manager cannot be enabled.
     */
    public function registerProviderManager(INabuProviderManager $nb_manager)
    {
        $this->nb_provider_factory->addManager($nb_manager);

        return $nb_manager;
    }

    /**
     * Gets a Provider Manager instance.
     * This method is intended normally to be used internally and inside provider modules.
     * @param string $vendor_key Vendor Key to identify the Manager.
     * @param string $module_key Module Key to identify the Manager.
     * @return INabuProviderManager Returns the Manager instance if exists or false if not.
     * @throws ENabuProviderException Raises an exception if $vendor_key or $module_key have invalid values.
     */
    public function getProviderManager(string $vendor_key, string $module_key)
    {
        if ($this->nb_provider_factory instanceof CNabuProviderFactory) {
            return $this->nb_provider_factory->getManager($vendor_key, $module_key);
        } else {
            throw new ENabuProviderException(ENabuProviderException::ERROR_PROVIDER_FACTORY_NOT_AVAILABLE);
        }
    }

    /**
     * Register a Provider Interface to be used.
     * @param CNabuProviderInterfaceDescriptor $nb_descriptor Descriptor instance of the interface.
     * @throws ENabuProviderException Raises an exception if the interface is not registered.
     */
    public function registerProviderInterface(CNabuProviderInterfaceDescriptor $nb_descriptor)
    {
        return $this->nb_provider_factory->addInterface($nb_descriptor);
    }

    /**
     * Gets the Provider Interface descriptors list for a type of interfaces.
     * @param int $interface_type Interface type.
     * @return CNabuProviderInterfaceDescriptorList Returns the list of descriptors. If none the the list is empty.
     */
    public function getProvidersInterfaceDescriptors(int $interface_type)
    {
        return $this->nb_provider_factory->getInterfaceDescriptors($interface_type);
    }
}
