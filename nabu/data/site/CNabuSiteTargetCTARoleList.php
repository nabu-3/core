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
use nabu\data\site\base\CNabuSiteTargetCTARoleListBase;
use nabu\data\site\traits\TNabuSiteTargetCTAChild;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site
 */
class CNabuSiteTargetCTARoleList extends CNabuSiteTargetCTARoleListBase
{
    use TNabuSiteTargetCTAChild;

    public function __construct(CNabuSiteTargetCTA $nb_site_target_cta = null)
    {
        parent::__construct();

        if ($nb_site_target_cta !== null) {
            $this->setSiteTargetCTA($nb_site_target_cta);
        }
    }

    public function fillFromCTA()
    {
        $this->clear();
        if ($this->nb_site_target_cta !== null &&
            is_numeric($nb_site_target_cta_id = $this->nb_site_target_cta->getId())
        ) {
            $this->merge(
                CNabuSiteTargetCTARole::buildObjectListFromSQL(
                    'nb_role_id',
                    'select * '
                    . 'from nb_site_target_cta_role '
                   . 'where nb_site_target_cta_id=%cta_id$d',
                    array(
                       'cta_id' => $nb_site_target_cta_id
                   ),
                   $this->nb_site_target_cta
                )
            );
        }
    }
}
