<?php
	include(__DIR__.'/../include/init.php');

	# for each realm, plot population and insane players
	$ret = db_fetch("SELECT * FROM realms");
	foreach (($ret['rows'] ?? []) as $row){

		$bind = array('region' => $row['region'], 'realm' => $row['slug']);

		list($c1) = db_list(db_fetch("SELECT COUNT(*) FROM characters WHERE region=:region AND realm=:realm", $bind));
		list($c2) = db_list(db_fetch("SELECT COUNT(*) FROM characters WHERE region=:region AND realm=:realm AND got_it=1", $bind));

		echo "$row[region]-$row[slug], $c1, $c2\n";
	}
