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

namespace nabu\http\renders\base;
use nabu\core\CNabuObject;
use nabu\http\CNabuHTTPRequest;
use nabu\http\CNabuHTTPResponse;
use nabu\http\app\base\CNabuHTTPApplication;
use nabu\http\interfaces\INabuHTTPResponseRender;
use nabu\http\renders\base\CNabuHTTPResponseRenderData;

/**
 * Abstract base class to implement HTTP based renders.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.12 Surface
 * @package \nabu\http\renders\base
 */
abstract class CNabuHTTPResponseRenderAdapter extends CNabuObject implements INabuHTTPResponseRender
{
    /** @var CNabuHTTPApplication $nb_application Running application instance that owns this render. */
    protected $nb_application = null;
    /** @var CNabuHTTPRequest $nb_request Request instance. */
    protected $nb_request = null;
    /** @var CNabuHTTPResponse $cms_response Response instance. */
    protected $cms_response = null;
    /** @var string $source_filename Filename to be rended. */
    protected $source_filename = false;
    /** @var string $params Optional params for the render instance. */
    protected $params = null;
    /** @var bool $is_main_render True if this instance is the main render. */
    protected $is_main_render = false;
    /** @var CNabuHTTPResponseRenderData $nb_render_data Render data storage. */
    protected $nb_render_data = null;
    /** @var bool unlink_source_file_after_render If true, $source_filename will be unlinked after render it. */
    protected $unlink_source_file_after_render = true;

    /**
     * Constructor.
     * @param CNabuHTTPApplication $nb_application HTTP Application instance to run this render.
     * @param INabuHTTPResponseRender|null $main_render HTTP Main render related with this render or null if not needed.
     */
    public function __construct(CNabuHTTPApplication $nb_application, INabuHTTPResponseRender $main_render = null)
    {
        parent::__construct();

        $this->nb_render_data = new CNabuHTTPResponseRenderData();

        $this->nb_application = $nb_application;
        $this->is_main_render = ($main_render !== null);
    }

    public function setRequest(CNabuHTTPRequest $nb_request) : INabuHTTPResponseRender
    {
        $this->nb_request = $nb_request;

        return $this;
    }

    public function setResponse(CNabuHTTPResponse $nb_response) : INabuHTTPResponseRender
    {
        $this->cms_response = $nb_response;

        return $this;
    }

    public function getSourceFile() : string
    {
        return $this->source_filename;
    }

    public function setSourceFile(string $filename) : INabuHTTPResponseRender
    {
        $this->source_filename = $filename;

        return $this;
    }

    public function unlinkSourceFileAfterRender(bool $unlink = true)
    {
        $this->unlink_source_file_after_render = $unlink;
    }

    public function getValue(string $name)
    {
        return $this->nb_render_data->getValue($name);
    }

    public function setValue(string $name, $value, $nb_language = null, $cache_key = false) : INabuHTTPResponseRender
    {
        $this->nb_render_data->setValue($name, $value);

        return $this;
    }

    public function hasValue(string $name): bool
    {
        return $this->nb_render_data->hasValue($name);
    }

    public function isMainRender() : bool
    {
        return $this->is_main_render;
    }

    /**
     * Basic functionality to dump a file to the output stream.
     * @param string $filename File Name of file to be dumped.
     * @return bool Returns true if $filename exits and is a valid file and is dumped.
     */
    protected function dumpFile($filename) {

        if (strlen($filename) > 0 && file_exists($filename) && is_file($filename)) {
            echo file_get_contents($filename);
            return true;
        }

        return false;
    }
}
