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

namespace nabu\http\app;
use Throwable;
use Exception;
use nabu\core\CNabuEngine;
use nabu\core\exceptions\ENabuCoreException;
use nabu\http\app\base\CNabuHTTPApplication;

/**
 * Abstract class to implement child classes to manage HTTP Server Applications.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package name
 */
abstract class CNabuHTTPHostedApplication extends CNabuHTTPApplication
{
    /**
     * Launch the application represented in an inherited class of this class.
     * @return mixed Returns the value returned internally by the run method, or
     * false if prepareEnvironment fails.
     */
    final public static function launch()
    {
        //try {
            $nb_engine = CNabuEngine::getEngine();

            $class_name = get_called_class();
            $instance = new $class_name();
            if (!($instance instanceof CNabuHTTPHostedApplication)) {
                throw new ENabuCoreException(ENabuCoreException::ERROR_OPERATION_MODE_NOT_ALLOWED);
            }

            $instance->init();

            $retval = $instance->prepareEnvironment();

            /*
            $nb_engine->getHTTPServer()
                      ->getSite()
                          ->indexURLs()
                          ->indexSiteMaps()
                          ->indexStaticContents()
            ;*/

            if ($retval) {
                $retval = $instance->run();
            }

            $nb_engine->removeApplication();
        /*} catch (Throwable $re) {
            if (isset($instance)) {
                nb_displayErrorPage(
                    ($instance->getResponse() !== null ? $instance->getResponse()->getHTTPResponseCode() : 500),
                    $re
                );
            }
            throw $re;
        } catch (Exception $re) {
            if (isset($instance)) {
                nb_displayErrorPage(
                    ($instance->getResponse() !== null ? $instance->getResponse()->getHTTPResponseCode() : 500),
                    $re
                );
            }
            throw $re;
        }*/

        return $retval;
    }
}
