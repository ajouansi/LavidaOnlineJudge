# need argument $1 (INSTALL_PATH)
if [[ -z "$1" ]]; then
	echo "NO INSTALL_PATH SPECIFIED"
	exit
fi

# settings

INSTALL_PATH=$1

# install mysql
wget  http://ftp.yz.yamagata-u.ac.jp/pub/dbms/mariadb/mariadb-10.0.11/source/mariadb-10.0.11.tar.gz -O $INSTALL_PATH/setup/src/mariadb-10.0.11.tar.gz
cd $INSTALL_PATH/setup/src
tar xvzf mariadb-10.0.11.tar.gz
cd mariadb-10.0.11
mkdir build
cd build
cmake .. \
-DBUILD_CONFIG=mysql_release \
-DWITH_READLINE=1 \
-DWITH_SSL=bundled \
-DWITH_ZLIB=system \
-DDEFAULT_CHARSET=utf8 \
-DDEFAULT_COLLATION=utf8_general_ci \
-DDEFAULT_ENGINE=INNOBASE \
-DENABLED_LOCAL_INFILE=1 \
-DWITH_EXTRA_CHARSETS=all \
-DWITH_ARIA_STORAGE_ENGINE=1 \
-DWITH_XTRADB_STORAGE_ENGINE=0 \
-DWITH_ARCHIVE_STORAGE_ENGINE=0 \
-DWITH_INNOBASE_STORAGE_ENGINE=1 \
-DWITH_PARTITION_STORAGE_ENGINE=0 \
-DWITH_BLACKHOLE_STORAGE_ENGINE=0 \
-DWITH_FEDERATEDX_STORAGE_ENGINE=0 \
-DWITH_PERFSCHEMA_STORAGE_ENGINE=1 \
-DCMAKE_INSTALL_PREFIX=$INSTALL_PATH/packages/mariadb \
-DMYSQL_DATADIR=$INSTALL_PATH/data/mariadb/data
make && make install

### add library
sudo ln -s $INSTALL_PATH/packages/mariadb/lib $INSTALL_PATH/packages/mariadb/lib64
sudo bash -c "echo \"/usr/local/mariadb/lib\" > /etc/ld.so.conf.d/mysql.conf"

### add mysql user, group
sudo groupadd -g 27 -o -r mysql
useradd -M -g mysql -o -r -d $INSTALL_PATH/data/mariadb/data -s /bin/false -c "MariaDB" -u 27 mysql

### add to service daemon
sudo cp $INSTALL_PATH/packages/mariadb/support-files/mysql.server /etc/init.d/mysqld
sudo update-rc.d mysqld defaults

### link my.cnf to $INSTALL_CONF
sudo ln -s /etc/my.cnf $INSTALL_PATH/config/my.cnf
