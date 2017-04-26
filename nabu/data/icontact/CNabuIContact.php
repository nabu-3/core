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

namespace nabu\data\icontact;
use nabu\data\icontact\base\CNabuIContactBase;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\catalog
 */
class CNabuIContact extends CNabuIContactBase
{
    /** @var CNabuIContactProspectList List of prospects in this iContact. */
    private $nb_icontact_prospect_list = null;

    public function __construct($nb_icontact = false)
    {
        parent::__construct($nb_icontact);

        $this->nb_icontact_prospect_list = new CNabuIContactProspectList($this);
    }

    public function getProspectsOfUser($nb_user)
    {
        $this->nb_icontact_prospect_list->clear();
        $this->nb_icontact_prospect_list->merge(CNabuIContactProspect::getProspectsOfUser($this, $nb_user));

        return $this->nb_icontact_prospect_list;
    }

    public function getTreeData($nb_language = null, $dataonly = false)
    {
        $tdata = parent::getTreeData($nb_language, $dataonly);
        $tdata['prospects'] = $this->nb_icontact_prospect_list;

        return $tdata;
    }
}
