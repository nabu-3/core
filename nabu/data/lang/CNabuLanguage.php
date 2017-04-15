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

namespace nabu\data\lang;

use \nabu\data\lang\base\CNabuLanguageBase;

/**
 * Class to manage a language instance
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 * @package name
 */
class CNabuLanguage extends CNabuLanguageBase
{
    const LANGUAGE_ENABLED = 'T';
    const LANGUAGE_DISABLED = 'F';
    const LANGUAGE_IS_API = 'T';
    const LANGUAGE_IS_NATURAL = 'F';

    public function isEnabled()
    {
        return $this->getEnabled() === self::LANGUAGE_ENABLED;
    }

    public function isDisabled()
    {
        return $this->getEnabled() !== self::LANGUAGE_ENABLED;
    }

    public function enable()
    {
        $this->setEnabled(self::LANGUAGE_ENABLED);

        return $this;
    }

    public function disable()
    {
        $this->setDisabled(self::LANGUAGE_DISABLED);

        return $this;
    }

    /**
     * Get natural languages list in the storage where the field 'nb_language_id' is the index, and each
     * value is an instance of class CNabuLanguageBase or inherited.
     * @return mixed Returns and array with all items.
     */
    public static function getNaturalLanguages()
    {
        return forward_static_call(
                array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_language_id',
                'select * from nb_language where nb_language_is_api=\'F\''
        );
    }

    /**
     * Get API languages list in the storage where the field 'nb_language_id' is the index, and each
     * value is an instance of class CNabuLanguageBase or inherited.
     * @return mixed Returns and array with all items.
     */
    public static function getAPILanguages()
    {
        return forward_static_call(
                array(get_called_class(), 'buildObjectListFromSQL'),
                'nb_language_id',
                'select * from nb_language where nb_language_is_api=\'T\''
        );
    }

    /**
     * Check if the instance contains a natural language.
     * @return bool Returns true if the instance is a natural language.
     */
    public function isNaturalLanguage()
    {
        return $this->getIsApi() === self::LANGUAGE_IS_NATURAL;
    }

    /**
     * Check if the instance contains an API language.
     * @return bool Returns true if the instance is an API language.
     */
    public function isAPILanguage()
    {
        return $this->getIsApi() === self::LANGUAGE_IS_API;
    }
}
