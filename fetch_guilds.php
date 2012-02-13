<?
	include('init.php');

	ini_set('memory_limit', '128M');

	# we need to realm mappings
	$realm_map = array();
	$ret = db_fetch("SELECT * FROM realms");
	foreach ($ret['rows'] as $row){
		$realm_map[mb_StrToLower($row['slug'])] = $row['slug'];
		$realm_map[mb_StrToLower($row['name'])] = $row['slug'];

		if (preg_match('!-portugues$!', $row['slug'])){
			$realm_map[mb_StrToLower(substr($row['slug'], 0, -10))] = $row['slug'];
		}

		$realm_map[mb_StrToLower(str_replace('-', '', $row['slug']))] = $row['slug'];

		$realm_map[mb_StrToLower(str_replace(' ', '-', $row['name']))] = $row['slug'];
	}


	#
	# only reload guild rosters once per week
	#

	echo "finding unsynced guilds... ";

	$limit = time() - (60 * 60 * 24 * 7);
	$ret = db_write("UPDATE guilds SET needs_fetch=1 WHERE last_fetched<$limit AND last_failed<$limit");

	echo "ok ($ret[affected_rows])\n";
	flush();


	#
	# fetch some guilds to update
	#

	$row_limit = 50000;

	$ret = db_fetch("SELECT * FROM guilds WHERE needs_fetch=1 ORDER BY world_rank ASC LIMIT $row_limit");

	$failures = array();

	$num = count($ret['rows']);
	echo "fetching roster for $num guilds:\n";
	foreach ($ret['rows'] as $row){

		$slug = $realm_map[mb_strToLower($row['realm'])];
		if (!strlen($slug)) $slug = $realm_map[mb_strToLower(str_replace('-', '', $row['realm']))];

		if (strlen($slug)){

			update_guild($row, $slug);

			echo '.';
		}else{
			$failures[$row['region'].'-'.$row['realm']]++;

			$realm_enc = AddSlashes($row['realm']);
			$guild_enc = AddSlashes($row['name']);

			db_update("guilds", array(
				'last_failed' => time(),
				'needs_fetch' => 0,
			), "region='$row[region]' AND realm='$realm_enc' AND name='$guild_enc'");

		}
	}

	echo "\ndone\n";

	print_r($failures);


	function update_guild($g_row, $realm){

		$region = $g_row['region'];
		$guild = $g_row['name'];

		$guild_url = str_replace("%27", "'", rawurlencode($guild));
		$ret = bnet_make_request($region, "/guild/$realm/$guild_url?fields=members");

		#echo "\n/guild/$realm/$guild_url?fields=members ";

		if ($ret['ok']){

			$num = 0;

			foreach ($ret['data']['members'] as $row){

				$row = $row['character'];

				#if ($row['achievementPoint'] < 1000) continue;
				if ($row['level'] < 85) continue;

				db_insert_dupe('characters', array(
					'region'	=> AddSlashes($region),
					'realm'		=> AddSlashes($realm), # stub
					'name'		=> AddSlashes($row['name']),
					'guild_rank'	=> $g_row['world_rank'],
					'last_fetched'	=> 0,
				), array(
					'guild_rank'	=> $g_row['world_rank'],
				));

				$num++;
			}


			echo "($num)";
		}else{
			# guilds go missing pretty often
			if ($ret['req']['status'] == 404){

				echo "(missing)";
			}else{

				print_r($ret);
				#exit;
				return;
			}
		}

		$realm_enc = AddSlashes($g_row['realm']);
		$guild_enc = AddSlashes($g_row['name']);

		db_update("guilds", array(
			'last_fetched'	=> time(),
			'needs_fetch' => 0,
		), "region='$region' AND realm='$realm_enc' AND name='$guild_enc'");

#exit;
	}
