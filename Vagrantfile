# -*- mode: ruby -*-
# vim: set ft=ruby :

# Vagrantfile Lavida 3.0
VAGRANTFILE_API_VERSION = "2"

# Configure Variables
INSTALL_PATH="/lavida"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
	config.vm.box = "ubuntu/trusty64"

	config.ssh.username = "vagrant"
	config.ssh.password = "vagrant"

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
		shell.args=[INSTALL_PATH]
	end

	# apache
	config.vm.provision "shell" do |shell|
		shell.path="ops/scripts/apache.sh"
		shell.args=[INSTALL_PATH]
	end

	# mariadb
	config.vm.provision "shell" do |shell|
		shell.path="ops/scripts/mariadb.sh"
		shell.args=[INSTALL_PATH]
	end

	# php
	config.vm.provision "shell" do |shell|
		shell.path="ops/scripts/php.sh"
		shell.args=[INSTALL_PATH]
	end

	# after apm
	config.vm.provision "shell" do |shell|
		shell.path="ops/scripts/after_apm.sh"
		shell.args=[INSTALL_PATH]
	end

	# chown
	config.vm.provision "shell", inline: "chown %{user}:%{group} %{INSTALL_PATH}" % {
		:user => config.ssh.username,
		:group => config.ssh.username,
		:INSTALL_PATH => INSTALL_PATH
	}

	# daemon up
	config.vm.provision "shell" do |shell|
		shell.path="ops/scripts/daemon.sh"
		shell.args=["start"]
	end

	# port forwarding
	config.vm.network "forwarded_port", guest:80, host:8080
end
