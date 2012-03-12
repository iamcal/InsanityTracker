<?


	function fetch_character($region, $realm, $name){

		$realm_url = str_replace("%27", "'", rawurlencode($realm));
		$name_url = str_replace("%27", "'", rawurlencode($name));

		$region_enc = AddSlashes($region);
		$realm_enc = AddSlashes($realm);
		$name_enc = AddSlashes($name);
		$where = "region='$region_enc' AND realm='$realm_enc' AND name='$name_enc'";
		$row = db_single(db_fetch("SELECT * FROM characters WHERE $where"));

		$ret = bnet_make_request($region, "/character/$realm_url/$name_url?fields=achievements,guild");

		if ($ret['ok']){

			$hash = array(
				'region'		=> AddSlashes($region),
				'realm'			=> AddSlashes($realm),
				'name'			=> AddSlashes($name),
				'guild'			=> AddSlashes($ret['data']['guild']['name']),
				'last_fetched'		=> time(),
				'fetch_count'		=> $row['fetch_count']+1,
				'process_state'		=> 0,
				'class_id'		=> intval($ret['data']['class']),
				'race_id'		=> intval($ret['data']['race']),
				'gender_id'		=> intval($ret['data']['gender']),
				'achievement_points'	=> intval($ret['data']['achievementPoints']),
				'got_it'		=> 0,
				'date_got'		=> 0,
				'last_found'		=> time(),
			);

			if (!is_array($ret['data']['achievements']['achievementsCompleted'])){
				return array(
					'ok'		=> 0,
					'error'		=> 'bad_achieves',
					'bnet'		=> $ret,
				);
			}

			foreach ($ret['data']['achievements']['achievementsCompleted'] as $k => $v){

				if ($v == 2336){

					$when = $ret['data']['achievements']['achievementsCompletedTimestamp'][$k];

					$hash['got_it'] = 1;
					$hash['date_got'] = substr($when, 0, -3);
				}
			}

			db_insert_dupe('characters', $hash, $hash);


			return array(
				'ok'		=> 1,
				'got_it'	=> $hash['got_it'],
			);
		}


		#
		# bas JSON
		#

		if ($ret['malformed']){
			return array(
				'ok'	=> 0,
				'error'	=> 'malformed',
			);
		}


		#
		# service/character unavailable
		#

		if ($ret['req']['status'] == 500 &&
			$ret['data']['status'] == 'nok' && 
			$ret['data']['reason'] == 'Character unavailable'){

			return array(
				'ok'	=> 0,
				'error'	=> 'unavailable',
			);
		}


		#
		# character not found
		#

		if ($ret['req']['status'] == 404){

			$hash = array(
				'region'	=> AddSlashes($region),
				'realm'		=> AddSlashes($realm),
				'name'		=> AddSlashes($name),
				'last_fetched'	=> time(),
				'last_missing'	=> time(),
				'process_state'	=> 0,
			);

			db_insert_dupe('characters', $hash, $hash);

			return array(
				'ok'	=> 0,
				'error'	=> 'not_found',
			);
		}


		#
		# service down
		#

		if ($ret['req']['status'] == 500 &&
			$ret['data']['status'] == 'nok' && 
			$ret['data']['reason'] == 'Internal server error.'){

			return array(
				'ok'	=> 0,
				'error'	=> 'ise',
			);
		}


		#
		# uh?
		#

		return array(
			'ok'	=> 0,
			'error'	=> 'unknown',
			'bnet'	=> $ret,
		);
	}
