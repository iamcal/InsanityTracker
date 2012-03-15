<?
	include('init.php');



	$realms = array();
	$ret = db_fetch("SELECT * FROM realms");
	foreach ($ret['rows'] as $row) $realms[$row['region'].'-'.$row['slug']] = $row;

	function realm($n){
		$row = $GLOBALS['realms'][$n];
		$realm = urlencode($row['slug']);
		return "<a href=\"/insanity/guilds/$row[region]/$realm/\">".realm_name($row)."</a>";
	}

	function guild($r, $g){
		$row = $GLOBALS['realms'][$r];
		$realm = urlencode($row['slug']);
		$guild = urlencode($g);
		return "<a href=\"/insanity/guilds/$row[region]/$realm/$guild/\">".HtmlSpecialChars($g)."</a>";
	}



	#
	# data series
	#

	$series = array (
		'2009-04' => 140,
		'2009-05' => 159,
		'2009-06' => 294,
		'2009-07' => 304,
		'2009-08' => 270,
		'2009-09' => 358,
		'2009-10' => 402,
		'2009-11' => 376,
		'2009-12' => 509,
		'2010-01' => 561,
		'2010-02' => 371,
		'2010-03' => 336,
		'2010-04' => 571,
		'2010-05' => 569,
		'2010-06' => 681,
		'2010-07' => 685,
		'2010-08' => 617,
		'2010-09' => 977,
		'2010-10' => 1563,
		'2010-11' => 1399,
		'2010-12' => 1075,
		'2011-01' => 360,
		'2011-02' => 505,
		'2011-03' => 498,
		'2011-04' => 597,
		'2011-05' => 424,
		'2011-06' => 776,
		'2011-07' => 464,
		'2011-08' => 586,
		'2011-09' => 598,
		'2011-10' => 697,
		'2011-11' => 771,
		'2011-12' => 758,
		'2012-01' => 1190,
		'2012-02' => 1856,
	);

	$max = max($series);
	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

	$data = '';
	foreach ($series as $val){
		$v = floor(61 * $val / $max);
		$data .= substr($chars, $v, 1);
	}


	#
	# google chart url
	#

	$spark_w = 740;
	$spark_h = 40;

	$params = array(
		'chs'	=> "{$spark_w}x{$spark_h}",
		'cht'	=> 'lc:nda',
		'chco'	=> '113B92',
		'chd'	=> 's:'.$data,
		'chm'	=> 'B,113B92,0,0,0',
	);

	$pairs = array();
	foreach ($params as $k => $v) $pairs[] = "$k=$v";
	$spark_url = 'http://chart.apis.google.com/chart?'.implode('&', $pairs);

	$current = 'stats';
	include('head.txt');
?>

<header class="jumbotron subhead">
	<h1>Stats</h1>
</header>

<div class="row">
<div class="span6">

<h2>Total scanned characters</h2>

<p>The scanning method is <a href="/insanity/about/">explained here</a>.</p>

<table class="table table-striped table-bordered table-condensed">
	<tr>
		<th>Region</th>
		<th>Realms</th>
		<th>Guilds scanned</th>
		<th>Characters scanned</th>
	</tr>
	<tr>
		<td>US</td>
		<td class="ar">246</td>
		<td class="ar">61,448</td>
		<td class="ar">3,293,058</td>
	</tr>
	<tr>
		<td>Europe</td>
		<td class="ar">265</td>
		<td class="ar">63,502</td>
		<td class="ar">3,397,761</td>
	</tr>
	<tr>
		<td>Taiwan</td>
		<td class="ar">46</td>
		<td class="ar">4,816</td>
		<td class="ar">587,624</td>
	</tr>
	<tr>
		<td>Korea</td>
		<td class="ar">33</td>
		<td class="ar">1,591</td>
		<td class="ar">218,563</td>
	</tr>
</table>

</div>
<div class="span6">

<h2>Biggest &amp; smallest realms</h2>

<p>By the number of scanned players.</p>

<table class="table table-striped table-bordered table-condensed">
	<tr>
		<th>Region</th>
		<th colspan="2">Smallest</th>
		<th colspan="2">Biggest</th>
	</tr>
	<tr>
		<td>US</td>
		<td><?=realm('us-tol-barad')?></td>
		<td class="ar">1,514</td>
		<td><?=realm('us-illidan')?></td>
		<td class="ar">40,924</td>
	</tr>
	<tr>
		<td>Europe</td>
		<td><?=realm('eu-veklor')?></td>
		<td class="ar">2,988</td>
		<td><?=realm('eu-ravencrest')?></td>
		<td class="ar">31,202</td>
	</tr>
	<tr>
		<td>Taiwan</td>
		<td><?=realm('tw-deathwing')?></td>
		<td class="ar">9,546</td>
		<td><?=realm('tw-bleeding-hollow')?></td>
		<td class="ar">28,155</td>
	</tr>
	<tr>
		<td>Korea</td>
		<td><?=realm('kr-blackmoore')?></td>
		<td class="ar">294</td>
		<td><?=realm('kr-durotan')?></td>
		<td class="ar">13,520</td>
	</tr>
</table>

</div>
</div>

<div class="row">
<div class="span6">

<h2>Biggest guilds (by active 85s)</h2>

<table class="table table-striped table-bordered table-condensed">
	<tr>
		<th>Region</th>
		<th>Realm</th>
		<th>Guild</th>
		<th>Players</th>
	</tr>
	<tr>
		<td>US</td>
		<td><?=realm('us-nemesis')?></td>
		<td><?=guild('us-nemesis', "\xc3\x90ream \xc3\x90ivinity")?></td>
		<td class="ar">916</td>
	</tr>
	<tr>
		<td>Europe</td>
		<td><?=realm('eu-shattered-hand')?></td>
		<td><?=guild('eu-shattered-hand', 'D A W N')?></td>
		<td class="ar">941</td>
	</tr>
	<tr>
		<td>Taiwan</td>
		<td><?=realm('tw-silverwing-hold')?></td>
		<td><?=guild('tw-silverwing-hold', "\xe5\x81\xbd\xe5\xa8\x98\xe9\xbb\x91\xe6\xbe\x80\xe6\x9c\x83")?></td>
		<td class="ar">912</td>
	</tr>
	<tr>
		<td>Korea</td>
		<td><?=realm('kr-azshara')?></td>
		<td><?=guild('kr-azshara', "\xeb\xb6\x88\xec\x96\x91")?></td>
		<td class="ar">727</td>
	</tr>	
</table>

</div>
<div class="span6">

<h2>Most insane guilds</h2>

<table class="table table-striped table-bordered table-condensed">
	<tr>
		<th>Region</th>
		<th>Realm</th>
		<th>Guild</th>
		<th>Insanes</th>
	</tr>
	<tr>
		<td>US</td>
		<td><?=realm('us-frostmane')?></td>
		<td><?=guild('us-frostmane', "War Front")?></td>
		<td class="ar">16</td>
	</tr>
	<tr>
		<td>Europe</td>
		<td><?=realm('eu-howling-fjord')?></td>
		<td><?=guild('eu-howling-fjord', "\xd0\xa1\xd0\xb8\xd0\xbd\xd0\xb5\xd1\x81\xd1\x82\xd0\xb5\xd0\xb7\xd0\xb8\xd1\x8f")?></td>
		<td class="ar">22</td>
	</tr>
	<tr>
		<td>Taiwan</td>
		<td><?=realm('tw-wrathbringer')?></td>
		<td><?=guild('tw-wrathbringer', "\xe7\xa5\x9e\xe6\xa8\xa3")?></td>
		<td class="ar">14</td>
	</tr>
	<tr>
		<td>Korea</td>
		<td><?=realm('kr-ragnaros')?></td>
		<td><?=guild('kr-ragnaros', "La Invictus")?></td>
		<td class="ar">6</td>
	</tr>
</table>

</div>
</div>


<h2>Date of achievement</h2>

<table class="table table-striped table-bordered table-condensed">
	<tr>
		<th>Patch</th>
		<th>Achievers</th>
	</tr>
	<tr>
		<td>Before it was added</td>
		<td class="ar">107</td>
	</tr>
	<tr>
		<td>Wrath of the Lich King</td>
		<td class="ar">10,930</td>
	</tr>
	<tr>
		<td>Cataclysm</td>
		<td class="ar">11,377</td>
	</tr>
</table>

<img src="<?=$spark_url?>" width="<?=$spark_w?>" width="<?=$spark_h?>" alt="Weblogs added over time" />

<?
	include('foot.txt');
?>
