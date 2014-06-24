if [[ -z $1 ]]; then
	echo "no argv[1] (start|stop|restart ... etc)"
	exit
fi

# daemon start
sudo service mysqld $1
sudo service apache2 $1
