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

namespace nabu\http\managers;
use nabu\core\exceptions\ENabuCoreException;
use nabu\data\CNabuDataObject;
use nabu\http\app\base\CNabuHTTPApplication;

/**
 * This class acts as a render descriptor to know how it works and obtain all class names.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \nabu\http\managers
 */
class CNabuHTTPRenderDescriptor extends CNabuDataObject
{
    /**
     * Get Descriptor key attribute value
     * @return string Returns the Descriptor key.
     */
    public function getKey()
    {
        return $this->getValue('nb_http_render_descriptor_key');
    }

    /**
     * Sets the Descriptor key attribute value
     * @param int $key New value for attribute
     * @return CNabuHTTPRenderDescriptor Returns $this
     */
    public function setKey($key)
    {
        if ($key === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$key")
            );
        }
        $this->setValue("nb_http_render_descriptor_key", $key);

        return $this;
    }

    /**
     * Get Descriptor Class Name attribute value
     * @return string Returns the Descriptor Class Name.
     */
    public function getClassName()
    {
        return $this->getValue('nb_http_render_descriptor_class_name');
    }

    /**
     * Sets the Descriptor class name attribute value
     * @param int $class_name New value for attribute
     * @return CNabuHTTPRenderDescriptor Returns $this
     */
    public function setClassName($class_name)
    {
        if ($class_name === null) {
            throw new ENabuCoreException(
                    ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN,
                    array("\$class_name")
            );
        }

        $implements_list = class_implements($class_name, true);

        if (is_array($implements_list) &&
            array_search('nabu\http\interfaces\INabuHTTPResponseRender', $implements_list)
        ) {
            $this->setValue("nb_http_render_descriptor_class_name", $class_name);
        } else {
            throw new ENabuCoreException(ENabuCoreException::ERROR_INVALID_RENDER_DESCRIPTOR_CLASS);
        }

        return $this;
    }

    public function createRender(CNabuHTTPApplication $nb_application)
    {
        $retval = false;

        if ($this->isValueString('nb_http_render_descriptor_class_name')) {
            $class_name = $this->getValue('nb_http_render_descriptor_class_name');
            $retval = new $class_name($nb_application);
        }

        return $retval;
    }
}
