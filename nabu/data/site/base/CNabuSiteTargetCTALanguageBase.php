<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/05/11 13:36:33 UTC
 * ===========================================================================
 * Copyright 2009-2011 Rafael Gutierrez Martinez
 * Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 * Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 * Copyright 2017 nabu-3 Group
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
use \nabu\data\site\CNabuSiteTargetCTALanguage;
use \nabu\data\site\CNabuSiteTargetCTALanguageList;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Site Target CTA Language stored in the storage named nb_site_target_cta_lang.
 * @version 3.0.12 Surface
 * @package \nabu\data\site\base
 */
abstract class CNabuSiteTargetCTALanguageBase extends CNabuDBInternalObject implements INabuTranslation
{
    use TNabuTranslation;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_site_target_cta An instance of CNabuSiteTargetCTALanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_site_target_cta_id, or a valid ID.
     * @param mixed $nb_language An instance of CNabuSiteTargetCTALanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_language_id, or a valid ID.
     */
    public function __construct($nb_site_target_cta = false, $nb_language = false)
    {
        if ($nb_site_target_cta) {
            $this->transferMixedValue($nb_site_target_cta, 'nb_site_target_cta_id');
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
        return 'nb_site_target_cta_lang';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_site_target_cta_id') && $this->isValueNumeric('nb_language_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_site_target_cta_lang '
                   . "where nb_site_target_cta_id=%nb_site_target_cta_id\$d "
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
        $nb_site_target_cta_id = nb_getMixedValue($translated, 'nb_site_target_cta_id');
        if (is_numeric($nb_site_target_cta_id)) {
            $retval = CNabuLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select l.* '
                    . 'from nb_language l, nb_site_target_cta t1, nb_site_target_cta_lang t2 '
                   . 'where t1.nb_site_target_cta_id=t2.nb_site_target_cta_id '
                     . 'and t1.nb_site_target_cta_id=%nb_site_target_cta_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_site_target_cta_id' => $nb_site_target_cta_id
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
     * @return CNabuSiteTargetCTALanguageList Returns a list of translations. If no translations are available, the
     * list is empty.
     */
    public static function getTranslationsForTranslatedObject($translated)
    {
        $nb_site_target_cta_id = nb_getMixedValue($translated, 'nb_site_target_cta_id');
        if (is_numeric($nb_site_target_cta_id)) {
            $retval = CNabuSiteTargetCTALanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select t2.* '
                    . 'from nb_language l, nb_site_target_cta t1, nb_site_target_cta_lang t2 '
                   . 'where t1.nb_site_target_cta_id=t2.nb_site_target_cta_id '
                     . 'and t1.nb_site_target_cta_id=%nb_site_target_cta_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_site_target_cta_id' => $nb_site_target_cta_id
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
            $retval = new CNabuSiteTargetCTALanguageList();
        }
        
        return $retval;
    }

    /**
     * Get Site Target CTA Id attribute value
     * @return int Returns the Site Target CTA Id value
     */
    public function getSiteTargetCTAId() : int
    {
        return $this->getValue('nb_site_target_cta_id');
    }

    /**
     * Sets the Site Target CTA Id attribute value.
     * @param int $nb_site_target_cta_id New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setSiteTargetCTAId(int $nb_site_target_cta_id) : CNabuDataObject
    {
        if ($nb_site_target_cta_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_site_target_cta_id")
            );
        }
        $this->setValue('nb_site_target_cta_id', $nb_site_target_cta_id);
        
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
     * Get Site Target CTA Lang Target URL attribute value
     * @return null|string Returns the Site Target CTA Lang Target URL value
     */
    public function getTargetURL()
    {
        return $this->getValue('nb_site_target_cta_lang_target_url');
    }

    /**
     * Sets the Site Target CTA Lang Target URL attribute value.
     * @param null|string $target_url New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setTargetURL(string $target_url = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_cta_lang_target_url', $target_url);
        
        return $this;
    }

    /**
     * Get Site Target CTA Lang Title attribute value
     * @return null|string Returns the Site Target CTA Lang Title value
     */
    public function getTitle()
    {
        return $this->getValue('nb_site_target_cta_lang_title');
    }

    /**
     * Sets the Site Target CTA Lang Title attribute value.
     * @param null|string $title New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setTitle(string $title = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_cta_lang_title', $title);
        
        return $this;
    }

    /**
     * Get Site Target CTA Lang Alternate attribute value
     * @return null|string Returns the Site Target CTA Lang Alternate value
     */
    public function getAlternate()
    {
        return $this->getValue('nb_site_target_cta_lang_alternate');
    }

    /**
     * Sets the Site Target CTA Lang Alternate attribute value.
     * @param null|string $alternate New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setAlternate(string $alternate = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_cta_lang_alternate', $alternate);
        
        return $this;
    }

    /**
     * Get Site Target CTA Lang Image attribute value
     * @return null|string Returns the Site Target CTA Lang Image value
     */
    public function getImage()
    {
        return $this->getValue('nb_site_target_cta_lang_image');
    }

    /**
     * Sets the Site Target CTA Lang Image attribute value.
     * @param null|string $image New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setImage(string $image = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_cta_lang_image', $image);
        
        return $this;
    }

    /**
     * Get Site Target CTA Lang Anchor Text attribute value
     * @return null|string Returns the Site Target CTA Lang Anchor Text value
     */
    public function getAnchorText()
    {
        return $this->getValue('nb_site_target_cta_lang_anchor_text');
    }

    /**
     * Sets the Site Target CTA Lang Anchor Text attribute value.
     * @param null|string $anchor_text New value for attribute
     * @return CNabuDataObject Returns self instance to grant chained setters call.
     */
    public function setAnchorText(string $anchor_text = null) : CNabuDataObject
    {
        $this->setValue('nb_site_target_cta_lang_anchor_text', $anchor_text);
        
        return $this;
    }
}
