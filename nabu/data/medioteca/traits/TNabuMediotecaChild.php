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

namespace nabu\data\medioteca\traits;
use nabu\data\CNabuDataObject;
use nabu\data\medioteca\CNabuMedioteca;
/**
 * This trait implements default actions to manage a Medioteca child object in nabu-3.
 * You can apply this trait to your own classes to speed up your development,
 * or create your own management.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\data\medioteca\traits;
 */
trait TNabuMediotecaChild
{
    /**
     * Medioteca instance
     * @var CNabuMedioteca
     */
    private $nb_medioteca = null;

    /**
     * Gets the Medioteca instance using the field value contained in parent class CNabuDataObject
     * @param mixed $nb_customer An instance of CNabuDataObject which contains a field named nb_customer_id or an ID.
     * When false, then uses as Customer the owner of the application.
     * @param bool $force If true forces to reload the Medioteca instance from their repository.
     * @return CNabuMedioteca|null Returns the Medioteca instance if setted or null if not.
     */
    public function getMedioteca($nb_customer = false, $force = false)
    {
        if (($this->nb_medioteca === null || $force) && !$this->isValueNull(NABU_MEDIOTECA_FIELD_ID)) {
            $this->nb_medioteca = null;
            if (($nb_final_cust = nb_grantCustomer($nb_customer, $force)) !==false) {
                $this->nb_medioteca = $nb_final_cust->getMedioteca($this);
            }
        }

        return $this->nb_medioteca;
    }

    /**
     * Sets the Medioteca instance that owns this object and sets the field containing the Medioteca id.
     * @param CNabuMedioteca $nb_medioteca Medioteca instance to be setted.
     * @param string $field Field name where the Medioteca id will be stored.
     * @return CNabuDataObject Returns $this to allow the cascade chain of setters.
     */
    public function setMedioteca(CNabuMedioteca $nb_medioteca, $field = NABU_MEDIOTECA_FIELD_ID)
    {
        $this->nb_medioteca = $nb_medioteca;
        if ($this instanceof CNabuDataObject) {
            $this->transferValue($nb_medioteca, NABU_MEDIOTECA_FIELD_ID, $field);
        }

        return $this;
    }
}
