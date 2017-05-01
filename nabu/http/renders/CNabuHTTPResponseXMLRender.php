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

namespace nabu\http\renders;
use SimpleXMLElement;
use nabu\core\exceptions\ENabuCoreException;
use nabu\http\interfaces\INabuHTTPResponseRender;
use nabu\http\renders\base\CNabuHTTPResponseRenderAdapter;

/**
 * Class to dump XML as HTTP response.
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.12 Surface
 * @package \nabu\http\renders
 */
class CNabuHTTPResponseXMLRender extends CNabuHTTPResponseRenderAdapter
{
    /** @var SimpleXMLElement $xml XML Data */
    private $xml = null;

    public function __construct($main_render = false)
    {
        parent::__construct($main_render);
    }

    public function render()
    {
        if ($this->xml instanceof SimpleXMLElement) {
            echo $this->xml->asXML();
        } else {
            echo '<?xml version="1.0"?><root/>';
        }
    }

    /**
     * Sets the XML root element.
     * @param SimpleXMLElement $xml XML Root element to be rendered.
     * @return CNabuHTTPResponseXMLRender Returns the self render.
     */
    public function setXML(SimpleXMLElement $xml)
    {
        $this->xml = $xml;

        return $this;
    }

    public function hasValue(string $name): bool
    {
        throw new ENabuCoreException(ENabuCoreException::ERROR_FEATURE_NOT_IMPLEMENTED);
    }

    public function getValue(string $name)
    {
        throw new ENabuCoreException(ENabuCoreException::ERROR_FEATURE_NOT_IMPLEMENTED);
    }

    public function setValue(string $name, $value, $nb_language = null, $cache_key = false): INabuHTTPResponseRender
    {
        throw new ENabuCoreException(ENabuCoreException::ERROR_FEATURE_NOT_IMPLEMENTED);
    }
}
