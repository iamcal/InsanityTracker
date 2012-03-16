<?
	include('../init.php');

	# for each realm, plot population and insane players
	$ret = db_fetch("SELECT * FROM realms");
	foreach ($ret['rows'] as $row){

		list($c1) = db_list(db_fetch("SELECT COUNT(*) FROM characters WHERE region='$row[region]' AND realm='$row[slug]'"));
		list($c2) = db_list(db_fetch("SELECT COUNT(*) FROM characters WHERE region='$row[region]' AND realm='$row[slug]' AND got_it=1"));

		echo "$row[region]-$row[slug], $c1, $c2\n";
	}
