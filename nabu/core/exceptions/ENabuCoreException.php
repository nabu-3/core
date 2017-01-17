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

namespace nabu\core\exceptions;

/**
 * Exception to inform errors in Core processes.
 * @author Rafael Gutiérrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 */
class ENabuCoreException extends ENabuException
{
    /* General error messages */
    const ERROR_BASEDIR_NOT_STABLISHED                  = 0x0001;
    const ERROR_HOST_PATH_NOT_FOUND                     = 0x0002;
    const ERROR_REDIRECTION_TARGET_NOT_VALID            = 0x0003;

    const ERROR_FEATURE_NOT_IMPLEMENTED                 = 0x0fff;

    /* Engine error messages */
    const ERROR_ENGINE_NOT_FOUND                        = 0x1001;
    const ERROR_ENGINE_NOT_INSTANTIATED                 = 0x1002;

    const ERROR_MAIN_DB_NOT_FOUND                       = 0x1003;
    const ERROR_MAIN_DB_ALREADY_EXISTS                  = 0x1004;

    const ERROR_OPERATION_MODE_NOT_ALLOWED              = 0x1005;
    const ERROR_OPERATION_MODE_CANNOT_BE_MODIFIED       = 0x1006;

    const ERROR_APPLICATION_REQUIRED                    = 0x1007;
    const ERROR_RUNNING_APPLICATION                     = 0x1008;

    const ERROR_SERVER_NOT_FOUND                        = 0x1009;
    const ERROR_SERVER_NOT_INITIALIZED                  = 0x100a;

    const ERROR_SERVER_HOST_NOT_FOUND                   = 0x100b;
    const ERROR_SERVER_HOST_NOT_INITIALIZED             = 0x100c;
    const ERROR_SERVER_HOST_MISCONFIGURED               = 0x100d;

    const ERROR_HTTP_SERVER_NOT_FOUND                   = 0x100e;

    const ERROR_CUSTOMER_NOT_FOUND                      = 0x100f;
    const ERROR_CUSTOMERS_DOES_NOT_MATCH                = 0x1010;
    const ERROR_CUSTOMER_NOT_ALLOWED                    = 0x1011;

    const ERROR_DOMAIN_ZONE_NOT_FOUND                   = 0x1012;

    const ERROR_SITE_NOT_FOUND                          = 0x1013;
    const ERROR_SITE_NOT_INSTANTIATED                   = 0x1014;
    const ERROR_SITE_NOT_PUBLISHED                      = 0x1015;

    const ERROR_SITE_ALIAS_NOT_FOUND                    = 0x1016;

    const ERROR_PLUGINS_MANAGER_REQUIRED                = 0x1017;

    const ERROR_MODULES_MANAGER_REQUIRED                = 0x1018;
    const ERROR_MODULES_MANAGER_INIT_ERROR              = 0x1019;

    const ERROR_PLUGIN_REDIRECTION_NOT_ALLOWED          = 0x101a;
    const ERROR_PLUGIN_COMMAND_INVALID_RETURN_VALUE     = 0x101b;

    const ERROR_SITE_PLUGIN_ALREADY_ASSIGNED            = 0x101c;
    const ERROR_SITE_PLUGIN_NOT_VALID                   = 0x101d;
    const ERROR_SITE_PLUGIN_INIT_ERROR                  = 0x101e;

    const ERROR_SITE_TARGET_PLUGIN_ALREADY_ASSIGNED     = 0x101f;
    const ERROR_SITE_TARGET_PLUGIN_NOT_VALID            = 0x1020;
    const ERROR_SITE_TARGET_PLUGIN_INIT_ERROR           = 0x1021;

    const ERROR_ROLE_NOT_ASSIGNED                       = 0x1022;
    const ERROR_ROLE_NOT_VALID                          = 0x1023;

    const ERROR_REQUEST_NOT_FOUND                       = 0x1024;

    const ERROR_RESPONSE_NOT_FOUND                      = 0x1025;

    const ERROR_PAGE_URI_NOT_FOUND                      = 0x1026;
    const ERROR_LANGUAGE_NOT_FOUND_FOR_PAGE_URI         = 0x1027;

    const ERROR_SITE_TARGET_NOT_FOUND                   = 0x1028;
    const ERROR_SITE_TARGET_LOGIN_MISMATCH              = 0x1029;
    const ERROR_SITE_TARGET_ERROR_MISMATCH              = 0x102a;

    const ERROR_INVALID_HTTP_MANAGER_CLASS              = 0x102b;
    const ERROR_ENABLING_HTTP_MANAGER                   = 0x202c;

    const ERROR_RENDER_NOT_SET                          = 0x102d;
    const ERROR_INVALID_RENDER_DESCRIPTOR_CLASS         = 0x102e;
    const ERROR_RENDER_NOT_FOUND                        = 0x102f;

    const ERROR_URL_FILTER_INVALID                      = 0X1030;

    /* Syntax and semantic error messages */
    const ERROR_CLASS_NOT_FOUND                         = 0x2000;

    const ERROR_OBJECT_EXPECTED                         = 0x2001;
    const ERROR_OBJECT_NOT_EXPECTED                     = 0x2002;

    const ERROR_CONSTRUCTOR_PARAMETER_IS_EMPTY          = 0x2003;

    const ERROR_METHOD_NOT_IMPLEMENTED                  = 0x2004;
    const ERROR_METHOD_NOT_AVAILABLE                    = 0x2005;
    const ERROR_METHOD_PARAMETER_IS_EMPTY               = 0x2006;
    const ERROR_METHOD_PARAMETER_NOT_VALID              = 0x2007;

    const ERROR_UNEXPECTED_PARAM_CLASS_TYPE             = 0x2008;
    const ERROR_UNEXPECTED_PARAM_VALUE                  = 0x2009;

    const ERROR_NULL_VALUE_NOT_ALLOWED_IN               = 0x200a;

    const ERROR_FOLDER_NOT_FOUND                        = 0x200b;
    const ERROR_FILE_NOT_FOUND                          = 0x200c;

    const ERROR_INVALID_INDEX                           = 0x200d;

    /* Devel builder error messages */
    const ERROR_CLASS_CANNOT_BE_BUILT                   = 0xe001;

    /* Install mode messages */
    const ERROR_INSTALL_MODE_LOCKED                     = 0xf00d;
    const ERROR_INSTALL_MODE_REQUIRED                   = 0xf00e;
    const ERROR_INSTALL_DB_NOT_FOUND                    = 0xf00f;


    private static $error_messages = array(

        /* General error messages */
        ENabuCoreException::ERROR_BASEDIR_NOT_STABLISHED =>
            'PHP open_basedir directive [%s] does not contain host base directory',
        ENabuCoreException::ERROR_HOST_PATH_NOT_FOUND =>
            'Host Path not found [%s]',
        ENabuCoreException::ERROR_REDIRECTION_TARGET_NOT_VALID =>
            'Redirection target not valid',

        ENabuCoreException::ERROR_FEATURE_NOT_IMPLEMENTED =>
            'Feature not implemented',

        /* Engine error messages */
        ENabuCoreException::ERROR_ENGINE_NOT_FOUND =>
            'Engine not found',
        ENabuCoreException::ERROR_ENGINE_NOT_INSTANTIATED =>
            'Engine not instantiated',

        ENabuCoreException::ERROR_MAIN_DB_NOT_FOUND =>
            'Main Database not found or not available',
        ENabuCoreException::ERROR_MAIN_DB_ALREADY_EXISTS =>
            'Main database already exists',

        ENabuCoreException::ERROR_OPERATION_MODE_NOT_ALLOWED =>
            'Operation mode selected is not valid',
        ENabuCoreException::ERROR_OPERATION_MODE_CANNOT_BE_MODIFIED =>
            'Operation mode cannot be modified after instantiate the Engine',

        ENabuCoreException::ERROR_APPLICATION_REQUIRED =>
            'Application required',
        ENabuCoreException::ERROR_RUNNING_APPLICATION =>
            'You cannot continue. Another Application is already running',

        ENabuCoreException::ERROR_SERVER_NOT_FOUND =>
            'Server not found for [%s] in [%s:%d]',
        ENabuCoreException::ERROR_SERVER_NOT_INITIALIZED =>
            'Server instance not initialized',

        ENabuCoreException::ERROR_SERVER_HOST_NOT_FOUND =>
            'Server host not found for [%s:%s]',
        ENabuCoreException::ERROR_SERVER_HOST_NOT_INITIALIZED  =>
            'Server Host instance not initialized',
        ENabuCoreException::ERROR_SERVER_HOST_MISCONFIGURED =>
            'Server host misconfigured',

        ENabuCoreException::ERROR_HTTP_SERVER_NOT_FOUND =>
            'No HTTP Server found',

        ENabuCoreException::ERROR_CUSTOMER_NOT_FOUND =>
            'Customer not found',
        ENabuCoreException::ERROR_CUSTOMERS_DOES_NOT_MATCH =>
            'Customers does not match',
        ENabuCoreException::ERROR_CUSTOMER_NOT_ALLOWED =>
            'The user intends to access a forbidden customer',

        ENabuCoreException::ERROR_DOMAIN_ZONE_NOT_FOUND =>
            'Domain Zone not found',

        ENabuCoreException::ERROR_SITE_NOT_FOUND =>
            'Site not found',
        ENabuCoreException::ERROR_SITE_NOT_INSTANTIATED =>
            'Site not instantiated',
        ENabuCoreException::ERROR_SITE_NOT_PUBLISHED =>
            'Site [%s] not published',

        ENabuCoreException::ERROR_SITE_ALIAS_NOT_FOUND =>
            'Site alias [%s] not found in site [%s]',

        ENabuCoreException::ERROR_PLUGINS_MANAGER_REQUIRED =>
            'Plugins Manager required',

        ENabuCoreException::ERROR_MODULES_MANAGER_REQUIRED =>
            'Modules Manager required',
        ENabuCoreException::ERROR_MODULES_MANAGER_INIT_ERROR =>
            'Modules Manager trap init error',

        ENabuCoreException::ERROR_PLUGIN_REDIRECTION_NOT_ALLOWED =>
            'Plugin method redirection not allowed',
        ENabuCoreException::ERROR_PLUGIN_COMMAND_INVALID_RETURN_VALUE =>
            'Plugin Command [%s] for plugin [%s] returns an invalid value',

        ENabuCoreException::ERROR_SITE_PLUGIN_ALREADY_ASSIGNED =>
            'Plugins Manager have a Site Plugin and cannot be reasigned',
        ENabuCoreException::ERROR_SITE_PLUGIN_NOT_VALID =>
            'Site plugin [%s] is not valid',
        ENabuCoreException::ERROR_SITE_PLUGIN_INIT_ERROR =>
            'Site plugin trap init error',

        ENabuCoreException::ERROR_SITE_TARGET_PLUGIN_ALREADY_ASSIGNED =>
         'Plugin Manager have a Article Plugin and cannot be reasigned',
        ENabuCoreException::ERROR_SITE_TARGET_PLUGIN_NOT_VALID =>
            'Site target plugin %s is not valid',
        ENabuCoreException::ERROR_SITE_TARGET_PLUGIN_INIT_ERROR =>
            'Site Target plugin trap init error',

        ENabuCoreException::ERROR_ROLE_NOT_ASSIGNED =>
            'Role not assigned',

        ENabuCoreException::ERROR_REQUEST_NOT_FOUND =>
            'Request not found',

        ENabuCoreException::ERROR_RESPONSE_NOT_FOUND =>
            'Response not found',

        ENabuCoreException::ERROR_PAGE_URI_NOT_FOUND =>
            'Page URI not found',
        ENabuCoreException::ERROR_LANGUAGE_NOT_FOUND_FOR_PAGE_URI =>
            'Language not found for Page URI',

        ENabuCoreException::ERROR_SITE_TARGET_NOT_FOUND =>
            'Site target not found',
        ENabuCoreException::ERROR_SITE_TARGET_LOGIN_MISMATCH =>
            'Login target configuration mismatch',
        ENabuCoreException::ERROR_SITE_TARGET_ERROR_MISMATCH =>
            'Error target configuration mismatch',

        ENabuCoreException::ERROR_INVALID_HTTP_MANAGER_CLASS =>
            'Invalid HTTP Manager class [%s]',
        ENabuCoreException::ERROR_ENABLING_HTTP_MANAGER =>
            'HTTP Manager [%s] was returned an invalid status after being enabled',

        ENabuCoreException::ERROR_RENDER_NOT_SET =>
            'Render not set',
        ENabuCoreException::ERROR_INVALID_RENDER_DESCRIPTOR_CLASS =>
            'Render descriptor contains invalid class',
        ENabuCoreException::ERROR_RENDER_NOT_FOUND =>
            'Render [%s] not found',

        ENabuCoreException::ERROR_URL_FILTER_INVALID =>
            'URL Filter not valid due to unknown type or in conflict with a previous filter setted.',

        /* Syntax and semantic error messages */
        ENabuCoreException::ERROR_CLASS_NOT_FOUND =>
            'Class [%s] not found',

        ENabuCoreException::ERROR_OBJECT_EXPECTED =>
            'Object instance expected',
        ENabuCoreException::ERROR_OBJECT_NOT_EXPECTED =>
            'Object instance not expected[%s]',

        ENabuCoreException::ERROR_CONSTRUCTOR_PARAMETER_IS_EMPTY =>
            'Constructor parameter %s is empty',

        ENabuCoreException::ERROR_METHOD_NOT_IMPLEMENTED =>
            'The required method [%s] is not implemented',
        ENabuCoreException::ERROR_METHOD_NOT_AVAILABLE =>
            'The required method [%s] is not available in this operation mode',
        ENabuCoreException::ERROR_METHOD_PARAMETER_IS_EMPTY =>
            'Method %s has parameter %s empty',
        ENabuCoreException::ERROR_METHOD_PARAMETER_NOT_VALID =>
            'Parameter [%s] has an invalid value [%s]',

        ENabuCoreException::ERROR_UNEXPECTED_PARAM_CLASS_TYPE =>
            'Unexpected object class type [%s] in param [%s]',
        ENabuCoreException::ERROR_UNEXPECTED_PARAM_VALUE =>
            'Unexpected value[%s] in param [%s]',

        ENabuCoreException::ERROR_NULL_VALUE_NOT_ALLOWED_IN =>
            'NULL value not allowed in %s',

        ENabuCoreException::ERROR_FOLDER_NOT_FOUND =>
            'Folder [%s] not found',
        ENabuCoreException::ERROR_FILE_NOT_FOUND =>
            'File [%s] not found',

        ENabuCoreException::ERROR_INVALID_INDEX =>
            'Invalid index [%s]',

        /* Devel builder error messages */
        ENabuCoreException::ERROR_CLASS_CANNOT_BE_BUILT =>
            'Class [%s] cannot be built',

        /* Install mode messages */
        ENabuCoreException::ERROR_INSTALL_MODE_LOCKED =>
            'Install mode cannot be changed',
        ENabuCoreException::ERROR_INSTALL_MODE_REQUIRED =>
            'Install mode required',
        ENabuCoreException::ERROR_INSTALL_DB_NOT_FOUND =>
            'Install database does not exists',
    );





//    const ERROR_OBJECT_EXPECTED = 9;
//    const ERROR_UNEXPECTED_OBJECT_CLASS_TYPE = 10;
//    const ERROR_ACCESS_NOT_ALLOWED_AFTER_LOGIN = 14;
//    const ERROR_SINGLETON_INSTANCE_ALREADY_INSTANTIATED = 18;
//
//    /* Plugin error messages */
//    const ERROR_PLUGIN_TYPE_NOT_ALLOWED = 1003;
//    const ERROR_PLUGIN_COMMAND_MISMATCH = 1005;
//
//    /* Server handling error messages */
//    const ERROR_VIRTUAL_HOST_MISCONFIGURED = 2006;
//
//    /* Medioteca classes handling error messages */
//    const ERROR_MEDIOTECA_NOT_EMPTY=3001;
//
//    /* i-Contact classes handling error messages */
//    const ERROR_ICONTACT_PROSPECT_CANNOT_STORE_FILE=4001;
//
//    /* General classes handling error messages */
//    const ERROR_CONSTRUCTOR_PARAMETER_IS_EMPTY = 10000;
//    const ERROR_INVALID_FIELD_CONVERSION_TO_INTEGER = 10003;
//    const ERROR_INVALID_FIELD_CONVERSION_TO_FLOAT = 10004;
//    const ERROR_INVALID_FLOAT_NUMBER = 10005;
//    const ERROR_INVALID_DATE_INTERVAL = 10006;
//    const ERROR_METHOD_NOT_IMPLEMENTED = 10007;
//    const ERROR_TYPE_NOT_SUPPORTED = 10010;
//    const ERROR_VALUE_NOT_VALID = 10011;
//    const ERROR_ATTRIBUTE_EXPECTED = 10012;
//
//    const ERROR_TODO_HAPPEN = 60000;
//
//    private static $ERROR_MESSAGES = array(
//
//        ENabuCoreException::ERROR_ENGINE_NOT_FOUND => 'Engine not found',
//        ENabuCoreException::ERROR_OBJECT_EXPECTED => 'Object instance expected',
//        ENabuCoreException::ERROR_UNEXPECTED_OBJECT_CLASS_TYPE => 'Unexpected object class type [%s]',
//        ENabuCoreException::ERROR_ACCESS_NOT_ALLOWED_AFTER_LOGIN =>
//         'Access to site [%s] not allowed after user [%s] logged',
//        ENabuCoreException::ERROR_SINGLETON_INSTANCE_ALREADY_INSTANTIATED =>
//         'Singleton instance already instantiated[%s]',
//        ENabuCoreException::ERROR_RENDER_NOT_SET => 'Render not set',
//
//        ENabuCoreException::ERROR_PLUGIN_TYPE_NOT_ALLOWED => 'Plugin type not allowed in class %s',
//        ENabuCoreException::ERROR_PLUGIN_COMMAND_MISMATCH => 'Plugin command mismatch',
//
//        ENabuCoreException::ERROR_VIRTUAL_HOST_MISCONFIGURED => 'Virtual host misconfigured',
//
//        ENabuCoreException::ERROR_MEDIOTECA_NOT_EMPTY => 'Medioteca repository is not empty in %s',
//
//        ENabuCoreException::ERROR_ICONTACT_PROSPECT_CANNOT_STORE_FILE => 'Moving file %s to final storage not able',
//
//        ENabuCoreException::ERROR_INVALID_FIELD_CONVERSION_TO_INTEGER =>
//         'Invalid field [%s] conversion to integer for value [%s]',
//        ENabuCoreException::ERROR_INVALID_FIELD_CONVERSION_TO_FLOAT =>
//         'Invalid field [%s] conversion to float for value [%s]',
//        ENabuCoreException::ERROR_INVALID_FLOAT_NUMBER => 'Invalid float number [%s]',
//        ENabuCoreException::ERROR_INVALID_DATE_INTERVAL => 'Invalid date interval [%s] for value [%s]',
//        ENabuCoreException::ERROR_METHOD_NOT_IMPLEMENTED => 'Method not implemented',
//        ENabuCoreException::ERROR_TYPE_NOT_SUPPORTED => 'Type [%s] is not supported',
//        ENabuCoreException::ERROR_VALUE_NOT_VALID => 'Variable [%s] contains invalid value [%s]',
//        ENabuCoreException::ERROR_ATTRIBUTE_EXPECTED => 'Attribute [%s] expected',
//
//        ENabuCoreException::ERROR_TODO_HAPPEN => 'Please talk to [%s] if this happen\'s'
//    );

    public function __construct($code, $values = null)
    {
        parent::__construct(ENabuCoreException::$error_messages[$code], $code, $values);
    }
}
