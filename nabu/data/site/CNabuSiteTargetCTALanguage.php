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

use \nabu\data\site\base\CNabuSiteTargetCTALanguageBase;
use nabu\core\CNabuEngine;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package name
 */
class CNabuSiteTargetCTALanguage extends CNabuSiteTargetCTALanguageBase
{
    public function getFinalURL()
    {
        return $this->getValue('nb_site_target_cta_lang_final_url');
    }

    public function setFinalURL($url)
    {
        $this->setValue('nb_site_target_cta_lang_final_url', $url);

        return $this;
    }

    public static function getCTATranslationsForSiteTarget($nb_site_target) {

        $nb_site_target_id = nb_getMixedValue($nb_site_target, NABU_SITE_TARGET_FIELD_ID);
        if (is_numeric($nb_site_target_id)) {
            $retval = CNabuEngine::getEngine()
                    ->getMainDB()
                    ->getQueryAsObjectArray(
                        '\nabu\data\site\CNabuSiteTargetCTALanguage', null,
                        'select * '
                        . 'from nb_site_target_cta stc, nb_site_target_cta_lang stcl '
                       . 'where stc.nb_site_target_cta_id=stcl.nb_site_target_cta_id '
                         . 'and stc.nb_site_target_id=%target_id$d ',
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
