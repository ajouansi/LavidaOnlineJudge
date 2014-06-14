# LAVIDA ONLINE JUDGE V3 ops script based on Ubuntu trusty64
### comment : 소스설치가 안정적이고 좋음

# need argument $1 (INSTALL_PATH)
if [[ -z "$1" ]]; then
	echo "NO INSTALL_PATH SPECIFIED"
	exit
fi

# settings

INSTALL_PATH=$1

mkdir $INSTALL_PATH

mkdir $INSTALL_PATH/data

mkdir $INSTALL_PATH/setup
mkdir $INSTALL_PATH/setup/src

mkdir $INSTALL_PATH/config
mkdir $INSTALL_PATH/packages
mkdir $INSTALL_PATH/packages/apache2
mkdir $INSTALL_PATH/packages/mariadb
mkdir $INSTALL_PATH/packages/php

# install components

## libxml2
sudo dpkg --purge --force-depends "libxml2"
sudo apt-get install -f -y
sudo apt-get install -y git lxc curl unzip tree rdate build-essential cmake auto-apt libncurses5-dev libaio-dev zlib1g-dev libxml2-dev

# time setting
sudo rdate -s time.bora.net
