<?php
/* ===========================================================================
 * File generated automatically by Nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/04/05 12:21:24 UTC
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
use \nabu\data\CNabuDataObject;
use \nabu\data\commerce\traits\TNabuCommerceChild;
use \nabu\data\customer\CNabuCustomer;
use \nabu\data\customer\traits\TNabuCustomerChild;
use \nabu\data\lang\CNabuLanguage;
use \nabu\data\lang\CNabuLanguageList;
use \nabu\data\lang\interfaces\INabuTranslated;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslated;
use \nabu\data\site\builtin\CNabuBuiltInSiteLanguage;
use \nabu\data\site\CNabuSite;
use \nabu\data\site\CNabuSiteLanguage;
use \nabu\data\site\CNabuSiteList;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Site stored in the storage named nb_site.
 * @author Rafael Gutiérrez Martínez <rgutierrez@wiscot.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\site\base
 */
abstract class CNabuSiteBase extends CNabuDBInternalObject implements INabuTranslated
{
    use TNabuCommerceChild;
    use TNabuCustomerChild;
    use TNabuTranslated;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_site An instance of CNabuSiteBase or another object descending from \nabu\data\CNabuDataObject
     * which contains a field named nb_site_id, or a valid ID.
     */
    public function __construct($nb_site = false)
    {
        if ($nb_site) {
            $this->transferMixedValue($nb_site, 'nb_site_id');
        }
        
        parent::__construct();
        $this->__translatedConstruct();
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
        return 'nb_site';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_site_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_site '
                   . "where nb_site_id=%nb_site_id\$d "
              )
            : null;
    }

    /**
     * Find an instance identified by nb_site_key field.
     * @param mixed $nb_customer Customer that owns Site
     * @param string $key Key to search
     * @return CNabuSite Returns a valid instance if exists or null if not.
     */
    public static function findByKey($nb_customer, $key)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, 'nb_customer_id');
        if (is_numeric($nb_customer_id)) {
            $retval = CNabuSite::buildObjectFromSQL(
                    'select * '
                    . 'from nb_site '
                   . 'where nb_customer_id=%cust_id$d '
                     . "and nb_site_key='%key\$s'",
                    array(
                        'cust_id' => $nb_customer_id,
                        'key' => $key
                    )
            );
        } else {
            $retval = null;
        }
        
        return $retval;
    }

    /**
     * Get all items in the storage as an associative array where the field 'nb_site_id' is the index, and each value
     * is an instance of class CNabuSiteBase.
     * @param CNabuCustomer $nb_customer The CNabuCustomer instance of the Customer that owns the Site List.
     * @return mixed Returns and array with all items.
     */
    public static function getAllSites(CNabuCustomer $nb_customer)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, 'nb_customer_id');
        if (is_numeric($nb_customer_id)) {
            $retval = forward_static_call(
            array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_site_id',
                'select * '
                . 'from nb_site '
               . 'where nb_customer_id=%cust_id$d',
                array(
                    'cust_id' => $nb_customer_id
                ),
                $nb_customer
            );
        } else {
            $retval = new CNabuSiteList();
        }
        
        return $retval;
    }

    /**
     * Gets a filtered list of Site instances represented as an array. Params allows the capability of select a subset
     * of fields, order by concrete fields, or truncate the list by a number of rows starting in an offset.
     * @throws \nabu\core\exceptions\ENabuCoreException Raises an exception if $fields or $order have invalid values.
     * @param mixed $nb_customer Customer instance, object containing a Customer Id field or an Id.
     * @param string $q Query string to filter results using a context index.
     * @param string|array $fields List of fields to put in the results.
     * @param string|array $order List of fields to order the results. Each field can be suffixed with "ASC" or "DESC"
     * to determine the short order
     * @param int $offset Offset of first row in the results having the first row at offset 0.
     * @param int $num_items Number of continue rows to get as maximum in the results.
     * @return array Returns an array with all rows found using the criteria.
     */
    public static function getFilteredSiteList($nb_customer, $q = null, $fields = null, $order = null, $offset = 0, $num_items = 0)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, NABU_CUSTOMER_FIELD_ID);
        if (is_numeric($nb_customer_id)) {
            $fields_part = nb_prefixFieldList(CNabuSiteBase::getStorageName(), $fields, false, true, '`');
            $order_part = nb_prefixFieldList(CNabuSiteBase::getStorageName(), $fields, false, false, '`');
        
            if ($num_items !== 0) {
                $limit_part = ($offset > 0 ? $offset . ', ' : '') . $num_items;
            } else {
                $limit_part = false;
            }
        
            $nb_item_list = CNabuEngine::getEngine()->getMainDB()->getQueryAsArray(
                "select " . ($fields_part ? $fields_part . ' ' : '* ')
                . 'from nb_site '
               . 'where ' . NABU_CUSTOMER_FIELD_ID . '=%cust_id$d '
                . ($order_part ? "order by $order_part " : '')
                . ($limit_part ? "limit $limit_part" : ''),
                array(
                    'cust_id' => $nb_customer_id
                )
            );
        } else {
            $nb_item_list = null;
        }
        
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
                $translation instanceof CNabuSiteLanguage &&
                $translation->matchValue($this, 'nb_site_id')
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
            $this->languages_list = CNabuSiteLanguage::getLanguagesForTranslatedObject($this);
        }
        
        return $this->languages_list;
    }

    /**
     * Gets available translation instances.
     * @param bool $force If true force to reload translations list from storage.
     * @return null|array Return an array of \nabu\data\site\CNabuSiteLanguage instances if they have translations or
     * null if not.
     */
    public function getTranslations($force = false)
    {
        if (!CNabuEngine::getEngine()->isOperationModeStandalone() &&
            ($this->translations_list->getSize() === 0 || $force)
        ) {
            $this->translations_list = CNabuSiteLanguage::getTranslationsForTranslatedObject($this);
        }
        
        return $this->translations_list;
    }

    /**
     * Creates a new translation instance. I the translation already exists then replaces ancient translation with this
     * new.
     * @param int|string|CNabuDataObject $nb_language A valid Id or object containing a nb_language_id field to
     * identify the language of new translation.
     * @return CNabuSiteLanguage Returns the created instance to store translation or null if not valid language was
     * provided.
     */
    public function newTranslation($nb_language)
    {
        $nb_language_id = nb_getMixedValue($nb_language, NABU_LANG_FIELD_ID);
        if (is_numeric($nb_language_id) || nb_isValidGUID($nb_language_id)) {
            $nb_translation = $this->isBuiltIn()
                            ? new CNabuBuiltInSiteLanguage()
                            : new CNabuSiteLanguage()
            ;
            $nb_translation->transferValue($this, 'nb_site_id');
            $nb_translation->transferValue($nb_language, NABU_LANG_FIELD_ID);
            $this->setTranslation($nb_translation);
        } else {
            $nb_translation = null;
        }
        
        return $nb_translation;
    }

    /**
     * Get all language instances used along of all Site set of a Customer
     * @param mixed $nb_customer A CNabuDataObject instance containing a field named nb_customer_id or a Customer ID
     * @return CNabuLanguageList Returns the list of language instances used.
     */
    public static function getCustomerUsedLanguages($nb_customer)
    {
        $nb_customer_id = nb_getMixedValue($nb_customer, NABU_CUSTOMER_FIELD_ID);
        if (is_numeric($nb_customer_id)) {
            $nb_language_list = CNabuLanguage::buildObjectListFromSQL(
                'nb_language_id',
                'select l.* '
                . 'from nb_language l, '
                     . '(select distinct nb_language_id '
                        . 'from nb_site ca, nb_site_lang cal '
                       . 'where ca.nb_site_id=cal.nb_site_id '
                         . 'and ca.nb_customer_id=%cust_id$d) as lid '
               . 'where l.nb_language_id=lid.nb_language_id',
                array('cust_id' => $nb_customer_id)
            );
        } else {
            $nb_language_list = new CNabuLanguageList();
        }
        
        return $nb_language_list;
    }

    /**
     * Overrides refresh method to add translations branch to refresh.
     * @param bool $force Forces to reload entities from the database storage.
     * @param bool $cascade Forces to reload child entities from the database storage.
     * @return bool Returns true if transations are empty or refreshed.
     */
    public function refresh(bool $force = false, bool $cascade = false)
    {
        return parent::refresh($force, $cascade) && $this->appendTranslatedRefresh($force);
    }

    /**
     * Get Site Id attribute value
     * @return int Returns the Site Id value
     */
    public function getId()
    {
        return $this->getValue('nb_site_id');
    }

    /**
     * Sets the Site Id attribute value
     * @param int $id New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setId($id)
    {
        if ($id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$id")
            );
        }
        $this->setValue('nb_site_id', $id);
        
        return $this;
    }

    /**
     * Get Cluster Group Id attribute value
     * @return null|int Returns the Cluster Group Id value
     */
    public function getClusterGroupId()
    {
        return $this->getValue('nb_cluster_group_id');
    }

    /**
     * Sets the Cluster Group Id attribute value
     * @param null|int $nb_cluster_group_id New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setClusterGroupId($nb_cluster_group_id)
    {
        $this->setValue('nb_cluster_group_id', $nb_cluster_group_id);
        
        return $this;
    }

    /**
     * Get Cluster User Id attribute value
     * @return null|int Returns the Cluster User Id value
     */
    public function getClusterUserId()
    {
        return $this->getValue('nb_cluster_user_id');
    }

    /**
     * Sets the Cluster User Id attribute value
     * @param null|int $nb_cluster_user_id New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setClusterUserId($nb_cluster_user_id)
    {
        $this->setValue('nb_cluster_user_id', $nb_cluster_user_id);
        
        return $this;
    }

    /**
     * Get Customer Id attribute value
     * @return null|int Returns the Customer Id value
     */
    public function getCustomerId()
    {
        return $this->getValue('nb_customer_id');
    }

    /**
     * Sets the Customer Id attribute value
     * @param null|int $nb_customer_id New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setCustomerId($nb_customer_id)
    {
        $this->setValue('nb_customer_id', $nb_customer_id);
        
        return $this;
    }

    /**
     * Get Project Id attribute value
     * @return null|int Returns the Project Id value
     */
    public function getProjectId()
    {
        return $this->getValue('nb_project_id');
    }

    /**
     * Sets the Project Id attribute value
     * @param null|int $nb_project_id New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setProjectId($nb_project_id)
    {
        $this->setValue('nb_project_id', $nb_project_id);
        
        return $this;
    }

    /**
     * Get Commerce Id attribute value
     * @return null|int Returns the Commerce Id value
     */
    public function getCommerceId()
    {
        return $this->getValue('nb_commerce_id');
    }

    /**
     * Sets the Commerce Id attribute value
     * @param null|int $nb_commerce_id New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setCommerceId($nb_commerce_id)
    {
        $this->setValue('nb_commerce_id', $nb_commerce_id);
        
        return $this;
    }

    /**
     * Get App OS Id attribute value
     * @return null|int Returns the App OS Id value
     */
    public function getAppOSId()
    {
        return $this->getValue('nb_app_os_id');
    }

    /**
     * Sets the App OS Id attribute value
     * @param null|int $nb_app_os_id New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setAppOSId($nb_app_os_id)
    {
        $this->setValue('nb_app_os_id', $nb_app_os_id);
        
        return $this;
    }

    /**
     * Get Site Delegate For Role attribute value
     * @return null|int Returns the Site Delegate For Role value
     */
    public function getDelegateForRole()
    {
        return $this->getValue('nb_site_delegate_for_role');
    }

    /**
     * Sets the Site Delegate For Role attribute value
     * @param null|int $delegate_for_role New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setDelegateForRole($delegate_for_role)
    {
        $this->setValue('nb_site_delegate_for_role', $delegate_for_role);
        
        return $this;
    }

    /**
     * Get Site Default Role Id attribute value
     * @return null|int Returns the Site Default Role Id value
     */
    public function getDefaultRoleId()
    {
        return $this->getValue('nb_site_default_role_id');
    }

    /**
     * Sets the Site Default Role Id attribute value
     * @param null|int $default_role_id New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setDefaultRoleId($default_role_id)
    {
        $this->setValue('nb_site_default_role_id', $default_role_id);
        
        return $this;
    }

    /**
     * Get Site Default Language Id attribute value
     * @return null|int Returns the Site Default Language Id value
     */
    public function getDefaultLanguageId()
    {
        return $this->getValue('nb_site_default_language_id');
    }

    /**
     * Sets the Site Default Language Id attribute value
     * @param null|int $default_language_id New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setDefaultLanguageId($default_language_id)
    {
        $this->setValue('nb_site_default_language_id', $default_language_id);
        
        return $this;
    }

    /**
     * Get Site Api Language Id attribute value
     * @return null|int Returns the Site Api Language Id value
     */
    public function getApiLanguageId()
    {
        return $this->getValue('nb_site_api_language_id');
    }

    /**
     * Sets the Site Api Language Id attribute value
     * @param null|int $api_language_id New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setApiLanguageId($api_language_id)
    {
        $this->setValue('nb_site_api_language_id', $api_language_id);
        
        return $this;
    }

    /**
     * Get Site Main Alias Id attribute value
     * @return null|int Returns the Site Main Alias Id value
     */
    public function getMainAliasId()
    {
        return $this->getValue('nb_site_main_alias_id');
    }

    /**
     * Sets the Site Main Alias Id attribute value
     * @param null|int $main_alias_id New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setMainAliasId($main_alias_id)
    {
        $this->setValue('nb_site_main_alias_id', $main_alias_id);
        
        return $this;
    }

    /**
     * Get Site Mounting Order attribute value
     * @return int Returns the Site Mounting Order value
     */
    public function getMountingOrder()
    {
        return $this->getValue('nb_site_mounting_order');
    }

    /**
     * Sets the Site Mounting Order attribute value
     * @param int $mounting_order New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setMountingOrder($mounting_order)
    {
        if ($mounting_order === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$mounting_order")
            );
        }
        $this->setValue('nb_site_mounting_order', $mounting_order);
        
        return $this;
    }

    /**
     * Get Site Key attribute value
     * @return string Returns the Site Key value
     */
    public function getKey()
    {
        return $this->getValue('nb_site_key');
    }

    /**
     * Sets the Site Key attribute value
     * @param string $key New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setKey($key)
    {
        if ($key === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$key")
            );
        }
        $this->setValue('nb_site_key', $key);
        
        return $this;
    }

    /**
     * Get Site Creation Datetime attribute value
     * @return mixed Returns the Site Creation Datetime value
     */
    public function getCreationDatetime()
    {
        return $this->getValue('nb_site_creation_datetime');
    }

    /**
     * Sets the Site Creation Datetime attribute value
     * @param mixed $creation_datetime New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setCreationDatetime($creation_datetime)
    {
        if ($creation_datetime === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$creation_datetime")
            );
        }
        $this->setValue('nb_site_creation_datetime', $creation_datetime);
        
        return $this;
    }

    /**
     * Get Site Published attribute value
     * @return mixed Returns the Site Published value
     */
    public function getPublished()
    {
        return $this->getValue('nb_site_published');
    }

    /**
     * Sets the Site Published attribute value
     * @param mixed $published New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setPublished($published)
    {
        $this->setValue('nb_site_published', $published);
        
        return $this;
    }

    /**
     * Get Site Default Target Use URI attribute value
     * @return mixed Returns the Site Default Target Use URI value
     */
    public function getDefaultTargetUseURI()
    {
        return $this->getValue('nb_site_default_target_use_uri');
    }

    /**
     * Sets the Site Default Target Use URI attribute value
     * @param mixed $default_target_use_uri New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setDefaultTargetUseURI($default_target_use_uri)
    {
        if ($default_target_use_uri === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$default_target_use_uri")
            );
        }
        $this->setValue('nb_site_default_target_use_uri', $default_target_use_uri);
        
        return $this;
    }

    /**
     * Get Site Default Target Id attribute value
     * @return null|int Returns the Site Default Target Id value
     */
    public function getDefaultTargetId()
    {
        return $this->getValue('nb_site_default_target_id');
    }

    /**
     * Sets the Site Default Target Id attribute value
     * @param null|int $default_target_id New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setDefaultTargetId($default_target_id)
    {
        $this->setValue('nb_site_default_target_id', $default_target_id);
        
        return $this;
    }

    /**
     * Get Site Page Not Found Target Use URI attribute value
     * @return mixed Returns the Site Page Not Found Target Use URI value
     */
    public function getPageNotFoundTargetUseURI()
    {
        return $this->getValue('nb_site_page_not_found_target_use_uri');
    }

    /**
     * Sets the Site Page Not Found Target Use URI attribute value
     * @param mixed $page_not_found_target_use_uri New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setPageNotFoundTargetUseURI($page_not_found_target_use_uri)
    {
        if ($page_not_found_target_use_uri === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$page_not_found_target_use_uri")
            );
        }
        $this->setValue('nb_site_page_not_found_target_use_uri', $page_not_found_target_use_uri);
        
        return $this;
    }

    /**
     * Get Site Page Not Found Target Id attribute value
     * @return null|int Returns the Site Page Not Found Target Id value
     */
    public function getPageNotFoundTargetId()
    {
        return $this->getValue('nb_site_page_not_found_target_id');
    }

    /**
     * Sets the Site Page Not Found Target Id attribute value
     * @param null|int $page_not_found_target_id New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setPageNotFoundTargetId($page_not_found_target_id)
    {
        $this->setValue('nb_site_page_not_found_target_id', $page_not_found_target_id);
        
        return $this;
    }

    /**
     * Get Site Page Not Found Error Code attribute value
     * @return int Returns the Site Page Not Found Error Code value
     */
    public function getPageNotFoundErrorCode()
    {
        return $this->getValue('nb_site_page_not_found_error_code');
    }

    /**
     * Sets the Site Page Not Found Error Code attribute value
     * @param int $page_not_found_error_code New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setPageNotFoundErrorCode($page_not_found_error_code)
    {
        if ($page_not_found_error_code === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$page_not_found_error_code")
            );
        }
        $this->setValue('nb_site_page_not_found_error_code', $page_not_found_error_code);
        
        return $this;
    }

    /**
     * Get Site Login Target Use URI attribute value
     * @return mixed Returns the Site Login Target Use URI value
     */
    public function getLoginTargetUseURI()
    {
        return $this->getValue('nb_site_login_target_use_uri');
    }

    /**
     * Sets the Site Login Target Use URI attribute value
     * @param mixed $login_target_use_uri New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setLoginTargetUseURI($login_target_use_uri)
    {
        $this->setValue('nb_site_login_target_use_uri', $login_target_use_uri);
        
        return $this;
    }

    /**
     * Get Site Login Target Id attribute value
     * @return null|int Returns the Site Login Target Id value
     */
    public function getLoginTargetId()
    {
        return $this->getValue('nb_site_login_target_id');
    }

    /**
     * Sets the Site Login Target Id attribute value
     * @param null|int $login_target_id New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setLoginTargetId($login_target_id)
    {
        $this->setValue('nb_site_login_target_id', $login_target_id);
        
        return $this;
    }

    /**
     * Get Site Login Redirection Target Use URI attribute value
     * @return mixed Returns the Site Login Redirection Target Use URI value
     */
    public function getLoginRedirectionTargetUseURI()
    {
        return $this->getValue('nb_site_login_redirection_target_use_uri');
    }

    /**
     * Sets the Site Login Redirection Target Use URI attribute value
     * @param mixed $login_redirection_target_use_uri New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setLoginRedirectionTargetUseURI($login_redirection_target_use_uri)
    {
        $this->setValue('nb_site_login_redirection_target_use_uri', $login_redirection_target_use_uri);
        
        return $this;
    }

    /**
     * Get Site Login Redirection Target Id attribute value
     * @return null|int Returns the Site Login Redirection Target Id value
     */
    public function getLoginRedirectionTargetId()
    {
        return $this->getValue('nb_site_login_redirection_target_id');
    }

    /**
     * Sets the Site Login Redirection Target Id attribute value
     * @param null|int $login_redirection_target_id New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setLoginRedirectionTargetId($login_redirection_target_id)
    {
        $this->setValue('nb_site_login_redirection_target_id', $login_redirection_target_id);
        
        return $this;
    }

    /**
     * Get Site Logout Redirection Target Use URI attribute value
     * @return mixed Returns the Site Logout Redirection Target Use URI value
     */
    public function getLogoutRedirectionTargetUseURI()
    {
        return $this->getValue('nb_site_logout_redirection_target_use_uri');
    }

    /**
     * Sets the Site Logout Redirection Target Use URI attribute value
     * @param mixed $logout_redirection_target_use_uri New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setLogoutRedirectionTargetUseURI($logout_redirection_target_use_uri)
    {
        $this->setValue('nb_site_logout_redirection_target_use_uri', $logout_redirection_target_use_uri);
        
        return $this;
    }

    /**
     * Get Site Logout Redirection Target Id attribute value
     * @return null|int Returns the Site Logout Redirection Target Id value
     */
    public function getLogoutRedirectionTargetId()
    {
        return $this->getValue('nb_site_logout_redirection_target_id');
    }

    /**
     * Sets the Site Logout Redirection Target Id attribute value
     * @param null|int $logout_redirection_target_id New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setLogoutRedirectionTargetId($logout_redirection_target_id)
    {
        $this->setValue('nb_site_logout_redirection_target_id', $logout_redirection_target_id);
        
        return $this;
    }

    /**
     * Get Site Alias Not Found Target Use URI attribute value
     * @return mixed Returns the Site Alias Not Found Target Use URI value
     */
    public function getAliasNotFoundTargetUseURI()
    {
        return $this->getValue('nb_site_alias_not_found_target_use_uri');
    }

    /**
     * Sets the Site Alias Not Found Target Use URI attribute value
     * @param mixed $alias_not_found_target_use_uri New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setAliasNotFoundTargetUseURI($alias_not_found_target_use_uri)
    {
        if ($alias_not_found_target_use_uri === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$alias_not_found_target_use_uri")
            );
        }
        $this->setValue('nb_site_alias_not_found_target_use_uri', $alias_not_found_target_use_uri);
        
        return $this;
    }

    /**
     * Get Site Alias Not Found Target Id attribute value
     * @return null|int Returns the Site Alias Not Found Target Id value
     */
    public function getAliasNotFoundTargetId()
    {
        return $this->getValue('nb_site_alias_not_found_target_id');
    }

    /**
     * Sets the Site Alias Not Found Target Id attribute value
     * @param null|int $alias_not_found_target_id New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setAliasNotFoundTargetId($alias_not_found_target_id)
    {
        $this->setValue('nb_site_alias_not_found_target_id', $alias_not_found_target_id);
        
        return $this;
    }

    /**
     * Get Site Alias Locked Target Use URI attribute value
     * @return mixed Returns the Site Alias Locked Target Use URI value
     */
    public function getAliasLockedTargetUseURI()
    {
        return $this->getValue('nb_site_alias_locked_target_use_uri');
    }

    /**
     * Sets the Site Alias Locked Target Use URI attribute value
     * @param mixed $alias_locked_target_use_uri New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setAliasLockedTargetUseURI($alias_locked_target_use_uri)
    {
        if ($alias_locked_target_use_uri === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$alias_locked_target_use_uri")
            );
        }
        $this->setValue('nb_site_alias_locked_target_use_uri', $alias_locked_target_use_uri);
        
        return $this;
    }

    /**
     * Get Site Alias Locked Target Id attribute value
     * @return null|int Returns the Site Alias Locked Target Id value
     */
    public function getAliasLockedTargetId()
    {
        return $this->getValue('nb_site_alias_locked_target_id');
    }

    /**
     * Sets the Site Alias Locked Target Id attribute value
     * @param null|int $alias_locked_target_id New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setAliasLockedTargetId($alias_locked_target_id)
    {
        $this->setValue('nb_site_alias_locked_target_id', $alias_locked_target_id);
        
        return $this;
    }

    /**
     * Get Site Use Cache attribute value
     * @return string Returns the Site Use Cache value
     */
    public function getUseCache()
    {
        return $this->getValue('nb_site_use_cache');
    }

    /**
     * Sets the Site Use Cache attribute value
     * @param string $use_cache New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setUseCache($use_cache)
    {
        if ($use_cache === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$use_cache")
            );
        }
        $this->setValue('nb_site_use_cache', $use_cache);
        
        return $this;
    }

    /**
     * Get Site Cache Handler attribute value
     * @return null|string Returns the Site Cache Handler value
     */
    public function getCacheHandler()
    {
        return $this->getValue('nb_site_cache_handler');
    }

    /**
     * Sets the Site Cache Handler attribute value
     * @param null|string $cache_handler New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setCacheHandler($cache_handler)
    {
        $this->setValue('nb_site_cache_handler', $cache_handler);
        
        return $this;
    }

    /**
     * Get Site Use Smarty attribute value
     * @return mixed Returns the Site Use Smarty value
     */
    public function getUseSmarty()
    {
        return $this->getValue('nb_site_use_smarty');
    }

    /**
     * Sets the Site Use Smarty attribute value
     * @param mixed $use_smarty New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setUseSmarty($use_smarty)
    {
        $this->setValue('nb_site_use_smarty', $use_smarty);
        
        return $this;
    }

    /**
     * Get Site Smarty Error Reporting attribute value
     * @return null|int Returns the Site Smarty Error Reporting value
     */
    public function getSmartyErrorReporting()
    {
        return $this->getValue('nb_site_smarty_error_reporting');
    }

    /**
     * Sets the Site Smarty Error Reporting attribute value
     * @param null|int $smarty_error_reporting New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setSmartyErrorReporting($smarty_error_reporting)
    {
        $this->setValue('nb_site_smarty_error_reporting', $smarty_error_reporting);
        
        return $this;
    }

    /**
     * Get Site Smarty Debugging attribute value
     * @return mixed Returns the Site Smarty Debugging value
     */
    public function getSmartyDebugging()
    {
        return $this->getValue('nb_site_smarty_debugging');
    }

    /**
     * Sets the Site Smarty Debugging attribute value
     * @param mixed $smarty_debugging New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setSmartyDebugging($smarty_debugging)
    {
        $this->setValue('nb_site_smarty_debugging', $smarty_debugging);
        
        return $this;
    }

    /**
     * Get Site Smarty Template Path attribute value
     * @return null|string Returns the Site Smarty Template Path value
     */
    public function getSmartyTemplatePath()
    {
        return $this->getValue('nb_site_smarty_template_path');
    }

    /**
     * Sets the Site Smarty Template Path attribute value
     * @param null|string $smarty_template_path New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setSmartyTemplatePath($smarty_template_path)
    {
        $this->setValue('nb_site_smarty_template_path', $smarty_template_path);
        
        return $this;
    }

    /**
     * Get Site Smarty Compile Path attribute value
     * @return null|string Returns the Site Smarty Compile Path value
     */
    public function getSmartyCompilePath()
    {
        return $this->getValue('nb_site_smarty_compile_path');
    }

    /**
     * Sets the Site Smarty Compile Path attribute value
     * @param null|string $smarty_compile_path New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setSmartyCompilePath($smarty_compile_path)
    {
        $this->setValue('nb_site_smarty_compile_path', $smarty_compile_path);
        
        return $this;
    }

    /**
     * Get Site Smarty Cache Path attribute value
     * @return null|string Returns the Site Smarty Cache Path value
     */
    public function getSmartyCachePath()
    {
        return $this->getValue('nb_site_smarty_cache_path');
    }

    /**
     * Sets the Site Smarty Cache Path attribute value
     * @param null|string $smarty_cache_path New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setSmartyCachePath($smarty_cache_path)
    {
        $this->setValue('nb_site_smarty_cache_path', $smarty_cache_path);
        
        return $this;
    }

    /**
     * Get Site Smarty Configs Path attribute value
     * @return null|string Returns the Site Smarty Configs Path value
     */
    public function getSmartyConfigsPath()
    {
        return $this->getValue('nb_site_smarty_configs_path');
    }

    /**
     * Sets the Site Smarty Configs Path attribute value
     * @param null|string $smarty_configs_path New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setSmartyConfigsPath($smarty_configs_path)
    {
        $this->setValue('nb_site_smarty_configs_path', $smarty_configs_path);
        
        return $this;
    }

    /**
     * Get Site Smarty Models Path attribute value
     * @return null|string Returns the Site Smarty Models Path value
     */
    public function getSmartyModelsPath()
    {
        return $this->getValue('nb_site_smarty_models_path');
    }

    /**
     * Sets the Site Smarty Models Path attribute value
     * @param null|string $smarty_models_path New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setSmartyModelsPath($smarty_models_path)
    {
        $this->setValue('nb_site_smarty_models_path', $smarty_models_path);
        
        return $this;
    }

    /**
     * Get Site Plugin Name attribute value
     * @return null|string Returns the Site Plugin Name value
     */
    public function getPluginName()
    {
        return $this->getValue('nb_site_plugin_name');
    }

    /**
     * Sets the Site Plugin Name attribute value
     * @param null|string $plugin_name New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setPluginName($plugin_name)
    {
        $this->setValue('nb_site_plugin_name', $plugin_name);
        
        return $this;
    }

    /**
     * Get Site HTTP Support attribute value
     * @return mixed Returns the Site HTTP Support value
     */
    public function getHTTPSupport()
    {
        return $this->getValue('nb_site_http_support');
    }

    /**
     * Sets the Site HTTP Support attribute value
     * @param mixed $http_support New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setHTTPSupport($http_support)
    {
        if ($http_support === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$http_support")
            );
        }
        $this->setValue('nb_site_http_support', $http_support);
        
        return $this;
    }

    /**
     * Get Site HTTPS Support attribute value
     * @return mixed Returns the Site HTTPS Support value
     */
    public function getHTTPSSupport()
    {
        return $this->getValue('nb_site_https_support');
    }

    /**
     * Sets the Site HTTPS Support attribute value
     * @param mixed $https_support New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setHTTPSSupport($https_support)
    {
        if ($https_support === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$https_support")
            );
        }
        $this->setValue('nb_site_https_support', $https_support);
        
        return $this;
    }

    /**
     * Get Site Google WMR Domain Id attribute value
     * @return null|string Returns the Site Google WMR Domain Id value
     */
    public function getGoogleWMRDomainId()
    {
        return $this->getValue('nb_site_google_wmr_domain_id');
    }

    /**
     * Sets the Site Google WMR Domain Id attribute value
     * @param null|string $google_wmr_domain_id New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setGoogleWMRDomainId($google_wmr_domain_id)
    {
        $this->setValue('nb_site_google_wmr_domain_id', $google_wmr_domain_id);
        
        return $this;
    }

    /**
     * Get Site Google WMR Site Id attribute value
     * @return null|string Returns the Site Google WMR Site Id value
     */
    public function getGoogleWMRSiteId()
    {
        return $this->getValue('nb_site_google_wmr_site_id');
    }

    /**
     * Sets the Site Google WMR Site Id attribute value
     * @param null|string $google_wmr_site_id New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setGoogleWMRSiteId($google_wmr_site_id)
    {
        $this->setValue('nb_site_google_wmr_site_id', $google_wmr_site_id);
        
        return $this;
    }

    /**
     * Get Site Use AWStats attribute value
     * @return mixed Returns the Site Use AWStats value
     */
    public function getUseAWStats()
    {
        return $this->getValue('nb_site_use_awstats');
    }

    /**
     * Sets the Site Use AWStats attribute value
     * @param mixed $use_awstats New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setUseAWStats($use_awstats)
    {
        if ($use_awstats === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$use_awstats")
            );
        }
        $this->setValue('nb_site_use_awstats', $use_awstats);
        
        return $this;
    }

    /**
     * Get Site Local Path attribute value
     * @return null|string Returns the Site Local Path value
     */
    public function getLocalPath()
    {
        return $this->getValue('nb_site_local_path');
    }

    /**
     * Sets the Site Local Path attribute value
     * @param null|string $local_path New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setLocalPath($local_path)
    {
        $this->setValue('nb_site_local_path', $local_path);
        
        return $this;
    }

    /**
     * Get Site Dynamic Cache Control attribute value
     * @return string Returns the Site Dynamic Cache Control value
     */
    public function getDynamicCacheControl()
    {
        return $this->getValue('nb_site_dynamic_cache_control');
    }

    /**
     * Sets the Site Dynamic Cache Control attribute value
     * @param string $dynamic_cache_control New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setDynamicCacheControl($dynamic_cache_control)
    {
        if ($dynamic_cache_control === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$dynamic_cache_control")
            );
        }
        $this->setValue('nb_site_dynamic_cache_control', $dynamic_cache_control);
        
        return $this;
    }

    /**
     * Get Site Dynamic Cache Default Max Age attribute value
     * @return null|int Returns the Site Dynamic Cache Default Max Age value
     */
    public function getDynamicCacheDefaultMaxAge()
    {
        return $this->getValue('nb_site_dynamic_cache_default_max_age');
    }

    /**
     * Sets the Site Dynamic Cache Default Max Age attribute value
     * @param null|int $dynamic_cache_default_max_age New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setDynamicCacheDefaultMaxAge($dynamic_cache_default_max_age)
    {
        $this->setValue('nb_site_dynamic_cache_default_max_age', $dynamic_cache_default_max_age);
        
        return $this;
    }

    /**
     * Get Emailing Id attribute value
     * @return null|int Returns the Emailing Id value
     */
    public function getEmailingId()
    {
        return $this->getValue('nb_emailing_id');
    }

    /**
     * Sets the Emailing Id attribute value
     * @param null|int $nb_emailing_id New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setEmailingId($nb_emailing_id)
    {
        $this->setValue('nb_emailing_id', $nb_emailing_id);
        
        return $this;
    }

    /**
     * Get Site Email Template New User attribute value
     * @return null|int Returns the Site Email Template New User value
     */
    public function getEmailTemplateNewUser()
    {
        return $this->getValue('nb_site_email_template_new_user');
    }

    /**
     * Sets the Site Email Template New User attribute value
     * @param null|int $email_template_new_user New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setEmailTemplateNewUser($email_template_new_user)
    {
        $this->setValue('nb_site_email_template_new_user', $email_template_new_user);
        
        return $this;
    }

    /**
     * Get Site Email Template Lost Password attribute value
     * @return null|int Returns the Site Email Template Lost Password value
     */
    public function getEmailTemplateLostPassword()
    {
        return $this->getValue('nb_site_email_template_lost_password');
    }

    /**
     * Sets the Site Email Template Lost Password attribute value
     * @param null|int $email_template_lost_password New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setEmailTemplateLostPassword($email_template_lost_password)
    {
        $this->setValue('nb_site_email_template_lost_password', $email_template_lost_password);
        
        return $this;
    }

    /**
     * Get Site Email Template Notify New User attribute value
     * @return null|int Returns the Site Email Template Notify New User value
     */
    public function getEmailTemplateNotifyNewUser()
    {
        return $this->getValue('nb_site_email_template_notify_new_user');
    }

    /**
     * Sets the Site Email Template Notify New User attribute value
     * @param null|int $email_template_notify_new_user New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setEmailTemplateNotifyNewUser($email_template_notify_new_user)
    {
        $this->setValue('nb_site_email_template_notify_new_user', $email_template_notify_new_user);
        
        return $this;
    }

    /**
     * Get Site Email Template Remember New User attribute value
     * @return null|int Returns the Site Email Template Remember New User value
     */
    public function getEmailTemplateRememberNewUser()
    {
        return $this->getValue('nb_site_email_template_remember_new_user');
    }

    /**
     * Sets the Site Email Template Remember New User attribute value
     * @param null|int $email_template_remember_new_user New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setEmailTemplateRememberNewUser($email_template_remember_new_user)
    {
        $this->setValue('nb_site_email_template_remember_new_user', $email_template_remember_new_user);
        
        return $this;
    }

    /**
     * Get Site Email Template Invite User attribute value
     * @return null|int Returns the Site Email Template Invite User value
     */
    public function getEmailTemplateInviteUser()
    {
        return $this->getValue('nb_site_email_template_invite_user');
    }

    /**
     * Sets the Site Email Template Invite User attribute value
     * @param null|int $email_template_invite_user New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setEmailTemplateInviteUser($email_template_invite_user)
    {
        $this->setValue('nb_site_email_template_invite_user', $email_template_invite_user);
        
        return $this;
    }

    /**
     * Get Site Email Template Invite Friend attribute value
     * @return null|int Returns the Site Email Template Invite Friend value
     */
    public function getEmailTemplateInviteFriend()
    {
        return $this->getValue('nb_site_email_template_invite_friend');
    }

    /**
     * Sets the Site Email Template Invite Friend attribute value
     * @param null|int $email_template_invite_friend New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setEmailTemplateInviteFriend($email_template_invite_friend)
    {
        $this->setValue('nb_site_email_template_invite_friend', $email_template_invite_friend);
        
        return $this;
    }

    /**
     * Get Site Email Template New Message attribute value
     * @return null|int Returns the Site Email Template New Message value
     */
    public function getEmailTemplateNewMessage()
    {
        return $this->getValue('nb_site_email_template_new_message');
    }

    /**
     * Sets the Site Email Template New Message attribute value
     * @param null|int $email_template_new_message New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setEmailTemplateNewMessage($email_template_new_message)
    {
        $this->setValue('nb_site_email_template_new_message', $email_template_new_message);
        
        return $this;
    }

    /**
     * Get Site Apache Personalized attribute value
     * @return mixed Returns the Site Apache Personalized value
     */
    public function getApachePersonalized()
    {
        return $this->getValue('nb_site_apache_personalized');
    }

    /**
     * Sets the Site Apache Personalized attribute value
     * @param mixed $apache_personalized New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setApachePersonalized($apache_personalized)
    {
        $this->setValue('nb_site_apache_personalized', $apache_personalized);
        
        return $this;
    }

    /**
     * Get Site Apache Last Update attribute value
     * @return mixed Returns the Site Apache Last Update value
     */
    public function getApacheLastUpdate()
    {
        return $this->getValue('nb_site_apache_last_update');
    }

    /**
     * Sets the Site Apache Last Update attribute value
     * @param mixed $apache_last_update New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setApacheLastUpdate($apache_last_update)
    {
        $this->setValue('nb_site_apache_last_update', $apache_last_update);
        
        return $this;
    }

    /**
     * Get Site Apache Last Update Error attribute value
     * @return mixed Returns the Site Apache Last Update Error value
     */
    public function getApacheLastUpdateError()
    {
        return $this->getValue('nb_site_apache_last_update_error');
    }

    /**
     * Sets the Site Apache Last Update Error attribute value
     * @param mixed $apache_last_update_error New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setApacheLastUpdateError($apache_last_update_error)
    {
        $this->setValue('nb_site_apache_last_update_error', $apache_last_update_error);
        
        return $this;
    }

    /**
     * Get Site WSearch Enabled attribute value
     * @return mixed Returns the Site WSearch Enabled value
     */
    public function getWSearchEnabled()
    {
        return $this->getValue('nb_site_wsearch_enabled');
    }

    /**
     * Sets the Site WSearch Enabled attribute value
     * @param mixed $wsearch_enabled New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setWSearchEnabled($wsearch_enabled)
    {
        $this->setValue('nb_site_wsearch_enabled', $wsearch_enabled);
        
        return $this;
    }

    /**
     * Get Site Use Framework attribute value
     * @return string Returns the Site Use Framework value
     */
    public function getUseFramework()
    {
        return $this->getValue('nb_site_use_framework');
    }

    /**
     * Sets the Site Use Framework attribute value
     * @param string $use_framework New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setUseFramework($use_framework)
    {
        if ($use_framework === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$use_framework")
            );
        }
        $this->setValue('nb_site_use_framework', $use_framework);
        
        return $this;
    }

    /**
     * Get Site Enable VirtualHost File attribute value
     * @return string Returns the Site Enable VirtualHost File value
     */
    public function getEnableVirtualHostFile()
    {
        return $this->getValue('nb_site_enable_vhost_file');
    }

    /**
     * Sets the Site Enable VirtualHost File attribute value
     * @param string $enable_vhost_file New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setEnableVirtualHostFile($enable_vhost_file)
    {
        if ($enable_vhost_file === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$enable_vhost_file")
            );
        }
        $this->setValue('nb_site_enable_vhost_file', $enable_vhost_file);
        
        return $this;
    }

    /**
     * Get Site Session Timeout Interval attribute value
     * @return null|string Returns the Site Session Timeout Interval value
     */
    public function getSessionTimeoutInterval()
    {
        return $this->getValue('nb_site_session_timeout_interval');
    }

    /**
     * Sets the Site Session Timeout Interval attribute value
     * @param null|string $session_timeout_interval New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setSessionTimeoutInterval($session_timeout_interval)
    {
        $this->setValue('nb_site_session_timeout_interval', $session_timeout_interval);
        
        return $this;
    }

    /**
     * Get Site Session Preserve Interval attribute value
     * @return null|string Returns the Site Session Preserve Interval value
     */
    public function getSessionPreserveInterval()
    {
        return $this->getValue('nb_site_session_preserve_interval');
    }

    /**
     * Sets the Site Session Preserve Interval attribute value
     * @param null|string $session_preserve_interval New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setSessionPreserveInterval($session_preserve_interval)
    {
        $this->setValue('nb_site_session_preserve_interval', $session_preserve_interval);
        
        return $this;
    }

    /**
     * Get Site Enable Session Strict Policies attribute value
     * @return null|string Returns the Site Enable Session Strict Policies value
     */
    public function getEnableSessionStrictPolicies()
    {
        return $this->getValue('nb_site_enable_session_strict_policies');
    }

    /**
     * Sets the Site Enable Session Strict Policies attribute value
     * @param null|string $enable_session_strict_policies New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setEnableSessionStrictPolicies($enable_session_strict_policies)
    {
        $this->setValue('nb_site_enable_session_strict_policies', $enable_session_strict_policies);
        
        return $this;
    }

    /**
     * Get Site Static Content Use Alternative attribute value
     * @return string Returns the Site Static Content Use Alternative value
     */
    public function getStaticContentUseAlternative()
    {
        return $this->getValue('nb_site_static_content_use_alternative');
    }

    /**
     * Sets the Site Static Content Use Alternative attribute value
     * @param string $static_content_use_alternative New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setStaticContentUseAlternative($static_content_use_alternative)
    {
        if ($static_content_use_alternative === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$static_content_use_alternative")
            );
        }
        $this->setValue('nb_site_static_content_use_alternative', $static_content_use_alternative);
        
        return $this;
    }

    /**
     * Get Site Base Path attribute value
     * @return null|string Returns the Site Base Path value
     */
    public function getBasePath()
    {
        return $this->getValue('nb_site_base_path');
    }

    /**
     * Sets the Site Base Path attribute value
     * @param null|string $base_path New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setBasePath($base_path)
    {
        $this->setValue('nb_site_base_path', $base_path);
        
        return $this;
    }

    /**
     * Get Site Modules Slots attribute value
     * @return null|string Returns the Site Modules Slots value
     */
    public function getModulesSlots()
    {
        return $this->getValue('nb_site_modules_slots');
    }

    /**
     * Sets the Site Modules Slots attribute value
     * @param null|string $modules_slots New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setModulesSlots($modules_slots)
    {
        $this->setValue('nb_site_modules_slots', $modules_slots);
        
        return $this;
    }

    /**
     * Get Site Notification Email attribute value
     * @return null|string Returns the Site Notification Email value
     */
    public function getNotificationEmail()
    {
        return $this->getValue('nb_site_notification_email');
    }

    /**
     * Sets the Site Notification Email attribute value
     * @param null|string $notification_email New value for attribute
     * @return CNabuSiteBase Returns $this
     */
    public function setNotificationEmail($notification_email)
    {
        $this->setValue('nb_site_notification_email', $notification_email);
        
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
