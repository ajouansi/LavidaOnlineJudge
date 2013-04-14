<?
	if(defined('__FROM_INDEX__')==false) exit;
	$col_id=$_GET['col_id'];

	$sql="select `problem_id`,`result` from solution where user_id='$_SESSION[user_id]'";
	$tmp=@mysql_query($sql);
	$solved_problem=array();
	$submit_problem=array();
	while($res=@mysql_fetch_object($tmp))
	{
		$submit_problem[$res->problem_id]=true;
		if( $res->result == 4 )
			$solved_problem[$res->problem_id]=true;
	}

	$sql="select * from `collections` where `col_id`='$col_id'";
	$tmp=@mysql_query($sql);
	if( @mysql_num_rows($tmp) != 1 ) errorpage("??");
	$res=@mysql_fetch_object($tmp);
?>
<script>
	function popCol(collection_id,problem_id)
	{
		$.get("/col_control.php",{
			mode:"pop",
			c:collection_id,
			pid:problem_id
		},function(data){
			var ret=parseInt(data);
			switch(ret)
			{
				case -1:
					alert("Failed");
					break;
				case 1:
					alert("Delete was successful");
					location.reload();
					break;
				default:
					break;
			}
		});
	}
</script>
<div class="row-fluid">
	<div class="span12">
		<h1>"<span><?=$res->title?></span>"<span style="font-size:11pt;"> by <a href="/profile/<?=$res->user_id?>"><?=$res->user_id?></a></span></h1>
		<hr/>
		<blockquote><?=$res->description?></blockquote>
		<table class="table table-bordered table-striped table-hover">
			<thead style="font-size:11pt">
				<tr>
					<th style="width:8%">Problem ID</th>
					<th style="width:54%">Title</th>
					<th style="width:10%">Info</th>
					<th style="width:6%">AC</th>
					<th style="width:6%">Submit</th>
					<th style="width:6%;text-align:center;">Ratio</th>
				</tr>
			</thead>
			<tbody style="font-size:10pt">
<?
	$owner=$res->user_id;
	$sql="select * from `collections_problem` where `col_id`='$col_id'";
	$tmp=@mysql_query($sql);
	while($res=@mysql_fetch_object($tmp))
	{
		$sql2="select * from `problem` where `problem_id`='$res->pid'";
		$tmp2=@mysql_query($sql2);
		$res2=@mysql_fetch_object($tmp2);
		$del_text="";
		if( $owner == $_SESSION['user_id'] ) $del_text=" <a href=\"#\" onclick=\"popCol($col_id,$res->pid);return false;\"><i class=\"icon-trash\"></i></a>";
		$info_text="";
		if( $submit_problem[$res2->problem_id] == true )
		{
			if( $solved_problem[$res2->problem_id] == true ) $info_text.="<span class='label label-success'>AC</span> ";
			else $info_text.="<span class='label label-important'>FAIL</span> ";
		}
		if( $res2->spj == 1 ) $info_text.="<span class='label label-info'>SPJ</span> ";
?>
				<tr>
					<td><?=$res2->problem_id?></td>
					<td><a href="/problem/<?=$res2->problem_id?>"><?=$res2->title?></a><?=$del_text?></td>
					<td><?=$info_text?></td>
					<td><?=$res2->accepted?></td>
					<td><?=$res2->submit?></td>
					<td style="text-align:center"><?=round(doubleval($res2->accepted/$res2->submit)*100,2)?>%</td>
				</tr>
<?
	}
?>
			</tbody>
		</table>
	</div>
</div>
