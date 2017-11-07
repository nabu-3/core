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
use nabu\data\CNabuDataObject;
use nabu\data\messaging\base\CNabuMessagingBase;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\messaging
 */
class CNabuMessaging extends CNabuMessagingBase
{
    /** @var CNabuMessagingServiceList $nb_messaging_service_list List of services of this instance. */
    private $nb_messaging_service_list = null;
    /** @var CNabuMessagingTemplateList $nb_messaging_template_list List of templates of this instance. */
    private $nb_messaging_template_list = null;

    public function __construct($nb_messaging = false)
    {
        parent::__construct($nb_messaging);

        $this->nb_messaging_service_list = new CNabuMessagingServiceList();
        $this->nb_messaging_template_list = new CNabuMessagingTemplateList();
    }

    /**
     * Get Services assigned to this Messaging instance.
     * @param bool $force If true, the Messaging Service list is refreshed from the database.
     * @return CNabuMessagingServiceList Returns the list of Services. If none Service exists, the list is empty.
     */
    public function getServices($force = false)
    {
        if ($this->nb_messaging_service_list === null) {
            $this->nb_messaging_service_list = new CNabuMessagingServiceList();
        }

        if ($this->nb_messaging_service_list->isEmpty() || $force) {
            $this->nb_messaging_service_list->clear();
            $this->nb_messaging_service_list->merge(CNabuMessagingService::getAllMessagingServices($this));
            $this->nb_messaging_service_list->iterate(function($key, CNabuMessagingService $nb_service) {
                $nb_service->setMessaging($this);
                return true;
            });
        }

        return $this->nb_messaging_service_list;
    }

    /**
     * Get Templates assigned to this Messaging instance.
     * @param bool $force If true, the Messaging Template List is refreshed from the database.
     * @return CNabuMessagingTemplateList Returns the list of Templates. If noe Template exists, the list is empty.
     */
    public function getTemplates($force = false)
    {
        if ($this->nb_messaging_template_list === null) {
            $this->nb_messaging_template_list = new CNabuMessagingTemplateList();
        }

        if ($this->nb_messaging_template_list->isEmpty() || $force) {
            $this->nb_messaging_template_list->clear();
            $this->nb_messaging_template_list->merge(CNabuMessagingTemplate::getAllMessagingTemplates($this));
            $this->nb_messaging_template_list->iterate(function($key, CNabuMessagingTemplate $nb_template) {
                $nb_template->setMessaging($this);
                return true;
            });
        }

        return $this->nb_messaging_template_list;
    }

    /**
     * Gets a Template by Id.
     * @param mixed $nb_template A CNabuDataObject containing a field named nb_messaging_template_id or a valid Id.
     * @return CNabuMessagingTemplate|bool Returns the Messaging Template instance if exists or false if not.
     */
    public function getTemplate($nb_template)
    {
        $retval = false;

        if (is_numeric($nb_template_id = nb_getMixedValue($nb_template, NABU_MESSAGING_TEMPLATE_FIELD_ID))) {
            $retval = $this->nb_messaging_template_list->getItem($nb_template_id);
            if ($retval instanceof CNabuMessagingTemplate && $retval->getMessaging() === null) {
                $retval->setMessaging($this);
            }
        }

        return $retval;
    }

    /**
     * Gets a Template by Key.
     * @param string $key A string Key representing the Template as defined in the database storage.
     * @return CNabuMessagingTemplate|bool Returns the Messaging Template instance if exists or false if not.
     */
    public function getTemplateByKey(string $key)
    {
        $retval = false;

        if (is_string($key) && strlen($key) > 0) {
            $retval = $this->nb_messaging_template_list->getItem($key, CNabuMessagingTemplateList::INDEX_KEY);
        }

        return $retval;
    }

    /**
     * Overrides getTreeData method to add translations branch.
     * If $nb_language have a valid value, also adds a translation object
     * with current translation pointed by it.
     * @param int|CNabuDataObject $nb_language Instance or Id of the language to be used.
     * @param bool $dataonly Render only field values and ommit class control flags.
     * @return array Returns a multilevel associative array with all data.
     */
    public function getTreeData($nb_language = null, $dataonly = false)
    {
        $trdata = parent::getTreeData($nb_language, $dataonly);

        $trdata['languages'] = $this->getLanguages();
        $trdata['services'] = $this->getServices();
        $trdata['templates'] = $this->getTemplates();

        return $trdata;
    }

    /**
     * Overrides refresh method to add messaging subentities to be refreshed.
     * @param bool $force Forces to reload entities from the database storage.
     * @param bool $cascade Forces to reload child entities from the database storage.
     * @return bool Returns true if transations are empty or refreshed.
     */
    public function refresh(bool $force = false, bool $cascade = false) : bool
    {
        return parent::refresh($force, $cascade) &&
               (!$cascade || (
                   $this->getServices($force) &&
                   $this->getTemplates($force)
               ))
        ;
    }
}
