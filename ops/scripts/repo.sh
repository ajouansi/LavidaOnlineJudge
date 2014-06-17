
REPO="
deb http://ftp.daum.net/ubuntu/ trusty main restricted
deb-src http://ftp.daum.net/ubuntu/ trusty main restricted
deb http://ftp.daum.net/ubuntu/ trusty universe
deb-src http://ftp.daum.net/ubuntu/ trusty universe
deb http://ftp.daum.net/ubuntu/ trusty multiverse
deb-src http://ftp.daum.net/ubuntu/ trusty multiverse
"
sudo bash -c "echo '$REPO' > /etc/apt/sources.list"

sudo apt-get check
sudo apt-get update
sudo apt-get -y upgrade
