<?php

function addproblem($title, $time_limit, $memory_limit, $description, $input, $output, $sample_input, $sample_output, $hint, $source, $spj,$OJ_DATA) {
	$title=mysql_real_escape_string($title);
	$time_limit=mysql_real_escape_string($time_limit);
	$memory_limit=mysql_real_escape_string($memory_limit);
	$description=mysql_real_escape_string($description);
	$input=mysql_real_escape_string($input);
	$output=mysql_real_escape_string($output);
	$sample_input=mysql_real_escape_string($sample_input);
	$sample_output=mysql_real_escape_string($sample_output);
//	$test_input=($test_input);
//	$test_output=($test_output);
	$hint=mysql_real_escape_string($hint);
	$source=mysql_real_escape_string($source);
//	$spj=($spj);
	
	$sql = "INSERT into `problem` (`title`,`time_limit`,`memory_limit`,
	`description`,`input`,`output`,`sample_input`,`sample_output`,`hint`,`source`,`spj`,`in_date`,`defunct`)
	VALUES('$title','$time_limit','$memory_limit','$description','$input','$output',
			'$sample_input','$sample_output','$hint','$source','$spj',NOW(),'Y')";
	//echo $sql;
	@mysql_query ( $sql ) or die ( mysql_error () );
	$pid = mysql_insert_id ();
	echo "<br>Add $pid  ";
	if (intval ( $_POST ['contest_id'] ) > 999) {
		$sql = "select count(*) FROM `contest_problem` WHERE `contest_id`=" . strval ( intval ( $_POST ['contest_id'] ) );
		$result = @mysql_query ( $sql ) or die ( mysql_error () );
		$row = mysql_fetch_row ( $result );
		$cid = $_POST ['contest_id'];
		$num = $row [0];
		echo "Num=" . $num . ":";
		$sql = "INSERT INTO `contest_problem` (`problem_id`,`contest_id`,`num`) VALUES('$pid','$cid','$num')";
		mysql_free_result ( $result );
		mysql_query ( $sql );
	}
	$basedir = "$OJ_DATA/$pid";
	echo "Please add more data file in $basedir";
	
	return $pid;
}
function mkdata($pid,$filename,$input,$OJ_DATA){
	
	$basedir = "$OJ_DATA/$pid";
	
	$fp = fopen ( $basedir . "/$filename", "w" );
	fputs ( $fp, preg_replace ( "(\r\n)", "\n", $input ) );
	fclose ( $fp );
	
	
	
}

?>
