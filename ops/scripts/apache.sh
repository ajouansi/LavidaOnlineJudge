# LAVIDA ONLINE JUDGE V3 ops script based on Ubuntu trusty64
### comment : 소스설치가 안정적이고 좋음

# need argument $1 (INSTALL_PATH)
if [[ -z "$1" ]]; then
	echo "NO INSTALL_PATH SPECIFIED"
	exit
fi

# settings

INSTALL_PATH=$1

# install apache

## apr
wget  http://apache.mirror.cdnetworks.com//apr/apr-1.5.1.tar.gz -O $INSTALL_PATH/setup/src/apr-1.5.1.tar.gz
cd $INSTALL_PATH/setup/src/
tar xvzf apr-1.5.1.tar.gz

## apr-util
wget  http://apache.mirror.cdnetworks.com//apr/apr-util-1.5.3.tar.gz -O $INSTALL_PATH/setup/src/apr-util-1.5.3.tar.gz
cd $INSTALL_PATH/setup/src/
tar xvzf apr-util-1.5.3.tar.gz

## pcre
wget  ftp://ftp.csx.cam.ac.uk/pub/software/programming/pcre/pcre-8.35.tar.gz
cd $INSTALL_PATH/setup/src/
tar xvzf pcre-8.35.tar.gz
cd pcre-8.35
./configure
make && sudo make install

## ldconfig
sudo ldconfig

## apache2
wget  http://mirror.apache-kr.org//httpd/httpd-2.4.9.tar.gz -O $INSTALL_PATH/setup/src/apache-2.4.9.tar.gz
cd $INSTALL_PATH/setup/src/
tar xvzf apache-2.4.9.tar.gz
cd httpd-2.4.9
cp -rf $INSTALL_PATH/setup/src/apr-1.5.1 ./srclib/apr
cp -rf $INSTALL_PATH/setup/src/apr-util-1.5.3 ./srclib/apr-util
auto-apt -y run ./configure --prefix=$INSTALL_PATH/packages/apache2 --with-included-apr
make && make install

# clone lavida
git clone https://github.com/flrngel/LavidaOnlineJudge $INSTALL_PATH/web

## apache2 config
sudo rm -rf $INSTALL_PATH/packages/apache2/htdocs
sudo ln -s $INSTALL_PATH/web $INSTALL_PATH/packages/apache2/htdocs

### add to service daemon
sudo cp $INSTALL_PATH/packages/apache2/bin/apachectl /etc/init.d/apache2
