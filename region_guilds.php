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
		$error = "Region not found";
		include('notfound.php');
	}

	$title = format_region($_GET['region']);
	include('head.txt');
?>

<header class="contextual">
	<a href="/">Insanity</a> /
	<h1><?=format_region($_GET['region'])?></h1>
</header>

<ul class="nav nav-tabs">
	<li><a href="/<?=$_GET['region']?>/">Players</a></li>
	<li class="active"><a href="#">Guilds</a></li>
</ul>

<div class="row">
<div class="span9">

<table class="table table-striped table-condensed">
	<tr>
		<th>Realm</th>
		<th>Guild</th>
		<th class="ac">Insanes</th>
		<th class="ac"><?=HtmlSpecialChars(StrToUpper($_GET['region']))?> Rank</th>
		<th class="ac">World Rank</th>
	</tr>
<? foreach ($guilds as $row){
	$region_url = $row['region'];
	$realm_url = urlencode($row['realm']['slug']);
	$guild_url = urlencode($row['name']);

	$realm = "/guilds/{$region_url}/{$realm_url}/";
	$guild = "/guilds/{$region_url}/{$realm_url}/{$guild_url}/";
?>
	<tr>
		<td><a href="<?=$realm?>"><?=HtmlSpecialChars(realm_name($row['realm']))?></a></td>
		<td><a href="<?=$guild?>"><?=HtmlSpecialChars($row['name'])?></a></td>
		<td class="ac"><?=$row['total_got']?></td>
		<td class="ac"><?=$row['rank_region']?></td>
		<td class="ac"><?=$row['rank_world']?></td>
	</tr>
<? } ?>
</table>

</div>
<div class="span3">

	<div class="well">
		It's quite common for guildies to do some portions of the grind together - especially Bloodsail rep.<br />
		<br />
		Some guilds have a lot of Insanes, although most have only one.
	</div>

</div>
</div>

<?
	include('foot.txt');
?>
