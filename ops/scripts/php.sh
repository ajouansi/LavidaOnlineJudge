# LAVIDA ONLINE JUDGE V3 ops script based on Ubuntu trusty64
### comment : 소스설치가 안정적이고 좋음

# need argument $1 (INSTALL_PATH)
if [[ -z "$1" ]]; then
	echo "NO INSTALL_PATH SPECIFIED"
	exit
fi

# settings

INSTALL_PATH=$1

# install php
wget http://kr1.php.net/distributions/php-5.5.13.tar.gz -O $INSTALL_PATH/setup/src/php-5.5.13.tar.gz
cd $INSTALL_PATH/setup/src
tar xvzf php-5.5.13.tar.gz
cd php-5.5.13
auto-apt -y run ./configure --prefix=$INSTALL_PATH/packages/php --with-apxs2=$INSTALL_PATH/packages/apache2/bin/apxs --with-mysqli=$INSTALL_PATH/packages/mariadb/bin/mysql_config --with-config-file-path=$INSTALL_PATH/packages/apache2/conf --enable-exif --enable-mbstring
make && make install
sudo ln -s $INSTALL_PATH/packages/apache2/conf/php.ini $INSTALL_PATH/config/php.ini
