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

/**
 * This Smarty tag is implemented using the Compile model of native sysplugins of Smarty due to their advanced
 * behaviors are not possible to make with public Smarty Plugins engine.
 * For that, is compounded of a set of classes Smarty_Internal_Compile_Nabu_Exists, Smarty_Internal_Compile_nabu_Exists_Else
 * and Smarty_Internal_Compile_NabuExistsclose.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 */
class Smarty_Internal_Compile_Nabu_Exists extends Smarty_Internal_CompileBase
{
    /**
     * Counter to grant that nested calls to {nabu_exists} does not have conflicts with local variables.
     * @var int
     */
    static private $counter = 0;

    public $optional_attributes = array('sitemap', 'cta', 'section', 'medioteca', 'static', 'item');

    public function compile($args, Smarty_Internal_TemplateCompilerBase $compiler, $parameter)
    {
        $_attr = $this->getAttributes($compiler, $args);
        $this->openTag($compiler, 'nabu_exists');
        $compiler->nocache = $compiler->nocache | $compiler->tag_nocache;

        $previous = '';
        $condition = 'false';

        if (array_key_exists('cta', $_attr)) {
            list($previous, $condition) = $this->ctaExists($_attr);
        } elseif (array_key_exists('sitemap', $_attr)) {
            list($previous, $condition) = $this->sitemapExists($_attr);
        } elseif (array_key_exists('medioteca', $_attr)) {
            list($previous, $condition) = $this->mediotecaExists($_attr);
        } elseif (array_key_exists('section', $_attr)) {
            list($previous, $condition) = $this->sectionExists($_attr);
        }

        return "<?php $previous\nif ($condition) {?>";
    }

    private function ctaExists($_attr)
    {
        $varname = '$nb_exists_site_target_' . (++self::$counter);
        $previous = "$varname = \$_smarty_tpl->tpl_vars['nb_site_target']->value;\n";
        $condition = "nb_checkTreePath($varname, 'ctas.' . $_attr[cta]) || "
                   . "(nb_checkTreePath($varname, 'cta_keys.' . $_attr[cta] . '.pointer') && "
                   . "nb_checkTreePath($varname, 'ctas.' . $varname" . "['cta_keys'][$_attr[cta]]['pointer']))"
        ;

        return array($previous, $condition);
    }

    private function sectionExists($_attr)
    {
        $varname = '$nb_exists_site_target_' . (++self::$counter);
        $previous = "$varname = \$_smarty_tpl->tpl_vars['nb_site_target']->value;\n";
        $condition = "nb_checkTreePath($varname, 'sections.' . $_attr[section]) || "
                   . "(nb_checkTreePath($varname, 'section_keys.' . $_attr[section] . '.pointer') && "
                   . "nb_checkTreePath($varname, 'sections.' . $varname" . "['section_keys'][$_attr[section]]['pointer']))"
        ;

        return array($previous, $condition);
    }

    private function sitemapExists($_attr)
    {
        $varname1 = '$nb_exists_site_' . (++self::$counter);
        $varname2 = '$nb_exists_site_map_' . (++self::$counter);
        $previous = "$varname1 = \$_smarty_tpl->tpl_vars['nb_site']->value;\n"
                  . "$varname2 = \$_smarty_tpl->tpl_vars['nb_site_map']->value;\n"
        ;
        $condition = "nb_checkTreePath($varname2, $_attr[sitemap]) || "
                   . "(nb_checkTreePath($varname1, 'sitemap_keys.' . $_attr[sitemap] . '.pointer') && "
                   . "nb_checkTreePath($varname2, $varname1" . "['sitemap_keys'][$_attr[sitemap]]['pointer']))"
        ;

        return array($previous, $condition);
    }

    private function mediotecaExists($_attr)
    {
        if (array_key_exists('item', $_attr)) {
            $varname1 = '$nb_exists_medioteca_sel_' . (++self::$counter);
            $varname2 = '$nb_exists_mediotecas_' . (++self::$counter);
            $previous = "$varname1 = null;\n"
                      . "if (is_string($_attr[medioteca])) {\n"
                      . "    $varname2 = \$_smarty_tpl->tpl_vars['nb_mediotecas']->value;\n"
                      . "    if (nb_checkTreePath($varname2, 'mediotecas.' . $_attr[medioteca])) {"
                      . "        $varname1 = $varname2" . "['mediotecas'][$_attr[medioteca]];\n"
                      . "    } elseif (nb_checkTreePath($varname2, 'keys.' . $_attr[medioteca] . '.pointer') && "
                      . "nb_checkTreePath($varname2, 'mediotecas.' . $varname2" . "['keys'][$_attr[medioteca]]['pointer'])) {\n"
                      . "        $varname1 = $varname2" . "['mediotecas'][$varname2" . "['keys'][$_attr[medioteca]]['pointer']];\n"
                      . "    }\n"
                      . "} elseif (is_array($_attr[medioteca])) {\n"
                      . "    $varname1 = $_attr[medioteca];\n"
                      . "}"
            ;
            $condition = "is_array($varname1) && is_string($_attr[item]) && "
                       . "(nb_checkTreePath($varname1, 'items.' . $_attr[item]) || "
                       . "(nb_checkTreePath($varname1, 'item_keys.' . $_attr[item] . '.pointer') && "
                       . "nb_checkTreePath($varname1, 'items.' . $varname1" . "['item_keys'][$_attr[item]]['pointer'])))"
            ;
        } else {
            $varname = '$nb_exists_mediotecas_' . (++self::$counter);
            $previous = "$varname =\$_smarty_tpl->tpl_vars['nb_mediotecas']->value;\n";
            $condition = "is_string($_attr[medioteca]) && "
                       . "(nb_checkTreePath($varname, 'mediotecas.' . $_attr[medioteca]) || "
                       . "(nb_checkTreePath($varname, 'keys.' . $_attr[medioteca] . '.pointer') && "
                       . "nb_checkTreePath($varname, 'mediotecas.' . $varname" . "['keys'][$_attr[medioteca]]['pointer'])))"
            ;
        }

        return array($previous, $condition);
    }
}

class Smarty_Internal_Compile_Nabu_Exists_Else extends Smarty_Internal_CompileBase
{
    public function compile($args, Smarty_Internal_TemplateCompilerBase $compiler, $parameter)
    {
        $this->closeTag($compiler, array('nabu_exists'));
        $this->openTag($compiler, 'nabu_exists_else');
        return '<?php } else {?>';
    }
}

class Smarty_Internal_Compile_Nabu_Existsclose extends Smarty_Internal_CompileBase
{
    public function compile($args, Smarty_Internal_TemplateCompilerBase $compiler, $parameter)
    {
        $this->closeTag($compiler, array('nabu_exists', 'nabu_exists_else'));
        return '<?php }?>';
    }
}
