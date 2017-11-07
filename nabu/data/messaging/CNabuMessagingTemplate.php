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

namespace nabu\data\messaging;
use nabu\data\messaging\base\CNabuMessagingTemplateBase;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\messaging
 */
class CNabuMessagingTemplate extends CNabuMessagingTemplateBase
{
    /** @var CNabuMessagingServiceList $nb_messaging_service_list List of services connected to this Template. */
    private $nb_messaging_service_list = null;

    public function __construct($nb_messaging_template = false)
    {
        parent::__construct($nb_messaging_template);

        $this->nb_messaging_service_list = new CNabuMessagingServiceList();
    }

    /**
     * Get the list of all Service instances connected to this Template.
     * @param bool $force If true, forces to refresh the list from the database storage.
     * @return CNabuMessagingServiceList Returns the list of connected services.
     */
    public function getServices(bool $force = false) : CNabuMessagingServiceList
    {
        if ($this->nb_messaging_service_list->isEmpty() || $force) {
            $this->nb_messaging_service_list->clear();
            if (($nb_messaging = $this->getMessaging()) instanceof CNabuMessaging &&
                !($nb_services_list = $nb_messaging->getServices())->isEmpty()
            ) {
                $nb_services_list->iterate(function($key, CNabuMessagingService $nb_service) {
                    if ($nb_service->isTemplateConnected($this->getId())) {
                        $this->nb_messaging_service_list->addItem($nb_service);
                    }
                    return true;
                });
            }
        }

        return $this->nb_messaging_service_list;
    }
}
