<?php

/** @license
 *  Copyright 2019-2011 Rafael Gutierrez Martinez
 *  Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 *  Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 *  Copyright 2017 nabu-3 Group
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

namespace nabu\data\lang\interfaces;

use \nabu\data\CNabuDataObject;
use \nabu\data\lang\CNabuLanguage;

/**
 * This interface group all classes that have translations associated to it.
 * You can use the trait \nabu\lang\traits\TNabuTranslated to speed up
 * your implementation or redefine at your way these methods.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package \nabu\lang\interfaces;
 */
interface INabuTranslated
{
    /**
     * Gets the language object owned by this object that represents a language entity allowed for them
     * @param CNabuDataObject|int $nb_language Object containing a valid language ID or and ID directly
     * @return CNabuLanguage If this entity owns the requested language returns a language instance else returns null
     */
    public function getLanguage($nb_language);

    /**
     * Gets a full list of all allowed languages for this entity
     * @param bool $force If true, forces this function to reload entire list of allowed languages from the database
     * @return array|null If allowed languages found, returns an associative array where the index
     * if the language ID and the value is an instance of type \nabu\lang\CNabuLanguage representing each language.
     */
    public function getLanguages($force = false);

    /**
     * Check if the instance have at least one language item.
     * @param bool $force If true forces to reload from the database the list of languages.
     * @return bool Returns true if the collection of languages have at least one item.
     */
    public function hasLanguages($force = false);

    /**
     * Add or replace a language object passed as parameter in the languages collection of this entity
     * @param CNabuLanguage $nb_language A instance of a language entity to add in the list of allowed languages
     */
    public function setLanguage(CNabuLanguage $nb_language);

    /**
     * Get the translation child object that represents the translated fields of this entity
     * @param CNabuDataObject|int $nb_language Object containing a valid language ID or and ID directly
     * @return array|null Returns a child object derivated from this entity that represents
     * the translation part of fields.
     */
    public function getTranslation($nb_language);

    /**
     * Get the full list of translations available for this entity
     * @param bool $force If true force to reload from database
     * @return array|null Return an associative array of child translation objects of this entity
     * that represents the translation part of fields or null if array is empty
     */
    public function getTranslations($force = false);

    /**
     * Returns true if the object has the translation for the laguage passed as parameter
     * @param CNabuDataObject|int $nb_language Object containing a valid language ID or and ID directly
     * @return bool Returns true the language is supported
     */
    public function hasTranslation($nb_language);

    /**
     * Check if the instance have at least one item in the translations collection.
     * @param bool $force If true, forces to reload from the storage all translations availables.
     * @return bool Returns true if the translations collection have at least one item.
     */
    public function hasTranslations(bool $force = false);

    /**
     * Creates a new translation instance. I the translation already exists then replaces ancient translation
     * with this new.
     * @param int|GUID|CNabuDataObject $nb_language A valid Id or object containing a nb_language_id field to identify
     * the language of new translation.
     * @return mixed Returns the created instance to store translation or null if not valid language was
     * provided.
     */
    public function newTranslation($nb_language);

    /**
     * Add or replace a translation child object that represents the translated fields of this entity
     * @param INabuTranslation $nb_translation Translation child object to add / replace
     */
    public function setTranslation(INabuTranslation $nb_translation);

    /**
     * Remove a translation child object from the internal list of this entity. If after this action
     * you calls updateTranslations method, then it call the delete method to remove definitely
     * this translation from the database
     * @param mixed $nb_translation
     */
    public function removeTranslation($nb_translation);

    /**
     * Calls internally all translation child objects stocked inside this entity to add / update them in the database
     * @return int|false Return the number of childs updated or false if no childs found
     */
    public function updateTranslations();
}
