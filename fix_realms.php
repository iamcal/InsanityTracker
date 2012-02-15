<?
	include('init.php');

	mb_internal_encoding("UTF-8");
	ini_set('memory_limit', '128M');


	#
	# load mapping
	#

	$map = array();

	$ret = db_fetch("SELECT * FROM realms");
	foreach ($ret['rows'] as $row){
		$more = unserialize($row['locales']);
		if (is_array($more)){
			foreach ($more as $loc){
				if ($loc['slug'] != $row['slug']){
					$map[$loc['slug']] = array($row['region'], $row['slug']);
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

		$from_enc = AddSlashes($from);
		$to_enc = AddSlashes($to[1]);

		db_write("UPDATE guilds SET realm='$to_enc' WHERE region='$to[0]' AND realm='$from_enc'");
		echo '.'; flush();
	}
	echo " done\n";


	#
	# fix characters
	#

	echo "fixing characters ";

	foreach ($map as $from => $to){

		$from_enc = AddSlashes($from);
		$to_enc = AddSlashes($to[1]);

		db_write("UPDATE characters SET realm='$to_enc' WHERE region='$to[0]' AND realm='$from_enc'");
		echo '.'; flush();
	}
	echo " done\n";
