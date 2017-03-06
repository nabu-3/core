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

namespace nabu\data\messaging;
use nabu\data\CNabuDataObject;
use nabu\data\messaging\base\CNabuMessagingBase;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\data\site
 */
class CNabuMessaging extends CNabuMessagingBase
{
    /** @var CNabuMessagingAccount $nb_messaging_account_list List of accounts of this instance. */
    private $nb_messaging_account_list = null;

    public function __construct($nb_messaging = false)
    {
        parent::__construct($nb_messaging);

        $this->nb_messaging_account_list = new CNabuMessagingAccountList();
    }

    /**
     * Get Accounts assigned to this Messaging instance.
     * @param bool $force If true, the Messaging Account list is refreshed from the database.
     * @return CNabuMessagingAccountList Returns the list of Accounts. If none Account exists, the list is empty.
     */
    public function getAccounts($force = false)
    {
        if ($this->nb_messaging_account_list === null) {
            $this->nb_messaging_account_list = new CNabuMessagingAccountList();
        }

        if ($this->nb_messaging_account_list->isEmpty() || $force) {
            $this->nb_messaging_account_list->clear();
            $this->nb_messaging_account_list->merge(CNabuMessagingAccount::getAllMessagingAccounts($this));
        }

        return $this->nb_messaging_account_list;
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
        $trdata['accounts'] = $this->getAccounts();

        return $trdata;
    }

    /**
     * Overrides refresh method to add messaging subentities to be refreshed.
     * @return bool Returns true if transations are empty or refreshed.
     */
    public function refresh()
    {
        error_log("////////=> Refresh");
        return parent::refresh() && $this->getAccounts();
    }
}
