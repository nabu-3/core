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
use nabu\core\exceptions\ENabuCoreException;
use nabu\data\CNabuDataObject;
use nabu\data\lang\CNabuLanguage;
use nabu\data\messaging\CNabuMessaging;
use nabu\data\messaging\CNabuMessagingService;
use nabu\data\messaging\CNabuMessagingTemplate;
use nabu\data\messaging\CNabuMessagingServiceList;
use nabu\data\security\CNabuUser;
use nabu\data\security\CNabuUserList;
use nabu\messaging\exceptions\ENabuMessagingException;
use nabu\messaging\interfaces\INabuMessagingModule;
use nabu\messaging\interfaces\INabuMessagingServiceInterface;
use nabu\messaging\interfaces\INabuMessagingTemplateRenderInterface;

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
    /** @var array Service Interfaces instantiated. */
    private $nb_service_interface_list = null;

    /**
     * Instantiates the Factory using a Messaging instance
     * @param CNabuMessaging $nb_messaging Messaging instance
     */
    public function __construct(CNabuMessaging $nb_messaging)
    {
        parent::__construct();
        $this->nb_messaging = $nb_messaging;
        $this->setValue('nb_messaging_id', $nb_messaging->getId());
    }

    /**
     * Discover the Service Interface.
     * @param CNabuMessagingService $nb_service A Messaging Service instance mapped to required Service Interface.
     * @return INabuMessagingServiceInterface If Service is mapped then returns a valid interface ready to use.
     * @throws ENabuMessagingException Raises an exception if the designated Service is not valid or applicable.
     */
    private function discoverServiceInterface(CNabuMessagingService $nb_service) : INabuMessagingServiceInterface
    {
        $retval = false;
        $nb_engine = CNabuEngine::getEngine();

        if (is_string($interface_name = $nb_service->getInterface())) {
            if (is_array($this->nb_service_interface_list) &&
                array_key_exists($interface_name, $this->nb_service_interface_list)
            ) {
                $retval = $this->nb_service_interface_list[$interface_name];
            } elseif (
                is_string($provider_key = $nb_service->getProvider()) &&
                count(list($vendor_key, $module_key) = preg_split("/:/", $provider_key)) === 2 &&
                ($nb_manager = $nb_engine->getProviderManager($vendor_key, $module_key)) instanceof INabuMessagingModule &&
                ($retval = $nb_manager->createServiceInterface($interface_name)) instanceof INabuMessagingServiceInterface
            ) {
                if (is_array($this->nb_service_interface_list)) {
                    $this->nb_service_interface_list[$interface_name] = $retval;
                } else {
                    $this->nb_service_interface_list = array($interface_name => $retval);
                }
                $retval->connect($nb_service);
            } else {
                throw new ENabuMessagingException(ENabuMessagingException::ERROR_INVALID_SERVICE_INSTANCE);
            }
        } else {
            throw new ENabuMessagingException(ENabuMessagingException::ERROR_INVALID_SERVICE_CLASS_NAME);
        }

        return $retval;
    }

    /**
     * Discover the Messaging Template instance beside a lazzy reference to it.
     * @param mixed $nb_template A Template instance, a CNabuDataObject containing a field named
     * nb_messaging_template_id or a valid Id.
     * @return CNabuMessagingTemplate Returns the Messaging Template instance discovered.
     * @throws ENabuMessagingException Raises an exception if the reference is not valid.
     */
    private function discoverTemplate($nb_template) : CNabuMessagingTemplate
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
            throw new ENabuMessagingException(
                ENabuMessagingException::ERROR_TEMPLATE_NOT_ALLOWED,
                array($nb_template->getId())
            );
        }

        return $nb_template;
    }

    /**
     * Discover the Language instance beside a lazzy reference to it.
     * @param mixed $nb_language A Language instance, a CNabuDataObject containing a field named nb_language_id,
     * or a valid Id.
     * @return CNabuLanguage Returns the Language instance discovered.
     * @throws ENabuCoreException Raises an exception if the reference is not valid.
     */
    private function discoverLanguage($nb_language) : CNabuLanguage
    {
        if (!($nb_language instanceof CNabuLanguage)) {
            $nb_language = new CNabuLanguage(nb_getMixedValue($nb_language, NABU_LANG_FIELD_ID));
        }

        if (!($nb_language instanceof CNabuLanguage) || $nb_language->isNew()) {
            throw new ENabuCoreException(ENabuCoreException::ERROR_LANGUAGE_REQUIRED);
        }

        return $nb_language;
    }

    /**
     * Prepares the subject and body of a message using a template.
     * @param CNabuMessagingTemplate $nb_template The Template instance to be used to render the message.
     * @param CNabuLanguage $nb_language The Language instance to get valid fields.
     * @param array|null $params An associative array with additional data for the template.
     * @return array Retuns an array of three cells, where the first is the subject, the second is the body in HTML
     * and the third the body as text.
     * @throws ENabuMessagingException Raises an exception if the designated template is not valid or applicable.
     */
    private function prepareMessageUsingTemplate(
        CNabuMessagingTemplate $nb_template,
        CNabuLanguage $nb_language,
        array $params = null
    ) : array
    {
        $nb_engine = CNabuEngine::getEngine();

        if (is_string($interface_name = $nb_template->getRenderInterface()) &&
            is_string($provider_key = $nb_template->getRenderProvider()) &&
            count(list($vendor_key, $module_key) = preg_split("/:/", $provider_key)) === 2 &&
            ($nb_manager = $nb_engine->getProviderManager($vendor_key, $module_key)) instanceof INabuMessagingModule &&
            ($nb_interface = $nb_manager->createTemplateRenderInterface($interface_name)) instanceof INabuMessagingTemplateRenderInterface
        ) {
            $nb_interface->setTemplate($nb_template);
            $nb_interface->setLanguage($nb_language);
            $subject = $nb_interface->createSubject($params);
            $body_html = $nb_interface->createBodyHTML($params);
            $body_text = $nb_interface->createBodyText($params);

            return array($subject, $body_html, $body_text);
        } else {
            throw new ENabuMessagingException(ENabuMessagingException::ERROR_INVALID_TEMPLATE_RENDER_INSTANCE);
        }
    }

    /**
     * Post a Message in the Messaging queue using a predefined template.
     * @param mixed $nb_template A Template instance, a child of CNabuDataObject containing a field named
     * nb_messaging_template_id or a valid Id.
     * @param mixed $nb_language A Language instance, a child of CNabuDataObject containing a field named
     * nb_language_id or a valid Id.
     * @param CNabuUser|CNabuUserList|string|array $to A User or User List instance, an inbox as string or an array
     * of strings each one an inbox, to send TO.
     * @param CNabuUser|CNabuUserList|string|array $cc A User or User List instance, an inbox as string or an array
     * of strings each one an inbox, to send in Carbon Copy.
     * @param CNabuUser|CNabuUserList|string|array $bcc A User or User List instance, an inbox as string or an array
     * of strings each one an inbox, to send in Blind Carbon Copy.
     * @param array|null $params An associative array with additional data for the template.
     * @param array|null $attachments An array of attached files to send in the message.
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
        $nb_template = $this->discoverTemplate($nb_template);
        $nb_language = $this->discoverLanguage($nb_language);
        list($subject, $body_html, $body_text) = $this->prepareMessageUsingTemplate($nb_template, $nb_language, $params);

        return $this->postMessage($nb_template->getServices(), $to, $cc, $bcc, $subject, $body_html, $body_text, $attachments);
    }

    /**
     * Send a Message directly using a predefined template.
     * @param mixed $nb_template A Template instance, a child of CNabuDataObject containing a field named
     * nb_messaging_template_id or a valid Id.
     * @param CNabuUser|CNabuUserList|string|array $to A User or User List instance, an inbox as string or an array
     * of strings each one an inbox, to send TO.
     * @param CNabuUser|CNabuUserList|string|array $cc A User or User List instance, an inbox as string or an array
     * of strings each one an inbox, to send in Carbon Copy.
     * @param CNabuUser|CNabuUserList|string|array $bcc A User or User List instance, an inbox as string or an array
     * of strings each one an inbox, to send in Blind Carbon Copy.
     * @param array|null $params An associative array with additional data for the template.
     * @param array|null $attachments An array of attached files to send in the message.
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
        $nb_template = $this->discoverTemplate($nb_template);
        $nb_language = $this->discoverLanguage($nb_language);
        list($subject, $body_html, $body_text) = $this->prepareMessageUsingTemplate($nb_template, $params);

        return $this->sendMessage($nb_template->getServices(), $to, $bc, $bcc, $subject, $body_html, $body_text, $attachments);
    }

    /**
     * Post a Message in the Messaging queue.
     * @param CNabuMessagingServiceList $nb_service_list List of Services to be used to post the message.
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
     * @return bool Returns true if the message was posted.
     * @throws ENabuMessagingException Raises an exception if something is wrong.
     */
    public function postMessage(CNabuMessagingServiceList $nb_service_list, $to, $cc, $bcc, $subject, $body_html, $body_text, $attachments)
    {
        $retval = false;

        $nb_service_list->iterate(
            function($key, CNabuMessagingService $nb_service)
            use ($retval, $to, $cc, $bcc, $subject, $body_html, $body_text, $attachments)
            {
                $nb_interface = $this->discoverServiceInterface($nb_service);
                $nb_interface->post($to, $cc, $bcc, $subject, $body_html, $body_text, $attachments);
                return true;
            }
        );

        return $retval;
    }

    /**
     * Send a Message directly.
     * @param CNabuMessagingServiceList $nb_service_list List of Services to be used to post the message.
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
     * @return bool Returns true if the message was sent.
     * @throws ENabuMessagingException Raises an exception if something is wrong.
     */
    public function sendMessage(CNabuMessagingServiceList $nb_service_list, $to, $cc, $bcc, $subject, $body_html, $body_text, $attachments)
    {
        $retval = false;

        $nb_service_list->iterate(function($key, CNabuMessagingService $nb_service) use($retval)
        {
            $nb_interface = $this->prepareServiceInterface($nb_service);
            $nb_interface->send($to, $cc, $bcc, $subject, $body_html, $body_text, $attachments);
            return true;
        });

        return $retval;
    }
}
