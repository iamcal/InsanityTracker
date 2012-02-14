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


	#
	# loop in batches, processing some rows.
	# the batching allows multiple versions of this script to run at once.
	#

	while (1){

		#
		# clear batches over 10m old
		#

		$limit = time() -(60 * 10);
		$ret = db_write("UPDATE characters USE INDEX(process_state_4) SET process_state=1 WHERE process_state=2 AND claimed_time<$limit");
		if ($ret['affected_rows']){
			echo '|'.$ret['affected_rows'].'|';
		}else{
			echo '|';
		}


		#
		# create a new batch
		#

		$batch_size = 10;
		$batch = rand(0, 999999999);
		$t = time();

		$ret = db_fetch("SELECT * FROM characters USE INDEX(process_state_5) WHERE process_state=1 AND region='us' ORDER BY guild_rank ASC LIMIT $batch_size");
		foreach ($ret['rows'] as $row){

			$realm_enc = AddSlashes($row['realm']);
			$name_enc = AddSlashes($row['name']);

			$where = "process_state=1 AND region='$row[region]' AND realm='$realm_enc' AND name='$name_enc'";

			db_update('characters', array(
				'claimed_group' => $batch,
				'claimed_time'	=> $t,
				'process_state'	=> 2,
			), $where);
		}


		#
		# process it
		#

		$ret = db_fetch("SELECT * FROM characters USE INDEX(process_state_3) WHERE process_state=2 AND claimed_group=$batch");
		foreach ($ret['rows'] as $row){
			process_character($row);
		}
	}

	echo "\ndone\n";



	function process_character($row){

		$realm_url = str_replace("%27", "'", rawurlencode($row['realm']));
		$name_url = str_replace("%27", "'", rawurlencode($row['name']));

		$realm_enc = AddSlashes($row['realm']);
		$name_enc = AddSlashes($row['name']);
		$where = "region='$row[region]' AND realm='$realm_enc' AND name='$name_enc'";

		#echo "making bnet request...";
		$ret = bnet_make_request($row['region'], "/character/$realm_url/$name_url?fields=achievements");
		#echo "ok\n"; flush();

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
