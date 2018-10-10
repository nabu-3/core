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

namespace nabu\messaging\managers;
use nabu\core\CNabuObject;
use nabu\core\exceptions\ENabuCoreException;
use nabu\core\interfaces\INabuManager;
use nabu\data\customer\CNabuCustomer;
use nabu\data\customer\traits\TNabuCustomerChild;
use nabu\data\messaging\CNabuMessaging;
use nabu\messaging\CNabuMessagingFactory;
use nabu\messaging\CNabuMessagingFactoryList;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\messaging\managers
 */
class CNabuMessagingPoolManager extends CNabuObject implements INabuManager
{
    use TNabuCustomerChild;

    /**
     * @var CNabuMessagingFactoryList $nb_messaging_factory_list List of factories instantiated, each one
     * for a Messaging instance.
     */
    private $nb_messaging_factory_list = null;

    public function __construct(CNabuCustomer $nb_customer)
    {
        parent::__construct();

        $this->setCustomer($nb_customer);
        $this->nb_messaging_factory_list = new CNabuMessagingFactoryList($nb_customer);
    }

    public function init(): bool
    {
        return true;
    }

    public function finish()
    {
    }

    /**
     * Gets a Messaging Factory instance for a Messaging instance. If Factory instance already exists then returns it.
     * @param CNabuMessaging $nb_messaging Messaging instance that owns the Factory required.
     * @return CNabuMessagingFactory|false Returns the Factory if $nb_messaging is owned by the Customer passed in the
     * constructor, or false if not.
     */
    public function getFactory(CNabuMessaging $nb_messaging)
    {
        $retval = false;
        $nb_customer = $this->getCustomer();

        if ($nb_messaging->validateCustomer($nb_customer)) {
            if (is_numeric($nb_messaging_id = nb_getMixedValue($nb_messaging, NABU_MESSAGING_FIELD_ID))) {
                $retval = $this->nb_messaging_factory_list->getItem($nb_messaging_id);
            }
            if (!$retval) {
                $retval = $this->nb_messaging_factory_list->addItem(new CNabuMessagingFactory($nb_messaging));
            }
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_CUSTOMERS_DOES_NOT_MATCH);
        }

        return $retval;
    }
}
