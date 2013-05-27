<?
	$sql="select `user_id` from `users`";
	$tmp=@mysql_query($sql);
	while($res=@mysql_fetch_object($tmp)){?>
<img src="/admin/reload_user.php?user=<?=$res->user_id?>"><?}?>
