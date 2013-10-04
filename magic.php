<?
	function array_map_r($callback,$var){
		$rarr=Array();
		foreach( $var as $key => $value ){
			$rarr[$key]=is_array($value)?array_map_r($callback,$value):$callback($value);
		}
		return $rarr;
	}
	$_GET=array_map_r("addslashes",$_GET);
	$_POST=array_map_r("addslashes",$_POST);
	$_REQUEST=array_map_r("addslashes",$_REQUEST);
?>
