<?php

namespace nabu\http\interfaces;

/**
 * Interface to create Site Target plugins to extend the functionality of nabu-3
 * @author Rafael Gutiérrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 */
interface INabuHTTPSiteTargetPlugin
{
    /**
     * This method is called just after the instantiation of plugin to set request and response objects
     * @param $nb_request nabu\http\CNabuHTTPRequest Request object
     * @param $nb_response nabu\http\CNabuHTTPResponse Response object
     * @return boolean Return true if trap successfully or false if not
     */
    public function trap($nb_request, $nb_response);
    
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
