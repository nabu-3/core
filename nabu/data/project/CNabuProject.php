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

namespace nabu\data\project;
use nabu\data\project\base\CNabuProjectBase;
use nabu\data\project\base\CNabuProjectVersion;
use nabu\data\project\base\CNabuProjectVersionList;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\messaging
 */
class CNabuProject extends CNabuProjectBase
{
    /** @var CNabuProjectVersionList $nb_project_version_list List of versions in the project. */
    private $nb_project_version_list = null;

    public function __construct($nb_project = false)
    {
        parent::__construct($nb_project);

        $this->nb_project_version_list = new CNabuProjectVersionList();
    }

    /**
     * Gets Versions assigned to the project.
     * @param bool $force If true, the Project Version list is refreshed from the database.
     * @return CNabuProjectVersionList Returns the list of Versions. If none Version exists, the list is empty.
     */
    public function getVersions($force = false)
    {
        if ($this->nb_project_version_list === null) {
            $this->nb_project_version_list = new CNabuProjectVersionList();
        }

        if ($this->nb_project_version_list->isEmpty() || $force) {
            $this->nb_project_version_list->clear();
            $this->nb_project_version_list->merge(CNabuProjectVersion::getAllProjectVersions($this));
        }

        return $this->nb_project_version_list;
    }
}
