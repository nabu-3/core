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
use nabu\data\messaging\base\CNabuMessagingServiceBase;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\messaging
 */
class CNabuMessagingService extends CNabuMessagingServiceBase
{
    /** @var CNabuMessagingServiceTemplateList $nb_messaging_template_service_list List of Templates connected to
     * to this Service instance.
     */
    private $nb_messaging_template_service_list = null;

    public function __construct($nb_messaging_service = false)
    {
        parent::__construct($nb_messaging_service);

        $this->nb_messaging_template_service_list = new CNabuMessagingServiceTemplateList();
    }

    /**
     * Gets all Template connections for this Service.
     * @param bool $force If true forces to reload the list from the database storage.
     * @return CNabuMessagingServiceTemplateList Returns the List of Template connections.
     */
    public function getTemplateConnections(bool $force = false) : CNabuMessagingServiceTemplateList
    {
        if ($this->nb_messaging_template_service_list->isEmpty() || $force) {
            $this->nb_messaging_template_service_list->clear();
            $this->nb_messaging_template_service_list->merge(
                CNabuMessagingServiceTemplate::getTemplatesForService($this)
            );
        }

        return $this->nb_messaging_template_service_list;
    }

    /**
     * Checks if a Template is connected to this Service.
     * @param mixed $nb_template A Messaging Template instance, a CNabuDataObject containing a field named
     * nb_messaging_template_id, or a valid Id.
     * @return bool Returns true if $nb_template is connected or false elsewhere.
     */
    public function isTemplateConnected($nb_template) : bool
    {
        $retval = false;

        if (is_numeric($nb_template_id = nb_getMixedValue($nb_template, NABU_MESSAGING_TEMPLATE_FIELD_ID))) {
            $this->getTemplateConnections();
            $retval = $this->nb_messaging_template_service_list->containsKey($nb_template_id);
        }

        return $retval;
    }

    public function getTreeData($nb_language = null, $dataonly = false)
    {
        $trdata = parent::getTreeData($nb_language, $dataonly);

        $trdata['templates'] = $this->getTemplateConnections();

        return $trdata;
    }

    public function refresh(bool $force = false, bool $cascade = false): bool
    {
        return parent::refresh($force, $cascade) &&
               (!$cascade || (
                    $this->getTemplateConnections($force)
               ))
        ;
    }
}
