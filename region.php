<?php
	include('init.php');


	$region_enc = AddSlashes($_GET['region']);
	$ret = db_fetch("SELECT * FROM realms WHERE region='$region_enc' ORDER BY name ASC");

	if (!count($ret['rows'])){
		die('region not found');
	}

	include('head.txt');
?>

<header class="contextual">
	<a href="/insanity/">Insanity</a> /
	<h1><?=format_region($_GET['region'])?></h1>
</header>

<ul class="nav nav-tabs">
	<li class="active"><a href="#">Players</a></li>
	<li><a href="/insanity/guilds/<?=$_GET['region']?>/">Guilds</a></li>
</ul>

<div class="row">
<div class="span8">

<table class="table table-striped table-condensed">
	<tr>
		<th>Realm</th>
		<th>Insanes</th>
	</tr>
<? foreach ($ret['rows'] as $row){ ?>
	<tr>
		<td><a href="/insanity/<?=$row['region']?>/<?=urlencode($row['slug'])?>/"><?=HtmlSpecialChars(realm_name($row))?></a></td>
		<td><?=$row['total_insane']?></td>
	</tr>
<? } ?>
</table>

</div>
<div class="span4">

	<div class="well">
		Bleh
	</div>

</div>
</div>

<?
	include('foot.txt');
?>
