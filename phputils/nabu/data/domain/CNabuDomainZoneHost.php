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

namespace nabu\data\domain;

use \nabu\data\domain\base\CNabuDomainZoneHostBase;
use nabu\data\domain\CNabuDomainZone;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package name
 */
class CNabuDomainZoneHost extends CNabuDomainZoneHostBase
{
    /**
     * Domain Zone instance that owns this host.
     * @var CNabuDomainZone
     */
    private $nb_domain_zone = null;

    public function getDomainZone()
    {
        return $this->nb_domain_zone;
    }

    public function setDomainZone(CNabuDomainZone $nb_domain_zone)
    {
        $this->nb_domain_zone = $nb_domain_zone;
        $this->transferValue($nb_domain_zone, 'nb_domain_zone_id');

        return $this;
    }
}
