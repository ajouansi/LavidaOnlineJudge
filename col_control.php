<?
	session_start();
	include "include/db_info.inc.php";

	function check_problem_auth($pid)
	{
		$sql="select * from `problem` where	`problem_id`='$pid'";
		$tmp=@mysql_query($sql);
		if( @mysql_num_rows($tmp) != 1 ) return false;
		$res=@mysql_fetch_object($tmp);
		if( $res->defunct == 'Y' && !isset($_SESSION['administrator']) ) return false;
		return true;
	}
	$col_id=$_GET['c'];

	$sql="select * from `collections` where `col_id`='$col_id'";
	$tmp=@mysql_query($sql);
	if( @mysql_num_rows($tmp) != 1 ){ echo "-1"; exit; }
	$res=@mysql_fetch_object($tmp);
	if( $res->user_id != $_SESSION['user_id'] ){ echo "-1"; exit; }
	if( !empty($_GET['pid']) && check_problem_auth($_GET['pid']) == false ){ echo "-1"; exit; }

	if( $_GET['mode'] == "push" )
	{
		$sql="select * from `collections_problem` where `pid`='$_GET[pid]' and `col_id`='$col_id'";
		$tmp=@mysql_query($sql);
		if( @mysql_num_rows($tmp) != 0 ){ echo "0"; exit; }
		$sql="insert into `collections_problem` values('','$col_id','$_GET[pid]')";
		@mysql_query($sql);
		$sql="update `collections` set `cnt`=`cnt`+'1' where `col_id`='$col_id'";
		@mysql_query($sql);
		echo "1";
	}
	else if($_GET['mode'] == "delete_col" )
	{
		$sql="delete from `collections` where `col_id`='$col_id' and `user_id`='$_SESSION[user_id]'";
		@mysql_query($sql);
		$sql="delete from `collections_problem` where `col_id`='$col_id'";
		@mysql_query($sql);
		echo "1";
	}
	else if($_GET['mode'] == "pop")
	{
		$sql="delete from `collections_problem` where `col_id`='$col_id' and `pid`='$_GET[pid]'";
		@mysql_query($sql);
		$sql="update `collections` set `cnt`=`cnt`-'1' where `col_id`='$col_id'";
		@mysql_query($sql);
		echo "1";
	}
?>
