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

namespace nabu\data\site;
use nabu\data\domain\CNabuDomainZone;
use nabu\data\domain\CNabuDomainZoneHost;
use nabu\data\security\CNabuRole;
use nabu\data\site\CNabuSiteAlias;
use nabu\data\site\base\CNabuSiteAliasBase;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site
 */
class CNabuSiteAlias extends CNabuSiteAliasBase
{
    const TYPE_NAME = 'F';
    const TYPE_ALIAS = 'T';
    const TYPE_REDIRECTION = 'R';
    const TYPE_NULL = 'N';

    const STATUS_ENABLED = 'E';
    const STATUS_DISABLED = 'D';
    const STATUS_OWNER = 'O';

    protected $alias_name = null;
    private $nb_domain_zone = null;
    private $nb_domain_zone_host = null;
    private $nb_alias_role = null;

    public function getDNSName($force = false) {

        if ($this->alias_name === null || $force) {
            if ($this->nb_domain_zone_host instanceof CNabuDomainZoneHost) {
                $nb_domain_zone = $this->nb_domain_zone_host->getDomainZone();
                $host = $this->nb_domain_zone_host->getName();
                $domain = $nb_domain_zone->getName();
                $this->alias_name = $host . (strlen($host) > 0 && strlen($domain) > 0 ? '.' : '') . $domain;
            } elseif ($this->isValueNumeric('nb_domain_zone_host_id') || $this->isValueGUID('nb_domain_zone_host_id')) {
                $this->alias_name = null;
                $this->nb_domain_zone = null;
                $this->nb_domain_zone_host = null;

                $host = new CNabuDomainZoneHost($this);
                if ($host->isFetched()) {
                    $this->nb_domain_zone_host = $host;
                    $this->nb_domain_zone = new CNabuDomainZone($host);
                    $left = $this->nb_domain_zone_host->getValue('nb_domain_zone_host_name');
                    $right = $this->nb_domain_zone->getValue('nb_domain_zone_name');
                    $this->alias_name = $left . (strlen($left) > 0 && strlen($right) > 0 ? '.' : '') . $right;
                }
            }
        }

        return $this->alias_name;
    }

    public function getOwnerRole($force = false) {

        if ($this->nb_alias_role === null || $force) {
            $this->nb_alias_role = null;
            if ($this->isValueNumeric('nb_user_id') && $this->isValueNumeric('nb_site_id')) {
                $this->nb_alias_role = CNabuRole::buildObjectFromSQL(
                        $this->buildSentence(
                                "select r.* "
                                . "from nb_role r, nb_site_user ur, nb_site s "
                               . "where r.nb_role_id=ur.nb_role_id "
                                 . "and ur.nb_user_id=%nb_user_id\$d "
                                 . "and s.nb_site_id=%nb_site_id\$d "
                                 . "and ur.nb_site_id=if(s.nb_site_delegate_for_role is null, s.nb_site_id , s.nb_site_delegate_for_role)"
                        )
                );
            }
        }

        return $this->nb_alias_role;
    }

    public static function getMainAliasForSite($nb_site) {

        $site_id = nb_getMixedValue($nb_site, 'nb_site_id');

        return is_numeric($site_id)
            ? CNabuSiteAlias::buildObjectFromSQL(
                    "select sa.*, concat(dzh.nb_domain_zone_host_name, '.', dz.nb_domain_zone_name) as nb_site_alias_name "
                    . "from nb_site_alias sa, nb_domain_zone dz, nb_domain_zone_host dzh "
                   . "where sa.nb_site_id=%site_id\$d "
                     . "and sa.nb_site_alias_type='F' "
                     . "and dz.nb_domain_zone_id=dzh.nb_domain_zone_id "
                     . "and dzh.nb_domain_zone_host_id=sa.nb_domain_zone_host_id",
                    array('site_id' => $site_id)
              )
            : null;
    }

    public function getDomainZoneHost()
    {
        return $this->nb_domain_zone_host;
    }

    public function setDomainZoneHost(CNabuDomainZoneHost $nb_domain_zone_host)
    {
        $this->nb_domain_zone_host = $nb_domain_zone_host;
        $this->setDomainZoneHostId($nb_domain_zone_host->getId());

        return $this;
    }
}
