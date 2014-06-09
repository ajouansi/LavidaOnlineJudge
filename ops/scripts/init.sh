# LAVIDA ONLINE JUDGE V3 ops script based on Ubuntu trusty64
### comment : chef-solo를 쓸까말까 고민중..

# settings

mkdir ~/setup
mkdir ~/setup/src

mkdir ~/package
mkdir ~/package/apache2

# install components
sudo apt-get install -y git lxc curl libcurl3 libcurl-dev wget unzip tree rdate

# time setting
sudo rdate -s time.bora.net




# install apache

## apr
wget http://apache.tt.co.kr//apr/apr-1.5.1.tar.gz -O ~/setup/src/apr-1.5.1.tar.gz
cd ~/setup/src/
tar xvzf apr-1.5.1.tar.gz

## apr-util
wget http://apache.tt.co.kr//apr/apr-util-1.5.3.tar.gz -O ~/setup/src/apr-util-1.5.3.tar.gz
cd ~/setup/src/
tar xvzf apr-util-1.5.3.tar.gz

## pcre
wget ftp://ftp.csx.cam.ac.uk/pub/software/programming/pcre/pcre-8.35.tar.gz
cd ~/setup/src/
tar xvzf pcre-8.35.tar.gz
cd pcre-8.35
./configure
make && sudo make install

## ldconfig
sudo ldconfig

## apache2
wget http://mirror.apache-kr.org//httpd/httpd-2.4.9.tar.gz -O ~/setup/src/apache-2.4.9.tar.gz
cd ~/setup/src/
tar xvzf apache-2.4.9.tar.gz
cd apache-2.4.9
mv ~/setup/src/apr-1.5.1 ./srclib/apr
mv ~/setup/src/apr-util-1.5.3 ./srclib/apr-util
./configure --prefix=~/package/apache2 --with-included-apr
make && make install

# clone lavida
git clone https://github.com/flrngel/lavida ~/lavida-webserver

## apache2 config
sudo rm -rf ~/package/apache2/htdocs
sudo ln -s ~/lavida/webserver ~/package/apache2/htdocs

### add to service daemon
sudo cp ~/package/apache2/bin/apachectl /etc/init.d/apache2




# install mysql
wget http://ftp.kaist.ac.kr/mariadb/mariadb-10.0.11/source/mariadb-10.0.11.tar.gz -O ~/setup/src/mariadb-10.0.11.tar.gz
cd ~/setup/src
tar xvzf mariadb-10.0.11.tar.gz
cd mariadb-10.0.11
./configure --prefix=~/package/mariadb
make && make install

### add to service daemon
sudo cp ~/package/mariadb/share/mysql.server /etc/init.d/mysqld
