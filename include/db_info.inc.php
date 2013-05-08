<?
	$DB_HOST=""; // ex) localhost
	$DB_NAME=""; // ex) mysql db_name
	$DB_USER=""; // ex) mysql user name
	$DB_PASS=""; // ex) mysql db password
	// connect db 
	$OJ_NAME="lavida.us"; // ex) lavida.us
	$OJ_HOME="."; // ex) .
	$OJ_ADMIN="root@localhost"; // ex) root@localhost
	$OJ_DATA="/home/judge/data/"; // ex) /home/judge/data
	$OJ_BBS="bbs";//"bbs" for phpBB3 bridge or "discuss" for mini-forum
	static  $OJ_SIM=false;

	if(mysql_pconnect($DB_HOST,$DB_USER,$DB_PASS));
	else die('Could not connect: ' . mysql_error());
	// use db
	mysql_query("set names utf8");
	mysql_set_charset("utf8");
	
	if(mysql_select_db($DB_NAME));
	else die('Can\'t use foo : ' . mysql_error());
	// @session_start();
?>
