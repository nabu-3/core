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

namespace nabu\data\customer\traits;
use nabu\data\customer\CNabuCustomer;
use nabu\data\CNabuDataObject;

/**
 * This trait implements default actions to manage a Customer owned object in nabu-3.
 * A great number of methods defined in the interface \nabu\customer\interfaces\INabuCustomerChild
 * are implemented here with a default behavior.
 * You can apply this trait to your own classes to speed up your development,
 * or create your own management.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\customer\traits;
 */
trait TNabuCustomerChild
{
    /**
     * Customer instance
     * @var CNabuCustomer
     */
    private $nb_customer = null;

    /**
     * Gets the customer instance {@link nabu\data\customer\CNabuCustomer} object if field exists and object
     * could be instantiated else null if not.
     * @param string $field If defined permits to construct the CNabuCustomer object from a customizable value
     * @return \nabu\data\customer\CNabuCustomer|null
     */
    public function getCustomer($force = false, $field = 'cms_customer_id') {

        if (!$this->isBuiltIn() && ($this->nb_customer === null || $force)) {
            $this->nb_customer = null;
            if (($this instanceof CNabuDataObject) && $this->contains($field)) {
                $nb_customer = new CNabuCustomer($this->getValue($field));
                if ($nb_customer->isFetched()) {
                    $this->nb_customer = $nb_customer;
                }
            }
        }

        return $this->nb_customer;
    }

    /**
     * Sets the customer instance that owns this object and sets the field containing the customer id.
     * @param CNabuCustomer $nb_customer Customer instance to be setted.
     * @param string $field Field name where the customer id will be stored.
     * @return CNabuDataObject Returns $this to allow the cascade chain of setters.
     */
    public function setCustomer(CNabuCustomer $nb_customer, $field = NABU_CUSTOMER_FIELD_ID)
    {
        $this->nb_customer = $nb_customer;
        if ($this instanceof CNabuDataObject) {
            if ($nb_customer !== null) {
                $this->transferValue($nb_customer, NABU_CUSTOMER_FIELD_ID, $field);
            } else {
                $this->setValue(NABU_CUSTOMER_FIELD_ID, null);
            }
        }

        return $this;
    }

    /**
     * Checks if the object is owned by the customer passed as param.
     * @param mixed $nb_customer object containing the field nb_customer_id or an scalar value containing
     * the customer ID.
     * @return bool Returns true if $nb_customer is a valid customer ID and is the owner of the object.
     */
    public function validateCustomer($nb_customer)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, NABU_CUSTOMER_FIELD_ID);
        return ($this instanceof CNabuDataObject) &&
               (is_numeric($nb_customer_id) || nb_isValidGUID($nb_customer_id)) &&
               $this->isValueEqualThan(NABU_CUSTOMER_FIELD_ID, $nb_customer_id)
        ;
    }
}
