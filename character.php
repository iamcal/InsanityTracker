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

<? dumper($character); ?>

<?
	include('foot.txt');
?>
