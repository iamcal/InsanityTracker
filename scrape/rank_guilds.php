<?php
	include(__DIR__.'/../include/init.php');


	# the rows stay buffered, so the loop can run its own queries as it goes
	$ret = db_query("SELECT region, realm, `name`, total_got FROM guilds WHERE region!='kr'");
	while ($row = $ret['stmt']->fetch(PDO::FETCH_ASSOC)){

		$got = intval($row['total_got']);

		list($c1) = db_list(db_fetch("SELECT COUNT(*) FROM guilds WHERE region=:region AND realm=:realm AND total_got>$got", array('region' => $row['region'], 'realm' => $row['realm'])));
		list($c2) = db_list(db_fetch("SELECT COUNT(*) FROM guilds WHERE region=:region AND total_got>$got", array('region' => $row['region'])));
		list($c3) = db_list(db_fetch("SELECT COUNT(*) FROM guilds WHERE total_got>$got"));

		db_update('guilds', array(

			'rank_realm' => 1+$c1,
			'rank_region' => 1+$c2,
			'rank_world' => 1+$c3,

		), "region=:region AND realm=:realm AND name=:name", array(
			'region'	=> $row['region'],
			'realm'		=> $row['realm'],
			'name'		=> $row['name'],
		));

		echo '.';
	}

	echo "\n ALL DONE\n";
