if [[ -z $1 ]]; then
	echo "no argv[1] (start|stop|restart ... etc)"
	exit
fi

# daemon start
sudo /etc/init.d/mysqld $1
sudo /etc/init.d/apache2 $1
