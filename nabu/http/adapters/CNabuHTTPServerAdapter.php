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

use \nabu\core\CNabuObject;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\cluster\CNabuServer;
use \nabu\data\site\CNabuSite;
use \nabu\http\interfaces\INabuHTTPServer;
use nabu\data\cluster\CNabuServerHost;
use nabu\data\domain\CNabuDomainZone;
use nabu\data\domain\CNabuDomainZoneHost;
use nabu\data\site\CNabuSiteAlias;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\http\adapters
 */
abstract class CNabuHTTPServerAdapter extends CNabuObject implements INabuHTTPServer
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

    public function __construct(CNabuServer $nb_server = null, CNabuSite $nb_site = null)
    {
        parent::__construct();

        $this->nb_server = $nb_server;
        $this->nb_site = $nb_site;
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
}
