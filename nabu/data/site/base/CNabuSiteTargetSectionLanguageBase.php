<?php
/* ===========================================================================
 * File generated automatically by Nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/03/07 13:56:40 UTC
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
use \nabu\data\site\CNabuSiteTargetSectionLanguage;
use \nabu\data\site\CNabuSiteTargetSectionLanguageList;
use \nabu\db\CNabuDBInternalObject;

/**
 * Class to manage the entity Site Target Section Language stored in the storage named nb_site_target_section_lang.
 * @author Rafael Gutiérrez Martínez <rgutierrez@wiscot.com>
 * @version 3.0.12 Surface
 * @package \nabu\data\site\base
 */
abstract class CNabuSiteTargetSectionLanguageBase extends CNabuDBInternalObject implements INabuTranslation
{
    use TNabuTranslation;

    /**
     * Instantiates the class. If you fill enough parameters to identify an instance serialized in the storage, then
     * the instance is deserialized from the storage.
     * @param mixed $nb_site_target_section An instance of CNabuSiteTargetSectionLanguageBase or another object
     * descending from \nabu\data\CNabuDataObject which contains a field named nb_site_target_section_id, or a valid ID.
     * @param mixed $nb_language An instance of CNabuSiteTargetSectionLanguageBase or another object descending from
     * \nabu\data\CNabuDataObject which contains a field named nb_language_id, or a valid ID.
     */
    public function __construct($nb_site_target_section = false, $nb_language = false)
    {
        if ($nb_site_target_section) {
            $this->transferMixedValue($nb_site_target_section, 'nb_site_target_section_id');
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
        return 'nb_site_target_section_lang';
    }

    /**
     * Gets SELECT sentence to load a single register from the storage.
     * @return string Return the sentence.
     */
    public function getSelectRegister()
    {
        return ($this->isValueNumeric('nb_site_target_section_id') && $this->isValueNumeric('nb_language_id'))
            ? $this->buildSentence(
                    'select * '
                    . 'from nb_site_target_section_lang '
                   . "where nb_site_target_section_id=%nb_site_target_section_id\$d "
                     . "and nb_language_id=%nb_language_id\$d "
              )
            : null;
    }

    /**
     * Query the storage to retrieve the full list of available languages (those that correspond to existent
     * translations) for $translated and returns an associative array in which each one is of class
     * \nabu\data\lang\CNabuLanguage.
     * @param object $translated Translated object to retrieve languages
     * @return false|null|array Returns an associative array indexed by the language Id, null if no languages are
     * available, or false if $translated cannot be identified.
     */
    public static function getLanguagesForTranslatedObject($translated)
    {
        $nb_site_target_section_id = nb_getMixedValue(
                $translated,
                'nb_site_target_section_id',
                '\\nabu\\data\\site\\CNabuSiteTargetSection'
        );
        if (is_numeric($nb_site_target_section_id)) {
            $retval = CNabuLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select l.* '
                    . 'from nb_language l, nb_site_target_section t1, nb_site_target_section_lang t2 '
                   . 'where t1.nb_site_target_section_id=t2.nb_site_target_section_id '
                     . 'and t1.nb_site_target_section_id=%nb_site_target_section_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_site_target_section_id' => $nb_site_target_section_id
                    )
            );
        } else {
            $retval = new CNabuLanguageList();
        }
        
        return $retval;
    }

    /**
     * Query the storage to retrieve the full list of available translations for $translated and returns an associative
     * array in which each one is of class \nabu\data\site\CNabuSiteTargetSectionLanguage.
     * @param object $translated Translated object to retrieve translations
     * @return false|null|array Returns an associative array indexed by the language Id, null if no languages are
     * available, or false if $translated cannot be identified.
     */
    public static function getTranslationsForTranslatedObject($translated)
    {
        $nb_site_target_section_id = nb_getMixedValue(
                $translated,
                'nb_site_target_section_id',
                '\\nabu\\data\\site\\CNabuSiteTargetSection'
        );
        if (is_numeric($nb_site_target_section_id)) {
            $retval = CNabuSiteTargetSectionLanguage::buildObjectListFromSQL(
                    'nb_language_id',
                    'select t2.* '
                    . 'from nb_language l, nb_site_target_section t1, nb_site_target_section_lang t2 '
                   . 'where t1.nb_site_target_section_id=t2.nb_site_target_section_id '
                     . 'and t1.nb_site_target_section_id=%nb_site_target_section_id$d '
                     . 'and l.nb_language_id=t2.nb_language_id ',
                    array(
                        'nb_site_target_section_id' => $nb_site_target_section_id
                    )
            );
            $retval->iterate(
                function ($key, $nb_translation) use($translated) {
                    $nb_translation->setTranslatedObject($translated);
                }
            );
        } else {
            $retval = new CNabuSiteTargetSectionLanguageList();
        }
        
        return $retval;
    }

    /**
     * Get Site Target Section Id attribute value
     * @return int Returns the Site Target Section Id value
     */
    public function getSiteTargetSectionId()
    {
        return $this->getValue('nb_site_target_section_id');
    }

    /**
     * Sets the Site Target Section Id attribute value
     * @param int $nb_site_target_section_id New value for attribute
     * @return CNabuSiteTargetSectionLanguageBase Returns $this
     */
    public function setSiteTargetSectionId($nb_site_target_section_id)
    {
        if ($nb_site_target_section_id === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$nb_site_target_section_id")
            );
        }
        $this->setValue('nb_site_target_section_id', $nb_site_target_section_id);
        
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
     * @return CNabuSiteTargetSectionLanguageBase Returns $this
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
     * Get Site Target Section Lang Main Image attribute value
     * @return null|string Returns the Site Target Section Lang Main Image value
     */
    public function getMainImage()
    {
        return $this->getValue('nb_site_target_section_lang_main_image');
    }

    /**
     * Sets the Site Target Section Lang Main Image attribute value
     * @param null|string $main_image New value for attribute
     * @return CNabuSiteTargetSectionLanguageBase Returns $this
     */
    public function setMainImage($main_image)
    {
        $this->setValue('nb_site_target_section_lang_main_image', $main_image);
        
        return $this;
    }

    /**
     * Get Site Target Section Lang Title attribute value
     * @return null|string Returns the Site Target Section Lang Title value
     */
    public function getTitle()
    {
        return $this->getValue('nb_site_target_section_lang_title');
    }

    /**
     * Sets the Site Target Section Lang Title attribute value
     * @param null|string $title New value for attribute
     * @return CNabuSiteTargetSectionLanguageBase Returns $this
     */
    public function setTitle($title)
    {
        $this->setValue('nb_site_target_section_lang_title', $title);
        
        return $this;
    }

    /**
     * Get Site Target Section Lang Subtitle attribute value
     * @return null|string Returns the Site Target Section Lang Subtitle value
     */
    public function getSubtitle()
    {
        return $this->getValue('nb_site_target_section_lang_subtitle');
    }

    /**
     * Sets the Site Target Section Lang Subtitle attribute value
     * @param null|string $subtitle New value for attribute
     * @return CNabuSiteTargetSectionLanguageBase Returns $this
     */
    public function setSubtitle($subtitle)
    {
        $this->setValue('nb_site_target_section_lang_subtitle', $subtitle);
        
        return $this;
    }

    /**
     * Get Site Target Section Lang Opening attribute value
     * @return null|string Returns the Site Target Section Lang Opening value
     */
    public function getOpening()
    {
        return $this->getValue('nb_site_target_section_lang_opening');
    }

    /**
     * Sets the Site Target Section Lang Opening attribute value
     * @param null|string $opening New value for attribute
     * @return CNabuSiteTargetSectionLanguageBase Returns $this
     */
    public function setOpening($opening)
    {
        $this->setValue('nb_site_target_section_lang_opening', $opening);
        
        return $this;
    }

    /**
     * Get Site Target Section Lang Content attribute value
     * @return null|string Returns the Site Target Section Lang Content value
     */
    public function getContent()
    {
        return $this->getValue('nb_site_target_section_lang_content');
    }

    /**
     * Sets the Site Target Section Lang Content attribute value
     * @param null|string $content New value for attribute
     * @return CNabuSiteTargetSectionLanguageBase Returns $this
     */
    public function setContent($content)
    {
        $this->setValue('nb_site_target_section_lang_content', $content);
        
        return $this;
    }

    /**
     * Get Site Target Section Lang Footer attribute value
     * @return null|string Returns the Site Target Section Lang Footer value
     */
    public function getFooter()
    {
        return $this->getValue('nb_site_target_section_lang_footer');
    }

    /**
     * Sets the Site Target Section Lang Footer attribute value
     * @param null|string $footer New value for attribute
     * @return CNabuSiteTargetSectionLanguageBase Returns $this
     */
    public function setFooter($footer)
    {
        $this->setValue('nb_site_target_section_lang_footer', $footer);
        
        return $this;
    }

    /**
     * Get Site Target Section Lang Aside attribute value
     * @return null|string Returns the Site Target Section Lang Aside value
     */
    public function getAside()
    {
        return $this->getValue('nb_site_target_section_lang_aside');
    }

    /**
     * Sets the Site Target Section Lang Aside attribute value
     * @param null|string $aside New value for attribute
     * @return CNabuSiteTargetSectionLanguageBase Returns $this
     */
    public function setAside($aside)
    {
        $this->setValue('nb_site_target_section_lang_aside', $aside);
        
        return $this;
    }
}
