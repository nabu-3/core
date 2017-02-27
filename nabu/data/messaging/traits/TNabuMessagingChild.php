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

namespace nabu\data\messaging\traits;
use nabu\data\CNabuDataObject;
use nabu\data\customer\CNabuCustomer;
use nabu\data\messaging\CNabuMessaging;

/**
 * This trait implements default actions to manage a Messaging child object in nabu-3.
 * You can apply this trait to your own classes to speed up your development,
 * or create your own management.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\messaging\traits;
 */
trait TNabuMessagingChild
{
    /**
     * Messaging instance
     * @var CNabuMessaging
     */
    protected $nb_messaging = null;

    /**
     * Gets the Messaging instance.
     * @param null|CNabuCustomer If you want to get the Messaging in safe mode, you need to pass this parameter
     * to grant the Customer that owns the Messaging where the Messaging is placed. In any other case,
     * the Messaging could not be retrieved.
     * @param bool $force If true, then tries to reload Messaging instance from their storage.
     * @return CNabuMessaging|null Returns the Messaging instance if setted or null if not.
     */
    public function getMessaging(CNabuCustomer $nb_customer = null, $force = false)
    {
        if ($nb_customer !== null && ($this->nb_messaging === null || $force)) {
            $this->nb_messaging = null;
            if ($this instanceof CNabuDataObject && $this->contains(NABU_MESSAGING_FIELD_ID)) {
                $this->nb_messaging = $nb_customer->getMessaging($this);
            }
        }

        return $this->nb_messaging;
    }

    /**
     * Sets the Messaging instance that owns this object and sets the field containing the messaging id.
     * @param CNabuMessaging $nb_messaging Messaging instance to be setted.
     * @param string $field Field name where the site id will be stored.
     * @return CNabuDataObject Returns $this to allow the cascade chain of setters.
     */
    public function setMessaging(CNabuMessaging $nb_messaging, $field = NABU_MESSAGING_FIELD_ID)
    {
        $this->nb_messaging = $nb_messaging;
        if ($this instanceof CNabuDataObject) {
            $this->transferValue($nb_messaging, NABU_MESSAGING_FIELD_ID, $field);
        }

        return $this;
    }
}
