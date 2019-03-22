<?php

/** @license
 *  Copyright 2019-2011 Rafael Gutierrez Martinez
 *  Copyright 2012-2013 Welma WEB MKT LABS, S.L.
 *  Copyright 2014-2016 Where Ideas Simply Come True, S.L.
 *  Copyright 2017 nabu-3 Group
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
use nabu\core\exceptions\ENabuCoreException;
use nabu\data\customer\CNabuCustomer;

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @version 3.0.0 Surface
 */

/**
 * spl_autoload function to load automatically classes, interfaces and traits
 * used in Nabu Framework.
 * If the Engine have a Site defined and requested entity does not represent
 * a valid nabu-3 Framework entity, then check if the entity exists and is
 * available in the Site File System.
 * This function is added internally to SPL Autoload Engine of PHP
 * and you should never call it directly.
 * @param string $class_name Class name to load
 */
function nb_autoLoadClasses($class_name)
{
    $file = stream_resolve_include_path(str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . ".php");
    //$file = NABU_PHPUTILS_PATH . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . ".php";
    if (file_exists($file)) {
        if (defined('NABU_TRACE_AUTOLOAD') && NABU_TRACE_AUTOLOAD === true) {
            if (class_exists('nabu\core\CNabuEngine') && \nabu\core\CNabuEngine::isInstantiated()) {
                \nabu\core\CNabuEngine::getEngine()->traceLog("Autoload Class", "$class_name in $file");
            } else {
                error_log("Autoload Class: $class_name in $file");
            }
        }
        nb_requireOnceIsolated($file);
    } else {
        if (class_exists('nabu\core\CNabuEngine') && \nabu\core\CNabuEngine::isInstantiated()) {
            \nabu\core\CNabuEngine::getEngine()->autoLoadClasses($class_name, false);
        }
    }
}

/**
 * Executes dinamycally a require_once sentence isolating the filename from another environments like a function
 * or a class method.
 * @param string $filename Name of the file with entire path to be joined.
 * @param bool $expose_engine If true a $nb_engine variable is created and exposed.
 * @param bool $expose_app If true a $nb_application variable is created and exposed.
 */
function nb_requireOnceIsolated($filename, $expose_engine = false, $expose_app = false)
{
    if ($expose_engine) {
        if (class_exists('nabu\core\CNabuEngine') && \nabu\core\CNabuEngine::isInstantiated()) {
            $nb_engine = \nabu\core\CNabuEngine::getEngine();
        } else {
            $nb_engine = null;
        }
    }

    if ($expose_app) {
        if (class_exists('nabu\core\CNabuEngine') && \nabu\core\CNabuEngine::isInstantiated()) {
            $nb_application = \nabu\core\CNabuEngine::getEngine()->getApplication();
        } else {
            $nb_application = null;
        }
    }

    require_once $filename;
}

/**
 * Executes dinamycally a include sentence isolating the filename from another environments like a function
 * or a class method.
 * @param string $filename Name of the file with entire path to be joined.
 * @param array $params Associative array with params to be passed to the included file.
 * @param bool $expose_engine If true a $nb_engine variable is created and exposed.
 * @param bool $expose_app If true a $nb_application variable is created and exposed.
 */
function nb_includeIsolated($filename, $params = false, $expose_engine = false, $expose_app = false)
{
    if ($expose_engine) {
        if (class_exists('nabu\core\CNabuEngine') && \nabu\core\CNabuEngine::isInstantiated()) {
            $nb_engine = \nabu\core\CNabuEngine::getEngine();
        } else {
            $nb_engine = null;
        }
    }

    if ($expose_app) {
        if (class_exists('nabu\core\CNabuEngine') && \nabu\core\CNabuEngine::isInstantiated()) {
            $nb_application = \nabu\core\CNabuEngine::getEngine()->getApplication();
        } else {
            $nb_application = null;
        }
    }

    if (is_array($params)) {
        foreach ($params as $name => $value) {
            $$name = $value;
        }
    }

    include $filename;
}

/**
 * Inspect $object to get a field value or a direct value.
 * If $object is an instance derived from {@see \nabu\data\CNabuDataObject} and, if $type
 * is setted, an instance is derived also from $type, search in this instance
 * for a field named $field and returns its value. If value not found returns false.
 * Otherwise, if $object is a number or a string then returns its value directly.
 * @param \nabu\data\CNabuDataObject|int|string $object Object instance or scalar variable
 * @param string $field Field name to search in $object
 * @param string $type Class name to force type cast or false for default
 * @return mixed Return the value of the field if $object is an instance and $field
 * exists inside it, or $object directly if is an integer or string.
 */
function nb_getMixedValue($object, $field, $type = false)
{
    $value = false;

    if ($type !== null) {
        if (($object instanceof \nabu\data\CNabuDataObject) && ($type === false || ($object instanceof $type))) {
            if ($object->contains($field)) {
                $value = $object->getValue($field);
            }
        } elseif (is_numeric($object) || is_string($object)) {
            $value = $object;
        }
    }

    return $value;
}

/**
 * If $array contains one value identified by $key, then returns the value else returns null.
 * This function solves the recuperation of an inexistent key over an array without raise an exception
 * @param mixed $key Value key
 * @param mixed $array Associative array to check
 * @return mixed A value of $array identified by $key or null otherwise
 */
function nb_getArrayValueByKey($key, $array)
{
    return (array_key_exists($key, $array) ? $array[$key] : null);
}

function nb_vnsprintf($format, array $data)
{
    preg_match_all(
        '/ (?<!%) % ( (?: [[:alpha:]_-][[:alnum:]_-]* | ([-+])? [0-9]+ (?(2) ' .
        '(?:\.[0-9]+)? | \.[0-9]+ ) ) ) \$ [-+]? \'? .? -? [0-9]* (\.[0-9]+)? \w/x',
        $format,
        $match,
        PREG_SET_ORDER | PREG_OFFSET_CAPTURE
    );
    $offset = 0;
    $keys = array_keys($data);
    foreach ($match as &$value) {
        if (($key = array_search($value[1][0], $keys, true) ) !== false ||
            (is_numeric($value[1][0]) && ($key = array_search((int) $value[1][0], $keys, true)) !== false)
        ) {
            $len = strlen($value[1][0]);
            $format = substr_replace($format, 1 + $key, $offset + $value[1][1], $len);
            $offset -= $len - strlen(1 + $key);
        }
    }

    return vsprintf($format, $data);
}

/**
 * Evaluates if $value is in the range [$left, $right] ($value >= $left and $value <= $right).
 * If $value is a number the comparison is performed as numeric comparison.
 * If $value is a string the comparison is performed as alphabetic comparison.
 * Any other type for $value raises an exception.
 * @param mixed $value A number or string to evaluate
 * @param mixed $left Left limit of the closed range.
 * @param mixed $right Right limit of the closed range.
 * @return bool Returns true if $value is between the range limits.
 * @throws ENabuCoreException Raises an exception if $value is not numeric and not string.
 */
function nb_isBetween($value, $left, $right) : bool
{
    if (is_numeric($value) && is_numeric($left) && is_numeric($right)) {
        $retval = ($value >= $left && $value <= $right);
    } elseif (is_string($value) && is_string($left) && is_string($right)) {
        $retval = (strcmp($left, $value) <= 0) && (strcmp($value, $right) <= 0);
    } else {
        throw new ENabuCoreException(ENabuCoreException::ERROR_FEATURE_NOT_IMPLEMENTED);
    }

    return $retval;
}

/**
 * Validates a key according to key definition in nabu-3. The pattern is stored in a defined constant to be available
 * for other purposes.
 * @param string|null $key The key to be validated.
 * @return bool Returns true if the key is valid of false if not.
 */
function nb_isValidKey(string $key = null) : bool
{
    return is_string($key) &&
           nb_isBetween(strlen($key), 2, 30) &&
           preg_match('/' . NABU_REGEXP_PATTERN_KEY . '/', $key)
    ;
}

/**
 * Validates a MIME type to know if is a valid string conform to syntax rule defined in RFC6838.
 * @param string|null $mimetype MIME type to evaluate.
 * @return bool Returns true if it is valid.
 */
function nb_isMIMEType(string $mimetype = null) : bool
{
    $retval = false;

    if (is_string($mimetype)) {
        $parts = preg_split('////', $mimetype);
        $retval = count($parts) === 2 &&
                  preg_match('/[a-zA-Z0-9]{1}[a-zA-Z0-9!#$&\-\^_.]*/', $parts[0]) &&
                  preg_match('/[a-zA-Z0-9]{1}[a-zA-Z0-9!#$&\-\^_.]*/', $parts[1]) &&
                  in_array(
                      $parts[0],
                      array(
                          'application', 'audio', 'font', 'example', 'image', 'message', 'model', 'multipart',
                          'text', 'video'
                      )
                  )
        ;
    }

    return $retval;
}

/*
function nb_populateTreeFromObjectList($list)
{
    if (count($list) > 0) {
        $tree = array();
        $discarded = array();
        foreach ($list as $child) {
            if ($child instanceof \nabu\core\interfaces\INabuTree) {
                if ($child->getTreeParentId() === null) {
                    $tree[$child->getTreeId()] = $child;
                } else {
                    $discarded[] = $child;
                }
            }
        }

        if (count($tree) > 0) {
            do {
                $new_discarded = array();
                $found = false;
                foreach ($discarded as $child) {
                    $placed = false;
                    foreach ($tree as $root) {
                        if ($root->populateTreeChildNode($child)) {
                            $found = true;
                            $placed = true;
                            break;
                        }
                    }
                    if (!$placed) {
                        $new_discarded[] = $child;
                    }
                }
                $discarded = $new_discarded;
            } while ($found && count($discarded) > 0);
        }

        return count($tree) > 0 ? $tree : null;
    }

    return null;
}
*/

/**
 * Check if a path exists in a tree (a multilevel associative array), using the dotted notation for tree path.
 * A path exists when $tree and $path are both empty, or when $path is contained in $tree and represents a valid
 * path of a branch or a leaf.
 * @param array $tree Tree to scan.
 * @param string $path Path in dotted notation mode.
 * @return bool Returns true if the path exists.
 */
function nb_checkTreePath($tree, $path)
{
    if (($tree === null || (is_array($tree) && count($tree) === 0)) &&
        ($path === null || (is_string($path) && strlen($path) === 0))
    ) {
        $retval = true;
    } elseif (!is_array($tree) || count($tree) === 0 || !is_string($path) || strlen($path) === 0) {
        $retval = false;
    } else {
        $route = preg_split('/\./', $path);

        if (count($route) > 0) {
            $l = count($route);
            $p = &$tree;
            for ($i = 0; $i < $l; $i++) {
                if (!is_array($p) || !array_key_exists($route[$i], $p)) {
                    $retval = false;
                    break;
                }
                $p = &$p[$route[$i]];
            }
            if ($i === $l) {
                $retval = true;
            }
        } else {
            $retval = false;
        }
    }

    return $retval;
}


function nb_getLocalIPv4()
{
    $out = explode(PHP_EOL, shell_exec("/sbin/ifconfig"));
    $local_addrs = array();
    $ifname = 'unknown';

    foreach ($out as $str) {
        $matches = array();
        if (preg_match('/^([a-z0-9]+)(:\d{1,2})?(\s)+Link/', $str, $matches)) {
            $ifname = $matches[1];
            if (strlen($matches[2]) > 0) {
                $ifname .= $matches[2];
            }
        } elseif (preg_match(
            '/inet addr:((?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3})\s/',
            $str,
            $matches
        )) {
            $local_addrs[$ifname] = $matches[1];
        }
    }

    return $local_addrs;
}

/**
 * Returns the Apache License header in text format.
 * @param string $comment_symbol If required, $comment_symbol is prepended
 * at the beginning or each line.
 */
function nb_apacheLicense($comment_symbol)
{
    $symbol = $comment_symbol . (strlen($comment_symbol) > 0 && !nb_strEndsWith($comment_symbol, ' ') ? ' ' : '');
    return  $symbol . "Copyright 2009-2011 Rafael Gutierrez Martinez\n"
          . $symbol . "Copyright 2012-2013 Welma WEB MKT LABS, S.L.\n"
          . $symbol . "Copyright 2014-2016 Where Ideas Simply Come True, S.L.\n"
          . $symbol . "Copyright 2017" . (date('Y') > 2017 ? '-' . date('Y') : '') . " nabu-3 Group\n"
          . $symbol . "\n"
          . $symbol . "Licensed under the Apache License, Version 2.0 (the \"License\");\n"
          . $symbol . "you may not use this file except in compliance with the License.\n"
          . $symbol . "You may obtain a copy of the License at\n"
          . $symbol . "\n"
          . $symbol . "    http://www.apache.org/licenses/LICENSE-2.0\n"
          . $symbol . "\n"
          . $symbol . "Unless required by applicable law or agreed to in writing, software\n"
          . $symbol . "distributed under the License is distributed on an \"AS IS\" BASIS,\n"
          . $symbol . "WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.\n"
          . $symbol . "See the License for the specific language governing permissions and\n"
          . $symbol . "limitations under the License.\n"
    ;
}

/**
 * Check if a string starts by another.
 * @param string $haystack The string to search in.
 * @param string $needle The searched string.
 * @return bool True if success of false if not.
 */
function nb_strStartsWith($haystack, $needle)
{
    return (is_string($haystack) && is_string($needle) && strlen($needle) > 0 && strlen($haystack) >= strlen($needle))
        ? substr($haystack, 0, strlen($needle)) === $needle
        : false;
}

/**
 * Check if a string ends by another.
 * @param string $haystack The string to search in.
 * @param string $needle The searched string.
 * @return bool True if success of false if not.
 */
function nb_strEndsWith($haystack, $needle)
{
    return (is_string($haystack) && is_string($needle) && strlen($needle) > 0 && strlen($haystack) >= strlen($needle))
        ? strrpos($haystack, $needle) === (strlen($haystack) - strlen($needle))
        : false;
}

/**
 * Converts an undescored name to Nabu Entity Name
 * @param string $underscored Underscored name to convert
 * @param bool $hide_nabu If true and Nabu fragment is present ('nb') then it is ignored
 * @param bool|string $dictionary False if not dictionary or associative array with equivalences
 * @return string Return the converted Nabu Entity Name
 */
function nb_underscore2EntityName($underscored, $hide_nabu = true, $dictionary = false)
{
    $entity_name = '';

    if (is_string($underscored) && strlen($underscored) > 0) {
        $parts = preg_split('/_/', $underscored);
        if (count($parts) > 0) {
            foreach ($parts as $part) {
                if (strlen($part) > 0 && (!$hide_nabu || strlen($entity_name) > 0 || $part !== 'nb')) {
                    $entity_name .= (strlen($entity_name) > 0 ? ' ' : '')
                            . ((count($dictionary) > 0 &&
                                array_key_exists($part, $dictionary) &&
                                strlen($dictionary[$part]) > 0
                               )
                               ? $dictionary[$part]
                               : nb_capitalize($part)
                              )
                    ;
                }
            }
        }
    }

    return $entity_name;
}

/**
 * Converts an undescored name to Camel Case Name
 * @param string $underscored Underscored name to convert
 * @param bool $hide_nabu If true and Nabu fragment is present ('nb') then it is ignored
 * @param bool|string $dictionary False if not dictionary or associative array with equivalences
 * @return string Return the converted Camel Case Name
 */
function nb_underscore2CamelCase($underscored, $hide_nabu = true, $dictionary = false)
{
    $camel = '';

    if (is_string($underscored) && strlen($underscored) > 0) {
        $parts = preg_split('/_/', $underscored);
        if (count($parts) > 0) {
            foreach ($parts as $part) {
                if (strlen($part) > 0 && (!$hide_nabu || strlen($camel) > 0 || $part !== 'nb')) {
                    $camel .=
                            ((count($dictionary) > 0 &&
                              array_key_exists($part, $dictionary) &&
                              strlen($dictionary[$part]) > 0
                             )
                             ? $dictionary[$part]
                             : (strlen($camel) === 0 ? strtolower($part) : nb_capitalize($part))
                            );
                }
            }
        }
    }

    return $camel;
}

/**
 * Converts an undescored name to Studly Caps Name
 * @param string $underscored Underscored name to convert
 * @param bool $hide_nabu If true and Nabu fragment is present ('nb') then it is ignored
 * @param bool|string $dictionary False if not dictionary or associative array with equivalences
 * @return string Return the converted Studly Caps Name
 */
function nb_underscore2StudlyCaps($underscored, $hide_nabu = true, $dictionary = false)
{
    $studly = nb_underscore2CamelCase($underscored, $hide_nabu, $dictionary);
    if (strlen($studly) > 0) {
        $studly = strtoupper(substr($studly, 0, 1)) . (strlen($studly) > 1 ? substr($studly, 1) : '');
    }

    return $studly;
}

/**
 * Capitalize the first character of a string
 * @param string $string String to capitalize
 * @return string Return the capitalized string
 */
function nb_capitalize($string)
{
    return (is_string($string) && strlen($string) > 0)
        ? strtoupper(substr($string, 0, 1)) . (strlen($string) > 1 ? strtolower(substr($string, 1)) : '')
        : ''
    ;
}

/**
 * Generates a GUID or UUID v4.
 * Courtesy of http://guid.us/GUID/PHP
 * @return string Returns the GUID in string format
 */
function nb_generateGUID()
{
    $guid = false;

    if (function_exists('com_create_guid')) {
        $guid = com_create_guid();
    } else {
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $guid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid, 12, 4).$hyphen
            .substr($charid, 16, 4).$hyphen
            .substr($charid, 20, 12)
            .chr(125);// "}"
    }

    return $guid;
}

/**
 * Check if a guid is valid
 * @param string $guid
 * @return bool Returns true if $guid is valid
 */
function nb_isValidGUID($guid)
{
    return is_string($guid) &&
           strlen($guid) === 38 &&
           preg_match('/^\{[0-9A-F]{8}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{4}-[0-9A-F]{12}\}$/', $guid) === 1
    ;
}

/**
 * Prepend a prefix to a list of field names. This list can be provided as comma separted (without spaces),
 * or as an array of field names. In case $fields was null or empty then is treated as a wildcard '*' list.
 * Between the $prefix and each field name, this function intercalates a underscore '_' as separator.
 * The resulted list can be returned as array or as a string comma separated list.
 * @param string $prefix Prefix to be applied.
 * @param string|array $fields List of fields to be treated. Can be a string comma separated lis or an array.
 * @param bool $as_array If true the result is returned as an array. If false then as a string comma separated list.
 * @param bool $remask If true, the names are created as the convention of "name1 as name2" to facilitate renaming
 * when use the fields list in SQL environments like MySQL.
 * @param string $quote If setted, all field are enclosed between $quote strings.
 * @return string|array Returns the treated list following the format of $as_array.
 * @throws ENabuCoreException Raises an exception if $fields is not of an expected type
 * (null, array, string).
 */
function nb_prefixFieldList($prefix, $fields, $as_array = false, $remask = false, $quote = '')
{
    if ($fields === null) {
        $fields_part = null;
    } elseif (is_array($fields)) {
        $fields_part = $fields;
    } elseif (is_string($fields)) {
        $fields_part = explode(',', $fields);
    } else {
        throw new ENabuCoreException(
            ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE,
            array($fields, print_r($fields, true))
        );
    }

    if (is_array($fields_part)) {
        array_walk($fields_part, function (&$item, $key) use ($prefix, $remask, $quote) {
            $item = trim($item);
            $item = $quote . $prefix . '_' . $item . $quote . ($remask ? " as $quote$item$quote" : '');
        });
    }

    if (!$as_array) {
        if (is_array($fields_part) && count($fields_part) > 0) {
            $fields_part = implode(',', $fields_part);
        }
    }

    return $fields_part;
}

/**
 *
 * @param string $date_interval Unpack a nabu-3 Encoded Date Interval
 */
function nb_unpackDateInterval($date_interval)
{
    if ($date_interval !== null) {
        $match = array();
        preg_match('/^([0-9]+)([SMHDNY]{1})$/', $date_interval, $match);

        if (count($match) === 3 && $date_interval == $match[0]) {
            $seconds = (int)$match[1];
            switch ($match[2]) {
                case 'Y':
                case 'N':
                    $seconds *= ($match[2] === 'Y' ? 365 : 30);
                    // Continue to next case
                case 'D':
                    $seconds *= 24;
                    // Continue to next case
                case 'H':
                    $seconds *= 60;
                    // Continue to next case
                case 'M':
                    $seconds *= 60;
            }
            $retval = array('value' => (int)$match[1], 'unit' => $match[2], 'seconds' => $seconds);
        } else {
            $retval = false;
        }
    } else {
        $retval = null;
    }

    return $retval;
}

/**
 * This function receives a Customer in $nb_customer as:
 * - A CNabuCustomer instance directly
 * - An instance that inherits CNabuDataObject and contains a field named nb_customer_id
 * - An ID to identify a Customer
 * If the function receives directly a CNabuCustomer instance then returns it, elsewhere locates a valid Customer ID
 * and look for it in the database storage. If a customer is found, then returns their instance else returns false.
 * @param mixed $nb_customer An instance descending from CNabuDataObject that contains a nb_customer_id field or an ID.
 * @param bool $force If true, forces to reload the instance if exists.
 * @return false|CNabuCustomer If a valid instance is located and confirmed, then returns this instance,
 * elsewhere returns false.
 */
function nb_grantCustomer($nb_customer, $force = false)
{
    if (!($nb_customer instanceof CNabuCustomer)) {
        if ($nb_customer === false) {
            $nb_customer = CNabuEngine::getEngine()->getCustomer();
        } else {
            $nb_customer_id = nb_getMixedValue($nb_customer, NABU_CUSTOMER_FIELD_ID);
            if (is_numeric($nb_customer_id)) {
                $nb_customer = new CNabuCustomer($nb_customer_id);
            }
        }
    }

    return ($nb_customer instanceof CNabuCustomer) ? $nb_customer : false;
}

/**
 * Dumps an HTML error page to show an anormal state of execution.
 * @param int $code HTTP Code for header response.
 * @param Exception $exception If exists, the exception instance raised before call this function.
 */
function nb_displayErrorPage($code, $exception = false)
{
    global $NABU_HTTP_CODES;

    /*
    if ((is_object($exception) &&
        (!($exception instanceof ENabuException)) || !array_key_exists($code, $NABU_HTTP_CODES))
    ) {
        $code = 500;
    }
    */
    if ($code < 400) {
        $code = 500;
    }

    $message = $NABU_HTTP_CODES[$code];
    header("HTTP/1.1 $code $message");
    nb_includeIsolated(
        dirname(__FILE__) . DIRECTORY_SEPARATOR . 'html_display_error_page.php',
        array(
            'exception' => $exception,
            'code' => $code,
            'message' => $message
        )
    );
}
