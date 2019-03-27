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

namespace nabu\http;

use nabu\core\CNabuObject;

use nabu\data\site\CNabuSite;

use nabu\http\interfaces\INabuHTTPFileSystem;

/**
 * Class used to manage HTTP Server File System.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\core
 */
class CNabuHTTPFileSystem extends CNabuObject implements INabuHTTPFileSystem
{
    public function getVirtualHostsBasePath(): string
    {
        return NABU_VHOSTS_PATH;
    }

    public function getVirtualLibrariesBasePath(): string
    {
        return NABU_VLIB_PATH;
    }

    public function getVirtualCacheBasePath(): string
    {
        return NABU_VCACHE_PATH;
    }

    public function getSiteBasePath(CNabuSite $nb_site): string
    {
        return $this->getVirtualHostsBasePath() . DIRECTORY_SEPARATOR . $nb_site->getBasePath();
    }

    public function getSiteVirtualLibraryPath(CNabuSite $nb_site): string
    {
        return $this->getVirtualLibrariesBasePath() . DIRECTORY_SEPARATOR . $nb_site->getBasePath();
    }

    public function getSiteCachePath(\nabu\data\site\CNabuSite $nb_site): string
    {
        return $this->getVirtualCacheBasePath() . DIRECTORY_SEPARATOR . $nb_site->getBasePath();
    }
}
