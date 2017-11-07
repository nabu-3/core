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

namespace nabu\data\site;

use nabu\data\security\interfaces\INabuRoleMask;
use nabu\data\security\traits\TNabuRoleMaskList;
use nabu\data\site\base\CNabuSiteTargetCTAListBase;
use nabu\data\site\traits\TNabuSiteTargetChild;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\site
 */
class CNabuSiteTargetCTAList extends CNabuSiteTargetCTAListBase implements INabuRoleMask
{
    use TNabuSiteTargetChild;
    use TNabuRoleMaskList;

    public function __construct(CNabuSiteTarget $nb_site_target = null)
    {
        parent::__construct();

        if ($nb_site_target !== null) {
            $this->setSiteTarget($nb_site_target);
        }
    }
}
