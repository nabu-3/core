<?php

/*  Copyright 2009-2011 Rafael Gutierrez Martinez
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
use nabu\data\security\CNabuUser;

/**
 * This trait implements default actions to manage a User child object in nabu-3.
 * You can apply this trait to your own classes to speed up your development,
 * or create your own management.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\security\traits;
 */
trait TNabuUserChild
{
    /** @var CNabuUser $nb_user User instance */
    private $nb_user = null;

    /**
     * Gets the User instance.
     * @param bool $force If true, forces to load User from storage.
     * @return CNabuUser|null Returns the User instance if setted or null if not.
     */
    public function getUser($force = false)
    {
        if ($this->nb_user === null || $force) {
            $this->nb_user = null;
            if ($this instanceof CNabuDataObject &&
                $this->contains(NABU_USER_FIELD_ID) &&
                $this->isValueNumeric(NABU_USER_FIELD_ID)
            ) {
                $nb_user = new CNabuUser($this->getValue(NABU_USER_FIELD_ID));
                if ($nb_user->isFetched()) {
                    $this->nb_user = $nb_user;
                }
            }
        }

        return $this->nb_user;
    }

    /**
     * Sets the User instance that owns this object and sets the field containing the User id.
     * @param CNabuUser $nb_user User instance to be setted.
     * @param string $field Field name where the User id will be stored.
     * @return mixed Returns $this to allow the cascade chain of setters.
     */
    public function setUser(CNabuUser $nb_user)
    {
        $this->nb_user = $nb_user;
        if ($this instanceof CNabuDataObject) {
            $this->transferValue($nb_user, NABU_USER_FIELD_ID);
        }

        return $this;
    }
}
