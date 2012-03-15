<?php
	include('head.txt');
?>

<header class="contextual">
	<a href="/insanity/">Insanity</a> /
	<a href="/insanity/<?=$realm['region']?>/"><?=format_region($realm['region'])?></a> /
	<a href="/insanity/<?=$realm['region']?>/<?=$realm['slug']?>/"><?=HtmlSpecialChars(realm_name($realm))?></a> /
	<h1><?=HtmlSpecialChars($_GET['name'])?></h1>
</header>

<p>We've never scanned any players from this guild.</p>

<?
	include('add.txt');
	include('foot.txt');
?>
