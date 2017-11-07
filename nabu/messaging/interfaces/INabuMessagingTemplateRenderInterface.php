<?php

/*  Copyright 2009-2011 Rafael Gutierrez Martinez
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

namespace nabu\messaging\interfaces;
use nabu\data\lang\CNabuLanguage;
use nabu\data\messaging\CNabuMessagingTemplate;
use nabu\messaging\exceptions\ENabuMessagingException;
use nabu\provider\interfaces\INabuProviderInterface;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\messaging\interfaces
 */
interface INabuMessagingTemplateRenderInterface extends INabuProviderInterface
{
    /**
     * Sets the template to be used to render the contents.
     * @param CNabuMessagingTemplate $nb_messaging_template The template to be used.
     * @return INabuMessagingTemplateRenderInterface Returns the self instance to grant cascade setters mechanism.
     */
    public function setTemplate(CNabuMessagingTemplate $nb_messaging_template) : INabuMessagingTemplateRenderInterface;
    /**
     * Sets the language used to render the contents.
     * @param CNabuLanguage $nb_language The language to be used.
     * @return INabuMessagingTemplateRenderInterface Returns the self instance to grant cascade setters mechanism.
     */
    public function setLanguage(CNabuLanguage $nb_language) : INabuMessagingTemplateRenderInterface;
    /**
     * Creates the Subject of a message using this template instance.
     * @param array|null $params Additional params in an associative array to use when build the Subject.
     * @return string Returns the formed string.
     * @throws ENabuMessagingException Raises an exception if Template or Language are not setted previously.
     */
    public function createSubject(array $params = null) : string;
    /**
     * Creates the Body of a message using this template instance as HTML.
     * @param array|null $params Additional params in an associative array to use when build the Body.
     * @return string Returns the formed string.
     * @throws ENabuMessagingException Raises an exception if Template or Language are not setted previously.
     */
    public function createBodyHTML(array $params = null) : string;
    /**
     * Creates the Body of a message using this template instance as Text.
     * @param array|null $params Additional params in an associative array to use when build the Body.
     * @return string Returns the formed string.
     * @throws ENabuMessagingException Raises an exception if Template or Language are not setted previously.
     */
    public function createBodyText(array $params = null) : string;
}
