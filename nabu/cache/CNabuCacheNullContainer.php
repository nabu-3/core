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

namespace nabu\cache;

use \nabu\cache\interfaces\INabuCacheable;
use \nabu\core\CNabuObject;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package name
 */
class CNabuCacheNullContainer extends CNabuObject implements INabuCacheable
{
    protected $key = null;
    protected $data = null;
    protected $prevent_callable = false;

    public function __construct($key = false, $source = false, bool $prevent_callable = false) {

        $this->key = $key;
        $this->prevent_callable = $prevent_callable;
        $this->data = self::explodeSource($source, $prevent_callable);

        parent::__construct();
    }

    private static function explodeSource($source, bool $prevent_callable = false) {

        if (!$prevent_callable && is_callable($source)) {
            return $source();
        } else {
            return $source;
        }
    }

    public function getKey() {

        return $this->key;
    }

    public function getData() {

        return $this->data;
    }

    public function setData($data) {

        $this->data = $data;
    }

    public function update() {

        return true;
    }
}
