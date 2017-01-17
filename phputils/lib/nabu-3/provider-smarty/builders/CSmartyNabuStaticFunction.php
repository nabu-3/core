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

namespace providers\smarty\builders;

use \providers\smarty\builders\base\CSmartyAbstractFunction;

/**
 * This class represents an URI request.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \providers\smarty\renders
 */
class CSmartyNabuStaticFunction extends CSmartyAbstractFunction
{
    public function execute($content = null, &$repeat = false)
    {
        $retval = '';

        if (!$this->attributeExists('key')) {
            trigger_error("nabu_static: missing 'key' parameter");
        } else {
            $retval = $this->executeStatic();
        }

        return $retval;
    }

    private function executeStatic()
    {
        $retval = false;

        $key = $this->params['key'];

        $nb_language = $this->template->getVariable('nb_language')->value;

        $nb_site = $this->template->getVariable('nb_site')->value;
        $nb_site_static_content = $this->template->getVariable('nb_site_static_content')->value;

        if (is_string($key) &&
            is_array($nb_language) &&
            is_array($nb_site_static_content) &&
            array_key_exists('id', $nb_language) &&
            is_scalar($nb_language_id = $nb_language['id']) &&
            is_array($nb_site) &&
            array_key_exists('static_content_keys', $nb_site) &&
            array_key_exists($key, $nb_site['static_content_keys']) &&
            array_key_exists('pointer', $nb_site['static_content_keys'][$key]) &&
            is_scalar($pointer = $nb_site['static_content_keys'][$key]['pointer']) &&
            is_array($nb_site_static_content) &&
            array_key_exists($pointer, $nb_site_static_content) &&
            is_array($nb_static = $nb_site_static_content[$pointer]) &&
            array_key_exists('translations', $nb_static)
        ) {
            if (($retval = $this->extractStatic($nb_static, $nb_language_id)) === false &&
                array_key_exists('use_alternative', $nb_static)
            ) {
                $retval = $this->extractAlternatives($nb_site, $nb_static, $nb_language_id);
            }
        }

        if ($retval === false) {
            trigger_error("nabu_static: static content indexed by key [$key] not found");
            $retval = '';
        }

        return $retval;
    }

    private function extractStatic($nb_static, $nb_language_id)
    {
        if (array_key_exists($nb_language_id, $nb_static['translations']) &&
            is_array($nb_static['translations'][$nb_language_id]) &&
            array_key_exists('text', $nb_static['translations'][$nb_language_id])
        ) {
            $retval = print_r($nb_static['translations'][$nb_language_id]['text'], true);
        } else {
            $retval = false;
        }

        return $retval;
    }

    private function extractAlternatives($nb_site, $nb_static, $nb_language_id)
    {
        $retval = false;

        $alternative = $nb_static['use_alternative'];
        if ($alternative === 'S') {
            if (array_key_exists($nb_site, 'static_content_use_alternative')) {
                $alternative = $nb_site['static_content_use_alternative'];
            } else {
                $alternative = 'D';
            }
        }

        if ($alternative !== 'D' &&
            array_key_exists('default_language_id', $nb_site) &&
            is_scalar($nb_alternate_lang_id = $nb_site['default_language_id'])
        ) {
            $retval = $this->extractStatic($nb_static, $nb_alternate_lang_id);
        }

        if ($retval === false &&
            $alternative === 'B' &&
            count($nb_static['translations']) > 0
        ) {
            foreach ($nb_static['translations'] as $translation) {
                if (array_key_exists('text', $translation)) {
                    $retval = $translation['text'];
                }
                break;
            }
        }

        return $retval;
    }
}
