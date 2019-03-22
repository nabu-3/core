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

namespace nabu\xml\lang;
use nabu\data\CNabuDataObject;
use nabu\data\CNabuDataObjectList;
use nabu\data\lang\interfaces\INabuTranslation;
use nabu\xml\CNabuXMLDataObject;
use nabu\xml\CNabuXMLDataObjectList;

/**
 * Class to manage Translations List as an XML branch.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package nabu\xml\lang
 */
abstract class CNabuXMLTranslationsList extends CNabuXMLDataObjectList
{
    /**
     * Create the XML Translation object filled with their translations.
     * @param INabuTranslation $nb_translation Translation data instance.
     * @return CNabuXMLTranslation Returns a XML instance with the translation instance.
     */
    abstract protected function createXMLTranslationsObject(INabuTranslation $nb_translation) : CNabuXMLTranslation;

    public function __construct(CNabuDataObjectList $nb_data_object)
    {
        parent::__construct($nb_data_object);
    }

    protected static function getTagName(): string
    {
        return 'translations';
    }

    /**
     * This method is declared as final to grant that he cannot be derived and force to call createXMLTranslationsObject
     * as replacement to ensure that child is a translation object.
     * @param CNabuDataObject $nb_child Child data object to create her XML object.
     * @return CNabuXMLDataObject Returns an instance of a descendant class from XNabuXMLDataObject.
     */
    final protected function createXMLChildObject(CNabuDataObject $nb_child = null): CNabuXMLDataObject
    {
        return $this->createXMLTranslationsObject($nb_child);
    }
}
