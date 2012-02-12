<?
	include('init.php');


	# only reload realm rankings once per week
	$limit = time() - (60 * 60 * 24 * 7);
	$ret = db_fetch("SELECT * FROM realm_rankings WHERE last_fetched<$limit");

	$num = count($ret['rows']);
	echo "fetching rankings for $num realms:\n";

	foreach ($ret['rows'] as $row){

		$n1 = fetch_ranks($row, $row['url'], 1);
		$n2 = fetch_ranks($row, str_replace('tier13', 'tier12', $row['url']), 0);
		#$n3 = fetch_ranks($row, str_replace('tier13', 'tier11', $row['url']), 0);
		#$n4 = fetch_ranks($row, str_replace('tier13', 'tier10_10', $row['url']), 0);
		#$n5 = fetch_ranks($row, str_replace('tier13', 'tier9_10', $row['url']), 0);
		#$n6 = fetch_ranks($row, str_replace('tier13', 'tier8', $row['url']), 0);

		$url_enc = AddSlashes($row['url']);
		db_update('realm_rankings', array(
			'last_fetched' => time(),
		), "url='$url_enc'");

		echo "($n1/$n2)";
	}

	function fetch_ranks($row, $url, $use_rank){

		$json = shell_exec("wget -q -O- \"http://www.wowprogress.com/export/ranks/$url\" | gunzip -c 2>/dev/null");
		if (!strlen($json)){
			return 'x';
		}

		$obj = JSON_decode($json, true);
		$num = 0;

		foreach ($obj as $grow){
			list($junk, $path) = explode('wowprogress.com/guild/', $grow['url']);
			$bits = explode('/', $path);
			$realm = urldecode($bits[1]);
			$region = $bits[0];

if (strlen($region) != 2){
	print_r($bits);
	print_r($grow);
	exit;
}

			$update = array( 'world_rank' => intval($grow['world_rank']) );
			if (!$use_rank) $update = array( 'name' => AddSlashes($grow['name']) );

			db_insert_dupe('guilds', array(
				'region'	=> AddSlashes($region),
				'realm'		=> AddSlashes($realm),
				'name'		=> AddSlashes($grow['name']),
				'world_rank'	=> $use_rank ? intval($grow['world_rank']) : 0,
				'last_fetched'	=> 0,
			), $update);

			$num++;
		}

		return $num;
	}

	echo "\ndone\n";
