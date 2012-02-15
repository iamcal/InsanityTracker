<?php
	include('init.php');

	$realm = check_realm("/insanity/guild/REGION/REALM/$_GET[name]/");

	$region_enc = AddSlashes($_GET['region']);
	$realm_enc = AddSlashes($_GET['realm']);
	$name_enc = AddSlashes($_GET['name']);

	$ret = db_fetch("SELECT * FROM characters WHERE region='$region_enc' AND realm='$realm_enc' AND guild='$name_enc'");
	if (!count($ret['rows'])){
		die('guild not found');
	}
	$chars = $ret['rows'];
	$name = $chars[0]['guild'];

	include('head.txt');
?>

<h1>
	<a href="/insanity/">Insanity</a>
	/
	<a href="/insanity/<?=$realm['region']?>/"><?=StrToUpper($realm['region'])?></a>
	/
	<a href="/insanity/<?=$realm['region']?>/<?=$realm['slug']?>/"><?=HtmlSpecialChars(realm_name($realm))?></a>
	/
	<?=HtmlSpecialChars($name)?>
</h1>

<? dumper($chars); ?>

<?
	include('foot.txt');
?>
