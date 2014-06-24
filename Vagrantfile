# -*- mode: ruby -*-
# vim: set ft=ruby :

# Vagrantfile Lavida 3.0
VAGRANTFILE_API_VERSION = "2"

# Configure Variables
INSTALL_PATH="/lavida"

USER="vagrant"
USER_GROUP="vagrant"
PASSWORD="vagrant"

ADMIN_EMAIL="no-reply@example.com"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
	config.vm.box = "ubuntu/trusty64"

	config.ssh.username = USER
	config.ssh.password = PASSWORD

	# set limit
	config.vm.provider "virtualbox" do |v|
		v.memory = 2048
	#	v.cpus = 2
	end

	config.vm.define :LOJ3_VM do |vm|
	end

	# dealing shell errors
	config.ssh.shell = "bash -c 'BASH_ENV=/etc/profile exec bash'"

	# change repo to daum.net
	config.vm.provision "shell" do |shell|
		shell.path="ops/scripts/repo.sh"
	end

	# initialize script
	config.vm.provision "shell" do |shell|
		shell.path="ops/scripts/init.sh"
		shell.args=[INSTALL_PATH,USER,USER_GROUP]
	end

	# chef_solo
	config.vm.provision "chef_solo" do |chef|
		chef.add_recipe "apache"
		chef.add_recipe "mariadb"
		chef.add_recipe "php"
		chef.cookbooks_path="ops/cookbooks"

		chef.json={
			"loj" => {
				"path" => INSTALL_PATH,
				"user" => USER,
				"admin_email" => ADMIN_EMAIL
			}
		}
	end

	# daemon up
	config.vm.provision "shell" do |shell|
		shell.path="ops/scripts/daemon.sh"
		shell.args=["start"]
	end

	# port forwarding
	config.vm.network "forwarded_port", guest:80, host:8080
end
