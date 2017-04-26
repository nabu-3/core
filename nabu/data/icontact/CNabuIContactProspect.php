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
use nabu\data\icontact\base\CNabuIContactProspectBase;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\catalog
 */
class CNabuIContactProspect extends CNabuIContactProspectBase
{
    private $nb_icontact = null;

    public function getIContact()
    {
        return $this->nb_icontact;
    }

    public function setIContact($nb_icontact)
    {
        $this->nb_icontact = $nb_icontact;
        return $this;
    }

    static public function getProspectsOfUser($nb_icontact, $nb_user)
    {
        if (is_numeric($nb_icontact_id = nb_getMixedValue($nb_icontact, 'nb_icontact_id')) &&
            is_numeric($nb_user_id = nb_getMixedValue($nb_user, 'nb_user_id'))
        ) {
            $retval = CNabuIContactProspect::buildObjectListFromSQL(
                'nb_icontact_prospect_id',
                "select ip.* "
                . "from nb_icontact_prospect ip, nb_icontact i "
               . "where ip.nb_icontact_id=i.nb_icontact_id "
                 . "and i.nb_icontact_id=%cont_id\$d "
                 . "and ip.nb_user_id=%user_id\$d "
               . "order by ip.nb_icontact_prospect_creation_datetime desc",
                array(
                    'cont_id' => $nb_icontact_id,
                    'user_id' => $nb_user_id
                )
            );
            if ($nb_icontact instanceof CNabuIContact) {
                $retval->iterate(function ($key, $nb_prospect) use($nb_icontact) {
                    $nb_prospect->setIContact($nb_icontact);
                    return true;
                });
            }
        } else {
            $retval = new CNabuIContactProspectList();
        }

        return $retval;
    }
}
