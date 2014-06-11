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

	# initialize script
	config.vm.provision "shell" do |shell|
		shell.path="ops/scripts/init.sh"
		shell.args=[INSTALL_PATH]
	end

#	# cook
#	config.vm.provision "chef_solo" do |chef|
#		chef.cookbooks_path=["ops/cookbooks"]
#		
#		#chef.add_recipe "apt"
#		chef.add_recipe "apache2"
#		chef.add_recipe "mariadb"
#
#		chef.json={
##			"apache" => {
##				"dir" => INSTALL_PATH+"/packages/apache2",
##				"log_dir" => INSTALL_PATH+"/logs"
##			},
#			"mariadb"=> {
#				"server_root_password" => "vagrant"
#			}
#		}
#	end

	# port forwarding
	config.vm.network "forwarded_port", guest:80, host:8080
end
