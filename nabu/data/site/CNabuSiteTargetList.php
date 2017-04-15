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
 *  See the License for the specific SiteTarget governing permissions and
 *  limitations under the License.
 */

namespace nabu\data\site;

use nabu\core\CNabuEngine;
use nabu\data\site\base\CNabuSiteTargetListBase;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site
 */
class CNabuSiteTargetList extends CNabuSiteTargetListBase
{
    /**
     * Secondary index using only Site Targets with static URLs
     * @var string
     */
    const INDEX_URL_STATIC = 'static_urls';
    /**
     * Secondary index using only Site Targets with like URLs
     * @var string
     */
    const INDEX_URL_LIKE = 'like_urls';
    /**
     * Secondary index using only Site Targets with regexp URLs
     * @var string
     */
    const INDEX_URL_REGEXP = 'regex_urls';

    public function __construct()
    {
        parent::__construct('nb_site_target_id');
    }

    protected function createSecondaryIndexes()
    {
        parent::createSecondaryIndexes();

        $this->addIndex(
            new CNabuSiteTargetListIndexURL(
                $this,
                CNabuSiteTarget::URL_TYPE_PATH,
                'nb_site_target_lang_url', 'nb_site_target_order',
                self::INDEX_URL_STATIC)
        );
        $this->addIndex(
            new CNabuSiteTargetListIndexURL(
                $this,
                CNabuSiteTarget::URL_TYPE_LIKE,
                'nb_site_target_lang_url', 'nb_site_target_order',
                self::INDEX_URL_LIKE)
        );
        $this->addIndex(
            new CNabuSiteTargetListIndexURL(
                $this,
                CNabuSiteTarget::URL_TYPE_REGEXP,
                'nb_site_target_lang_url', 'nb_site_target_order',
                self::INDEX_URL_REGEXP)
        );
    }

    /**
     * Overrides temporally this method to support nb_site_target_key acquisitions.
     */
    public function acquireItem($key, $index = false)
    {
        if ($index === self::INDEX_KEY) {
            $nb_site = CNabuEngine::getEngine()->getApplication()->getRequest()->getSite();
            $retval = CNabuSiteTarget::findByKey($nb_site, $key);
        } else {
            $retval = parent::acquireItem($key, $index);
        }

        return $retval;
    }

    /**
     * Search in the collection to find targets that matches $url.
     * @param string $url URL to looking for.
     * @return CNabuSiteTarget Returns the found instance if any, or null if none.
     */
    public function findURL($url)
    {
        $nb_site_target = $this->getItem($url, self::INDEX_URL_STATIC);
        if ($nb_site_target === null) {
            //$nb_site_target = $this->findByRegExpr(self::INDEX_URL_REGEXP, $url);
        }

        return $nb_site_target;
    }

    /**
     * Search a Site Target instance only using static URLs.
     * @param string $url URL to looking for.
     * @return CNabuSiteTarget Returns the found instance if any, or null if none.
     */
    public function findStaticURL($url)
    {
        return $this->getItem($url, self::INDEX_URL_STATIC);
    }

    /**
     * Search a Site Target instance only using regexp URLs.
     * @param string $url URL to looking for.
     * @return CNabuSiteTarget Returns the found instance if any, or null if none.
     */
    public function findRegExprURL($url)
    {
        //return $this->findByRegExpr(self::INDEX_URL_REGEXP, $url);
    }
}
