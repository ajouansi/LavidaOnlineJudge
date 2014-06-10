
REPO="
deb http://ftp.neowiz.com/ubuntu/ trusty main restricted
deb-src http://ftp.neowiz.com/ubuntu/ trusty main restricted
deb http://ftp.neowiz.com/ubuntu/ trusty universe
deb-src http://ftp.neowiz.com/ubuntu/ trusty universe
deb http://ftp.neowiz.com/ubuntu/ trusty multiverse
deb-src http://ftp.neowiz.com/ubuntu/ trusty multiverse
"
sudo bash -c "echo '$REPO' > /etc/apt/sources.list"

sudo apt-get update
sudo apt-get install -f
sudo apt-get upgrade
