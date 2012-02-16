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

<p>We've scanned <?=$num_total?> players in this guild, but none of them have the achievement.</p>

<?
	include('add.txt');
	include('foot.txt');
?>
