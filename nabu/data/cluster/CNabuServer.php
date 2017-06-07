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
use nabu\data\site\CNabuSiteList;

/**
 * Class to extend management of table 'nb_server' where Servers are stored.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.11 Surface
 */
class CNabuServer extends CNabuServerBase
{
    /** @var $nb_admin_user Admin user instance of this server */
    private $nb_admin_user = null;

    /**
     * Get all available sites to be indexed in the Web Server
     * @return CNabuSiteList Returns a list of all available sites if someone exists.
     */
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
            $retval = new CNabuSiteList();
        }

        return $retval;
    }

    /**
     * Gets the Admin User instance of this server instance.
     * @param bool $force If true forces to reload instance from the database storage.
     * @return CNabuUser Retuns a User instance if a user is assigned or null if none.
     */
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

    /**
     * Find a Host using a basic set of params.
     * @param string $addr IP address of the Web Server.
     * @param int $port TCP Port of the Web Server.
     * @param string $server_name Server name or alias of the host.
     * @return CNabuServer Returns a Server instance if someone is assigned to a server with same param coordinates.
     */
    public static function findByHostParams($addr, $port, $server_name) {

        return CNabuServer::buildObjectFromSQL(
            "select se.*, sh.nb_server_host_id, cg.nb_cluster_group_id, cgs.nb_cluster_group_service_id, "
                 . "s.nb_customer_id, s.nb_site_id, sa.nb_site_alias_id, dzh.nb_domain_zone_id, "
                 . "dzh.nb_domain_zone_host_id, c.nb_customer_id "
            . "from nb_server se, nb_server_host sh, nb_ip i, nb_site s, nb_cluster_group cg, "
                 . "nb_cluster_group_service cgs, nb_site_alias_service sas, "
                 . "nb_site_alias sa, nb_domain_zone_host dzh, nb_domain_zone dz, nb_customer c "
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
             . "and concat(dzh.nb_domain_zone_host_name, '.', dz.nb_domain_zone_name)='%server_name\$s' "
             . "and s.nb_customer_id=c.nb_customer_id "
             . "and s.nb_customer_id=dz.nb_customer_id",
            array(
                'addr' => $addr,
                'port' => $port,
                'server_name' => $server_name
            )
        );
    }

    public function getFrameworkPath()
    {
        return NABU_BASE_PATH;
    }

    public function getLogsPath()
    {
        return NABU_LOG_PATH . DIRECTORY_SEPARATOR . $this->getKey();
    }

    public function getVirtualHostsPath()
    {
        return NABU_VHOSTS_PATH;
    }

    public function getVirtualLibrariesPath()
    {
        return NABU_VLIB_PATH;
    }

    public function getVirtualCachePath()
    {
        return NABU_VCACHE_PATH;
    }

    public function getRuntimePath()
    {
        return NABU_RUNTIME_PATH;
    }
}
