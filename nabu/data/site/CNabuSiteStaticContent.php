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

use \nabu\data\site\base\CNabuSiteStaticContentBase;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site
 */
class CNabuSiteStaticContent extends CNabuSiteStaticContentBase
{
    const TYPE_PLAIN = 'P';
    const TYPE_HTML = 'H';

    const USE_ALTERNATIVE_DISABLED = 'D';
    const USE_ALTERNATIVE_SITE = 'S';
    const USE_ALTERNATIVE_DEFAULT = 'E';
    const USE_ALTERNATIVE_AVAILABLE = 'B';

    /**
     * Gets a list of all Static Contents for a Site.
     * @param mixed $nb_site A CNabuDataObject containing a field named nb_site_id or a valid ID.
     * @return CNabuSiteStaticContentList Returns a Static Content List with statics found.
     */
    static public function getStaticContentsForSite($nb_site) : CNabuSiteStaticContentList
    {
        $nb_site_id = nb_getMixedValue($nb_site, NABU_SITE_FIELD_ID);
        if (is_numeric($nb_site_id)) {
            $retval = CNabuSiteStaticContent::buildObjectListFromSQL(
                'nb_site_static_content_id',
                'select * '
                . 'from nb_site_static_content '
               . 'where nb_site_id=%site_id$d',
                array(
                    'site_id' => $nb_site_id
                ),
                $nb_site
            );
            if ($nb_site instanceof CNabuSite) {
                $retval->iterate(function($key, $nb_static) use ($nb_site) {
                    $nb_static->setSite($nb_site);
                });
            }
        } else {
            $retval = new CNabuSiteStaticContentList();
        }

        return $retval;
    }
}
