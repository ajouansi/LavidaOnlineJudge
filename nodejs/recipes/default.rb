#
# Cookbook Name:: nodejs
# Recipe:: default
#
# Copyright 2014, YOUR_COMPANY_NAME
#
# All rights reserved - Do Not Redistribute
#
remote_file "#{node['loj']['path']}/setup/src/node-#{node['nodejs']['version']}.tar.gz" do
	source "http://nodejs.org/dist/v0.10.29/node-#{node['nodejs']['version']}.tar.gz"
end

bash "nodejs_install" do
	user "#{node['loj']['user']}"
	cwd "#{node['loj']['path']}/setup/src"
	code <<-EOH
		mkdir #{node['loj']['path']}/packages/node

		tar xvzf node-#{node['nodejs']['version']}.tar.gz
		cd node-#{node['nodejs']['version']}
		sudo auto-apt -y run ./configure --prefix=#{node['loj']['path']}/packages/node
		make && make install
		
		echo "PATH=\$PATH:#{node['loj']['path']}/packages/node/bin" >> ~#{node['loj']['user']}/.bashrc
	EOH
end
