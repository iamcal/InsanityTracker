<?php
	include('init.php');

	$ret = db_fetch("SELECT process_state, COUNT(*) AS num FROM characters USE INDEX(process_state) GROUP BY process_state");

	$map = array();
	foreach ($ret['rows'] as $row){
		$map[$row['process_state']] = $row['num'];
		$total += $row['num'];
	}

	include('head.txt');
?>

<h1>
	Insanity - Fetcher Status
</h1>

<ul>
	<li> Pending: <?=number_format($map[1])?> </li>
	<li> Claimed: <?=number_format($map[2])?> </li>
	<li> Complete: <?=number_format($map[0])?> (<?=round(1000 * $map[0] / $total)/10?>%) </li>
</ul>

<?
	include('foot.txt');
?>
