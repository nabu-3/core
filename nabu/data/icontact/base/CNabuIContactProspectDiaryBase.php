<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2019/10/10 11:56:26 UTC
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

namespace nabu\data\icontact\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\CNabuDataObject;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity iContact Prospect Diary stored in the storage named nb_icontact_prospect_diary.
 * @author Rafael Gutiérrez <rgutierrez@nabu-3.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\icontact\base
 */
abstract class CNabuIContactProspectDiaryBase extends CNabuDBInternalObject
{
    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_icontact_prospect_diary An instance of CNabuIContactProspectDiaryBase or another object
     * descending from \nabu\data\CNabuDataObject which contains a field named nb_icontact_prospect_diary_id, or a
     * valid ID.
     */
    public function __construct($nb_icontact_prospect_diary = false)
    {
        if ($nb_icontact_prospect_diary) {
            $this->transferMixedValue($nb_icontact_prospect_diary, 'nb_icontact_prospect_diary_id');
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
        return 'nb_icontact_prospect_diary';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_icontact_prospect_diary_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_icontact_prospect_diary '
                   . "where nb_icontact_prospect_diary_id=%nb_icontact_prospect_diary_id\$d "
              )
            : null;
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_icontact_prospect_diary_id' is the
     * index, and each value is an instance of class CNabuIContactProspectDiaryBase.
     * @return mixed Returns and array with all items.
     */
    public static function getAlliContactProspectDiaries()
    {
        return forward_static_call(
                array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_icontact_prospect_diary_id',
                'select * from nb_icontact_prospect_diary'
        );
    }

    /**
     * Gets a filtered list of iContact Prospect Diary instances represented as an array. Params allows the capability
     * of select a subset of fields, order by concrete fields, or truncate the list by a number of rows starting in an
     * offset.
     * @throws \nabu\core\exceptions\ENabuCoreException Raises an exception if $fields or $order have invalid values.
     * @param string $q Query string to filter results using a context index.
     * @param string|array $fields List of fields to put in the results.
     * @param string|array $order List of fields to order the results. Each field can be suffixed with "ASC" or "DESC"
     * to determine the short order
     * @param int $offset Offset of first row in the results having the first row at offset 0.
     * @param int $num_items Number of continue rows to get as maximum in the results.
     * @return array Returns an array with all rows found using the criteria.
     */
    public static function getFilterediContactProspectDiaryList($q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $fields_part = nb_prefixFieldList(CNabuIContactProspectDiaryBase::getStorageName(), $fields, false, true, '`');
        $order_part = nb_prefixFieldList(CNabuIContactProspectDiaryBase::getStorageName(), $fields, false, false, '`');
        
        if ($num_items !== 0) {
            $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
        } else {
            $limit_part = false;
        }
        
        $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
            "select " . ($fields_part ? $fields_part . ' ' : '* ')
            . 'from nb_icontact_prospect_diary '
            . ($order_part ? "order by $order_part " : '')
            . ($limit_part ? "limit $limit_part" : ''),
            array(
            )
        );
        
        return $nb_item_list;
    }

    /**
     * Get Icontact Prospect Diary Id attribute value
     * @return int Returns the Icontact Prospect Diary Id value
     */
    public function getId() : int
    {
        return $this->getValue('nb_icontact_prospect_diary_id');
    }

    /**
     * Sets the Icontact Prospect Diary Id attribute value.
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
        $this->setValue('nb_icontact_prospect_diary_id', $id);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Id attribute value
     * @return int Returns the Icontact Prospect Id value
     */
    public function getIcontactProspectId() : int
    {
        return $this->getValue('nb_icontact_prospect_id');
    }

    /**
     * Sets the Icontact Prospect Id attribute value.
     * @param int $nb_icontact_prospect_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setIcontactProspectId(int $nb_icontact_prospect_id) : CNabuDataObject
    {
        if ($nb_icontact_prospect_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_icontact_prospect_id")
            );
        }
        $this->setValue('nb_icontact_prospect_id', $nb_icontact_prospect_id);
        
        return $this;
    }

    /**
     * Get User Id attribute value
     * @return null|int Returns the User Id value
     */
    public function getUserId()
    {
        return $this->getValue('nb_user_id');
    }

    /**
     * Sets the User Id attribute value.
     * @param int|null $nb_user_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setUserId(int $nb_user_id = null) : CNabuDataObject
    {
        $this->setValue('nb_user_id', $nb_user_id);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Diary Creation Datetime attribute value
     * @return mixed Returns the Icontact Prospect Diary Creation Datetime value
     */
    public function getCreationDatetime()
    {
        return $this->getValue('nb_icontact_prospect_diary_creation_datetime');
    }

    /**
     * Sets the Icontact Prospect Diary Creation Datetime attribute value.
     * @param mixed $creation_datetime New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setCreationDatetime($creation_datetime) : CNabuDataObject
    {
        if ($creation_datetime === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$creation_datetime")
            );
        }
        $this->setValue('nb_icontact_prospect_diary_creation_datetime', $creation_datetime);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Diary Last Update Datetime attribute value
     * @return mixed Returns the Icontact Prospect Diary Last Update Datetime value
     */
    public function getLastUpdateDatetime()
    {
        return $this->getValue('nb_icontact_prospect_diary_last_update_datetime');
    }

    /**
     * Sets the Icontact Prospect Diary Last Update Datetime attribute value.
     * @param mixed $last_update_datetime New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setLastUpdateDatetime($last_update_datetime) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_diary_last_update_datetime', $last_update_datetime);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Diary Before Status Id attribute value
     * @return null|int Returns the Icontact Prospect Diary Before Status Id value
     */
    public function getBeforeStatusId()
    {
        return $this->getValue('nb_icontact_prospect_diary_before_status_id');
    }

    /**
     * Sets the Icontact Prospect Diary Before Status Id attribute value.
     * @param int|null $before_status_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setBeforeStatusId(int $before_status_id = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_diary_before_status_id', $before_status_id);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Diary After Status Id attribute value
     * @return null|int Returns the Icontact Prospect Diary After Status Id value
     */
    public function getAfterStatusId()
    {
        return $this->getValue('nb_icontact_prospect_diary_after_status_id');
    }

    /**
     * Sets the Icontact Prospect Diary After Status Id attribute value.
     * @param int|null $after_status_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setAfterStatusId(int $after_status_id = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_diary_after_status_id', $after_status_id);
        
        return $this;
    }

    /**
     * Get Icontact Prospect Diary Notes attribute value
     * @return null|string Returns the Icontact Prospect Diary Notes value
     */
    public function getNotes()
    {
        return $this->getValue('nb_icontact_prospect_diary_notes');
    }

    /**
     * Sets the Icontact Prospect Diary Notes attribute value.
     * @param string|null $notes New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setNotes(string $notes = null) : CNabuDataObject
    {
        $this->setValue('nb_icontact_prospect_diary_notes', $notes);
        
        return $this;
    }
}
