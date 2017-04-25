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

namespace nabu\data\security;
use nabu\data\customer\CNabuCustomer;
use nabu\data\security\base\CNabuUserGroupBase;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\security
 */
class CNabuUserGroup extends CNabuUserGroupBase
{
    /** @var CNabuUser $nb_user User owner instance of this group. */
    private $nb_user = null;

    public function getOwner(bool $force = false)
    {
        if ($nb_user === null || $this->getUserId() !== $nb_user->getId() || $force) {
            error_log("HOLA 1");
            $this->nb_user = null;
            if (($nb_customer = $this->getCustomer()) instanceof CNabuCustomer) {
                error_log("HOLA 2");
                $this->nb_user = $nb_customer->getUser($this->getUserId());
            }
        } else {
            error_log("HOLA 3");
        }

        return $this->nb_user;
    }
}
