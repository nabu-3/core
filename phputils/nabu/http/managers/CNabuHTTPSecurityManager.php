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

namespace nabu\http\managers;

use Exception;
use nabu\core\CNabuEngine;
use nabu\core\exceptions\ENabuCoreException;
use nabu\data\customer\CNabuCustomer;
use nabu\data\security\CNabuUser;
use nabu\data\security\CNabuRole;
use nabu\data\security\interfaces\INabuRoleMask;
use nabu\data\site\CNabuSite;
use nabu\data\site\CNabuSiteUser;
use nabu\data\site\CNabuSiteTarget;
use nabu\http\CNabuHTTPRequest;
use nabu\http\CNabuHTTPResponse;
use nabu\http\app\base\CNabuHTTPApplication;
use nabu\http\managers\base\CNabuHTTPManager;

/**
 * Class to manage Security of HTTP requests.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\http\managers
 */
class CNabuHTTPSecurityManager extends CNabuHTTPManager
{
    /**
     * Session name for Role instance.
     * @var string
     */
    const VAR_SESSION_ROLE = 'nb_role';
    /**
     * Session name for User instance.
     * @var string
     */
    const VAR_SESSION_USER = 'nb_user';
    /**
     * Session name for Site User instance.
     * @var string
     */
    const VAR_SESSION_SITE_USER = 'nb_site_user';
    /**
     * Session name for preserved flag.
     * @var string
     */
    const VAR_SESSION_PRESERVED = 'nb_session_preserved';
    /**
     * Session name for Work Customer instance.
     * @var string
     */
    const VAR_SESSION_WORK_CUSTOMER = 'nb_work_customer';
    /**
     * Role Mask key for additional param user_signed
     * @var string
     */
    const ROLE_MASK_USER_SIGNED = 'user_signed';

    /**
     * Contains the User instance that makes request.
     * @var CNabuUser
     */
    private $nb_user;
    /**
     * Contains the Role instance that pertains to $nb_user.
     * @var CNabuRole
     */
    private $nb_role;
    /**
     * Contains the Site User (Profile) instance for $nb_user in the current Site.
     * @var CNabuSiteUser
     */
    private $nb_site_user;
    /**
     * Contains the Work Customer instance for $nb_user to allow it to manage an alternate Customer.
     * Normally this feature is intended only for Nabu 3 CMS, but can be applied to other use cases.
     * @var CNabuCustomer
     */
    private $nb_work_customer;

    public function __construct(CNabuHTTPApplication $nb_application)
    {
        parent::__construct($nb_application);
    }

    /**
     * Register the provider in current application to extend their functionalities.
     * @return bool Returns true if enable process is succeed.
     */
    protected function enableManager()
    {
        return true;
    }

    /**
     * Init the Security Manager Engine and recover session variables.
     */
    public function initSecurity()
    {
        $nb_session = $this->nb_application->getSession();
        $this->nb_role = $nb_session->getVariable(self::VAR_SESSION_ROLE, null);
        $this->nb_user = $nb_session->getVariable(self::VAR_SESSION_USER, null);
        $this->nb_site_user = $nb_session->getVariable(self::VAR_SESSION_SITE_USER, null);
        $this->nb_work_customer = $nb_session->getVariable(self::VAR_SESSION_WORK_CUSTOMER, null);
    }

    /**
     * Gets current Role instance.
     * @return CNabuRole Returns current Role instance if user is logged in or null if not.
     */
    public function getRole()
    {
        return $this->nb_role;
    }

    /**
     * Gets current User instance.
     * @return CNabuUser Returns current User instance if user is logged in or null if not.
     */
    public function getUser()
    {
        return $this->nb_user;
    }

    /**
     * Gets current instance of User Profile to current Site
     * @return CNabuSiteUser Returns current Site User Profile instance or null if not.
     */
    public function getSiteUser()
    {
        return $this->nb_site_user;
    }

    /**
     * Gets the current Work Customer. This customer is used as a delegated Customer from the main Customer that
     * owns the Engine instance.
     * @return CNabuCustomer Returns the Work Customer instance if exists or null if not.
     */
    public function getWorkCustomer()
    {
        return $this->nb_work_customer;
    }

    /**
     * Checks if a user is logged.
     * @return bool Returns true if the user is logged
     */
    public function isUserLogged()
    {
        return ($this->nb_user instanceof CNabuUser);
    }

    /**
     * Validate if current user or anonymous user if none user is nogged in can access to $nb_site_target.
     * @param CNabuSite $nb_site Current Site that owns the target.
     * @param CNabuSiteTarget $nb_site_target Current Site Target to evaluate.
     * @return bool Returns true if the target is visible.
     * @throws ENabuCoreException Throws an exception no role is assigned.
     */
    public function validateVisibility(CNabuSite $nb_site, CNabuSiteTarget $nb_site_target)
    {
        $retval = false;

        $nb_engine = CNabuEngine::getEngine();
        $nb_http_server = $nb_engine->getHTTPServer();

        $nb_engine->traceLog(
            "HTTP Support",
            ($nb_site->isHTTPSupportEnabled() && $nb_site_target->isHTTPEnabled() ? "yes" : "no")
        );
        $nb_engine->traceLog(
            "HTTPS Support",
            ($nb_site->isHTTPSSupportEnabled() && $nb_site_target->isHTTPSEnabled() ? "yes" : "no")
        );

        $secure = $nb_http_server->isSecureServer();
        $nb_engine->traceLog("Secure Request", ($secure ? 'yes' : 'no'));

        $is_http = !$secure && $nb_site->isHTTPSupportEnabled() && $nb_site_target->isHTTPEnabled();
        $is_https = $secure && $nb_site->isHTTPSSupportEnabled() && $nb_site_target->isHTTPSEnabled();

        if ($is_http || $is_https) {
            $nb_session = $this->nb_application->getSession();
            $this->nb_user = $nb_session->getVariable(self::VAR_SESSION_USER, null);
            if ($this->nb_user !== null) {
                $nb_engine->traceLog("User ID", $this->nb_user->getValue('nb_user_id'));
                $nb_engine->traceLog("User", $this->nb_user->getValue('nb_user_login'));
            }

            $this->nb_role = $nb_session->getVariable(self::VAR_SESSION_ROLE, null);
            if ($this->nb_role === null) {
                $this->nb_role = (
                    $this->nb_user !== null
                    ? $nb_site->getRoleForUser($this->nb_user)
                    : $nb_site->getDefaultRole()
                );
            }
            if ($this->nb_role === null) {
                throw new ENabuCoreException(ENabuCoreException::ERROR_ROLE_NOT_ASSIGNED);
            }
            $nb_session->setVariable(self::VAR_SESSION_ROLE, $this->nb_role);
            $nb_engine->traceLog("Role", $this->nb_role->getKey());

            $retval = true;
        }

        return $retval;
    }

    /**
     * Validates if the zone is allowed for current user.
     * @param CNabuSiteTarget $nb_site_target Site Target to grant zone access.
     * @return bool Return true if the zone is allowed for requested Site Target.
     */
    public function validateZone(CNabuSiteTarget $nb_site_target)
    {
        $nb_engine = CNabuEngine::getEngine();

        $this->zone = ($this->nb_user !== null ? 'P' : 'O');
        $nb_engine->traceLog("Request Zone", ($this->zone === 'P' ? 'Private' : 'Public'));

        $site_target_zone = $nb_site_target->getZone();
        $nb_engine->traceLog(
                "Page Zone",
                ($site_target_zone === 'P' ? 'Private' : ($site_target_zone === 'O' ? 'Public' : 'Both'))
        );

        return ($site_target_zone === 'B') ||
               ($site_target_zone !== null && $site_target_zone === $this->zone);
    }

    /**
     * Validates if the Customer identified in $nb_customer is a valid Customer to be managed by current User.
     * If the customer is valid, then returns the Customer instance.
     * @param mixed $nb_customer A CNabuDataObject containing a field named nb_customer_id or and ID.
     * @return CNabuCustomer Returns the identified Customer instance if exists and is allowed to current user.
     */
    public function validateWorkCustomer($nb_customer)
    {
        $this->nb_work_customer = null;

        $nb_customer_id = nb_getMixedValue($nb_customer, NABU_CUSTOMER_FIELD_ID);
        if (is_numeric($nb_customer_id)) {
            if ($this->nb_role->isRoot()) {
                $nb_customer = new CNabuCustomer($nb_customer_id);
                if ($nb_customer->isFetched()) {
                    $this->nb_work_customer = $nb_customer;
                }
            } elseif ($this->nb_user !== null) {
                $this->nb_work_customer = $this->nb_user->getAllowedCustomer($nb_customer_id);
            }
        }

        $this->nb_application->getSession()->setVariable(self::VAR_SESSION_WORK_CUSTOMER, $this->nb_work_customer);
        $this->nb_application
            ->getPluginsManager()
            ->invoqueTrap($this->nb_application->getRequest(), $this->nb_application->getResponse())
        ;

        return $this->nb_work_customer;
    }

    /**
     * Validate a touple (user, password) including their validation status in the database.
     * If the user exists identified with this touple in the active site, and he is active, then performs
     * the login process following the Site configuration for login process.
     * If Site have redirections configured, this method raises an exception to break the flow.
     * @param string $user User identifier (email or login)
     * @param string $passwd Password of user without encoding.
     * @param bool $remember If true, applies policies of Site to remember the session in the browser.
     * @return string|bool Returns true if the touple exists and user is validated.
     * @throws ENabuCoreException Raises an exception if some parameter or HTTP Server / Application are invalids
     * or to force redirections.
     */
    public function validateLogin($user, $passwd, $remember = false)
    {
        if (!is_string($user)) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                array('$user', print_r($user, true))
            );
        }

        if (!is_string($passwd)) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                array('$passwd', print_r($passwd, true))
            );
        }

        $nb_plugin_manager = $this->nb_application->getPluginsManager();
        if ($nb_plugin_manager === null) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_PLUGINS_MANAGER_REQUIRED);
        }

        $nb_request = $this->nb_application->getRequest();
        if ($nb_request === null || !($nb_request instanceof CNabuHTTPRequest)) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_REQUEST_NOT_FOUND);
        }

        $nb_response = $this->nb_application->getResponse();
        if ($nb_response === null || !($nb_response instanceof CNabuHTTPResponse)) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_RESPONSE_NOT_FOUND);
        }

        $nb_site = $this->nb_application->getHTTPServer()->getSite();
        if ($nb_site === null || !($nb_site instanceof CNabuSite)) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_NOT_FOUND);
        }

        $retval = false;

        $nb_user = CNabuUser::findBySiteLogin($nb_site, $user, $passwd);
        if ($nb_user !== null) {
            $retval = $this->loginDecision($nb_site, $nb_user, $remember);
        }

        return $retval;
    }

    /**
     * Validate a touple (user, password) and ignores their validation status in the database.
     * If the user exists identified with this touple in the active site, then returns the validation status of user.
     * @param string $user User identifier (email or login)
     * @param string $passwd Password of user without encoding.
     * @return string|bool Returns the validation status of the user if exists a valid touple or false if not.
     */
    public function checkUserValidationStatus($user, $passwd)
    {
        if (!is_string($user)) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                array('$user', print_r($user, true))
            );
        }

        if (!is_string($passwd)) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
                array('$passwd', print_r($passwd, true))
            );
        }

        $nb_site = $this->nb_application->getHTTPServer()->getSite();
        if ($nb_site === null || !($nb_site instanceof CNabuSite)) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_NOT_FOUND);
        }

        $retval = false;

        $nb_user = CNabuUser::findBySiteLogin($nb_site, $user, $passwd, false);
        if ($nb_user !== null) {
            $retval = $nb_user->getValidationStatus();
        }

        return $retval;
    }

    /**
     * Check if the user is logged.
     * @return bool Returns true if the user is logged.
     */
    public function isLogged()
    {
        return ($this->nb_user !== null && $this->nb_role !== null && $this->nb_site_user !== null);
    }

    /**
     * Make internal processes and call plugin to check if the user can be signed in.
     * If sign in is granted and an automatic redirection is configured or returned by plugin, then perform the redirection.
     * If none redirection is allowed then, returns true if user is logged or false if not.
     * Finally, in any case, log in the user table the timestamp of log in.
     * @param CNabuSite $nb_site Site to give the decision
     * @param CNabuUser $nb_user User to evaluate
     * @param bool $preserve If true the session is preserved against navigator restarts
     * @return bool Return true if user is logged and false if not
     * @throws ENabuCoreException Raises an exception if some parameter or HTTP Server / Application are invalids
     * or to force redirection.
     */
    private function loginDecision(CNabuSite $nb_site, CNabuUser $nb_user, bool $preserve)
    {
        if (!($nb_site instanceof CNabuSite)) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_METHOD_PARAMETER_NOT_VALID, array('$nb_site'));
        }

        if (!($nb_user instanceof CNabuUser) || !$nb_user->isFetched()) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_METHOD_PARAMETER_NOT_VALID, array('$nb_user'));
        }

        $nb_plugins_manager = $this->nb_application->getPluginsManager();
        if ($nb_plugins_manager === null) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_PLUGIN_MANAGER_REQUIRED);
        }

        if (($nb_site_user = $nb_site->getUserProfile($nb_user)) === null ||
            ($nb_role = $nb_site_user->getRole()) === null
        ) {
            $after_login = false;
        } else {
            if (($after_login = $nb_plugins_manager->invoqueAfterLogin($nb_user, $nb_role, $nb_site_user)) === true) {
                $nb_session = $this->nb_application->getSession();
                $nb_session->setVariable(self::VAR_SESSION_USER, $nb_user);
                $nb_session->setVariable(self::VAR_SESSION_ROLE, $nb_role);
                $nb_session->setVariable(self::VAR_SESSION_SITE_USER, $nb_site_user);
                $nb_session->setVariable(self::VAR_SESSION_PRESERVED, $preserve);

                $this->nb_user = $nb_user;
                $this->nb_role = $nb_role;
                $this->nb_site_user = $nb_site_user;

                if ($nb_site->isValueEqualThan('nb_site_enable_session_strict_policies', 'T')) {
                    session_regenerate_id(true);
                }

                if ($nb_site_user->isValueEqualThan('nb_user_role_force_default_lang', 'T') &&
                    $nb_site_user->isValueNumeric('nb_language_id')
                ) {
                    $lang = $nb_site_user->getLanguageId();
                } else {
                    $lang = null;
                }
                if ($lang === null) {
                    try {
                        $lang = $this->nb_application->getRequest()->getLanguage()->getId();
                    } catch (Exception $e) {
                        $lang = null;
                    }
                }
                if ($lang === null) {
                    $lang = $nb_site->getDefaultLanguageId();
                }
                $url = $nb_site->getLoginRedirectionTargetLink()->getBestQualifiedURL();
                if (strlen($url) > 0) {
                    $after_login = $url . "?logged";
                }

                $this->nb_site_user->logAccess();
                //$nb_user->getStats(true);
            }

            return $nb_plugins_manager->pluginRedirectToPage($after_login);
        }

        return $this->logout();
    }

    /**
     * Perform the user logout in current session.
     * If user is logged in then calls site plugin method interfaces\ICMSSitePlugin::beforeLogout()
     * to prevent site for user log out and treats the response of this method. If user isn't logged
     * or if log out is performed, then if a redirection is configured in the site do the redirection
     * else return true.
     * Finally, if the user can't log out the method returns false.
     * @return bool Return true if session is logged out or false if not
     * @throws ENabuCoreException
     */
    public function logout()
    {
        $nb_plugin_manager = $this->nb_application->getPluginsManager();
        if ($nb_plugin_manager === null) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_PLUGINS_MANAGER_REQUIRED);
        }

        $nb_request = $this->nb_application->getRequest();
        if ($nb_request === null || !($nb_request instanceof CNabuHTTPRequest)) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_REQUEST_NOT_FOUND);
        }

        $nb_response = $this->nb_application->getResponse();
        if ($nb_response === null || !($nb_response instanceof CNabuHTTPResponse)) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_RESPONSE_NOT_FOUND);
        }

        $nb_site = $this->nb_application->getHTTPServer()->getSite();
        if ($nb_site === null || !($nb_site instanceof CNabuSite)) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_SITE_NOT_FOUND);
        }

        if ($this->isLogged()) {
            $before = $nb_plugin_manager->invoqueBeforeLogout();
        } else {
            $before = true;
        }

        if ($before !== false) {
            $_SESSION = array_diff_key(
                    $_SESSION,
                    array(
                        self::VAR_SESSION_USER => null,
                        self::VAR_SESSION_ROLE => null,
                        self::VAR_SESSION_SITE_USER => null,
                        self::VAR_SESSION_PRESERVED => null,
                        self::VAR_SESSION_WORK_CUSTOMER => null
                    )
            );
            if ($nb_site->isValueEqualThan('cms_site_enable_session_strict_policies', 'T')) {
                session_regenerate_id(true);
            }
        }

        if ($before === true) {
            $url = $nb_site->getLogoutRedirectionTargetLink()->getBestQualifiedURL();
            if (strlen(trim($url)) > 0) {
                $before = $url;
            }
        }

        if ($before !== false) {
            if ($before !== true) {
                $nb_response->temporaryRedirect($before);
            }
        }

        return $before;
    }

    public function applyRoleMask(INabuRoleMask $object, array $params = null)
    {
        return $object->applyRoleMask(
            $this->nb_role,
            array(
                self::ROLE_MASK_USER_SIGNED => $this->isLogged()
            )
        );
    }
}