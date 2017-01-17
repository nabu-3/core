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
 * PHP-CLI common functions.
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 */

/**
 * Write the Apache License header in an output handler.
 * @param file_handler $handler Output Handler to write the license.
 * @param string $comment_symbol If required, $comment_symbol is prepended
 * at the beginning or each line.
 */
function writeApacheLicense($handler, $comment_symbol)
{
    fwrite(
            $handler,
            nb_apacheLicense($comment_symbol)
    );
}

/**
 * Gets a string via STDIN. The input can be preceeded by a title.
 * If $default is false, then the function ends even if an empty string is typed.
 * If $default is true, then force that a string is introduced.
 * If $default is not boolean, then this value is returned in case
 * that the string type was empty.
 * @param string $title Title to preceed the input.
 * @param string|boolean $default Default value if no input.
 * @return string Returns the string readed from the STDIN.
 */
function nbCLIInput($title, $default = true, $silent = false)
{
    if ($silent && strlen($default) > 0) {
        return $default;
    }
    
    do {
        echo $title;
        if ($default !== false && $default !== true) {
            echo " [$default]";
        }
        echo ": ";
        $input = preg_replace('/^\\s*/', '', preg_replace('/\\s*$/', '', fgets(STDIN)));
    } while (strlen($input) === 0 && ($default === true || strlen($default) === 0));
    
    if (strlen($input) === 0 && strlen($default) > 0) {
        $input = $default;
    }
    
    return $input;
}

/**
 * Request to the user to continue. If $die is true then the execution is aborted
 * if the user answers No.
 * @param boolean $die True if the execution die inmediately.
 * @return boolean Return true y the user type Yes or false if not.
 */
function nbCLICheckContinue($die = true, $die_message = false)
{
    return nbCLICheckYesNo('Continue?', $die_message, $die);
}

/**
 * Request to the user to confirm an action. If $die is true then the execution
 * is aborted if the user answers No.
 * @param boolean $die True if the execution die inmediately.
 * @return boolean Return true y the user type Yes or false if not.
 */
function nbCLICheckConfirm($die = true, $die_message = false)
{
    return nbCLICheckYesNo('Are you sure?', $die_message, $die);
}

/**
 * Generic request to the user for a Yes/no answer. If $die is true
 * then the execution is aborted if the user answers No.
 * @param type $title Title to preceed the cursor.
 * @param type $die_message In case to die, the message to show.
 * @param boolean $die True if the execution die inmediately.
 * @return boolean Return true y the user type Yes or false if not.
 */
function nbCLICheckYesNo($title, $die_message = false, $die = true)
{
    do {
        echo "$title [Y/N]: ";
        $input = preg_replace('/\\s/', '', strtolower(fgets(STDIN)));
        if ($input === 'y' || $input === 'yes') {
            return true;
        } elseif ($input === 'n' || $input === 'no') {
            if ($die) {
                die($die_message ? $die_message : "Aborted\n");
            } else {
                return false;
            }
        } else {
            echo "\n";
        }
    } while (true);
}

/**
 * Wrapper for the funcion getopt to control the existence of a parameter
 * in the PHP-CLI call.
 * @param string $short Short name of the parameter.
 * @param string $large Large name of the parameter.
 * @param string $required Mandatory/Optional/Empty value:
 * - ':' => Mandatory
 * - '::' => Optional
 * - '' => Empty
 * @param string $default Default value if the parameter does not exists.
 * @param boolean $die If true the execution die.
 * @return string Return a string containing finally value for the parameter.
 */
function nbCLICheckOption($short, $large, $required = '', $default = false, $die = true)
{
    $retval = $default;
    
    $options = getopt($short . $required, array($large . $required));
    if (array_key_exists($short, $options)) {
        if (array_key_exists($large, $options)) {
            if ($die) {
                die("$large are defined more than one time\n");
            }
        } else {
            $retval = $options[$short];
        }
    } elseif (array_key_exists($large, $options)) {
        $retval = ($required === '' || strlen($options[$large]) === 0 ? true : $options[$large]);
    } elseif ($die && $required === ':') {
        die ("$large not defined\n");
    }
    
    return $retval;
}
