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

namespace nabu\messaging;
use nabu\data\CNabuDataObject;
use nabu\data\messaging\CNabuMessaging;
use nabu\data\security\CNabuUser;
use nabu\data\security\CNabuUserList;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\messaging
 */
class CNabuMessagingFactory extends CNabuDataObject
{
    /** @var CNabuMessaging $nb_messaging Messaging instance used to post messages */
    private $nb_messaging = null;

    /**
     * Instantiates the Factory using a Messaging instance
     * @param CNabuMessaging $nb_messaging Messaging instance
     */
    public function __construct(CNabuMessaging $nb_messaging)
    {
        $this->nb_messaging = $nb_messaging;
    }

    /**
     * Post a Message using a predefined template.
     * @param mixed $nb_template A Template instance, a child of CNabuDataObject containing a field named
     * nb_messaging_template_id or a valid Id.
     * @param CNabuUser|CNabuUserList|string|array $to A User or User List instance, an email as string or an array
     * of strings each one an email, to send TO.
     * @param CNabuUser|CNabuUserList|string|array $cc A User or User List instance, an email as string or an array
     * of strings each one an email, to send in Carbon Copy.
     * @param CNabuUser|CNabuUserList|string|array $bcc A User or User List instance, an email as string or an array
     * of strings each one an email, to send in Blind Carbon Copy.
     * @param array $params An associative array with additional data for the template.
     * @param array $attachments An array of attached files to send in the message.
     * @return bool Returns true if the message was posted.
     */
    public function postTemplateMessage($nb_template, $to = null, $cc = null, $bcc = null, array $params = null, array $attachments = null) : bool
    {
        return true;
    }
}
