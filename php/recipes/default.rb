#
# Cookbook Name:: php
# Recipe:: default
#
# Copyright 2014, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
remote_file "#{node['loj']['path']}/setup/src/php-#{node['php']['version']}.tar.gz" do
	source "http://kr1.php.net/distributions/php-#{node['php']['version']}.tar.gz"
end

bash "php_install" do
	user "#{node['loj']['user']}"
	cwd "#{node['loj']['path']}/setup/src"
	code <<-EOH
		mkdir #{node['loj']['path']}/packages/php

		tar xvzf php-#{node['php']['version']}.tar.gz
		cd php-#{node['php']['version']}
		sudo auto-apt -y run ./configure --prefix=#{node['loj']['path']}/packages/php --with-apxs2=#{node['loj']['path']}/packages/apache2/bin/apxs --with-mysqli=#{node['loj']['path']}/packages/mariadb/bin/mysql_config --with-config-file-path=#{node['loj']['path']}/packages/apache2/conf --enable-exif --enable-mbstring
		make && make install
		sudo ln -s #{node['loj']['path']}/packages/apache2/conf/php.ini #{node['loj']['path']}/config/php.ini
	EOH
end
