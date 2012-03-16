<?php
	include('init.php');

	$realm = check_realm("/insanity/guild/REGION/REALM/$_GET[name]/");

	$region_enc = AddSlashes($_GET['region']);
	$realm_enc = AddSlashes($_GET['realm']);
	$name_enc = AddSlashes($_GET['name']);

	list($num_total) = db_list(db_fetch("SELECT COUNT(*) FROM characters WHERE region='$region_enc' AND realm='$realm_enc' AND guild='$name_enc'"));

	$ret = db_fetch("SELECT * FROM characters WHERE region='$region_enc' AND realm='$realm_enc' AND guild='$name_enc' AND got_it=1 ORDER BY date_got ASC");
	if (!count($ret['rows'])){

		if ($num_total){
			include('guild_notinsane.php');
		}else{
			include('guild_notfound.php');
		}
		exit;
	}
	$chars = $ret['rows'];
	$name = $chars[0]['guild'];

	$guild = db_single(db_fetch("SELECT * FROM guilds WHERE region='$region_enc' AND realm='$realm_enc' AND name='$name_enc'"));


	$host = $GLOBALS['cfg']['bnet_region_hosts'][$guild['region']];
	$realm_url = rawurlencode($guild['realm']);
	$guild_url = rawurlencode($guild['name']);
	$armory = "http://$host/wow/en/guild/{$realm_url}/{$guild_url}/";

	foreach ($chars as $k => $v){
		assign_patch($chars[$k]);
	}

	function format_rank($i){
		if ($i == 0) return '<i>unranked</i>';
		return $i;
	}

	$title = HtmlSpecialChars($name);
	include('head.txt');
?>

<header class="contextual">
	<a href="/insanity/">Insanity</a> /
	<a href="/insanity/guilds/<?=$realm['region']?>/"><?=format_region($realm['region'])?></a> /
	<a href="/insanity/guilds/<?=$realm['region']?>/<?=$realm['slug']?>/"><?=HtmlSpecialChars(realm_name($realm))?></a> /
	<h1><?=HtmlSpecialChars($name)?></h1>
</header>

<div class="well">
<div class="row-fluid">
<div class="span6">

	<table>
		<tr>
			<td>Realm rank:</td>
			<td class="ar" style="padding-left: 20px"><a href="/insanity/guilds/<?=$realm['region']?>/<?=$realm['slug']?>/"><?=format_rank($guild['rank_realm'])?></a></td>
		</tr>
		<tr>
			<td><?=StrToUpper($realm['region'])?> rank:</td>
			<td class="ar" style="padding-left: 20px"><a href="/insanity/guilds/<?=$realm['region']?>/"><?=format_rank($guild['rank_region'])?></a></td>
		</tr>
		<tr>
			<td>World rank:</td>
			<td class="ar" style="padding-left: 20px"><?=format_rank($guild['rank_world'])?></td>
		</tr>
	</table>

</div>
<div class="span6">

<p>
	Total active:	<?=$guild['total_found']?><br />
	Insane members:	<?=$guild['total_got']?><br />
	<a href="<?=$armory?>">View on Armory</a>
</p>

</div>
</div>
</div>

<?
	$characters = $chars;
	$hide_guild = 1;
	include('inc_list.php')
?>

<?
	include('add.txt');
	include('foot.txt');
?>
