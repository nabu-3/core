<?php

namespace nabu\http\exceptions;

use nabu\core\exceptions\ENabuException;
use nabu\http\CNabuHTTPRedirection;

/**
 * Exception to throw redirections to Engine level breaking the process and return flow to Core main process.
 * @author Rafael Gutiérrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
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
