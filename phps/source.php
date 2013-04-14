<?
	if(defined('__FROM_INDEX__')==false) exit;
	if( !isset($_SESSION['user_id']) ) errorpage();
	$rid=$_GET['rid'];
	$sql="select * from solution where `solution_id`='$rid'";
	$tmp=@mysql_query($sql);
	$res=@mysql_fetch_object($tmp);
	if( $res->user_id != $_SESSION['user_id'] && !isset($_SESSION['source_browser']) ) errorpage();
	$sql="select `source` from source_code where `solution_id`='$rid'";
	$tmp=@mysql_query($sql);
	$res2=@mysql_fetch_object($tmp);
?>
<link href='/highlight/styles/shCore.css' rel='stylesheet' type='text/css'/> 
<link href='/highlight/styles/shThemeDefault.css' rel='stylesheet' type='text/css'/> 
<script src='/highlight/scripts/shCore.js' type='text/javascript'></script> 
<script src='/highlight/scripts/shBrushCpp.js' type='text/javascript'></script> 
<script src='/highlight/scripts/shBrushCSharp.js' type='text/javascript'></script> 
<script src='/highlight/scripts/shBrushCss.js' type='text/javascript'></script> 
<script src='/highlight/scripts/shBrushJava.js' type='text/javascript'></script>
<script>SyntaxHighlighter.all();</script>
<table class="table table-striped table-bordered table-hover">
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
	<tbody style="font-size:10pt">
		<tr>
			<td><?=$res->solution_id?></td>
			<td><a href="/profile/<?=$res->user_id?>"><?=$res->user_id?></a></td>
			<td><a href="/problem/<?=$res->problem_id?>"><?=$res->problem_id?></a></td>
			<td><b class="text-<?=$judge_color[$res->result]?>"><?=$judge_result[$res->result]?></b></td>
			<td><?=$res->memory?> kb</td>
			<td><?=$res->time?> ms</td>
			<td><?=$language_name[$res->language]?></td>
			<td><?=$res->code_length?> B</td>
			<td><?=$res->in_date?></td>
		</tr>
	</tbody>
</table>
<hr/>
<pre class="brush:<?=strtolower($language_name[$res->language])?>"><?=htmlspecialchars(str_replace("\n\r","\n",$res2->source))?></pre>
