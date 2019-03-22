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

namespace nabu\data\messaging\traits;
use nabu\data\CNabuDataObject;
use nabu\data\messaging\CNabuMessagingService;

/**
 * This trait implements default actions to manage a Messaging Service child object in nabu-3.
 * You can apply this trait to your own classes to speed up your development,
 * or create your own management.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\messaging\traits;
 */
trait TNabuMessagingServiceChild
{
    /**
     * Messaging Service instance
     * @var CNabuMessagingService
     */
    protected $nb_messaging_service = null;

    /**
     * Gets the Messaging Service instance.
     * @return CNabuMessagingService|null Returns the Messaging Service instance if setted or null if not.
     */
    public function getMessagingService()
    {
        return $this->nb_messaging_service;
    }

    /**
     * Sets the Messaging instance that owns this object and sets the field containing the messaging id.
     * @param CNabuMessagingService $nb_messaging_service Messaging instance to be setted.
     * @param string $field Field name where the site id will be stored.
     * @return CNabuDataObject Returns $this to allow the cascade chain of setters.
     */
    public function setMessagingService(
        CNabuMessagingService $nb_messaging_service,
        $field = NABU_MESSAGING_SERVICE_FIELD_ID
    ) {
        $this->nb_messaging_service = $nb_messaging_service;
        if ($this instanceof CNabuDataObject) {
            $this->transferValue($nb_messaging_service, NABU_MESSAGING_SERVICE_FIELD_ID, $field);
        }

        return $this;
    }
}
