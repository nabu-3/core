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

use nabu\data\site\CNabuSite;

/**
 * Interface to implement HTTP Server File Systems.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\http\interfaces
 */
interface INabuHTTPFileSystem
{
    /**
     * Gets the Virtual Hosts Storage Base Path for all Hosts.
     * @return string Returns the path.
     */
    public function getVirtualHostsBasePath() : string;
    /**
     * Gets the Virtual Libraries Storage Base Path for all Hosts.
     * @return string Returns the path.
     */
    public function getVirtualLibrariesBasePath() : string;
    /**
     * Gets the Cache Storage Base Path for all Hosts.
     * @return string Returns the path.
     */
    public function getVirtualCacheBasePath() : string;
    /**
     * Gets the Site Storage Base Path.
     * @param CNabuSite $nb_site Site instance.
     * @return string Returns the effective Site Storage Base Path.
     */
    public function getSiteBasePath(CNabuSite $nb_site) : string;
    /**
     * Gets the Site Virtual Library Storage Path.
     * @param CNabuSite $nb_site Site instance.
     * @return string Returns the effective Site Storage Virtual Library Path.
     */
    public function getSiteVirtualLibraryPath(CNabuSite $nb_site) : string;
    /**
     * Gets the Site Cache Path.
     * @param CNabuSite $nb_site Site instance.
     * @return string Returns the effective Site Storage Cache Path.
     */
    public function getSiteCachePath(CNabuSite $nb_site) : string;
}
