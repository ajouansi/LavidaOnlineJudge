<?
	if( $_POST['posted'] == 'ok' ){
		foreach($_POST as $key=>$value){
			$_POST[$key]=htmlspecialchars($value);
		}
		$tmp=@mysql_query("insert into news (news_id,comment,time) values('','$_POST[comment]',now())");
		$mars=mysql_affected_rows($tmp);
		if( $mars ){
			echo "Success..<br/>";
		}
	}
?>
<div class="row-fluid">
	<form method="post">
		<input type="hidden" name="posted" value="ok"/>
		<input type="text" name="comment" class="span11" placeholder="comment"/>
		<input type="submit" class="btn btn-primary btn-small pull-right span1" value="Add"/>
	</form>
</div>
<?
	define('__FROM_INDEX__','1');
	include "../phps/news.php"
?>
