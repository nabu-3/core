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
use nabu\data\icontact\CNabuIContact;

/**
 * This trait implements default actions to manage an iContact child object in nabu-3.
 * You can apply this trait to your own classes to speed up your development,
 * or create your own management.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\iconcat\traits;
 */
trait TNabuIContactChild
{
    /**
     * iContact instance that owns this object.
     * @var CNabuIContact
     */
    private $nb_icontact = null;

    /**
     * Gets the iContact instance that owns this object.
     * @param bool $force If true forces to reload IContact from the database.
     * @return CNabuIContact Returns the iContact instance if exists or null if not.
     */
    public function getIContact($force = false)
    {
        if ($this->nb_icontact === null || $force) {
            $this->nb_icontact = null;
            if ($this instanceof CNabuDataObject &&
                !$this->isBuiltIn()
                && $this->isValueNumeric(NABU_ICONTACT_FIELD_ID)
            ) {
                $nb_icontact = new CNabuIContact($this->getValue(NABU_ICONTACT_FIELD_ID));
                if ($nb_icontact->isFetched()) {
                    $this->nb_icontact = $nb_icontact;
                }
            }
        }

        return $this->nb_icontact;
    }

    /**
     * Sets the iContact instance that onws this object.
     * @param CNabuIContact $nb_icontact The iContact instance to be setted.
     * @return mixed Return $this to grant cascade chain.
     */
    public function setIContact(CNabuIContact $nb_icontact)
    {
        $this->nb_icontact = $nb_icontact;
        if ($this instanceof CNabuDataObject && $nb_icontact->contains(NABU_ICONTACT_FIELD_ID)) {
            $this->transferValue($nb_icontact, NABU_ICONTACT_FIELD_ID);
        }

        return $this;
    }
}
