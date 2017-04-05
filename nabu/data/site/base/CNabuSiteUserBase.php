<?php
/* ===========================================================================
 * File generated automatically by Nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/04/05 12:21:29 UTC
 * ===========================================================================
 * Copyright 2009-2011 Rafael Gutierrez Martinez
 * Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 * Copyright 2014-2017 Where Ideas Simply Come True, S.L.
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

namespace nabu\data\site\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\security\traits\TNabuRoleChild;
use \nabu\data\site\traits\TNabuSiteChild;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Site User stored in the storage named nb_site_user.
 * @author Rafael Gutiérrez Martínez <rgutierrez@wiscot.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\site\base
 */
abstract class CNabuSiteUserBase extends CNabuDBInternalObject
{
    use TNabuRoleChild;
    use TNabuSiteChild;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_site An instance of CNabuSiteUserBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_site_id, or a valid ID.
     * @param mixed $nb_user An instance of CNabuSiteUserBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_user_id, or a valid ID.
     */
    public function __construct($nb_site = false, $nb_user = false)
    {
        if ($nb_site) {
            $this->transferMixedValue($nb_site, 'nb_site_id');
        }
        
        if ($nb_user) {
            $this->transferMixedValue($nb_user, 'nb_user_id');
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
        return 'nb_site_user';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_site_id') && $this->isValueNumeric('nb_user_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_site_user '
                   . "where nb_site_id=%nb_site_id\$d "
                     . "and nb_user_id=%nb_user_id\$d "
              )
            : null;
    }

    /**
     * Gets a filtered list of Site User instances represented as an array. Params allows the capability of select a
     * subset of fields, order by concrete fields, or truncate the list by a number of rows starting in an offset.
     * @throws \nabu\core\exceptions\ENabuCoreException Raises an exception if $fields or $order have invalid values.
     * @param string $q Query string to filter results using a context index.
     * @param string|array $fields List of fields to put in the results.
     * @param string|array $order List of fields to order the results. Each field can be suffixed with "ASC" or "DESC"
     * to determine the short order
     * @param int $offset Offset of first row in the results having the first row at offset 0.
     * @param int $num_items Number of continue rows to get as maximum in the results.
     * @return array Returns an array with all rows found using the criteria.
     */
    public static function getFilteredSiteUserList($q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $fields_part = nb_prefixFieldList(CNabuSiteUserBase::getStorageName(), $fields, false, true, '`');
        $order_part = nb_prefixFieldList(CNabuSiteUserBase::getStorageName(), $fields, false, false, '`');
        
        if ($num_items !== 0) {
            $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
        } else {
            $limit_part = false;
        }
        
        $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
            "select " . ($fields_part ? $fields_part . ' ' : '* ')
            . 'from nb_site_user '
            . ($order_part ? "order by $order_part " : '')
            . ($limit_part ? "limit $limit_part" : ''),
            array(
            )
        );
        
        return $nb_item_list;
    }

    /**
     * Get Site Id attribute value
     * @return int Returns the Site Id value
     */
    public function getSiteId()
    {
        return $this->getValue('nb_site_id');
    }

    /**
     * Sets the Site Id attribute value
     * @param int $nb_site_id New value for attribute
     * @return CNabuSiteUserBase Returns $this
     */
    public function setSiteId($nb_site_id)
    {
        if ($nb_site_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_site_id")
            );
        }
        $this->setValue('nb_site_id', $nb_site_id);
        
        return $this;
    }

    /**
     * Get User Id attribute value
     * @return int Returns the User Id value
     */
    public function getUserId()
    {
        return $this->getValue('nb_user_id');
    }

    /**
     * Sets the User Id attribute value
     * @param int $nb_user_id New value for attribute
     * @return CNabuSiteUserBase Returns $this
     */
    public function setUserId($nb_user_id)
    {
        if ($nb_user_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_user_id")
            );
        }
        $this->setValue('nb_user_id', $nb_user_id);
        
        return $this;
    }

    /**
     * Get Role Id attribute value
     * @return int Returns the Role Id value
     */
    public function getRoleId()
    {
        return $this->getValue('nb_role_id');
    }

    /**
     * Sets the Role Id attribute value
     * @param int $nb_role_id New value for attribute
     * @return CNabuSiteUserBase Returns $this
     */
    public function setRoleId($nb_role_id)
    {
        if ($nb_role_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_role_id")
            );
        }
        $this->setValue('nb_role_id', $nb_role_id);
        
        return $this;
    }

    /**
     * Get Language Id attribute value
     * @return null|int Returns the Language Id value
     */
    public function getLanguageId()
    {
        return $this->getValue('nb_language_id');
    }

    /**
     * Sets the Language Id attribute value
     * @param null|int $nb_language_id New value for attribute
     * @return CNabuSiteUserBase Returns $this
     */
    public function setLanguageId($nb_language_id)
    {
        $this->setValue('nb_language_id', $nb_language_id);
        
        return $this;
    }

    /**
     * Get Site User Force Default Lang attribute value
     * @return string Returns the Site User Force Default Lang value
     */
    public function getForceDefaultLang()
    {
        return $this->getValue('nb_site_user_force_default_lang');
    }

    /**
     * Sets the Site User Force Default Lang attribute value
     * @param string $force_default_lang New value for attribute
     * @return CNabuSiteUserBase Returns $this
     */
    public function setForceDefaultLang($force_default_lang)
    {
        if ($force_default_lang === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$force_default_lang")
            );
        }
        $this->setValue('nb_site_user_force_default_lang', $force_default_lang);
        
        return $this;
    }

    /**
     * Get Site User Last Login Datetime attribute value
     * @return mixed Returns the Site User Last Login Datetime value
     */
    public function getLastLoginDatetime()
    {
        return $this->getValue('nb_site_user_last_login_datetime');
    }

    /**
     * Sets the Site User Last Login Datetime attribute value
     * @param mixed $last_login_datetime New value for attribute
     * @return CNabuSiteUserBase Returns $this
     */
    public function setLastLoginDatetime($last_login_datetime)
    {
        $this->setValue('nb_site_user_last_login_datetime', $last_login_datetime);
        
        return $this;
    }

    /**
     * Get Site User Notification Sended Datetime attribute value
     * @return mixed Returns the Site User Notification Sended Datetime value
     */
    public function getNotificationSendedDatetime()
    {
        return $this->getValue('nb_site_user_notification_sended_datetime');
    }

    /**
     * Sets the Site User Notification Sended Datetime attribute value
     * @param mixed $notification_sended_datetime New value for attribute
     * @return CNabuSiteUserBase Returns $this
     */
    public function setNotificationSendedDatetime($notification_sended_datetime)
    {
        $this->setValue('nb_site_user_notification_sended_datetime', $notification_sended_datetime);
        
        return $this;
    }
}
