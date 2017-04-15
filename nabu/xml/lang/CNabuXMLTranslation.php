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

namespace nabu\xml\lang;
use nabu\data\lang\interfaces\INabuTranslation;
use nabu\xml\CNabuXMLDataObject;

/**
 * Class to manage a Translation as an XML element.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package nabu\xml\lang
 */
abstract class CNabuXMLTranslation extends CNabuXMLDataObject
{
    /**
     * Overrides parent constructor to grant that $nb_translation implements the interface INabuTranslation.
     * @param INabuTranslation $nb_translation Data Object instance managed by this XML instance.
     */
    public function __construct(INabuTranslation $nb_translation)
    {
        parent::__construct($nb_translation);
    }

    protected static function getTagName(): string
    {
        return 'translation';
    }
}
