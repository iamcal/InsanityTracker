<?php
	include(__DIR__.'/../include/init.php');

	mb_internal_encoding("UTF-8");
	ini_set('memory_limit', '128M');


	#
	# loop over each realm, gathering some stats
	#

	$region = $_SERVER['argv'][1] ?? null;

	$ret = db_fetch("SELECT * FROM realms");
	foreach (($ret['rows'] ?? []) as $row){

		if ($region && $row['region'] != $region) continue;

		update_realm($row);
		echo '.';
	}
	echo "\ndone!\n";


	function update_realm($row){

		$bind = array('region' => $row['region'], 'slug' => $row['slug']);

		list($num) = db_list(db_fetch("SELECT COUNT(*) FROM characters WHERE region=:region AND realm=:slug AND got_it=1", $bind));

		db_update('realms', array(
			'total_insane' => intval($num),
		), "region=:region AND slug=:slug", $bind);
	}
