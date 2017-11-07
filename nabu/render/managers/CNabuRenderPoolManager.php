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

namespace nabu\render\managers;
use nabu\core\CNabuObject;
use nabu\core\interfaces\INabuManager;
use nabu\data\customer\CNabuCustomer;
use nabu\data\customer\traits\TNabuCustomerChild;
use nabu\render\CNabuRenderFactory;
use nabu\render\CNabuRenderFactoryList;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\render\managers
 */
class CNabuRenderPoolManager extends CNabuObject implements INabuManager
{
    use TNabuCustomerChild;

    /** @var CNabuRenderFactoryList List of factories instantiated, each one for a Messaging instance. */
    private $nb_render_factory_list = null;

    public function __construct(CNabuCustomer $nb_customer)
    {
        parent::__construct();

        $this->setCustomer($nb_customer);
        $this->nb_render_factory_list = new CNabuRenderFactoryList($nb_customer);
    }

    public function init(): bool
    {
        return true;
    }

    public function finish()
    {
    }

    /**
     * Gets a Render Factory instance for a mimetype. If Factory instance already exists then returns it.
     * @param string $nb_mimetype Render instance to locate the Factory required.
     * @return CNabuRenderFactory|false Returns the Factory if $nb_mimetype exists, or false if not.
     */
    public function getFactory(string $nb_mimetype)
    {
        $retval = false;

        if (nb_isMIMEType($nb_mimetype)) {
            $retval = $this->nb_messaging_factory_list->getItem($nb_mimetype);
        }
        if (!$retval) {
            $retval = $this->nb_messaging_factory_list->addItem(new CNabuRenderFactory($nb_mimetype));
        }

        return $retval;
    }
}
