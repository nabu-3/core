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

namespace providers\smarty;
use Smarty;
use nabu\data\CNabuDataObject;

/**
 * Class to manage a Smarty model fragment.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \providers\smarty
 */
class CSmartyModelFragment extends CNabuDataObject
{
    /**
     * Instance of CSmartyModel that owns this fragment.
     * @var CSmartyModel
     */
    private $nb_smarty_model = null;
    /**
     * Path to the Smarty Template file
     */
    private $template_path = null;

    public function __construct(CSmartyModel $model, $path)
    {
        $this->nb_smarty_model = $model;
        $this->template_path = $path;
    }

    /**
     * Gets the Model Fragment key attribute value
     * @return string Returns the key attribute value
     */
    public function getKey()
    {
        return $this->getValue('nb_smarty_model_fragment_key');
    }

    /**
     * Sets the Model Fragment key attribute value
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
        $this->setValue('nb_smarty_model_fragment_key', $key);

        return $this;
    }

    public function build($params, Smarty $smarty)
    {
        return '<?php $_smarty_tpl->_subTemplateRender("file:' . $this->template_path . '", '
             . '$_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, '
             . $this->exportArray($params) . ', ' // "array('var01'=>'Rafa'), "
             . '0, false);?>'
        ;
    }

    private function exportArray($array, $empty_value = 'null')
    {
        $output = '';

        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $output .= (strlen($output) === 0 ? '' : ', ') . "'$key' => $value";
            }
            $output = 'array(' . $output . ')';
        } else if (is_string($empty_value)) {
            $output = $empty_value;
        }

        return $output;
    }
}
