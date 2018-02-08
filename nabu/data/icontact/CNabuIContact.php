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

    /**
     * Get a Prospect by his Id.
     * @param mixed $nb_prospect A CNabuDataObject containing a field named nb_icontact_prospect_id or a valid Id.
     * @return CNabuIContactProspect|false Returns a valid instance if found or false if none exists.
     */
    public function getProspect($nb_prospect)
    {
        $retval = false;

        if (is_numeric($nb_prospect_id = nb_getMixedValue($nb_prospect, NABU_ICONTACT_PROSPECT_FIELD_ID))) {
            $retval = $this->nb_icontact_prospect_list->getItem($nb_prospect_id);
        }

        return $retval;
    }

    /**
     * Get a list with all Prospects owned by a User.
     * @param mixed $nb_user A CNabuDataObject containing a field named nb_user_id or a valid Id.
     * @param CNabuIContactProspectStatusType|null $nb_status_type If setted, the list is filtered using this status.
     * @return CNabuIContactProspectList Returns a list instance with all types found.
     */
    public function getProspectsOfUser($nb_user, CNabuIContactProspectStatusType $nb_status_type = null) : CNabuIContactProspectList
    {
        $this->nb_icontact_prospect_list->clear();
        $this->nb_icontact_prospect_list->merge(CNabuIContactProspect::getProspectsOfUser($this, $nb_user, $nb_status_type));

        return $this->nb_icontact_prospect_list;
    }

    /**
     * Get the number of Prospects owned by a User.
     * @param mixed $nb_user A CNabuDataObject containing a field named nb_user_id or a valid Id.
     * @param CNabuIContactProspectStatusType|null $nb_status_type If setted, the list is filtered using this status.
     * @return int Returns the count of all types found.
     */
    public function getCountProspectsOfUser($nb_user, CNabuIContactProspectStatusType $nb_status_type = null) : int
    {
        return CNabuIContactProspect::getCountProspectsOfUser($this, $nb_user, $nb_status_type);
    }

    /**
     * Get Prospect Status Types of this instance.
     * @param bool $force If true then force to reload complete list from database storage.
     * @return CNabuIContactProspectStatusTypeList Returns a list instance with all types found.
     */
    public function getProspectStatusTypes(bool $force = false) : CNabuIContactProspectStatusTypeList
    {
        if ($this->nb_icontact_prospect_status_type_list->isEmpty() || $force) {
            $this->nb_icontact_prospect_status_type_list->clear();
            $this->nb_icontact_prospect_status_type_list->merge(CNabuIContactProspectStatusType::getTypesForIContact($this));
        }

        return $this->nb_icontact_prospect_status_type_list;
    }

    /**
     * Get a specific Prospect Status Type of this instance.
     * @param mixed $type A CNabuDataObject containing a field named nb_icontact_prospect_status_type_id or a valid Id.
     * @return CNabuIContactProspectStatusType|false Returns the status type instance if exists or false if not.
     */
    public function getProspectStatusType($type)
    {
        $retval = false;

        if (is_numeric($nb_type_id = nb_getMixedValue($type, 'nb_icontact_prospect_status_type_id'))) {
            $retval = $this->nb_icontact_prospect_status_type_list->getItem($nb_type_id);
        }

        return $retval;
    }

    /**
     * Get a specific Prospect Status Type of this instance identified by his key.
     * @param string $key A key to looking for.
     * @return CNabuIContactProspectStatusType|false Returns the status type instance if exists or false if not.
     */
    public function getProspectStatusTypeByKey(string $key)
    {
        $retval = false;

        if (is_string($key)) {
            $this->getProspectStatusTypes();
            $retval = $this->nb_icontact_prospect_status_type_list->getItem($key, CNabuIContactProspectStatusTypeList::INDEX_KEY);
        }

        return $retval;
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
     * Overrides refresh method to allow iContact subentities to be refreshed.
     * @param bool $force Forces to reload entities from the database storage.
     * @param bool $cascade Forces to reload child entities from the database storage.
     * @return bool Returns true if transations are empty or refreshed.
     */
    public function refresh(bool $force = false, bool $cascade = false) : bool
    {
        return parent::refresh($force, $cascade) &&
               (!$cascade || (
                   $this->getProspectStatusTypes($force)
               ))
        ;
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
