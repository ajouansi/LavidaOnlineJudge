<?

  if(defined('__FROM_INDEX__')==false) exit;
        $OJ_CACHE_SHARE=true;
        $cache_time=10;
        //require_once('./include/cache_start.php');
        require_once('./include/db_info.inc.php');
        //require_once('./include/setlang.php');
        //$view_title= $MSG_CONTEST.$MSG_RANKLIST;
        $title="";
        require_once("./include/const.inc.php");
        require_once("./include/my_func.inc.php");
class TM{
        var $solved=0;
        var $time=0;
        var $p_wa_num;
        var $p_ac_sec;
        var $user_id;
        var $nick;
        function TM(){
                $this->solved=0;
                $this->time=0;
                $this->p_wa_num=array(0);
                $this->p_ac_sec=array(0);
        }
        function Add($pid,$sec,$res){
//              echo "Add $pid $sec $res<br>";
                if (isset($this->p_ac_sec[$pid])&&$this->p_ac_sec[$pid]>0)
                        return;
                if ($res!=4){
                        if(isset($this->p_wa_num[$pid])){
                                $this->p_wa_num[$pid]++;
                        }else{
                                $this->p_wa_num[$pid]=1;
                        }
                }else{
                        $this->p_ac_sec[$pid]=$sec;
                        $this->solved++;
                        if(!isset($this->p_wa_num[$pid])) $this->p_wa_num[$pid]=0;
                        $this->time+=$sec+$this->p_wa_num[$pid]*1200;
//                      echo "Time:".$this->time."<br>";
//                      echo "Solved:".$this->solved."<br>";
                }
        }
}

function s_cmp($A,$B){
//      echo "Cmp....<br>";
        if ($A->solved!=$B->solved) return $A->solved<$B->solved;
        else return $A->time>$B->time;
}

// contest start time
if (!isset($_GET['cid'])) die("No Such Contest!");
$cid=intval($_GET['cid']);

$sql="SELECT `start_time`,`title`,`end_time` FROM `contest` WHERE `contest_id`='$cid'";
//$result=mysql_query($sql) or die(mysql_error());
//$rows_cnt=mysql_num_rows($result);
if($OJ_MEMCACHE){
        require("./include/memcache.php");
        $result = mysql_query_cache($sql);// or die("Error! ".mysql_error());
        if($result) $rows_cnt=count($result);
        else $rows_cnt=0;
}else{

        $result = mysql_query($sql);// or die("Error! ".mysql_error());
        if($result) $rows_cnt=mysql_num_rows($result);
        else $rows_cnt=0;
}


$start_time=0;
$end_time=0;
if ($rows_cnt>0){
//      $row=mysql_fetch_array($result);

        if($OJ_MEMCACHE)
                $row=$result[0];
        else
                $row=mysql_fetch_array($result);
        $start_time=strtotime($row['start_time']);
        $end_time=strtotime($row['end_time']);
        $title=$row['title'];
        
}
if(!$OJ_MEMCACHE)mysql_free_result($result);
if ($start_time==0){
        $msg="hi2";
        require("/error.php");
        exit(0);
}

if ($start_time>time()){
        $msg="hi3";
        require("/error.php");
        exit(0);
}
if(!isset($OJ_RANK_LOCK_PERCENT)) $OJ_RANK_LOCK_PERCENT=0;
$lock=$end_time-($end_time-$start_time)*$OJ_RANK_LOCK_PERCENT;

//echo $lock.'-'.date("Y-m-d H:i:s",$lock);


$sql="SELECT count(1) as pbc FROM `contest_problem` WHERE `contest_id`='$cid'";
//$result=mysql_query($sql);
if($OJ_MEMCACHE){
//        require("./include/memcache.php");
        $result = mysql_query_cache($sql);// or die("Error! ".mysql_error());
        if($result) $rows_cnt=count($result);
        else $rows_cnt=0;
}else{

        $result = mysql_query($sql);// or die("Error! ".mysql_error());
        if($result) $rows_cnt=mysql_num_rows($result);
        else $rows_cnt=0;
}

if($OJ_MEMCACHE)
        $row=$result[0];
else
        $row=mysql_fetch_array($result);

//$row=mysql_fetch_array($result);
$pid_cnt=intval($row['pbc']);
if(!$OJ_MEMCACHE)mysql_free_result($result);

$sql="SELECT
        users.user_id,users.nick,solution.result,solution.num,solution.in_date
                FROM
                        (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
                left join users
                on users.user_id=solution.user_id
        ORDER BY users.user_id,in_date";
//echo $sql;
//$result=mysql_query($sql);
if($OJ_MEMCACHE){
   //     require("./include/memcache.php");
        $result = mysql_query_cache($sql);// or die("Error! ".mysql_error());
        if($result) $rows_cnt=count($result);
        else $rows_cnt=0;
}else{

        $result = mysql_query($sql);// or die("Error! ".mysql_error());
        if($result) $rows_cnt=mysql_num_rows($result);
        else $rows_cnt=0;
}

$user_cnt=0;
$user_name='';
$U=array();
for ($i=0;$i<$rows_cnt;$i++){
        if($OJ_MEMCACHE)
                $row=$result[$i];
        else
                $row=mysql_fetch_array($result);

        $n_user=$row['user_id'];
        if (strcmp($user_name,$n_user)){
                $user_cnt++;
                $U[$user_cnt]=new TM();

                $U[$user_cnt]->user_id=$row['user_id'];
                $U[$user_cnt]->nick=$row['nick'];

                $user_name=$n_user;
        }
        if(time()<$end_time&&$lock<strtotime($row['in_date']))
                   $U[$user_cnt]->Add($row['num'],strtotime($row['in_date'])-$start_time,0);
        else
                   $U[$user_cnt]->Add($row['num'],strtotime($row['in_date'])-$start_time,intval($row['result']));
       
}
if(!$OJ_MEMCACHE) mysql_free_result($result);
usort($U,"s_cmp");

////firstblood
$first_blood=array();
for($i=0;$i<$pid_cnt;$i++){
   $sql="select user_id from solution where contest_id=$cid and result=4 and num=$i order by in_date limit 1";
   $result=mysql_query($sql);
   $row_cnt=mysql_num_rows($result);
   $row=mysql_fetch_array($result);
   if($row_cnt==1){
      $first_blood[$i]=$row['user_id'];
   }else{
      $first_blood[$i]="";
   }

}


/////////////////////////Template
//require("template/".$OJ_TEMPLATE."/contestrank.php");
/////////////////////////Common foot
//if(file_exists('./include/cache_end.php'))
        //require_once('./include/cache_end.php');
?>
<script type="text/javascript" src="include/jquery.tablesorter.js"></script> 
<script type="text/javascript">
$(document).ready(function() 
    { 

 $.tablesorter.addParser({ 
        // set a unique id 
        id: 'punish', 
        is: function(s) { 
            // return false so this parser is not auto detected 
            return false; 
        }, 
        format: function(s) { 
            // format your data for normalization 
            var v=s.toLowerCase().replace(/\:/,'').replace(/\:/,'').replace(/\(-/,'.').replace(/\)/,''); 
            //alert(v);
            v=parseFloat('0'+v);
            return v>1?v:v+Number.MAX_VALUE-1;
        }, 
        // set type, either numeric or text 
        type: 'numeric' 
    }); 

        $("#rank").tablesorter({ 
            headers: { 
                4: { 
                    sorter:'punish' 
                }
                
<?php
for ($i=0;$i<$pid_cnt;$i++){
                echo ",".($i+5).": { ";
                echo "    sorter:'punish' ";
                echo "}";
}
?>
            } 
        }); 
    } 
); 
</script>
<div id="wrapper">
<div id=main>
<div class="row-fluid">
    <div class="span10">
        <div class="tabable">
            <ul class="nav nav-tabs">
                <li> <a> Contest <?=$cid?> </a> </li>
                <li> <a href="/contest/<?=$cid?>">Problemset</a></li>
                <li class="active"><a>Standing</a></li>
                <li><a href="/conteststatus/<?=$cid?>">Status</a></li>
            </ul>
        </div>
    </div>
</div>
<?php
$rank=1;
?>
<center>
<h3>Contest RankList -- <?php echo $title?></h3>
<table id=rank class="table table-bordered table-striped table-hover">
<thead><tr class=toprow align=center><td width=5%>Rank<th width=10%>User<th width=5%>Solved<th width=5%>Penalty
<?php
for ($i=0;$i<$pid_cnt;$i++)
        echo "<th><a href=/problem/$cid&$i>$PID[$i]</a>";
     echo "</tr></thead>\n<tbody>";
for ($i=0;$i<$user_cnt;$i++){
        if ($i&1) echo "<tr class=oddrow align=center>\n";
        else echo "<tr class=evenrow align=center>\n";
        echo "<td>";
        $uuid=$U[$i]->user_id;
  $nick=$U[$i]->nick;
  if($nick[0]!="*")
        echo $rank++;
  else 
        echo "*";
      
        $usolved=$U[$i]->solved;
  if($uuid==$_GET['user_id']) echo "<td bgcolor=#ffff77>";
  else echo"<td>";
        echo "<a name=\"$uuid\" href=/profile/$uuid>$uuid</a>";
        echo "<td><a href=status.php?user_id=$uuid&cid=$cid>$usolved</a>";
        echo "<td>".sec2str($U[$i]->time);
        for ($j=0;$j<$pid_cnt;$j++){
		$AC = 0;
                if(isset($U[$i])){
                        if (isset($U[$i]->p_ac_sec[$j])&&$U[$i]->p_ac_sec[$j]>0) 
							echo "<td style=\"font-weight:bold;color:#558543\">";
                        else if (isset($U[$i]->p_wa_num[$j])&&$U[$i]->p_wa_num[$j]>0) 
                       		echo "<td style=\"font-weight:bold;color:#d36969\">";
                        else 
                        	echo "<td>";
                }
                if(isset($U[$i])){
                    if (isset($U[$i]->p_ac_sec[$j])&&$U[$i]->p_ac_sec[$j]>0){
                        echo sec2str($U[$i]->p_ac_sec[$j])." ";
						if (isset($U[$i]->p_wa_num[$j])&&$U[$i]->p_wa_num[$j]>0) {
							echo "(-".$U[$i]->p_wa_num[$j].")";
						}
					}
					else if (isset($U[$i]->p_wa_num[$j])&&$U[$i]->p_wa_num[$j]>0) {
						echo "(-".$U[$i]->p_wa_num[$j].")";
					}else{
						echo "-";
					}
				}
        }
        echo "</tr>\n";
}
     echo "</tbody></table>";
?>
</center>
