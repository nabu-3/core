<?php
if (!function_exists('dumpTraceItem')) {
    function dumpTraceItem($trace)
    {
        $output = '';

        if (array_key_exists('class', $trace)) {
            $output .=
                  "<span class=\"class\">$trace[class]</span>"
                . "<span class=\"operator\">$trace[type]</span>"
                . "<span class=\"symbol\">$trace[function]</span>"
                . '<span class="operator">(</span>'
                . '<span class="params">' . params2string($trace['function'], $trace['args']) . '</span>'
                . '<span class="symbol">)</span>'
            ;
        } elseif (array_key_exists('function', $trace)) {
            if (in_array($trace['function'], array('forward_static_call', 'call_user_func'))) {
                $target = array_shift($trace['args']);
                $output .=
                      "<span class=\"forward\">$trace[function]</span>"
                    . " to "
                    . (is_string($target)
                       ? "<span class=\"symbol\">$target</span>"
                       : "<span class=\"class\">"
                         . (is_string($target[0])
                            ? $target[0] . "</span><span class=\"operator\">::</span>"
                            : get_class($target[0]) . "</span><span class=\"operator\">-&gt;</span>"
                           )
                         . "<span class=\"symbol\">$target[1]</span>"
                      )
                    . '<span class="operator">(</span>'
                    . '<span class="params">' . params2string($trace['function'], $trace['args']) . '</span>'
                    . '<span class="operator">)</span>'
                ;
            } else {
                $output .=
                      "<span class=\"symbol\">$trace[function]</span>"
                    . '<span class="operator">(</span>'
                    . "<span class=\"params\">" . params2string($trace['function'], $trace['args']) . '</span>'
                    . '<span class="operator">)</span>'
                ;
            }
        }
        if (array_key_exists('file', $trace) && array_key_exists('line', $trace)) {
            $output .=
                  "<br>in <span class=\"file\">$trace[file]</span>"
                . '<span class="operator">:</span>'
                . "<span class=\"number\">$trace[line]</span>"
            ;
        }

        return $output;
    }
}

if (!function_exists('params2string')) {
    function params2string($function, $params)
    {
        $output = '';

        if (is_array($params) && count($params) > 0) {
            $output = '';
            foreach ($params as $value) {
                $output .= (strlen($output) > 0 ? '<span class="operator">, </span>' : '');
                if (is_object($value)) {
                    $output .= '<span class="class">' . get_class($value) . '</span>';
                } elseif (is_array($value)) {
                    $output .= '<span class="class">array</span>';
                } else {
                    $export = htmlentities(preg_replace('/,\)$/', ')', str_replace("\n", '', var_export($value, true))));
                    $output .= "<span class=\"params\">$export</span>"
                    ;
                }
            }
        }

        return $output;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Error <?php echo $code;?></title>
        <style type="text/css">
            @import 'https://fonts.googleapis.com/css?family=Baloo|Lato&subset=latin-ext';
            body {
                background-color: #fdf6e3;
                color: #586e75;
                font-family: "Lato",sans-serif;
                font-size: 16px;
                font-weight: normal;
                margin: 0;
                padding: 0 20px;
            }
            h1 {
                border-bottom: 2px solid #dc322f;
                color: #dc322f;
                margin: 20px 0;
                padding-bottom: 9px;
                font-family: "Baloo",cursive;
            }
            h1 small {
                color: #2aa198;
                font-family: "Lato",sans-serif;
                font-weight: bold;
                font-size: 65%;
                line-height: 1;
            }
            h2 {
                font-size: 18px;
                margin: 10px 0;
            }
            h3 {
                font-size: 20px;
                color: #d33682;
            }
            footer {
                border-top: 2px solid #dc322f;
                padding-top: 10px;
                margin-top: 20px;
                color: #93a1a1;
                text-align: right;
            }

            footer .left {
                float: left;
            }

            .class {
                color: #b58900;
            }
            .forward {
                color: #cb4b16;
                font-weight: bold;
            }
            .symbol {
                color: #6c71c4;
                font-weight: bold;
            }
            .operator {
                color: #586e75;
            }
            .params {
                color: #859900;
            }
            .file {
                color: #2aa198;
            }
            .number {
                color: #268bd2;
            }
            td {
                vertical-align: top;
                padding-bottom: 5px;
            }
            td:first-child {
                color: #dc322f;
                padding-right: 10px;
                text-align: right;
            }
        </style>
    </head>
    <body>
        <h1><?php echo 'nabu-3 <small>v'. NABU_VERSION . '</small>';?></h1>
        <h3>Error <?php echo "$code: $message";?></h3>
<?php   if ($exception) { ?>
        <code>
<?php       if ($exception instanceof SmartyCompilerException) {
                echo $exception->getMessage();
                //echo str_replace("\n", "<br>", print_r($exception));
            } else { ?>
            <h2>Exception: <span class="symbol"><?php echo get_class($exception);?></span></h2>
<?php           if ($exception->getCode() > 0) { ?>
            Code&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo $exception->getCode();?><br>
<?php           } ?>
            Message&nbsp;: <?php echo $exception->getMessage();?><br>
            File&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo $exception->getFile() . ':' . $exception->getLine();?><br><br>
            <h2>Trace</h2>
            <table>
<?php               foreach ($exception->getTrace() as $line => $trace) { ?>
                <tr>
                    <td>#<?php echo $line;?></td>
                    <td><?php echo dumpTraceItem($trace);?></td>
                </tr>
<?php               } ?>
            </table>
<?php       } ?>
<?php       if (isset($exception->xdebug_message)) { ?>
                <h2>xDebug Info</h2>
                <table>
<?php               echo $exception->xdebug_message; ?>
                </table>
<?php       } ?>
        </code>
<?php   } ?>
        <footer><span class="left"><?php echo NABU_LICENSE_TITLE . (NABU_LICENSED !== NABU_OWNER ? ' to ' . ( NABU_LICENSEE_TARGET !== '' ? '<a href="' . NABU_LICENSEE_TARGET . '">' . NABU_LICENSED . '</a>' : NABU_LICENSED ) : '');?></span>&copy; 2009-<?php echo date('Y');?> <?php echo NABU_OWNER;?></footer>
    </body>
</html>
