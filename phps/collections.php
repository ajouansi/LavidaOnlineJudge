<?
	if(defined('__FROM_INDEX__')==false) exit;
	$active=Array();
	if( $_GET['show'] == 'unofficial' ){ $active[1]="active"; $div=2; }
	else{ $active[0]="active"; $div=1; }
?>
<div class="tabable">
	<ul class="nav nav-tabs">
		<li class="<?=$active[0]?>"><a href="/collections/official">Official</a></li>
		<li class="<?=$active[1]?>"><a href="/collections/unofficial">Unofficial</a></li>
	</ul>
</div>
<?if(isset($_SESSION['user_id'])){?><a href="/settings/collections" class="pull-right" style="margin-bottom:5px;margin-right:10px;"><i class="icon-wrench"></i></a><?}?>
<table class="table table-striped table-hover table-bordered">
	<thead>
		<tr style="font-size:11pt">
			<th style="width:8%">ID</th>
			<th style="width:8%">Owner</th>
			<th style="width:68%">Title</th>
			<th style="width:8%">Problems</th>
			<th style="width:8%">Div</th>
		</tr>
	</thead>
	<tbody>
<?
	$sql="select * from `collections` where `div`='$div'";
	$tmp=@mysql_query($sql);
	$i=0;
	while($res=@mysql_fetch_object($tmp))
	{
		$i++;
		if($res->div==1) $label="<span class=\"label label-info\">Official</span>";
		else $label="<span class=\"label label-warning\">Unofficial</span>";
?>
		<tr style="font-size:10pt">
			<td><?=$i?></td>
			<td><a href="/profile/<?=$res->user_id?>"><?=$res->user_id?></a></td>
			<td><a href="/collection/<?=$res->col_id?>"><?=$res->title?></a></td>
			<td><?=$res->cnt?></td>
			<td><?=$label?></td>
		</tr>
<?
	}
?>
	</tbody>
</table>
