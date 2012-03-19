<?php
	include('init.php');

	$realm = check_realm('/guilds/REGION/REALM/');

	$region_enc = AddSlashes($_GET['region']);
	$realm_enc = AddSlashes($_GET['realm']);

	$ret = db_fetch("SELECT * FROM guilds WHERE region='$region_enc' AND realm='$realm_enc' AND total_got>0 ORDER BY rank_world ASC");
	$guilds = $ret['rows'];

	$title = HtmlSpecialChars(realm_name($realm));
	include('head.txt');
?>

<header class="contextual">
	<a href="/">Insanity</a> /
	<a href="/guilds/<?=$_GET['region']?>/"><?=format_region($_GET['region'])?></a> /
	<h1><?=HtmlSpecialChars(realm_name($realm))?></h1>
</header>

<ul class="nav nav-tabs">
	<li><a href="/<?=$_GET['region']?>/<?=HtmlSpecialChars($_GET['realm'])?>/">Players</a></li>
	<li class="active"><a href="#">Guilds</a></li>
</ul>

<?
	# **************************** THIS REALM HAS GUILDS ****************************
	if (count($guilds)){
?>

<div class="row">
<div class="span9">

<table class="table table-striped table-condensed">
	<tr>
		<th>Guild</th>
		<th class="ac">Insanes</th>
		<th class="ac">Realm Rank</th>
		<th class="ac"><?=StrToUpper($_GET['region'])?> Rank</th>
		<th class="ac">World Rank</th>
	</tr>
<? foreach ($guilds as $row){

	$host = $GLOBALS['cfg']['bnet_region_hosts'][$realm['region']];
	$name_url = rawurlencode($row['name']);

	$guild = "/guilds/{$realm['region']}/{$realm['slug']}/{$name_url}/"
?>
	<tr>
		<td><a href="<?=$guild?>"><?=HtmlSpecialChars($row['name'])?></a></td>

		<td class="ac"><?=$row['total_got']?></td>
		<td class="ac"><?=$row['rank_realm']?></td>
		<td class="ac"><?=$row['rank_region']?></td>
		<td class="ac"><?=$row['rank_world']?></td>
	</tr>
<? } ?>
</table>

</div>
<div class="span3">
<div class="well">

	On this realm, <?=count($guilds)?> guilds have earned <a href="http://www.wowhead.com/achievement=2336">Insane in the Membrane</a>.

</div>
</div>
</div>


<?
	# **************************** THIS REALM HAS NO GUILDS ****************************
	}else{
?>

<p>Oops, looks like no guilds on this realm has earned <a href="http://www.wowhead.com/achievement=2336">Insane in the Membrane</a> yet.</p>

<?
	}
?>

<?
	include('foot.txt');
?>
