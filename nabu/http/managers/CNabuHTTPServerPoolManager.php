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

namespace nabu\http\managers;

use nabu\core\CNabuObject;

use nabu\core\interfaces\INabuManager;

use nabu\data\customer\CNabuCustomer;

use nabu\data\customer\traits\TNabuCustomerChild;

use nabu\http\CNabuHTTPServerFactory;
use nabu\http\CNabuHTTPServerFactoryList;

use nabu\http\descriptors\CNabuHTTPServerInterfaceDescriptor;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\http
 */
class CNabuHTTPServerPoolManager extends CNabuObject implements INabuManager
{
    /** @var CNabuHTTPServerFactoryList List of HTTP Server factories instantiated, each one for a HTTP Server instance. */
    private $nb_http_server_factory_list = null;

    public function __construct()
    {
        parent::__construct();

        $this->nb_http_server_factory_list = new CNabuHTTPServerFactoryList();
    }

    public function init() : bool
    {
        return true;
    }

    public function finish()
    {
    }

    /**
     * Gets a HTTP Server Factory instance for a Descriptor. If Factory instance already exists then returns it.
     * @param CNabuHTTPServerInterfaceDescriptor $nb_descriptor HTTP Server Descriptor instance to locate the required Factory.
     * @return CNabuHTTPServerFactory|false Returns the Factory if exists, or false if not.
     */
    public function getHTTPServerFactory(CNabuHTTPServerInterfaceDescriptor $nb_descriptor)
    {
        if (!($retval = $this->nb_http_server_factory_list->getItem($nb_descriptor->getKey()))) {
            $retval = $this->nb_http_server_factory_list->addItem(new CNabuHTTPServerFactory($nb_descriptor));
        }

        return $retval;
    }


}
