<?
	$GLOBALS['cfg']['db_main'] = array(
		'host'	=> 'localhost',
		'user'	=> 'www-rw',
		'pass'	=> 'pass',
		'name'	=> 'insanity',
	);

	include('lib_db.php');

	include('lib_json.php');
	include('lib_http.php');
	include('lib_wowhead.php');
	include('lib_bnet.php');
