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

namespace nabu\data\icontact;
use nabu\data\icontact\base\CNabuIContactBase;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\icontact
 */
class CNabuIContact extends CNabuIContactBase
{
    /** @var CNabuIContactProspectList List of prospects in this iContact. */
    private $nb_icontact_prospect_list = null;
    /** @var CNabuIContactProspectStatusTypeList List of Prospect Status Types in this iContact. */
    private $nb_icontact_prospect_status_type_list = null;

    public function __construct($nb_icontact = false)
    {
        parent::__construct($nb_icontact);

        $this->nb_icontact_prospect_list = new CNabuIContactProspectList($this);
        $this->nb_icontact_prospect_status_type_list = new CNabuIContactProspectStatusTypeList($this);
    }

    public function getProspectsOfUser($nb_user)
    {
        $this->nb_icontact_prospect_list->clear();
        $this->nb_icontact_prospect_list->merge(CNabuIContactProspect::getProspectsOfUser($this, $nb_user));

        return $this->nb_icontact_prospect_list;
    }

    /**
     * Get Prospect Status Types of this instance.
     * @param bool $force If true then force to reload complete list from database storage.
     * @return CNabuIContactProspectStatusTypeList Returns a list instance with all types found.
     */
    public function getProspectStatusTypes(bool $force = false)
    {
        if ($this->nb_icontact_prospect_status_type_list->isEmpty() || $force) {
            $this->nb_icontact_prospect_status_type_list->clear();
            $this->nb_icontact_prospect_status_type_list->merge(CNabuIContactProspectStatusType::getTypesForIContact($this));
        }

        return $this->nb_icontact_prospect_status_type_list;
    }

    public function getTreeData($nb_language = null, $dataonly = false)
    {
        $tdata = parent::getTreeData($nb_language, $dataonly);
        $tdata['languages'] = $this->getLanguages();
        $tdata['prospects'] = $this->nb_icontact_prospect_list;
        $tdata['prospect_status_types'] = $this->getProspectStatusTypes();

        return $tdata;
    }

    /**
     * To find a list of Prospects related by the same Email hash.
     * @param string $hash Hash that identifies the Email.
     * @return CNabuIContactProspectList The list of Prospects found.
     */
    public function findProspectsByEmailHash(string $hash) : CNabuIContactProspectList
    {
        return CNabuIContactProspect::findIContactProspectsByEmailHash($this, $hash);
    }
}
