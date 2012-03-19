<?php
	include('init.php');


	$region_enc = AddSlashes($_GET['region']);
	$ret = db_fetch("SELECT * FROM realms WHERE region='$region_enc' ORDER BY name ASC");

	if (!count($ret['rows'])){
		$error = "Region not found";
		include('notfound.php');
	}

	$title = format_region($_GET['region']);
	include('head.txt');
?>

<header class="contextual">
	<a href="/">Insanity Tracker</a> /
	<h1><?=format_region($_GET['region'])?></h1>
</header>

<ul class="nav nav-tabs">
	<li class="active"><a href="#">Players</a></li>
	<li><a href="/guilds/<?=$_GET['region']?>/">Guilds</a></li>
</ul>

<div class="row">
<div class="span8">

<table class="table table-striped table-condensed">
	<tr>
		<th>Realm</th>
		<th class="ac">Insanes</th>
	</tr>
<? foreach ($ret['rows'] as $row){ ?>
	<tr>
		<td><a href="/<?=$row['region']?>/<?=urlencode($row['slug'])?>/"><?=HtmlSpecialChars(realm_name($row))?></a></td>
		<td class="ac"><?=$row['total_insane']?></td>
	</tr>
<? } ?>
</table>

</div>
<div class="span4">

	<div class="well">
		Most realms have at least a few players with The Insane title.
	You tend to run into them in-game quite often, since they're generally very active players.<br />
		<br />
		Pick your own realm from this list to find out which of your realm-mates have completed the grind.
	</div>

</div>
</div>

<?
	include('foot.txt');
?>
