<?php

namespace nabu\http\interfaces;
use nabu\data\security\CNabuRole;
use nabu\data\security\CNabuUser;
use nabu\data\site\CNabuSiteUser;

/**
 * Interface to create site plugins to extend the functionality of nabu-3
 * @author Rafael Gutiérrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\http\interfaces
 */
interface INabuHTTPSitePlugin
{
    /**
     * This method is called just after the instantiation of plugin to set request and response objects
     * @param $nb_request nabu\http\CNabuHTTPRequest Request object
     * @param $nb_response nabu\http\CNabuHTTPResponse Response object
     * @return boolean Return true if trap successfully or false if not
     */
    public function trap($nb_request, $nb_response);

    /**
     * This method is called when the site is prepared at the beggining of the build page step,
     * before obtain the current user. Handle with care.
     * @return mixed Returns different values according to result of call:
     * - true: if success
     * - false: if error
     * - \nabu\core\CNabuHTTPRedirection: if redirection needed
     */
    public function prepareSite();

    /**
     * This method if called when the request header have a CORS Origin header in order to validate the target.
     * @param string $origin URL targeted by CORS
     * @return boolean Returns true if $origin is allowed or false elsewhere.
     */
    public function validateCORSOrigin($origin);

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
     * @param $path file path that cannot be found
     * @return mixed Returns different values according to result of call:
     * - true: if success
     * - false: if error
     * - \nabu\core\CNabuHTTPRedirection: if redirection needed
     */
    public function targetNotFound($path);

    /**
     * This method is called when invoque programmatically to {@see \nabu\http\CCMSSession logout}
     */
    public function beforeLogout();

    /**
     * This method is called after a login is made
     * @param $nb_user nabu\security\CNabuUser User logged
     * @param $nb_role nabu\security\CNabuRole Role granted for private session
     * @param $nb_site_user nabu\security\CNabuSiteUser Profile of user in this site with this role
     * @return mixed Returns different values according to result of call:
     * - true: if success
     * - false: if error
     * - \nabu\core\CNabuHTTPRedirection: if redirection needed
     */
    public function afterLogin(CNabuUser $nb_user, CNabuRole $nb_role, CNabuSiteUser $nb_site_user);
}
