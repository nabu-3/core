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

namespace nabu\render\descriptors;
use nabu\provider\CNabuProviderFactory;
use nabu\provider\base\CNabuProviderInterfaceDescriptor;
use nabu\provider\interfaces\INabuProviderManager;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\render\descriptors
 */
class CNabuRenderInterfaceDescriptor extends CNabuProviderInterfaceDescriptor
{
    /** @var string MIME Type of this Descriptor. */
    private $mimetype;

    public function __construct(
        INabuProviderManager $nb_manager,
        string $key,
        string $name,
        string $namespace,
        string $class_name,
        string $mimetype
    ) {
        parent::__construct(
            $nb_manager, CNabuProviderFactory::INTERFACE_RENDER, $key, $name, $namespace, $class_name
        );

        $this->mimetype = $mimetype;
    }

    /**
     * Gets the MIME Type of this descriptor.
     * @return string Returns the MIME Type.
     */
    public function getMIMEType(): string
    {
        return $this->mimetype;
    }
}
