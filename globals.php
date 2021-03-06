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

/**
 * @author Rafael Gutierrez <rgutierrez@nabu-3.com>
 * @since 3.0.0 Surface
 * @version 3.0.12 Surface
 */

/* Main constants definition */
define('NABU_VERSION', '3.0.12 Surface');
define('NABU_OWNER', 'nabu-3 group');
define('NABU_OWNER_TARGET', 'http://www.nabu-3.com/');
define('NABU_LICENSE_TITLE', 'Licensed under the Apache License, Version 2.0');
define('NABU_LICENSE_TARGET', 'http://www.apache.org/licenses/LICENSE-2.0');

/* Locale and Charset defaults */
define('NABU_CHARSET', 'utf8');

/* File configuration names */
define('NABU_DB_DEFAULT_FILENAME_CONFIG', 'nabu-db-config.php.conf');
define('NABU_VHOST_CONFIG_FILENAME', 'httpd.include');

/* Trace files */
define('NABU_TRACE_AUTOLOAD', false);

/* Path constants definition */
define('NABU_ETC_FOLDER', DIRECTORY_SEPARATOR . 'etc');
define('NABU_SRC_FOLDER', DIRECTORY_SEPARATOR . 'src');
define('NABU_SDK_FOLDER', DIRECTORY_SEPARATOR . 'sdk');
define('NABU_PUB_FOLDER', DIRECTORY_SEPARATOR . 'pub');
define('NABU_LIB_FOLDER', DIRECTORY_SEPARATOR . 'lib');
define('NABU_TMP_FOLDER', DIRECTORY_SEPARATOR . 'tmp');
define('NABU_VAR_FOLDER', DIRECTORY_SEPARATOR . 'var');
define('NABU_LOG_FOLDER', DIRECTORY_SEPARATOR . 'log');

define('NABU_ICONTACT_FOLDER', DIRECTORY_SEPARATOR . 'icontact');

define('NABU_RUNTIME_FOLDER', DIRECTORY_SEPARATOR . 'runtime');
define('NABU_COMMONDOCS_FOLDER', NABU_PUB_FOLDER . DIRECTORY_SEPARATOR . 'commondocs');
define('NABU_HTTPDOCS_FOLDER', NABU_PUB_FOLDER . DIRECTORY_SEPARATOR . 'httpdocs');
define('NABU_HTTPSDOCS_FOLDER', NABU_PUB_FOLDER . DIRECTORY_SEPARATOR . 'httpsdocs');
define('NABU_PHP_FOLDER', NABU_SRC_FOLDER . DIRECTORY_SEPARATOR . 'php');
define('NABU_VHOSTS_FOLDER', DIRECTORY_SEPARATOR . 'vhosts');
define('NABU_CLASSES_FOLDER', DIRECTORY_SEPARATOR . 'classes');
define('NABU_PLUGINS_FOLDER', DIRECTORY_SEPARATOR . 'plugins');
define('NABU_PROVIDERS_FOLDER', DIRECTORY_SEPARATOR . 'providers');
define('NABU_CACHE_FOLDER', DIRECTORY_SEPARATOR . 'cache');
define('NABU_VHOST_CONFIG_FOLDER', DIRECTORY_SEPARATOR . 'conf');

$dirname = dirname(__FILE__);
while (strlen($dirname) > 0 && !defined('NABU_BASE_PATH')) {
    if (file_exists($dirname . NABU_ETC_FOLDER)) {
        define('NABU_BASE_PATH', $dirname);
    } else {
        $dirname = dirname($dirname);
    }
}

if (!defined('NABU_BASE_PATH')) {
    error_log('nabu-3 base path not found.');
    die();
}

define('NABU_ETC_PATH', NABU_BASE_PATH . NABU_ETC_FOLDER);
define('NABU_LOG_PATH', NABU_BASE_PATH . NABU_VAR_FOLDER . NABU_LOG_FOLDER);

define('NABU_SRC_PATH', NABU_BASE_PATH . NABU_SRC_FOLDER);
define('NABU_SDK_PATH', NABU_BASE_PATH . NABU_SDK_FOLDER . NABU_SRC_FOLDER);
define('NABU_LIB_PATH', NABU_BASE_PATH . NABU_LIB_FOLDER);
define('NABU_PUB_PATH', NABU_BASE_PATH . NABU_PUB_FOLDER);
define('NABU_VAR_PATH', '/var/opt/nabu-3');

define('NABU_PROVIDERS_PATH', NABU_PUB_PATH . NABU_PROVIDERS_FOLDER);
define('NABU_RUNTIME_PATH', NABU_PUB_PATH . NABU_RUNTIME_FOLDER);

define('NABU_WEB_PATH', NABU_VAR_PATH);
define('NABU_VHOSTS_PATH', NABU_WEB_PATH . NABU_VHOSTS_FOLDER);
define('NABU_VLIB_PATH', NABU_WEB_PATH . NABU_LIB_FOLDER);
define('NABU_VCACHE_PATH', NABU_WEB_PATH . NABU_CACHE_FOLDER);

define('NABU_ICONTACT_PATH', NABU_VAR_PATH . NABU_ICONTACT_FOLDER);

/* Class constants */
define('NABU_ENGINE_CLASS', '\nabu\core\CNabuEngine');

/* Engine constants */
define('NABU_PATH_PARAM', '__x_nb_path');

/* Table constants */
define('NABU_LANG_TABLE', 'nb_language');
define('NABU_LANG_FIELD_ID', 'nb_language_id');
define('NABU_LANG_TABLE_SUFFIX', '_lang');

define('NABU_CUSTOMER_TABLE', 'nb_customer');
define('NABU_CUSTOMER_FIELD_ID', 'nb_customer_id');

define('NABU_ROLE_TABLE', 'nb_role');
define('NABU_ROLE_FIELD_ID', 'nb_role_id');

define('NABU_USER_TABLE', 'nb_user');
define('NABU_USER_FIELD_ID', 'nb_user_id');

define('NABU_USER_GROUP_TABLE', 'nb_user_group');
define('NABU_USER_GROUP_FIELD_ID', 'nb_user_group_id');

define('NABU_USER_GROUP_TYPE_TABLE', 'nb_user_group_type');
define('NABU_USER_GROUP_TYPE_FIELD_ID', 'nb_user_group_type_id');

define('NABU_SITE_TABLE', 'nb_site');
define('NABU_SITE_FIELD_ID', 'nb_site_id');

define('NABU_SITE_ALIAS_TABLE', 'nb_site_alias');
define('NABU_SITE_ALIAS_FIELD_ID', 'nb_site_alias_id');

define('NABU_SITE_MAP_TABLE', 'nb_site_map');
define('NABU_SITE_MAP_FIELD_ID', 'nb_site_map_id');

define('NABU_SITE_TARGET_TABLE', 'nb_site_target');
define('NABU_SITE_TARGET_FIELD_ID', 'nb_site_target_id');

define('NABU_SITE_TARGET_CTA_TABLE', 'nb_site_target_cta');
define('NABU_SITE_TARGET_CTA_FIELD_ID', 'nb_site_target_cta_id');

define('NABU_DOMAIN_ZONE_TABLE', 'nb_domain_zone');
define('NABU_DOMAIN_ZONE_FIELD_ID', 'nb_domain_zone_id');

define('NABU_DOMAIN_ZONE_HOST_TABLE', 'nb_domain_zone_host');
define('NABU_DOMAIN_ZONE_HOST_FIELD_ID', 'nb_domain_zone_host_id');

define('NABU_COMMERCE_TABLE', 'nb_commerce');
define('NABU_COMMERCE_FIELD_ID', 'nb_commerce_id');

define('NABU_COMMERCE_PRODUCT_TABLE', 'nb_commerce_product');
define('NABU_COMMERCE_PRODUCT_FIELD_ID', 'nb_commerce_product_id');

define('NABU_COMMERCE_PRODUCT_CATEGORY_TABLE', 'nb_commerce_product_category');
define('NABU_COMMERCE_PRODUCT_CATEGORY_FIELD_ID', 'nb_commerce_product_category_id');

define('NABU_MEDIOTECA_TABLE', 'nb_medioteca');
define('NABU_MEDIOTECA_FIELD_ID', 'nb_medioteca_id');

define('NABU_CATALOG_TABLE', 'nb_catalog');
define('NABU_CATALOG_FIELD_ID', 'nb_catalog_id');
define('NABU_CATALOG_ITEM_TABLE', 'nb_catalog_item');
define('NABU_CATALOG_ITEM_FIELD_ID', 'nb_catalog_item_id');

define('NABU_MESSAGING_TABLE', 'nb_messaging');
define('NABU_MESSAGING_FIELD_ID', 'nb_messaging_id');

define('NABU_MESSAGING_SERVICE_TABLE', 'nb_messaging_service');
define('NABU_MESSAGING_SERVICE_FIELD_ID', 'nb_messaging_service_id');

define('NABU_MESSAGING_TEMPLATE_TABLE', 'nb_messaging_template');
define('NABU_MESSAGING_TEMPLATE_FIELD_ID', 'nb_messaging_template_id');

define('NABU_ICONTACT_TABLE', 'nb_icontact');
define('NABU_ICONTACT_FIELD_ID', 'nb_icontact_id');
define('NABU_ICONTACT_PROSPECT_TABLE', 'nb_icontact_prospect');
define('NABU_ICONTACT_PROSPECT_FIELD_ID', 'nb_icontact_prospect_id');
define('NABU_ICONTACT_PROSPECT_ATTACHMENT_TABLE', 'nb_icontact_prospect_attachment');
define('NABU_ICONTACT_PROSPECT_ATTACHMENT_FIELD_ID', 'nb_icontact_prospect_attachment_id');

define('NABU_PROJECT_TABLE', 'nb_project');
define('NABU_PROJECT_FIELD_ID', 'nb_project_id');

/* Regular expression patterns */
define('NABU_REGEXP_PATTERN_KEY', '^[a-zA-Z_][a-zA-Z0-9_]+$');

/* Encoding algorithms */
define('NABU_TEMP_ENC_ID_EXPIRATION_TIME', 1800);
define('NABU_ENC_ID_PREF', '#hKBFA7C');
define('NABU_ENC_ID_SUFF', 'v8YgA.tk');
define('NABU_ENC_LOGIN_PREF','nasn2293');
define('NABU_ENC_LOGIN_SUFF', '935nkwnf');
