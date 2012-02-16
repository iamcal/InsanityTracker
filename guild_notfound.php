<?php
	include('head.txt');
?>

<h1>
	<a href="/insanity/">Insanity</a>
	/
	<a href="/insanity/<?=$realm['region']?>/"><?=StrToUpper($realm['region'])?></a>
	/
	<a href="/insanity/<?=$realm['region']?>/<?=$realm['slug']?>/"><?=HtmlSpecialChars(realm_name($realm))?></a>
	/
	<?=HtmlSpecialChars($_GET['name'])?>
</h1>

<p>We've never scanned any players from this guild.</p>

<?
	include('add.txt');
	include('foot.txt');
?>
