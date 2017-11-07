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
use nabu\data\security\CNabuRole;
use nabu\data\security\interfaces\INabuRoleMask;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package nabu\data\traits
 */
trait TNabuRoleMaskList
{
    private $role_applied = false;

    /**
     * Applies Role policies to this list. All items that does not accomplishes the policies are removed.
     * @param CNabuRole $nb_role Role instance to be applied.
     * @param array $additional Additional data to be considered. The treatment of this data depends on the
     * class representing each item.
     * @return int Return the number of items that accomplished policies.
     */
    public function applyRoleMask(CNabuRole $nb_role, array $additional = null)
    {
        $total = $this->getSize();
        $count = 0;
        $this->iterate(function($key, INabuRoleMask $item) use($count, $nb_role, $additional) {
            if ($item->applyRoleMask($nb_role, $additional)) {
                $count++;
            } else {
                $this->removeItem($item);
            }
            return true;
        });

        $this->role_applied = true;

        return $total === 0 || ($count > 0 && $count <= $total);
    }

    public function isEmpty()
    {
        return (!$this->role_applied) && parent::isEmpty();
    }
}
