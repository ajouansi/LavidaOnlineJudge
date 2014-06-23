#
# Cookbook Name:: apache
# Recipe:: default
#
# Copyright 2014, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#

# pcre install
remote_file "#{node['loj']['path']}/setup/src/pcre-#{node['apache']['pcre']['version']}.tar.gz" do
	source "ftp://ftp.csx.cam.ac.uk/pub/software/programming/pcre/pcre-#{node['apache']['pcre']['version']}.tar.gz"
end

bash "pcre_install" do
	user "#{node['loj']['user']}"
	cwd "#{node['loj']['path']}/setup/src"
	code <<-EOH
		tar xvzf pcre-#{node['apache']['pcre']['version']}.tar.gz
		cd pcre-#{node['apache']['pcre']['version']}/
		./configure
		make && sudo make install
		sudo ldconfig
	EOH
end

# httpd install
remote_file "#{node['loj']['path']}/setup/src/apr-#{node['apache']['apr']['version']}.tar.gz" do
	source "http://apache.mirror.cdnetworks.com//apr/apr-#{node['apache']['apr']['version']}.tar.gz"
end

remote_file "#{node['loj']['path']}/setup/src/apr-util-#{node['apache']['apr-util']['version']}.tar.gz" do
	source "http://apache.mirror.cdnetworks.com//apr/apr-util-#{node['apache']['apr-util']['version']}.tar.gz"
end

remote_file "#{node['loj']['path']}/setup/src/httpd-#{node['apache']['httpd']['version']}.tar.gz" do
	source "http://mirror.apache-kr.org//httpd/httpd-#{node['apache']['httpd']['version']}.tar.gz"
end

bash "httpd_install" do
	user "#{node['loj']['user']}"
	cwd "#{node['loj']['path']}/setup/src"
	code <<-EOH
		mkdir #{node['loj']['path']}/packages/apache2

		tar xvzf httpd-#{node['apache']['httpd']['version']}.tar.gz
		
		tar xvzf apr-#{node['apache']['apr']['version']}.tar.gz
		mv apr-#{node['apache']['apr']['version']} httpd-#{node['apache']['httpd']['version']}/srclib/apr

		tar xvzf apr-util-#{node['apache']['apr-util']['version']}.tar.gz
		mv apr-util-#{node['apache']['apr-util']['version']} httpd-#{node['apache']['httpd']['version']}/srclib/apr-util

		cd httpd-#{node['apache']['httpd']['version']}
		sudo auto-apt -y run ./configure --prefix=#{node['loj']['path']}/packages/apache2 --with-included-apr
		make && make install

		git clone https://github.com/flrngel/LavidaOnlineJudge #{node['loj']['path']}/web

		rm -rf #{node['loj']['path']}/packages/apache2/htdocs
		ln -s #{node['loj']['path']}/web #{node['loj']['path']}/packages/apache2/htdocs

		sudo cp -rf #{node['loj']['path']}/packages/apache2/bin/apachectl /etc/init.d/apache2

		echo \"PATH=$PATH:#{node['loj']['path']}/packages/apache2/bin\" >> ~#{node['loj']['user']}/.bashrc
	EOH
end
