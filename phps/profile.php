<?
	if(defined('__FROM_INDEX__')==false) exit;
	$user=$_GET['user'];
	$sql="select count(*) from users where user_id='$user'";
	$tmp=@mysql_query($sql);
	$res=@mysql_fetch_row($tmp);
	if( $res[0] == 0 ) exit("no such user");

	$sql="select `problem_id`,`result` from solution where user_id='$user'";
	$tmp=@mysql_query($sql);
	$solved_problem=array();
	$submit_problem=array();
	while($res=@mysql_fetch_object($tmp))
	{
		$submit_count++;
		$submit_problem[$res->problem_id]=1;
		if( $res->result == 4 )
			$solved_problem[$res->problem_id]=1;
	}
	ksort($submit_problem);
	ksort($solved_problem);
	$accept_count=count($solved_problem);

	$sql="update users set `solved`='$accetp_count',`submit`='$submit_count' where `user_id`='$user'";
	$tmp=@mysql_query($sql);

	$sql="select * from users where user_id='$user'";
	$tmp=@mysql_query($sql);
	$res=@mysql_fetch_object($tmp);
?>
<div class="row">
	<div class="span12">
		<pre><h1><?=htmlspecialchars($user)?></h1><blockquote><?=htmlspecialchars($res->nick)?></blockquote></pre>
<?
	if( isset($_SESSION['user_id']) )
	{
?>
		<div class="pull-right">
			<a href="/status/?pflag=n&problem_id=&user=<?=$user?>&jresult=">[Status]</a>
			<a href="/dual/?user1=<?=$_SESSION['user_id']?>&user2=<?=$user?>">[Dual]</a>
		</div>
<?
	}
?>
	</div>
</div>
<div class="row">
	<div class="span4">
		<table class="table table-striped">
			<thead style="font-size:10pt;font-style:none;">
				<tr>
					<td>School</td>
					<td><?=htmlspecialchars($res->school)?></td>
				</tr>
			</thead>
			<tbody style="font-size:10pt;">
				<tr>
					<td>Email</td>
					<td><?=htmlspecialchars($res->email)?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="span8">
		<table class="table table-hover">
			<thead>
				<tr>
					<td><h4>Solved Problem List</h4></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
<?
	foreach( $solved_problem as $pkey => $pbool )
	{
?>
			<a href="/problem/<?=$pkey?>"><?=$pkey?></a>
<?
	}
?>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="table table-hover">
			<thead>
				<tr>
					<th><h4>Failed Problem List</h4></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
<?
	foreach( $submit_problem as $pkey => $pbook )
	{
		if( !isset($solved_problem[$pkey]))
		{
?>
			<a href="/problem/<?=$pkey?>" class="text-error"><?=$pkey?></a>
<?
		}
	}
?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
