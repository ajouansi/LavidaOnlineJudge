<?
	if(defined('__FROM_INDEX__')==false) exit;
	if( !isset($_GET['code']) ) errorpage();
	$code=$_GET['code'];
	$sql = "select * from `activation` where `code`='$code'";
	$tmp=@mysql_query($sql);
	$num=@mysql_num_rows($tmp);
	if( $num == 1 )
	{
		$res=mysql_fetch_object($tmp);
		$user=$res->user_id;
		$sql = "update `users` SET activated='1' where `user_id`='$user'";
		@mysql_query($sql);
		$sql = "delete from `activation` where `user_id`='$user'";
		@mysql_query($sql);
		session_destroy();
?>
<div class="hero-unit">
	<h1>Email Activation Complete!</h1>
	<p></br>Welcome <?=$user?>!</p>
</div>
<?
	}
	else
	{
		errorpage();
	}
?>

