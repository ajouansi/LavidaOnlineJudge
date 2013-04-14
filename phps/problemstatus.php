<?
	if(defined('__FROM_INDEX__')==false) exit;
	$sql="select result,count(result) from `solution` where `problem_id`='$_GET[pn]' group by `result`";
	$tmp=@mysql_query($sql);
	$stats=array();
	while($res=@mysql_fetch_row($tmp))
	{
		$stats[$res[0]]=$res[1];
	}
?>
<div class="row">
	<div class="span12">
		<div class="tabable">
			<ul class="nav nav-tabs">
				<li><a href="/problemset/">Problemset</a></li>
				<li><a href="/problem/<?=$_GET['pn']?>"><?=$_GET['pn']?></a></li>
				<li class="active"><a>Hall of fame</a></li>
				<li><a href="/status/<?=$_GET['pn']?>">Status</a></li>
			</ul>
		</div>
	</div>
</div>
<div class="row">
	<div class="span2">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th colspan="2" style="text-align:center;font-size:9pt;">Statistic</th>
				</tr>
			</thead>
			<tbody style="font-size:9pt">
<?
	for($i=4;$i<=11;$i++)
	{
?>
				<tr><td style="width:36%;text-align:center"><?=$jresult[$i]?></td><td style="text-align:center"><?=(isset($stats[$i])?$stats[$i]:0)?></td></tr>
<?
	}
?>
			</tbody>
		</table>
	</div>
	<div class="span10">
		<pre>
		<table class="table">
			<thead>
				<tr><th style="font-size:12pt;">Wall of fame</th></tr>
			</thead>
			<tbody>
				<tr>
					<td>
<?
	$sql="select distinct `user_id` from `solution` where `problem_id`='$_GET[pn]' and `result`='4' order by `in_date`";
	$tmp=@mysql_query($sql);
	while($res=@mysql_fetch_object($tmp))
	{
		echo "<span><a href=\"/profile/$res->user_id\">$res->user_id</a></span> ";
	}
?>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="2" style="text-align:right;font-size:9pt;"><a href="/status/?pflag=y&problem_id=<?=$_GET['pn']?>&user=&jresult=4">[Status]</a></th>
				</tr>
			</tfoot>
		</table>
		</pre>
	</div>
</div>
