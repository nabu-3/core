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

use nabu\core\CNabuEngine;

 /**
  * Messaging Daemon of nabu-3.
  * @author Rafael Gutierrez <rgutierrez@wiscot.com>
  * @since 3.0.12 Surface
  * @version 3.0.12 Surface
  * @package \nabu\messaging\app
  */

require_once 'common.php';
require_once 'cli.php';

CNabuEngine::setOperationModeCLI();
CNabuEngine::setInstallMode(true);
CNabuEngine::getEngine()->runApplication('\nabu\messaging\app\CNabuMessagingDaemonApplication');
