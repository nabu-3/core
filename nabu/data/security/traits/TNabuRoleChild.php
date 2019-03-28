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

namespace nabu\data\security\traits;
use nabu\data\CNabuDataObject;
use nabu\data\security\CNabuRole;

/**
 * This trait implements default actions to manage a Role child object in nabu-3.
 * You can apply this trait to your own classes to speed up your development,
 * or create your own management.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\security\traits;
 */
trait TNabuRoleChild
{
    /**
     * Role instance
     * @var CNabuRole
     */
    private $nb_role = null;

    /**
     * Gets the Role instance.
     * @param bool $force If true, forces to load Role from storage.
     * @return CNabuRole|null Returns the Role instance if setted or null if not.
     */
     public function getRole($force = false)
     {
         if ($this->nb_role === null || $force) {
             $this->nb_role = null;
             if ($this->isValueNumeric('nb_role_id')) {
                 $nb_role = new CNabuRole($this);
                 if ($nb_role->isFetched()) {
                     $this->nb_role = $nb_role;
                 }
             }
         }

         return $this->nb_role;
     }

    /**
     * Sets the Role instance that owns this object and sets the field containing the Role id.
     * @param CNabuRole $nb_role Role instance to be setted.
     * @param string $field Field name where the Role id will be stored.
     * @return mixed Returns $this to allow the cascade chain of setters.
     */
    public function setRole(CNabuRole $nb_role)
    {
        $this->nb_role = $nb_role;
        if ($this instanceof CNabuDataObject) {
            $this->transferValue($nb_role, NABU_ROLE_FIELD_ID);
        }

        return $this;
    }
}
