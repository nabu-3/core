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

namespace nabu\provider\base;
use nabu\data\CNabuDataObject;
use nabu\data\lang\CNabuLanguage;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\http\managers\base
 */
class CNabuProviderInterfaceDescriptorLanguage extends CNabuDataObject
{
    /** @var CNabuLanguage $nb_language Language instance of tnis translation. */
    private $nb_language = null;

    public function getLanguage()
    {
        return $nb_language;
    }

    public function setLanguage(CNabuLanguage $nb_language)
    {
        $this->nb_language = $nb_language;

        return $this;
    }

    public function getTitle()
    {
        return $this->getValue('interface_lang_title');
    }

    public function setTitle(string $title)
    {
        $this->setValue('interface_lang_title', $title);

        return $this;
    }
}
