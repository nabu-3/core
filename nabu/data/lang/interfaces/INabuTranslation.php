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

namespace nabu\data\lang\interfaces;

use \nabu\data\lang\interfaces\INabuTranslated;

/**
 * This interface have the mission to group all classes that represents
 * the translations of parent classes. Some processes of nabu-3 uses this interface
 * to determine if a object is a valid translation of another.
 * We encourage to use this interface to have a well performance of translations.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\lang\interfaces;
 */
interface INabuTranslation
{
    /**
     * Gets the Parent translated instance.
     * @return INabuTranslated Returns the parent translated instance.
     */
    public function getTranslatedObject();
    
    /**
     * Sets the Parent translated instance that owns this instance.
     * @param INabuTranslated $nb_parent_translated
     * @return INabuTranslated Returns this object.
     */
    public function setTranslatedObject(INabuTranslated $nb_parent_translated);
}
