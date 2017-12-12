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

namespace nabu\render;
use nabu\provider\adapters\CNabuProviderInterfaceFactoryAdapter;
use nabu\provider\base\CNabuProviderInterfaceDescriptor;
use nabu\provider\interfaces\INabuProviderManager;
use nabu\render\exceptions\ENabuRenderException;
use nabu\render\interfaces\INabuRenderTransformInterface;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\render
 */
class CNabuRenderTransformFactory extends CNabuProviderInterfaceFactoryAdapter
{
    public function createInterface(INabuProviderManager $nb_manager, CNabuProviderInterfaceDescriptor $nb_descriptor)
    {
        return $nb_manager->createTransformInterface($nb_descriptor->getClassName());
    }

    public function transform($source)
    {
        $this->discoverInterface();

        if ($this->nb_interface instanceof INabuRenderTransformInterface) {
            $this->nb_interface->transform($source);
        } else {
            throw new ENabuRenderException(
                ENabuRenderException::ERROR_RENDER_TRANSFORM_NOT_FOUND,
                array($this->nb_descriptor->getKey())
            );
        }
    }
}
