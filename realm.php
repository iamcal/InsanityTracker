<?php
	include('init.php');

	check_realm('/insanity/REGION/REALM/');

	$region_enc = AddSlashes($_GET['region']);
	$realm_enc = AddSlashes($_GET['realm']);

	$realm = db_single(db_fetch("SELECT * FROM realms WHERE region='$region_enc' AND slug='$realm_enc'"));
	if (!$realm['region']){
		die('realm not found');
	}

	$ret = db_fetch("SELECT * FROM characters WHERE region='$region_enc' AND realm='$realm_enc' AND got_it=1 ORDER BY date_got ASC");

	$classes = array(
		1 => 'Warrior',
		2 => 'Paladin',
		3 => 'Hunter',
		4 => 'Rogue',
		5 => 'Priest',
		6 => 'Death Knight',
		7 => 'Shaman',
		8 => 'Mage',
		9 => 'Warlock',
		11 => 'Druid',
	);

	$races = array(
		1 => array('Human', 1),
		2 => array('Orc', 2),
		3 => array('Dwarf', 1),
		4 => array('Night Elf', 1),
		5 => array('Undead', 2),
		6 => array('Tauren', 2),
		7 => array('Gnome', 1),
		8 => array('Troll', 2),
		9 => array('Goblin', 2),
		10 => array('Blood Elf', 2),
		11 => array('Draenei', 1),
		22 => array('Worgen', 1),
	);

	$factions = array(
		1 => array('Alliance', 'http://wowimg.zamimg.com/images/icons/alliance.gif'),
		2 => array('Horde', 'http://wowimg.zamimg.com/images/icons/horde.gif'),
	);

	$patches = array(
		3 => array('WotLK'),
		4 => array('Cata'),
	);

	$totals = array();
	foreach ($ret['rows'] as &$row){

		$row['patch'] = 4;
		if ($row['date_got'] < mktime(0,0,0,11,23,2010)) $row['patch'] = 3;

		$totals['classes'][$row['class_id']]++;
		$totals['races'][$row['race_id']]++;
		$totals['factions'][$races[$row['race_id']][1]]++;
		$totals['patches'][$row['patch']]++;
	}

	include('head.txt');
?>

<h1>
	<a href="/insanity/">Insanity</a>
	/
	<a href="/insanity/<?=$_GET['region']?>/"><?=StrToUpper($_GET['region'])?></a>
	/
	<?=HtmlSpecialChars(realm_name($realm))?>
</h1>

<style>
.meter {
	height: 16px;
	background-color: green;
}
.listing th {
	padding: 4px;
	border-bottom: 3px solid #000;
	margin-bottom: 4px;
	text-align: left;
	font-size: 120%;
}
.listing td {
	padding: 4px;
}
.listing td.ap {
	padding-right: 10px;
	text-align: right;
}
.listing td.date {
	xtext-align: center;
}
</style>

<?
	# **************************** THIS REALM HAS PLAYERS  ****************************
	if (count($ret['rows'])){
?>

<p>
	On this realm, <?=count($ret['rows'])?> players have earned <a href="http://www.wowhead.com/achievement=2336">Insane in the Membrane</a>.
</p>

<div style="background-color: #eee; padding: 10px; margin-bottom: 2em">
<table border="0"><tr valign="top"><td>

<table>
<? foreach ($classes as $id => $class){
	$num = intval($totals['classes'][$id]);
	$width = 180 * $num / max($totals['classes']);
?>
	<tr>
		<td><img src="http://us.media.blizzard.com/wow/icons/18/class_<?=$id?>.jpg" /></td>
		<td align="right"><?=$num?></td>
		<td><div class="meter" style="width: <?=$width?>px"></div>
	</tr>
<? } ?>
</table>

</td><td>

<table>
<? foreach ($races as $id => $race){
	$num = intval($totals['races'][$id]);
	$width = 180 * $num / max($totals['races']);
?>
	<tr>
		<td><img src="http://us.media.blizzard.com/wow/icons/18/race_<?=$id?>_1.jpg" /></td>
		<td align="right"><?=$num?></td>
		<td><div class="meter" style="width: <?=$width?>px"></div>
	</tr>
<? } ?>
</table>

</td><td>

<table>
<? foreach ($factions as $id => $faction){
	$num = intval($totals['factions'][$id]);
	$width = 180 * $num / max($totals['factions']);
?>
	<tr>
		<td><img src="<?=$faction[1]?>" /></td>
		<td align="right"><?=$num?></td>
		<td><div class="meter" style="width: <?=$width?>px"></div>
	</tr>
<? } ?>
</table>

&nbsp;<br />

<table>
<? foreach ($patches as $id => $patch){
	$num = intval($totals['patches'][$id]);
	$width = 180 * $num / max($totals['patches']);
?>
	<tr>
		<td><img src="<?=$patch[1]?>" /></td>
		<td align="right"><?=$num?></td>
		<td><div class="meter" style="width: <?=$width?>px"></div>
	</tr>
<? } ?>
</table>

</td></tr></table>

</div>

<table border="0" width="100%" class="listing">
	<tr>
		<th>Character</th>
		<th>Guild</th>
		<th colspan="2">Class</th>
		<th colspan="2">Race</th>
		<th>Points</th>
		<th>Went Insane</th>
	</tr>
<? foreach ($ret['rows'] as &$row){

	$host = $GLOBALS['cfg']['bnet_region_hosts'][$realm['region']];
	$name_url = rawurlencode($row['name']);
	$guild_url = rawurlencode($row['guild']);

	$profile = "http://$host/wow/en/character/{$realm['slug']}/{$name_url}/";
	$guild = "http://$host/wow/en/guild/{$realm['slug']}/{$guild_url}/?character={$name_url}";
?>
	<tr>
		<td><a href="<?=$profile?>"><?=HtmlSpecialChars($row['name'])?></a></td>
<? if ($row['guild']){ ?>
		<td><a href="<?=$guild?>"><?=HtmlSpecialChars($row['guild'])?></a></td>
<? }else{ ?>
		<td>none</td>
<? }?>
		<td width="14"><img src="http://us.media.blizzard.com/wow/icons/18/class_<?=$row['class_id']?>.jpg" /></td>
		<td><?=$classes[$row['class_id']]?></td>
		<td width="14"><img src="http://us.media.blizzard.com/wow/icons/18/race_<?=$row['race_id']?>_<?=$row['gender_id']?>.jpg" /></td>
		<td><?=$races[$row['race_id']][0]?></td>
		<td class="ap"><?=number_format($row['achievement_points'])?></td>
		<td class="date">
			<?=date('Y-m-d', $row['date_got'])?>
			(<?=$patches[$row['patch']][0]?>)
		</td>
	</tr>
<? } ?>
</table>

<?
	# **************************** THIS REALM HAS NO PLAYERS  ****************************
	}else{
?>

<p>Oops, looks like nobody on this realm has earned <a href="http://www.wowhead.com/achievement=2336">Insane in the Membrane</a> yet.</p>

<?
	}
?>

<?
	include('foot.txt');
?>
