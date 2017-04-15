<?php

namespace nabu\http\interfaces;
use nabu\http\CNabuHTTPRequest;
use nabu\http\CNabuHTTPResponse;

/**
 * Interface to create Site Target plugins to extend the functionality of nabu-3
 * @author Rafael Gutiérrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.12 Surface
 * @package \nabu\http\interfaces
 */
interface INabuHTTPSiteTargetPlugin
{
    /**
     * This method is called just after the instantiation of plugin to set request and response objects
     * @param CNabuHTTPRequest $nb_request Request object
     * @param CNabuHTTPResponse $nb_response Response object
     * @return bool Return true if trap successfully or false if not
     */
    public function trap(CNabuHTTPRequest $nb_request, CNabuHTTPResponse $nb_response) : bool;

    /**
     * This method is called when the target is located
     * @return mixed Returns different values according to result of call:
     * - true: if success
     * - false: if error
     * - \nabu\core\CNabuHTTPRedirection: if redirection needed
     */
    public function prepareTarget();

    /**
     * This method is called after the end of article preparation, just before render it
     * @return mixed Returns different values according to result of call:
     * - true: if success
     * - false: if error
     * - \nabu\core\CNabuHTTPRedirection: if redirection needed
     */
    public function beforeDisplayTarget();
}
