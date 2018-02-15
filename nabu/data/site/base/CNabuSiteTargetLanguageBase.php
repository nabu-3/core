<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2018/02/15 12:53:41 UTC
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
use \nabu\data\site\CNabuSiteTargetLanguage;
use \nabu\data\site\CNabuSiteTargetLanguageList;
use \nabu\data\site\traits\TNabuSiteTargetChild;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Site Target Language stored in the storage named nb_site_target_lang.
 * @version 3.0.12 Surface
 * @package \nabu\data\site\base
 */
abstract class CNabuSiteTargetLanguageBase extends CNabuDBInternalObject implements INabuTranslation
{
    use TNabuSiteTargetChild;
    use TNabuTranslation;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_site_target An instance of CNabuSiteTargetLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_site_target_id, or a valid ID.
     * @param mixed $nb_language An instance of CNabuSiteTargetLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_language_id, or a valid ID.
     */
    public function __construct($nb_site_target = false, $nb_language = false)
    {
        if ($nb_site_target) {
            $this->transferMixedValue($nb_site_target, 'nb_site_target_id');
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
        return 'nb_site_target_lang';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_site_target_id') && $this->isValueNumeric('nb_language_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_site_target_lang '
                   . "where nb_site_target_id=%nb_site_target_id\$d "
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
        $nb_site_target_id = nb_getMixedValue($translated, 'nb_site_target_id');
        if (is_numeric($nb_site_target_id)) {
            $retval = CNabuLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select l.* '
                    . 'from nb_language l, nb_site_target t1, nb_site_target_lang t2 '
                   . 'where t1.nb_site_target_id=t2.nb_site_target_id '
                     . 'and t1.nb_site_target_id=%nb_site_target_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_site_target_id' => $nb_site_target_id
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
     * @return CNabuSiteTargetLanguageList Returns a list of translations. If no translations are available, the list
     * is empty.
     */
    public static function getTranslationsForTranslatedObject($translated)
    {
        $nb_site_target_id = nb_getMixedValue($translated, 'nb_site_target_id');
        if (is_numeric($nb_site_target_id)) {
            $retval = CNabuSiteTargetLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select t2.* '
                    . 'from nb_language l, nb_site_target t1, nb_site_target_lang t2 '
                   . 'where t1.nb_site_target_id=t2.nb_site_target_id '
                     . 'and t1.nb_site_target_id=%nb_site_target_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_site_target_id' => $nb_site_target_id
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
            $retval = new CNabuSiteTargetLanguageList();
        }
        
        return $retval;
    }

    /**
     * Get Site Target Id attribute value
     * @return int Returns the Site Target Id value
     */
    public function getSiteTargetId() : int
    {
        return $this->getValue('nb_site_target_id');
    }

    /**
     * Sets the Site Target Id attribute value.
     * @param int $nb_site_target_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setSiteTargetId(int $nb_site_target_id) : CNabuDataObject
    {
        if ($nb_site_target_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_site_target_id")
            );
        }
        $this->setValue('nb_site_target_id', $nb_site_target_id);
        
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
     * Get Site Target Lang Title attribute value
     * @return null|string Returns the Site Target Lang Title value
     */
    public function getTitle()
    {
        return $this->getValue('nb_site_target_lang_title');
    }

    /**
     * Sets the Site Target Lang Title attribute value.
     * @param string|null $title New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setTitle(string $title = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_lang_title', $title);
        
        return $this;
    }

    /**
     * Get Site Target Lang Subtitle attribute value
     * @return null|string Returns the Site Target Lang Subtitle value
     */
    public function getSubtitle()
    {
        return $this->getValue('nb_site_target_lang_subtitle');
    }

    /**
     * Sets the Site Target Lang Subtitle attribute value.
     * @param string|null $subtitle New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setSubtitle(string $subtitle = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_lang_subtitle', $subtitle);
        
        return $this;
    }

    /**
     * Get Site Target Lang Opening attribute value
     * @return null|string Returns the Site Target Lang Opening value
     */
    public function getOpening()
    {
        return $this->getValue('nb_site_target_lang_opening');
    }

    /**
     * Sets the Site Target Lang Opening attribute value.
     * @param string|null $opening New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setOpening(string $opening = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_lang_opening', $opening);
        
        return $this;
    }

    /**
     * Get Site Target Lang Content attribute value
     * @return null|string Returns the Site Target Lang Content value
     */
    public function getContent()
    {
        return $this->getValue('nb_site_target_lang_content');
    }

    /**
     * Sets the Site Target Lang Content attribute value.
     * @param string|null $content New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setContent(string $content = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_lang_content', $content);
        
        return $this;
    }

    /**
     * Get Site Target Lang Footer attribute value
     * @return null|string Returns the Site Target Lang Footer value
     */
    public function getFooter()
    {
        return $this->getValue('nb_site_target_lang_footer');
    }

    /**
     * Sets the Site Target Lang Footer attribute value.
     * @param string|null $footer New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setFooter(string $footer = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_lang_footer', $footer);
        
        return $this;
    }

    /**
     * Get Site Target Lang Aside attribute value
     * @return null|string Returns the Site Target Lang Aside value
     */
    public function getAside()
    {
        return $this->getValue('nb_site_target_lang_aside');
    }

    /**
     * Sets the Site Target Lang Aside attribute value.
     * @param string|null $aside New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setAside(string $aside = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_lang_aside', $aside);
        
        return $this;
    }

    /**
     * Get Site Target Lang Main Image attribute value
     * @return null|string Returns the Site Target Lang Main Image value
     */
    public function getMainImage()
    {
        return $this->getValue('nb_site_target_lang_main_image');
    }

    /**
     * Sets the Site Target Lang Main Image attribute value.
     * @param string|null $main_image New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setMainImage(string $main_image = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_lang_main_image', $main_image);
        
        return $this;
    }

    /**
     * Get Site Target Lang URL attribute value
     * @return null|string Returns the Site Target Lang URL value
     */
    public function getURL()
    {
        return $this->getValue('nb_site_target_lang_url');
    }

    /**
     * Sets the Site Target Lang URL attribute value.
     * @param string|null $url New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setURL(string $url = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_lang_url', $url);
        
        return $this;
    }

    /**
     * Get Site Target Lang URL Rebuild attribute value
     * @return null|string Returns the Site Target Lang URL Rebuild value
     */
    public function getURLRebuild()
    {
        return $this->getValue('nb_site_target_lang_url_rebuild');
    }

    /**
     * Sets the Site Target Lang URL Rebuild attribute value.
     * @param string|null $url_rebuild New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setURLRebuild(string $url_rebuild = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_lang_url_rebuild', $url_rebuild);
        
        return $this;
    }

    /**
     * Get Site Target Lang Head Title attribute value
     * @return null|string Returns the Site Target Lang Head Title value
     */
    public function getHeadTitle()
    {
        return $this->getValue('nb_site_target_lang_head_title');
    }

    /**
     * Sets the Site Target Lang Head Title attribute value.
     * @param string|null $head_title New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setHeadTitle(string $head_title = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_lang_head_title', $head_title);
        
        return $this;
    }

    /**
     * Get Site Target Lang Meta Description attribute value
     * @return null|string Returns the Site Target Lang Meta Description value
     */
    public function getMetaDescription()
    {
        return $this->getValue('nb_site_target_lang_meta_description');
    }

    /**
     * Sets the Site Target Lang Meta Description attribute value.
     * @param string|null $meta_description New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setMetaDescription(string $meta_description = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_lang_meta_description', $meta_description);
        
        return $this;
    }

    /**
     * Get Site Target Lang Meta Keywords attribute value
     * @return null|string Returns the Site Target Lang Meta Keywords value
     */
    public function getMetaKeywords()
    {
        return $this->getValue('nb_site_target_lang_meta_keywords');
    }

    /**
     * Sets the Site Target Lang Meta Keywords attribute value.
     * @param string|null $meta_keywords New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setMetaKeywords(string $meta_keywords = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_lang_meta_keywords', $meta_keywords);
        
        return $this;
    }
}
