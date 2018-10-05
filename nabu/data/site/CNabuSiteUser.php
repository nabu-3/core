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

namespace nabu\data\site;

use nabu\data\CNabuDataObject;

use nabu\core\exceptions\ENabuCoreException;

use nabu\data\security\CNabuUserList;

use \nabu\data\site\base\CNabuSiteUserBase;
use nabu\core\exceptions\ENabuSecurityException;
use nabu\data\customer\CNabuCustomer;
use nabu\data\security\CNabuUser;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\site
 */
class CNabuSiteUser extends CNabuSiteUserBase
{
    /**
     * Upate table to set last access datetime of the user.
     */
    public function logAccess()
    {
        $this->db->executeUpdate(
                "update nb_site_user "
                 . "set nb_site_user_last_login_datetime=now() "
               . "where nb_site_id=%site_id\$d "
                 . "and nb_role_id=%role_id\$d "
                 . "and nb_user_id=%user_id\$d",
                array(
                    'site_id' => $this->getValue('nb_site_id'),
                    'role_id' => $this->getValue('nb_role_id'),
                    'user_id' => $this->getValue('nb_user_id')
                )
        );
    }

    /**
     * Get the list of Profiles of a User.
     * @param mixed $nb_user A CNabuDataObject that contains a nb_user_id field or a valid Id.
     * @return CNabuSiteUserList Returns the list of all profiles found.
     */
    public static function getSitesForUser($nb_user)
    {
        if (is_numeric ($nb_user_id = nb_getMixedValue($nb_user, NABU_USER_FIELD_ID))) {
            $retval = CNabuSiteUser::buildObjectListFromSQL(
                'nb_site_id',
                'SELECT su.*
                   FROM nb_site_user su, nb_user u, nb_site s
                  WHERE su.nb_user_id=u.nb_user_id
                    AND su.nb_site_id=s.nb_site_id
                    AND su.nb_user_id=%user_id$d',
                array(
                    'user_id' => $nb_user_id
                ),
                ($nb_user instanceof CNabuUser ? $nb_user : null)
            );
        } else {
            $retval = new CNabuSiteUserList();
        }

        return $retval;
    }

    /**
     * Get not subscribed available Sites for a User.
     * @param CNabuCustomer $nb_customer The Customer that owns Sites and User.
     * @param mixed $nb_user The User to looking for.
     * @return CNabuSiteList The list of Sites availables for requested User.
     * @throws ENabuSecurityException Raises an exception if the User is not owned by the Customer.
     */
    public static function getAvailableSitesForUser(CNabuCustomer $nb_customer, $nb_user)
    {
        if ($nb_customer->isFetched() &&
            is_numeric($nb_user_id = nb_getMixedValue($nb_user, NABU_USER_FIELD_ID))
        ) {
            if (!($nb_user = $nb_customer->getUser($nb_user_id))) {
                throw new ENabuSecurityException(ENabuSecurityException::ERROR_USER_NOT_ALLOWED);
            }
            $retval = CNabuSite::buildObjectListFromSQL(
                'nb_site_id',
                'SELECT s.*, su.nb_user_id
                   FROM nb_site s
                   LEFT OUTER JOIN nb_site_user su
                     ON s.nb_site_id=su.nb_site_id
                    AND su.nb_user_id=%user_id$d
                  WHERE s.nb_customer_id=%cust_id$d
                 HAVING su.nb_user_id IS NULL',
                array(
                    'cust_id' => $nb_customer->getId(),
                    'user_id' => $nb_user->getId()
                ),
                $nb_customer
            );
        } else {
            $retval = new CNabuSiteList($nb_customer);
        }

        return $retval;
    }

    /**
     * Get the full list of active Users. If $nb_role is defined, then filters the list by the represented Role.
     * @param mixed $nb_site A CNabuDataObject instance containing a field called nb_site_id or a valid Site Id.
     * @param mixed $nb_role If defined, then will be a CNabuDataObject instance containing a field named nb_role_id
     * or a valid Id.
     * @return CNabuUserList Returns the list of Users found.
     */
    public static function getActiveUsersForSite($nb_site, $nb_role) : CNabuUserList
    {
        if (is_numeric($nb_site_id = nb_getMixedValue($nb_site, NABU_SITE_FIELD_ID))) {
            if (is_numeric($nb_role_id = nb_getMixedValue($nb_role, NABU_ROLE_FIELD_ID))) {
                $retval = CNabuUser::buildObjectListFromSQL(
                    'nb_user_id',
                    'SELECT u.*
                      FROM nb_user u, nb_site_user su
                     WHERE u.nb_user_id=su.nb_user_id
                       AND u.nb_user_validation_status=\'T\'
                       AND su.nb_site_id=%site_id$d
                       AND su.nb_role_id=%role_id$d',
                    array(
                        'site_id' => $nb_site_id,
                        'role_id' => $nb_role_id
                    ),
                    ($nb_site instanceof CNabuSite ? $nb_site : null)
                );
            } elseif ($nb_role === null) {
                $retval = CNabuUser::buildObjectListFromSQL(
                    'nb_user_id',
                    'SELECT u.*
                      FROM nb_user u, nb_site_user su
                     WHERE u.nb_user_id=su.nb_user_id
                       AND u.nb_user_validation_status=\'T\'
                       AND su.nb_site_id=%site_id$d',
                    array(
                        'site_id' => $nb_site_id
                    ),
                    ($nb_site instanceof CNabuSite ? $nb_site : null)
                );
            } else {
                throw new ENabuCoreException(ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE, array(print_r($nb_role, true), '$nb_role'));
            }
            if ($nb_site instanceof CNabuSite) {
                $retval->iterate(function($key, CNabuUser $nb_user) use ($nb_site)
                {
                    $nb_user->setCustomer($nb_site->getCustomer());
                    return true;
                });
            }
        } else {
            $retval = new CNabuUserList();
        }

        return $retval;
    }

    /**
     * Get the full list of available Users. If $nb_role is defined, then filters the list by the represented Role.
     * @param mixed $nb_site A CNabuDataObject instance containing a field called nb_site_id or a valid Site Id.
     * @param mixed $nb_role If defined, then will be a CNabuDataObject instance containing a field named nb_role_id
     * or a valid Id.
     * @return CNabuUserList Returns the list of Users found.
     */
    public static function getAvailableUsersForSite($nb_site, $nb_role) : CNabuUserList
    {
        if (is_numeric($nb_site_id = nb_getMixedValue($nb_site, NABU_SITE_FIELD_ID))) {
            if (is_numeric($nb_role_id = nb_getMixedValue($nb_role, NABU_ROLE_FIELD_ID))) {
                $retval = CNabuUser::buildObjectListFromSQL(
                    'nb_user_id',
                    'SELECT u.*
                      FROM nb_user u, nb_site_user su
                     WHERE u.nb_user_id=su.nb_user_id
                       AND u.nb_user_validation_status in (\'T\', \'F\', \'I\', \'P\')
                       AND su.nb_site_id=%site_id$d
                       AND su.nb_role_id=%role_id$d',
                    array(
                        'site_id' => $nb_site_id,
                        'role_id' => $nb_role_id
                    ),
                    ($nb_site instanceof CNabuSite ? $nb_site : null)
                );
            } elseif ($nb_role === null) {
                $retval = CNabuUser::buildObjectListFromSQL(
                    'nb_user_id',
                    'SELECT u.*
                      FROM nb_user u, nb_site_user su
                     WHERE u.nb_user_id=su.nb_user_id
                       AND u.nb_user_validation_status in (\'T\', \'F\', \'I\', \'P\')
                       AND su.nb_site_id=%site_id$d',
                    array(
                        'site_id' => $nb_site_id
                    ),
                    ($nb_site instanceof CNabuSite ? $nb_site : null)
                );
            } else {
                throw new ENabuCoreException(ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE, array(print_r($nb_role, true), '$nb_role'));
            }
            if ($nb_site instanceof CNabuSite) {
                $retval->iterate(function ($key, CNabuUser $nb_user) use ($nb_site) {
                    $nb_user->setCustomer($nb_site->getCustomer());
                    return true;
                });
            }
        } else {
            $retval = new CNabuUserList();
        }

        return $retval;
    }
}
