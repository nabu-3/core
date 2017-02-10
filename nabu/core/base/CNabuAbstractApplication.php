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

namespace nabu\core\base;

use \nabu\core\CNabuEngine;
use \nabu\core\CNabuObject;
use \nabu\core\interfaces\INabuApplication;

/**
 * Abstract base class to implement applications running under nabu-3 Engine.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package name
 */
abstract class CNabuAbstractApplication extends CNabuObject implements INabuApplication
{
    /**
     * Engine instance
     * @var CNabuEngine
     */
    protected $nb_engine = null;

    /**
     * Create a instance of this class and get the Engine to store in $nb_engine.
     * This method is public but is intended to use internally in launch method.
     */
    public function __construct()
    {
        parent::__construct();

        $this->nb_engine = CNabuEngine::getEngine();
    }
}
