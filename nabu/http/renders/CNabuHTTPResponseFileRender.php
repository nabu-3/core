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

namespace nabu\http\renders;

use nabu\http\renders\base\CNabuHTTPResponseRenderAdapter;
use nabu\sdk\builders\CNabuAbstractBuilder;

/**
 * Render to dump a file as attachment
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\http\renders\base
 */
class CNabuHTTPResponseFileRender extends CNabuHTTPResponseRenderAdapter
{
    public function __construct($main_render = false)
    {

        parent::__construct($main_render);
    }

    public function render()
    {
        if (is_string($this->source_filename) &&
            file_exists($this->source_filename) &&
            is_file($this->source_filename)
        ) {
            $this->dumpFile($this->source_filename);
            if ($this->unlink_source_file_after_render) {
                unlink($this->source_filename);
            }
        } elseif (isset($this->contentBuilder) && $this->contentBuilder instanceof CNabuAbstractBuilder) {
            echo $this->contentBuilder->create();
        }
    }
}
