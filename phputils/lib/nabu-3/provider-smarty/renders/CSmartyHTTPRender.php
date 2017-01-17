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

namespace providers\smarty\renders;

use \nabu\cache\interfaces\INabuCacheable;
use \nabu\cache\interfaces\INabuCacheStorage;
use \nabu\data\CNabuDataObject;
use \nabu\core\CNabuEngine;
use \nabu\data\CNabuDataObjectList;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\site\CNabuSite;
use \nabu\data\site\CNabuSiteTarget;
use \nabu\http\interfaces\INabuHTTPResponseRender;
use \nabu\http\renders\base\CNabuHTTPResponseRenderAdapter;
use \Smarty;
use nabu\data\CNabuDataObjectListIndex;
use nabu\http\app\base\CNabuHTTPApplication;

require_once "lib/smarty/Smarty.class.php";
require_once SMARTY_PROVIDER_PATH . '/plugins/compiler.nabu_exists.php';

/**
 * Class to dump HTML rendered with Smarty as HTTP response.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\http\renders
 */
class CSmartyHTTPRender extends CNabuHTTPResponseRenderAdapter {

    private $smarty = null;
    private $cache_storage = null;
    private $nb_smarty_manager = null;

    public function __construct(
        CNabuHTTPApplication $nb_application,
        INabuHTTPResponseRender $main_render = null,
        $params = false
    ) {
        parent::__construct($nb_application, $main_render);

        $this->nb_smarty_manager = $nb_application->getManager(SMARTY_MANAGER_KEY);

        $nb_server = $nb_application->getHTTPServer()->getServer();
        $nb_site = $nb_application->getHTTPServer()->getSite();

        if (is_array($params) && array_key_exists('basepath', $params)) {
            $base = $params['basepath'];
        } else {
            $base = $nb_server->getVirtualHostsPath() . $nb_site->getValue('nb_site_base_path');
        }
        if (realpath($base) && $nb_site->isSmartyAvailable()) {
            $this->smarty = new Smarty();
            /**
            * @todo Solve this strong path to be configurable via constant or similar
            */
            $this->smarty->addPluginsDir($this->nb_smarty_manager->getPluginsPath());
            $this->smarty->setTemplateDir(
                (is_array($params) && array_key_exists('templates', $params))
                ? $params['templates']
                : $base . $nb_site->getSmartyTemplatePath()
            );
            $this->smarty->setCompileDir(
                (is_array($params) && array_key_exists('compiles', $params))
                ? $params['compiles']
                : $base . $nb_site->getSmartyCompilePath()
            );
            $this->smarty->setConfigDir(
                (is_array($params) && array_key_exists('config', $params))
                ? $params['config']
                : $base . $nb_site->getSmartyConfigsPath()
            );
            $this->smarty->setCacheDir(
                (is_array($params) && array_key_exists('cache', $params))
                ? $params['cache']
                : $base.$nb_site->getSmartyCachePath()
            );
            $this->smarty->compile_check = true;
            $this->smarty->setDebugging(
                (is_array($params) && array_key_exists('debugging', $params))
                ? ($params['debugging'] === 'T')
                : ($nb_site->getSmartyDebugging() === 'T')
            );
            $this->smarty->setErrorReporting(
                (is_array($params) && array_key_exists('error_reporting', $params))
                ? $params['error_reporting']
                : $nb_site->getSmartyErrorReporting()
            );
            $this->smarty->setDebugTemplate($this->nb_smarty_manager->getTemplatesPath() . DIRECTORY_SEPARATOR . 'debug.tpl');
            $this->smarty->setCaching(false);
        } else {
            return false;
        }
    }

    public function setCacheStorage($cache_storage) {

        $this->cache_storage = $cache_storage;
    }

    public function isSmartyInitialized() {

        return $this->smarty != null;
    }

    /**
     * Expands a value to convert to Smarty associative arrays structure.
     * If $value is an array or a multilevel array and contains an object,
     * then converts each object in an associative array. If as result of convert
     * an object we have a new array, this array is iterated to convert child objects.
     * @param mixed $value Value to convert
     * @param mixed $nb_language Language to extract array adapted for a translation
     * @return boolean|array If success return null or an associative array. If fails returns false
     */
     /*
    public static function convertValue($value, $nb_language = null) {

        $converted = false;

        if ($value instanceof \cms\core\CCMSDataObject) {
            if ($nb_language === null) {
                $converted = self::anidateConversion($value->getTreeData());
            } else {
                $converted = self::anidateConversion($value->getTreeData($nb_language), $nb_language);
            }
        } else if (is_array($value)) {
            $converted = self::anidateConversion($value, $nb_language);
        } else if (is_object ($value)) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_OBJECT_NOT_EXPECTED,
                array(print_r($value, true))
            );
        } else {
            $converted = $value;
        }

        return $converted;
    }
    */

    /*
    private static function anidateConversion($array, $nb_language = null) {

        if ($array != null && is_array($array)) {
            $list = array();
            foreach ($array as $key=>$row) {
                if ($row instanceof \cms\core\CCMSDataObject) {
                    if ($nb_language == null) {
                        $list[$key] = self::anidateConversion($row->getTreeData());
                    } else {
                        $list[$key] = self::anidateConversion($row->getTreeData($nb_language), $nb_language);
                    }
                } else if (is_array($row)) {
                    $list[$key] = self::anidateConversion($row, $nb_language);
                } else if (is_object($row)) {
                    throw new ENabuCoreException(
                        ENabuCoreException::ERROR_OBJECT_NOT_EXPECTED,
                        array(print_r($row, true))
                    );
                } else {
                    $list[$key] = $row;
                }
            }
            return $list;
        } else if ($array instanceof CNabuDataObject) {
            if ($nb_language == null) {
                return self::anidateConversion($array->getTreeData());
            } else {
                return self::anidateConversion($array->getTreeData($nb_language), $nb_language);
            }
        }else if (is_object($array)) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_OBJECT_NOT_EXPECTED,
                array(print_r($array, true))
            );
        } else if ($array != null) {
            return array($array);
        }

        return null;
    }
    */

    /**
     * Assigns the $value to an $name smarty variable without convert their content
     * @param string $name Name of smarty variable
     * @param mixed $value Value to put into smarty variable
     * @return bool  If success return true, otherwise false
     */
    public function smartyBypassAssign($name, $value, $cache_key = false, $cache_update = false) {

        if ($this->isSmartyInitialized()) {

            $is_cacheable = ($this->cache_storage instanceof INabuCacheStorage && $cache_key);
            if ($is_cacheable) {
                $container = $this->cache_storage->getContainer($cache_key);
                if ($container !== null) {
                    $this->smarty->assign($name, $container->getData());
                    return true;
                } else {
                    $container = $this->cache_storage->createContainer($cache_key, $value);
                    $cache_update = true;
                }
                $final_value = $container->getData();
            } else {
                if (is_callable($value)) {
                    $final_value = $value();
                } else {
                    $final_value = $value;
                }
            }

            $this->smarty->assign($name, $final_value);

            if ($is_cacheable && $cache_update) {
                if (isset($container) && $container instanceof INabuCacheable) {
                    $container->setData($final_value);
                    $container->update();
                } else {
                    $container = $this->cache_storage->createContainer($cache_key, $final_value);
                    $container->update();
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Assigns the $value to an $name smarty variable
     * @param string $name Name of smarty variable
     * @param mixed $value Value to put into smarty variable
     * @param mixed $nb_language Language to extract smarty array
     * @return bool If success return true, otherwise false
     */
    public function smartyAssign($name, $value, $nb_language = null, $cache_key = false)
    {
        if ($this->isSmartyInitialized()) {
            $cache_update = false;
            if ($this->cache_storage instanceof INabuCacheStorage && $cache_key) {
                $container = $this->cache_storage->getContainer($cache_key);
                if ($container !== null) {
                    $this->smartyBypassAssign($name, $container->getData());
                    return true;
                } else {
                    $container = $this->cache_storage->createContainer($cache_key, $value);
                    $cache_update = true;
                }
                $final_value = $container->getData();
            } else {
                if (is_callable($value)) {
                    $final_value = $value();
                } else {
                    $final_value = $value;
                }
                $cache_key = false;
            }

            if ($final_value instanceof CNabuDataObject) {
                if ($nb_language ===  null) {
                    $this->smartyBypassAssign(
                        $name,
                        $this->anidateSmartyConversion($final_value->getTreeData()),
                        $cache_key,
                        $cache_update
                    );
                    return true;
                } else {
                    $this->smartyBypassAssign(
                        $name,
                        $this->anidateSmartyConversion($final_value->getTreeData($nb_language), $nb_language),
                        $cache_key,
                        $cache_update
                    );
                    return true;
                }
            } elseif ($final_value instanceof CNabuDataObjectList) {
                return $this->smartyAssign($name, $final_value->getItems(), $nb_language, $cache_key);
            } elseif ($final_value instanceof CNabuDataObjectListIndex) {
                return $this->smartyAssign($name, $final_value->getKeys(), $nb_language, $cache_key);
            } else if (is_array($final_value)) {
                $this->smartyBypassAssign(
                    $name,
                    $this->anidateSmartyConversion($final_value, $nb_language),
                    $cache_key,
                    $cache_update
                );
                return true;
            } else if (is_object($final_value)) {
                throw new ENabuCoreException(
                    ENabuCoreException::ERROR_OBJECT_NOT_EXPECTED,
                    array(print_r($final_value, true))
                );
            } else {
                $this->smartyBypassAssign($name, $final_value, $cache_key, $cache_update);
                return true;
            }
        } else {
            CNabuEngine::getEngine()->triggerError(
                "Unable to assign Smarty variable '$name' before initialization",
                E_USER_ERROR
            );
        }

        return false;
    }

    private function anidateSmartyConversion($array, $nb_language = null)
    {
        if ($array != null && is_array($array)) {
            $list = array();
            foreach ($array as $key=>$row) {
                if ($row instanceof CNabuDataObject) {
                    if ($nb_language == null) {
                        $list[$key] = $this->anidateSmartyConversion($row->getTreeData());
                    } else {
                        $list[$key] = $this->anidateSmartyConversion($row->getTreeData($nb_language), $nb_language);
                    }
                } elseif ($row instanceof CNabuDataObjectList) {
                    $list[$key] = $this->anidateSmartyConversion($row->getItems(), $nb_language);
                } elseif ($row instanceof CNabuDataObjectListIndex) {
                    $list[$key] = $row->getKeys();
                } else if (is_array($row)) {
                    $list[$key] = $this->anidateSmartyConversion($row, $nb_language);
                } else if (is_object($row)) {
                    throw new ENabuCoreException(
                        ENabuCoreException::ERROR_OBJECT_NOT_EXPECTED,
                        array(print_r(get_class($row), true))
                    );
                } else {
                    $list[$key] = $row;
                }
            }
            return $list;
        } else if ($array instanceof CNabuDataObject) {
            if ($nb_language == null) {
                return $this->anidateSmartyConversion($array->getTreeData());
            } else {
                return $this->anidateSmartyConversion($array->getTreeData($nb_language), $nb_language);
            }
        } else if (is_object($array)) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_OBJECT_NOT_EXPECTED,
                array(print_r($array, true))
            );
        } else if ($array != null) {
            return array($array);
        }

        return null;
    }

    public function renderTemplate($filename) {

        return $this->smarty->fetch($filename);
    }

    /**
     * Execute and display the $display_file. Optionally the $content_file represents
     * an internal part to prepare before execute the display
     * @param string $display_file Full file name of smarty template to display
     * @param string $content_file Full file name of smarty template to fetch before display
     * @return bool Returns true if smarty display is executed and otherwise false
     */
    public function display($display_file, $content_file = null, $output_type = 'HTML', $filename = null) {

        if ($this->isSmartyInitialized()) {
            if ($content_file !== null) {
                $content = $this->smarty->fetch('content/'.$content_file);
                $this->smarty->assign('content', $content);
            }
            switch ($output_type) {
                case 'HTML':
                    {
                        $this->smarty->display('display/'.$display_file);
                        break;
                    }
                /*
                case 'PDF':
                    {
                        $page = $this->smarty->fetch('display/'.$display_file);
                        $pdf = new \providers\html2pdf\CCMSPDFBuilder('A4', 'P', 'en');
                        $pdf->writeHTML($page);
                        if (strlen($filename) < 1) $filename = 'document.pdf';
                        $pdf->buildPDF2Stream($filename);
                        break;
                    }
                case 'XLS':
                    {
                        $page = $this->smarty->fetch('display/'.$display_file);
                        $excel = new CCMSExcelBuilder();
                        if ($excel->createFromExcel2003XMLStream($page)) {
                            $stream = $excel->exportToExcel5Stream();
                            if ($stream) echo $stream;
                        }
                        break;
                    }
                case 'XLSX':
                    {
                        $page = $this->smarty->fetch('display/'.$display_file);
                        $excel = new CCMSExcelBuilder();
                        if ($excel->createFromExcel2003XMLStream($page)) {
                            $stream = $excel->exportToExcel2007Stream();
                            if ($stream) echo $stream;
                        }
                        break;
                    }
                */
            }

            return true;
        }

        return false;
    }

    public function render()
    {
        global $NABU_HTTP_CODES;

        $use_smarty = false;

        $nb_engine = CNabuEngine::getEngine();
        $nb_request = $this->nb_application->getRequest();
        $nb_response = $this->nb_application->getResponse();
        $nb_site = $nb_request->getSite();
        $cache_storage = $nb_site->getCacheStorage();

        $nb_site_target = $nb_request->getSiteTarget();
        $nb_language = $nb_request->getLanguage();
        if ($nb_language === null) {
            $nb_language = $nb_site->getDefaultLanguage();
        }
        $nb_language_id = $nb_language->getId();
        $nb_user = $this->nb_application->getUser();
        $user_logged = ($nb_user !== null);
        $nb_role = $this->nb_application->getRole();
        $nb_user_role = $this->nb_application->getSecurityManager()->getSiteUser();
        $nb_commerce = $nb_site->getCommerce();

        $this->setCacheStorage($cache_storage);

        if ($nb_site->isSmartyAvailable()) {
            $display = $nb_site_target->getSmartyDisplayFile();
            $content = $nb_site_target->getSmartyContentFile();
            if ($display !== null && $display != -1 && strlen($display) > 0) {
                $nb_work_customer = $this->nb_application->getSecurityManager()->getWorkCustomer();
                if (($has_wcust = ($nb_work_customer !== null))) {
                    $nb_engine->traceLog("Has work customer", $nb_work_customer->getId());
                }
                /*
                $cache_dir = $nb_site->getCacheDirectory();
                $sm_site = $nb_site->loadFromCache($nb_role, $user_logged, $nb_language_id, $has_wcust);
                if ($sm_site === false) {
                    $sm_site = $nb_site->recreateCacheFile($cache_dir, $nb_role, $user_logged, $nb_language_id, $has_wcust);
                }

                $sm_site_target = $nb_site_target->loadFromCache($nb_role, $user_logged, $nb_language_id, $has_wcust);
                if ($sm_site_target['menu'] == false) {
                    $sm_site_target = $nb_site_target->recreateCacheFile($cache_dir, $nb_role, $user_logged, $nb_language_id, $has_wcust);
                }
                 */

                if ($nb_site_target->getSmartyDebugging() === 'T') {
                    $this->setSmartyDebug(true);
                }

                $this->smartyAssign('NABU_VERSION', NABU_VERSION);
                $this->smartyAssign('NABU_LICENSE_TITLE', NABU_LICENSE_TITLE);
                $this->smartyAssign('NABU_LICENSE_TARGET', NABU_LICENSE_TARGET);
                $this->smartyAssign('NABU_LICENSED', NABU_LICENSED);
                $this->smartyAssign('NABU_LICENSEE_TARGET', NABU_LICENSEE_TARGET);
                $this->smartyAssign('NABU_OWNER', NABU_OWNER);
                $this->smartyAssign('nb_customer', $nb_engine->getCustomer(), $nb_language);
                $this->smartyAssign('nb_work_customer', $nb_work_customer, $nb_language);
                $this->smartyAssign('nb_server', $this->nb_application->getHTTPServer()->getServer());
                //if (isset($nb_clients)) $nb_site->smartyAssign('nb_clients', $nb_clients);
                $this->smartyAssign('nb_response', array(
                    'http_response_code' => $nb_response->getHTTPResponseCode(),
                    'http_response_message' => $NABU_HTTP_CODES[$nb_response->getHTTPResponseCode()]
                ));
                $this->smartyAssign(
                        'nb_role', $nb_role, $nb_language,
                        $cache_storage->createContainerId(
                            'system', 'roles', $nb_role->getValue('nb_role_id'),
                            array('l' => $nb_language_id)
                        )
                );

                $this->smartyAssign('nb_user', $nb_user, $nb_language);
                /*
                if ($nb_user !== null) {
                    $render->smartyAssign(
                            'nb_user',
                            function() use ($nb_user) { return $nb_user; },
                            $nb_language,
                            $cache_storage->createContainerId('system', 'users', $nb_user->getValue('nb_user_id'), array('l' => $nb_language_id))
                    );
                } else {
                    $render->smartyAssign('nb_user', null);
                }
                 */

                $this->smartyAssign('nb_user_role', $nb_user_role);

                if ($nb_commerce !== null) {
                    $this->smartyAssign(
                        'nb_commerce', function() use($nb_commerce) {
                            $nb_commerce->getProductCategories();
                            return $nb_commerce;
                        },
                        $nb_language,
                        $cache_storage->createContainerId(
                            'commerces', 'commerce', $nb_commerce->getId(),
                            array(
                                'ul' => $user_logged,
                                'l' => $nb_language_id
                            )
                        )
                    );
                } else {
                    $this->smartyAssign('nb_commerce', null);
                }

                $this->smartyAssign(
                    'nb_site', $nb_site, $nb_language,
                    $cache_storage->createContainerId(
                        'sites', 'site', $nb_site->getValue('nb_site_id'),
                        array (
                            'r' => $nb_role->getValue('nb_role_id'),
                            'ul' => $user_logged,
                            'l' => $nb_language_id,
                            'hc' => $has_wcust
                        )
                    )
                );

                $this->smartyAssign('nb_site_alias', $nb_request->getSiteAlias());
                $this->smartyAssign('nb_site_alias_role', $nb_request->getSiteAliasRole());
                $this->smartyAssign('nb_language', $nb_language);
                $this->smartyAssign(
                    'nb_site_target', $nb_site_target, $nb_language,
                    $cache_storage->createContainerId(
                        'sites', 'target', $nb_site_target->getId(),
                        array(
                            'r' => $nb_role->getValue('nb_role_id'),
                            'ul' => $user_logged,
                            'l' => $nb_language_id
                        )
                    )
                );

                $this->smartyAssign(
                    'nb_site_map', $site_maps = $nb_site->getSiteMaps(), $nb_language,
                    $cache_storage->createContainerId(
                        'sites', 'sitemap', $nb_site_target->getValue('nb_site_target_id'),
                        array(
                            'r' => $nb_role->getValue('nb_role_id'),
                            'ul' => $user_logged,
                            'l' => $nb_language_id,
                            'hc' => $has_wcust
                        )
                    )
                );

                $this->smartyAssign(
                    'nb_mediotecas',
                    function() {
                        $nb_mediotecas_manager = $this->nb_application->getMediotecasManager();
                        $nb_mediotecas_manager->indexAll();
                        return array(
                            'mediotecas' => $nb_mediotecas_manager->getMediotecas(),
                            'keys' => $nb_mediotecas_manager->getKeysIndex()
                        );
                    },
                    $nb_language,
                    $cache_storage->createContainerId(
                        'mediotecas', 'list', $nb_site->getValue('nb_site_id'),
                        array(
                            'l' => $nb_language_id
                        )
                    )
                );

                $this->smartyAssign(
                    'nb_site_static_content', $nb_site->getStaticContents(), $nb_language,
                    $cache_storage->createContainerId('sites', 'static_content',
                        $nb_site->getValue('nb_site_id'),
                        array('l' => $nb_language_id)
                    )
                );

                $use_smarty = true;
            }
        }

        if ($use_smarty) {
            /** @todo Pending to validate in a near future */
            /*
            $this->nb_modules_manager->render();
            $render->smartyAssign('nb_apps',
                    array (
                        'morphs' => $this->getAppsMorphs(),
                        'slots' => $this->getAppsSlots(),
                        'widgets' => $this->getAppsWidgets(),
                        'scripts' => $this->getAppsScripts(),
                        'styles' => $this->getAppsStyles(),
                        'i18n' => $this->getAppsi18n()
                    ),
                    $nb_language
            );
            */
            $this->display($display, $content);
        }
    }

    public function setSmartyDebug($status)
    {
        $this->smarty->debugging = $status;
    }

    public function preparePagesURL($nb_site, $pages, $lang, $always_full = false, $params = null)
    {
        if (!($nb_site instanceof CNabuSite)) {
            throw new ENabuCoreException(
                ENabuCoreException::ERROR_METHOD_PARAMETER_NOT_VALID,
                array('$nb_site', $nb_site)
            );
        }

        if (count($pages) > 0) {
            if (is_array($pages)) {
                $list = $nb_site->implodeStringArray($pages, ', ');
                $site_target_list = CNabuSiteTarget::buildObjectListFromSQL(
                        'nb_site_target_id',
                        "select * "
                        . "from nb_site_target "
                       . "where nb_site_target_key in ($list) and nb_site_id=%site_id\$d",
                        array(
                            'site_id' => $nb_site->getValue('nb_site_id')
                        )
                );
            } else {
                $site_target_list = CNabuSiteTarget::buildObjectListFromSQL(
                        'nb_site_target_id',
                        "select * "
                        . "from nb_site_target "
                       . "where nb_site_target_key='%pages\$s' and nb_site_id=%site_id\$d",
                        array(
                            'pages' => $pages,
                            'site_id' => $nb_site->getValue('nb_site_id')
                        )
                );
            }

            if (count($site_target_list) > 0) {

                $nb_engine = CNabuEngine::getEngine();
                $nb_modules_manager = $nb_engine->getAppsManager();
                foreach ($site_target_list as $page) {
                    $key = str_replace(array(' ', '-'), array('_', '_'), $page->getValue('nb_site_target_key'));
                    $name = 'form_'.$key.'_url';
                    $url = $page->getFullyCualifiedDefaultURL($nb_site, $lang, $always_full, $params);
                    $this->smartyAssign($name, $url);
                    if ($this->is_main_render && $nb_modules_manager !== null) {
                        $nb_modules_manager->smartyAssign($name, $url);
                    }
                }
            }
        }
    }
}
