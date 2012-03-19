<?php
	$title = HtmlSpecialChars($_GET['name']);
	include('head.txt');
?>

<header class="contextual">
	<a href="/">Insanity</a> /
	<a href="/<?=$realm['region']?>/"><?=format_region($realm['region'])?></a> /
	<a href="/<?=$realm['region']?>/<?=$realm['slug']?>/"><?=HtmlSpecialChars(realm_name($realm))?></a> /
	<h1><?=HtmlSpecialChars($_GET['name'])?></h1>
</header>

<p>We've scanned <?=$num_total?> players in this guild, but none of them have the achievement.</p>

<?
	include('add.txt');
	include('foot.txt');
?>
