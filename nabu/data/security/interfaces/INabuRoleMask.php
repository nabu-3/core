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

namespace nabu\data\security\interfaces;

use nabu\data\security\CNabuRole;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package nabu\data\interfaces
 */
interface INabuRoleMask
{
    /**
     * Apply a Role to the object to secure it before to be used. All components of the object that don't meet
     * with the role will be removed before return. These components will be removed only in memory.
     * @param CNabuRole $nb_role Role to be applied.
     * @param array $additional Additional data to consider when Role Mask is applied.
     * @return bool Returns true if the object meets the role and false if not.
     */
    public function applyRoleMask(CNabuRole $nb_role, array $additional = null);
}
