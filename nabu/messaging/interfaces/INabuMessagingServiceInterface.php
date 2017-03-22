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

namespace nabu\messaging\interfaces;
use nabu\data\messaging\CNabuMessagingService;
use nabu\provider\interfaces\INabuProviderInterface;

/**
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\messaging\interfaces
 */
interface INabuMessagingServiceInterface extends INabuProviderInterface
{
    /**
     * Gets the current hash and, if none exists, then creates it.
     * @return string Retuns a valid unique hash (GUID) to identify this instance.
     */
    public function getHash();

    /**
     * Initialize the interface instance.
     * @return bool Return true if the instance is initialized.
     */
    public function init();

    /**
     * Open the connection of this interface.
     * @param CNabuMessagingService $nb_messaging_service Messaging Service instance with connection params.
     * @return bool Returns true if the connection is made.
     */
    public function connect(CNabuMessagingService $nb_messaging_service);

    /**
     * Close the connection of this interface.
     */
    public function disconnect();

    /**
     * Finish the interface instance.
     */
    public function finish();
}
