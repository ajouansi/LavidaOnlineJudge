<?
	if(defined('__FROM_INDEX__')==false) exit;
	if( !empty($_GET[user1]) && !empty($_GET[user2]))
	{
		$sql="select max(problem_id) from `problem`";
		$result=@mysql_query($sql) or die(mysql_error());
		$row=@mysql_fetch_array($result);
		$maxpid=$row[0];
		$user1_ac = Array();
		$user2_ac = Array();

		$total=0;
		$user2_win=0;
		$user1_win=0;
		$draw=0;
		
		$sql="select `problem_id` from `solution` where `user_id`='$_GET[user1]' and `result`='4' group by `problem_id`";
		$result=@mysql_query($sql) or die(mysql_error());
		while($row=mysql_fetch_array($result))
		{
			$user1_ac[$row[0]]=true;
			$total++;
		}

		$sql="select `problem_id` from `solution` where `user_id`='$_GET[user2]' and `result`='4' group by `problem_id`";
		$result=@mysql_query($sql) or die(mysql_error());
		while($row=mysql_fetch_array($result))
		{
			$user2_ac[$row[0]]=true;
			if($user1_ac[$row[0]] == false){ $user2_win++; $total++; }
			else $draw++;
		}
		$user1_win = $total-$user2_win-$draw;
?>
<center>
<h2>Dual System</h2><hr/>
	<h3><b><font style="font-size:9pt;font-weight:normal">(Win:<?=$user1_win?> Draw:<?=$draw?> Lose:<?=$user2_win?>)</font> <?=$_GET[user1]?> VS <?=$_GET[user2]?> <font style="font-size:9pt;font-weight:normal">(Win:<?=$user2_win?> Draw:<?=$draw?> Lose:<?=$user1_win?>)</font></b></h3><br>
<h4>
<?
	if( $user1_win == $user2_win ) echo "Draw !!";
	else if ($user1_win > $user2_win) echo "Winner is ",$_GET[user1]," !!!";
	else echo "Winner is ",$_GET[user2]," !!!";
?>
</h4>
<br/>
<p>
	<h4>둘다 푼 문제</h4>
	<?
		$c=0;
		for($i=1000;$i<=$maxpid;$i++)
		{
			if( $user1_ac[$i] == true && $user2_ac[$i] == true )
			{
				$c++;
				echo "<a href=\"/$i\">$i</a>&nbsp";
				if( $c%10==0) echo "<br>";
			}
		}
	?>
</p>
<hr/>
<p>
	<h4><?=$_GET[user1]?>만 풀은 문제</h4>
	<?
		$c=0;
		for($i=1000;$i<=$maxpid;$i++)
		{
			if( $user1_ac[$i] == true && $user2_ac[$i] == false )
			{
				$c++;
				echo "<a href=\"/$i\">$i</a>&nbsp";
				if( $c%10==0) echo "<br>";
			}
		}
	?>
</p>
<hr/>
<p>
	<h4><?=$_GET[user2]?>만 풀은 문제</h4>
	<?
		$c=0;
		for($i=1000;$i<=$maxpid;$i++)
		{
			if( $user1_ac[$i] == false && $user2_ac[$i] == true )
			{
				$c++;
				echo "<a href=\"/$i\">$i</a>&nbsp";
				if( $c%10==0) echo "<br>";
			}
		}
	?>
</p>
</center>
<?
	}
	else
	{
?>
<center>
	<h2>Dual System</h2>
	<form action="/dual/">
		<input type="text" name="user1"> VS <input type="text" name="user2"><br>
		<input type="submit" class="btn" value="GO">
	</form>
</center>
<?
	}
?>
