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

namespace nabu\http\renders\base;

use \nabu\data\CNabuDataObject;
use \nabu\http\interfaces\INabuHTTPResponseRender;
use nabu\http\app\base\CNabuHTTPApplication;

/**
 * Abstract base class to implement HTTP based renders.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\http\renders\base
 */
abstract class CNabuHTTPResponseRenderAdapter extends CNabuDataObject implements INabuHTTPResponseRender
{
    /**
     * Running application instance that owns this render.
     * @var CNabuHTTPApplication
     */
    protected $nb_application = null;

    protected $cms_request = null;
    protected $cms_response = null;

    protected $source_filename = false;
    protected $params = null;
    protected $is_main_render = false;

    public function __construct(CNabuHTTPApplication $nb_application, INabuHTTPResponseRender $main_render = null) {

        parent::__construct();

        $this->nb_application = $nb_application;
        $this->is_main_render = ($main_render !== null);
    }

    public function setRequest($nb_request) {

        $this->cms_request = $nb_request;
    }

    public function setResponse($nb_response) {

        $this->cms_response = $nb_response;
    }

    public function getSourceFile() {

        return $this->source_filename;
    }

    public function setSourceFile($filename) {

        $this->source_filename = $filename;
    }

    public function isMainRender() {

        return $this->is_main_render;
    }

    protected function dumpFile($filename) {

        if (strlen($filename) > 0 && file_exists($filename)) {
            echo file_get_contents($filename);
            return true;
        }

        return false;
    }
}
