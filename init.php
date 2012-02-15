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

		$names = array($row['name'] => 1);

		$more = unserialize($row['locales']);
		if (is_array($more)){
			foreach ($more as $row2){
				$names[$row2['name']] ++;
			}
		}

		return implode(' / ', array_keys($names));
	}
