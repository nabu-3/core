<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2018/02/20 17:26:53 UTC
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

namespace nabu\data\icontact\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\core\interfaces\INabuHashed;
use \nabu\core\traits\TNabuHashed;
use \nabu\data\CNabuDataObject;
use \nabu\data\icontact\CNabuIContactProspectAttachment;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity iContact Prospect Attachment stored in the storage named nb_icontact_prospect_attachment.
 * @version 3.0.12 Surface
 * @package \nabu\data\icontact\base
 */
abstract class CNabuIContactProspectAttachmentBase extends CNabuDBInternalObject implements INabuHashed
{
    use TNabuHashed;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_icontact_prospect_attachment An instance of CNabuIContactProspectAttachmentBase or another
     * object descending from \nabu\data\CNabuDataObject which contains a field named
     * nb_icontact_prospect_attachment_id, or a valid ID.
     */
    public function __construct($nb_icontact_prospect_attachment = false)
    {
        if ($nb_icontact_prospect_attachment) {
            $this->transferMixedValue($nb_icontact_prospect_attachment, 'nb_icontact_prospect_attachment_id');
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
        return 'nb_icontact_prospect_attachment';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_icontact_prospect_attachment_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_icontact_prospect_attachment '
                   . "where nb_icontact_prospect_attachment_id=%nb_icontact_prospect_attachment_id\$d "
              )
            : null;
    }

    /**
     * Find an instance identified by nb_icontact_prospect_attachment_hash field.
     * @param string $hash Hash to search
     * @return CNabuDataObject Returns a valid instance if exists or null if not.
     */
    public static function findByHash(string $hash)
    {
        return CNabuIContactProspectAttachment::buildObjectFromSQL(
                'select * '
                . 'from nb_icontact_prospect_attachment '
               . "where nb_icontact_prospect_attachment_hash='%hash\$s'",
                array(
                    'hash' => $hash
                )
        );
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_icontact_prospect_attachment_id' is the
     * index, and each value is an instance of class CNabuIContactProspectAttachmentBase.
     * @return mixed Returns and array with all items.
     */
    public static function getAlliContactProspectAttachments()
    {
        return forward_static_call(
                array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_icontact_prospect_attachment_id',
                'select * from nb_icontact_prospect_attachment'
        );
    }

    /**
     * Gets a filtered list of iContact Prospect Attachment instances represented as an array. Params allows the
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
    public static function getFilterediContactProspectAttachmentList($q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $fields_part = nb_prefixFieldList(CNabuIContactProspectAttachmentBase::getStorageName(), $fields, false, true, '`');
        $order_part = nb_prefixFieldList(CNabuIContactProspectAttachmentBase::getStorageName(), $fields, false, false, '`');
        
        if ($num_items !== 0) {
            $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
        } else {
            $limit_part = false;
        }
        
        $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
            "select " . ($fields_part ? $fields_part . ' ' : '* ')
            . 'from nb_icontact_prospect_attachment '
            . ($order_part ? "order by $order_part " : '')
            . ($limit_part ? "limit $limit_part" : ''),
            array(
            )
        );
        
        return $nb_item_list;
    }

    /**
     * Get Icontact Prospect Attachment Id attribute value
     * @return int Returns the Icontact Prospect Attachment Id value
     */
    public function getId() : int
    {
        return $this->getValue('nb_icontact_prospect_attachment_id');
    }

    /**
     * Sets the Icontact Prospect Attachment Id attribute value.
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
        $this->setValue('nb_icontact_prospect_attachment_id', $id);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Id attribute value
     * @return null|int Returns the Icontact Prospect Id value
     */
    public function getIcontactProspectId()
    {
        return $this->getValue('nb_icontact_prospect_id');
    }

    /**
     * Sets the Icontact Prospect Id attribute value.
     * @param int|null $nb_icontact_prospect_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setIcontactProspectId(int $nb_icontact_prospect_id = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_id', $nb_icontact_prospect_id);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Diary Id attribute value
     * @return null|int Returns the Icontact Prospect Diary Id value
     */
    public function getIcontactProspectDiaryId()
    {
        return $this->getValue('nb_icontact_prospect_diary_id');
    }

    /**
     * Sets the Icontact Prospect Diary Id attribute value.
     * @param int|null $nb_icontact_prospect_diary_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setIcontactProspectDiaryId(int $nb_icontact_prospect_diary_id = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_diary_id', $nb_icontact_prospect_diary_id);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Attachment Hash attribute value
     * @return null|string Returns the Icontact Prospect Attachment Hash value
     */
    public function getHash()
    {
        return $this->getValue('nb_icontact_prospect_attachment_hash');
    }

    /**
     * Sets the Icontact Prospect Attachment Hash attribute value.
     * @param string|null $hash New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setHash(string $hash = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_attachment_hash', $hash);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Attachment Name attribute value
     * @return null|string Returns the Icontact Prospect Attachment Name value
     */
    public function getName()
    {
        return $this->getValue('nb_icontact_prospect_attachment_name');
    }

    /**
     * Sets the Icontact Prospect Attachment Name attribute value.
     * @param string|null $name New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setName(string $name = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_attachment_name', $name);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Attachment Mimetype attribute value
     * @return null|string Returns the Icontact Prospect Attachment Mimetype value
     */
    public function getMimetype()
    {
        return $this->getValue('nb_icontact_prospect_attachment_mimetype');
    }

    /**
     * Sets the Icontact Prospect Attachment Mimetype attribute value.
     * @param string|null $mimetype New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setMimetype(string $mimetype = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_attachment_mimetype', $mimetype);
        
        return $this;
    }
}
