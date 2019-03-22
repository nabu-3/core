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

namespace nabu\provider\adapters;

use nabu\core\CNabuEngine;
use nabu\data\CNabuDataObject;
use nabu\data\lang\CNabuLanguage;
use nabu\http\CNabuHTTPRequest;
use nabu\http\CNabuHTTPResponse;

use nabu\provider\base\CNabuProviderInterfaceDescriptor;
use nabu\provider\exceptions\ENabuProviderException;
use nabu\provider\interfaces\INabuProviderManager;
use nabu\provider\interfaces\INabuProviderInterface;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\provider\adapters
 */
abstract class CNabuProviderInterfaceFactoryAdapter extends CNabuDataObject
{
    /** @var CNabuProviderInterfaceDescriptor Valid Descriptor instance. */
    protected $nb_descriptor = null;
    /** @var INabuProviderInterface Valid Interface instance. */
    protected $nb_interface = null;
    /** @var CNabuLanguage Language to the output. */
    protected $nb_language = null;

    /**
     * Abstract method to be inherited and implemented.
     * This method invoques the Manager instance to create a new Interface.
     * @param INabuProviderManager $nb_manager The Manager instance to ve invoqued.
     * @param CNabuProviderInterfaceDescriptor $nb_descriptor The Descriptor instance
     * identifying the Interface requested.
     * @return INabuProviderInterface|false Returns the new Interface or false it's not allowed.
     */
    abstract protected function createInterface(
        INabuProviderManager $nb_manager,
        CNabuProviderInterfaceDescriptor $nb_descriptor
    );

    /**
     * Initializes the Factory.
     * @param CNabuProviderInterfaceDescriptor $nb_descriptor Interface Descriptor.
     */
    public function __construct(CNabuProviderInterfaceDescriptor $nb_descriptor)
    {
        parent::__construct();

        $this->nb_descriptor = $nb_descriptor;
        $this->setValue('nb_interface_id', $this->nb_descriptor->getKey());
    }

    /**
     * Discover the Render Transform Interface.
     * @return bool If the Interface is discovered then returns true.
     * @throws ENabuProviderException Raises an exception if the designated Render Transform is not valid or applicable.
     */
    protected function discoverInterface() : bool
    {
        $nb_engine = CNabuEngine::getEngine();

        if (!($this->nb_interface instanceof INabuProviderInterface) &&
            ($nb_manager = $this->nb_descriptor->getManager()) instanceof INabuProviderManager
        ) {
            $this->nb_interface = $this->createInterface($nb_manager, $this->nb_descriptor);
        }

        return ($this->nb_interface instanceof INabuProviderInterface);
    }

    /**
     * Gets the Interface instance assigned to this Factory
     * @return INabuProviderInterface|null Returns a valid Interface if assigned or null if not.
     */
    public function getInterface()
    {
        return $this->nb_interface;
    }

    /**
     * Gets current Language.
     * @return CNabuLanguage|null Returns the assigned language or null if none.
     */
    public function getLanguage()
    {
        return $this->nb_language;
    }

    /**
     * Sets current Language.
     * @param CNabuLanguage $nb_language Language instance to be setted.
     * @return CNabuProviderInterfaceFactoryAdapter Returns self instance to grant setter cascade.
     */
    public function setLanguage(CNabuLanguage $nb_language)
    {
        $this->nb_language = $nb_language;

        return $this;
    }

    /**
     * Pass Request instance to managed interface.
     * @param CNabuHTTPRequest $nb_request Request instance to be passed.
     * @return CNabuProviderInterfaceFactoryAdapter Returns self pointer to grant chained setters.
     * @throws ENabuProviderException Raises an exception if the Interface is not setted.
     */
    public function setRequest(CNabuHTTPRequest $nb_request) : CNabuProviderInterfaceFactoryAdapter
    {
        if ($this->discoverInterface()) {
            $this->nb_interface->setRequest($nb_request);
        } else {
            throw new ENabuProviderException(ENabuProviderException::ERROR_INTERFACE_NOT_SETTED);
        }

        return $this;
    }

    /**
     * Pass Response instance to managed interface.
     * @param CNabuHTTPResponse|null $nb_response Response instance to be passed.
     * @return CNabuProviderInterfaceFactoryAdapter Returns self pointer to grant chained setters.
     * @throws ENabuProviderException Raises an exception if the Interface is not setted.
     */
    public function setResponse(CNabuHTTPResponse $nb_response = null) : CNabuProviderInterfaceFactoryAdapter
    {
        if ($this->discoverInterface()) {
            $this->nb_interface->setResponse($nb_response);
        } else {
            throw new ENabuProviderException(ENabuProviderException::ERROR_INTERFACE_NOT_SETTED);
        }

        return $this;
    }
}
