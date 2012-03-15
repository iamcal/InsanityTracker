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

	function microtime_ms(){
		    list($usec, $sec) = explode(" ", microtime());
		    return intval(1000 * ((float)$usec + (float)$sec));
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

	function assign_patch(&$row){
		$row['patch'] = 4;
		if ($row['date_got'] < mktime(0,0,0,11,23,2010)) $row['patch'] = 3;
		if ($row['date_got'] < mktime(0,0,0,4,14+7,2009)) $row['patch'] = 2; # within the first week
		if ($row['date_got'] == 1) $row['patch'] = 4; # fucked
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

	$classes = array(
		1 => array('Warrior'),
		2 => array('Paladin'),
		3 => array('Hunter'),
		4 => array('Rogue'),
		5 => array('Priest'),
		6 => array('Death Knight'),
		7 => array('Shaman'),
		8 => array('Mage'),
		9 => array('Warlock'),
		11 => array('Druid'),
	);

	$races = array(
		1 => array('Human', 1),
		3 => array('Dwarf', 1),
		4 => array('Night Elf', 1),
		7 => array('Gnome', 1),
		11 => array('Draenei', 1),
		22 => array('Worgen', 1),

		2 => array('Orc', 2),
		5 => array('Undead', 2),
		6 => array('Tauren', 2),
		8 => array('Troll', 2),
		10 => array('Blood Elf', 2),
		9 => array('Goblin', 2),
	);

	$factions = array(
		1 => array('Alliance', 'http://wowimg.zamimg.com/images/icons/alliance.gif'),
		2 => array('Horde', 'http://wowimg.zamimg.com/images/icons/horde.gif'),
	);

	$patches = array(
		2 => array('Early'),
		3 => array('WotLK'),
		4 => array('Cata'),
	);

