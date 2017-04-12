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

use \nabu\data\security\base\CNabuUserBase;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package name
 */
class CNabuUser extends CNabuUserBase
{
    /**
     * User is active
     * @var string
     */
    const USER_ACTIVE = 'T';
    /**
     * User is new and validation is pending.
     * @var string
     */
    const USER_VALIDATION_PENDING = 'F';
    /**
     * User is new with invitation and validation is pending.
     * @var string
     */
    const USER_VALIDATION_PENDING_WITH_INVITATION = 'P';
    /**
     * User is banned
     * @var string
     */
    const USER_BANNED = 'B';
    /**
     * User is disabled
     * @var string
     */
    const USER_DISABLED = 'D';
    /**
     * User is invited
     * @var string
     */
    const USER_INVITED = 'I';
    /**
     * Prefix to be used when build the encoded password.
     * @var string
     */
    const PASS_PREF = 'nasn2293';
    /**
     * Suffix to be used when build the encoded password.
     * @var string
     */
    const PASS_SUFF = '935nkwnf';

    public function setPassword($password)
    {
        $this->setValue('nb_user_passwd', CNabuUser::encodePassword($password));
    }

    /**
     * Encodes a clear password using the nabu-3 algorithm. This algorithm is not reversible.
     * @param string $password Password string to be encoded.
     * @return string Returns the encoded password as string.
     */
    static public function encodePassword($password)
    {
        return md5(CNabuUser::PASS_PREF . $password . CNabuUser::PASS_SUFF);
    }

    /**
     * Find a user by his login data in a site.
     * @param mixed $nb_site Site instance, object containing a Site Id field or an Id.
     * @param string $login Login name
     * @param string $passwd Password in clear format
     * @param bool $active If true only active users are found else if false then looks for all validation statuses.
     * @return \nabu\data\security\CNabuUser Returns the instance representing the User if found or null if not.
     */
    static public function findBySiteLogin($nb_site, $login, $passwd, $active = true)
    {
        $retval = null;

        $nb_site_id = nb_getMixedValue($nb_site, 'nb_site_id');
        if (is_numeric($nb_site_id)) {
            $retval = CNabuUser::buildObjectFromSQL(
                    "select u.*, su.nb_role_id "
                    . "from nb_user u, nb_site_user su "
                   . "where (u.nb_user_login='%login\$s' or lower(u.nb_user_email)=lower('%login\$s')) "
                     . "and u.nb_user_passwd='%passwd\$s' "
                     . ($active ? "and u.nb_user_validation_status='T' " : '')
                     . "and u.nb_user_id=su.nb_user_id "
                     . "and su.nb_site_id=%site_id\$d",
                    array(
                        'login' => $login,
                        'passwd' => CNabuUser::encodePassword($passwd),
                        'site_id' => $nb_site_id
                    )
            );
        }

        return $retval;
    }

    /**
     * Gets a filtered list of User instances represented as an array. Params allows the capability of select
     * a subset of fields, order by concrete fields, or truncate the list by a number of rows starting in an offset.
     * @param mixed $nb_customer Customer instance, object containing a Customer Id field or an Id.
     * @param string $q Query string to filter results using a context index.
     * @param string|array $fields List of fields to put in the results.
     * @param string|array $order List of fields to order the results. Each field can be suffixed with "ASC" or "DESC"
     * to determine the short order
     * @param int $offset Offset of first row in the results having the first row at offset 0.
     * @param int $num_items Number of continue rows to get as maximum in the results.
     * @return array Returns an array with all rows found using the criteria.
     * @throws \nabu\core\exceptions\ENabuCoreException Raises an exception if $fields or $order have invalid values.
     */
    /*
    static public function getFilteredUserList(
        $nb_customer, $q = null, $fields = null, $order = null, $offset = 0, $num_items = 0
    ) {
        $nb_customer_id = nb_getMixedValue($nb_customer, NABU_CUSTOMER_FIELD_ID);
        if (is_numeric($nb_customer_id)) {
            $fields_part = nb_prefixFieldList(CNabuUser::getStorageName(), $fields, false, true);
            $order_part = nb_prefixFieldList(CNabuUser::getStorageName(), $fields);

            if ($num_items !== 0) {
                $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
            } else {
                $limit_part = false;
            }

            $nb_user_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
                "select " . ($fields_part ? $fields_part . ' ' : '* ')
                . 'from nb_user '
               . 'where ' . NABU_CUSTOMER_FIELD_ID . '=%cust_id$d '
                . ($order_part ? "order by $order_part " : '')
                . ($limit_part ? "limit $limit_part" : ''),
                array(
                    'cust_id' => $nb_customer_id
                )
            );
        } else {
            $nb_user_list = null;
        }

        return $nb_user_list;
    }
    */
}
