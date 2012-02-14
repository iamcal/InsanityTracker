<?
	include('init.php');

	mb_internal_encoding("UTF-8");
	ini_set('memory_limit', '128M');



	#
	# loop over each realm, gathering some stats
	#

	$ret = db_fetch("SELECT * FROM realms");
	foreach ($ret['rows'] as $row){

		update_realm($row);
		echo '.';
	}
	echo "\ndone!\n";


	function update_realm($row){

		$slug_enc = AddSlashes($row['slug']);

		list($num) = db_list(db_fetch("SELECT COUNT(*) FROM characters WHERE region='$row[region]' AND realm='$slug_enc' AND got_it=1"));

		db_update('realms', array(
			'total_insane' => intval($num),
		), "region='$row[region]' AND slug='$slug_enc'");
	}
