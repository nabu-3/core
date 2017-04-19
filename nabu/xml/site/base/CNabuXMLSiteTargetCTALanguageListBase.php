<?php
/* ===========================================================================
 * File generated automatically by nabu-3.
 * You can modify this file if you need to add more functionalities.
 * ---------------------------------------------------------------------------
 * Created: 2017/04/19 12:55:26 UTC
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

namespace nabu\xml\site\base;

use \nabu\data\lang\interfaces\INabuTranslation;
use \nabu\data\site\CNabuSiteTargetCTALanguageList;
use \nabu\xml\lang\CNabuXMLTranslation;
use \nabu\xml\lang\CNabuXMLTranslationsList;
use \nabu\xml\site\CNabuXMLSiteTargetCTALanguage;

/**
 * Class to manage the Site Target CTA List as a XML branch.
 * @author Rafael Gutiérrez Martínez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\xml\site\base
 */
abstract class CNabuXMLSiteTargetCTALanguageListBase extends CNabuXMLTranslationsList
{
    /**
     * Instantiates the class. Receives as parameter a qualified CNabuSiteTargetCTALanguageList class.
     * @param CNabuSiteTargetCTALanguageList $nb_site_target_cta_lang $this->entity_name instance to be managed as XML
     */
    public function __construct(CNabuSiteTargetCTALanguageList $nb_site_target_cta_lang)
    {
        parent::__construct($nb_site_target_cta_lang);
    }

    /**
     * Create the XML Translation object filled with their translations.
     * @param INabuTranslation $nb_translation Translation data instance.
     * @return CNabuXMLTranslation Returns a XML instance with the translation instance.
     */
    protected function createXMLTranslationsObject(INabuTranslation $nb_translation) : CNabuXMLTranslation
    {
        return new CNabuXMLSiteTargetCTALanguage($nb_translation);
    }
}
