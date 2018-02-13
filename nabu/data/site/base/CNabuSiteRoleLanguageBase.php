<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2018/02/13 10:21:45 UTC
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

use \nabu\core\exceptions\ENabuCoreException;
use \nabu\data\CNabuDataObject;
use \nabu\data\lang\CNabuLanguage;
use \nabu\data\lang\CNabuLanguageList;
use \nabu\data\lang\interfaces\INabuTranslated;
use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\lang\traits\TNabuTranslation;
use \nabu\data\site\CNabuSiteRoleLanguage;
use \nabu\data\site\CNabuSiteRoleLanguageList;
use \nabu\data\site\traits\TNabuSiteChild;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Site Role Language stored in the storage named nb_site_role_lang.
 * @version 3.0.12 Surface
 * @package \nabu\data\site\base
 */
abstract class CNabuSiteRoleLanguageBase extends CNabuDBInternalObject implements INabuTranslation
{
    use TNabuSiteChild;
    use TNabuTranslation;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_site An instance of CNabuSiteRoleLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_site_id, or a valid ID.
     * @param mixed $nb_role An instance of CNabuSiteRoleLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_role_id, or a valid ID.
     * @param mixed $nb_language An instance of CNabuSiteRoleLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_language_id, or a valid ID.
     */
    public function __construct($nb_site = false, $nb_role = false, $nb_language = false)
    {
        if ($nb_site) {
            $this->transferMixedValue($nb_site, 'nb_site_id');
        }
        
        if ($nb_role) {
            $this->transferMixedValue($nb_role, 'nb_role_id');
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
        return 'nb_site_role_lang';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_site_id') && $this->isValueNumeric('nb_role_id') && $this->isValueNumeric('nb_language_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_site_role_lang '
                   . "where nb_site_id=%nb_site_id\$d "
                     . "and nb_role_id=%nb_role_id\$d "
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
        $nb_role_id = nb_getMixedValue($translated, 'nb_role_id');
        if (is_numeric($nb_site_id) &&
            is_numeric($nb_role_id)
           )
        {
            $retval = CNabuLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select l.* '
                    . 'from nb_language l, nb_site_role t1, nb_site_role_lang t2 '
                   . 'where t1.nb_site_id=t2.nb_site_id '
                     . 'and t1.nb_site_id=%nb_site_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id '
                     . 'and t1.nb_role_id=t2.nb_role_id '
                     . 'and t1.nb_role_id=%nb_role_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_site_id' => $nb_site_id,
                        'nb_role_id' => $nb_role_id
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
     * @return CNabuSiteRoleLanguageList Returns a list of translations. If no translations are available, the list is
     * empty.
     */
    public static function getTranslationsForTranslatedObject($translated)
    {
        $nb_site_id = nb_getMixedValue($translated, 'nb_site_id');
        $nb_role_id = nb_getMixedValue($translated, 'nb_role_id');
        if (is_numeric($nb_site_id) &&
            is_numeric($nb_role_id)
           )
        {
            $retval = CNabuSiteRoleLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select t2.* '
                    . 'from nb_language l, nb_site_role t1, nb_site_role_lang t2 '
                   . 'where t1.nb_site_id=t2.nb_site_id '
                     . 'and t1.nb_site_id=%nb_site_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id '
                     . 'and t1.nb_role_id=t2.nb_role_id '
                     . 'and t1.nb_role_id=%nb_role_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_site_id' => $nb_site_id,
                        'nb_role_id' => $nb_role_id
                    )
            );
            if ($translated instanceof INabuTranslated) {
                $retval->iterate(
                    function ($key, $nb_translation) use($translated) {
                        $nb_translation->setTranslatedObject($translated);
                        return true;
                    }
                );
            }
        } else {
            $retval = new CNabuSiteRoleLanguageList();
        }
        
        return $retval;
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
     * Get Site Role Lang Login Target URL attribute value
     * @return null|string Returns the Site Role Lang Login Target URL value
     */
    public function getLoginTargetURL()
    {
        return $this->getValue('nb_site_role_lang_login_target_url');
    }

    /**
     * Sets the Site Role Lang Login Target URL attribute value.
     * @param string|null $login_target_url New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setLoginTargetURL(string $login_target_url = null) : CNabuDataObject
    {
        $this->setValue('nb_site_role_lang_login_target_url', $login_target_url);
        
        return $this;
    }
}