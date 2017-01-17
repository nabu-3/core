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

namespace providers\smarty;
use Smarty;
use nabu\core\exceptions\ENabuCoreException;
use nabu\data\CNabuDataObject;
use nabu\http\managers\CNabuHTTPRenderDescriptor;

/**
 * Class to manage a Smarty model to speed up the creation of templates based on layout and style standards like
 * Bootstrap.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \providers\smarty
 */
class CSmartyModel extends CNabuDataObject
{
    /**
     * Main path for model fragment templates
     * @var string
     */
    private $model_main_path = null;
    /**
     * Array list of model fragment templates
     * @var array
     */
    private $model_location_array = array();
    /**
     * Model fragments collection
     * @var CSmartyModelFragmentList
     */
    private $model_fragment_list = null;

    public function __construct()
    {
        parent::__construct();

        $this->model_fragment_list = new CSmartyModelFragmentList();
    }

    /**
     * Gets the Model key attribute value
     * @return string Returns the key attribute value
     */
    public function getKey()
    {
        return $this->getValue('nb_smarty_model_key');
    }

    /**
     * Sets the Descriptor key attribute value
     * @param int $key New value for attribute
     * @return CNabuHTTPRenderDescriptor Returns $this
     */
    public function setKey($key)
    {
        if ($key === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$key")
            );
        }
        $this->setValue('nb_smarty_model_key', $key);

        return $this;
    }

    /**
     * Gets the Main Path where model fragments are stored.
     * @return string Returns the path if setted or null if not.
     */
    public function getMainPath()
    {
        return $this->model_main_path;
    }

    /**
     * Sets the Main Path where model fragments are stored.
     * @param string $path Path to be setted.
     * @return CSmartyModel Returns $this to grant chained calls.
     */
    public function setMainPath($path)
    {
        $this->model_main_path = $path;

        return $this;
    }

    /**
     * Adds a new location path for model templates. Precedence order is the order in which additions are made.
     * @param string $path Folder path to be added.
     * @return bool Returns true if the folder exists or false if not.
     */
    public function addLocation($path)
    {
        $retval = false;

        if (!($retval = array_search($path, $this->model_location_array, $path))) {
            if (($retval = is_dir($path))) {
                $this->model_location_array[] = $path;
            }
        }

        return $retval;
    }

    /**
     * Checks if a fragment is valid. If the check of a fragment is performed the first time, the result is cached
     * to speed up next checks of same fragment. This method also discover if the fragment is available in their
     * original location (as part of Smarty provider package) or as part of the Site folder (as an overwrite engine
     * where the developer can override the behavior of the original fragment).
     * @return bool Returns true if the fragment is valid.
     */
    public function isValidFragment($fragment)
    {
        $retval = false;

        if (!($retval = $this->model_fragment_list->containsKey($fragment)) &&
            count($this->model_location_array) > 0
        ) {
            $path = null;
            foreach ($this->model_location_array as $basepath) {
                $filename = $basepath . DIRECTORY_SEPARATOR . $fragment . '.tpl';
                if (file_exists($filename)) {
                    $path = $filename;
                    $retval = true;
                }
            }
            if ($path !== null) {
                $model_fragment = new CSmartyModelFragment($this, $path);
                $model_fragment->setKey($fragment);
                $this->model_fragment_list->addItem($model_fragment);
            }
        }

        return $retval;
    }

    public function buildFragment($fragment, $params, Smarty $smarty)
    {
        $retval = '';

        if ($this->isValidFragment($fragment)) {
            $fragment = $this->model_fragment_list->getItem($fragment);
            $retval = $fragment->build($params, $smarty);
        }

        return $retval;
    }
}
