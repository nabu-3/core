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

namespace nabu\http\managers\base;

use nabu\core\CNabuObject;
use nabu\core\exceptions\ENabuCoreException;
use nabu\http\app\base\CNabuHTTPApplication;
use nabu\http\managers\CNabuHTTPRenderDescriptor;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.9 Surface
 * @package \nabu\http\managers\base
 */
abstract class CNabuHTTPManager extends CNabuObject
{
    /**
     * nabu-3 HTTP Application that owns this Manager
     * @var CNabuHTTPApplication
     */
    protected $nb_application = null;

    public function __construct(CNabuHTTPApplication $nb_application)
    {
        parent::__construct();

        $this->nb_application = $nb_application;
    }

    /**
     * Gets the HTTP Application that owns this manager.
     * @return CNabuHTTPApplication Returns the active instance.
     */
    public function getApplication()
    {
        return $this->nb_application;
    }

    /**
     * Gets the HTTP Manager key attribute value.
     * @return string Returns the key value.
     */
    public function getKey()
    {
        return $this->getValue('nb_http_manager_key');
    }

    /**
     * Sets the HTTP Manager key attribute value
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
        $this->setValue('nb_http_manager_key', $key);

        return $this;
    }
}
