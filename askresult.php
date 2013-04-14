<?
	session_start();
	require_once('include/db_info.inc.php');
	$sql="SELECT `result` from solution where solution_id='$rid'";
	$tmp=@mysql_query($sql);
	$res=mysql_fetch_object($tmp);
	echo $res->result;
?>
