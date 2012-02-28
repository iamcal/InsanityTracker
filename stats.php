<?
	include('init.php');

	include('head.txt');


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
?>

<h1>Stats</h1>

<h2>Method</h2>

<p>
	To get the initial set of data, I scanned all of the guilds that were ranked in tier 8 to 13 progression on <a href="http://www.wowprogress.com/">wowprogress</a>.
	This included <i>most</i> guilds that had ever downed a raid boss, although not including amalgamated guilds,
	guilds who pug many of their raid spots or guilds who somehow slipped under the wowprogress radar.
	Because I went back to tier 8, quite a few of these guilds no longer exist.
	After generating the guild list, I fetched a roster from the Battle.net API and recorded every character who had hit 85.
	While it's possible to get the achievement at level 60, it's unlikely that somebody would have gotten the achievement
	and still be an active player without having reached 85 (and they need to be active to be on the armory).
</p>

<p>
	For each player, I then fetched their achievements and checked for Insane in the Membrane
	 This page contains some generalized statistics from this data set.
</p>


<h2>Total scanned characters</h2>

<table border="1">
	<tr>
		<th>Region</th>
		<th>Realms</th>
		<th>Guilds scanned</th>
		<th>Characters scanned</th>
	</tr>
	<tr>
		<td>US</td>
		<td>246</td>
		<td>61,448</td>
		<td>3,293,058</td>
	</tr>
	<tr>
		<td>Europe</td>
		<td>265</td>
		<td>63,502</td>
		<td>3,397,761</td>
	</tr>
	<tr>
		<td>Taiwan</td>
		<td>46</td>
		<td>4,816</td>
		<td>587,624</td>
	</tr>
	<tr>
		<td>Korea</td>
		<td>33</td>
		<td>1,591</td>
		<td>218,563</td>
	</tr>
</table>

<h2>Biggest &amp; smallest realms (by scanned players)</h2>

<table border="1">
	<tr>
		<th>Region</th>
		<th colspan="2">Smallest</th>
		<th colspan="2">Biggest</th>
	</tr>
	<tr>
		<td>US</td>
		<td><?=realm('us-tol-barad')?></td>
		<td>1,514</td>
		<td><?=realm('us-illidan')?></td>
		<td>40,924</td>
	</tr>
	<tr>
		<td>Europe</td>
		<td><?=realm('eu-veklor')?></td>
		<td>2,988</td>
		<td><?=realm('eu-ravencrest')?></td>
		<td>31,202</td>
	</tr>
	<tr>
		<td>Taiwan</td>
		<td><?=realm('tw-deathwing')?></td>
		<td>9,546</td>
		<td><?=realm('tw-bleeding-hollow')?></td>
		<td>28,155</td>
	</tr>
	<tr>
		<td>Korea</td>
		<td><?=realm('kr-blackmoore')?></td>
		<td>294</td>
		<td><?=realm('kr-durotan')?></td>
		<td>13,520</td>
	</tr>
</table>


<h2>Biggest guilds (by active 85s)</h2>

<table border="1">
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
		<td>916</td>
	</tr>
	<tr>
		<td>Europe</td>
		<td><?=realm('eu-shattered-hand')?></td>
		<td><?=guild('eu-shattered-hand', 'D A W N')?></td>
		<td>941</td>
	</tr>
	<tr>
		<td>Taiwan</td>
		<td><?=realm('tw-silverwing-hold')?></td>
		<td><?=guild('tw-silverwing-hold', "\xe5\x81\xbd\xe5\xa8\x98\xe9\xbb\x91\xe6\xbe\x80\xe6\x9c\x83")?></td>
		<td>912</td>
	</tr>
	<tr>
		<td>Korea</td>
		<td><?=realm('kr-azshara')?></td>
		<td><?=guild('kr-azshara', "\xeb\xb6\x88\xec\x96\x91")?></td>
		<td>727</td>
	</tr>	
</table>


<h2>Most insane guilds</h2>

<table border="1">
	<tr>
		<th>Region</th>
		<th>Realm</th>
		<th>Guild</th>
		<th>Insanes/th>
	</tr>
	<tr>
		<td>US</td>
		<td><?=realm('us-frostmane')?></td>
		<td><?=guild('us-frostmane', "War Front")?></td>
		<td>16</td>
	</tr>
	<tr>
		<td>Europe</td>
		<td><?=realm('eu-howling-fjord')?></td>
		<td><?=guild('eu-howling-fjord', "\xd0\xa1\xd0\xb8\xd0\xbd\xd0\xb5\xd1\x81\xd1\x82\xd0\xb5\xd0\xb7\xd0\xb8\xd1\x8f")?></td>
		<td>22</td>
	</tr>
	<tr>
		<td>Taiwan</td>
		<td><?=realm('tw-wrathbringer')?></td>
		<td><?=guild('tw-wrathbringer', "\xe7\xa5\x9e\xe6\xa8\xa3")?></td>
		<td>14</td>
	</tr>
	<tr>
		<td>Korea</td>
		<td><?=realm('kr-ragnaros')?></td>
		<td><?=guild('kr-ragnaros', "La Invictus")?></td>
		<td>6</td>
	</tr>
</table>

<?
	include('foot.txt');
?>
