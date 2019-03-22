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

namespace nabu\render;

use nabu\provider\adapters\CNabuProviderInterfaceFactoryAdapter;

use nabu\provider\base\CNabuProviderInterfaceDescriptor;

use nabu\provider\interfaces\INabuProviderManager;

use nabu\render\exceptions\ENabuRenderException;
use nabu\render\interfaces\INabuRenderInterface;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.12 Surface
 * @version 3.0.12 Surface
 * @package \nabu\render
 */
class CNabuRenderFactory extends CNabuProviderInterfaceFactoryAdapter
{
    public function createInterface(INabuProviderManager $nb_manager, CNabuProviderInterfaceDescriptor $nb_descriptor)
    {
        return $nb_manager->createRenderInterface($nb_descriptor->getClassName());
    }

    protected function discoverInterface() : bool
    {
        return (parent::discoverInterface() && $this->nb_interface instanceof INabuRenderInterface);
    }

    /**
     * This method render the content passed as parameter and exposes the result in the default output stream.
     */
    public function render()
    {
        if ($this->discoverInterface()) {
            $this->nb_interface->render();
        } else {
            throw new ENabuRenderException(
                ENabuRenderException::ERROR_RENDER_NOT_FOUND,
                array($this->nb_descriptor->getKey())
            );
        }
    }

    /**
     * Pass MIME Type to Render Instance.
     * @param string $mimetype MIME Type to be setted.
     * @return CNabuRenderFactory Returns self pointer to grant chained setter calls.
     * @throws ENabuRenderException Raises an exception if the Render Interface is not setted.
     */
    public function setMIMEType(string $mimetype)
    {
        if ($this->discoverInterface()) {
            $this->nb_interface->setMIMEType($mimetype);
        } else {
            throw new ENabuRenderException(
                ENabuRenderException::ERROR_RENDER_NOT_FOUND,
                array($this->nb_descriptor->getKey())
            );
        }

        return $this;
    }
}
