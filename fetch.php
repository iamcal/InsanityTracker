<?
	include('lib_json.php');
	include('lib_http.php');
	include('lib_wowhead.php');
	include('lib_bnet.php');

	mysql_connect('localhost','www-rw','pass');
	mysql_select_db('insanity');


	function fetch_user($region, $realm_slug, $name){

		$name_stub = mb_strtolower($name);
		$name_stub = str_replace("%27", "'", rawurlencode($name_stub));

		$ret = bnet_make_request($region, "/character/{$realm_slug}/{$name_stub}?fields=achievements");

		if ($ret['ok']){

			foreach ($ret['data']['achievements']['achievementsCompleted'] as $k => $v){

				if ($v == 2336){

					$when = $ret['data']['achievements']['achievementsCompletedTimestamp'][$k];
					$date = date('Y-m-d H:i:s', substr($when, 0, -3));

					update_db($region, $realm_slug, $name, 1, $date);
					echo "got it on $when\n";
					return;
				}
			}
		}

		update_db($region, $realm_slug, $name, 0);
#print_r($ret);
	}

	function update_db($region, $realm_slug, $name, $got_it, $date=null){

		$region = AddSlashes($region);
		$realm = AddSlashes($realm_slug);
		$name = AddSlashes($name);
		$date = AddSlashes($date);

		if ($got_it){
			mysql_query("INSERT INTO characters (region, realm, name, `when`) VALUES ('$region', '$realm', '$name', '$date') ON DUPLICATE KEY UPDATE `when`='$date'");
		}

		$time = time();
		$got_it = $got_it ? 1 : 0;

		$sql = "INSERT INTO cache (region, realm, name, got_it, last_fetched, fetch_count) VALUES ('$region', '$realm', '$name', $got_it, $time, 1)".
		" ON DUPLICATE KEY UPDATE got_it=$got_it, last_fetched=$time, fetch_count=fetch_count+1";

		echo "$sql\n";
		mysql_query($sql);
	}

	fetch_user('us', 'hyjal', 'bees');
