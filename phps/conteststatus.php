<?
if(defined('__FROM_INDEX__')==false) exit;

$last=0;
if(!empty($_GET['last']))$last=intval($_GET['last']);

$s_cid=$_GET['cid'];
if( !isset($s_cid) ) {
		$msg = "no search contest!";
			require("error.php");
			exit(0);
}
$s_res=$_GET['jresult'];
$s_pid=$_GET['problem_id'];
$s_pflag=$_GET['pflag'];
$contesting = 0;
if( isset($_SESSION['administrator']) == false ) {
	$sql="SELECT `end_time`, `contest_mode` from `contest` where `contest_id`='$s_cid'";
	$ret=mysql_query($sql);
	if(mysql_num_rows($ret)==1){
	  $row=mysql_fetch_row($ret);
		$end=strtotime($row[0]);
		$cur=time();
		if( $cur <= $end && $row[1] == 1 )$contesting = 1;
	}
}
?>
<div class="row-fluid">
<div class="span10">
<div class="tabable">
<ul class="nav nav-tabs">
<li> <a> Contest <?=$s_cid?> </a> </li>
<li><a href="/contest/<?=$s_cid?>">Problemset</a></li>
<li><a href="/contestrank/<?=$s_cid?>">Standing</a></li>
<li class="active"><a>Status</a></li>
</ul>
</div>
</div>
</div>
<div style="text-align:center">
<form class="form-inline" action="/conteststatus/">
<input type="hidden"name="pflag" value='<?=$s_pflag?>'>
<input class="input-small" type="text" name="problem_id" placeholder="Problem" value='<?=$s_pid?>'>
<input class="input-medium" type="text" name="user" placeholder="User" value='<?=$s_user?>'>
<select name="jresult">
<option value=""<?=empty($s_res)?'':' selected'?>>All</option>
<?
$opval=array('4','6','7','5','8','9','10','11','0','1','2','3');
for($i=0;$i<12;$i++)
{
		?>
		<option value="<?=$opval[$i]?>"<?=($_GET[jresult]==$opval[$i])?' selected':''?>><?=$judge_result[$opval[$i]]?></option>
		<?
}
?>
</select>
<button type="submit" class="btn">Search</button>
</form>
</div>
<table class="table table-striped table-hover table-bordered">
<thead style="font-size:11pt">
<tr>
<th style="width:8%">Run ID</th>
<th style="width:10%">User</th>
<th style="width:6%">Problem</th>
<th style="width:17%">Result</th>
<th style="width:10%">Memory</th>
<th style="width:8%">Time</th>
<th style="width:6%">Language</th>
<th style="width:10%">Code Length</th>
<th style="width:17%">Submit Time</th>
</tr>
</thead>
<tbody style="font-size:10pt;">
<?
if( empty($_GET['jresult']) ) $s_res='%';
if( empty($_GET['problem_id']) ) $s_pid='%';
$s_user='%';
if( $contesting == 1 ) {
	$s_user=$_SESSION['user_id'];
	if( !isset($s_user) ) $s_user="sexyguy";
}
$sql="SELECT * FROM `solution` where `contest_id`=$s_cid and `result` like '$s_res' and `user_id` like '$s_user' and `problem_id` like '$s_pid' order by `solution_id` DESC limit $last,20";
if($_GET['test']==1)echo$sql;
$tmp=@mysql_query($sql);
while($res=@mysql_fetch_object($tmp))
{
		if(!isset($bottom)) $bottom=$res->solution_id;
			?>
		<tr>
		<td><?=$res->solution_id?></td>
		<td><a href="/profile/<?=$res->user_id?>"><?=$res->user_id?></a></td>
		<td><a href="/problem/<?=$res->contest_id?>&<?=$res->num?>"><?=$res->problem_id?></a></td>
		<td><b class="text-<?=$judge_color[$res->result]?>"><?=$judge_result[$res->result]?></b></td>
		<td><?=$res->memory?> kb</td>
		<td><?=$res->time?> ms</td>
		<?
		if($_SESSION['user_id']==$res->user_id || isset($_SESSION['source_browser']))
		{
			?>
				<td><a href="/source/<?=$res->solution_id?>" target=_blank><?=$language_name[$res->language]?></a></td>
				<?
		}
		else
		{
			?>
				<td><?=$language_name[$res->language]?></td>
				<?
		}
	?>
		<td><?=$res->code_length?> B</td>
		<td><?=$res->in_date?></td>
		</tr>
		<?
}
$top=$last+20;
$bottom=$last-20;

$s_res=$_GET['jresult'];
$s_pid=$_GET['problem_id'];
$s_user=$_GET['user'];
$qsa="";
if( !empty($s_cid) ) $qsa.="cid=$s_cid";
if( !empty($s_pflag) ) $qsa.="&pflag=$s_pflag";
if( !empty($s_pid) ) $qsa.="&problem_id=$s_pid";
if( !empty($s_user) ) $qsa.="&user=$s_user";
if( !empty($s_res) ) $qsa.="&jresult=$s_res";

$qsa_b=$qsa."&last=$bottom";
$qsa_t=$qsa."&last=$top";
?>
</tbody>
</table>
<div class="row">
<div class="span2 pull-right">
<a href="/conteststatus/?<?=$qsa_b?>">[Prev]</a>&nbsp;<a href="/conteststatus/?<?=$qsa_t?>">[Next]</a>
</div>
</div>
