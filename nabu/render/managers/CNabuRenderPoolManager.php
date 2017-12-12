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
use nabu\render\CNabuRenderTransformFactory;
use nabu\render\CNabuRenderTransformFactoryList;
use nabu\render\descriptors\CNabuRenderInterfaceDescriptor;
use nabu\render\descriptors\CNabuRenderTransformInterfaceDescriptor;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\render\managers
 */
class CNabuRenderPoolManager extends CNabuObject implements INabuManager
{
    use TNabuCustomerChild;

    /** @var CNabuRenderFactoryList List of Render factories instantiated, each one for a Render instance. */
    private $nb_render_factory_list = null;
    /** @var CNabuRenderTransformFactoryList List of Render Transform factories instantiated, each one for a Render
     * Transform instance. */
    private $nb_render_transform_factory_list = null;

    public function __construct(CNabuCustomer $nb_customer)
    {
        parent::__construct();

        $this->setCustomer($nb_customer);
        $this->nb_render_factory_list = new CNabuRenderFactoryList($nb_customer);
        $this->nb_render_transform_factory_list = new CNabuRenderTransformFactoryList($nb_customer);
    }

    public function init(): bool
    {
        return true;
    }

    public function finish()
    {
    }

    /**
     * Gets a Render Factory instance for a Descriptor. If Factory instance already exists then returns it.
     * @param CNabuRenderInterfaceDescriptor $nb_descriptor Render Descriptor instance to locate the required Factory.
     * @return CNabuRenderFactory|false Returns the Factory if $nb_mimetype exists, or false if not.
     */
    public function getRenderFactory(CNabuRenderInterfaceDescriptor $nb_descriptor)
    {
        if (!($retval = $this->nb_render_factory_list->getItem($nb_descriptor->getKey()))) {
            $retval = $this->nb_render_factory_list->addItem(new CNabuRenderFactory($nb_descriptor));
        }

        return $retval;
    }

    /**
     * Gets a Render Transform Factory instance for a Descriptor. If Factory instance already exists then returns it.
     * @param CNabuRenderTransformInterfaceDescriptor $nb_descriptor Render Transform Descriptor instance to locate
     * the required Factory.
     * @return CNabuRenderTransformFactory|false Returns the Factory if exists, or false if not.
     */
    public function getTransformFactory(CNabuRenderTransformInterfaceDescriptor $nb_descriptor)
    {
        if (!($retval = $this->nb_render_transform_factory_list->getItem($nb_descriptor->getKey()))) {
            $retval = $this->nb_render_transform_factory_list->addItem(new CNabuRenderTransformFactory($nb_descriptor));
        }

        return $retval;
    }
}
