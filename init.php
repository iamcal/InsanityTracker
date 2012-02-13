<?
	include('config.php');
	include('lib_db.php');

	include('lib_json.php');
	include('lib_http.php');
	include('lib_wowhead.php');
	include('lib_bnet.php');

	function log_error($msg){
		echo "ERROR: $msg\n";
		exit;
	}
