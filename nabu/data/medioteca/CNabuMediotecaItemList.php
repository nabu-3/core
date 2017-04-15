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

namespace nabu\data\medioteca;

use nabu\core\CNabuEngine;
use nabu\data\medioteca\base\CNabuMediotecaItemListBase;
use nabu\data\medioteca\traits\TNabuMediotecaChild;

/**
 * Class to manage Medioteca Item Lists of entities.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\medioteca
 */
class CNabuMediotecaItemList extends CNabuMediotecaItemListBase
{
    use TNabuMediotecaChild;

    public function __construct(CNabuMedioteca $nb_medioteca)
    {
        parent::__construct();

        $this->setMedioteca($nb_medioteca);
    }

    public function acquireItem($key, $index = false)
    {
        if (!($item = parent::acquireItem($key, $index)) && CNabuEngine::getEngine()->isMainDBAvailable()) {
            switch ($index) {
                case self::INDEX_KEY:
                    $item = CNabuMediotecaItem::findByKey($this->nb_medioteca, $key);
                    break;
            }
        }

        return $item;
    }
}
