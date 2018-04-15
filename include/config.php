<?
	$GLOBALS['cfg'] = array();

	$GLOBALS['cfg']['db_host'] = 'localhost';
	$GLOBALS['cfg']['db_user'] = 'insanitytracker';
	$GLOBALS['cfg']['db_pass'] = trim(file_get_contents(__DIR__.'/../secrets/mysql_password'));
	$GLOBALS['cfg']['db_name'] = 'insanitytracker';

	$GLOBALS['cfg']['bnet_key_public'] = trim(file_get_contents(__DIR__.'/../secrets/bnet_public'));
	$GLOBALS['cfg']['bnet_key_private'] = trim(file_get_contents(__DIR__.'/../secrets/bnet_private'));
