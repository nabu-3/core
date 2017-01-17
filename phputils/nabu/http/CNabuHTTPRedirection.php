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

namespace nabu\http;

use \nabu\core\CNabuObject;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\site\CNabuSiteTarget;

/**
 * Class used to store redirection parameters of a redirection
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\core
 */
class CNabuHTTPRedirection extends CNabuObject
{
    private $http_code = false;
    private $url = false;
    private $nb_site_target = false;

    public function __construct($http_code, $target)
    {
        parent::__construct();

        if (!is_numeric($http_code)) {
            throw new ENabuCoreException(
            ENabuCoreException::ERROR_UNEXPECTED_PARAM_CLASS_TYPE,
                array(get_class($http_code), '$http_code')
            );
        } else {
            $this->http_code = $http_code;
        }

        if ($target instanceof CNabuSiteTarget) {
            $this->url = false;
            $this->nb_site_target = $target;
        } elseif (is_string($target)) {
            $this->url = $target;
            $this->nb_site_target = false;
        } else {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_CLASS_TYPE,
                array(get_class($target), '$target')
            );
        }
    }

    public function getHTTPCode()
    {
        return $this->http_code;
    }

    public function getSiteTarget()
    {
        return $this->nb_site_target;
    }

    public function getURL()
    {
        return $this->url;
    }

    public function getEffectiveURL()
    {
        throw new ENabuCoreException(ENabuCoreException::ERROR_METHOD_NOT_IMPLEMENTED, __METHOD__);
    }
}
