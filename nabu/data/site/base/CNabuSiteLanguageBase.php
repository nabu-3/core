<?php
/* ===========================================================================
 * File generated automatically by Nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/03/29 13:30:26 UTC
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

use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\lang\CNabuLanguage;
use \nabu\data\lang\CNabuLanguageList;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslation;
use \nabu\data\site\CNabuSiteLanguage;
use \nabu\data\site\CNabuSiteLanguageList;
use \nabu\data\site\traits\TNabuSiteChild;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Site Language stored in the storage named nb_site_lang.
 * @author Rafael Gutiérrez Martínez <rgutierrez@wiscot.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\site\base
 */
abstract class CNabuSiteLanguageBase extends CNabuDBInternalObject implements INabuTranslation
{
    use TNabuSiteChild;
    use TNabuTranslation;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_site An instance of CNabuSiteLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_site_id, or a valid ID.
     * @param mixed $nb_language An instance of CNabuSiteLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_language_id, or a valid ID.
     */
    public function __construct($nb_site = false, $nb_language = false)
    {
        if ($nb_site) {
            $this->transferMixedValue($nb_site, 'nb_site_id');
        }
        
        if ($nb_language) {
            $this->transferMixedValue($nb_language, 'nb_language_id');
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
        return 'nb_site_lang';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_site_id') && $this->isValueNumeric('nb_language_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_site_lang '
                   . "where nb_site_id=%nb_site_id\$d "
                     . "and nb_language_id=%nb_language_id\$d "
              )
            : null;
    }

    /**
     * Query the storage to retrieve the full list of available languages (those that correspond to existent
     * translations) for $translated and returns a list with all languages.
     * @param mixed $translated Translated object or Id to retrieve languages.
     * @return CNabuLanguageList Returns a list of languages. If no languages are available, the list is empty.
     */
    public static function getLanguagesForTranslatedObject($translated)
    {
        $nb_site_id = nb_getMixedValue($translated, 'nb_site_id');
        if (is_numeric($nb_site_id)) {
            $retval = CNabuLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select l.* '
                    . 'from nb_language l, nb_site t1, nb_site_lang t2 '
                   . 'where t1.nb_site_id=t2.nb_site_id '
                     . 'and t1.nb_site_id=%nb_site_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id '
                   . 'order by t2.nb_site_lang_order',
                    array(
                        'nb_site_id' => $nb_site_id
                    )
            );
        } else {
            $retval = new CNabuLanguageList();
        }
        
        return $retval;
    }

    /**
     * Query the storage to retrieve the full list of available translations for $translated and returns a list with
     * all translations.
     * @param mixed $translated Translated object or Id to retrieve translations.
     * @return CNabuSiteLanguageList Returns a list of translations. If no translations are available, the list is
     * empty.
     */
    public static function getTranslationsForTranslatedObject($translated)
    {
        $nb_site_id = nb_getMixedValue($translated, 'nb_site_id');
        if (is_numeric($nb_site_id)) {
            $retval = CNabuSiteLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select t2.* '
                    . 'from nb_language l, nb_site t1, nb_site_lang t2 '
                   . 'where t1.nb_site_id=t2.nb_site_id '
                     . 'and t1.nb_site_id=%nb_site_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id '
                   . 'order by t2.nb_site_lang_order',
                    array(
                        'nb_site_id' => $nb_site_id
                    )
            );
            $retval->iterate(
                function ($key, $nb_translation) use($translated) {
                    $nb_translation->setTranslatedObject($translated);
                }
            );
        } else {
            $retval = new CNabuSiteLanguageList();
        }
        
        return $retval;
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
     * @return CNabuSiteLanguageBase Returns $this
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
     * Get Language Id attribute value
     * @return int Returns the Language Id value
     */
    public function getLanguageId()
    {
        return $this->getValue('nb_language_id');
    }

    /**
     * Sets the Language Id attribute value
     * @param int $nb_language_id New value for attribute
     * @return CNabuSiteLanguageBase Returns $this
     */
    public function setLanguageId($nb_language_id)
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
     * Get Site Lang Enabled attribute value
     * @return null|string Returns the Site Lang Enabled value
     */
    public function getEnabled()
    {
        return $this->getValue('nb_site_lang_enabled');
    }

    /**
     * Sets the Site Lang Enabled attribute value
     * @param null|string $enabled New value for attribute
     * @return CNabuSiteLanguageBase Returns $this
     */
    public function setEnabled($enabled)
    {
        $this->setValue('nb_site_lang_enabled', $enabled);
        
        return $this;
    }

    /**
     * Get Site Lang Translation Status attribute value
     * @return null|string Returns the Site Lang Translation Status value
     */
    public function getTranslationStatus()
    {
        return $this->getValue('nb_site_lang_translation_status');
    }

    /**
     * Sets the Site Lang Translation Status attribute value
     * @param null|string $translation_status New value for attribute
     * @return CNabuSiteLanguageBase Returns $this
     */
    public function setTranslationStatus($translation_status)
    {
        $this->setValue('nb_site_lang_translation_status', $translation_status);
        
        return $this;
    }

    /**
     * Get Site Lang Order attribute value
     * @return null|int Returns the Site Lang Order value
     */
    public function getOrder()
    {
        return $this->getValue('nb_site_lang_order');
    }

    /**
     * Sets the Site Lang Order attribute value
     * @param null|int $order New value for attribute
     * @return CNabuSiteLanguageBase Returns $this
     */
    public function setOrder($order)
    {
        $this->setValue('nb_site_lang_order', $order);
        
        return $this;
    }

    /**
     * Get Site Lang Editable attribute value
     * @return string Returns the Site Lang Editable value
     */
    public function getEditable()
    {
        return $this->getValue('nb_site_lang_editable');
    }

    /**
     * Sets the Site Lang Editable attribute value
     * @param string $editable New value for attribute
     * @return CNabuSiteLanguageBase Returns $this
     */
    public function setEditable($editable)
    {
        if ($editable === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$editable")
            );
        }
        $this->setValue('nb_site_lang_editable', $editable);
        
        return $this;
    }

    /**
     * Get Site Lang Short Datetime Format attribute value
     * @return null|string Returns the Site Lang Short Datetime Format value
     */
    public function getShortDatetimeFormat()
    {
        return $this->getValue('nb_site_lang_short_datetime_format');
    }

    /**
     * Sets the Site Lang Short Datetime Format attribute value
     * @param null|string $short_datetime_format New value for attribute
     * @return CNabuSiteLanguageBase Returns $this
     */
    public function setShortDatetimeFormat($short_datetime_format)
    {
        $this->setValue('nb_site_lang_short_datetime_format', $short_datetime_format);
        
        return $this;
    }

    /**
     * Get Site Lang Middle Datetime Format attribute value
     * @return null|string Returns the Site Lang Middle Datetime Format value
     */
    public function getMiddleDatetimeFormat()
    {
        return $this->getValue('nb_site_lang_middle_datetime_format');
    }

    /**
     * Sets the Site Lang Middle Datetime Format attribute value
     * @param null|string $middle_datetime_format New value for attribute
     * @return CNabuSiteLanguageBase Returns $this
     */
    public function setMiddleDatetimeFormat($middle_datetime_format)
    {
        $this->setValue('nb_site_lang_middle_datetime_format', $middle_datetime_format);
        
        return $this;
    }

    /**
     * Get Site Lang Full Datetime Format attribute value
     * @return null|string Returns the Site Lang Full Datetime Format value
     */
    public function getFullDatetimeFormat()
    {
        return $this->getValue('nb_site_lang_full_datetime_format');
    }

    /**
     * Sets the Site Lang Full Datetime Format attribute value
     * @param null|string $full_datetime_format New value for attribute
     * @return CNabuSiteLanguageBase Returns $this
     */
    public function setFullDatetimeFormat($full_datetime_format)
    {
        $this->setValue('nb_site_lang_full_datetime_format', $full_datetime_format);
        
        return $this;
    }

    /**
     * Get Site Lang Short Date Format attribute value
     * @return null|string Returns the Site Lang Short Date Format value
     */
    public function getShortDateFormat()
    {
        return $this->getValue('nb_site_lang_short_date_format');
    }

    /**
     * Sets the Site Lang Short Date Format attribute value
     * @param null|string $short_date_format New value for attribute
     * @return CNabuSiteLanguageBase Returns $this
     */
    public function setShortDateFormat($short_date_format)
    {
        $this->setValue('nb_site_lang_short_date_format', $short_date_format);
        
        return $this;
    }

    /**
     * Get Site Lang Middle Date Format attribute value
     * @return null|string Returns the Site Lang Middle Date Format value
     */
    public function getMiddleDateFormat()
    {
        return $this->getValue('nb_site_lang_middle_date_format');
    }

    /**
     * Sets the Site Lang Middle Date Format attribute value
     * @param null|string $middle_date_format New value for attribute
     * @return CNabuSiteLanguageBase Returns $this
     */
    public function setMiddleDateFormat($middle_date_format)
    {
        $this->setValue('nb_site_lang_middle_date_format', $middle_date_format);
        
        return $this;
    }

    /**
     * Get Site Lang Full Date Format attribute value
     * @return null|string Returns the Site Lang Full Date Format value
     */
    public function getFullDateFormat()
    {
        return $this->getValue('nb_site_lang_full_date_format');
    }

    /**
     * Sets the Site Lang Full Date Format attribute value
     * @param null|string $full_date_format New value for attribute
     * @return CNabuSiteLanguageBase Returns $this
     */
    public function setFullDateFormat($full_date_format)
    {
        $this->setValue('nb_site_lang_full_date_format', $full_date_format);
        
        return $this;
    }

    /**
     * Get Site Lang Full Time Format attribute value
     * @return null|string Returns the Site Lang Full Time Format value
     */
    public function getFullTimeFormat()
    {
        return $this->getValue('nb_site_lang_full_time_format');
    }

    /**
     * Sets the Site Lang Full Time Format attribute value
     * @param null|string $full_time_format New value for attribute
     * @return CNabuSiteLanguageBase Returns $this
     */
    public function setFullTimeFormat($full_time_format)
    {
        $this->setValue('nb_site_lang_full_time_format', $full_time_format);
        
        return $this;
    }

    /**
     * Get Site Lang Name attribute value
     * @return null|string Returns the Site Lang Name value
     */
    public function getName()
    {
        return $this->getValue('nb_site_lang_name');
    }

    /**
     * Sets the Site Lang Name attribute value
     * @param null|string $name New value for attribute
     * @return CNabuSiteLanguageBase Returns $this
     */
    public function setName($name)
    {
        $this->setValue('nb_site_lang_name', $name);
        
        return $this;
    }

    /**
     * Get Site Lang Default Target URL attribute value
     * @return null|string Returns the Site Lang Default Target URL value
     */
    public function getDefaultTargetURL()
    {
        return $this->getValue('nb_site_lang_default_target_url');
    }

    /**
     * Sets the Site Lang Default Target URL attribute value
     * @param null|string $default_target_url New value for attribute
     * @return CNabuSiteLanguageBase Returns $this
     */
    public function setDefaultTargetURL($default_target_url)
    {
        $this->setValue('nb_site_lang_default_target_url', $default_target_url);
        
        return $this;
    }

    /**
     * Get Site Lang Page Not Found Target URL attribute value
     * @return null|string Returns the Site Lang Page Not Found Target URL value
     */
    public function getPageNotFoundTargetURL()
    {
        return $this->getValue('nb_site_lang_page_not_found_target_url');
    }

    /**
     * Sets the Site Lang Page Not Found Target URL attribute value
     * @param null|string $page_not_found_target_url New value for attribute
     * @return CNabuSiteLanguageBase Returns $this
     */
    public function setPageNotFoundTargetURL($page_not_found_target_url)
    {
        $this->setValue('nb_site_lang_page_not_found_target_url', $page_not_found_target_url);
        
        return $this;
    }

    /**
     * Get Site Lang Login Target URL attribute value
     * @return null|string Returns the Site Lang Login Target URL value
     */
    public function getLoginTargetURL()
    {
        return $this->getValue('nb_site_lang_login_target_url');
    }

    /**
     * Sets the Site Lang Login Target URL attribute value
     * @param null|string $login_target_url New value for attribute
     * @return CNabuSiteLanguageBase Returns $this
     */
    public function setLoginTargetURL($login_target_url)
    {
        $this->setValue('nb_site_lang_login_target_url', $login_target_url);
        
        return $this;
    }

    /**
     * Get Site Lang Login Redirection Target URL attribute value
     * @return null|string Returns the Site Lang Login Redirection Target URL value
     */
    public function getLoginRedirectionTargetURL()
    {
        return $this->getValue('nb_site_lang_login_redirection_target_url');
    }

    /**
     * Sets the Site Lang Login Redirection Target URL attribute value
     * @param null|string $login_redirection_target_url New value for attribute
     * @return CNabuSiteLanguageBase Returns $this
     */
    public function setLoginRedirectionTargetURL($login_redirection_target_url)
    {
        $this->setValue('nb_site_lang_login_redirection_target_url', $login_redirection_target_url);
        
        return $this;
    }

    /**
     * Get Site Lang Logout Redirection Target URL attribute value
     * @return null|string Returns the Site Lang Logout Redirection Target URL value
     */
    public function getLogoutRedirectionTargetURL()
    {
        return $this->getValue('nb_site_lang_logout_redirection_target_url');
    }

    /**
     * Sets the Site Lang Logout Redirection Target URL attribute value
     * @param null|string $logout_redirection_target_url New value for attribute
     * @return CNabuSiteLanguageBase Returns $this
     */
    public function setLogoutRedirectionTargetURL($logout_redirection_target_url)
    {
        $this->setValue('nb_site_lang_logout_redirection_target_url', $logout_redirection_target_url);
        
        return $this;
    }

    /**
     * Get Site Lang Alias Not Found Target URL attribute value
     * @return null|string Returns the Site Lang Alias Not Found Target URL value
     */
    public function getAliasNotFoundTargetURL()
    {
        return $this->getValue('nb_site_lang_alias_not_found_target_url');
    }

    /**
     * Sets the Site Lang Alias Not Found Target URL attribute value
     * @param null|string $alias_not_found_target_url New value for attribute
     * @return CNabuSiteLanguageBase Returns $this
     */
    public function setAliasNotFoundTargetURL($alias_not_found_target_url)
    {
        $this->setValue('nb_site_lang_alias_not_found_target_url', $alias_not_found_target_url);
        
        return $this;
    }

    /**
     * Get Site Lang Alias Locked Target URL attribute value
     * @return null|string Returns the Site Lang Alias Locked Target URL value
     */
    public function getAliasLockedTargetURL()
    {
        return $this->getValue('nb_site_lang_alias_locked_target_url');
    }

    /**
     * Sets the Site Lang Alias Locked Target URL attribute value
     * @param null|string $alias_locked_target_url New value for attribute
     * @return CNabuSiteLanguageBase Returns $this
     */
    public function setAliasLockedTargetURL($alias_locked_target_url)
    {
        $this->setValue('nb_site_lang_alias_locked_target_url', $alias_locked_target_url);
        
        return $this;
    }
}
