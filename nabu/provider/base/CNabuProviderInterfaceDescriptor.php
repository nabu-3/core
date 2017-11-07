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

namespace nabu\provider\base;
use nabu\data\CNabuDataObject;
use nabu\provider\interfaces\INabuProviderManager;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\http\managers\base
 */
class CNabuProviderInterfaceDescriptor extends CNabuDataObject
{
    /** @var INabuProviderManager $nb_manager Provider Manager that owns the interface. */
    private $nb_manager = null;

    public function __construct(
        INabuProviderManager $nb_manager,
        string $type,
        string $key,
        string $name,
        string $namespace,
        string $class_name
    ) {
        parent::__construct();

        $this->setManager($nb_manager);
        $this->setType($type);
        $this->setKey($key);
        $this->setName($name);
        $this->setNamespace($namespace);
        $this->setClassName($class_name);
    }

    /**
     * Gets the Manager instance.
     * @return INabuProviderManager Returns the Manager instance.
     */
    public function getManager() : INabuProviderManager
    {
        return $this->nb_manager;
    }

    /**
     * Sets the Manager instance.
     * @param INabuProviderManager $nb_manager Manager instance to be setted.
     * @return CNabuProviderInterfaceDescriptor Returns the self descriptor to grant cascade setter calls.
     */
    public function setManager(INabuProviderManager $nb_manager)
    {
        $this->nb_manager = $nb_manager;

        return $this;
    }

    /**
     * Gets the Interface type.
     * @return int Returns the Interface type.
     */
    public function getType()
    {
        return $this->getValue('interface_type');
    }

    /**
     * Sets the Interface type.
     * @param int $type Interface type.
     * @return CNabuProviderInterfaceDescriptor Returns the self descriptor to grant cascade setter calls.
     */
    public function setType(int $type)
    {
        $this->setValue('interface_type', $type);

        return $this;
    }

    /**
     * Gets the Interface key.
     * @return string Returns the Interface key.
     */
    public function getKey()
    {
        return $this->getValue('interface_key');
    }

    /**
     * Sets the Interface key.
     * @param string $key Interface key.
     * @return CNabuProviderInterfaceDescriptor Returns the self descriptor to grant cascade setter calls.
     */
    public function setKey(string $key)
    {
        $this->setValue('interface_key', $key);

        return $this;
    }

    /**
     * Gets the Interface name.
     * @return string Returns the Interface name.
     */
    public function getName()
    {
        return $this->getValue('interface_name');
    }

    /**
     * Sets the Interface name.
     * @param string $name Interface name.
     * @return CNabuProviderInterfaceDescriptor Returns the self descriptor to grant cascade setter calls.
     */
    public function setName(string $name)
    {
        $this->setValue('interface_name', $name);

        return $this;
    }

    /**
     * Gets the Interface namespace.
     * @return string Returns the Interface namespace.
     */
    public function getNamespace()
    {
        return $this->getValue('interface_namespace');
    }

    /**
     * Sets the Interface namespace.
     * @param string $namespace Interface namespace.
     * @return CNabuProviderInterfaceDescriptor Returns the self descriptor to grant cascade setter calls.
     */
    public function setNamespace($namespace)
    {
        $this->setValue('interface_namespace', $namespace);

        return $this;
    }

    /**
     * Gets the Interface Class name.
     * @return string Returns the Interface Class name.
     */
    public function getClassName()
    {
        return $this->getValue('interface_class_name');
    }

    /**
     * Sets the Interface Class name.
     * @param string $class_name Interface Class name.
     * @return CNabuProviderInterfaceDescriptor Returns the self descriptor to grant cascade setter calls.
     */
    public function setClassName($class_name)
    {
        $this->setValue('interface_class_name', $class_name);

        return $this;
    }
}
