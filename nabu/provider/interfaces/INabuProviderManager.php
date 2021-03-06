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

namespace nabu\provider\interfaces;
use nabu\core\interfaces\INabuApplication;
use nabu\provider\exceptions\ENabuProviderException;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.9 Surface
 * @version 3.0.9 Surface
 * @package \nabu\core\interfaces
 */
interface INabuProviderManager
{
    /**
     * Enables the manager to operate with it.
     * @return bool Returns true if the manager has enabled or false if not.
     */
    public function enableManager();
    /**
     * Gets the vendor key to identify this manager.
     * @return string Returns the assigned vendor key.
     */
    public function getVendorKey();
    /**
     * Gets the module key to identify this manager.
     * @return string Returns the assigned module key.
     */
    public function getModuleKey();
    /**
     * Gets the combined key to identify this manager as "vendor:module"
     * @return string Returns the combined key.
     * @throws ENabuProviderException Raises an exception if vendor or module keys are empty or invalid.
     */
    public function getComplexKey();
    /**
     * Register an application instance in the module. This method is responsible to connect both module and
     * application instances to work together.
     * @param INabuApplication $nb_application Application instance to be registered.
     * @return INabuProviderManager Returns the same manager instance to grant cascade setter calls.
     */
    public function registerApplication(INabuApplication $nb_application);
}
