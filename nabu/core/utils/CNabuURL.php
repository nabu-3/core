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

namespace nabu\core\utils;

use \nabu\core\CNabuObject;

/**
 * Class to manage URLs
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\core\utils
 */
class CNabuURL extends CNabuObject
{
    private $url;
    private $scheme;
    private $host;
    private $port;
    private $user;
    private $password;
    private $path;
    private $query;
    private $fragment;
    private $valid = false;
    
    public function __construct($url)
    {
        parent::__construct();
        
        $this->parse($url);
    }
    
    public function parse($url)
    {
        $this->url = $url;
        $parts = parse_url($url);
        if ($parts === false) {
            $this->valid = false;
        } else {
            $this->scheme = isset($parts['scheme']) ? $parts['scheme'] : null;
            $this->host = isset($parts['host']) ? $parts['host'] : null;
            $this->port = isset($parts['port']) ? $parts['port'] : null;
            $this->user = isset($parts['user']) ? $parts['user'] : null;
            $this->password = isset($parts['pass']) ? $parts['pass'] : null;
            $this->path = isset($parts['path']) ? $parts['path'] : null;
            $this->query = isset($parts['query']) ? $parts['query'] : null;
            $this->fragment = isset($parts['fragment']) ? $parts['fragment'] : null;
            $this->valid = true;
        }
    }
    
    public function isValid()
    {
        return $this->valid;
    }

    public function getURL()
    {
        return $this->url;
    }
    
    public function getScheme()
    {
        return $this->valid ? $this->scheme : null;
    }
    
    public function getHost()
    {
        return $this->valid ? $this->host : null;
    }
    
    public function getPort()
    {
        return $this->valid ? $this->port : null;
    }
    
    public function getUser()
    {
        return $this->valid ? $this->user : null;
    }
    
    public function getPassword()
    {
        return $this->valid ? $this->password : null;
    }
    
    public function getPath()
    {
        return $this->valid ? $this->path : null;
    }
    
    public function getQUery()
    {
        return $this->valid ? $this->query : null;
    }
    
    public function getFragment()
    {
        return $this->valid ? $this->fragment : null;
    }
}