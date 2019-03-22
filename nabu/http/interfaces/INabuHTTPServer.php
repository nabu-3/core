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

namespace nabu\http\interfaces;

use \nabu\data\cluster\CNabuServer;
use \nabu\data\site\CNabuSite;

/**
 * Interface for classes that represents a Nabu HTTP Server running instance.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.12 Surface
 * @package \nabu\http\interfaces
 */
interface INabuHTTPServer
{
    /**
     * Check if the Server instance is a secure server (HTTPS built-in)
     * @return bool Returns true if is a secure server or false if not.
     */
    public function isSecureServer();
    /**
     * Gets current Server instance.
     * @return CNabuServer Returns the Server instance if exists or null if not.
     */
    public function getServer();
    /**
     * Sets the current Server instance.
     * @param CNabuServer $nb_server New Server instance to be setted.
     * @return INabuHTTPServer Returns the $this instance to grant chained setters.
     */
    public function setServer(CNabuServer $nb_server);
    /**
     * Gets current Site instance.
     * @return CNabuSite Returns the Site instance if exists or null if not.
     */
    public function getSite();
    /**
     * Sets the current Site instance.
     * @param CNabuSite $nb_site New Site instance to be setted.
     * @return INabuHTTPServer Returns the $this instance to grant chained setters.
     */
    public function setSite(CNabuSite $nb_site);

    public function createStandaloneConfiguration();
    public function getHostBaseDirectory();
    public function locateRunningConfiguration();
    public function locateRemoteAddress();
    public function getAcceptedMIMETypes();
    public function getAcceptedLanguages();
    public function getReferer();
    public function getOrigin();
    public function getContentLength();
    public function getContentType();

    public function getContextDocumentRoot();
    public function getContextPrefix();
    public function getDocumentRoot();
    public function getGatewayInterface();
    public function getHTTPAccept();
    public function getHTTPAcceptEncoding();
    public function getHTTPAcceptLanguage();
    public function getHTTPConnection();
    public function getHTTPHost();
    public function getHTTPS();
    public function getHTTPUserAgent();
    public function getPHPSelf();
    public function getQueryString();
    public function getRemoteAddress();
    public function getRemotePort();
    public function getRequestMethod();
    public function getRequestScheme();
    public function getRequestTime($float = false);
    public function getRequestURI();
    public function getScriptFilename();
    public function getScriptName();
    public function getServerAddress();
    public function getServerAdmin();
    public function getServerName();
    public function getServerPort();
    public function getServerProtocol();
    public function getServerSignature();
}
