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
use \Smarty;
use \Smarty_Variable;

/**
 * This class represents an URI request.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \providers\smarty\builders
 */
class CSmartyNabuAssignFunction extends CSmartyAbstractFunction
{
    public function execute($content = null, &$repeat = false)
    {
        $retval = '';

        if (!$this->attributeExists('var')) {
            trigger_error("nabu_assign: missing 'var' parameter");
        } else {
            $actions = array('sitemap', 'cta', 'section', 'medioteca', 'static');
            switch ($this->getFirstOccurence($actions)) {
                case 'sitemap':
                    $retval = $this->executeSitemap();
                    break;
                case 'cta':
                    $retval = $this->executeCTA();
                    break;
                case 'section':
                    $retval = $this->executeSection();
                    break;
                case 'medioteca':
                    $retval = $this->executeMedioteca();
                    break;
                case 'static':
                    $retval = $this->executeStatic();
                    break;
                default:
                    trigger_error("nabu_assign: missing parameter between [" . implode($actions, ', ') . "]");
            }
        }

        return $retval;
    }

    private function executeSitemap()
    {
        $varname = $this->params['var'];
        $sitemap = $this->params['sitemap'];
        //$scope = $this->getScope();

        $nb_site = $this->template->getVariable('nb_site')->value;
        $nb_site_map = $this->template->getVariable('nb_site_map')->value;

        if (is_array($nb_site) &&
            is_array($nb_site_map) &&
            array_key_exists('sitemap_keys', $nb_site) &&
            is_array($nb_site['sitemap_keys']) &&
            array_key_exists($sitemap, $nb_site['sitemap_keys']) &&
            array_key_exists('pointer', $nb_site['sitemap_keys'][$sitemap]) &&
            is_scalar(($pointer = $nb_site['sitemap_keys'][$sitemap]['pointer'])) &&
            array_key_exists($pointer, $nb_site_map)
        ) {
            $nb_root_sitemap = $nb_site_map[$pointer];
            if ($this->attributeExists('level') && is_numeric($level = $this->params['level']) && $level >= 0) {
                for ($i = 0; $nb_root_sitemap !== null && $i <= $level; $i++) {
                    if ($nb_root_sitemap['breadcrumb']) {
                        if ($i < $level) {
                            $nested_sitemap = null;
                            if (is_array($nb_root_sitemap['childs']) &&
                                count($nb_root_sitemap['childs']) > 0
                            ) {
                                foreach ($nb_root_sitemap['childs'] as $child) {
                                    if ($child['breadcrumb']) {
                                        $nested_sitemap = $child;
                                        break;
                                    }
                                }
                            }
                            $nb_root_sitemap = $nested_sitemap;
                        }
                    } else {
                        $nb_root_sitemap = null;
                    }
                }
            }
            $this->template->assign($varname, $nb_root_sitemap);
        } else {
            trigger_error("nabu_assign: required sitemap [$sitemap] not found.");
        }

        return '';
    }

    private function executeCTA()
    {
        $varname = $this->params['var'];
        $cta = $this->params['cta'];
        //$scope = $this->getScope();

        $nb_site_target = $this->template->getVariable('nb_site_target')->value;

        if (is_array($nb_site_target) &&
            array_key_exists('ctas', $nb_site_target) &&
            is_array($nb_site_target['ctas']) &&
            array_key_exists('cta_keys', $nb_site_target) &&
            is_array($nb_site_target['cta_keys']) &&
            array_key_exists($cta, $nb_site_target['cta_keys']) &&
            array_key_exists('pointer', $nb_site_target['cta_keys'][$cta]) &&
            array_key_exists($nb_site_target['cta_keys'][$cta]['pointer'], $nb_site_target['ctas'])
        ) {
            $this->template->assign($varname, $nb_site_target['ctas'][$nb_site_target['cta_keys'][$cta]['pointer']]);
        } else {
            trigger_error("nabu_assign: required CTA [$cta] not found.");
        }

        return '';
    }

    private function executeSection()
    {
        $varname = $this->params['var'];
        $section = $this->params['section'];
        //$scope = $this->getScope();

        $nb_site_target = $this->template->getVariable('nb_site_target')->value;

        if (is_array($nb_site_target) &&
            array_key_exists('sections', $nb_site_target) &&
            is_array($nb_site_target['sections']) &&
            array_key_exists('section_keys', $nb_site_target) &&
            is_array($nb_site_target['section_keys']) &&
            array_key_exists($section, $nb_site_target['section_keys']) &&
            array_key_exists('pointer', $nb_site_target['section_keys'][$section]) &&
            array_key_exists($nb_site_target['section_keys'][$section]['pointer'], $nb_site_target['sections'])
        ) {
            $this->template->assign(
                $varname, $nb_site_target['sections'][$nb_site_target['section_keys'][$section]['pointer']]
            );
        } else {
            trigger_error("nabu_assign: required section [$section] not found.");
        }

        return '';
    }

    private function executeMedioteca()
    {
        $varname = $this->params['var'];
        $medioteca = $this->params['medioteca'];
        $medioteca_array = false;
        //$scope = $this->getScope();

        if (is_string($medioteca)) {
            $nb_mediotecas = $this->template->getVariable('nb_mediotecas')->value;

            if (is_array($nb_mediotecas) &&
                array_key_exists('mediotecas', $nb_mediotecas) &&
                is_array($nb_mediotecas['mediotecas'])
            ) {
                if (array_key_exists($medioteca, $nb_mediotecas['mediotecas'])) {
                    $medioteca_array = $nb_mediotecas['mediotecas'][$medioteca];
                } elseif (array_key_exists('keys', $nb_mediotecas) &&
                    is_array($nb_mediotecas['keys']) &&
                    array_key_exists($medioteca, $nb_mediotecas['keys']) &&
                    array_key_exists('pointer', $nb_mediotecas['keys'][$medioteca]) &&
                    array_key_exists($nb_mediotecas['keys'][$medioteca]['pointer'], $nb_mediotecas['mediotecas'])
                ) {
                    $medioteca_array = $nb_mediotecas['mediotecas'][$nb_mediotecas['keys'][$medioteca]['pointer']];
                }
            }
        } elseif (is_array($medioteca)) {
            $medioteca_array = $medioteca;
        }

        if (is_array($medioteca_array)) {
            if (array_key_exists('item', $this->params) &&
                is_string($item = $this->params['item'])
            ) {
                if (array_key_exists('items', $medioteca_array) &&
                    is_array($medioteca_array['items']) &&
                    array_key_exists($item, $medioteca_array['items'])
                ) {
                    $item_array = $medioteca_array['items'][$item];
                    $this->template->assign($varname, $item_array);
                } elseif (array_key_exists('item_keys', $medioteca_array) &&
                    is_array($medioteca_array['item_keys']) &&
                    array_key_exists($item, $medioteca_array['item_keys']) &&
                    is_array($medioteca_array['item_keys'][$item]) &&
                    array_key_exists('pointer', $medioteca_array['item_keys'][$item]) &&
                    array_key_exists($medioteca_array['item_keys'][$item]['pointer'], $medioteca_array['items'])
                ) {
                    $item_array = $medioteca_array['items'][$medioteca_array['item_keys'][$item]['pointer']];
                    $this->template->assign($varname, $item_array);
                } else {
                    trigger_error("nabu_assign: required medioteca item [$item] not found.");
                }
            } else {
                $this->template->assign($varname, $medioteca_array);
            }
        } elseif (is_string($medioteca_array)) {
            trigger_error("nabu_assign: required medioteca [$medioteca] not found.");
        } else {
            trigger_error("nabu_assign: unexpected value in medioteca param.");
        }

        return '';
    }

    private function executeStatic()
    {
        $varname = $this->params['var'];
        $static = $this->params['static'];

        if (is_string($static)) {
            if (is_array($nb_site = $this->template->getVariable('nb_site')->value) &&
                array_key_exists('static_content_keys', $nb_site) &&
                is_array($nb_site['static_content_keys']) &&
                array_key_exists($static, $nb_site['static_content_keys']) &&
                is_array($nb_site['static_content_keys'][$static]) &&
                array_key_exists('pointer', $nb_site['static_content_keys'][$static]) &&
                ($pointer = $nb_site['static_content_keys'][$static]['pointer']) !== null &&
                is_array($nb_static_content_keys = $this->template->getVariable('nb_site_static_content')->value) &&
                array_key_exists($pointer, $nb_static_content_keys) &&
                is_array($nb_site_static_content = $nb_static_content_keys[$pointer]) &&
                array_key_exists('translation', $nb_site_static_content) &&
                is_array($nb_site_static_content['translation']) &&
                array_key_exists('text', $nb_site_static_content['translation'])
            ) {
                $this->template->assign($varname, $nb_site_static_content['translation']['text']);
            } else {
                trigger_error("nabu_assign: static content indexed by key [$static] not found");
            }
        } else {
            trigger_error("nabu_assign: [static] attribute value is not valid");
        }

        return '';
    }

    private function getScope()
    {
        $scope = Smarty::SCOPE_LOCAL;

        if ($this->attributeExists('scope')) {
            $valid_scopes = array(
                'local' => Smarty::SCOPE_LOCAL,
                'parent' => Smarty::SCOPE_PARENT,
                'root' => Smarty::SCOPE_ROOT,
                'global' => Smarty::SCOPE_GLOBAL,
                'tpl_root' => Smarty::SCOPE_TPL_ROOT,
                'smarty' => Smarty::SCOPE_SMARTY
            );
            if (array_key_exists($this->params['scope'], $valid_scopes)) {
                $scope = $valid_scopes[$this->params['scope']];
            } else {
                trigger_error("nabu_assign: invalid scope value. Root assumed.");
            }
        }

        return $scope;
    }
}
