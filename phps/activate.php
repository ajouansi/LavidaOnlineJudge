<?
	if(defined('__FROM_INDEX__')==false) exit;
	if( !isset($_SESSION['user_id']) ) errorpage();
	$user=$_SESSION['user_id'];
	$sql="select `email`,`activated` from `users` where `user_id`='$user'";
	$tmp=@mysql_query($sql);
	$res=@mysql_fetch_object($tmp);
	$_SESSION['email']=$res->email;
	function mail_utf8($to, $from_user, $from_email,$subject = '(No subject)', $message = '')
	{
		$from_user = "=?UTF-8?B?".base64_encode($from_user)."?=";
		$subject = "=?UTF-8?B?".base64_encode($subject)."?=";
		$headers = "From: $from_user <$from_email>\r\n". 
			"MIME-Version: 1.0" . "\r\n" . 
			"Content-type: text/html; charset=UTF-8" . "\r\n"; 
		return mail($to, $subject, $message, $headers); 
	}
	if( $res->activated != 0 ) errorpage();
	$ok=false;
	if( isset($_POST['send']) )
	{
		$email=$_POST['lavida_email'];
		if( filter_var($email,FILTER_VALIDATE_EMAIL) == "" )
		{
			echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button><strong>Error!</strong> \"$email\" is not validate email.</div>";
		}
		else
		{
			$sql="select count(*) from `users` where `email`='$email' and `user_id`<>'$user'";
			$tmp=@mysql_query($sql);
			$res=@mysql_fetch_row($tmp);
			if( $res[0] == 0 )
			{
				$code=sha1(time()." - Espresso Coffee - ".$user);
				$title="Lavida Online Judge Account Activation Mail";
				$message="$user 님!! 안녕하세요.<br/>"
					."본 메일은 Lavida Online Judge 시스템 계정 활성화를 위한 메일입니다.<br/>"
					."하단의 링크를 방문하셔서 계정을 활성화 해주시길 바랍니다.<br/>"
					."<a href='http://judge.lavida.us/activate/$code'>[인증하기]</a><br/>";
				$ok=mail_utf8($email,"administrator","noreply@lavida.us",$title,$message);
				//$ok=false;
				if( $ok == true )
				{
					echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button><strong>Success!</strong> Activation mail has been sent to \"$email\"</div>";
					$sql="insert into activation (user_id,code,time) values('$user','$code',now())";
					@mysql_query($sql);
					$sql="update `users` SET `email`='$email' where `user_id`='$user'";
					@mysql_query($sql);
					session_destroy();
				}
				else
				{
					echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button><strong>Error!</strong> Contact Administrator.</div>";
				}
			}
			else
			{
				echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">x</button><strong>Error!</strong> $email already exists!</div>";
			}
		}
	}
	if( $ok == false )
	{
?>
<form action="" method="post" class="form-horizontal">
<legend>You need to activate your account!</legend>
	<input type="hidden" name="send" value="ok">
	<div class="control-group">
		<label class="control-label" for="lavida_id">ID</label>
		<div class="controls">
			<input type="text" value="<?=$_SESSION['user_id']?>" disabled>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="lavida_email">Email</label>
		<div class="controls">
			<input type="text" name="lavida_email" value="<?=$_SESSION['email']?>">
		</div>
	</div>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary btn-large">Activate!</button>
	</div>
</form>
<?
	}
?>
