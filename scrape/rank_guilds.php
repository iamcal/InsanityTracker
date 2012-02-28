<?
	include('../init.php');


	$ret = _db_query("SELECT region, realm, `name`, total_got FROM guilds WHERE region!='kr'", 'main');
	while ($row = mysql_fetch_array($ret['result'], MYSQL_ASSOC)){

		$region = $row['region'];
		$realm = AddSlashes($row['realm']);
		$name = AddSlashes($row['name']);

		list($c1) = db_list(db_fetch("SELECT COUNT(*) FROM guilds WHERE region='$region' AND realm='$realm' AND total_got>$row[total_got]"));
		list($c2) = db_list(db_fetch("SELECT COUNT(*) FROM guilds WHERE region='$region' AND total_got>$row[total_got]"));
		list($c3) = db_list(db_fetch("SELECT COUNT(*) FROM guilds WHERE total_got>$row[total_got]"));

		db_update('guilds', array(

			'rank_realm' => 1+$c1,
			'rank_region' => 1+$c2,
			'rank_world' => 1+$c3,

		), "region='$region' AND realm='$realm' AND name='$name'");

		echo '.';
	}

	echo "\n ALL DONE\n";
