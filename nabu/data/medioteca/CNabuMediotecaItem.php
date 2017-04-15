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

use \nabu\data\medioteca\base\CNabuMediotecaItemBase;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\medioteca
 */
class CNabuMediotecaItem extends CNabuMediotecaItemBase
{
    static public function getItemsForMedioteca(CNabuMedioteca $nb_medioteca)
    {
        $nb_medioteca_id = nb_getMixedValue($nb_medioteca, NABU_MEDIOTECA_FIELD_ID);
        if (is_numeric($nb_medioteca_id)) {
            $retval = CNabuMediotecaItem::buildObjectListFromSQL(
                'nb_medioteca_item_id',
                'select * '
                . 'from nb_medioteca_item mi '
                . 'where mi.nb_medioteca_id=%medioteca_id$d '
                . 'order by nb_medioteca_item_order',
                array(
                    'medioteca_id' => $nb_medioteca_id
                ),
                $nb_medioteca
            );
        } else {
            $retval = new CNabuMediotecaItemList($nb_medioteca);
        }

        return $retval;
    }
}
