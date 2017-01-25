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
 * @author Rafael Gutierrez <rgutierrez@wiscot.com>
 * @version 3.0.0 Surface
 */

/* Main constants definition */
define('NABU_VERSION', '3.0.0 Surface');
define('NABU_OWNER', 'Where Ideas Simply Come True, S.L.');
define('NABU_LICENSE_TITLE', 'Licensed under the Apache License, Version 2.0');
define('NABU_LICENSE_TARGET', 'http://www.apache.org/licenses/LICENSE-2.0');

/* File configuration names */
define('NABU_DB_DEFAULT_FILENAME_CONFIG', 'nabu-db-config.php.conf');
define('NABU_VHOST_CONFIG_FILENAME', 'httpd.include');

/* Path constants definition */
define('NABU_PHPUTILS_FOLDER', DIRECTORY_SEPARATOR . 'phputils');
define('NABU_RUNTIME_FOLDER', DIRECTORY_SEPARATOR . 'runtime');
define('NABU_COMMONDOCS_FOLDER', DIRECTORY_SEPARATOR . 'commondocs');
define('NABU_HTTPDOCS_FOLDER', DIRECTORY_SEPARATOR . 'httpdocs');
define('NABU_HTTPSDOCS_FOLDER', DIRECTORY_SEPARATOR . 'httpsdocs');
define('NABU_VHOSTS_FOLDER', DIRECTORY_SEPARATOR . 'vhosts');
define('NABU_CLASSES_FOLDER', DIRECTORY_SEPARATOR . 'classes');
define('NABU_PLUGINS_FOLDER', DIRECTORY_SEPARATOR . 'plugins');
define('NABU_PROVIDERS_FOLDER', DIRECTORY_SEPARATOR . 'providers');
define('NABU_CACHE_FOLDER', DIRECTORY_SEPARATOR . 'cache');
define('NABU_VHOST_CONFIG_FOLDER', DIRECTORY_SEPARATOR . 'conf');

define('NABU_ETC_PATH', '/etc/opt/nabu-3.conf.d');
define('NABU_LOG_PATH', '/var/log/nabu-3');
define('NABU_BASE_PATH', '/opt/nabu-3');
define('NABU_PHPUTILS_PATH', NABU_BASE_PATH . NABU_PHPUTILS_FOLDER);

define('NABU_WEB_PATH', '/var/opt/nabu-3');
define('NABU_VHOSTS_PATH', NABU_WEB_PATH . NABU_VHOSTS_FOLDER);

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

define('NABU_MEDIOTECA_TABLE', 'nb_medioteca');
define('NABU_MEDIOTECA_FIELD_ID', 'nb_medioteca_id');

define('NABU_CATALOG_TABLE', 'nb_catalog');
define('NABU_CATALOG_FIELD_ID', 'nb_catalog_id');

/* Trace files */
define('NABU_TRACE_AUTOLOAD', false);