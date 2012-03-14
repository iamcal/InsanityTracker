<?php

	include('init.php');

	$realms = array();
	$ret = db_fetch("SELECT region, COUNT(slug) AS num FROM realms GROUP BY region");
	foreach ($ret['rows'] as $row){
		$realms[$row['region']] = $row['num'];
	}

	$top = array();
	$top[] = db_single(db_fetch("SELECT * FROM realms WHERE region='eu' ORDER BY total_insane DESC LIMIT 1"));
	$top[] = db_single(db_fetch("SELECT * FROM realms WHERE region='us' ORDER BY total_insane DESC LIMIT 1"));
	$top[] = db_single(db_fetch("SELECT * FROM realms WHERE region='kr' ORDER BY total_insane DESC LIMIT 1"));
	$top[] = db_single(db_fetch("SELECT * FROM realms WHERE region='tw' ORDER BY total_insane DESC LIMIT 1"));

	$guilds = array();
	$ret = db_fetch("SELECT * FROM guilds WHERE rank_world>0 ORDER BY rank_world ASC LIMIT 5");
	foreach ($ret['rows'] as $row){
		$slug = AddSlashes($row['realm']);
		$row['realm'] = db_single(db_fetch("SELECT * FROM realms WHERE region='$row[region]' AND slug='$slug'"));

		$realm_url = urlencode($row['realm']['slug']);
		$guild_url = urlencode($row['name']);
		$row['url'] = "/insanity/guilds/$row[region]/$realm_url/$guild_url/";

		$guilds[] = $row;
	}

	$current = 'home';
	include('head.txt');
?>

<header class="jumbotron subhead">
	<h1>Insanity</h1>
	<p class="lead">Mumble mumble</p>
</header>

<div class="row">
<div class="span6">

	<h2>Top Realms</h2>

	<table class="table table-striped">
<? foreach ($top as $row){ ?>
		<tr>
			<td><?=StrToUpper($row['region'])?></td>
			<td><a href="/insanity/<?=$row['region']?>/<?=urlencode($row['slug'])?>/"><?=HtmlSpecialChars(realm_name($row))?></a></td>
			<td><?=$row['total_insane']?></td>
		</tr>
<? } ?>
	</table>

	<h2>Top Guilds</h2>

	<table class="table table-striped">
<? foreach ($guilds as $row){ ?>
		<tr>
			<td><?=StrToUpper($row['region'])?></td>
			<td><a href="<?=$row['url']?>"><?=HtmlSpecialChars($row['name'])?></a></td>
			<td><?=$row['total_got']?></td>
		</tr>
<? } ?>
	</table>


</div>
<div class="span6">

	<!-- div style="padding: 1em; background-color: #eee" -->
	<div class="well">

		This site tracks players who gain the achievement <a href="http://www.wowhead.com/achievement=2336">Insane in the Membrane</a>,
		arguably the hardest task in World of Warcraft. Earning the prestigious 'Insane' title takes months of dedicated play, for
		essentially no reward.

	</div>

	<h2>Realms by region</h2>

	<table class="table table-striped">
<? foreach ($realms as $k => $v){?>
		<tr>
			<td><a href="/insanity/<?=$k?>/"><?=StrToUpper($k)?></a></td>
			<td><?=$v?></td>
		</tr>
<? } ?>
	</table>

</div>
</div>

<?
	include('foot.txt');
?>
