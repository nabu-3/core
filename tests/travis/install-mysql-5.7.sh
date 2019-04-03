#!/usr/bin/env bash

set -ex

echo "Installing MySQL 5.7..."

sudo service mysql stop
sudo apt-get remove "^mysql.*"
sudo apt-get autoremove
sudo apt-get autoclean
echo mysql-apt-config mysql-apt-config/select-server select mysql-5.7 | sudo debconf-set-selections
wget https://dev.mysql.com/get/mysql-apt-config_0.8.6-1_all.deb
sudo DEBIAN_FRONTEND=noninteractive dpkg -i mysql-apt-config_0.8.6-1_all.deb
sudo rm -rf /var/lib/apt/lists/*
sudo apt-get clean
sudo apt-get update -q
sudo apt-get install -q -y --allow-unauthenticated -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold" mysql-server libmysqlclient-dev
sudo mysql_upgrade

echo "Restart mysql..."
sudo mysql -e "use mysql; update user set authentication_string=PASSWORD('') where User='root'; update user set plugin='mysql_native_password';FLUSH PRIVILEGES;"
sudo mysql -e "create schema if not exists `nabu-3` default charset set='utf8mb4' default collate='utf8mb4_general_ci'"
sudo mysql -e "grant all on 'nabu-3'.* to 'nabu-3'@'%' identified by 'nabu-3' with grant option"
