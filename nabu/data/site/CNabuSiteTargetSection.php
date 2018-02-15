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

use \nabu\data\site\base\CNabuSiteTargetSectionBase;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site
 */
class CNabuSiteTargetSection extends CNabuSiteTargetSectionBase
{
    /**
     * Gets entire collection of Section instances of a Site Target.
     * @param mixed $nb_site_target A Site Target instance, an object containing a Site Target Id, or an Id.
     * @return CNabuSiteTargetSectionList Returns a CNabuSiteTargetSectionList instance containing located
     * Sections for $nb_site_target.
     */
    static public function getSiteTargetSections($nb_site_target)
    {
        $nb_site_target_id = nb_getMixedValue($nb_site_target, 'nb_site_target_id');
        if (is_numeric($nb_site_target_id)) {
            $retval = CNabuSiteTargetSection::buildObjectListFromSQL(
                'nb_site_target_section_id',
                'select * '
                . 'from nb_site_target_section '
               . 'where nb_site_target_id=%target_id$d '
               . 'order by nb_site_target_section_order',
                array(
                    'target_id' => $nb_site_target_id
                )
            );
            if ($nb_site_target instanceof CNabuSiteTarget) {
                $retval->iterate(function($key, $nb_site_target_section) use ($nb_site_target) {
                    $nb_site_target_section->setSiteTarget($nb_site_target);
                    return true;
                });
            }
        } else {
            $retval = new CNabuSiteTargetSectionList();
        }

        return $retval;
    }
}
