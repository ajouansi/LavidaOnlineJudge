<?
if(defined('__FROM_INDEX__')==false) exit;

define("GIT_REPO_PATH", "/home/judge/www"); //set the path to your repo here

$repo_dir = getenv('GIT_DIR');
if (empty($repo_dir)) {
	chdir(GIT_REPO_PATH);
	$repo_dir = GIT_REPO_PATH;
}
$repo_name = basename($repo_dir);

$news_cmd = 'git log --graph --date-order --all -C -M -n 100 --date=iso --pretty=format:"B[%d] C[%H] D[%ad] A[%an] E[%ae] H[%h] S[%s]"';
ob_start();
passthru($news_cmd . ' 2>&1');
$news_o = ob_get_contents();
@ob_end_clean();

$rawRows = explode("\n", $news_o);
$graphItems = array();

foreach ($rawRows as $news_row) {
	if (preg_match("/^(.+?)(\s(B\[(.*?)\])? C\[(.+?)\] D\[(.+?)\] A\[(.+?)\] E\[(.+?)\] H\[(.+?)\] S\[(.+?)\])?$/", $news_row, $news_output)) {
		if (!isset($news_output[4])) {
			$graphItems[] = array(
				"relation"=>$news_output[1]
			);
			continue;
		}
		$graphItems[] = array(
			"relation"=>$news_output[1],
			"branch"=>$news_output[4],
			"rev"=>$news_output[5],
			"date"=>$news_output[6],
			"author"=>$news_output[7],
			"author_email"=>$news_output[8],
			"short_rev"=>$news_output[9],
			"subject"=>preg_replace('/(^|\s)(#[[:xdigit:]]+)(\s|$)/', '$1<a href="$2">$2</a>$3', $news_output[10])
			);
	}
}

$title = "Git Graph of " . $repo_name;
?>

<script type="text/javascript" src="/gitgraph/gitgraph.js"></script>
<script type="text/javascript" src="/gitgraph/draw.js"></script>
<link href="/css/gitgraph.css" rel="stylesheet" type="text/css">

<h1>News</h1>
<div class="news_row-fluid">
	<div id="header">
		<h2>
		</h2>
	</div>
	<div id="git-graph-container">
		<div id="rel-container">
			<canvas id="graph-canvas" width="100px">
			<ul id="graph-raw-list">
<?
foreach ($graphItems as $graphItem) {
	echo "<li><span class=\"node-relation\">" . $graphItem['relation'] . "</span></li>\n";
}
?>
			</ul>
			</canvas>
		</div>
		<div id="rev-container">
			<ul id="rev-list">
<?
foreach ($graphItems as $graphItem) {
	echo "<li>";
	if (isset($graphItem['rev'])) {
		echo "<code id='".$graphItem['short_rev']."'>".$graphItem['short_rev']."</code> <strong>" . $graphItem['branch'] . "</strong> <em>" . $graphItem['subject'] . "</em> by <span class=\"author\">" . $graphItem['author'] . " &lt;" . $graphItem['author_email'] . "&gt;</span>  <span class=\"time\">" . $graphItem['date'] . "</span>";
	} else {
		echo "<span />";
	}
	echo "</li>";
}
?>
				</ul>
			</div>
		</div>
	</div>
