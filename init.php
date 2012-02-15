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
