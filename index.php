<?
	/*ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	ini_set('error_log', dirname(__FILE__) . '/error_log.txt');*/
	error_reporting(E_ALL);
	require_once("./include/my_func.inc.php");
	require_once("./include/const.inc.php");
	require_once("./include/db_info.inc.php");
	session_start();
	define('__FROM_INDEX__','1');
	$active_menu=array();
	$now_page="phps/";
	if( isset($_GET['mode']) )
	{
		if($_GET['mode']=='status'){ $now_page.="status.php"; if($_GET['pflag']=='y'){$active_menu[2]="active";}else{$active_menu[1]="active";}}
		else if($_GET['mode']=='problemset'){ $now_page.="problemset.php"; $active_menu[2]="active";}
		else if($_GET['mode']=='problem'){ $now_page.="problem.php"; $active_menu[2]="active";}
		else if($_GET['mode']=='collections'){ $now_page.="collections.php"; $active_menu[3]="active";}
		else if($_GET['mode']=='contests'){ $now_page.="contests.php"; $active_menu[4]="active";}
		else if($_GET['mode']=='hof'){ $now_page.="hof.php"; $active_menu[5]="active";}
		else if($_GET['mode']=='profile'){ $now_page.="profile.php"; }
		else if($_GET['mode']=='source'){$now_page.="source.php";}
		else if($_GET['mode']=='register'){$now_page.="register.php";}
		else if($_GET['mode']=='activate'){$now_page.="activate_confirm.php";}
		else if($_GET['mode']=='search'){$now_page.="search.php";}
		else if($_GET['mode']=='random'){$now_page.="random_problem.php";}
		else if($_GET['mode']=='settings'){$now_page.="settings.php";}
		else if($_GET['mode']=='problemstatus'){$now_page.="problemstatus.php"; $active_menu[2]="active";}
		else if($_GET['mode']=='dual'){$now_page.="dual.php";}
		else if($_GET['mode']=='collection'){$now_page.="collection.php"; $active_menu[3]="active";}
		else if($_GET['mode']=='news'){$now_page.="news.php";}
	}
	else
		$now_page.="main.php";
	if( isset($_SESSION['user_id']) && isset($_SESSION['need_activate']) && $_GET['mode']!='activate' )
		$now_page="phps/activate.php";
?>
<!DOCTYPE html>
<html lang="kr">
	<head>
		<meta charset="utf-8">
		<title>Lavida Online Judge</title>
		<!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- Le styles -->
		<link href="/css/bootstrap.min.css" rel="stylesheet">
		<link href="/gritter/css/jquery.gritter.css" rel="stylesheet">
		<link href="/codemirror/lib/codemirror.css" rel="stylesheet">
		<link rel="stylesheet" href="/codemirror/theme/blackboard.css">
		<style type="text/css">
			body {
				padding-top: 60px;
				padding-bottom: 40px;
			}
		</style>
		<link href="/css/bootstrap-responsive.css" rel="stylesheet">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<!--script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script-->
		<script src="/gritter/js/jquery.gritter.min.js"></script>
		<script src="/codemirror/lib/codemirror.js"></script>
		<script src="/codemirror/mode/clike/clike.js"></script>

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Fav and touch icons -->
		<link rel="shortcut icon" href="/ico/favicon.ico">
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="/ico/apple-touch-icon-57-precomposed.png">
<!--Google Analytics-->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-40330748-1', 'lavida.us');
  ga('send', 'pageview');

</script>
<!--GA end-->
	</head>

	<body>
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="/">Lavida Online Judge</a>
					<div class="nav-collapse collapse">
						<ul class="nav">
							<li class="<?=$active_menu[1];?>"><a href="/status">Status</a></li>
							<li class="<?=$active_menu[2];?>"><a href="/problemset">Problems</a></li>
							<li class="<?=$active_menu[3];?>"><a href="/collections">Collections</a></li>
							<li class="<?=$active_menu[4];?>"><a href="/contests">Contests</a></li>
							<li class="<?=$active_menu[5];?>"><a href="/hof">Hall of fame</a></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Tools<b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="/random">Random Problem</a></li>
									<li><a>Documents</a></li>
									<li><a>Links</a></li>
									<li class="divider"></li>
									<li><a>Hall of Shame</a></li>
								</ul>
							</li>
						</ul>
						<ul class="nav pull-right">
							<li class="divider-vertical"></li>
<?
	if(isset($_SESSION['user_id']))
	{
?>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$_SESSION['user_id']?><b class="caret"></b></a>
								<ul class="dropdown-menu">
<?
	if(isset($_SESSION['administrator']))
	{
?>
									<li><a href="/admin/">Admin</a></li>
								<li class="divider"></li>
<?
	}
?>
									<li><a href="/profile/<?=$_SESSION['user_id']?>">Profile</a></li>
									<li><a href="/settings/">Settings</a></li>
									<li><a href="/logout.php">Logout</a></li>
								</ul>
							</li>
<?
	}
	else
	{
?>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Login<b class="caret"></b></a>
								<ul class="dropdown-menu">
									<form action="/login.php" method="post" class="form-horizontal" style="padding:15 15 15 15;">
										<table class="table">
											<tr><td colspan="2"><input type="text" class="span2" name="user_id" placeholder="ID" tabindex="0"></td></tr>
											<tr><td colspan="2"><input type="password" class="span2" name="password" placeholder="Password" tabindex="0"></td></tr>
											<tr><td style="vertical-align:middle"><a href="/register">Register</a></td><td><button type="submit" class="btn btn-primary pull-right" tabindex="0">Login</button></td></tr>
										</table>
									</form>
								</ul>
							</li>
<?
	}
?>
						</ul>
					</div><!--/.nav-collapse -->
				</div>
			</div>
		</div>

		<div class="container">

			<!--require start-->

<?
$msg=file_get_contents("admin/msg.txt");
if( strlen($msg) > 4 )
{
	infoBox($msg);
}
?>
			<?include_once $now_page;?>

			<!--require end-->

			<hr/>

			<footer>
				<div class="row-fluid">
					<center>
						<a href="https://github.com/flrngel/LavidaOnlineJudge.git" target="_blank"><img src="/image/github.png"></a>
					</center>
				</div>
				<div class="row-fluid" style="margin-top:10px">
					<center>
						<p>&copy; lavida.us 2008-2013 <a href="mailto:flrngel@gmail.com">Hwe-chul Cho</a>, <a href="mailto:libe.ajou.ac.kr">Hyun-hwan Jeong</a></p>
					</center>
				</div>
			</footer>

		</div> <!-- /container -->

		<!-- Le javascript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faste -->
		<script src="/js/bootstrap.min.js"></script>
	</body>
</html>
