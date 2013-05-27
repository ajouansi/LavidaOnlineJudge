<?
	if(defined('__FROM_INDEX__')==false) exit;
	$sql="select `solved`,`user_id` from `users` order by `solved` DESC, `submit` ASC limit 0,100";
	$tmp=@mysql_query($sql);
	$i=0;
	$me_showed=false;
?>
<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">x</button><strong>Information</strong> Hall of fame shows only 1~100 rankers</div>
<table class="table table-striped table-hover table-bordered">
	<thead>
		<tr>
			<th style="width:6%">Rank</th>
			<th style="width:80%">User</th>
			<th>Solved</th>
		</tr>
	</thead>
	<tbody>
	<?while($res=mysql_fetch_object($tmp)){?>
		<tr <?if($res->user_id == $_SESSION['user_id']){$me_showed=true;?>class="warning" style="font-weight:bold;"<?}?>>
			<td><?=++$i?></td>
			<td><a href="/profile/<?=$res->user_id?>"><?=$res->user_id?></a></td>
			<td><?=$res->solved?></td>
		</tr>
	<?}?>
	<?if(isset($_SESSION['user_id']) && $me_showed==false){
		$sql="select `solved`,`user_id` from `users` where `user_id`='$_SESSION[user_id]'";
		$tmp=mysql_query($sql);
		$res=mysql_fetch_object($tmp);
	?>
		<tr class="warning" style="font-weight:bold;">
			<td>-</td>
			<td><a href="/profile/<?=$res->user_id?>"><?=$res->user_id?></a></td>
			<td><?=$res->solved?></td>
		</tr>
	<?}?>
	</tbody>
</table>
