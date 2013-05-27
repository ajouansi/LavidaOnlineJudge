<?
	$user=$_GET['user'];
	$sql="select count(*) from users where user_id='$user'";
	$tmp=@mysql_query($sql);
	$res=@mysql_fetch_row($tmp);
	if( $res[0] == 0 ) exit("no such user");

	$sql="select `problem_id`,`result` from solution where user_id='$user'";
	$tmp=@mysql_query($sql);
	$solved_problem=array();
	$submit_problem=array();
	while($res=@mysql_fetch_object($tmp))
	{
		$submit_count++;
		$submit_problem[$res->problem_id]=1;
		if( $res->result == 4 )
			$solved_problem[$res->problem_id]=1;
	}
	ksort($submit_problem);
	ksort($solved_problem);
	$accept_count=count($solved_problem);

	$sql="update users set `solved`='$accept_count',`submit`='$submit_count' where `user_id`='$user'";
	$tmp=@mysql_query($sql);
?>
