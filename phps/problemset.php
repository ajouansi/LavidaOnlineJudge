<?
	if(defined('__FROM_INDEX__')==false) exit;

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

	$nsearch=intval($_GET['search']);
	$nowpage=0;
	$page=0;
	$pagecnt=50;
	$cond="where 1";
	if(isset($_GET['page'])){$nowpage=intval($_GET['page'])-1;$page=$nowpage*$pagecnt;}
	if( !empty($_GET['search']) ) $cond.=" and (`title` like '%$_GET[search]%' or `problem_id` like '$nsearch')";
	if(!isset($_SESSION['administrator']))$cond.=" and `defunct`='N'";
	$sql="select count(*) from `problem` $cond  order by `problem_id`";
	$tmp=@mysql_query($sql);
	$res=@mysql_fetch_row($tmp);
	$tproblem=intval($res[0]);
	$tpage=$tproblem/$pagecnt;
	if($tproblem == 1 && $nsearch <= 9999 && $nsearch >= 1000)
	{
		movePage("/problem/$nsearch");
	}
?>
<div style="text-align:center">
	<form class="form-search" action="/problemset/">
	<div class="input-append">
		<input type="text" class="span4 search-query" name="search" placeholder="Smart Problem Search" value="<?=stripslashes($_GET['search'])?>" tabindex=1>
		<button type="submit" class="btn"><i class="icon-search"></i></button>
	</div>
	</form>
</div>
<hr/>
<div class="pagination pagination-centered">
	<ul>
<?
	for($i=0;$i<=$tpage;$i++)
	{
		if( $nowpage == $i ) $nclass="active";
		else $nclass="";
?>
		<li class="<?=$nclass?>"><a href="/problemset/<?=!empty($_GET['search'])?"?search=$_GET[search]&page=".($i+1):$i+1?>"><?=$i+1?></a></li>
<?
	}
?>
	</ul>
</div>
<table class="table table-striped table-hover table-bordered">
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
	$sql="select * from `problem` $cond order by `problem_id` ASC limit $page,$pagecnt";
	$tmp=@mysql_query($sql);
	while($res=@mysql_fetch_object($tmp))
	{
		$info_text="";
		$ratio=0;
		if( !empty($_GET['search']) )
			$retitle = preg_replace("|($_GET[search])|i", "<b>$1</b>", $res->title);
		else
			$retitle=$res->title;
		if( $res->submit != 0 )
			$ratio=round(doubleval($res->accepted)/doubleval($res->submit)*100,2);
		if( $submit_problem[$res->problem_id] == true )
		{
			if( $solved_problem[$res->problem_id] == true ) $info_text.="<span class='label label-success'>AC</span> ";
			else $info_text.="<span class='label label-important'>FAIL</span> ";
		}	
		if( $res->spj == 1 ) $info_text.="<span class='label label-info'>SPJ</span> ";

		if ( $res->problem_id > 2079 ) continue; // 임시 코드
?>
		<tr>
			<td><?=$res->problem_id?></td>
			<td><a href="/problem/<?=$res->problem_id?>"><?=$retitle?></a></td>
			<td><?=$info_text?></td>
			<td><?=$res->accepted?></td>
			<td><?=$res->submit?></td>
			<td style="text-align:right"><?=$ratio?>%</td>
		</tr>
<?
	}
?>
	</tbody>
</table>
<div class="pagination pagination-centered">
    <ul>
<?
    for($i=0;$i<=$tpage;$i++)
    {
        if( $nowpage == $i ) $nclass="active";
        else $nclass="";
?>
		<li class="<?=$nclass?>"><a href="/problemset/<?=!empty($_GET['search'])?"?search=$_GET[search]&page=".($i+1):$i+1?>"><?=$i+1?></a></li>
<?
    }
?>
    </ul>
</div>

