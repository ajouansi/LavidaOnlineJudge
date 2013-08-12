<?
	if(defined('__FROM_INDEX__')==false) exit;

	$sql="select distinct problem_id from solution where result='4' and user_id='$_SESSION[user_id]'";
	$tmp=@mysql_query($sql);
	$issolve=array();
	while($res=@mysql_fetch_object($tmp))
	{	
		$issolve[$res->problem_id]=true;
	}
	
 	$cid=$_GET['cid'];
 	$pid=$_GET['pid'];
	if( isset($cid) && isset($pid) ) {
		$sql = "select problem_id  from contest_problem where contest_id=$cid AND num='$pid'";
		$tmp = @mysql_query($sql);
		$res = @mysql_fetch_row($tmp);
		$pn = $res[0];
	}
	else $pn=$_GET['pn'];
	$sql="select count(*) from `problem` where `problem_id`='$pn' and `defunct`='N'";
	$tmp=@mysql_query($sql);
	$res=@mysql_fetch_row($tmp);
	$avail=$res[0];
	if( $avail < 1 ) exit("<span>No Such Problem!</span>");


	$sql="select * from `problem` where `problem_id`='$pn'";
	$tmp=@mysql_query($sql);
	$res=@mysql_fetch_object($tmp);
?>
<? if( !isset($cid) ) { ?>
<div class="row-fluid">
	<div class="span10">
		<div class="tabable">
			<ul class="nav nav-tabs">
				<li><a href="/problemset/">Problemset</a></li>
				<li class="active"><a><?=$pn?></a></li>
				<li><a href="/hof/<?=$pn?>">Hall of fame</a></li>
				<li><a href="/status/<?=$pn?>">Status</a></li>
<? } else { ?>
<div class="row-fluid">
	<div class="span10">
		<div class="tabable">
			<ul class="nav nav-tabs">
				<li><a href="/contest/<?=$cid?>">Problemset</a></li>
				<li class="active"><a><?=$pn?></a></li>
				<li><a href="/contestrank/<?=$cid?>">Standing</a></li>
				<li><a href="/conteststatus/<?=$cid?>">Status</a></li>
<? 	}
	if( isset($_SESSION['user_id']) )
	{
?>
				<li class="dropdown pull-right">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Push to Collections<b class="caret"></b></a>
					<ul class="dropdown-menu">
<?
	$sql2="select * from `collections` where `user_id`='$_SESSION[user_id]'";
	$tmp2=@mysql_query($sql2);
	while($res2=@mysql_fetch_object($tmp2))
	{
?>
						<li><a href="#" onclick="pushCol(<?=$res2->col_id?>,<?=$pn?>);return false;"><?=$res2->title?></a></li>
<?
	}
?>
						<li class="row-fluid divider"></li>
						<li><a href="/settings/collections">Configure</a></li>
					</ul>
				</li>
<?
	}
?>
			</ul>
		</div>
		<h1><?=$res->title?></h1>
		<hr/>
		<?=($res->spj==1)?infoBox('<b>Notice!</b> Submissions on this problem will be special judged'):""?>
		<h5>Description</h5>
		<p><?=$res->description?></p>
		<hr/>
		<h5>Input</h5>
		<p><?=$res->input?></p><br/>
		<h5>Output</h5>
		<p><?=$res->output?></p>
		<hr/>
		<h5>Sample Input</h5>
		<pre><?=$res->sample_input?></pre>
		<h5>Sample Output</h5>
		<pre><?=$res->sample_output?></pre>
	</div>
	<div class="span2">
		<div>
		<table class="table table-striped table-bordered" style="margin-top:5px;">
			<tbody style="font-size:9pt">
<?
	if(isset($_SESSION['user_id']))
	{
?>
				<tr>
					<td style="width:50%"><b>Status</b></td>
					<td style="text-align:center" id='status'><?=(($issolve[$res->problem_id]==true)?"<b>Solved</b>":"Unsolved")?></td>
				</tr>
<?
	}
?>
				<tr>
					<td style="width:50%"><b>Accepted</b></td>
					<td style="text-align:center"><?=$res->accepted?></td>
				</tr>
				<tr>
					<td style="width:50%"><b>Submit</b></td>
					<td style="text-align:center"><?=$res->submit?></td>
				</tr>
				<tr>
					<td style="width:50%"><b>Ratio</b></td>
					<td style="text-align:center"><?=round(doubleval($res->accepted/$res->submit)*100,2)?>%</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center"><button type="button" data-toggle="modal" id="submitButton" class="btn btn-small btn-primary">Submit <?=$res->problem_id?></button></td>
				</tr>
			</tbody>
		</table>
		</div>
	</div>
</div>
<style>
	#submit {
		width: 900px; /* SET THE WIDTH OF THE MODAL */
		margin: 0 0 0 -450px; /* CHANGE MARGINS TO ACCOMODATE THE NEW WIDTH (original = margin: -250px 0 0 -280px;) */
	}
</style>
<div id="submit" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="submitModal" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3>Submit <?=$res->problem_id?></h3>
	</div>
	<div class="modal-body">
		<form>
		<div class="control-group">
			<label class="control-label" for="language">Language</label>
			<div class="controls">
				<select id="language" name="language" onchange="codeLang()">
<?
if( isset($_SESSION['user_id']) )
{
	$sql = "SELECT language from solution where user_id = '".$_SESSION['user_id']."' ORDER BY solution_id DESC LIMIT 1";
	$sql_result = mysql_query($sql);
	$row = mysql_fetch_object($sql_result);
	$lastlang = (int)$row->language;
	$langmask=$_GET['langmask'];
	$lang=(~((int)$langmask))&127;
	$C_=($lang&1)>0;
	$CPP_=($lang&2)>0;
	$J_=($lang&8)>0;
	$Y_=($lang&64)>0;
	if($C_) echo"					<option value=0 ".( $lastlang==0?"selected":"").">C</option>\r\n";
	if($CPP_) echo"					<option value=1 ".( $lastlang==1?"selected":"").">C++</option>\r\n";
	if($J_) echo"					<option value=3 ".( $lastlang==3?"selected":"").">Java</option>\r\n";
}
?>
				</select>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<textarea id="mysource" name="mysource"></textarea>
			</div>
		</div>
		<input type="hidden" id="secretCode" value="<?=sha1('lavida_secret:::'.$res->problem_id)?>">
		<input type="hidden" id="problemID" value="<?=$res->problem_id?>">
		</form>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		<button type="button" class="btn btn-primary" id='submitFinal'>Submit</button>
	</div>
</div>
<script>
	var editor;
$.extend($.gritter.options, {
	position:'bottom-right'
});
$(document).ready(function(){
	var runid=0;
	$("#submitFinal").click(function(){
		if(runid==0){
			$.ajax({
				data:"secretCode="+$('#secretCode').val()+"&id="+$('#problemID').val()+"&language="+$('#language').val()+"&source="+encodeURIComponent(editor.getValue())<? if( isset($cid) && isset($pid) ) { echo "+\"&pid=$pid&cid=$cid\""; }?>,
				dataType:'html',
				type:'POST',
				url:'/submit.php',
				success:function(response,status,request)
				{
					runid=parseInt(response);
					if(runid>0){
						$.gritter.add({
							title:'Notification',
							text:'Successful submission (RunID:'+response+')'
						});
						var timer=setInterval(function(){
							$.ajax({
								data:"rid="+runid,
								dataType:'html',
								type:'POST',
								url:'/askresult.php',
								success:function(response,status,request)
								{
									var gtext;
									var rn=parseInt(response);
									if(rn>=4)
									{
										if( rn == 4 )
										{
											$('#status').text("Solved");
											gtext="Accepted";
										}
										else if( rn == 5 ) gtext="Presentation Error";
										else if( rn == 6 ) gtext="Wrong Answer";
										else if( rn == 7 ) gtext="Time Limit Exceeded";
										else if( rn == 8 ) gtext="Memory Limit Exceed";
										else if( rn == 9 ) gtext="Output Limit Exceed";
										else if( rn == 10 ) gtext="Runtime Error";
										else if( rn == 11 ) gtext="Compile Error";
										$.gritter.add({
											title:'Judge Response'+'(RunID:'+runid+')',
											text:gtext
										});
										runid=0;
										clearInterval(timer);
									}
								},
								error:function(request,status,error)
								{
									clearInterval(timer);
								}
							});
						}, 3000);	
					}
					else
					{
						$.gritter.add({
							title:'Error',
							text:'Error Code:'+runid
						});
						runid=0;
					}
				},
				error:function(request,status,error)
				{
					$.gritter.add({
						title:'Error',
						text:'Please contact administrator'
					});
				}
			});
		}
		else
		{
			$.gritter.add({
				title:'Error',
				text:'Please wait for your last submission being judged'
			});
		}
		$('#submit').modal('hide');
	});
	$("#submit").one("focus",function(){
		editor = CodeMirror.fromTextArea(document.getElementById("mysource"), {
			lineNumbers: true,
			matchBrackets: true,
			indentUnit: 4,
			theme: "blackboard"
		})
		codeLang();
	});

	$("#submitButton").click(function(){
<?
		if( isset($_SESSION['user_id']) )
		{
?>
		$('#submit').modal('show');
<?
		}
		else
		{
?>
		loginError();
<?
		}
?>
	});
});
function codeLang()
{
	var now=document.getElementById("language").value;
	if( now == 0 )
	{
		editor.setOption("mode","text/x-csrc");
	}
	else if( now == 1 )
	{
		editor.setOption("mode","text/x-c++src");
	}
	else if( now == 3 )
	{
		editor.setOption("mode","text/x-java");
	}
}
function loginError()
{
	$.gritter.add({
		title:'Error!',
		text:'You should login first before submit.',
	});
	return false;
}
function pushCol(collection_id,problem_id)
{
	$.get("/col_control.php",{
		mode:"push",
		pid:problem_id,
		c:collection_id
	},function(data){
		var ret=parseInt(data);
		switch(ret)
		{
			case -1:
				alert('Push failed');
				break;
			case 0:
				alert('Already pushed');
				break;
			case 1:
				alert('Push was successful');
				break;
			default:
				break;
		}
	});
}
</script>
