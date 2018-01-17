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
use nabu\data\icontact\base\CNabuIContactProspectStatusTypeBase;
use nabu\data\icontact\traits\TNabuIContactChild;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\icontact
 */
class CNabuIContactProspectStatusType extends CNabuIContactProspectStatusTypeBase
{
    use TNabuIContactChild;
    
    /**
     * Gets all Types associated with an iContact.
     * @param mixed $nb_icontact A CNabuIContact instance, or a CNabuDataObject instance containing
     * a field named nb_icontact_id or a valid iContact Id.
     * @return CNabuIContactProspectStatusTypeList Returns a list with existing types.
     */
    public static function getTypesForIContact($nb_icontact)
    {
        if (is_numeric($nb_icontact_id = nb_getMixedValue($nb_icontact, 'nb_icontact_id'))) {
            $retval = CNabuIContactProspectStatusType::buildObjectListFromSQL(
                'nb_icontact_prospect_status_type_id',
                'SELECT ipst.*
                   FROM nb_icontact_prospect_status_type ipst, nb_icontact i
                  WHERE ipst.nb_icontact_id=i.nb_icontact_id
                    AND i.nb_icontact_id=%cont_id$d
                  ORDER BY ipst.nb_icontact_prospect_status_type_creation_datetime',
                array(
                    'cont_id' => $nb_icontact_id
                ),
                ($nb_icontact instanceof CNabuIContact ? $nb_icontact : null)
            );

            if ($nb_icontact instanceof CNabuIContact) {
                $retval->iterate(function($key, CNabuIContactProspectStatusType $nb_status) {
                    $nb_status->setIContact($nb_icontact);
                    return true;
                });
            }
        } else {
            if ($nb_icontact instanceof CNabuIContact) {
                $retval = new CNabuIContactProspectStatusTypeList($nb_icontact);
            } else {
                $retval = new CNabuIContactProspectStatusTypeList();
            }
        }
    }
}
