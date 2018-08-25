<?php

/*  Copyright 2009-2011 Rafael Gutierrez Martinez
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

use \nabu\data\CNabuDataObject;
use \nabu\core\CNabuObject;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\core\utils\CNabuURL;
use \nabu\data\site\CNabuSiteTarget;
use \nabu\http\CNabuHTTPRequest;
use nabu\http\exceptions\ENabuHTTPException;
use \nabu\http\exceptions\ENabuRedirectionException;
use \nabu\http\interfaces\INabuHTTPResponseRender;
use \nabu\http\managers\CNabuHTTPPluginsManager;
use nabu\render\CNabuRenderFactory;
use nabu\render\CNabuRenderTransformFactory;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\http
 */
final class CNabuHTTPResponse extends CNabuObject
{
    /**
     * Plugins Manager instance
     * @var CNabuHTTPPluginsManager
     */
    private $nb_plugins_manager = null;
    /**
     * Contains the instance of request object
     * @var CNabuHTTPRequest
     */
    private $nb_request;
    /**
     * HTTP Response Code
     * @var int
     */
    private $http_response_code = 0;
    /**
     * Associative array with param values for plugins
     * @var array
     */
    private $params = null;
    /**
     * Mimetype for the response. By default contains the mimetype defined in the site target
     * that renders this page. Can be changed in any moment before call method
     * {@see CNabuHTTPApplication::prepareHeaders()}
     * @var string
     */
    private $mimetype = null;
    /**
     * Render subsystem to build the Response
     * @var INabuHTTPResponseRender
     */
    private $render = null;
    /**
     * Render Factory to render the Response
     * @var CNabuRenderFactory
     */
    private $render_factory = null;
    /**
     * Render Transform Factory to transform the Response
     * @var CNabuRenderTransformFactory
     */
    private $transform_factory = null;
    /**
     * Headers list
     * @var array
     */
    private $header_list = null;
    /**
     * Flag to determine if response uses CORS protocol
     * @var bool
     */
    private $use_cors = false;
    /**
     * URL allowed for CORS Origin
     * @var string
     */
    private $cors_allow_origin = null;
    /**
     * Flag to determine if CORS requires credentials
     * @var bool
     */
    private $cors_with_credentials = false;
    /**
     * Attachment filename
     * @var string
     */
    private $attachment_filename = null;

    /**
     * Default constructor.
     * @param CNabuHTTPPluginsManager $nb_plugins_manager nabu-3 HTTP Plugins Manager.
     * @param CNabuHTTPRequest|null $nb_request nabu-3 HTTP Request.
     * @throws ENabuCoreException Throws an exception if Plugins Manager is invalid.
     */
    public function __construct($nb_plugins_manager, CNabuHTTPRequest $nb_request = null)
    {
        parent::__construct();

        if ($nb_plugins_manager === null || !($nb_plugins_manager instanceof CNabuHTTPPluginsManager)) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_PLUGIN_MANAGER_REQUIRED);
        }
        $this->nb_plugins_manager = $nb_plugins_manager;
        $this->nb_request = $nb_request;

        $this->init();
    }

    private function init()
    {
        $this->http_response_code = 0;
        $this->params = null;
    }

    /**
     * Get the current HTTP Response Code pending request handling
     * @return int HTTP Error Code
     */
    public function getHTTPResponseCode()
    {
        return $this->http_response_code;
    }

    /**
     * Set current HTTP Response Code
     * @param int $http_response_code
     */
    public function setHTTPResponseCode($http_response_code)
    {
        $this->http_response_code = $http_response_code;
    }

    /**
     * Get the mimetype for this response
     * @return string Returns the mimetype
     */
    public function getMIMEType()
    {
        return $this->mimetype;
    }

    /**
     * Set the mimetype for this response. Calls of this method after call
     * to {@see CNabuHTTPApplication::prepareHeaders()} has no effect.
     * @param string $mimetype New mimetype
     */
    public function setMIMEType($mimetype)
    {
        $this->mimetype = $mimetype;
    }

    public function getHeader($header)
    {
        if ($this->header_list !== null && array_key_exists($header, $this->header_list)) {
            return $this->header_list[$header];
        }

        return null;
    }

    public function setHeader($header, $content)
    {
        if ($this->header_list === null) {
            $this->header_list = array($header => $content);
        } else {
            $this->header_list[$header] = $content;
        }
    }

    /**
     * Get current Attachment File Name setted.
     * @return string|null Returns current File Name if setted or null if none.
     */
    public function getAttachmentFilename()
    {
        return $this->attachment_filename;
    }

    /**
     * Set the Attachment File Name.
     * @param string|null $filename File Name to be setted or null to remove.
     * @return CNabuHTTPResponse Returns the self pointer to grant cascade setter calls.
     */
    public function setAttachmentFilename(string $filename = null) : CNabuHTTPResponse
    {
        $this->attachment_filename = $filename;

        return $this;
    }

    /**
     * Calculates the X-Frame-Options value if setted.
     * @return string|null If X-Frame-Options is setted returns the proper value,
     * else if not is setted then returns null.
     * @throws ENabuHTTPException Raises an exception if kind is ALLOW-FROM and none URL is setted.
     */
    private function calculateFrameOptions()
    {
        $retval = null;

        $nb_site = $this->nb_request->getSite();

        switch ($nb_site->getXFrameOptions()) {
            case 'D':
                $retval = 'DENY';
                break;
            case 'S':
                $retval = 'SAMEORIGIN';
                break;
            case 'A':
                if (is_string($url = $nb_site->getXFrameOptionsURL()) &&
                    strlen($url = preg_replace(array('/^\s*/', '/\s*$/'), '', $url)) > 0) {
                    $retval = 'ALLOW-FROM ' . $url;
                } else {
                    throw new ENabuHTTPException(ENabuHTTPException::ERROR_X_FRAME_OPTIONS_URL_NOT_FOUND);
                }
        }

        return $retval;
    }

    // Headers index at http://www.iana.org/assignments/message-headers/message-headers.xhtml
    /**
     * Build HTTP Headers.
     */
    public function buildHeaders()
    {
        if ($this->use_cors) {
            if (strlen($this->cors_allow_origin) > 0) {
                header("Access-Control-Allow-Origin: $this->cors_allow_origin");
            }
            if ($this->cors_with_credentials) {
                header('Access-Control-Allow-Credentials: true');
            } else {
                header('Access-Control-Allow-Credentials: false');
            }
        }

        // Cache Control RFC: https://tools.ietf.org/html/rfc7234
        if ($this->nb_request instanceof CNabuHTTPRequest &&
            ($nb_site_target = $this->nb_request->getSiteTarget()) instanceof CNabuSiteTarget
        ) {
            if (($max_age = $nb_site_target->getDynamicCacheEffectiveMaxAge()) !== false) {
                $expire_date = gmdate("D, d M Y H:i:s", time() + $max_age);
                $this->setHeader('Expires', $expire_date . 'GMT');
                $this->setHeader('Cache-Control', "max-age=$max_age");
                $this->setHeader('User-Cache-Control', "max-age=$max_age");
                $this->setHeader('Pragma', 'cache');
            } else {
                $this->setHeader('Expires', 'Thu, 1 Jan 1981 00:00:00 GMT');
                $this->setheader('Cache-Control', 'no-store, no-cache, must-revalidate');
                $this->setHeader('Pragma', 'no-cache');
            }
            if ($nb_site_target->getAttachment() === 'T') {
                if (is_string($this->attachment_filename) && strlen($this->attachment_filename) > 0) {
                    $this->setHeader('Content-Disposition', 'attachment; filename=' . $this->attachment_filename);
                } else {
                    $this->setHeader('Content-Disposition', 'attachment');
                }
            }
        }

        if (($frame_options = $this->calculateFrameOptions()) !== null) {
            $this->setHeader('X-Frame-Options', $frame_options);
        }

        if (count($this->header_list) > 0) {
            foreach ($this->header_list as $name => $value) {
                header("$name: $value");
            }
        }
    }

    /**
     * Get the current Render for this response.
     * @return INabuHTTPResponseRender Returns tge Render instance.
     */
    public function getRender()
    {
        if ($this->render_factory !== null) {
            $retval = $this->render_factory->getInterface();
        } else {
            $retval = $this->render;
        }

        return $retval;
    }

    /**
     * Set the Render for this response. Calls of this method after call
     * to {@see CCMSEngine::buildResponse()} has no effect.
     * @param INabuHTTPResponseRender|null $render New Render instance.
     * @throws ENabuCoreException
     */
    public function setRender(INabuHTTPResponseRender $render = null)
    {
        if ($render instanceof INabuHTTPResponseRender) {
            $this->render = $render;
        } elseif ($render === null) {
            $this->render = null;
        } else {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_METHOD_PARAMETER_IS_EMPTY,
                array('setRender', '$render')
            );
        }
    }

    /**
     * Get the current Render Factory for this response.
     * @return CNabuRenderFactory Returns the Render instance.
     */
    public function getRenderFactory()
    {
        return $this->render_factory;
    }

    /**
     * Set the Render Factory for this response. Calls of this method after call
     * to {@see CNabuEngine::buildResponse()} has no effect.
     * @param CNabuRenderFactory|null $factory New Render Factory.
     */
    public function setRenderFactory(CNabuRenderFactory $factory = null)
    {
        $this->render_factory = $factory;
    }

    /**
     * Get the current Render Transform Factory for this response.
     * @return CNabuRenderTransformFactory Returns the Render Transform instance.
     */
    public function getTransformFactory()
    {
        return $this->transform_factory;
    }

    /**
     * Set the Render Transform Factory for this response. Calls of this method after call
     * to {@see CNabuEngine::buildResponse()} has no effect.
     * @param CNabuRenderTransformFactory|null $factory New Render Transform Factory.
     */
    public function setTransformFactory(CNabuRenderTransformFactory $factory = null)
    {
        $this->transform_factory = $factory;
    }

    public function movedPermanentlyRedirect($target, $nb_language = null, $params = null)
    {
        $this->redirect(301, $target, $nb_language, $params);
    }

    public function temporaryRedirect($target, $nb_language = null, $params = null)
    {
        $this->redirect(307, $target, $nb_language, $params);
    }

    public function internalError()
    {
        $this->redirect(500, $this->nb_request->getArticle());
    }

    /**
     * This function makes the redirection to another page. Parameters permits a wide range of options.
     * @param int $code result code for HTTP protocol message
     * @param mixed $nb_site_target target to redirect. It can be a string with a URL or a Site Target key,
     * an integer with a Site Target ID or a CNabuDataObject containing a field named nb_site_target_id
     * @param mixed $nb_language language to use in redirection. It can be a Language ID,
     * a CNabuLanguage object or a CNabuDataObject containing a field named nb_language_id
     * @param array $params aditional params for URL as an associative array
     * @return bool If redirection is not allowed, then returns false.
     * In another case, the function makes exit internally and breaks the program flow.
     */
    public function redirect($code, $nb_site_target, $nb_language = null, $params = null)
    {
        global $NABU_HTTP_CODES;

        if (is_string($nb_site_target)) {
            $url = new CNabuURL($nb_site_target);
            if (!$url->isValid()) {
                $nb_site_target = CNabuSiteTarget::findByKey($this, $nb_site_target);
                unset($url);
            }
        } elseif (is_numeric($nb_site_target)) {
            $nb_site_target = new CNabuSiteTarget($nb_site_target);
            if ($nb_site_target->isNew()) {
                $nb_site_target = null;
            }
        } elseif ($nb_site_target instanceof CNabuDataObject) {
            if (!($nb_site_target instanceof CNabuSiteTarget)) {
                $nb_site_target = new CNabuSiteTarget($nb_site_target);
                if ($nb_site_target->isNew()) {
                    $nb_site_target = null;
                }
            }
        } else {
            $nb_site_target = null;
        }

        if ($nb_site_target != null) {
            $encoded = '';
            if ($params != null && count($params) > 0) {
                foreach ($params as $field => $value) {
                    $encoded .= (strlen($encoded) > 0 ? "&" : "").urlencode($field)."=".urlencode($value);
                }
                $encoded = '?'.$encoded;
            }

            $nb_language_id = nb_getMixedValue($nb_language, 'nb_language_id');
            $nb_site = $this->nb_request->getSite();
            if ($nb_language_id == null) {
                $nb_language_id = $nb_site->getDefaultLanguageId();
            }

            $url = ($nb_site_target instanceof CNabuSiteTarget
                   ? $nb_site_target->getFullyQualifiedURL($nb_language_id) . $encoded
                   : $nb_site_target);
            throw new ENabuRedirectionException($code, $url);
        } elseif (isset($url)) {
            throw new ENabuRedirectionException($code, $url->getURL());
        } else {
            $this->http_response_code = 500;
            throw new ENabuCoreException(ENabuCoreException::ERROR_REDIRECTION_TARGET_NOT_VALID);
        }
    }
}
