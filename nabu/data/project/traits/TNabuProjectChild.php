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

namespace nabu\data\project\traits;
use nabu\data\CNabuDataObject;
use nabu\data\customer\CNabuCustomer;
use nabu\data\project\CNabuProject;

/**
 * This trait implements default actions to manage a Project child object in nabu-3.
 * You can apply this trait to your own classes to speed up your development,
 * or create your own management.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\project\traits;
 */
trait TNabuProjectChild
{
    /**
     * Project instance
     * @var CNabuProject
     */
    protected $nb_project = null;

    /**
     * Gets the Project instance.
     * @param null|CNabuCustomer If you want to get the Project in safe mode, you need to pass this parameter
     * to grant the Customer that owns the Project where the Project is placed. In any other case,
     * the Project could not be retrieved.
     * @param bool $force If true, then tries to reload Project instance from their storage.
     * @return CNabuProject|null Returns the Project instance if setted or null if not.
     */
    public function getProject(CNabuCustomer $nb_customer = null, $force = false)
    {
        if ($nb_customer !== null && ($this->nb_project === null || $force)) {
            $this->nb_project = null;
            if ($this instanceof CNabuDataObject && $this->contains(NABU_PROJECT_FIELD_ID)) {
                $this->nb_project = $nb_customer->getProject($this);
            }
        }

        return $this->nb_project;
    }

    /**
     * Sets the Project instance that owns this object and sets the field containing the project id.
     * @param CNabuProject $nb_project Project instance to be setted.
     * @param string $field Field name where the site id will be stored.
     * @return CNabuDataObject Returns $this to allow the cascade chain of setters.
     */
    public function setProject(CNabuProject $nb_project, $field = NABU_PROJECT_FIELD_ID)
    {
        $this->nb_project = $nb_project;
        if ($this instanceof CNabuDataObject) {
            $this->transferValue($nb_project, NABU_PROJECT_FIELD_ID, $field);
        }

        return $this;
    }
}
