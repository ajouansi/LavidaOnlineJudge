# LAVIDA ONLINE JUDGE V3 ops script based on Ubuntu trusty64
### comment : 소스설치가 안정적이고 좋음

# need argument $1 (INSTALL_PATH)
if [[ -z "$1" ]]; then
	echo "NO INSTALL_PATH SPECIFIED"
	exit
fi

# settings

INSTALL_PATH=$1

# add to $PATH
echo "PATH=\$PATH:$INSTALL_PATH/packages/apache2/bin" >> ~/.bashrc
echo "PATH=\$PATH:$INSTALL_PATH/packages/mariadb/bin" >> ~/.bashrc
echo "PATH=\$PATH:$INSTALL_PATH/packages/php/bin" >> ~/.bashrc
. ~/.bashrc
