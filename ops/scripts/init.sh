# LAVIDA ONLINE JUDGE V3 ops script based on Ubuntu trusty64
### comment : 소스설치가 안정적이고 좋음

# need argument $1 (INSTALL_PATH)
if [!$1]; then
	echo "NO INSTALL_PATH SPECIFIED"
	exit
fi

# settings

INSTALL_PATH=$1

mkdir $INSTALL_PATH

mkdir $INSTALL_PATH/data

mkdir $INSTALL_PATH/setup
mkdir $INSTALL_PATH/setup/src

mkdir $INSTALL_PATH/packages
mkdir $INSTALL_PATH/packages/apache2

# install components
sudo apt-get install -y git lxc curl unzip tree rdate build-essential cmake auto-apt

# time setting
sudo rdate -s time.bora.net




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
mv $INSTALL_PATH/setup/src/apr-1.5.1 ./srclib/apr
mv $INSTALL_PATH/setup/src/apr-util-1.5.3 ./srclib/apr-util
auto-apt run ./configure --prefix=$INSTALL_PATH/packages/apache2 --with-included-apr
make && make install

# clone lavida
git clone https://github.com/flrngel/LavidaOnlineJudge $INSTALL_PATH/web

## apache2 config
sudo rm -rf $INSTALL_PATH/packages/apache2/htdocs
sudo ln -s $INSTALL_PATH/web $INSTALL_DIR/packages/apache2/htdocs

### add to service daemon
sudo cp $INSTALL_PATH/packages/apache2/bin/apachectl /etc/init.d/apache2




# install mysql
wget  http://ftp.yz.yamagata-u.ac.jp/pub/dbms/mariadb/mariadb-10.0.11/source/mariadb-10.0.11.tar.gz -O $INSTALL_PATH/setup/src/mariadb-10.0.11.tar.gz
cd $INSTALL_PATH/setup/src
tar xvzf mariadb-10.0.11.tar.gz
cd mariadb-10.0.11
mkdir build
cd build
cmake .. \
-DWITH_READLINE=1 \
-DWITH_SSL=bundled \
-DWITH_ZLIB=system \
-DDEFAULT_CHARSET=utf8 \
-DDEFAULT_COLLATION=utf8_general_ci \
-DENABLED_LOCAL_INFILE=1 \
-DWITH_EXTRA_CHARSETS=all \
-DWITH_ARIA_STORAGE_ENGINE=1 \
-DWITH_XTRADB_STORAGE_ENGINE=1 \
-DWITH_ARCHIVE_STORAGE_ENGINE=1 \
-DWITH_INNOBASE_STORAGE_ENGINE=1 \
-DWITH_PARTITION_STORAGE_ENGINE=1 \
-DWITH_BLACKHOLE_STORAGE_ENGINE=1 \
-DWITH_FEDERATEDX_STORAGE_ENGINE=1 \
-DWITH_PERFSCHEMA_STORAGE_ENGINE=1 \
-DCMAKE_INSTALL_PREFIX=$INSTALL_PATH/packages/mariadb \
-DMYSQL_DATADIR=$INSTALL_PATH/data/mariadb/data
make && make install

### add library
sudo ln -s $INSTALL_PATH/packages/mariadb/lib $INSTALL_PATH/packages/mariadb/lib64
sudo bash -c "echo \"/usr/local/mariadb/lib\" > /etc/ld.so.conf.d/mysql.conf"

### add mysql user, group
sudo groupadd -g 27 -o -r mysql
useradd -M -g mysql -o -r -d $INSTALL_PATH/data/mariadb/data -s /bin/false -c “MariaDB” -u 27 mysql

### add to service daemon
sudo cp $INSTALL_PATH/packages/mariadb/share/mysql.server /etc/init.d/mysqld
sudo update-rc.d mysqld defaults




# daemon start
sudo /etc/init.d/mysqld start
sudo /etc/init.d/apache2 start
