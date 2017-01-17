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

use nabu\core\CNabuEngine;
use nabu\http\app\base\CNabuHTTPApplication;
use providers\smarty\CSmartyManager;
use providers\smarty\builders\CSmartyNabuModelCompiler;

/**
 * This function is a wrapper to call CSmartyNabuModelCompiler that implements all functionalities.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 */
function smarty_compiler_nabu_model($params, Smarty $smarty)
{
    $compiler = new CSmartyNabuModelCompiler($params, $smarty);

    return $compiler->execute();
}

function smarty_compiler_nabu_generic_model($params, Smarty $smarty, $fragment)
{
    $nb_application = CNabuEngine::getEngine()->getApplication();
    if (($nb_application instanceof CNabuHTTPApplication) &&
        ($nb_smarty_manager = $nb_application->getManager(SMARTY_MANAGER_KEY)) &&
        ($nb_smarty_manager instanceof CSmartyManager) &&
        ($nb_smarty_model = $nb_smarty_manager->getCurrentModel()) !== null &&
        ($nb_smarty_model->isValidFragment($fragment))
    ) {
        return $nb_smarty_model->buildFragment($fragment, $params, $smarty);
    } else {
        throw new \SmartyCompilerException("Model fragment [$fragment] cannot be accessed");
    }
}

function smarty_compiler_nabu_form($params, Smarty $smarty)
{
    return smarty_compiler_nabu_generic_model($params, $smarty, 'form');
}

function smarty_compiler_nabu_formclose($params, Smarty $smarty)
{
    return smarty_compiler_nabu_generic_model($params, $smarty, 'formclose');
}

function smarty_compiler_nabu_form_variable($params, Smarty $smarty)
{
    return smarty_compiler_nabu_generic_model($params, $smarty, 'form_variable');
}

function smarty_compiler_nabu_form_fieldset($params, Smarty $smarty)
{
    return smarty_compiler_nabu_generic_model($params, $smarty, 'form_fieldset');
}

function smarty_compiler_nabu_form_fieldsetclose($params, Smarty $smarty)
{
    return smarty_compiler_nabu_generic_model($params, $smarty, 'form_fieldsetclose');
}

function smarty_compiler_nabu_form_row($params, Smarty $smarty)
{
    return smarty_compiler_nabu_generic_model($params, $smarty, 'form_row');
}

function smarty_compiler_nabu_form_rowclose($params, Smarty $smarty)
{
    return smarty_compiler_nabu_generic_model($params, $smarty, 'form_rowclose');
}

function smarty_compiler_nabu_form_textbox($params, Smarty $smarty)
{
    return smarty_compiler_nabu_generic_model($params, $smarty, 'form_textbox');
}

function smarty_compiler_nabu_form_checkbox($params, Smarty $smarty)
{
    return smarty_compiler_nabu_generic_model($params, $smarty, 'form_checkbox');
}

function smarty_compiler_nabu_form_radiobox($params, Smarty $smarty)
{
    return smarty_compiler_nabu_generic_model($params, $smarty, 'form_radiobox');
}

function smarty_compiler_nabu_form_select($params, Smarty $smarty)
{
    return smarty_compiler_nabu_generic_model($params, $smarty, 'form_select');
}

function smarty_compiler_nabu_form_static($params, Smarty $smarty)
{
    return smarty_compiler_nabu_generic_model($params, $smarty, 'form_static');
}

function smarty_compiler_nabu_form_command($params, Smarty $smarty)
{
    return smarty_compiler_nabu_generic_model($params, $smarty, 'form_command');
}

function smarty_compiler_nabu_form_commands($params, Smarty $smarty)
{
    return smarty_compiler_nabu_generic_model($params, $smarty, 'form_commands');
}

function smarty_compiler_nabu_form_commandsclose($params, Smarty $smarty)
{
    return smarty_compiler_nabu_generic_model($params, $smarty, 'form_commandsclose');
}

function smarty_compiler_nabu_navbar($params, Smarty $smarty)
{
    return smarty_compiler_nabu_generic_model($params, $smarty, 'navbar');
}

function smarty_compiler_nabu_navbarclose($params, Smarty $smarty)
{
    return smarty_compiler_nabu_generic_model($params, $smarty, 'navbarclose');
}

function smarty_compiler_nabu_navigation($params, Smarty $smarty)
{
    return smarty_compiler_nabu_generic_model($params, $smarty, 'navigation');
}

function smarty_compiler_nabu_breadcrumb($params, Smarty $smarty)
{
    return smarty_compiler_nabu_generic_model($params, $smarty, 'breadcrumb');
}

function smarty_compiler_nabu_table($params, Smarty $smarty)
{
    return smarty_compiler_nabu_generic_model($params, $smarty, 'table');
}

function smarty_compiler_nabu_tree($params, Smarty $smarty)
{
    return smarty_compiler_nabu_generic_model($params, $smarty, 'tree');
}
