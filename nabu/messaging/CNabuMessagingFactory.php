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
use nabu\core\CNabuEngine;
use nabu\data\CNabuDataObject;
use nabu\data\messaging\CNabuMessaging;
use nabu\data\messaging\CNabuMessagingTemplate;
use nabu\data\security\CNabuUser;
use nabu\data\security\CNabuUserList;
use nabu\messaging\exceptions\ENabuMessagingException;
use nabu\messaging\interfaces\INabuMessagingModule;
use nabu\messaging\interfaces\INabuMessagingTemplateRenderInterface;
use nabu\provider\interfaces\INabuProviderManager;

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
        $this->setValue('nb_messaging_id', $nb_messaging->getId());
    }

    /**
     * Prepares the subject and body of a message using a template.
     * @param CNabuMessagingTemplate|CNabuDataObject|string|int $nb_template A Template instance,
     * a child of CNabuDataObject containing a field named nb_messaging_template_id or a valid Id.
     * @param array $params An associative array with additional data for the template.
     * @return array Retuns an array of two cells, where the first is the subject and the second is the body.
     * @throws ENabuMessagingException Raises an exception if the designated template is not valid or applicable.
     */
    private function prepareMessageUsingTemplate($nb_template, array $params)
    {
        if (!($nb_template instanceof CNabuMessagingTemplate)) {
            if (is_numeric($nb_template_id = nb_getMixedValue($nb_template, NABU_MESSAGING_TEMPLATE_FIELD_ID))) {
                $nb_template = $this->nb_messaging->getTemplate($nb_template_id);
            } elseif (is_string($nb_template_id)) {
                $nb_template = $this->nb_messaging->getTemplateByKey($nb_template_id);
            }
        }

        if (!($nb_template instanceof CNabuMessagingTemplate)) {
            throw new ENabuMessagingException(ENabuMessagingException::ERROR_INVALID_TEMPLATE);
        } elseif ($nb_template->getMessagingId() !== $this->nb_messaging->getId()) {
            throw new ENabuMessagingException(ENabuMessagingException::ERROR_TEMPLATE_NOT_ALLOWED);
        }

        $nb_engine = CNabuEngine::getEngine();

        if (is_string($interface_name = $nb_template->getRenderInterface()) &&
            is_string($provider_key = $nb_template->getRenderProvider()) &&
            count(list($vendor_key, $module_key) = preg_split("/:/", $provider_key)) === 2 &&
            ($nb_manager = $nb_engine->getProviderManager($vendor_key, $module_key)) instanceof INabuMessagingModule &&
            ($nb_interface = $nb_manager->createTemplateRenderInterface($interface_name)) instanceof INabuMessagingTemplateRenderInterface
        ) {
            $nb_interface->setTemplate($nb_template);
            $subject = $nb_interface->createSubject($params);
            $body = $nb_interface->createBody($params);

            error_log($subject);
            error_log($body);

            return array($subject, $body);
        } else {
            throw new ENabuMessagingException(ENabuMessagingException::ERROR_INVALID_TEMPLATE_RENDER_INSTANCE);
        }
    }

    /**
     * Post a Message in the Messaging queue using a predefined template.
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
     * @throws ENabuMessagingException Raises an exception if something is wrong.
     */
    public function postTemplateMessage(
        $nb_template,
        $nb_language,
        $to = null,
        $cc = null,
        $bcc = null,
        array $params = null,
        array $attachments = null
    ) : bool
    {
        error_log(__METHOD__);
        list($subject, $body) = $this->prepareMessageUsingTemplate($nb_template, $params);

        return $this->postMessage($to, $bc, $bcc, $subject, $body, $attachments);
    }

    /**
     * Send a Message directly using a predefined template.
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
     * @throws ENabuMessagingException Raises an exception if something is wrong.
     */
    public function sendTemplateMessage(
        $nb_template,
        $to = null,
        $cc = null,
        $bcc = null,
        array $params = null,
        array $attachments = null
    ) : bool
    {
        list($subject, $body) = $this->prepareMessageUsingTemplate($nb_template, $params);

        return $this->sendMessage($to, $bc, $bcc, $subject, $body, $attachments);
    }

    /**
     * Post a Message in the Messaging queue.
     * @param CNabuUser|CNabuUserList|string|array $to A User or User List instance, an email as string or an array
     * of strings each one an email, to send TO.
     * @param CNabuUser|CNabuUserList|string|array $cc A User or User List instance, an email as string or an array
     * of strings each one an email, to send in Carbon Copy.
     * @param CNabuUser|CNabuUserList|string|array $bcc A User or User List instance, an email as string or an array
     * of strings each one an email, to send in Blind Carbon Copy.
     * @param string $subject Subject of the message if any.
     * @param string $body Body of the message.
     * @param array $attachments An array of attached files to send in the message.
     * @return bool Returns true if the message was posted.
     * @throws ENabuMessagingException Raises an exception if something is wrong.
     */
    public function postMessage($to, $cc, $bcc, $subject, $body, $attachments)
    {
        return $this->sendMessage($to, $cc, $bcc, $subject, $body, $attachments);
    }

    /**
     * Send a Message directly.
     * @param CNabuUser|CNabuUserList|string|array $to A User or User List instance, an email as string or an array
     * of strings each one an email, to send TO.
     * @param CNabuUser|CNabuUserList|string|array $cc A User or User List instance, an email as string or an array
     * of strings each one an email, to send in Carbon Copy.
     * @param CNabuUser|CNabuUserList|string|array $bcc A User or User List instance, an email as string or an array
     * of strings each one an email, to send in Blind Carbon Copy.
     * @param string $subject Subject of the message if any.
     * @param string $body Body of the message.
     * @param array $attachments An array of attached files to send in the message.
     * @return bool Returns true if the message was posted.
     * @throws ENabuMessagingException Raises an exception if something is wrong.
     */
    public function sendMessage($to, $cc, $bcc, $subject, $body, $attachments)
    {
        return true;
    }
}
