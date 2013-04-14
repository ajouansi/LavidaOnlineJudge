<?
	session_start();
	define('__FROM_MPAGE__','1');
	require_once("include/db_info.inc.php");
	require_once("include/my_func.inc.php");
	if( !isset($_SESSION['user_id']) ) errorpage_nofooter("Login Plz~!!");
	$page=Array("smenu_ac"=>"phps/setting_account.php",
				"smenu_theme"=>"phps/setting_theme.php",
				"smenu_collections"=>"phps/setting_collections.php");
	if( !isset($page[$_GET['id']]) ) errorpage_nofooter();
	if(!include_once($page[$_GET['id']])) errorpage_nofooter();
?>
