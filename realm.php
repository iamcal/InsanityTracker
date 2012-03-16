<?php
	include('init.php');

	$realm = check_realm('/insanity/REGION/REALM/');

	$region_enc = AddSlashes($_GET['region']);
	$realm_enc = AddSlashes($_GET['realm']);

	$ret = db_fetch("SELECT * FROM characters WHERE region='$region_enc' AND realm='$realm_enc' AND got_it=1 ORDER BY date_got ASC");

	$totals = array();
	foreach ($ret['rows'] as &$row){

		assign_patch($row);

		$totals['classes'][$row['class_id']]++;
		$totals['races'][$row['race_id']]++;
		$totals['factions'][$races[$row['race_id']][1]]++;
		$totals['patches'][$row['patch']]++;
	}
	unset($row);

	$title = HtmlSpecialChars(realm_name($realm));
	include('head.txt');
?>

<header class="contextual">
	<a href="/insanity/">Insanity</a> /
	<a href="/insanity/<?=$_GET['region']?>/"><?=format_region($_GET['region'])?></a> /
	<h1><?=HtmlSpecialChars(realm_name($realm))?></h1>
</header>

<ul class="nav nav-tabs">
	<li class="active"><a href="#">Players</a></li>
	<li><a href="/insanity/guilds/<?=$_GET['region']?>/<?=HtmlSpecialChars($_GET['realm'])?>/">Guilds</a></li>
</ul>

<?
	# **************************** THIS REALM HAS PLAYERS  ****************************
	if (count($ret['rows'])){
?>

<p>
	On this realm, <?=count($ret['rows'])?> players have earned <a href="http://www.wowhead.com/achievement=2336">Insane in the Membrane</a>.
</p>

<div class="well">
<div class="row-fluid">
<div class="span4">

<h4 style="margin-bottom: 10px;">By class</h4>

<table>
<? foreach ($classes as $id => $class){
	$num = intval($totals['classes'][$id]);
	$width = 180 * $num / max($totals['classes']);
	$per = 100 * $num / max($totals['classes']);
?>
	<tr>
		<td><img src="http://us.media.blizzard.com/wow/icons/18/class_<?=$id?>.jpg" width="18" height="18" alt="<?=$class[0]?>" title="<?=$class[0]?>" /></td>
		<td align="right"><?=$num?></td>
		<td><div class="progress" style="width: 180px; margin-bottom:0"><div class="bar" style="width: <?=$per?>%"></div></div></div>
	</tr>
<? } ?>
</table>

</div>
<div class="span4">

<h4 style="margin-bottom: 10px;">By race</h4>

<table>
<? foreach ($races as $id => $race){
	$num = intval($totals['races'][$id]);
	$width = 180 * $num / max($totals['races']);
	$per = 100 * $num / max($totals['races']);
?>
	<tr>
		<td><img src="http://us.media.blizzard.com/wow/icons/18/race_<?=$id?>_1.jpg" width="18" height="18" alt="<?=$race[0]?>" title="<?=$race[0]?>" /></td>
		<td align="right"><?=$num?></td>
		<td><div class="progress" style="width: 180px; margin-bottom:0"><div class="bar" style="width: <?=$per?>%"></div></div></td>
	</tr>
<? } ?>
</table>

</div>
<div class="span4">

<h4 style="margin-bottom: 10px;">By faction &amp; expansion</h4>

<table>
<? foreach ($factions as $id => $faction){
	$num = intval($totals['factions'][$id]);
	$width = 180 * $num / max($totals['factions']);
	$per = 100 * $num / max($totals['factions']);
?>
	<tr>
		<td class="ac"><img src="http://us.media.blizzard.com/wow/icons/18/<?=$faction[1]?>" width="18" height="18" alt="<?=$faction[0]?>" title="<?=$faction[0]?>" /></td>
		<td align="right"><?=$num?></td>
		<td><div class="progress" style="width: 180px; margin-bottom:0"><div class="bar" style="width: <?=$per?>%"></div></div></td>
	</tr>
<? } ?>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
<? foreach ($patches as $id => $patch){
	$num = intval($totals['patches'][$id]);
	$width = 180 * $num / max($totals['patches']);
	$per = 100 * $num / max($totals['patches']);
?>
	<tr>
		<td class="ac"><img src="/insanity/img/<?=$patch[1]?>" width="<?=$patch[2]?>" height="<?=$patch[3]?>" alt="<?=$patch[0]?>" title="<?=$patch[0]?>" /></td>
		<td align="right"><?=$num?></td>
		<td><div class="progress" style="width: 180px; margin-bottom:0"><div class="bar" style="width: <?=$per?>%"></div></div></td>
	</tr>
<? } ?>
</table>

</div>
</div>
</div>

<?
	$characters = $ret['rows'];
	include('inc_list.php');
?>

<?
	# **************************** THIS REALM HAS NO PLAYERS  ****************************
	}else{
?>

<p>Oops, looks like nobody on this realm has earned <a href="http://www.wowhead.com/achievement=2336">Insane in the Membrane</a> yet.</p>

<?
	}
?>

<?
	include('add.txt');
	include('foot.txt');
?>
