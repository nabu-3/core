<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2018/02/20 04:20:59 UTC
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

namespace nabu\data\site\base;

use \nabu\core\CNabuEngine;
use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\CNabuDataObject;
use \nabu\data\lang\interfaces\INabuTranslated;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslated;
use \nabu\data\site\builtin\CNabuBuiltInSiteRoleLanguage;
use \nabu\data\site\CNabuSiteRoleLanguage;
use \nabu\data\site\CNabuSiteRoleLanguageList;
use \nabu\data\site\traits\TNabuSiteChild;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Site Role stored in the storage named nb_site_role.
 * @version 3.0.12 Surface
 * @package \nabu\data\site\base
 */
abstract class CNabuSiteRoleBase extends CNabuDBInternalObject implements INabuTranslated
{
    use TNabuSiteChild;
    use TNabuTranslated;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_site An instance of CNabuSiteRoleBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_site_id, or a valid ID.
     * @param mixed $nb_role An instance of CNabuSiteRoleBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_role_id, or a valid ID.
     */
    public function __construct($nb_site = false, $nb_role = false)
    {
        if ($nb_site) {
            $this->transferMixedValue($nb_site, 'nb_site_id');
        }
        
        if ($nb_role) {
            $this->transferMixedValue($nb_role, 'nb_role_id');
        }
        
        parent::__construct();
        $this->__translatedConstruct();
        $this->translations_list = new CNabuSiteRoleLanguageList();
    }

    /**
     * Overrides refresh method to add translations branch to refresh.
     * @param bool $force Forces to reload entities from the database storage.
     * @param bool $cascade Forces to reload child entities from the database storage.
     * @return bool Returns true if transations are empty or refreshed.
     */
    public function refresh(bool $force = false, bool $cascade = false) : bool
    {
        return parent::refresh($force, $cascade) && $this->appendTranslatedRefresh($force);
    }

    /**
     * Overrides delete method to delete translations when delete the this instance.
     * @return bool Returns true if the entity and their translations are deleted.
     */
    public function delete() : bool
    {
        $this->deleteTranslations(true);
        
        return parent::delete();
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
        return 'nb_site_role';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_site_id') && $this->isValueNumeric('nb_role_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_site_role '
                   . "where nb_site_id=%nb_site_id\$d "
                     . "and nb_role_id=%nb_role_id\$d "
              )
            : null;
    }

    /**
     * Gets a filtered list of Site Role instances represented as an array. Params allows the capability of select a
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
    public static function getFilteredSiteRoleList($q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $fields_part = nb_prefixFieldList(CNabuSiteRoleBase::getStorageName(), $fields, false, true, '`');
        $order_part = nb_prefixFieldList(CNabuSiteRoleBase::getStorageName(), $fields, false, false, '`');
        
        if ($num_items !== 0) {
            $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
        } else {
            $limit_part = false;
        }
        
        $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
            "select " . ($fields_part ? $fields_part . ' ' : '* ')
            . 'from nb_site_role '
            . ($order_part ? "order by $order_part " : '')
            . ($limit_part ? "limit $limit_part" : ''),
            array(
            )
        );
        
        return $nb_item_list;
    }

    /**
     * Check if the instance passed as parameter $translation is a valid child translation for this object
     * @param INabuTranslation $translation Translation instance to check
     * @return bool Return true if a valid object is passed as instance or false elsewhere
     */
    protected function checkForValidTranslationInstance($translation)
    {
        return ($translation !== null &&
                $translation instanceof CNabuSiteRoleLanguage &&
                $translation->matchValue($this, 'nb_site_id') &&
                $translation->matchValue($this, 'nb_role_id')
        );
    }

    /**
     * Get all language instances corresponding to available translations.
     * @param bool $force If true force to reload languages list from storage.
     * @return null|array Return an array of \nabu\data\lang\CNabuLanguage instances if they have translations or null
     * if not.
     */
    public function getLanguages($force = false)
    {
        if (!CNabuEngine::getEngine()->isOperationModeStandalone() &&
            ($this->languages_list->getSize() === 0 || $force)
        ) {
            $this->languages_list = CNabuSiteRoleLanguage::getLanguagesForTranslatedObject($this);
        }
        
        return $this->languages_list;
    }

    /**
     * Gets available translation instances.
     * @param bool $force If true force to reload translations list from storage.
     * @return null|array Return an array of \nabu\data\site\CNabuSiteRoleLanguage instances if they have translations
     * or null if not.
     */
    public function getTranslations($force = false)
    {
        if (!CNabuEngine::getEngine()->isOperationModeStandalone() &&
            ($this->translations_list->getSize() === 0 || $force)
        ) {
            $this->translations_list = CNabuSiteRoleLanguage::getTranslationsForTranslatedObject($this);
        }
        
        return $this->translations_list;
    }

    /**
     * Creates a new translation instance. I the translation already exists then replaces ancient translation with this
     * new.
     * @param int|string|CNabuDataObject $nb_language A valid Id or object containing a nb_language_id field to
     * identify the language of new translation.
     * @return CNabuSiteRoleLanguage Returns the created instance to store translation or null if not valid language
     * was provided.
     */
    public function newTranslation($nb_language)
    {
        $nb_language_id = nb_getMixedValue($nb_language, NABU_LANG_FIELD_ID);
        if (is_numeric($nb_language_id) || nb_isValidGUID($nb_language_id)) {
            $nb_translation = $this->isBuiltIn()
                            ? new CNabuBuiltInSiteRoleLanguage()
                            : new CNabuSiteRoleLanguage()
            ;
            $nb_translation->transferValue($this, 'nb_site_id');
            $nb_translation->transferValue($this, 'nb_role_id');
            $nb_translation->transferValue($nb_language, NABU_LANG_FIELD_ID);
            $this->setTranslation($nb_translation);
        } else {
            $nb_translation = null;
        }
        
        return $nb_translation;
    }

    /**
     * Get Site Id attribute value
     * @return int Returns the Site Id value
     */
    public function getSiteId() : int
    {
        return $this->getValue('nb_site_id');
    }

    /**
     * Sets the Site Id attribute value.
     * @param int $nb_site_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setSiteId(int $nb_site_id) : CNabuDataObject
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
     * Get Role Id attribute value
     * @return int Returns the Role Id value
     */
    public function getRoleId() : int
    {
        return $this->getValue('nb_role_id');
    }

    /**
     * Sets the Role Id attribute value.
     * @param int $nb_role_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setRoleId(int $nb_role_id) : CNabuDataObject
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
     * Get Site Role Login Redirection Target Use URI attribute value
     * @return mixed Returns the Site Role Login Redirection Target Use URI value
     */
    public function getLoginRedirectionTargetUseURI()
    {
        return $this->getValue('nb_site_role_login_redirection_target_use_uri');
    }

    /**
     * Sets the Site Role Login Redirection Target Use URI attribute value.
     * @param mixed $login_redirection_target_use_uri New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setLoginRedirectionTargetUseURI($login_redirection_target_use_uri) : CNabuDataObject
    {
        if ($login_redirection_target_use_uri === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$login_redirection_target_use_uri")
            );
        }
        $this->setValue('nb_site_role_login_redirection_target_use_uri', $login_redirection_target_use_uri);
        
        return $this;
    }

    /**
     * Get Site Role Login Redirection Target Id attribute value
     * @return null|int Returns the Site Role Login Redirection Target Id value
     */
    public function getLoginRedirectionTargetId()
    {
        return $this->getValue('nb_site_role_login_redirection_target_id');
    }

    /**
     * Sets the Site Role Login Redirection Target Id attribute value.
     * @param int|null $login_redirection_target_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setLoginRedirectionTargetId(int $login_redirection_target_id = null) : CNabuDataObject
    {
        $this->setValue('nb_site_role_login_redirection_target_id', $login_redirection_target_id);
        
        return $this;
    }

    /**
     * Get Messaging Template New User attribute value
     * @return null|int Returns the Messaging Template New User value
     */
    public function getMessagingTemplateNewUser()
    {
        return $this->getValue('nb_messaging_template_new_user');
    }

    /**
     * Sets the Messaging Template New User attribute value.
     * @param int|null $nb_messaging_template_new_user New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setMessagingTemplateNewUser(int $nb_messaging_template_new_user = null) : CNabuDataObject
    {
        $this->setValue('nb_messaging_template_new_user', $nb_messaging_template_new_user);
        
        return $this;
    }

    /**
     * Get Messaging Template Forgot Password attribute value
     * @return null|int Returns the Messaging Template Forgot Password value
     */
    public function getMessagingTemplateForgotPassword()
    {
        return $this->getValue('nb_messaging_template_forgot_password');
    }

    /**
     * Sets the Messaging Template Forgot Password attribute value.
     * @param int|null $nb_messaging_template_forgot_password New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setMessagingTemplateForgotPassword(int $nb_messaging_template_forgot_password = null) : CNabuDataObject
    {
        $this->setValue('nb_messaging_template_forgot_password', $nb_messaging_template_forgot_password);
        
        return $this;
    }

    /**
     * Get Messaging Template Notify New User attribute value
     * @return null|int Returns the Messaging Template Notify New User value
     */
    public function getMessagingTemplateNotifyNewUser()
    {
        return $this->getValue('nb_messaging_template_notify_new_user');
    }

    /**
     * Sets the Messaging Template Notify New User attribute value.
     * @param int|null $nb_messaging_template_notify_new_user New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setMessagingTemplateNotifyNewUser(int $nb_messaging_template_notify_new_user = null) : CNabuDataObject
    {
        $this->setValue('nb_messaging_template_notify_new_user', $nb_messaging_template_notify_new_user);
        
        return $this;
    }

    /**
     * Get Messaging Template Remember New User attribute value
     * @return null|int Returns the Messaging Template Remember New User value
     */
    public function getMessagingTemplateRememberNewUser()
    {
        return $this->getValue('nb_messaging_template_remember_new_user');
    }

    /**
     * Sets the Messaging Template Remember New User attribute value.
     * @param int|null $nb_messaging_template_remember_new_user New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setMessagingTemplateRememberNewUser(int $nb_messaging_template_remember_new_user = null) : CNabuDataObject
    {
        $this->setValue('nb_messaging_template_remember_new_user', $nb_messaging_template_remember_new_user);
        
        return $this;
    }

    /**
     * Get Messaging Template Invite User attribute value
     * @return null|int Returns the Messaging Template Invite User value
     */
    public function getMessagingTemplateInviteUser()
    {
        return $this->getValue('nb_messaging_template_invite_user');
    }

    /**
     * Sets the Messaging Template Invite User attribute value.
     * @param int|null $nb_messaging_template_invite_user New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setMessagingTemplateInviteUser(int $nb_messaging_template_invite_user = null) : CNabuDataObject
    {
        $this->setValue('nb_messaging_template_invite_user', $nb_messaging_template_invite_user);
        
        return $this;
    }

    /**
     * Get Messaging Template Invite Friend attribute value
     * @return null|int Returns the Messaging Template Invite Friend value
     */
    public function getMessagingTemplateInviteFriend()
    {
        return $this->getValue('nb_messaging_template_invite_friend');
    }

    /**
     * Sets the Messaging Template Invite Friend attribute value.
     * @param int|null $nb_messaging_template_invite_friend New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setMessagingTemplateInviteFriend(int $nb_messaging_template_invite_friend = null) : CNabuDataObject
    {
        $this->setValue('nb_messaging_template_invite_friend', $nb_messaging_template_invite_friend);
        
        return $this;
    }

    /**
     * Get Messaging Template New Message attribute value
     * @return null|int Returns the Messaging Template New Message value
     */
    public function getMessagingTemplateNewMessage()
    {
        return $this->getValue('nb_messaging_template_new_message');
    }

    /**
     * Sets the Messaging Template New Message attribute value.
     * @param int|null $nb_messaging_template_new_message New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setMessagingTemplateNewMessage(int $nb_messaging_template_new_message = null) : CNabuDataObject
    {
        $this->setValue('nb_messaging_template_new_message', $nb_messaging_template_new_message);
        
        return $this;
    }

    /**
     * Overrides this method to add support to traits and/or attributes.
     * @param int|CNabuDataObject $nb_language Instance or Id of the language to be used.
     * @param bool $dataonly Render only field values and ommit class control flags.
     * @return array Returns a multilevel associative array with all data.
     */
    public function getTreeData($nb_language = null, $dataonly = false)
    {
        $trdata = parent::getTreeData($nb_language, $dataonly);
        
        $trdata = $this->appendTranslatedTreeData($trdata, $nb_language, $dataonly);
        
        return $trdata;
    }
}
