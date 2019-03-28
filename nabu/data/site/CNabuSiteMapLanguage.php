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

use \nabu\data\site\base\CNabuSiteMapLanguageBase;
use nabu\core\CNabuEngine;
use nabu\core\exceptions\ENabuCoreException;
use nabu\data\CNabuDataObject;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site
 */
class CNabuSiteMapLanguage extends CNabuSiteMapLanguageBase
{
    /**
     * Final URL calculated in the canonize method of CNabuSiteMap
     * @var string
     */
    private $final_url = null;
    /**
     * Cached JSON of nb_site_map_lang_match_url_fragment
     * @var array
     */
    private $match_url_fragment = null;

    public function getFinalURL()
    {
        return $this->final_url;
    }

    public function setFinalURL($url)
    {
        $this->final_url = $url;

        return $this;
    }

    public function getMatchURLFragment($force = false)
    {
        if ($match_url_fragment === null || $force) {
            $fragment = parent::getMatchURLFragment();
            $this->match_url_fragment = json_decode($fragment, true);
        }

        return $this->match_url_fragment;
    }

    public function setMatchURLFragment(string $match_list = null) : CNabuDataObject
    {
        if (is_string($match_list)) {
            $this->match_url_fragment = array($match_list);
        } elseif (is_array($match_list)) {
            $this->match_url_fragment = $match_list;
        } else {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_CLASS_TYPE,
                array('$match_list', print_r($match_list, true)))
            ;
        }

        if (is_array($this->match_url_fragment)) {
            parent::setMatchURLFragment(json_encode($this->match_url_fragment));
        } else {
            parent::setMatchURLFragment(null);
        }

        return $this;
    }

    public function isValueEqualThan($name, $test)
    {
        $retval = false;

        if ($name === 'nb_site_map_lang_match_url_fragment') {
            if (is_string($test)) {
                $test = array($test);
            }
            $retval = ($test === null && $this->match_url_fragment === null) ||
                      (is_array($test) && is_array($this->match_url_fragment) &&
                       count($test) === count($this->match_url_fragment) && $test === $this->match_url_fragment)
            ;
        } else {
            $retval = parent::isValueEqualThan($name, $test);
        }

        return $retval;
    }

    public function getTreeData($nb_language = null, $dataonly = false)
    {
        $trdata = parent::getTreeData($nb_language, $dataonly);

        $trdata['final_url'] = $this->final_url;

        return $trdata;
    }

    static public function getMapTranslationsForSite($nb_site)
    {
        $nb_site_id = nb_getMixedValue($nb_site, NABU_SITE_FIELD_ID);
        if (is_numeric($nb_site_id)) {
            $retval = CNabuEngine::getEngine()
                    ->getMainDB()
                    ->getQueryAsObjectArray(
                        '\nabu\data\site\CNabuSiteMapLanguage', null,
                        'select sml.* '
                        . 'from nb_site_map sm, nb_site_map_lang sml '
                       . 'where sm.nb_site_map_id=sml.nb_site_map_id '
                         . 'and sm.nb_site_id=%site_id$d '
                       . 'order by sm.nb_site_map_order, sml.nb_language_id',
                        array(
                            'site_id' => $nb_site_id
                        )
                    )
            ;
        } else {
            $retval = null;
        }

        return $retval;
    }
}
