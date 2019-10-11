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

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.12 Surface
 */

/* Include files */
require_once 'globals.php';

$license_file = NABU_ETC_PATH . DIRECTORY_SEPARATOR . 'license.php';
if (file_exists($license_file)) {
    require_once $license_file;
} else {
    if ($handle = fopen($license_file, 'w+')) {
        $content = <<<FILE
<?php

define('NABU_LICENSED', 'nabu-3 group');
define('NABU_LICENSEE_TARGET', 'https://www.nabu-3.com/');
FILE;
        fwrite($handle, $content);
        fclose($handle);
        require_once $license_file;
    } else {
        error_log('Error creating license file');
    }
}
unset($license_file);

$autoload_file = NABU_BASE_PATH
               . DIRECTORY_SEPARATOR . 'lib'
               . DIRECTORY_SEPARATOR . 'autoload.php'
;

if (file_exists($autoload_file)) {
    require $autoload_file;
}
unset($autoload_file);

require_once 'vars.php';
require_once 'functions.php';
require_once 'init.php';
