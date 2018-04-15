<?php
	$title = HtmlSpecialChars($_GET['name']);
	include('../templates/head.txt');
?>

<header class="contextual">
	<a href="/">Insanity Tracker</a> /
	<a href="/<?=$realm['region']?>/"><?=format_region($realm['region'])?></a> /
	<a href="/<?=$realm['region']?>/<?=$realm['slug']?>/"><?=HtmlSpecialChars(realm_name($realm))?></a> /
	<h1><?=HtmlSpecialChars($_GET['name'])?></h1>
</header>

<p>We've scanned <?=$num_total?> players in this guild, but none of them have the achievement.</p>

<?
	include('../templates/add.txt');
	include('../templates/foot.txt');
?>
