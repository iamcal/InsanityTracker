<?php
	include('init.php');


	$region_enc = AddSlashes($_GET['region']);

	$guilds = array();
	$ret = db_fetch("SELECT * FROM guilds WHERE region='$region_enc' AND total_got>0 ORDER BY total_got DESC LIMIT 50");
	foreach ($ret['rows'] as $row){
		$row['realm'] = local_fetch_realm($row['region'], $row['realm']);
		$guilds[] = $row;
	}

	function local_fetch_realm($region, $realm){
		$slug = AddSlashes($realm);
		return db_single(db_fetch("SELECT * FROM realms WHERE region='$region' AND slug='$slug'"));
	}

	if (!count($guilds)){
		die('region not found');
	}

	include('head.txt');
?>

<h1>
	<a href="/insanity/">Insanity</a>
	/
	<?=StrToUpper($_GET['region'])?>
</h1>

<p class="tabbar">
	Viewing: <a href="/insanity/<?=$_GET['region']?>/">Players</a> | <b>Guilds</b>
</p>

<table>
	<tr>
		<th>Realm</th>
		<th>Guild</th>
		<th>Inmates</th>
		<th><?=HtmlSpecialChars(StrToUpper($_GET['region']))?> Rank</th>
		<th>World Rank</th>
	</tr>
<? foreach ($guilds as $row){
	$region_url = $row['region'];
	$realm_url = urlencode($row['realm']['slug']);
	$guild_url = urlencode($row['name']);

	$realm = "/insanity/guilds/{$region_url}/{$realm_url}/";
	$guild = "/insanity/guilds/{$region_url}/{$realm_url}/{$guild_url}/";
?>
	<tr>
		<td><a href="<?=$realm?>"><?=HtmlSpecialChars(realm_name($row['realm']))?></a></td>
		<td><a href="<?=$guild?>"><?=HtmlSpecialChars($row['name'])?></a></td>
		<td><?=$row['total_got']?></td>
		<td><?=$row['rank_region']?></td>
		<td><?=$row['rank_world']?></td>
	</tr>
<? } ?>
</table>

<?
	include('foot.txt');
?>
