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

namespace nabu\data\project;
use nabu\data\CNabuDataObject;
use nabu\data\project\base\CNabuProjectBase;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
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

    /**
     * Overrides getTreeData method to add translations branch.
     * If $nb_language have a valid value, also adds a translation object
     * with current translation pointed by it.
     * @param int|CNabuDataObject $nb_language Instance or Id of the language to be used.
     * @param bool $dataonly Render only field values and ommit class control flags.
     * @return array Returns a multilevel associative array with all data.
     */
    public function getTreeData($nb_language = null, $dataonly = false)
    {
        $trdata = parent::getTreeData($nb_language, $dataonly);

        $trdata['languages'] = $this->getLanguages();
        $trdata['versions'] = $this->getVersions();

        return $trdata;
    }

    /**
     * Overrides refresh method to add messaging subentities to be refreshed.
     * @param bool $force Forces to reload entities from the database storage.
     * @param bool $cascade Forces to reload child entities from the database storage.
     * @return bool Returns true if transations are empty or refreshed.
     */
    public function refresh(bool $force = false, bool $cascade = false) : bool
    {
        return parent::refresh($force, $cascade) && (!$cascade || $this->getVersions($force));
    }

}
