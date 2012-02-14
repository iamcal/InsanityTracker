<?php

	include('init.php');


	$region_enc = AddSlashes($_GET['region']);
	$ret = db_fetch("SELECT * FROM realms WHERE region='$region_enc' ORDER BY name ASC");

	if (!count($ret['rows'])){
		die('region not found');
	}

	include('head.txt');
?>

<h1>
	<a href="/insanity/">Insanity</a>
	/
	<?=StrToUpper($_GET['region'])?>
</h1>

<table>
<? foreach ($ret['rows'] as $row){ ?>
	<tr>
		<td><a href="/insanity/<?=$row['region']?>/<?=urlencode($row['slug'])?>/"><?=HtmlSpecialChars($row['name'])?></a></td>
		<td><?=$row['total_insane']?></td>
	</tr>
<? } ?>
</table>

<?
	include('foot.txt');
?>
