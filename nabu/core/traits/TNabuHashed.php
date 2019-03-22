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

namespace nabu\core\traits;
use nabu\db\CNabuDBObject;

/**
 * Add some methods to manage by default the hashing of some classes.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\core\traits
 */
trait TNabuHashed
{
    /**
     * Grants that this object have a valid hash. Optionally can save the hash in the database.
     * @param bool $save If true, the save() method is called after grant the hash and just before return.
     * @return string Returns a valid hash.
     */
    public function grantHash(bool $save = false) : string
    {
        if (!nb_isValidGUID($hash = $this->getHash())) {
            $this->setHash($hash = nb_generateGUID());
            if ($save && $this instanceof CNabuDBObject) {
                $this->save();
            }
        }

        return $hash;
    }
}
