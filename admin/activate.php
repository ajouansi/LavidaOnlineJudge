<?
	session_start();
	if( !isset($_SESSION['administrator']) ) exit;
	require_once("../include/db_info.inc.php");
	$sql="select distinct `user_id` from `activation`";
	$tmp=@mysql_query($sql);
	$cnt=0;
	while($res=@mysql_fetch_object($tmp))
	{
		$sql="update `users` SET `activated`='1' where `user_id`='$res->user_id'";
		$tmp2=@mysql_query($sql);
		if( $tmp2 )
		{
			$sql="delete from `activation` where `user_id`='$res->user_id'";
			$tmp3=@mysql_query($sql);
			$cnt++;
		}
	}
	echo $cnt," person(s) has activated.";
?>
