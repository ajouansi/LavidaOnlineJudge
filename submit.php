<?
session_start();
require_once("include/db_info.inc.php");
if( !isset($_SESSION['user_id']) )
{
	echo "-9";
	exit(0);
}
$user_id=$_SESSION['user_id'];
if (isset($_POST['id'])) $id=intval($_POST['id']);
else exit("-1");
if( sha1('lavida_secret:::'.$id) != $_POST['secretCode'] ) exit("-1");

$cid=$_POST['cid'];
$pid=$_POST['pid'];

if( isset($cid) ) {
        $sql="SELECT `start_time`, `end_time` from `contest` where `contest_id`=$cid";
        $res=mysql_query($sql);
        if (mysql_num_rows($res)==1){
                $row=mysql_fetch_row($res);
                $start=strtotime($row[0]);
                $end=strtotime($row[1]);
                $cur=time();
		if( $cur < $start ||  $end <= $cur ){
                        $pid = NULL;
			$cid = NULL;
        	}
        }
}

$source=$_POST['source'];
$source=stripslashes($source);
$source=mysql_real_escape_string($source);
//$source=trim($source);
$len=strlen($source);
//echo $source;

$language=intval($_POST['language']);
if ($language>6 || $language<0) $language=0;
$language=strval($language);
$ip=$_SERVER['REMOTE_ADDR'];

if ($len<20){
        echo "-2";
        exit(0);
}
if ($len>65536){
        echo "-3";
        exit(0);
}

// last submit

$sql="SELECT `in_date` from `solution` where `user_id`='$user_id' order by `in_date` desc limit 1";
$res=mysql_query($sql);
if (mysql_num_rows($res)==1){
        $row=mysql_fetch_row($res);
        $last=strtotime($row[0]);
        $cur=time();
        if ($cur-$last<10){
                echo "-4";
                exit(0);
        }
}

if (!isset($pid)){
$sql="INSERT INTO solution(problem_id,user_id,in_date,language,ip,code_length)
        VALUES('$id','$user_id',NOW(),'$language','$ip','$len')";
}else{
$sql="INSERT INTO solution(problem_id,user_id,in_date,language,ip,code_length,contest_id,num)
        VALUES('$id','$user_id',NOW(),'$language','$ip','$len','$cid','$pid')";
}
mysql_query($sql);
$insert_id=mysql_insert_id();
$_SESSION['lastsid']=$insert_id;

$sql="INSERT INTO `source_code`(`solution_id`,`source`)VALUES('$insert_id','$source')";
$res=mysql_query($sql);
?><?=$insert_id?>
