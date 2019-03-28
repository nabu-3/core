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

namespace nabu\render\interfaces;
use nabu\data\lang\CNabuLanguage;
use nabu\provider\interfaces\INabuProviderInterface;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\render\interfaces
 */
interface INabuRenderTransformInterface extends INabuProviderInterface
{
    /**
     * Gets current Language.
     * @return CNabuLanguage|null Returns the assigned language or null if none.
     */
    public function getLanguage();
    /**
     * Sets current Language.
     * @param CNabuLanguage $nb_language Language instance to be setted.
     * @return INabuRenderTransformInterface Returns self instance to grant setter cascade.
     */
    public function setLanguage(CNabuLanguage $nb_language);
    /**
     * Abstract method to be inherited and implemented.
     * This method transform the content passed as parameter and exposes the result in the default output stream.
     * @param mixed $source Source content to be transformed.
     */
    public function transform($source);
}
