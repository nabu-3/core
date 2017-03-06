<?php

/*  Copyright 2009-2011 Rafael Gutierrez Martinez
 *  Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 *  Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

namespace nabu\data\lang\traits;

use nabu\data\CNabuDataObject;
use nabu\data\lang\CNabuLanguage;
use nabu\data\lang\CNabuLanguageList;
use nabu\data\lang\CNabuTranslationList;
use nabu\data\lang\interfaces\INabuTranslation;

/**
 * This trait implements default actions to manage a Translated object in nabu-3.
 * A great number of methods defined in the interface \nabu\lang\interfaces\INabuTranslated
 * are implemented here with a default behavior.
 * You can apply this trait to your own classes to speed up your development,
 * or create your own management.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\lang\traits;
 */
trait TNabuTranslated
{
    /**
     * Language instances list
     * @var CNabuLanguageList
     */
    protected $languages_list = null;

    /**
     * Translation instances list
     * @var CNabuTranslationList
     */
    protected $translations_list = null;

    /**
     * Removed translations instances list
     * @var CNabuTranslationList
     */
    protected $translations_removed = null;

    /**
     * Check if the instance passed as parameter $translation is a valid child translation for this object
     * @param INabuTranslation $translation Translation instance to check
     * @return bool Return true if a valid object is passed as instance or false elsewhere
     */
    protected abstract function checkForValidTranslationInstance($translation);

    protected function __translatedConstruct()
    {
        $this->languages_list = new CNabuLanguageList();
        $this->translations_list = new CNabuTranslationList();
        $this->translations_removed = new CNabuTranslationList();
    }

    /**
     * Gets the language object owned by this object that represents a language entity allowed for them
     * @param CNabuDataObject|int|GUID $nb_language Object containing a valid language ID or and ID directly
     * @return INabuTranslation If this entity owns the requested language returns a language instance
     * else returns null.
     */
    public function getLanguage($nb_language)
    {
        $retval = false;

        $nb_language_id = nb_getMixedValue($nb_language, NABU_LANG_FIELD_ID);
        if (is_numeric($nb_language_id) || nb_isValidGUID($nb_language_id)) {
            $this->getLanguages();
            if ($this->languages_list->containsKey($nb_language_id)) {
                $retval = $this->languages_list->getItem($nb_language_id);
            }
        }

        return $retval;
    }

    /**
     * Add or replace a language object passed as parameter in the languages collection of this entity
     * @param CNabuLanguage $nb_language A instance of a language entity to add in the list of allowed languages
     */
    public function setLanguage(CNabuLanguage $nb_language)
    {
        if ($nb_language->isValueNumeric(NABU_LANG_FIELD_ID) || $nb_language->isValueGUID(NABU_LANG_FIELD_ID)) {
            $nb_language_id = $nb_language->getValue(NABU_LANG_FIELD_ID);
            $this->languages_list->addItem($nb_language);
        }
    }

    /**
     * Check if the instance have at least one language item.
     * @param bool $force If true forces to reload from the database the list of languages.
     * @return bool Returns true if the collection of languages have at least one item.
     */
    public function hasLanguages($force = false)
    {
        $this->getLanguages($force);

        return $this->languages_list->isFilled();
    }

    /**
     * Get the translation child object that represents the translated fields of this entity
     * @param CNabuDataObject|int|GUID $nb_language Object containing a valid language ID or and ID directly
     * @return array|null Returns a child object derivated from this entity that represents
     * the translation part of fields
     */
    public function getTranslation($nb_language)
    {
        $retval = false;

        $nb_language_id = nb_getMixedValue($nb_language, NABU_LANG_FIELD_ID);
        if (is_numeric($nb_language_id) || nb_isValidGUID($nb_language_id)) {
            $this->getTranslations();
            if ($this->translations_list->containsKey($nb_language_id)) {
                $retval = $this->translations_list->getItem($nb_language_id);
            }
        }

        return $retval;
    }

    /**
     * Returns true if the object has the translation for the laguage passed as parameter
     * @param CNabuDataObject|int|GUID $nb_language Object containing a valid language ID or and ID directly
     * @return bool Returns true the language is supported
     */
    public function hasTranslation($nb_language)
    {
        $retval = false;

        $nb_language_id = nb_getMixedValue($nb_language, NABU_LANG_FIELD_ID);
        if (is_numeric($nb_language_id) || nb_isValidGUID($nb_language_id)) {
            $this->getTranslations();
            $retval = $this->translations_list->containsKey($nb_language_id);
        }

        return $retval;
    }

    /**
     * Check if the instance have at least one item in the translations collection.
     * @param bool $force If true, forces to reload from the storage all translations availables.
     * @return bool Returns true if the translations collection have at least one item.
     */
    public function hasTranslations(bool $force = false)
    {
        $this->getTranslations($force);

        return $this->translations_list->isFilled();

    }

    /**
     * Add or replace a translation child object that represents the translated fields of this entity
     * @param INabuTranslation $nb_translation Translation child object to add / replace
     * @return CNabuDataObject Returns the translation setted.
     */
    public function setTranslation(INabuTranslation $nb_translation)
    {
        if ($this->checkForValidTranslationInstance($nb_translation) &&
            ($nb_translation->isValueNumeric(NABU_LANG_FIELD_ID) || $nb_translation->isValueGUID(NABU_LANG_FIELD_ID))
        ) {
            $nb_translation->setTranslatedObject($this);
            $nb_language_id = $nb_translation->getValue(NABU_LANG_FIELD_ID);
            $this->translations_list->addItem($nb_translation);
            if ($this->translations_removed->containsKey($nb_language_id)) {
                $this->translations_removed->removeItem($nb_language_id);
            }
        }

        return $nb_translation;
    }

    /**
     * Remove a translation child object from the internal list of this entity. If after this action
     * you calls updateTranslations method, then it call the delete method to remove definitely
     * this translation from the database
     * @param CNabuDataObject|int|GUID $nb_translation Object containing a valid language ID or and ID directly
     */
    public function removeTranslation($nb_translation)
    {
        if ($this->checkForValidTranslationInstance($nb_translation) &&
            ($nb_translation->isValueNumeric(NABU_LANG_FIELD_ID)||
             $nb_translation->isValueGUID(NABU_LANG_FIELD_ID))
        ) {
            $nb_language_id = $nb_translation->getValue(NABU_LANG_FIELD_ID);
            $this->translations_removed->addItem($nb_translation);
            if ($this->translations_list->containsKey($nb_language_id)) {
                $this->translations_list->removeItem($nb_translation);
            }
        }
    }

    /**
     * Calls internally all translation child objects stocked inside this entity to add / update them in the database.
     * @return int|false Return the number of childs updated or false if no childs found
     */
    public function updateTranslations()
    {
        $this->translations_list->iterate(
            function ($key, $nb_translation)
            {
                return $nb_translation->save();
            }
        );

        $this->translations_removed->iterate(
            function ($key, $nb_translation)
            {
                return $nb_translation->delete();
            }
        );
    }

    /**
     * Overrides getTreeData method to add translations branch.
     * If $nb_language have a valid value, also adds a translation object
     * with current translation pointed by it.
     * @param array $tdata Tree data from main class to be modified.
     * @param int|object $nb_language Instance or Id of the language to be used.
     * @param bool $dataonly Render only field values and ommit class control flags.
     * @return array Returns a multilevel associative array with all data.
     */
    public function appendTranslatedTreeData($tdata, $nb_language = null, $dataonly = false)
    {
        //$tdata = parent::getTreeData($nb_language, $dataonly);

        $this->getTranslations();

        $nb_language_id = nb_getMixedValue($nb_language, NABU_LANG_FIELD_ID);
        if ((is_numeric($nb_language_id) || nb_isValidGUID($nb_language_id)) &&
            $this->translations_list->containsKey($nb_language_id)
        ) {
            $tdata['translation'] = $this->translations_list->getItem($nb_language_id);
        } else {
            $tdata['translation'] = null;
        }

        $tdata['translations'] = $this->translations_list;

        return $tdata;
    }

    /**
     * Overrides refresh method to add translations branch to refresh.
     * @return bool Returns true if transations are empty or refreshed.
     */
    public function appendTranslatedRefresh()
    {
        return $this->getLanguages(true) && $this->getTranslations(true);
    }
}
