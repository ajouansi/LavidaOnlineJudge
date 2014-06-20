#
# Cookbook Name:: mariadb
# Recipe:: default
#
# Copyright 2014, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#

remote_file "#{node['loj']['path']}/setup/src/mariadb-#{node['mariadb']['version']}.tar.gz" do
	source "http://ftp.yz.yamagata-u.ac.jp/pub/dbms/mariadb/mariadb-#{node['mariadb']['version']}/source/mariadb-#{node['mariadb']['version']}.tar.gz"
end

bash "mariradb_install" do
	user "#{node['loj']['user']}"
	cwd "#{node['loj']['path']}/setup/src"
	code <<-EOH
		mkdir #{node['loj']['path']}/packages/mariadb
		mkdir #{node['loj']['path']}/data/mariadb

		tar xvzf mariadb-#{node['mariadb']['version']}.tar.gz
		cd mariadb-#{node['mariadb']['version']}
		mkdir build
		cd build
		cmake .. \
		-DBUILD_CONFIG=mysql_release \
		-DWITH_READLINE=1 \
		-DWITH_SSL=bundled \
		-DWITH_ZLIB=system \
		-DDEFAULT_CHARSET=utf8 \
		-DDEFAULT_COLLATION=utf8_general_ci \
		-DDEFAULT_ENGINE=INNOBASE \
		-DENABLED_LOCAL_INFILE=1 \
		-DWITH_EXTRA_CHARSETS=all \
		-DWITH_ARIA_STORAGE_ENGINE=1 \
		-DWITH_XTRADB_STORAGE_ENGINE=0 \
		-DWITH_ARCHIVE_STORAGE_ENGINE=0 \
		-DWITH_INNOBASE_STORAGE_ENGINE=1 \
		-DWITH_PARTITION_STORAGE_ENGINE=0 \
		-DWITH_BLACKHOLE_STORAGE_ENGINE=0 \
		-DWITH_FEDERATEDX_STORAGE_ENGINE=0 \
		-DWITH_PERFSCHEMA_STORAGE_ENGINE=1 \
		-DCMAKE_INSTALL_PREFIX=#{node['loj']['path']}/packages/mariadb \
		-DMYSQL_DATADIR=#{node['loj']['path']}/data/mariadb
		make && make install

		ln -s #{node['loj']['path']}/packages/mariadb/lib #{node['loj']['path']}/packages/mariadb/lib64
		sudo bash -c "echo \"#{node['loj']['path']}/packages/mariadb/lib\" > /etc/ld.so.conf.d/mysql.conf"

		sudo groupadd -g 27 -o -r mysql
		sudo useradd -M -g mysql -o -r -d #{node['loj']['path']}/data/mariadb/data -s /bin/false -c "MariaDB" -u 27 mysql

		sudo cp #{node['loj']['path']}/packages/mariadb/support-files/mysql.server /etc/init.d/mysqld
		sudo update-rc.d mysqld defaults

		sudo cp #{node['loj']['path']}/packages/mariadb/support-files/my-small.cnf /etc/my.cnf
		sudo ln -s /etc/my.cnf #{node['loj']['path']}/config/my.cnf
		
		echo "PATH=\$PATH:#{node['loj']['path']}/packages/mariadb/bin" >> ~#{node['loj']['user']}/.bashrc
	EOH
end
