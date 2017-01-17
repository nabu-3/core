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

namespace providers\smarty\builders\base;

use \nabu\core\CNabuObject;
use \providers\smarty\builders\interfaces\ISmartyBuilder;

/**
 * This class represents an URI request.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \providers\smarty\builders\base
 */
abstract class CSmartyAbstractBuilder extends CNabuObject implements ISmartyBuilder
{
    /**
     * Params array of the Smarty request
     * @var array
     */
    protected $params = null;

    public function __construct($params)
    {
        parent::__construct();

        $this->params = $params;
    }

    public function attributeExists($attr)
    {
        return (is_string($attr) && is_array($this->params) && array_key_exists($attr, $this->params));
    }

    public function getFirstOccurence($attr_list)
    {
        $retval = false;

        if (is_array($attr_list) && count($attr_list) > 0) {
            foreach ($attr_list as $attr) {
                if (array_key_exists($attr, $this->params)) {
                    $retval = $attr;
                    break;
                }
            }
        }

        return $retval;
    }
}
