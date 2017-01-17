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

namespace nabu\data\site;
use nabu\core\CNabuEngine;
use nabu\data\site\base\CNabuSiteTargetCTARoleBase;
use nabu\data\site\traits\TNabuSiteRoleMask;
use nabu\data\site\traits\TNabuSiteTargetCTAChild;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site
 */
class CNabuSiteTargetCTARole extends CNabuSiteTargetCTARoleBase
{
    use TNabuSiteTargetCTAChild;
    use TNabuSiteRoleMask;

    /**
     * Get all roles to be applied to all CTAs of a Site Target.
     * @param mixed $nb_site_target Site Target to retrieve Site Target CTA Roles.
     * @return array|null Returns a Site Map Role List with roles. If no roles the list is empty.
     */
    static public function getCTARolesForSiteTarget($nb_site_target)
    {
        $nb_site_target_id = nb_getMixedValue($nb_site_target, NABU_SITE_TARGET_FIELD_ID);
        if (is_numeric($nb_site_target_id)) {
            $retval = CNabuEngine::getEngine()
                    ->getMainDB()
                    ->getQueryAsObjectArray(
                        '\nabu\data\site\CNabuSiteTargetCTARole', null,
                        'select stcr.* '
                        . 'from nb_site_target_cta stc, nb_site_target_cta_role stcr '
                       . 'where stc.nb_site_target_cta_id=stcr.nb_site_target_cta_id '
                         . 'and stc.nb_site_target_id=%target_id$d',
                        array(
                            'target_id' => $nb_site_target_id
                        )
                    )
            ;
        } else {
            $retval = null;
        }

        return $retval;
    }

}
