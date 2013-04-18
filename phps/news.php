<?
	if(defined('__FROM_INDEX__')==false) exit;
	require_once("/home/judge/www/magpierss/rss_fetch.inc");
	$rss=fetch_rss("http://judge.lavida.us/.news.atom");

	$comment=Array();
	foreach( $rss->items as $item ){
		$dt=substr($item['updated'],0,10);
		!is_array($comment[$dt])?$comment[$dt]=Array():false;
		array_push($comment[$dt],$item['title']);
	}
?>
	<h1>News</h1>
<?
	foreach( $comment as $key=>$value ){
?>
	<div class="row-fluid">
		<legend style="padding-top:15px"><?=$key?></legend>
		<ul>
		<?foreach($value as $ak=>$av){?>
			<li><?=$av?></li>
		<?}?>
		</ul>
	</div>
<?
	}
?>
