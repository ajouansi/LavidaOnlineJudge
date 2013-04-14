<?
	if(defined('__FROM_INDEX__')==false) exit;
	if( !isset($_SESSION['user_id']) ) errorpage("Login Plz~!!");
	define('__FROM_MENU__','1');

	$menu=Array("account"=>"phps/setting_account.php",
				"theme"=>"phps/setting_theme.php",
				"collections"=>"phps/setting_collections.php");
?>
<div class="row-fluid">
	<div class="span3">
		<div class="well" style="max-width: 340px; padding: 8px 0;">
			<ul class="nav nav-list">
				<li class="nav-header">Settings</li>
				<li class="divider"></li>
				<li><a href="/settings/account">Account</a></li>
				<li><a href="/settings/theme">Theme</a></li>
				<li><a href="/settings/collections">Collections</a></li>
			</ul>
		</div>
	</div>
	<div class="span9" id="spage">
		<?if(isset($_GET['page']))include($menu[$_GET['page']]);?>
	</div>
</div>
