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
use SimpleXMLElement;
use nabu\data\lang\interfaces\INabuTranslated;
use nabu\xml\CNabuXMLDataObject;

/**
 * Abstract class to manage XML translated elements.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package nabu\xml\lang
 */
abstract class CNabuXMLTranslated extends CNabuXMLDataObject
{
    /**
     * Create the XML Translations List object filled with their translations.
     * @return CNabuXMLTranslationsList Return a instance XML list with all translations.
     */
    abstract protected function createXMLTranslationsObject() : CNabuXMLTranslationsList;

    public function __construct(INabuTranslated $nb_translated = null)
    {
        parent::__construct($nb_translated);
    }

    protected function getChilds(SimpleXMLElement $element)
    {
        if (isset($element->translations)) {
            $xml = $this->createXMLTranslationsObject();
            $xml->collect($element->translations);
        }
    }

    protected function setChilds(SimpleXMLElement $element)
    {
        $xml = $this->createXMLTranslationsObject();
        $xml->build($element);
    }
}
