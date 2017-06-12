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

namespace nabu\messaging\interfaces;
use nabu\data\messaging\CNabuMessagingService;
use nabu\data\security\CNabuUser;
use nabu\data\security\CNabuUserList;
use nabu\provider\interfaces\INabuProviderInterface;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\messaging\interfaces
 */
interface INabuMessagingServiceInterface extends INabuProviderInterface
{
    /**
     * Open the connection of this interface.
     * @param CNabuMessagingService $nb_messaging_service Messaging Service instance with connection params.
     * @return bool Returns true if the connection is made.
     */
    public function connect(CNabuMessagingService $nb_messaging_service);
    /**
     * Sends directly a message to one inbox or a list of them.
     * @param CNabuUser|CNabuUserList|string|array $to A User or User List instance, an inbox as string or an array
     * of strings each one an inbox, to send TO.
     * @param CNabuUser|CNabuUserList|string|array $cc A User or User List instance, an inbox as string or an array
     * of strings each one an inbox, to send in Carbon Copy.
     * @param CNabuUser|CNabuUserList|string|array $bcc A User or User List instance, an inbox as string or an array
     * of strings each one an inbox, to send in Blind Carbon Copy.
     * @param string $subject Subject of the message if any.
     * @param string $body_html Body of the message in HTML format.
     * @param string $body_text Body of the message in text format.
     * @param array $attachments An array of attached files to send in the message.
     * @return int Returns the result integer code.
     */
    public function send($to, $cc, $bcc, $subject, $body_html, $body_text, $attachments) : int;
    /**
     * Sends directly a message to one inbox or a list of them.
     * @param CNabuUser|CNabuUserList|string|array $to A User or User List instance, an inbox as string or an array
     * of strings each one an inbox, to send TO.
     * @param CNabuUser|CNabuUserList|string|array $cc A User or User List instance, an inbox as string or an array
     * of strings each one an inbox, to send in Carbon Copy.
     * @param CNabuUser|CNabuUserList|string|array $bcc A User or User List instance, an inbox as string or an array
     * of strings each one an inbox, to send in Blind Carbon Copy.
     * @param string $subject Subject of the message if any.
     * @param string $body_html Body of the message in HTML format.
     * @param string $body_text Body of the message in text format.
     * @param array $attachments An array of attached files to send in the message.
     * @return int Returns the result integer code.
     */
    public function post($to, $cc, $bcc, $subject, $body_html, $body_text, $attachments) : int;
    /**
     * Close the connection of this interface.
     */
    public function disconnect();
}
