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

namespace nabu\http\adapters;

use nabu\core\CNabuEngine;
use \nabu\core\CNabuObject;
use \nabu\core\exceptions\ENabuCoreException;
use nabu\core\utils\CNabuURL;

use \nabu\data\cluster\CNabuServer;
use nabu\data\customer\CNabuCustomer;

use \nabu\data\site\CNabuSite;
use \nabu\http\interfaces\INabuHTTPServerInterface;
use nabu\data\cluster\CNabuServerHost;
use nabu\data\domain\CNabuDomainZone;
use nabu\data\domain\CNabuDomainZoneHost;
use nabu\data\site\CNabuSiteAlias;

use nabu\http\interfaces\INabuHTTPFileSystem;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\http\adapters
 */
abstract class CNabuHTTPServerAdapter extends CNabuObject implements INabuHTTPServerInterface
{
    /**
     * Server instance
     * @var CNabuServer
     */
    protected $nb_server = null;
    /**
     * Server host instance
     * @var CNabuServerHost
     */
    protected $nb_server_host = null;
    /**
     * Domain Zone instance
     * @var CNabuDomainZone
     */
    protected $nb_domain_zone = null;
    /**
     * Domain Zone Host instance
     * @var CNabuDomainZoneHost
     */
    protected $nb_domain_zone_host = null;
    /**
     * Site instance
     * @var CNabuSite
     */
    protected $nb_site = null;
    /**
     * Site Alias instance
     * @var CNabuSiteAlias
     */
    protected $nb_site_alias = null;
    /**
     * @var INabuHTTPFileSystem HTTP Server File System instance.
     */
    protected $nb_file_system = null;

    /**
     * Creates the HTTP Server File System instance.
     * @return INabuHTTPFileSystem Returns created instance to represent the File System.
     */
    abstract protected function createFileSystem() : INabuHTTPFileSystem;

    public function init()
    {
        $this->nb_file_system = $this->createFileSystem();

        return true;
    }

    public function finish()
    {
    }

    public function getFileSystem(): \nabu\http\interfaces\INabuHTTPFileSystem
    {
        return $this->nb_file_system;
    }

    public function getServer()
    {
        return $this->nb_server;
    }

    public function setServer(CNabuServer $nb_server)
    {
        $this->nb_server = $nb_server;

        return $this;
    }

    public function getServerHost()
    {
        return $this->nb_server_host;
    }

    public function setServerHost(CNabuServerHost $nb_server_host)
    {
        $this->nb_server_host = $nb_server_host;

        return $this;
    }

    public function getDomainZone()
    {
        return $this->nb_domain_zone;
    }

    public function setDomainZone(CNabuDomainZone $nb_domain_zone)
    {
        $this->nb_domain_zone = $nb_domain_zone;

        return $this;
    }

    public function getDomainZoneHost()
    {
        return $this->nb_domain_zone_host;
    }

    public function setDomainZoneHost(CNabuDomainZoneHost $nb_domain_zone_host)
    {
        $this->nb_domain_zone_host = $nb_domain_zone_host;

        return $this;
    }

    public function getSite()
    {
        return $this->nb_site;
    }

    public function setSite(CNabuSite $nb_site)
    {
        $this->nb_site = $nb_site;

        return $this;
    }

    public function getSiteAlias()
    {
        return $this->nb_site_alias;
    }

    public function setSiteAlias(CNabuSiteAlias $nb_site_alias)
    {
        $this->nb_site_alias = $nb_site_alias;

        return $this;
    }

    public function getHostBaseDirectory()
    {
        $document_root = $this->getDocumentRoot();
        $folder = $this->isSecureServer() ? 'httpsdocs' : 'httpdocs';
        $open_basedir = ini_get('open_basedir');
        $base_dir = false;

        $parts = preg_split('/:/', $open_basedir);

        if (count($parts) > 0) {
            foreach ($parts as $path) {
                $test = $path . DIRECTORY_SEPARATOR . $folder;
                if ($test === $document_root) {
                    $base_dir = $path;
                    break;
                }
            }
        }

        if (!$base_dir) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_BASEDIR_NOT_STABLISHED);
        }

        if (!is_dir($base_dir)) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_HOST_PATH_NOT_FOUND, array($base_dir));
        }

        return $base_dir;
    }

    public function locateRunningConfiguration()
    {
        if ($this->nb_server === null) {
            $this->locateNabuServer();
        }

        $this->locateCustomer();

        if ($this->nb_site === null) {
            $this->locateNabuSite();
        }

        if ($this->nb_domain_zone === null) {
            $this->locateNabuDomainZone();
        }
    }

    private function locateNabuServer()
    {
        $nb_engine = CNabuEngine::getEngine();

        $this->nb_server_host = null;

        if (($addr = $this->getServerAddress()) &&
            ($port = $this->getServerPort()) &&
            ($server_name = $this->getServerName())
        ) {
            if (($this->nb_server = CNabuServer::findByHostParams($addr, $port, $server_name)) === null) {
                $nb_engine->errorLog("Server host not found $server_name:$port @$addr");
                $this->nb_server_invalid = true;
            } else {
                $this->nb_server_invalid = false;
            }
            if ($this->nb_server === null) {
                throw new ENabuCoreException(
                    ENabuCoreException::ERROR_SERVER_NOT_FOUND, array($server_name, $addr, $port));
            }

            $this->nb_server_host = new CNabuServerHost($this->nb_server);
            if ($this->nb_server_host->isNew()) {
                throw new ENabuCoreException(
                    ENabuCoreException::ERROR_SERVER_HOST_NOT_FOUND, array($addr, $port));
            }
            $nb_engine->traceLog(
                "Server",
                "$addr:$port [" . $this->nb_server->getId() . ',' . $this->nb_server_host->getId() . ']'
            );
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_SERVER_HOST_MISCONFIGURED);
        }

        return $this->nb_server;
    }

    private function locateCustomer()
    {
        $nb_engine = CNabuEngine::getEngine();
        $nb_customer = $nb_engine->getCustomer();

        if ($nb_customer === null && $this->nb_server->contains(NABU_CUSTOMER_FIELD_ID)) {
            $nb_customer = new CNabuCustomer($this->nb_server->getValue(NABU_CUSTOMER_FIELD_ID));
            if ($nb_customer->isFetched()) {
                $nb_engine->setCustomer($nb_customer);
            }
        }
    }

    private function locateNabuDomainZone()
    {
        if ($this->nb_server->contains(NABU_DOMAIN_ZONE_FIELD_ID)) {
            $this->nb_domain_zone = new CNabuDomainZone($this->nb_server);
        } elseif ($this->nb_site->contains(NABU_DOMAIN_ZONE_FIELD_ID)) {
            $this->nb_domain_zone = new CNabuDomainZone($this->nb_site);
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_DOMAIN_ZONE_NOT_FOUND);
        }

        if ($this->nb_server->contains(NABU_DOMAIN_ZONE_HOST_FIELD_ID)) {
            $this->nb_domain_zone_host = new CNabuDomainZoneHost($this->nb_server);
        } elseif ($this->nb_site->contains(NABU_DOMAIN_ZONE_HOST_FIELD_ID)) {
            $this->nb_domain_zone_host = new CNabuDomainZoneHost($this->nb_site);
        }

        if ($this->nb_domain_zone_host !== null) {
            if ($this->nb_domain_zone !== null) {
                $this->nb_domain_zone_host->setDomainZone($this->nb_domain_zone);
            }
            if ($this->nb_site_alias !== null) {
                $this->nb_site_alias->setDomainZoneHost($this->nb_domain_zone_host);
            }
        }
    }

    private function locateNabuSite()
    {
        $nb_engine = CNabuEngine::getEngine();

        $this->nb_site_alias_force_default = false;

        if ($this->nb_server->contains(NABU_SITE_FIELD_ID)) {
            $this->nb_site = $nb_engine->getCustomer()->getSite($this->nb_server);
        } elseif ($server_name = $this->getServerName()) {
            $this->nb_site = $nb_engine->getCustomer()->getSiteByAlias($server_name);
        }

        if ($this->nb_site === null || !$this->nb_site->isFetched()) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_NOT_FOUND);
        } elseif (!$this->nb_site->isPublished()) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_NOT_PUBLISHED, $this->nb_site->getId());
        }

        if ($this->nb_server->contains(NABU_SITE_ALIAS_FIELD_ID)) {
            $this->nb_site_alias = new CNabuSiteAlias($this->nb_server);
        } elseif ($this->nb_site->contains(NABU_SITE_ALIAS_FIELD_ID)) {
            $this->nb_site_alias = new CNabuSiteAlias($this->nb_site);
        }

        if ($this->nb_site_alias === null || !$this->nb_site_alias->isFetched()) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_ALIAS_NOT_FOUND);
        }

        $this->nb_site->setAlias($this->nb_site_alias);

        $nb_engine->traceLog("Site", $this->nb_site->getId());
        $nb_engine->traceLog("Site Alias", $this->nb_site_alias->getId());
    }

    public function locateRemoteAddress()
    {
        $remote_ip = filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED_FOR', false);
        if (!$remote_ip) {
            $remote_ip = filter_input(INPUT_SERVER, 'REMOTE_ADDR', false);
        }

        return $remote_ip;
    }

    public function getAcceptedMIMETypes() {

        if (array_key_exists('HTTP_ACCEPT', $_SERVER)) {
            $mimetypes = preg_split("/(\s*,\s*)/", filter_input(INPUT_SERVER, 'HTTP_ACCEPT'));
            if (count($mimetypes) > 0) {
                $list = array();
                foreach ($mimetypes as $mimetype) {
                    $attrs = $this->parseAcceptedMimetype($mimetype);
                    if (is_array($attrs)) {
                        $list[$attrs['mimetype']] = $attrs;
                    }
                }
            }
        }

        return isset($list) && count($list) > 0 ? $list : null;
    }

    private function parseAcceptedMimetype($mimetype)
    {
        $retval = false;

        $parts = preg_split("/(\s*;\s*)/", $mimetype);
        switch (count($parts)) {
            case 1:
                $retval = array (
                    'mimetype' => $parts[0],
                    'q' => 1
                );
                break;
            case 2:
                $retval = array (
                    'mimetype' => $parts[0],
                    'q' => floatval(substr($parts[1], 2))
                );
            default:
        }

        return $retval;
    }

    public function getAcceptedLanguages() {

        if (array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER)) {
            $languages = preg_split("/(\s*,\s*)/", filter_input(INPUT_SERVER, 'HTTP_ACCEPT_LANGUAGE'));
            if (count($languages) > 0) {
                $list = array();
                foreach ($languages as $language) {
                    $attrs = $this->parseAcceptedLanguage($language);
                    if (is_array($attrs)) {
                        $list[$attrs['language']] = $attrs;
                    }
                }
            }
        }

        return isset($list) && count($list) > 0 ? $list : null;
    }

    private function parseAcceptedLanguage($language)
    {
        $retval = false;

        $parts = preg_split("/(\s*;\s*)/", $language);
        switch (count($parts)) {
            case 1:
                $retval = array (
                    'language' => $parts[0],
                    'q' => 1
                );
                break;
            case 2:
                $retval = array (
                    'language' => $parts[0],
                    'q' => floatval(substr($parts[1], 2))
                );
            default:
        }

        return $retval;
    }

    public function getContentLength()
    {
        return (array_key_exists('CONTENT_LENGTH', $_SERVER) ? (int)$_SERVER['CONTENT_LENGTH'] : 0);
    }

    public function getContentType()
    {
        return (array_key_exists('CONTENT_TYPE', $_SERVER) ? $_SERVER['CONTENT_TYPE'] : null);
    }

    public function getReferer() {

        $referer = null;

        if (array_key_exists('HTTP_REFERER', $_SERVER)) {
            $referer = new CNabuURL(filter_input(INPUT_SERVER, 'HTTP_REFERER'));
            if (!$referer->isValid()) {
                $referer = null;
            }
        }

        return $referer;
    }

    public function isSecureServer()
    {
        $https = $this->getHTTPS();
        return $https !== false && $https !== null;
    }

    /**
     * Checks if a server is valid.
     * Valid servers are Built-in instances or fetched servers.
     * @return bool Returns true if the server is valid.
     */
    public function isServerValid()
    {
        return ($this->nb_server !== null &&
                ($this->nb_server->isBuiltIn() || $this->nb_server->isFetched()));
    }

    public function getOrigin()
    {
        return filter_input(INPUT_SERVER, 'HTTP_ORIGIN');
    }

    public function getContextDocumentRoot()
    {
        return filter_input(INPUT_SERVER, 'CONTEXT_DOCUMENT_ROOT');
    }

    public function getContextPrefix()
    {
        return filter_input(INPUT_SERVER, 'CONTEXT_PREFIX');
    }

    public function getDocumentRoot()
    {
        return filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
    }

    public function getGatewayInterface()
    {
        return filter_input(INPUT_SERVER, 'GATEWAY_INTERFACE');
    }

    public function getHTTPAccept()
    {
        return filter_input(INPUT_SERVER, 'HTTP_ACCEPT');
    }

    public function getHTTPAcceptEncoding()
    {
        return filter_input(INPUT_SERVER, 'HTTP_ACCEPT_ENCODING');
    }

    public function getHTTPAcceptLanguage()
    {
        return filter_input(INPUT_SERVER, 'HTTP_ACCEPT_LANGUAGE');
    }

    public function getHTTPConnection()
    {
        return filter_input(INPUT_SERVER, 'HTTP_CONNECTION');
    }

    public function getHTTPHost()
    {
        return filter_input(INPUT_SERVER, 'HTTP_HOST');
    }

    public function getHTTPS()
    {
        return filter_input(INPUT_SERVER, 'HTTPS');
    }

    public function getHTTPUserAgent()
    {
        return filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');
    }

    public function getPHPSelf()
    {
        return filter_input(INPUT_SERVER, 'PHP_SELF');
    }

    public function getQueryString()
    {
        return filter_input(INPUT_SERVER, 'QUERY_STRING');
    }

    public function getRemoteAddress()
    {
        return filter_input(INPUT_SERVER, 'REMOTE_ADDRESS');
    }

    public function getRemotePort()
    {
        return filter_input(INPUT_SERVER, 'REMOTE_PORT');
    }

    public function getRequestMethod()
    {
        return filter_input(INPUT_SERVER, 'REQUEST_METHOD');
    }

    public function getRequestScheme()
    {
        return filter_input(INPUT_SERVER, 'REQUEST_SCHEME');
    }

    public function getRequestTime($float = false)
    {
        if ($float) {
            $retval = filter_input(INPUT_SERVER, 'REQUEST_TIME_FLOAT');
        } else {
            $retval = filter_input(INPUT_SERVER, 'REQUEST_TIME');
        }

        return $retval;
    }

    public function getRequestURI()
    {
        return filter_input(INPUT_SERVER, 'REQUEST_URI');
    }

    public function getScriptFilename()
    {
        return filter_input(INPUT_SERVER, 'SCRIPT_FILENAME');
    }

    public function getScriptName()
    {
        return filter_input(INPUT_SERVER, 'SCRIPT_NAME');
    }

    public function getServerAddress()
    {
        return filter_input(INPUT_SERVER, 'SERVER_ADDR');
    }

    public function getServerAdmin()
    {
        return filter_input(INPUT_SERVER, 'SERVER_ADMIN');
    }

    public function getServerName()
    {
        return filter_input(INPUT_SERVER, 'SERVER_NAME');
    }

    public function getServerPort()
    {
        return filter_input(INPUT_SERVER, 'SERVER_PORT');
    }

    public function getServerProtocol()
    {
        return filter_input(INPUT_SERVER, 'SERVER_PROTOCOL');
    }

    public function getServerSignature()
    {
        return filter_input(INPUT_SERVER, 'SERVER_SIGNATURE');
    }

    public function getServerSoftware()
    {
        return filter_input(INPUT_SERVER, 'SERVER_SOFTWARE');
    }
}
