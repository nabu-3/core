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

namespace nabu\data\cluster;

use \nabu\data\cluster\base\CNabuServerBase;
use \nabu\data\site\CNabuSite;
use nabu\data\security\CNabuUser;

/**
 * Class to extend management of table 'nb_server' where Servers are stored.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 */
class CNabuServer extends CNabuServerBase
{
    private $nb_admin_user = null;

    public function getSitesIndex() {

        if ($this->isValueNumeric('nb_server_id')) {
            $retval = CNabuSite::buildObjectListFromSQL('nb_site_id',
                    "select distinct si.* "
                    . "from nb_site si "
                   . "inner join nb_cluster_group cg "
                      . "on si.nb_cluster_group_id=cg.nb_cluster_group_id "
                   . "inner join nb_cluster_group_service cgs "
                      . "on cg.nb_cluster_group_id=cgs.nb_cluster_group_id "
                   . "inner join nb_server_host sh "
                      . "on cgs.nb_cluster_group_service_id=sh.nb_cluster_group_service_id "
                   . "inner join nb_server se "
                      . "on sh.nb_server_id=se.nb_server_id "
                     . "and se.nb_server_id=%server_id\$d "
                   . "inner join nb_site_alias sa "
                      . "on si.nb_site_id=sa.nb_site_id "
                   . "order by si.nb_site_mounting_order asc",
                    array('server_id' => $this->getValue('nb_server_id'))
            );
        } else {
            $retval = null;
        }

        return $retval;
    }

    public function getAdminUser($force = false) {

        if ($this->nb_admin_user === null || $force) {
            $this->nb_admin_user = null;
            if ($this->isValueNumeric('nb_server_admin_user_id')) {
                $nb_user = new CNabuUser($this->getAdminUserId());
                if ($nb_user->isFetched()) {
                    $this->nb_admin_user = $nb_user;
                }
            }
        }

        return $this->nb_admin_user;
    }

    public static function findByHostParams($addr, $port, $server_name) {

        return CNabuServer::buildObjectFromSQL(
            "select se.*, sh.nb_server_host_id, cg.nb_cluster_group_id, cgs.nb_cluster_group_service_id, "
                 . "s.nb_customer_id, s.nb_site_id, sa.nb_site_alias_id, dzh.nb_domain_zone_id, dzh.nb_domain_zone_host_id "
            . "from nb_server se, nb_server_host sh, nb_ip i, nb_site s, nb_cluster_group cg, nb_cluster_group_service cgs, nb_site_alias_service sas, "
                 . "nb_site_alias sa, nb_domain_zone_host dzh, nb_domain_zone dz "
           . "where se.nb_server_id=sh.nb_server_id "
             . "and sh.nb_ip_id=i.nb_ip_id "
             . "and i.nb_ip_ip='%addr\$s' "
             . "and sh.nb_server_host_port=%port\$d "
             . "and sh.nb_cluster_group_id=cg.nb_cluster_group_id "
             . "and sh.nb_cluster_group_id=s.nb_cluster_group_id "
             . "and sh.nb_cluster_group_id=cgs.nb_cluster_group_id "
             . "and sh.nb_cluster_group_service_id=cgs.nb_cluster_group_service_id "
             . "and cgs.nb_cluster_group_service_id=sas.nb_cluster_group_service_id "
             . "and sas.nb_site_alias_id=sa.nb_site_alias_id "
             . "and sa.nb_site_id=s.nb_site_id "
             . "and sa.nb_domain_zone_host_id=dzh.nb_domain_zone_host_id "
             . "and dzh.nb_domain_zone_id=dz.nb_domain_zone_id "
             . "and dzh.nb_domain_zone_host_type in ('A', 'CNAME') "
             . "and concat(dzh.nb_domain_zone_host_name, '.', dz.nb_domain_zone_name)='%server_name\$s'",
            array(
                'addr' => $addr,
                'port' => $port,
                'server_name' => $server_name
            )
        );
    }

    public static function findByDefaultHostParams($addr, $port, $server_name) {

        $parts = explode('.', $server_name);
        if (count($parts) > 0) {
            $parts[0] = '*';
            $final_name = implode('.', $parts);
        } else {
            return null;
        }
        return CNabuServer::buildObjectFromSQL(
                "select se.*, sh.nb_server_host_id, cg.nb_cluster_group_id, cgs.nb_cluster_group_service_id, "
                     . "s.nb_customer_id, s.nb_site_id, sa.nb_site_alias_id "
                . "from nb_server se, nb_server_host sh, nb_ip i, nb_site s, nb_cluster_group cg, nb_cluster_group_service cgs, nb_site_alias_service sas, "
                     . "nb_site_alias sa, nb_domain_zone_host dzh, nb_domain_zone dz "
               . "where se.nb_server_id=sh.nb_server_id "
                 . "and sh.nb_ip_id=i.nb_ip_id "
                 . "and i.nb_ip_ip='%addr\$s' "
                 . "and sh.nb_server_host_port=%port\$d "
                 . "and sh.nb_cluster_group_id=cg.nb_cluster_group_id "
                 . "and sh.nb_cluster_group_id=s.nb_cluster_group_id "
                 . "and sh.nb_cluster_group_id=cgs.nb_cluster_group_id "
                 . "and sh.nb_cluster_group_service_id=cgs.nb_cluster_group_service_id "
                 . "and cgs.nb_cluster_group_service_id=sas.nb_cluster_group_service_id "
                 . "and sas.nb_site_alias_id=sa.nb_site_alias_id "
                 . "and sa.nb_site_id=s.nb_site_id "
                 . "and sa.nb_domain_zone_host_id=dzh.nb_domain_zone_host_id "
                 . "and dzh.nb_domain_zone_id=dz.nb_domain_zone_id "
                 . "and dzh.nb_domain_zone_host_type in ('A', 'CNAME') "
                 . "and concat(dzh.nb_domain_zone_host_name, '.', dz.nb_domain_zone_name)='%server_name\$s'",
                array(
                    'addr' => $addr,
                    'port' => $port,
                    'server_name' => $final_name
                )
        );
    }
}
