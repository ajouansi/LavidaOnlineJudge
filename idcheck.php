<?
	session_start();
	require_once("include/db_info.inc.php");
	require_once("include/my_func.inc.php");
	$sql="select * from users where `user_id`='$_POST[id]'";
	$tmp=@mysql_query($sql);
	$cnt=@mysql_num_rows($tmp);
	if( $cnt >= 1 || !is_valid_user_name($_POST['id']) ) echo "false";
	else echo "true";
?>
