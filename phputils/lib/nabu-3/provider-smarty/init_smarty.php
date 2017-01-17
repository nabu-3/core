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

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \providers\smarty
 */

define ('SMARTY_MANAGER_KEY', 'SmartyManager');

define ('SMARTY_PROVIDER_PATH', dirname(__FILE__));

define('SMARTY_TEMPLATES_FOLDER', DIRECTORY_SEPARATOR . 'templates');
define('SMARTY_COMPILES_FOLDER', DIRECTORY_SEPARATOR . 'compiles');
define('SMARTY_CONFIG_FOLDER', DIRECTORY_SEPARATOR . 'config');
define('SMARTY_MODELS_FOLDER', DIRECTORY_SEPARATOR . 'models');
define('SMARTY_PLUGINS_FOLDER', DIRECTORY_SEPARATOR . 'plugins');

use nabu\core\CNabuEngine;
use nabu\http\app\base\CNabuHTTPApplication;
use providers\smarty\CSmartyManager;

$nb_engine->traceLog(null, 'Init Smarty');
$nb_application->registerManager('providers\smarty\CSmartyManager');
