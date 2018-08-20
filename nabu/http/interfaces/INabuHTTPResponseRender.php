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

namespace nabu\http\interfaces;
use nabu\http\CNabuHTTPRequest;
use nabu\http\CNabuHTTPResponse;

/**
 * Interface to implement Response Renders.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.12 Surface
 * @package \nabu\http\interfaces
 */
interface INabuHTTPResponseRender
{
    /**
    * Sets the Request instance.
    * @param CNabuHTTPRequest $nb_request The instance to be setted.
    * @return INabuHTTPResponseRender Retuns the self instance to grant chained setters functionality.
    */
    public function setRequest(CNabuHTTPRequest $nb_request) : INabuHTTPResponseRender;
    /**
    * Sets the Response instance.
    * @param CNabuHTTPResponse $nb_response The instance to be setted.
    * @return INabuHTTPResponseRender Retuns the self instance to grant chained setters functionality.
    */
    public function setResponse(CNabuHTTPResponse $nb_response) : INabuHTTPResponseRender;
    /**
     * Gets the Source File Name assigned to this render if one.
     * @return string|null Returns the filename if exists or null if not.
     */
    public function getSourceFile();
    /**
     * Sets the Source File Name to work with this render.
     * @param string $filename Source File Name to be assigned.
     * @return INabuHTTPResponseRender Returns the self instance to grant chained setters functionality.
     */
    public function setSourceFile(string $filename) : INabuHTTPResponseRender;
    /**
     * Declares that the Source File associated to this render will be unlinked after finish to render it.
     * @param bool $unlink If true, the Source File will be unlinked.
     */
    public function unlinkSourceFileAfterRender(bool $unlink = true);
    /**
     * Gets a variable value from the render instance.
     * @param string $name Name of the value to get.
     * @return mixed Returns the value represented by $name.
     */
    public function getValue(string $name);
    /**
     * Sets a variable value to the render instance.
     * @param string $name Name of the value to get.
     * @param mixed $value Value to be setted.
     * @param mixed $nb_language Language to apply to $value if possible.
     * @param mixed $cache_key Cache key for the value if it can be cacheable or false if not.
     * @return INabuHTTPResponseRender Returns the self instance to grant chained setters functionality.
     */
    public function setValue(string $name, $value, $nb_language = null, $cache_key = false) : INabuHTTPResponseRender;
    /**
     * Check if a value name exists in the render.
     * @param string $name Name to be checked.
     * @return bool Returns true if $name is registered.
     */
    public function hasValue(string $name) : bool;
    /**
     * Check if this render is the main render.
     * @return bool Returns true if is the main render.
     */
    public function isMainRender() : bool;
    /**
     * Renders the content and put the result in the HTTP output stream.
     */
    public function render();
}
