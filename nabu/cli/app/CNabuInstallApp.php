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

namespace nabu\cli\app;

use \nabu\cli\app\CNabuCLIApplication;
use \providers\mysql\driver\CMySQLConnector;
use \providers\mysql\driver\EMySQLException;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.11 Surface
 * @package \nabu\cli\app
 */
class CNabuInstallApp extends CNabuCLIApplication
{
    /** @var bool $silent_mode Silent mode. If true the install does not prompt confirmation to continue */
    private $silent_mode = false;
    /** @var string $etc_path Path to etc folder */
    private $etc_path = NABU_ETC_PATH;
    /** @var string $base_path Base path of Nabu installation */
    private $base_path = NABU_BASE_PATH;
    /** @var string $web_path Base path of Nabu web folders */
    private $web_path = NABU_WEB_PATH;
    /** @var string $log_path Path of Nabu log files */
    private $log_path = NABU_LOG_PATH;
    /** @var string $mysql_server Host to connect to MySQL Server */
    private $mysql_server = '127.0.0.1';
    /** @var int $mysql_port Port to connect to MySQL Server */
    private $mysql_port = 3306;
    /** @var string $mysql_schema Schema name of Nabu database in MySQL Server */
    private $mysql_schema = 'nabu-3';
    /** @var string $mysql_user User of MySQL Server to access to Nabu database */
    private $mysql_user = 'nabu-3';
    /** @var string $mysql_password Password of MySQL Server User to access to Nabu database */
    private $mysql_password = 'nabu-3';
    /** @var CMySQLConnector $mysql_connector MySQL Connector to manage Nabu database */
    private $mysql_connector = null;

    public function prepareEnvironment()
    {
        echo "Setting up environment for nabu-3\n";

        $this->loadParameters();
        $this->checkPaths();
        $this->changeIniVars();
        $this->prepareMySQLConfiguration();
        $this->writeConfigFiles();

        echo "Machine environment is ready.\n";
        echo "\n------------------------------------------------------------------------\n\n";
    }

    public function run()
    {
        $this->createTables();
        echo "\n------------------------------------------------------------------------\n\n";

        echo "Installation finished successful.\n\n";
        echo "If you want to change configuration files you can access to:\n";
        echo "    [" . $this->etc_path . DIRECTORY_SEPARATOR . "nabu-3.conf] --> Configuration of Shell script files\n";
        echo "    ["
           . $this->etc_path . DIRECTORY_SEPARATOR
           . "nabu-db-config.php.conf] -> Configuration of Nabu Core MySQL Database\n";
        echo "\n";
    }

    private function loadParameters()
    {
        echo "    ...collecting environment values...";
        $this->silent_mode = nbCLICheckOption('s', 'silent', '', $this->silent_mode, false);
        $this->etc_path = nbCLICheckOption('e', 'etc-path', ':', $this->etc_path, false);
        $this->base_path = nbCLICheckOption('b', 'base-path', ':', $this->base_path, false);
        $this->web_path = nbCLICheckOption('w', 'web-path', ':', $this->web_path, false);
        $this->log_path = nbCLICheckOption('l', 'log-path', ':', $this->log_path, false);
        $this->mysql_server = nbCLICheckOption('dh', 'db-host', ':', $this->mysql_server, false);
        $this->mysql_port = nbCLICheckOption('dp', 'db-port', ':', $this->mysql_port, false);
        $this->mysql_schema = nbCLICheckOption('ds', 'db-schema', ':', $this->mysql_schema, false);
        $this->mysql_user = nbCLICheckOption('du', 'db-user', ':', $this->mysql_user, false);
        $this->mysql_password = nbCLICheckOption('da', 'db-password', ':', $this->mysql_password, false);
        echo "OK\n";
    }

    private function changeIniVars()
    {
        ini_set('include_path',
            implode(PATH_SEPARATOR, array(
                '.',
                $this->base_path . NABU_SRC_FOLDER,
                $this->base_path . NABU_PUB_FOLDER,
                $this->base_path . NABU_SDK_FOLDER,
                $this->base_path . NABU_LIB_FOLDER
            ))
        );
    }

    private function checkPaths()
    {
        $this->checkingPath($this->etc_path, true);
        $this->checkingPath($this->base_path, false);
        $this->checkingPath($this->web_path, true);
        $this->checkingPath($this->log_path, true);
        echo "OK\n\n";
    }

    private function checkingPath($path, $create = false, $rights = 0755, $die = true)
    {
        echo "    ...checking folder [$path]...";

        if (!file_exists($path) && $create && mkdir($path, $rights, true)) {
            echo "CREATED\n";
            $retval = true;
        } elseif (!is_dir($path) && $create) {
            if ($die) {
                die('Error: cannot be created\n');
            } else {
                echo 'Error: cannot be created\n';
                $retval = false;
            }
        } elseif (is_dir($path)) {
            echo "EXISTS\n";
            $retval = true;
        } else {
            echo "NOT PRESENT\n";
            $retval = false;
        }

        return $retval;
    }

    private function prepareMySQLConfiguration()
    {
        echo  "Now we collect information about your MySQL database connection.\n"
            . "Please, before continue, ensure that you has created a schema\n"
            . "in your MySQL Server and a user with password, and grant at least\n"
            . "these operations:\n\n"
            . "CREATE, ALTER, INSERT, SELECT, UPDATE, DELETE\n\n";

        do {
            $this->getMySQLConfiguration();
            if ($this->checkMySQLConfiguration()) {
                echo "\nConnection successful\n\n";
                echo "Client      : " . $this->mysql_connector->getDriverName() . "\n";
                $info = $this->mysql_connector->getServerInfo();
                if (array_key_exists('host', $info)) {
                    echo "Connected to: " . $info['host'] . "\n";
                }
                if (array_key_exists('name', $info)) {
                    echo "Server type : " . $info['name'] . "\n";
                }
                break;
            } else {
                echo  "\n\nError connecting to MySQL Server.\n"
                    . "Please review your configuration\n\n";
            }
        } while (true && !$this->silent_mode);

        $this->nb_engine->setMainDB($this->mysql_connector);
    }

    private function getMySQLConfiguration()
    {
        if (!$this->silent_mode) {
            nbCLICheckContinue(true, "Installation aborted.\n");
        }

        $this->mysql_server = nbCLIInput('MySQL Server Host', $this->mysql_server, $this->silent_mode);
        $this->mysql_port = nbCLIInput('MySQL Server Port', $this->mysql_port, $this->silent_mode);
        $this->mysql_schema = nbCLIInput('MySQL Schema', $this->mysql_schema, $this->silent_mode);
        $this->mysql_user = nbCLIInput('MySQL User', $this->mysql_user, $this->silent_mode);
        $this->mysql_password = nbCLIInput('MySQL Password', $this->mysql_password, $this->silent_mode);

        do {
            echo "\nYou have selected this configuration to connect to MySQL\n\n";
            echo "Host    : $this->mysql_server\n";
            echo "Port    : $this->mysql_port\n";
            echo "Schema  : $this->mysql_schema\n";
            echo "User    : $this->mysql_user\n";
            echo "Password: $this->mysql_password\n\n";
        } while (!$this->silent_mode && !nbCLICheckConfirm(false, "Installation aborted.\n"));

        echo "\n";
    }

    private function checkMySQLConfiguration()
    {
        echo      "Now we check if the configuration is right and connection is alive.\n"
                . "Please, before continue, be sure that the MySQL Server is running.\n\n";

        if (!$this->silent_mode) {
            nbCLICheckContinue(true, "Installation aborted.\n");
        }

        $this->mysql_connector = new CMySQLConnector();
        $this->mysql_connector->setHost($this->mysql_server);
        $this->mysql_connector->setPort($this->mysql_port);
        $this->mysql_connector->setSchema($this->mysql_schema);
        $this->mysql_connector->setUser($this->mysql_user);
        $this->mysql_connector->setPassword($this->mysql_password);

        try {
            $this->mysql_connector->connect();
            $retval = $this->mysql_connector->isConnected();
        } catch (EMySQLException $ex) {
            if ($ex->getCode() === EMySQLException::ERROR_NOT_CONNECTED) {
                $retval = false;
            } else {
                throw $ex;
            }
        }

        return $retval;
    }

    private function createTables()
    {

        echo "After validate the connection we will start to create the Nabu schema.\n\n";

        if ($this->silent_mode || nbCLICheckContinue(true, "Installation aborted.\n")) {
            echo "\nCreating tables...\n";
            $this->createTable('\\nabu\\data\\cluster\\CNabuIP');
            $this->createTable('\\nabu\\data\\cluster\\CNabuServer');
            $this->createTable('\\nabu\\data\\cluster\\CNabuServerHost');
            $this->createTable('\\nabu\\data\\cluster\\CNabuClusterUserGroup');
            $this->createTable('\\nabu\\data\\cluster\\CNabuClusterUser');
            $this->createtable('\\nabu\\data\\cluster\\CNabuClusterGroup');
            $this->createTable('\\nabu\\data\\cluster\\CNabuClusterGroupService');

            $this->createTable('\\nabu\\data\\customer\\CNabuCustomer');

            $this->createTable('\\nabu\\data\\domain\\CNabuDomainZone');
            $this->createTable('\\nabu\\data\\domain\\CNabuDomainZoneHost');

            $this->createTable('\\nabu\\data\\lang\\CNabuLanguage');

            $this->createTable('\\nabu\\data\\security\\CNabuRole');
            $this->createTable('\\nabu\\data\\security\\CNabuRoleLanguage');
            $this->createTable('\\nabu\\data\\security\\CNabuUser');

            $this->createTable('\\nabu\\data\\site\\CNabuSite');
            $this->createTable('\\nabu\\data\\site\\CNabuSiteLanguage');
            $this->createTable('\\nabu\\data\\site\\CNabuSiteUser');
            $this->createTable('\\nabu\\data\\site\\CNabuSiteAlias');
            $this->createTable('\\nabu\\data\\site\\CNabuSiteAliasHost');
            $this->createTable('\\nabu\\data\\site\\CNabuSiteAliasService');
            $this->createTable('\\nabu\\data\\site\\CNabuSiteMap');
            $this->createTable('\\nabu\\data\\site\\CNabuSiteMapLanguage');
            $this->createTable('\\nabu\\data\\site\\CNabuSiteMapRole');
            $this->createTable('\\nabu\\data\\site\\CNabuSiteRole');
            $this->createTable('\\nabu\\data\\site\\CNabuSiteRoleLanguage');
            $this->createTable('\\nabu\\data\\site\\CNabuSiteStaticContent');
            $this->createTable('\\nabu\\data\\site\\CNabuSiteStaticContentLanguage');
            $this->createTable('\\nabu\\data\\site\\CNabuSiteTarget');
            $this->createTable('\\nabu\\data\\site\\CNabuSiteTargetLanguage');
            $this->createTable('\\nabu\\data\\site\\CNabuSiteTargetSection');
            $this->createTable('\\nabu\\data\\site\\CNabuSiteTargetSectionLanguage');
            $this->createTable('\\nabu\\data\\site\\CNabuSiteTargetCTA');
            $this->createTable('\\nabu\\data\\site\\CNabuSiteTargetCTALanguage');
            $this->createTable('\\nabu\\data\\site\\CNabuSiteTargetCTARole');
        }

        echo "OK.\n";
    }

    private function createTable($class_name)
    {
        if ($this->nb_engine->preloadClass($class_name)) {
            echo "    ..." . forward_static_call_array(array($class_name, 'getStorageName'), array()) . "...";
            $result = forward_static_call_array(array($class_name, "createStorage"), array($this->mysql_connector));
            echo ($result ? "CREATED\n" : "EXISTS\n");
        } else {
            die ("Class $class_name not found\n");
        }
    }

    private function writeConfigFiles()
    {
        echo "\nNow we create default configuration files to run nabu-3\n";
        $this->writeEtcConfMainFile();
        $this->writeEtcConfPHPMySQLFile();
    }

    private function writeEtcConfMainFile()
    {
        $filename = $this->base_path . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'nabu-3.conf';

        echo "    ...writing [$filename]...";

        if (($handle = fopen($filename, 'w'))) {
            fwrite($handle, "#!/bin/sh\n");
            writeApacheLicense($handle, '#');
            fwrite($handle,
                    "\n# Configure Nabu paths\n"
                  . "NABU_ETC_PATH=$this->etc_path\n"
                  . "NABU_BASE_PATH=$this->base_path\n"
                  . "NABU_BIN_PATH=$this->base_path/bin\n"
                  . "NABU_WEB_PATH=$this->web_path\n"
                  . "\n# Configure MySQL Connexion\n"
                  . "MYSQL_SERVER=$this->mysql_server\n"
                  . "MYSQL_PORT=$this->mysql_port\n"
                  . "MYSQL_SCHEMA=$this->mysql_schema\n"
                  . "MYSQL_USER=$this->mysql_user\n"
                  . "MYSQL_PASSWORD=$this->mysql_password\n"
                  . "\n# Configure PHP common params\n"
                  . 'PHP_PARAMS="-d safe_mode=Off -d open_basedir=none -d include_path=.:${NABU_BASE_PATH}/src/:${NABU_BASE_PATH}/pub/:${NABU_BASE_PATH}/sdk/:${NABU_BASE_PATH}/lib/"' . "\n"
            );

            fclose($handle);
        }

        echo "OK\n";
    }

    private function writeEtcConfPHPMySQLFile()
    {
        $filename = $this->etc_path . DIRECTORY_SEPARATOR . 'nabu-db-config.php.conf';

        echo "    ...writing [$filename]...";

        if (($handle = fopen($filename, 'w'))) {
            fwrite($handle, "<?php\n\n/*\n");
            writeApacheLicense($handle, ' * ');
            fwrite($handle, " */\n\n");
            fwrite($handle,
                    "\$db_host = \"$this->mysql_server\";\n"
                    . "\$db_port = $this->mysql_port;\n"
                    . "\$db_schema = \"$this->mysql_schema\";\n"
                    . "\$db_user = \"$this->mysql_user\";\n"
                    . "\$db_passwd = \"$this->mysql_password\";\n"
                    . "\$db_trace_query = false;\n"
                    . "\$db_charset = \"utf8\";\n"
            );
            fclose($handle);
        }

        echo "OK\n";
    }
}
