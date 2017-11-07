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

namespace nabu\core\interfaces;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package name
 */
interface INabuApplication
{
    /**
     * This method is called before call the run method.
     * He is intended to prepare the environment and set variables or process
     * command line parameters.
     */
    public function prepareEnvironment();

    /**
     * This method contains the application main process. Normally, you don't need to call it directly.
     * The launch method is the responsible to call this method, and returned value is passed as return
     * value after call the static method launch.
     * If required, this method can return a value.
     * @Return mixed If required,
     */
    public function run();

    /**
     * Launch the application represented in an inherited class of this class.
     * @return mixed Returns the value returned internally by the run method.
     */
    public static function launch();
}
