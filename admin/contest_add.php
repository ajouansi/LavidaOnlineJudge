<?php

require_once("../include/const.inc.php");
if (isset($_POST['syear']))
{
        
        require_once("../include/db_info.inc.php");
        //require_once("../include/check_post_key.php");
        
        $starttime=intval($_POST['syear'])."-".intval($_POST['smonth'])."-".intval($_POST['sday'])." ".intval($_POST['shour']).":".intval($_POST['sminute']).":00";
        $endtime=intval($_POST['eyear'])."-".intval($_POST['emonth'])."-".intval($_POST['eday'])." ".intval($_POST['ehour']).":".intval($_POST['eminute']).":00";
        //      echo $starttime;
        //      echo $endtime;

        $title=$_POST['title'];
        $private=$_POST['private'];
        $contest_mode=$_POST['contest_mode'];
        if ( true ){
                $title = stripslashes ($title);
                $private = stripslashes ($private);
                $contest_mode = stripslashes ($contest_mode);
        }

        $title=mysql_real_escape_string($title);
        $private=mysql_real_escape_string($private);
        $contest_mode=mysql_real_escape_string($contest_mode);
        
    $lang=$_POST['lang'];
    $langmask=0;
    foreach($lang as $t){
                        $langmask+=1<<$t;
        } 
        $langmask=1023&(~$langmask);
        //echo $langmask;       
        
        $sql="INSERT INTO `contest`(`title`,`start_time`,`end_time`,`private`,`langmask`,`contest_mode`)
                VALUES('$title','$starttime','$endtime','$private',$langmask,'$contest_mode')";
//      echo $sql;
        mysql_query($sql) or die(mysql_error());
        $cid=mysql_insert_id();
        echo "Add Contest ".$cid;
        $sql="DELETE FROM `contest_problem` WHERE `contest_id`=$cid";
        $plist=trim($_POST['cproblem']);
        $pieces = explode(",",$plist );
        if (count($pieces)>0 && strlen($pieces[0])>0){
                $sql_1="INSERT INTO `contest_problem`(`contest_id`,`problem_id`,`num`) 
                        VALUES ('$cid','$pieces[0]',0)";
                for ($i=1;$i<count($pieces);$i++){
                        $sql_1=$sql_1.",('$cid','$pieces[$i]',$i)";
                }
                //echo $sql_1;
                mysql_query($sql_1) or die(mysql_error());
                $sql="update `problem` set defunct='N' where `problem_id` in ($plist)";
                mysql_query($sql) or die(mysql_error());
        }
        $sql="DELETE FROM `privilege` WHERE `rightstr`='c$cid'";
        mysql_query($sql);
        $sql="insert into `privilege` (`user_id`,`rightstr`)  values('".$_SESSION['user_id']."','m$cid')";
        mysql_query($sql);
        $_SESSION["m$cid"]=true;
        $pieces = explode("\n", trim($_POST['ulist']));
        if (count($pieces)>0 && strlen($pieces[0])>0){
                $sql_1="INSERT INTO `privilege`(`user_id`,`rightstr`) 
                        VALUES ('".trim($pieces[0])."','c$cid')";
                for ($i=1;$i<count($pieces);$i++)
                        $sql_1=$sql_1.",('".trim($pieces[$i])."','c$cid')";
                //echo $sql_1;
                mysql_query($sql_1) or die(mysql_error());
        }
        //echo "<script>window.location.href=\"contest_list.php\";</script>";
}
else{
   if(isset($_GET['cid'])){
                   $cid=intval($_GET['cid']);
                   $sql="select * from contest WHERE `contest_id`='$cid'";
                   $result=mysql_query($sql) or die(mysql_error());
                   $row=mysql_fetch_object($result);
                   $title=$row->title;
                   mysql_free_result($result);
                        $plist="";
                        $sql="SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`=$cid ORDER BY `num`";
                        $result=mysql_query($sql) or die(mysql_error());
                        for ($i=mysql_num_rows($result);$i>0;$i--){
                                $row=mysql_fetch_row($result);
                                $plist=$plist.$row[0];
                                if ($i>1) $plist=$plist.',';
                        }
                        mysql_free_result($result);
   }
else if(isset($_POST['problem2contest'])){
           $plist="";
           //echo $_POST['pid'];
           sort($_POST['pid']);
           foreach($_POST['pid'] as $i){                    
                        if ($plist) 
                                $plist.=','.$i;
                        else
                                $plist=$i;
           }
}else if(isset($_GET['spid'])){
        require_once("../include/check_get_key.php");
                   $spid=intval($_GET['spid']);
                 
                        $plist="";
                        $sql="SELECT `problem_id` FROM `problem` WHERE `problem_id`>=$spid ";
                        $result=mysql_query($sql) or die(mysql_error());
                        for ($i=mysql_num_rows($result);$i>0;$i--){
                                $row=mysql_fetch_row($result);
                                $plist=$plist.$row[0];
                                if ($i>1) $plist=$plist.',';
                        }
                        mysql_free_result($result);
}  
  //include_once("../fckeditor/fckeditor.php") ;
?>
        
        <form method=POST >
        <p align=center><font size=4 color=#333399>Add a Contest</font></p>
        <p align=left>Title:<input class=input-xxlarge  type=text name=title size=71 value="<?php echo isset($title)?$title:""?>"></p>
        <p align=left>Start Time:<br>&nbsp;&nbsp;&nbsp;
        Year:<input  class=input-mini type=text name=syear value=<?php echo date('Y')?> size=4 >
        Month:<input class=input-mini  type=text name=smonth value=<?php echo date('m')?> size=2 >
        Day:<input class=input-mini type=text name=sday size=2 value=<?php echo date('d')?> >&nbsp;
        Hour:<input class=input-mini    type=text name=shour size=2 value=<?php echo date('H')?>>&nbsp;
        Minute:<input class=input-mini    type=text name=sminute value=00 size=2 ></p>
        <p align=left>End Time:<br>&nbsp;&nbsp;&nbsp;
        Year:<input class=input-mini    type=text name=eyear value=<?php echo date('Y')?> size=4 >
        Month:<input class=input-mini    type=text name=emonth value=<?php echo date('m')?> size=2 >
        
        Day:<input class=input-mini  type=text name=eday size=2 value=<?php echo date('d')+(date('H')+4>23?1:0)?>>&nbsp;
        Hour:<input class=input-mini  type=text name=ehour size=2 value=<?php echo (date('H')+4)%24?>>&nbsp;
        Minute:<input class=input-mini  type=text name=eminute value=00 size=2 ></p>
        Public:<select name=private><option value=0>Public</option><option value=1>Private</option></select>
        Mode:<select name="contest_mode"><option value=0>Open</option><option value=1>Hidden</select>
	<br/>
	Language:<select name="lang[]" multiple="multiple"    style="height:220px">
        <?php
$lang_count=count($language_name);

 $langmask=$OJ_LANGMASK;

 for($i=0;$i<$lang_count;$i++){
                 echo "<option value=$i selected>
                        ".$language_name[$i]."
                 </option>";
  }

?>


        </select>
        <?php //require_once("../include/set_post_key.php");?>
        <br>Problems:<input class=input-xxlarge type=text size=60 name=cproblem value="<?php echo isset($plist)?$plist:""?>">
        <br>
        Users:<textarea name="ulist" rows="20" cols="20"></textarea>
        <br />
        <p><input type=submit value=Submit name=submit><input type=reset value=Reset name=reset></p>
        </form>
<?php }
//require_once("../oj-footer.php");

?>
