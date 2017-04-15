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

use \nabu\data\site\base\CNabuSiteTargetLanguageBase;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site
 */
class CNabuSiteTargetLanguage extends CNabuSiteTargetLanguageBase
{
    public function getEffectiveURL($params = false)
    {
        $url = false;
        $nb_site_target = $this->getTranslatedObject();

        if ($nb_site_target !== null) {
            if (($nb_site_target->isURLRegExpExpression() || $nb_site_target->isURLLikeExpression()) &&
                strlen($this->getURLRebuild()) > 0
            ) {
                if (count($params) > 0) {
                    $url = nb_vnsprintf($this->getURLRebuild(), $params);
                } else {
                    $url = $this->getURLRebuild();
                }
            } elseif ($nb_site_target->isURLStaticPath()) {
                $url = $this->getURL();
            }
        }

        return $url;
    }
}
