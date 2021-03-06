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

namespace nabu\http\renders;

use nabu\core\CNabuEngine;

use \nabu\http\renders\base\CNabuHTTPResponseRenderAdapter;

/**
 * Class to dump JSON as HTTP response.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.12 Surface
 * @package \nabu\http\renders
 */
class CNabuHTTPResponseJSONRender extends CNabuHTTPResponseRenderAdapter
{
    public function __construct($main_render = false)
    {
        parent::__construct($main_render);
    }

    public function setArrayValues($array)
    {
        $this->nb_render_data->setArrayValues($array);
    }

    public function render()
    {
        if ($this->nb_render_data->isEmpty()) {
            echo "{}";
        } else {
            if (($json = json_encode($this->nb_render_data->getTreeData(null, true))) !== false) {
                echo $json;
            } else {
                CNabuEngine::getEngine()->errorLog(json_last_error_msg());
            }
        }
    }
}
