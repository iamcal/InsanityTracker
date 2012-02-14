<?
	include('init.php');

	mb_internal_encoding("UTF-8");
	ini_set('memory_limit', '128M');


	#
	# only reload characters once per month
	#

if (0){
	echo "finding unsynced characters... ";

	$limit = time() - (60 * 60 * 24 * 30);
	$ret = db_write("UPDATE characters SET process_state=1 WHERE process_state=0 AND last_fetched<$limit");

	echo "ok ($ret[affected_rows])\n";
	flush();
}


	while (1){
		$ret = db_fetch("SELECT * FROM characters WHERE process_state=1 AND realm='hyjal' ORDER BY guild_rank ASC LIMIT 10");
		foreach ($ret['rows'] as $row){
			process_character($row);
		}
		break;
	}

	echo "\ndone\n";



	function process_character($row){

		$realm_url = str_replace("%27", "'", rawurlencode($row['realm']));
		$name_url = str_replace("%27", "'", rawurlencode($row['name']));

		$realm_enc = AddSlashes($row['realm']);
		$name_enc = AddSlashes($row['name']);
		$where = "region='$row[region]' AND realm='$realm_enc' AND name='$name_enc'";

		$ret = bnet_make_request($row['region'], "/character/$realm_url/$name_url?fields=achievements");

		if ($ret['ok']){

			$hash = array(
				'last_fetched'	=> time(),
				'fetch_count'	=> $row['fetch_count']+1,
				'process_state'	=> 0,
				'class_id'	=> intval($ret['data']['class']),
				'race_id'	=> intval($ret['data']['race']),
				'gender_id'	=> intval($ret['data']['gender']),
				'achievement_points'	=> intval($ret['data']['achievementPoints']),
				'got_it'	=> 0,
				'date_got'	=> 0,
				'last_found'	=> time(),
			);

			foreach ($ret['data']['achievements']['achievementsCompleted'] as $k => $v){

				if ($v == 2336){

					$when = $ret['data']['achievements']['achievementsCompletedTimestamp'][$k];

					$hash['got_it'] = 1;
					$hash['date_got'] = substr($when, 0, -3);
				}
			}

			db_update('characters', $hash, $where);

			if ($hash['got_it']){
				echo 'x';
			}else{
				echo '.';
			}

		}else if ($ret['req']['status'] == 404){

			db_update('characters', array(

				'last_fetched'	=> time(),
				'last_missing'	=> time(),
				'process_state'	=> 0,

			), $where);

			echo "-";

		}elseif ($ret['req']['status'] == 500 &&
			$ret['data']['status'] == 'nok' && 
			$ret['data']['reason'] == 'Internal server error.'){

			echo "(ISE)";
		}else{

			print_r($ret);
			exit;
		}
	}
