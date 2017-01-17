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

namespace providers\smarty\builders\interfaces;

/**
 * Interface to implement different Smarty Renders to create ad-hoc plugins of Smarty.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \providers\smarty\builders\interfaces
 */
interface ISmartyBuilder
{
    /**
     * Executes the render and returns the formed output if needed.
     * @param string $content Content enclosed inside Smarty tags if they are a block.
     * @param bool $repeat Referenced variable to know it the block needs to be repeated.
     * @return string Returns the formed string if needed or a empty string or null if not.
     */
    public function execute($content = null, &$repeat = false);
}
