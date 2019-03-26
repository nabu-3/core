<?php

/** @license
 *  Copyright 2019-2011 Rafael Gutierrez Martinez
 *  Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 *  Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 *  Copyright 2017 nabu-3 Group
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

use \nabu\core\CNabuEngine;
use \nabu\core\CNabuObject;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\core\exceptions\ENabuSingletonException;
use \nabu\core\interfaces\INabuSingleton;
use \nabu\data\site\CNabuSite;
use \nabu\db\interfaces\INabuDBObject;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\http
 */
final class CNabuHTTPSession extends CNabuObject implements INabuSingleton
{
    const NONCE_PREFIX = 'ase92';
    const NONCE_SUFFIX = 'weru21';
    const NONCE_GLUE = '13@#=';
    /**
     * Contains the singleton instance of class.
     * @var CNabuHTTPSession
     */
    private static $nb_session = null;

    /**
     * Default constructor. This object is singleton then, more than one instantion throws Exception.
     * It is not recommendable to instantiate directly. To do this, call {@see \nabu\http\CNabuHTTPSession::getSession()}
     * @throws ENabuSingletonException
     */
    public function __construct()
    {
        if (CNabuHTTPSession::$nb_session != null) {
            throw new ENabuSingletonException("Session already instantiated");
        }
        parent::__construct();
    }

    /**
     * If singleton instance not exists then instantiate it.
     * @return CNabuHTTPSession Returns singleton instance
     * @throws ENabuSingletonException
     */
    public static function getSession()
    {
        if (CNabuHTTPSession::$nb_session === null) {
            CNabuHTTPSession::$nb_session = new CNabuHTTPSession();
            CNabuHTTPSession::$nb_session->init();
        }

        return CNabuHTTPSession::$nb_session;
    }

    /**
     * Check if the session is instantiated.
     * @return bool Returns true if the session is instantiated
     */
    public static function isInstantiated() : bool
    {
        return (CNabuHTTPSession::$nb_session !== null);
    }

    /**
     * This method initializes the instance.
     * Init process consists in start the session and check all objects stored in session
     * to relink the database connection of each one.
     */
    private function init()
    {
        $nb_engine = CNabuEngine::getEngine();

        $session_name = session_name();
        $nb_engine->traceLog("Session Name", $session_name);
        if (array_key_exists($session_name, $_GET)) {
            $sessid = filter_input(INPUT_GET, $session_name);
            if (preg_match('/^[a-zA-Z0-9]{26}$/', $sessid)) {
                session_id($sessid);
                $nb_engine->traceLog("Session source", "GET");
            } else {
                $nb_engine->traceLog("Session source", "COOKIE");
            }
        } else {
            $nb_engine->traceLog("Session source", "COOKIE");
        }

        session_start();
        $nb_engine->traceLog("Session ID", session_id());

        $this->purgeNonce();

        if (isset($_SESSION) && count($_SESSION) > 0) {
            foreach ($_SESSION as $value) {
                if ($value instanceof INabuDBObject && !$value->isBuiltIn()) {
                    $value->relinkDB();
                }
            }
        }
    }

    /**
     * Apply requested security options to protect the session.
     * @param bool $secure If true, forces to set the secure flag of session cookie
     * @param bool $httponly If true, forces to set the httponly flag of session cookie
     */
    public function applySecurityRules(bool $secure = false, bool $httponly = false)
    {
        $nb_engine = CNabuEngine::getEngine();

        $attrs = session_get_cookie_params();
        session_set_cookie_params(
            $attrs['lifetime'],
            $attrs['path'],
            $attrs['domain'],
            $secure ? true : $attrs['secure'],
            $httponly ? true : (array_key_exists('httponly', $attrs) ? $attrs['httponly'] : false)
        );
    }

    /**
     * Check Nonce storage and purge those Nonce keys that are expired.
     */
    public function purgeNonce()
    {
        $nonce_list = $this->getVariable('nonce_list', array());
        $new_nonce_list = array();
        if (count($nonce_list) > 0) {
            $stop_nonce = time() - 1800;
            foreach ($nonce_list as $key => $word) {
                if (count($word) > 0) {
                    $this->purgeNonceWord($key, $word, $stop_nonce, $new_nonce_list);
                }
            }
        }

        if (count($new_nonce_list) > 0) {
            $this->setVariable('nonce_list', $new_nonce_list);
        } else {
            $this->unsetVariable('nonce_list');
        }
    }

    /**
     * Purge a Nonce Word list.
     * @param string $key Nonce key
     * @param array $word Array of words created for $key
     * @param int $stop_nonce Time limit of words to preserve each one
     * @param array &$new_nonce_list New nonce list which is being built
     */
    private function purgeNonceWord(string $key, array $word, int $stop_nonce, array &$new_nonce_list)
    {
        foreach ($word as $nonce) {
            if ($nonce['time'] >= $stop_nonce) {
                if (!array_key_exists($key, $new_nonce_list)) {
                    $new_nonce_list[$key] = array();
                }
                $new_nonce_list[$key][] = $nonce;
            }
        }
    }

    /**
     * Test if the session contains a value stored with the name $name
     * @param string $name Name of value stored
     * @return boolean Returns true if some value is stored under $name name.
     */
    public function hasVariable($name)
    {
        return array_key_exists($name, $_SESSION);
    }

    /**
     * Get one value stored in the session with $name name
     * @param string $name Name of stored value
     * @param mixed $default Default value if $name does not exists
     * @return mixed Value stored if exists or $default if not
     */
    public function getVariable($name, $default = false)
    {
        return (array_key_exists($name, $_SESSION)
               ? $_SESSION[$name]
               : $default);
    }

    /**
     * Stores a pair ($name, $value) in the session.
     * @param type $name Storage name of the value
     * @param type $value Value to store
     */
    public function setVariable($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * Remove a value from the Session storage.
     * @param string $name Storage name to remove
     * @return boolean Returns true if the value was removed.
     */
    public function unsetVariable($name)
    {
        $retval = false;

        if (array_key_exists($name, $_SESSION)) {
            unset($_SESSION[$name]);
            $retval = true;
        }

        return $retval;
    }

    /**
     * Refresh session cookies to update the expiration date and adjust to the operative parameters of $nb_site.
     * @param CNabuSite $nb_site Site used to get refresh criteria.
     * @throws ENabuCoreException Throws this exception if the Session Intervals in $nb_site are not valid.
     */
    public function refreshCookies($nb_site) {

        $nb_engine = CNabuEngine::getEngine();

        if (!($nb_site instanceof CNabuSite)) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_METHOD_PARAMETER_IS_EMPTY, '$nb_site');
        }

        if ($this->hasVariable('preserve_session') && $this->getVariable('preserve_session') === true) {
            $date_interval = $nb_site->getValue('nb_site_session_preserve_interval');
            $pre_interval = nb_unpackDateInterval($date_interval);
            if (is_array($pre_interval)) {
                $seconds = $pre_interval['seconds'];
            } else if ($pre_interval === false) {
                throw new ENabuCoreException(
                    ENabuCoreException::ERROR_INVALID_DATE_INTERVAL,
                    array('nb_site_session_timeout_interval', $date_interval)
                );
            }
        }

        if (!isset($seconds)) {
            $date_interval = $nb_site->getValue('nb_site_session_timeout_interval');
            $ses_interval = nb_unpackDateInterval($date_interval);
            if ($ses_interval !== null) {
                $seconds = $ses_interval['seconds'];
            } else if ($ses_interval === false) {
                throw new ENabuCoreException(
                    ENabuCoreException::ERROR_INVALID_DATE_INTERVAL,
                    array('nb_site_session_timeout_interval', $date_interval)
                );
            }
        }

        if (isset($seconds)) {
            $cookie_params = session_get_cookie_params();
            if (is_array($cookie_params)) {
                $cookie_params['lifetime'] = time() + $seconds;
                if ($seconds === 0) {
                    $nb_engine->traceLog("Session timeout", "session");
                } else {
                    $nb_engine->traceLog("Session timeout", $seconds);
                }
                setcookie(
                    session_name(),
                    session_id(),
                    $cookie_params['lifetime'],
                    $cookie_params['path'],
                    $cookie_params['domain'],
                    $cookie_params['secure'],
                    $cookie_params['httponly']
                );
            }
        }
    }

    /**
     * Check if the session has a cookie
     * @param string $name Cookie name to check
     * @return boolean Returns true if $name Cookie exists
     */
    public function hasCookie($name) {

        return (filter_has_var(INPUT_COOKIE, $name));
    }

    /**
     * Gets a Cookie contents
     * @param string $name Cookie name to get
     * @return string Returns the content of the cookie
     */
    public function getCookie($name) {

        return (filter_has_var(INPUT_COOKIE, $name) ? filter_input(INPUT_COOKIE, $name) : false);
    }

    /**
     * Sets or updates a Cookie
     * @param string $name Cookie name to be setted
     * @param string $value Content string of the Cookie.
     * @param int $expires Timestamp of Cookie expiration time.
     * @param string $path Path to apply the Cookie.
     * @param string $domain Domain of the Cookie.
     * @param boolean $secure True if the cookie is marked as secure.
     * @param boolean $httponly True if the cookie is applied only in HTTP mode.
     */
    public function setCookie($name, $value, $expires = null, $path = null, $domain = null, $secure = false, $httponly = false) {

        if ($expires !== null) {
            setcookie($name, $value, time() + $expires, $path, $domain, $secure, $httponly);
        } else {
            setcookie($name, $value, 0, $path, $domain, $secure, $httponly);
        }
    }
}
