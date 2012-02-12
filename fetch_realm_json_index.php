<?
	include('init.php');


	#
	# get all realm t13 rankings
	#

	$data = shell_exec("wget -q -O- \"http://www.wowprogress.com/export/ranks/\"");
	preg_match_all('!href="([^"]+_tier13\.json\.gz)"!', $data, $m);


	#
	# insert into DB
	#

	db_write("UPDATE realm_rankings SET in_last_run=0");
	foreach ($m[1] as $url){

		db_insert_dupe('realm_rankings', array(
			'url'		=> AddSlashes($url),
			'last_fetched'	=> 0,
			'in_last_run'	=> 1,
		), array(
			'in_last_run'	=> 1,
		));

		echo '.';
	}

	echo "\ndone\n";
