<?php


	function fetch_character($region, $realm, $name, $update_guild=0){

		$realm_url = str_replace("%27", "'", rawurlencode($realm));
		$name_url = str_replace("%27", "'", rawurlencode($name));

		$row = db_single(db_fetch("SELECT * FROM characters WHERE region=:region AND realm=:realm AND name=:name", array(
			'region'	=> $region,
			'realm'		=> $realm,
			'name'		=> $name,
		)));

		$ret = bnet_make_request($region, "/character/$realm_url/$name_url?fields=achievements,guild");

		if ($ret['ok'] ?? null){

			$hash = array(
				'region'		=> $region,
				'realm'			=> $realm,
				'name'			=> $name,
				'guild'			=> $ret['data']['guild']['name'] ?? '',
				'last_fetched'		=> time(),
				'fetch_count'		=> ($row['fetch_count'] ?? 0)+1,
				'process_state'		=> 0,
				'level'			=> intval($ret['data']['level'] ?? 0),
				'class_id'		=> intval($ret['data']['class'] ?? 0),
				'race_id'		=> intval($ret['data']['race'] ?? 0),
				'gender_id'		=> intval($ret['data']['gender'] ?? 0),
				'achievement_points'	=> intval($ret['data']['achievementPoints'] ?? 0),
				'got_it'		=> 0,
				'date_got'		=> 0,
				'last_found'		=> time(),
			);

			if (!is_array($ret['data']['achievements']['achievementsCompleted'] ?? null)){
				return array(
					'ok'		=> 0,
					'error'		=> 'bad_achieves',
					'bnet'		=> $ret,
				);
			}

			foreach ($ret['data']['achievements']['achievementsCompleted'] as $k => $v){

				if ($v == 2336){

					$when = $ret['data']['achievements']['achievementsCompletedTimestamp'][$k] ?? null;

					$hash['got_it'] = 1;
					$hash['date_got'] = intval(substr((string)$when, 0, -3));
				}
			}

			db_insert_dupe('characters', $hash, $hash);

			if ($update_guild && ($ret['data']['guild']['name'] ?? null)){

				fetch_update_guild($region, $realm, $ret['data']['guild']['name']);
			}

			return array(
				'ok'		=> 1,
				'got_it'	=> $hash['got_it'],
			);
		}


		#
		# bas JSON
		#

		if ($ret['malformed'] ?? null){
			return array(
				'ok'	=> 0,
				'error'	=> 'malformed',
			);
		}


		#
		# service/character unavailable
		#

		if (($ret['req']['status'] ?? null) == 500 &&
			($ret['data']['status'] ?? null) == 'nok' &&
			($ret['data']['reason'] ?? null) == 'Character unavailable'){

			return array(
				'ok'	=> 0,
				'error'	=> 'unavailable',
			);
		}


		#
		# character not found
		#

		if (($ret['req']['status'] ?? null) == 404){

			$hash = array(
				'region'	=> $region,
				'realm'		=> $realm,
				'name'		=> $name,
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

		if (($ret['req']['status'] ?? null) == 500 &&
			($ret['data']['status'] ?? null) == 'nok' &&
			($ret['data']['reason'] ?? null) == 'Internal server error.'){

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


	function fetch_update_guild($region, $realm, $name){

		$hash = array(
			'region'	=> $region,
			'realm'		=> $realm,
			'name'		=> $name,
			'total_roster'	=> 0,
			'total_found'	=> 0,
			'total_got'	=> 0,
		);

		$ret = db_fetch("SELECT * FROM characters WHERE region=:region AND realm=:realm AND guild=:guild", array(
			'region'	=> $region,
			'realm'		=> $realm,
			'guild'		=> $name,
		));
		foreach (($ret['rows'] ?? []) as $row){

			$hash['total_roster']++;
			if ($row['last_found'] ?? null) $hash['total_found']++;
			if ($row['got_it'] ?? null) $hash['total_got']++;
		}

		db_insert_dupe('guilds', $hash, $hash);
	}

