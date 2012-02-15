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

	function dumper($foo){
		echo "<pre style=\"text-align: left;\">";
		echo HtmlSpecialChars(var_export($foo, 1));
		echo "</pre>\n";
	}

	function realm_name($row){

		$more = unserialize($row['locales']);
		if (is_array($more)){
			$names = array();
			$names[$row['name']] = 1;
			foreach ($more as $row2){
				$names[$row2['name']]++;
			}
			$names = array_keys($names);
			if (count($names) > 1){

				$us = array_shift($names);
				$primary = array_shift($names);
				array_unshift($names, $us);

				return $primary." (".implode(' / ', $names).")";
			}
		}

		return $row['name'];
	}

	function check_realm($url){

		$region_enc = AddSlashes($_GET['region']);
		$realm_enc = AddSlashes($_GET['realm']);


		#
		# simple case - we have a correct realm
		#

		$realm = db_single(db_fetch("SELECT * FROM realms WHERE region='$region_enc' AND slug='$realm_enc'"));
		if ($realm['region']) return $realm;


		#
		# complex case - this is a locale slug
		#

		$ret = db_fetch("SELECT * FROM realms WHERE region='$region_enc'");
		foreach ($ret['rows']as $row){
			$more = unserialize($row['locales']);
			if (!is_array($more)) continue;

			foreach ($more as $loc){
				if ($loc['slug'] == $_GET['realm']){

					$url = str_replace('REGION', $row['region'], $url);
					$url = str_replace('REALM', $row['slug'], $url);

					header("location: $url");
					exit;
				}
			}
		}

		die("realm not found");
	}
