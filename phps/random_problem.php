<?
	if(defined('__FROM_INDEX__')==false) exit;
	if( !isset($_SESSION['user_id']) ) errorpage('Login Plz~!!');

	$sql="select max(problem_id) from `problem`";
	$result=@mysql_query($sql) or die(mysql_error());
	$row=@mysql_fetch_array($result);
	$maxpid=$row[0];

	$solved=Array();
	for($i=1001;$i<=$maxpid;$i++)
	{
		$solved[$i]=false;
	}


	$sql="select `problem_id` from `solution` where `user_id`='$_SESSION[user_id]' and `result`='4' group by `problem_id`";
	$result=@mysql_query($sql) or die(mysql_error());
	while($row=mysql_fetch_array($result))
	{
		$solved[$row[0]]=true;
	}

	$notSolvedProblems=Array();
        for($i=1001;$i<=$maxpid;$i++)
        {
                if($solved[$i] == false) array_push($notSolvedProblems, $i);
        }

        $countNotSolvedProblems = count($notSolvedProblems);
        if($countNotSolvedProblems > 0)
        {
                echo "<script>location.href='/problem/".$notSolvedProblems[rand(0, count($notSolvedProblems)-1)]."'</script>";
        }
        else
        {
                echo "You solved all problems!!!";
                #echo "<script>location.href='/problem/".rand(1001, $maxpid)."'</script>";
        }
?>
