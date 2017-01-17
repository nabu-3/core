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

use Smarty;
use SmartyCompilerException;
use nabu\core\CNabuEngine;
use providers\smarty\CSmartyManager;
use providers\smarty\builders\base\CSmartyAbstractCompiler;

/**
 * This class compiles a {nabu_textbox} Smarty tag.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \providers\smarty\builders
 */
class CSmartyNabuModelCompiler extends CSmartyAbstractCompiler
{
    /**
     * Smarty Manager instance
     * @var CSmartyManager
     */
    private $nb_smarty_manager = null;

    public function __construct($params, Smarty $smarty)
    {
        parent::__construct($params, $smarty);

        $this->nb_smarty_manager = CNabuEngine::getEngine()->getApplication()->getManager(SMARTY_MANAGER_KEY);
    }

    public function execute($content = null, &$repeat = false)
    {
        if (!$this->attributeExists('model')) {
            /* @todo To look and find a best solution to treat syntax errors */
            // Below, an example of error raised with Smarty Core
            //     Syntax error in template "file:/var/opt/nabu-3/vhosts/cms.nabu.local/templates/content/login.tpl" on line 1 "{nabu_model xmodel="bootstrap-3.3.7"*}" - Unexpected "}"
            // And below, the best that we can do out of Smarty Core without warranty of compatibility in next versions.
            throw new SmartyCompilerException('Syntax error in template "file:' . $this->smarty->_current_file .'" on {nabu_model ...} - Missing model attribute');
        } else {
            $model = $this->params['model'];
            $this->nb_smarty_manager->setModel(substr($model, 1, strlen($model) - 2), $this->smarty);

            $output = "<?php "
                    . "\\nabu\\core\\CNabuEngine::getEngine()->getApplication()->getManager(SMARTY_MANAGER_KEY)->setModel($model, \$_smarty_tpl->smarty);?>\n";

            return $output;
        }
    }
}
