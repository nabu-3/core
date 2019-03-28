<?php

/** @license
 *  Copyright 2019-2011 Rafael Gutierrez Martinez
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

namespace nabu\data\icontact\traits;

use nabu\data\CNabuDataObject;
use nabu\data\icontact\CNabuIContactProspect;

/**
 * This trait implements default actions to manage an iContact Prospect child object in nabu-3.
 * You can apply this trait to your own classes to speed up your development,
 * or create your own management.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\iconcat\traits;
 */
trait TNabuIContactProspectChild
{
    /**
     * iContact instance that owns this object.
     * @var CNabuIContactProspect
     */
    private $nb_icontact_prospect = null;

    /**
     * Gets the iContact Prospect instance that owns this object.
     * @param bool $force If true forces to reload IContact Prospect from the database.
     * @return CNabuIContactProspect Returns the iContact instance if exists or null if not.
     */
    public function getIContactProspect($force = false)
    {
        if ($this->nb_icontact_prospect === null || $force) {
            $this->nb_icontact_prospect = null;
            if ($this instanceof CNabuDataObject &&
                !$this->isBuiltIn()
                && $this->isValueNumeric(NABU_ICONTACT_PROSPECT_FIELD_ID)
            ) {
                $nb_icontact_prospect = new CNabuIContactProspect($this->getValue(NABU_ICONTACT_PROSPECT_FIELD_ID));
                if ($nb_icontact_prospect->isFetched()) {
                    $this->nb_icontact_prospect = $nb_icontact_prospect;
                }
            }
        }

        return $this->nb_icontact_prospect;
    }

    /**
     * Sets the iContact Prospect instance that onws this object.
     * @param CNabuIContactProspect $nb_icontact_prospect The iContact Prospect instance to be setted.
     * @return mixed Return $this to grant cascade chain.
     */
    public function setIContactProspect(CNabuIContactProspect $nb_icontact_prospect)
    {
        $this->nb_icontact_prospect = $nb_icontact_prospect;
        if ($this instanceof CNabuDataObject && $nb_icontact_prospect->contains(NABU_ICONTACT_PROSPECT_FIELD_ID)) {
            $this->transferValue($nb_icontact_prospect, NABU_ICONTACT_PROSPECT_FIELD_ID);
        }

        return $this;
    }
}
