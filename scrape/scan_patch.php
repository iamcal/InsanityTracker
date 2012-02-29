<?
	include('../init.php');


	$d1 = mktime(0,0,0,11,23,2010);
	$d2 = mktime(0,0,0,4,14+7,2009);

	$counts = array();
	$months = array();

	$ret = db_fetch("SELECT date_got FROM characters WHERE got_it=1");
	foreach ($ret['rows'] as $row){

		$patch = 4;
		if ($row['date_got'] < $d1) $patch = 3;
		if ($row['date_got'] < $d2) $patch = 2;
		if ($row['date_got'] == 1) $patch = 4;

		$m = date('Y-m', $row['date_got']);
		if ($row['date_got'] > 1) $months[$m]++;

		$counts[$patch]++;
	}

	print_r($counts);
	ksort($months);
	var_export($months);
?>
