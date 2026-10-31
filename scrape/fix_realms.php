<?php
	include(__DIR__.'/../include/init.php');

	mb_internal_encoding("UTF-8");
	ini_set('memory_limit', '128M');


	#
	# load mapping
	#

	$map = array();

	$ret = db_fetch("SELECT * FROM realms");
	foreach (($ret['rows'] ?? []) as $row){
		$more = unserialize($row['locales'] ?? '');
		if (is_array($more)){
			foreach ($more as $loc){
				if (($loc['slug'] ?? null) != $row['slug']){
					$map[$loc['slug'] ?? ''] = array($row['region'], $row['slug']);
				}
			}
		}
	}

	echo "found ".count($map)." slugs to map\n";


	#
	# fix guilds
	#

	echo "fixing guilds ";

	foreach ($map as $from => $to){

		db_query("UPDATE guilds SET realm=:to WHERE region=:region AND realm=:from", array('to' => $to[1], 'region' => $to[0], 'from' => $from));
		echo '.'; flush();
	}
	echo " done\n";


	#
	# fix characters
	#

	echo "fixing characters ";

	foreach ($map as $from => $to){

		db_query("UPDATE characters SET realm=:to WHERE region=:region AND realm=:from", array('to' => $to[1], 'region' => $to[0], 'from' => $from));
		echo '.'; flush();
	}
	echo " done\n";
