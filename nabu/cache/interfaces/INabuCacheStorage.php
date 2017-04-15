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

namespace nabu\cache\interfaces;

/**
 * Interface for Cache Storage Controllers.
 * @author Rafael Gutiérrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\cache\interfaces
 */
interface INabuCacheStorage {
    
    public function initStorage();
    public function createContainerId($type, $index, $id, $params);
    public function createContainer($key = false, $source = false);
    public function getContainer($key);
    public function releaseContainer($key);
}