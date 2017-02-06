#!/bin/sh
echo ========================================================================
echo Nabu 3 - Install Tool
echo ========================================================================
echo Copyright 2009-2011 Rafael Gutierrez Martinez
echo Copyright 2012-2013 Welma WEB MKT LABS, S.L.
echo Copyright 2014-2016 Where Ideas Simply Come True, S.L.
echo
echo Licensed under the Apache License, Version 2.0 \(the ""License""\);
echo you may not use this file except in compliance with the License.
echo You may obtain a copy of the License at
echo
echo     http://www.apache.org/licenses/LICENSE-2.0
echo
echo Unless required by applicable law or agreed to in writing, software
echo distributed under the License is distributed on an \"AS IS\" BASIS,
echo WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
echo See the License for the specific language governing permissions and
echo limitations under the License.
echo ========================================================================
echo

# Default variables for install.
#
# If you want to personalize install you can change these variables as your
# convenience.
# Act as your own risk changing this variables. If you are not sure,
# then please consult the installation guide.

# This variable defines the path for config files. You can change this value.
# When the PHP install script runs, he creates this path if not exists.
NABU_ETC_PATH=/etc/opt/nabu-3.conf.d
INSTALL_PATH=`realpath $0`
INSTALL_PATH=`dirname $INSTALL_PATH`

if [ -d ${NABU_ETC_PATH} ] && [ -f ${NABU_ETC_PATH}/nabu-3.conf ] ; then
    source ${NABU_ETC_PATH}/nabu-3.conf
else
    echo Install warning: Config not found. Using defaults.
    echo
    NABU_BASE_PATH=`dirname ${INSTALL_PATH}`
    NABU_BASE_PATH=`dirname ${NABU_BASE_PATH}`
    NABU_WEB_PATH=/var/opt/nabu-3
    PHP_PARAMS="-d safe_mode=Off -d open_basedir=none -d include_path=.:${NABU_BASE_PATH}/src/:${NABU_BASE_PATH}/pub/:${NABU_BASE_PATH}/sdk/:${NABU_BASE_PATH}/lib/"
fi

if [ -f ${INSTALL_PATH}/inc/install.php ] ; then
    php ${PHP_PARAMS} ${INSTALL_PATH}/inc/install.php \
        --etc-path=${NABU_ETC_PATH} \
        --base-path=${NABU_BASE_PATH} \
        --web-path=${NABU_WEB_PATH} \
        --db-host=${MYSQL_SERVER} \
        --db-port=${MYSQL_PORT} \
        --db-schema=${MYSQL_SCHEMA} \
        --db-user=${MYSQL_USER} \
        --db-password=${MYSQL_PASSWORD} \
        "$@"
else
    echo Install error: install.php script not found.
fi
