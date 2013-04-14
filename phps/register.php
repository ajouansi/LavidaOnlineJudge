<?
	if(defined('__FROM_INDEX__')==false) exit;
	if( isset($_POST['send']) )
	{
		if( $_POST['lavida_pw'] != $_POST['lavida_pw_confirm'] ) alertBox("<strong>Error!</strong> Confirm your password!");
		$sql="select * from `users` where `user_id`='$_POST[lavida_id]'";
		$tmp=@mysql_query($sql);
		$rows=@mysql_num_rows($tmp);
		$flag=1;
		if( $rows >= 1 || strlen($_POST['lavida_id']) > 20 || strlen($_POST['lavida_id']) < 3 ) alertBox("<strong>Error!</strong> User ID '$_POST[lavida_id]' is invalid!");
		else if( $_POST['lavida_pw'] != $_POST['lavida_pw_confirm'] ) alertBox("<strong>Error!</strong> Check your password!");
		else if( !is_valid_user_name($user_id) ) alertBox("<strong>Error!</strong> User ID '$_POST[lavida_id]' is invalid!");
		else
		{
			$pw=md5($_POST['lavida_pw']);
			$sql="insert into `users` (`user_id`,`password`,`ip`,`accesstime`) values('$_POST[lavida_id]','$pw','$_SERVER[REMOTE_ADDR]',now())";
			$tmp=@mysql_query($sql);
			if( $tmp )
			{
				infoBox("<strong>Success</strong> Register Complete!");
			}
			else
			{
				alertBox("<strong>Error</strong> Contact Administrator!");
			}
		}
	}
	else
	{
?>
<form action="" method="post" class="form-horizontal" id="myform">
	<legend>Welcome to Lavida Online Judge!</legend>
	<input type="hidden" name="send" value="ok">
	<div class="control-group">
		<label class="control-label" for="lavida_id">ID</label>
		<div class="controls">
			<input type="text" name="lavida_id" id="lavida_id" tabindex="1">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="lavida_pw">Password</label>
		<div class="controls">
			<input type="password" name="lavida_pw" id="lavida_pw" tabindex="2">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="lavida_pw_confirm">Confirm</label>
		<div class="controls">
			<input type="password" name="lavida_pw_confirm" id="lavida_pw_confirm" tabindex="3">
		</div>
	</div>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary btn-large" id="register" tabindex="4">Register!</button>
	</div>
</form>
<script>
	$(document).ready(function(){
		$('#myform').validate({
			rules: {
				lavida_id: {
					required: true,
					minlength: 3,
					maxlength: 20,
					remote: {
						url: "/idcheck.php",
						type: "POST",
						data: {
							id: function(){
								return $('#lavida_id').val();
							}
						}
					}
				},
				lavida_pw: {
					required: true,
					minlength: 4
				},
				lavida_pw_confirm: {
					required: true,
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
				lavida_pw: {
					required: "Please input your password",
					minlength: "Length has to be more than 4"
				},
				lavida_pw_confirm: {
					required: "Please input your password confirm",
					minlength: "Length has to be more than 4",
					equalTo: "Password does not match"
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
