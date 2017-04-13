<?php

namespace nabu\http\interfaces;
use nabu\data\security\CNabuRole;
use nabu\data\security\CNabuUser;
use nabu\data\site\CNabuSiteUser;
use nabu\http\CNabuHTTPRequest;
use nabu\http\CNabuHTTPResponse;

/**
 * Interface to create site plugins to extend the functionality of nabu-3
 * @author Rafael Gutiérrez <rgutierrez@wiscot.com>
 * @since 3.0.0 Surface
 * @version 3.0.12 Surface
 * @package \nabu\http\interfaces
 */
interface INabuHTTPSitePlugin
{
    /**
     * This method is called just after the instantiation of plugin to set request and response objects
     * @param CNabuHTTPRequest $nb_request Request object
     * @param CNabuHTTPResponse $nb_response Response object
     * @return bool Return true if trap successfully or false if not
     */
    public function trap(CNabuHTTPRequest $nb_request, CNabuHTTPResponse $nb_response);

    /**
     * This method is called when the site is prepared at the beggining of the build page step,
     * before obtain the current user. Handle with care.
     * @return mixed Returns different values according to result of call:
     * - true: if success
     * - false: if error
     * - CNabuHTTPRedirection: if redirection needed
     */
    public function prepareSite();

    /**
     * This method if called when the request header have a CORS Origin header in order to validate the target.
     * @param string $origin URL targeted by CORS
     * @return bool Returns true if $origin is allowed or false elsewhere.
     */
    public function validateCORSOrigin(string $origin) : bool;

    /**
     * This method is called when the root page is requested and not defined.
     * If you want to perform a default redirection to language root page simply return true.
     * In another case you can return a \nabu\site\CNabuSiteTarget or an URL string with the redirection.
     * If you return false then an error 500 will be thrown.
     * @return mixed Returns different values according to result of call:
     * - true: if success
     * - false: if error
     * - \nabu\core\CNabuHTTPRedirection: if redirection needed
     */
    public function redirectRoot();

    /**
     * This method is called when the article is located
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

    /**
     * This method is called when no valid page is found
     * @param string $path file path that cannot be found
     * @return mixed Returns different values according to result of call:
     * - true: if success
     * - false: if error
     * - \nabu\core\CNabuHTTPRedirection: if redirection needed
     */
    public function targetNotFound(string $path);

    /**
     * This method is called when invoque programmatically to {@see CNabuHTTPSession logout}
     */
    public function beforeLogout();

    /**
     * This method is called after a login is made
     * @param CNabuUser $nb_user User logged
     * @param CNabuRole $nb_role Role granted for private session
     * @param CNabuSiteUser $nb_site_user Profile of user in this site with this role
     * @return mixed Returns different values according to result of call:
     * - true: if success
     * - false: if error
     * - CNabuHTTPRedirection: if redirection needed
     */
    public function afterLogin(CNabuUser $nb_user, CNabuRole $nb_role, CNabuSiteUser $nb_site_user);
}
