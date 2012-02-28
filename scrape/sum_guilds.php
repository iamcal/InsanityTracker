<?
	include('../init.php');

	ini_set('memory_limit', '256M');


	update_region('tw');
	update_region('kr');
	update_region('eu');
	update_region('us');

	function update_region($region){

		echo "$region: "; flush();
		echo "fetching characters ... "; flush();

		$ret = _db_query("SELECT realm, guild, got_it, last_found FROM characters WHERE region='$region'", 'main');
		$num = mysql_num_rows($ret['result']);

		echo "$num players ... "; flush();

		$sums = array();

		while ($row = mysql_fetch_array($ret['result'], MYSQL_ASSOC)){
			if (!$row['guild']) continue;
			$key = $row['realm'].'|'.$row['guild'];
			$sums[$key]['total']++;
			if ($row['last_found']) $sums[$key]['found']++;
			if ($row['got_it']) $sums[$key]['got']++;
		}

		$num = count($sums);
		echo "$num guilds ... "; flush();

		foreach ($sums as $k => $nums){

			list($realm, $guild) = explode('|', $k);

			db_insert_dupe('guilds', array(
				'region'	=> $region,
				'realm'		=> AddSlashes($realm),
				'name'		=> AddSlashes($guild),
				'total_roster'	=> intval($nums['total']),
				'total_found'	=> intval($nums['found']),
				'total_got'	=> intval($nums['got']),
			), array(
				'total_roster'	=> intval($nums['total']),
				'total_found'	=> intval($nums['found']),
				'total_got'	=> intval($nums['got']),
			));
		}
	
		echo "DONE !\n";
	}
