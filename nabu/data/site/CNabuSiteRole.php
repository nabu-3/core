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

use nabu\data\CNabuDataObject;

use \nabu\data\site\base\CNabuSiteRoleBase;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site
 */
class CNabuSiteRole extends CNabuSiteRoleBase
{
    /**
     * Generic creation of a CNabuSiteTargetLink from a link descriptor contained in their fields.
     * Some of these descriptors can be the "Default" Target, or "Page Not Found" Target.
     * @param string $kind Infix fragment of names of fields involved in referred descriptor.
     * @return CNabuSiteTargetLink Returns the formed link instance.
     */
    public function getReferredTargetLink(string $kind)
    {
        $field_use_uri = 'nb_site_role_' . $kind . '_target_use_uri';
        $field_target_id = 'nb_site_role_' . $kind . '_target_id';
        $field_lang_url = 'nb_site_role_lang_' . $kind . '_target_url';

        return ($this->contains($field_use_uri) && $this->contains($field_target_id))
               ? CNabuSiteTargetLink::buildLinkFromReferredInstance(
                     $this->getSite(), $this, $field_use_uri, $field_target_id, $field_lang_url
                 )
               : new CNabuSiteTargetLink()
        ;
    }

    /**
     * Gets the Login Redirection Target Link instance.
     * @param CNabuSiteRole $nb_site_role The Site Role entity to discern if target is in the role or applies
     * general configuration.
     * @return CNabuSiteTargetLink Returns the Site Target Link generated instance.
     */
    public function getLoginRedirectionTargetLink(CNabuSiteRole $nb_site_role)
    {
        return $this->getReferredTargetLink('login_redirection', $nb_site_role);
    }

    /**
     * Get the list of all Roles assigned to a Site.
     * @param mixed $nb_site A CNabuDataObject containing a field named nb_site_id or a valid Site Id.
     * @return CNabuSiteRoleList Returns a list with all Site Role instances found.
     */
    public static function getSiteRolesForSite($nb_site) : CNabuSiteRoleList
    {
        if (is_numeric($nb_site_id = nb_getMixedValue($nb_site, NABU_SITE_FIELD_ID))) {
            $retval = CNabuSiteRole::buildObjectListFromSQL(
                'nb_role_id',
                'select * '
                . 'from nb_site_role '
               . 'where nb_site_id=%site_id$d',
                array(
                    'site_id' => $nb_site_id
                )
            );
            if ($nb_site instanceof CNabuSite) {
                $retval->iterate(function($key, CNabuSiteRole $nb_site_role) use ($nb_site) {
                    $nb_site_role->setSite($nb_site);
                    return true;
                });
            }
        } else {
            $retval = new CNabuSiteRoleList();
        }

        return $retval;
    }
}
