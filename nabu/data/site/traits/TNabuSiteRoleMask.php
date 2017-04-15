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

namespace nabu\data\site\traits;
use nabu\core\exceptions\ENabuCoreException;
use nabu\data\site\CNabuSite;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site
 */
trait TNabuSiteRoleMask
{
    /**
     * Check if the Role is applied to Public Zone.
     * @param bool $strict If true, only roles with nb_site_map_role_zone = 'O' are valid. If false, 'O' and 'B' values
     * are true.
     * @return bool Return true if the Role applies to Public Zone.
     */
    public function isForPublicZone(bool $strict = false)
    {
        if (!method_exists($this, 'getZone')) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_FEATURE_NOT_IMPLEMENTED);
        }

        $zone = $this->getZone();

        return ($zone === CNabuSite::ZONE_PUBLIC || (!$strict && $zone === CNabuSite::ZONE_BOTH));
    }

    /**
     * Check if the Role is applied to Private Zone.
     * @param bool $strict If true, only roles with nb_site_map_role_zone= 'P' are valid. If false, 'P' and 'B' values
     * are true.
     * @return bool Return true if the Role applies to Private Zone.
     */
    public function isForPrivateZone(bool $strict = false)
    {
        if (!method_exists($this, 'getZone')) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_FEATURE_NOT_IMPLEMENTED);
        }

        $zone = $this->getZone();

        return ($zone === CNabuSite::ZONE_PRIVATE || (!$strict && $zone === CNabuSite::ZONE_BOTH));
    }

    /**
     * Check if the Role is applied to both zones.
     * @return bool Return true if the Role applies to both zones.
     */
    public function isForBothZones()
    {
        if (!method_exists($this, 'getZone')) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_FEATURE_NOT_IMPLEMENTED);
        }

        return $this->getZone() === CNabuSite::ZONE_BOTH;
    }

    /**
     * Check it the Role is hidden.
     * @return bool Return true if the Role is hidden and does not to be applied.
     */
    public function isZoneHidden()
    {
        if (!method_exists($this, 'getZone')) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_FEATURE_NOT_IMPLEMENTED);
        }

        $zone = $this->getZone();

        return $zone !== CNabuSite::ZONE_PUBLIC && $zone !== CNabuSite::ZONE_PRIVATE && $zone !== CNabuSite::ZONE_HIDDEN;
    }
}
