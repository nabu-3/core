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

namespace nabu\http\exceptions;

use nabu\core\exceptions\ENabuException;
use nabu\http\CNabuHTTPRedirection;

/**
 * Exception to throw redirections to Engine level breaking the process and return flow to Core main process.
 * @author Rafael Gutiérrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.12 Surface
 * @package \nabu\http\exceptions
 */
class ENabuRedirectionException extends ENabuException
{
    private $http_response_code;
    /**
     * Location where the redirection goes
     * @var CNabuHTTPRedirection
     */
    private $location;

    /**
     * Constructor.
     * @param int $code HTTP Response Code
     * @param CNabuHTTPRedirection $location Redirection instance
     */
    public function __construct($code, $location)
    {
        $this->http_response_code = $code;
        $this->location = $location;

        parent::__construct();
    }

    public function getHTTPResponseCode()
    {
        return $this->http_response_code;
    }

    /**
     * Gets the instance of the location where the exception needs to redirect.
     * @return CNabuHTTPRedirection Returns the instance with the redirection information
     * raised when the exception was generated.
     */
    public function getLocation()
    {
        return $this->location;
    }
}
