<?
	if(defined('__FROM_INDEX__')==false) exit;
	if( !isset($_SESSION['user_id']) ) exit;
	if( isset($_POST['send']) )
	{
		if( $_POST['lavida_pw'] != $_POST['lavida_pw_confirm'] ) alertBox("<strong>Error!</strong> Confirm your password!");

		$old_password = MD5($_POST['lavida_old_pw']);
		$sql="select * from `users` where `user_id`='$_SESSION[user_id]' AND `password`='$old_password'";
		$tmp=@mysql_query($sql);
		$rows=@mysql_num_rows($tmp);

		if( $rows <= 0 ) alertBox("<strong>Error!</strong> Your old password is incorrect!");

		else if( $_POST['lavida_pw'] != $_POST['lavida_pw_confirm'] ) alertBox("<strong>Error!</strong> Check your new password!");
		else
		{
			$pw=md5($_POST['lavida_pw']);
			$sql="UPDATE `users` SET ";
			if( strlen($_POST['lavida_pw'])>0 )$sql.="`password` = '$pw',";
			$sql.="`school`='$_POST[lavida_school]' WHERE `user_id`='$_SESSION[user_id]';";
			$tmp=@mysql_query($sql);
			if( $tmp )
			{
				infoBox("<strong>Success</strong> Update Complete!");
			}
			else
			{
				alertBox("<strong>Error</strong> Contact Administrator!");
			}
		}
	}
	else
	{
		$sql="select * from `users` where `user_id`='$_SESSION[user_id]'";
		$tmp=@mysql_query($sql);
		$res=@mysql_fetch_object($tmp);
?>
<form action="" method="post" class="form-horizontal" id="myform">
	<legend>Account</legend>
	<input type="hidden" name="send" value="ok">
	<div class="control-group">
		<label class="control-label" for="lavida_id">ID</label>
		<div class="controls">
			<input type="text" name="lavida_id" id="lavida_id" tabindex="1" disabled value='<?=$_SESSION['user_id'];?>'>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="lavida_school">School</label>
		<div class="controls">
			<input type="text" name="lavida_school" id="lavida_school" tabindex="2" value='<?=$res->school?>'/>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="lavida_old_pw">Old password</label>
		<div class="controls">
			<input type="password" name="lavida_old_pw" id="lavida_old_pw" tabindex="3">
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="lavida_pw">New password</label>
		<div class="controls">
			<input type="password" name="lavida_pw" id="lavida_pw" tabindex="4">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="lavida_pw_confirm">Password confirm</label>
		<div class="controls">
			<input type="password" name="lavida_pw_confirm" id="lavida_pw_confirm" tabindex="5">
		</div>
	</div>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary btn-large" id="register" tabindex="6">Update!</button>
	</div>
</form>
<script>
	$(document).ready(function(){
		$('#myform').validate({
			rules: {
				lavida_old_pw: {
					required: true,
					minlength: 4
				},
				lavida_pw: {
					required: false,
					minlength: 4
				},
				lavida_pw_confirm: {
					required: false,
					minlength: 4,
					equalTo: "#lavida_pw"
				},
			},
			messages: {
				lavida_id: {
					required: "Please input your id",
					minlength: "Length has to be more than 3",
					maxlength: "Length has to be less or equal than 20",
					remote: "invalidate "
				},
				lavida_old_pw: {
					required: "Please input your old password",
					minlength: "Length has to be more than 4"
				},
				lavida_pw: {
					required: "Please input your new password",
					minlength: "Length has to be more than 4"
				},
				lavida_pw_confirm: {
					required: "Please input your new password confirm",
					minlength: "Length has to be more than 4",
					equalTo: "Password does not match",
				},
			},
			errorPlacement: function(error, element){
				error.addClass("help-inline").appendTo(element.parent());
			},
			highlight: function(element, errorClass){
				$(element).parent().parent().removeClass("success").addClass("error");
			},
			success: function(label){
				label.addClass("help-inline").parent().parent().removeClass("error").addClass("success");
			},
			errorElement: "span",
		});
	});
</script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.min.js"></script>
<?
	}
?>
