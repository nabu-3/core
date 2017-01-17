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
use providers\smarty\builders\base\CSmartyAbstractBlock;

/**
 * This class builds the tag nabu_raw_assign.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 * @package \providers\smarty\renders
 */
class CSmartyNabuRawAssignBlock extends CSmartyAbstractBlock
{
    public function execute($content = null, &$repeat = false)
    {
        if (!empty($content)) {
            $src = $content;

            // pre-tokenize strings
            $src_c = preg_match_all('/(".*")/', $src, $token_str);
            $src = preg_replace('/".*"/', ' STRING! ', $src);
            // "fix" array delimiters
            $src = str_replace('[', ' [ ', $src);
            $src = str_replace(']', ' ] ', $src);
            // split on whitespace
            $src = preg_split('/\s+/', $src);

            $msg = '';
            $stack = array();

            // take each token in turn...
            $level = 0;
            $items = 0;
            foreach ($src as $_token) {
                $token = trim($_token);
                $last_char = substr($token, strlen($token) - 1, 1);
                $stack[] = $items;
                switch ($last_char) {
                    case '[':
                        // array start
                        $msg .= "array(";
                        ++$level;
                        $stack[] = $items;
                        $items = 0;
                        break;
                    case ']':
                        // array end
                        $msg .= ")";
                        $items = array_pop($stack);
                        ++$items;
                        --$level;
                        break;
                    case ':':
                        if ($level == 0) {
                            if ($items > 0) {
                                $msg .= ';';
                            }
                            $msg .= "$" . substr($token, 0, strlen($token) - 1) . "=";
                        } else {
                            if ($items > 0) {
                                $msg .= ',';
                            }
                            $msg .= '"' . substr($token, 0, strlen($token) - 1) . '"=>';
                        }
                        // assignment
                        ++$items;
                        break;
                    case '!':
                        // pre tokenized type
                        switch ($token) {
                            case 'STRING!':
                                $msg .= array_shift($token_str[1]);
                                break;
                        }
                        break;
                    default:
                        if (substr($token, 0, 1) === '$') {
                            if (strlen($token) > 1) {
                                $token = '$this->template->getVariable(\'' . substr($token, 1) . '\')->value';
                            }
                        }
                        $msg .= $token;
                        break;
                }
            }
            $msg .= ';';
            $cnt = preg_match_all('/(\$(\w+)\s*=\s*(.*?;))/', $msg, $list);
            $cnt = count($list[1]);
            if ($cnt > 0) {
                for ($i = 0; $i < $cnt; $i++) {
                    $var = $list[2][$i];
                    if (!empty($var)) {
                        eval($list[1][$i]);
                        $this->template->assign($var, $$var);
                    }
                }
            }
        }
    }
}
