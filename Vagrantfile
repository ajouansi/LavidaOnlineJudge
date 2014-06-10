# -*- mode: ruby -*-
# vim: set ft=ruby :

# Vagrantfile Lavida 3.0
VAGRANTFILE_API_VERSION = "2"

# Configure Variables
INSTALL_PATH="/lavida"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "ubuntu/trusty64"

	config.vm.define :LOJ3_VM do |vm|
	end

	# dealing shell errors
	config.ssh.shell = "bash -c 'BASH_ENV=/etc/profile exec bash'"

	# change repo to daum.net
	config.vm.provision "shell" do |shell|
		shell.path="ops/scripts/repo.sh"
	end

	# initial install script
	config.vm.provision "shell" do |shell|
		shell.path="ops/scripts/init.sh"
		shell.args=[INSTALL_PATH]
	end

	# copy configure files
	config.vm.provision "file" do |file|
		file.source="ops/conf/httpd.conf"
		file.destination=INSTALL_PATH+"/packages/apache2/conf/httpd.conf"
	end

	# port forwarding
	config.vm.network "forwarded_port", guest:80, host:8080
end
