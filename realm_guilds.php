<?php
	include('init.php');

	$realm = check_realm('/insanity/guilds/REGION/REALM/');

	$region_enc = AddSlashes($_GET['region']);
	$realm_enc = AddSlashes($_GET['realm']);

	$ret = db_fetch("SELECT * FROM guilds WHERE region='$region_enc' AND realm='$realm_enc' AND total_got>0 ORDER BY rank_world ASC");
	$guilds = $ret['rows'];

	include('head.txt');
?>

<h1>
	<a href="/insanity/">Insanity</a>
	/
	<a href="/insanity/guilds/<?=$_GET['region']?>/"><?=StrToUpper($_GET['region'])?></a>
	/
	<?=HtmlSpecialChars(realm_name($realm))?>
</h1>

<?
	# **************************** THIS REALM HAS GUILDS ****************************
	if (count($guilds)){
?>

<p>
	On this realm, <?=count($guilds)?> guilds have earned <a href="http://www.wowhead.com/achievement=2336">Insane in the Membrane</a>.
</p>


<table border="0" width="100%" class="listing">
	<tr>
		<th>Guild</th>
		<th>Insanes</th>
		<th>Realm</th>
		<th>Region</th>
		<th>World</th>
	</tr>
<? foreach ($guilds as $row){

	$host = $GLOBALS['cfg']['bnet_region_hosts'][$realm['region']];
	$name_url = rawurlencode($row['name']);

	$guild = "/insanity/guilds/{$realm['region']}/{$realm['slug']}/{$name_url}/"
?>
	<tr>
		<td><a href="<?=$guild?>"><?=HtmlSpecialChars($row['name'])?></a></td>

		<td><?=$row['total_got']?></td>
		<td><?=$row['rank_realm']?></td>
		<td><?=$row['rank_region']?></td>
		<td><?=$row['rank_world']?></td>
	</tr>
<? } ?>
</table>



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
