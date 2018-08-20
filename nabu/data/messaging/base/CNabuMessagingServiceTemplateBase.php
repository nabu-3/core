<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2018/08/20 09:23:57 UTC
 * ===========================================================================
 * Copyright 2009-2011 Rafael Gutierrez Martinez
 * Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 * Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 * Copyright 2017-2018 nabu-3 Group
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
use nabu\data\messaging\traits\TNabuMessagingServiceChild;

/**
 * Class to manage the entity Messaging Service Template stored in the storage named nb_messaging_service_template.
 * @version 3.0.12 Surface
 * @package \nabu\data\messaging\base
 */
abstract class CNabuMessagingServiceTemplateBase extends CNabuDBInternalObject
{
    use TNabuMessagingServiceChild;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_messaging_service An instance of CNabuMessagingServiceTemplateBase or another object descending
     * from \nabu\data\CNabuDataObject which contains a field named nb_messaging_service_id, or a valid ID.
     * @param mixed $nb_messaging_template An instance of CNabuMessagingServiceTemplateBase or another object
     * descending from \nabu\data\CNabuDataObject which contains a field named nb_messaging_template_id, or a valid ID.
     */
    public function __construct($nb_messaging_service = false, $nb_messaging_template = false)
    {
        if ($nb_messaging_service) {
            $this->transferMixedValue($nb_messaging_service, 'nb_messaging_service_id');
        }
        
        if ($nb_messaging_template) {
            $this->transferMixedValue($nb_messaging_template, 'nb_messaging_template_id');
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
        return 'nb_messaging_service_template';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_messaging_service_id') && $this->isValueNumeric('nb_messaging_template_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_messaging_service_template '
                   . "where nb_messaging_service_id=%nb_messaging_service_id\$d "
                     . "and nb_messaging_template_id=%nb_messaging_template_id\$d "
              )
            : null;
    }

    /**
     * Gets a filtered list of Messaging Service Template instances represented as an array. Params allows the
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
    public static function getFilteredMessagingServiceTemplateList($q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $fields_part = nb_prefixFieldList(CNabuMessagingServiceTemplateBase::getStorageName(), $fields, false, true, '`');
        $order_part = nb_prefixFieldList(CNabuMessagingServiceTemplateBase::getStorageName(), $fields, false, false, '`');
        
        if ($num_items !== 0) {
            $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
        } else {
            $limit_part = false;
        }
        
        $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
            "select " . ($fields_part ? $fields_part . ' ' : '* ')
            . 'from nb_messaging_service_template '
            . ($order_part ? "order by $order_part " : '')
            . ($limit_part ? "limit $limit_part" : ''),
            array(
            )
        );
        
        return $nb_item_list;
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
}
