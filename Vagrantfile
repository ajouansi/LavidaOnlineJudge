# -*- mode: ruby -*-
# vim: set ft=ruby :

# Vagrantfile Lavida 3.0
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "ubuntu/trusty64"

	config.vm.define :LOJ3_VM do |vm|
	end

#	config.vm.provision "shell" do |shell|
#		shell.path="ops/scripts/repo.sh"
#	end

	config.vm.provision "shell" do |shell|
		shell.path="ops/scripts/init.sh"
	end
end
