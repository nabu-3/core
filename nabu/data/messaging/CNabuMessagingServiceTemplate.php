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
use nabu\data\messaging\base\CNabuMessagingServiceTemplateBase;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\messaging
 */
class CNabuMessagingServiceTemplate extends CNabuMessagingServiceTemplateBase
{
    /**
     * Gets the list of Template Connections for a Service.
     * @param mixed $nb_messaging_service A CNabuMessagingService instance, a CNabuDataObject inherited instance
     * containing a field named nb_messaging_service_id or a valid Id.
     * @return CNabuMessagingTemplateList Returns a list of all Template Connections found.
     */
    public static function getTemplatesForService($nb_messaging_service) : CNabuMessagingServiceTemplateList
    {
        if (is_numeric($nb_messaging_service_id = nb_getMixedValue($nb_messaging_service, NABU_MESSAGING_SERVICE_FIELD_ID))) {
            $retval = CNabuMessagingServiceTemplate::buildObjectListFromSQL(
                'nb_messaging_template_id',
                'select * '
                . 'from nb_messaging_service_template '
               . 'where nb_messaging_service_id=%service_id$d',
                array(
                    'service_id' => $nb_messaging_service_id
                )
           );

           if ($nb_messaging_service instanceof CNabuMessagingService) {
               $retval->iterate(function($key, CNabuMessagingServiceTemplate $nb_template) use ($nb_messaging_service) {
                   $nb_template->setMessagingService($nb_messaging_service);
                   return true;
               });
           }
       } else {
           $retval = new CNabuMessagingServiceTemplateList();
       }

       return $retval;
    }
}
