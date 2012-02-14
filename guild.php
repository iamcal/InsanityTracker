<?php

	include('init.php');

	$region_enc = AddSlashes($_GET['region']);
	$realm_enc = AddSlashes($_GET['realm']);
	$name_enc = AddSlashes($_GET['name']);

	$ret = db_fetch("SELECT * FROM characters WHERE region='$region_enc' AND realm='$realm_enc' AND guild='$name_enc'");
	if (!count($ret['rows'])){
		die('guild not found');
	}

	include('head.txt');
?>

<h1>
	<a href="/insanity/">Insanity</a>
	/
	<?=StrToUpper($character['region'])?>-<?=HtmlSpecialChars($character['realm'])?>
	<?=HtmlSpecialChars($character['name'])?>
</h1>

<? dumper($ret['rows']); ?>

<?
	include('foot.txt');
?>
