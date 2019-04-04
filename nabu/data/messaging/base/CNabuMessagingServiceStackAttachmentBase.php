<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2019/04/04 14:16:20 UTC
 * ===========================================================================
 * Copyright 2009-2011 Rafael Gutierrez Martinez
 * Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 * Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 * Copyright 2017-2019 nabu-3 Group
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
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Messaging Service Stack Attachment stored in the storage named
 * nb_messaging_service_stack_attachment.
 * @version 3.0.12 Surface
 * @package \nabu\data\messaging\base
 */
abstract class CNabuMessagingServiceStackAttachmentBase extends CNabuDBInternalObject
{
    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_messaging_service_stack_attachment An instance of CNabuMessagingServiceStackAttachmentBase or
     * another object descending from \nabu\data\CNabuDataObject which contains a field named
     * nb_messaging_service_stack_attachment_id, or a valid ID.
     */
    public function __construct($nb_messaging_service_stack_attachment = false)
    {
        if ($nb_messaging_service_stack_attachment) {
            $this->transferMixedValue($nb_messaging_service_stack_attachment, 'nb_messaging_service_stack_attachment_id');
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
        return 'nb_messaging_service_stack_attachment';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_messaging_service_stack_attachment_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_messaging_service_stack_attachment '
                   . "where nb_messaging_service_stack_attachment_id=%nb_messaging_service_stack_attachment_id\$d "
              )
            : null;
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_messaging_service_stack_attachment_id'
     * is the index, and each value is an instance of class CNabuMessagingServiceStackAttachmentBase.
     * @return mixed Returns and array with all items.
     */
    public static function getAllMessagingServiceStackAttachments()
    {
        return forward_static_call(
                array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_messaging_service_stack_attachment_id',
                'select * from nb_messaging_service_stack_attachment'
        );
    }

    /**
     * Gets a filtered list of Messaging Service Stack Attachment instances represented as an array. Params allows the
     * capability of select a subset of fields, order by concrete fields, or truncate the list by a number of rows
     * starting in an offset.
     * @throws \nabu\core\exceptions\ENabuCoreException Raises an exception if $fields or $order have invalid values.
     * @param string $q Query string to filter results using a context index.
     * @param string|array $fields List of fields to put in the results.
     * @param string|array $order List of fields to order the results. Each field can be suffixed with "ASC" or "DESC"
     * to determine the short order
     * @param int $offset Offset of first row in the results having the first row at offset 0.
     * @param int $num_items Number of continue rows to get as maximum in the results.
     * @return array Returns an array with all rows found using the criteria.
     */
    public static function getFilteredMessagingServiceStackAttachmentList($q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $fields_part = nb_prefixFieldList(CNabuMessagingServiceStackAttachmentBase::getStorageName(), $fields, false, true, '`');
        $order_part = nb_prefixFieldList(CNabuMessagingServiceStackAttachmentBase::getStorageName(), $fields, false, false, '`');
        
        if ($num_items !== 0) {
            $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
        } else {
            $limit_part = false;
        }
        
        $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
            "select " . ($fields_part ? $fields_part . ' ' : '* ')
            . 'from nb_messaging_service_stack_attachment '
            . ($order_part ? "order by $order_part " : '')
            . ($limit_part ? "limit $limit_part" : ''),
            array(
            )
        );
        
        return $nb_item_list;
    }

    /**
     * Get Messaging Service Stack Attachment Id attribute value
     * @return int Returns the Messaging Service Stack Attachment Id value
     */
    public function getId() : int
    {
        return $this->getValue('nb_messaging_service_stack_attachment_id');
    }

    /**
     * Sets the Messaging Service Stack Attachment Id attribute value.
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
        $this->setValue('nb_messaging_service_stack_attachment_id', $id);
        
        return $this;
    }

    /**
     * Get Messaging Service Stack Id attribute value
     * @return int Returns the Messaging Service Stack Id value
     */
    public function getMessagingServiceStackId() : int
    {
        return $this->getValue('nb_messaging_service_stack_id');
    }

    /**
     * Sets the Messaging Service Stack Id attribute value.
     * @param int $nb_messaging_service_stack_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setMessagingServiceStackId(int $nb_messaging_service_stack_id) : CNabuDataObject
    {
        if ($nb_messaging_service_stack_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_messaging_service_stack_id")
            );
        }
        $this->setValue('nb_messaging_service_stack_id', $nb_messaging_service_stack_id);
        
        return $this;
    }

    /**
     * Get Messaging Service Stack Attachment Mimetype attribute value
     * @return null|string Returns the Messaging Service Stack Attachment Mimetype value
     */
    public function getMimetype()
    {
        return $this->getValue('nb_messaging_service_stack_attachment_mimetype');
    }

    /**
     * Sets the Messaging Service Stack Attachment Mimetype attribute value.
     * @param string|null $mimetype New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setMimetype(string $mimetype = null) : CNabuDataObject
    {
        $this->setValue('nb_messaging_service_stack_attachment_mimetype', $mimetype);
        
        return $this;
    }

    /**
     * Get Messaging Service Stack Attachment Hash attribute value
     * @return null|string Returns the Messaging Service Stack Attachment Hash value
     */
    public function getHash()
    {
        return $this->getValue('nb_messaging_service_stack_attachment_hash');
    }

    /**
     * Sets the Messaging Service Stack Attachment Hash attribute value.
     * @param string|null $hash New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setHash(string $hash = null) : CNabuDataObject
    {
        $this->setValue('nb_messaging_service_stack_attachment_hash', $hash);
        
        return $this;
    }

    /**
     * Get Messaging Service Stack Attachment Filename attribute value
     * @return null|string Returns the Messaging Service Stack Attachment Filename value
     */
    public function getFilename()
    {
        return $this->getValue('nb_messaging_service_stack_attachment_filename');
    }

    /**
     * Sets the Messaging Service Stack Attachment Filename attribute value.
     * @param string|null $filename New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setFilename(string $filename = null) : CNabuDataObject
    {
        $this->setValue('nb_messaging_service_stack_attachment_filename', $filename);
        
        return $this;
    }
}
