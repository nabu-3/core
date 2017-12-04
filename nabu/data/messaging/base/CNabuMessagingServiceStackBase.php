<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/12/04 14:48:32 UTC
 * ===========================================================================
 * Copyright 2009-2011 Rafael Gutierrez Martinez
 * Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 * Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 * Copyright 2017 nabu-3 Group
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace nabu\data\messaging\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\CNabuDataObject;
use \nabu\data\messaging\CNabuMessaging;
use \nabu\data\messaging\CNabuMessagingServiceStackList;
use \nabu\data\messaging\traits\TNabuMessagingChild;
use \nabu\db\CNabuDBInternalObject;
use nabu\data\messaging\traits\TNabuMessagingServiceChild;

/**
 * Class to manage the entity Messaging Service Stack stored in the storage named nb_messaging_service_stack.
 * @version 3.0.12 Surface
 * @package \nabu\data\messaging\base
 */
abstract class CNabuMessagingServiceStackBase extends CNabuDBInternalObject
{
    use TNabuMessagingChild;
    use TNabuMessagingServiceChild;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_messaging_service_stack An instance of CNabuMessagingServiceStackBase or another object
     * descending from \nabu\data\CNabuDataObject which contains a field named nb_messaging_service_stack_id, or a
     * valid ID.
     */
    public function __construct($nb_messaging_service_stack = false)
    {
        if ($nb_messaging_service_stack) {
            $this->transferMixedValue($nb_messaging_service_stack, 'nb_messaging_service_stack_id');
        }
        
        parent::__construct();
    }

    /**
     * Get the file name and path where is stored the descriptor in JSON format.
     * @return string Return the file name with the full path
     */
    public static function getStorageDescriptorPath()
    {
        return preg_replace('/.php$/', '.json', __FILE__);
    }

    /**
     * Get the table name represented by this class
     * @return string Return the table name
     */
    public static function getStorageName()
    {
        return 'nb_messaging_service_stack';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_messaging_service_stack_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_messaging_service_stack '
                   . "where nb_messaging_service_stack_id=%nb_messaging_service_stack_id\$d "
              )
            : null;
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_messaging_service_stack_id' is the
     * index, and each value is an instance of class CNabuMessagingServiceStackBase.
     * @param CNabuMessaging $nb_messaging The CNabuMessaging instance of the Messaging that owns the Messaging Service
     * Stack List
     * @return mixed Returns and array with all items.
     */
    public static function getAllMessagingServiceStacks(CNabuMessaging $nb_messaging)
    {
        $nb_messaging_id = nb_getMixedValue($nb_messaging, 'nb_messaging_id');
        if (is_numeric($nb_messaging_id)) {
            $retval = forward_static_call(
            array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_messaging_service_stack_id',
                'select * '
                . 'from nb_messaging_service_stack '
               . 'where nb_messaging_id=%messaging_id$d',
                array(
                    'messaging_id' => $nb_messaging_id
                ),
                $nb_messaging
            );
        } else {
            $retval = new CNabuMessagingServiceStackList();
        }
        
        return $retval;
    }

    /**
     * Gets a filtered list of Messaging Service Stack instances represented as an array. Params allows the capability
     * of select a subset of fields, order by concrete fields, or truncate the list by a number of rows starting in an
     * offset.
     * @throws \nabu\core\exceptions\ENabuCoreException Raises an exception if $fields or $order have invalid values.
     * @param mixed $nb_messaging Messaging instance, object containing a Messaging Id field or an Id.
     * @param mixed $nb_messaging_service Messaging Service instance, object containing a Messaging Service Id field or
     * an Id.
     * @param string $q Query string to filter results using a context index.
     * @param string|array $fields List of fields to put in the results.
     * @param string|array $order List of fields to order the results. Each field can be suffixed with "ASC" or "DESC"
     * to determine the short order
     * @param int $offset Offset of first row in the results having the first row at offset 0.
     * @param int $num_items Number of continue rows to get as maximum in the results.
     * @return array Returns an array with all rows found using the criteria.
     */
    public static function getFilteredMessagingServiceStackList($nb_messaging = null, $nb_messaging_service = null, $q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $nb_messaging_id = nb_getMixedValue($nb_customer, NABU_MESSAGING_FIELD_ID);
        if (is_numeric($nb_messaging_id)) {
            $fields_part = nb_prefixFieldList(CNabuMessagingServiceStackBase::getStorageName(), $fields, false, true, '`');
            $order_part = nb_prefixFieldList(CNabuMessagingServiceStackBase::getStorageName(), $fields, false, false, '`');
        
            if ($num_items !== 0) {
                $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
            } else {
                $limit_part = false;
            }
        
            $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
                "select " . ($fields_part ? $fields_part . ' ' : '* ')
                . 'from nb_messaging_service_stack '
               . 'where ' . NABU_MESSAGING_FIELD_ID . '=%messaging_id$d '
                . ($order_part ? "order by $order_part " : '')
                . ($limit_part ? "limit $limit_part" : ''),
                array(
                    'messaging_id' => $nb_messaging_id
                )
            );
        } else {
            $nb_item_list = null;
        }
        
        return $nb_item_list;
    }

    /**
     * Get Messaging Service Stack Id attribute value
     * @return int Returns the Messaging Service Stack Id value
     */
    public function getId() : int
    {
        return $this->getValue('nb_messaging_service_stack_id');
    }

    /**
     * Sets the Messaging Service Stack Id attribute value.
     * @param int $id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setId(int $id) : CNabuDataObject
    {
        if ($id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$id")
            );
        }
        $this->setValue('nb_messaging_service_stack_id', $id);
        
        return $this;
    }

    /**
     * Get Messaging Id attribute value
     * @return int Returns the Messaging Id value
     */
    public function getMessagingId() : int
    {
        return $this->getValue('nb_messaging_id');
    }

    /**
     * Sets the Messaging Id attribute value.
     * @param int $nb_messaging_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setMessagingId(int $nb_messaging_id) : CNabuDataObject
    {
        if ($nb_messaging_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_messaging_id")
            );
        }
        $this->setValue('nb_messaging_id', $nb_messaging_id);
        
        return $this;
    }

    /**
     * Get Messaging Service Id attribute value
     * @return int Returns the Messaging Service Id value
     */
    public function getMessagingServiceId() : int
    {
        return $this->getValue('nb_messaging_service_id');
    }

    /**
     * Sets the Messaging Service Id attribute value.
     * @param int $nb_messaging_service_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setMessagingServiceId(int $nb_messaging_service_id) : CNabuDataObject
    {
        if ($nb_messaging_service_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_messaging_service_id")
            );
        }
        $this->setValue('nb_messaging_service_id', $nb_messaging_service_id);
        
        return $this;
    }

    /**
     * Get Messaging Template Id attribute value
     * @return int Returns the Messaging Template Id value
     */
    public function getMessagingTemplateId() : int
    {
        return $this->getValue('nb_messaging_template_id');
    }

    /**
     * Sets the Messaging Template Id attribute value.
     * @param int $nb_messaging_template_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setMessagingTemplateId(int $nb_messaging_template_id) : CNabuDataObject
    {
        if ($nb_messaging_template_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_messaging_template_id")
            );
        }
        $this->setValue('nb_messaging_template_id', $nb_messaging_template_id);
        
        return $this;
    }

    /**
     * Get Language Id attribute value
     * @return int Returns the Language Id value
     */
    public function getLanguageId() : int
    {
        return $this->getValue('nb_language_id');
    }

    /**
     * Sets the Language Id attribute value.
     * @param int $nb_language_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setLanguageId(int $nb_language_id) : CNabuDataObject
    {
        if ($nb_language_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_language_id")
            );
        }
        $this->setValue('nb_language_id', $nb_language_id);
        
        return $this;
    }

    /**
     * Get Messaging Service Stack Status attribute value
     * @return string Returns the Messaging Service Stack Status value
     */
    public function getStatus() : string
    {
        return $this->getValue('nb_messaging_service_stack_status');
    }

    /**
     * Sets the Messaging Service Stack Status attribute value.
     * @param string $status New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setStatus(string $status = "P") : CNabuDataObject
    {
        if ($status === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$status")
            );
        }
        $this->setValue('nb_messaging_service_stack_status', $status);
        
        return $this;
    }

    /**
     * Get Messaging Service Stack P2p From Internal Id attribute value
     * @return null|int Returns the Messaging Service Stack P2p From Internal Id value
     */
    public function getP2pFromInternalId()
    {
        return $this->getValue('nb_messaging_service_stack_p2p_from_internal_id');
    }

    /**
     * Sets the Messaging Service Stack P2p From Internal Id attribute value.
     * @param int|null $p2p_from_internal_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setP2pFromInternalId(int $p2p_from_internal_id = null) : CNabuDataObject
    {
        $this->setValue('nb_messaging_service_stack_p2p_from_internal_id', $p2p_from_internal_id);
        
        return $this;
    }

    /**
     * Get Messaging Service Stack P2p To Internal Id attribute value
     * @return null|int Returns the Messaging Service Stack P2p To Internal Id value
     */
    public function getP2pToInternalId()
    {
        return $this->getValue('nb_messaging_service_stack_p2p_to_internal_id');
    }

    /**
     * Sets the Messaging Service Stack P2p To Internal Id attribute value.
     * @param int|null $p2p_to_internal_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setP2pToInternalId(int $p2p_to_internal_id = null) : CNabuDataObject
    {
        $this->setValue('nb_messaging_service_stack_p2p_to_internal_id', $p2p_to_internal_id);
        
        return $this;
    }

    /**
     * Get Messaging Service Stack Creation Datetime attribute value
     * @return mixed Returns the Messaging Service Stack Creation Datetime value
     */
    public function getCreationDatetime()
    {
        return $this->getValue('nb_messaging_service_stack_creation_datetime');
    }

    /**
     * Sets the Messaging Service Stack Creation Datetime attribute value.
     * @param mixed $creation_datetime New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCreationDatetime($creation_datetime) : CNabuDataObject
    {
        $this->setValue('nb_messaging_service_stack_creation_datetime', $creation_datetime);
        
        return $this;
    }

    /**
     * Get Messaging Service Stack Sent Datetime attribute value
     * @return mixed Returns the Messaging Service Stack Sent Datetime value
     */
    public function getSentDatetime()
    {
        return $this->getValue('nb_messaging_service_stack_sent_datetime');
    }

    /**
     * Sets the Messaging Service Stack Sent Datetime attribute value.
     * @param mixed $sent_datetime New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setSentDatetime($sent_datetime) : CNabuDataObject
    {
        $this->setValue('nb_messaging_service_stack_sent_datetime', $sent_datetime);
        
        return $this;
    }

    /**
     * Get Messaging Service Stack Response Code attribute value
     * @return null|int Returns the Messaging Service Stack Response Code value
     */
    public function getResponseCode()
    {
        return $this->getValue('nb_messaging_service_stack_response_code');
    }

    /**
     * Sets the Messaging Service Stack Response Code attribute value.
     * @param int|null $response_code New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setResponseCode(int $response_code = null) : CNabuDataObject
    {
        $this->setValue('nb_messaging_service_stack_response_code', $response_code);
        
        return $this;
    }

    /**
     * Get Messaging Service Stack Response Message attribute value
     * @return null|string Returns the Messaging Service Stack Response Message value
     */
    public function getResponseMessage()
    {
        return $this->getValue('nb_messaging_service_stack_response_message');
    }

    /**
     * Sets the Messaging Service Stack Response Message attribute value.
     * @param string|null $response_message New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setResponseMessage(string $response_message = null) : CNabuDataObject
    {
        $this->setValue('nb_messaging_service_stack_response_message', $response_message);
        
        return $this;
    }

    /**
     * Get Messaging Service Stack Target attribute value
     * @return null|string Returns the Messaging Service Stack Target value
     */
    public function getTarget()
    {
        return $this->getValue('nb_messaging_service_stack_target');
    }

    /**
     * Sets the Messaging Service Stack Target attribute value.
     * @param string|null $target New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setTarget(string $target = null) : CNabuDataObject
    {
        $this->setValue('nb_messaging_service_stack_target', $target);
        
        return $this;
    }

    /**
     * Get Messaging Service Stack Params attribute value
     * @return null|string Returns the Messaging Service Stack Params value
     */
    public function getParams()
    {
        return $this->getValue('nb_messaging_service_stack_params');
    }

    /**
     * Sets the Messaging Service Stack Params attribute value.
     * @param string|null $params New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setParams(string $params = null) : CNabuDataObject
    {
        $this->setValue('nb_messaging_service_stack_params', $params);
        
        return $this;
    }

    /**
     * Get Messaging Service Stack Subject attribute value
     * @return null|string Returns the Messaging Service Stack Subject value
     */
    public function getSubject()
    {
        return $this->getValue('nb_messaging_service_stack_subject');
    }

    /**
     * Sets the Messaging Service Stack Subject attribute value.
     * @param string|null $subject New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setSubject(string $subject = null) : CNabuDataObject
    {
        $this->setValue('nb_messaging_service_stack_subject', $subject);
        
        return $this;
    }

    /**
     * Get Messaging Service Stack Body Html attribute value
     * @return null|string Returns the Messaging Service Stack Body Html value
     */
    public function getBodyHtml()
    {
        return $this->getValue('nb_messaging_service_stack_body_html');
    }

    /**
     * Sets the Messaging Service Stack Body Html attribute value.
     * @param string|null $body_html New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setBodyHtml(string $body_html = null) : CNabuDataObject
    {
        $this->setValue('nb_messaging_service_stack_body_html', $body_html);
        
        return $this;
    }

    /**
     * Get Messaging Service Stack Body Text attribute value
     * @return null|string Returns the Messaging Service Stack Body Text value
     */
    public function getBodyText()
    {
        return $this->getValue('nb_messaging_service_stack_body_text');
    }

    /**
     * Sets the Messaging Service Stack Body Text attribute value.
     * @param string|null $body_text New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setBodyText(string $body_text = null) : CNabuDataObject
    {
        $this->setValue('nb_messaging_service_stack_body_text', $body_text);
        
        return $this;
    }
}
