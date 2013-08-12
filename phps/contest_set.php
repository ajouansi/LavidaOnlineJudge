<?
if(defined('__FROM_INDEX__')==false) exit;
$sql="SELECT * FROM `contest` WHERE `defunct`='N' ORDER BY `contest_id` DESC";
$result=mysql_query($sql);

$view_contest=Array();
$i=0;
while ($row=mysql_fetch_object($result)){

	$view_contest[$i][0]= $row->contest_id;
	$view_contest[$i][1]= "<a href='/contest/$row->contest_id'>$row->title</a>";
	$start_time=strtotime($row->start_time);
	$end_time=strtotime($row->end_time);
	$now=time();
	// past
	if ($now>$end_time) 
		$view_contest[$i][2]= "<span class='label label-success'>Ended@$row->end_time</span>";
	// pending
	else if ($now<$start_time) 
		$view_contest[$i][2]=  "<span class='label label-info'>Start@$row->start_time</span>";
	// running
	else 
		$view_contest[$i][3]= "<span class='label label-important'> Running </span>";
	$private=intval($row->private);
	if ($private==0) 
		$view_contest[$i][4]= "<span class='label label-info'>Public</span>";
	else 
		$view_contest[$i][5]= "<span class='label label-important'>Private</span>";


	$i++;
}

mysql_free_result($result);
?>
<center> 
<h2>Contest List</h2>
<br>ServerTime :<span id=nowdate class="label label-success"></span>
<br><hr>
<table class="table table-striped table-hover table-bordered">
<thead><tr class=toprow align=center><td width=10%>ID<td width=50%>Name<td width=30%>Status<td width=10%>Private</tr></thead>
<tbody>
<?  
mysql_free_result($result); 
$cnt=0;
foreach($view_contest as $row){
	if ($cnt) 
		echo "<tr class='oddrow'>";
	else
		echo "<tr class='evenrow'>";
	foreach($row as $table_cell){
		echo "<td>";
		echo "\t".$table_cell;
		echo "</td>";
	}

	echo "</tr>";

	$cnt=1-$cnt;
}
?>
</tbody>                

</table></center>

<script>
var diff=new Date("<?php echo date("Y/m/d H:i:s")?>").getTime()-new Date().getTime();
//alert(diff);
function clock()
{
	var x,h,m,s,n,xingqi,y,mon,d;
	var x = new Date(new Date().getTime()+diff);
	y = x.getYear()+1900;
	if (y>3000) y-=1900;
	mon = x.getMonth()+1;
	d = x.getDate();
	xingqi = x.getDay();
	h=x.getHours();
	m=x.getMinutes();
	s=x.getSeconds();

	n=" "+y+"-"+mon+"-"+d+" "+(h>=10?h:"0"+h)+":"+(m>=10?m:"0"+m)+":"+(s>=10?s:"0"+s);
	//alert(n);
	document.getElementById('nowdate').innerHTML=n;
	setTimeout("clock()",1000);
} 
clock();
</script>

