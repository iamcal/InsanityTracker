<?php

	include('init.php');

	$realm = check_realm("/insanity/character/REGION/REALM/$_GET[name]/");

	$region_enc = AddSlashes($_GET['region']);
	$realm_enc = AddSlashes($_GET['realm']);
	$name_enc = AddSlashes($_GET['name']);

	$character = db_single(db_fetch("SELECT * FROM characters WHERE region='$region_enc' AND realm='$realm_enc' AND name='$name_enc'"));
	if (!$character['region']){
		die('character not found');
	}

	$guild_enc = AddSlashes($character['guild']);
	$guild = db_single(db_fetch("SELECT * FROM guilds WHERE region='$character[region]' AND realm='$realm_enc' AND name='$guild_enc'"));
	if ($guild['region']){

		$realm_url = rawurlencode($guild['realm']);
		$name_url = rawurlencode($guild['name']);

		$guild['url'] = "/insanity/guilds/$guild[region]/$realm_url/$name_url/";
	}


	$host = $GLOBALS['cfg']['bnet_region_hosts'][$realm['region']];
	$realm_url = rawurlencode($character['realm']);
	$name_url = rawurlencode($character['name']);
	$armory = "http://$host/wow/en/character/{$realm_url}/{$name_url}/";

	include('head.txt');
?>

<h1>
	<a href="/insanity/">Insanity</a>
	/
	<a href="/insanity/<?=$realm['region']?>/"><?=StrToUpper($realm['region'])?></a>
	/
	<a href="/insanity/<?=$realm['region']?>/<?=$realm['slug']?>/"><?=HtmlSpecialChars(realm_name($realm))?></a>
	/
	<?=HtmlSpecialChars($character['name'])?>
</h1>

<? if ($_GET['added']){ ?>

<div class="alert alert-success">
	<i class="icon-ok"></i>
	This character has been updated from the armory.
</div>

<? } ?>


<table border="1">
	<tr>
		<td>Level</td>
		<td><?=$character['level']?></td>
	</tr>
	<tr>
		<td>Class</td>
		<td><?=$character['class_id']?></td>
	</tr>
	<tr>
		<td>Race</td>
		<td><?=$character['race_id']?></td>
	</tr>
	<tr>
		<td>Achievements</td>
		<td><?=$character['achievement_points']?></td>
	</tr>
	<tr>
		<td>Insane</td>
		<td><?=$character['got_it']?> / <?=$character['date_got']?></td>
	</tr>
	<tr>
		<td>Armory</td>
		<td><a href="<?=$armory?>">Link</a>
	</tr>
</table>

&nbsp;<br />

<div class="statbox">
<? if ($character['guild'] && $guild['total_got']){ ?>
	This player is in the guild <a href="<?=$guild['url']?>"><?=HtmlSpecialChars($character['guild'])?></a>.<br />
	<br />
	Total active: <?=$guild['total_found']?><br />
	Total insane: <?=$guild['total_got']?><br />
<? }elseif ($character['guild']){ ?>
	This player is in the guild <a href="<?=$guild['url']?>"><?=HtmlSpecialChars($character['guild'])?></a>. This guild is currently unranked.
<? }else{ ?>
	This player is not currently in a guild.
<? } ?>
</div>

<?
	include('foot.txt');
?>
