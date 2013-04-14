<?
	if( defined('__FROM_MENU__')==false ) exit;
	if( $_POST['posted'] == 'ok' )
	{
		$newTitle=htmlspecialchars($_POST[newTitle]);
		$newDesc=htmlspecialchars($_POST[newDesc]);
		$newDesc = preg_replace("|\r\n|i", "<br/>", $newDesc);
		$sql = "insert into `collections` values('','$_SESSION[user_id]','$newTitle','$newDesc','0','2')";
		$res=@mysql_query($sql);
		echo "<script>location.reload(true);</script>";
		exit;
	}
?>
<script>
	$(document).ready(function(){
		var showed=false;
		$("#addbtn").click(function(){
			if( showed == false )
			{
				$("#addarea").show('fast');
				showed=true;
			}
			else
			{
				if( $('#newTitle').val() != "" && $('#newDesc').val() != "" )
					addform.submit();
				else
					alert('error');
			}
		});
	});
	function deleteCol(collection_id)
	{
		$.get("/col_control.php",{
			mode:"delete_col",
			c:collection_id
		},function(data){
			var ret=parseInt(data);
			switch(ret)
			{
				case -1:
					alert("Failed");
					break;
				case 1:
					alert("Delete was successful");
					break;
				default:
					break;
			}
			location.reload();
		});
	}
</script>
<div class="row-fluid">
	<h1>My Collections</h1><hr/>
	<table class="table table-bordered">
		<thead style="font-size:11pt;">
			<tr>
				<th style="width:5%">ID</th>
				<th style="width:64%">Title</th>
				<th style="width:10%">Problems</th>
				<th style="width:10%">Div</th>
				<th style="widht:10%">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
<?
	$sql="select * from `collections` where user_id='$_SESSION[user_id]' order by `div` ASC,`col_id` ASC";
	$tmp=@mysql_query($sql);
	$num=0;
	while($res=@mysql_fetch_object($tmp))
	{
		$num++;
		if($res->div==1) $label="<span class=\"label label-info\">Official</span>";
		else $label="<span class=\"label label-warning\">Unofficial</span>";
?>
			<tr>
				<td><?=$num?></td>
				<td><a href="/collection/<?=$res->col_id?>"><?=$res->title?></a></td>
				<td><?=$res->cnt?></td>
				<td><?=$label?></td>
				<td style="text-align:center"><a href="#" onclick="deleteCol(<?=$res->col_id?>);return false;">Delete</a></td>
			</tr>
		</tbody>
<?
	}
?>
	</table>
	<hr />
	<div id="addarea" style="display:none">
		<h3>Add Collection</h3>
		<form class="form-horizontal row-fluid" id="addform" method="post" action="/settings/collections">
			<input type="hidden" name="posted" value="ok">
			<div class="control-group">
				<input class="span12" type="text" placeholder="Title" id="newTitle" name="newTitle">
			</div>
			<div class="control-group">
				<textarea class="span12" rows="5" placeholder="Description" id="newDesc" name="newDesc"></textarea>
			</div>
		</form>
	</div>
	<button id="addbtn" class="btn btn-primary btn-small pull-right" data-role="button">Add</button>
</div>
