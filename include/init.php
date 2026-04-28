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

		$more = unserialize($row['locales'] ?? '');
		if (is_array($more)){
			$names = array();
			$names[$row['name'] ?? ''] = 1;
			foreach ($more as $row2){
				$key = $row2['name'] ?? '';
				if (isset($names[$key])){
					$names[$key]++;
				}else{
					$names[$key] = 1;
				}
			}
			$names = array_keys($names);
			if (count($names) > 1){

				$us = array_shift($names);
				$primary = array_shift($names);
				array_unshift($names, $us);

				return $primary." (".implode(' / ', $names).")";
			}
		}

		return $row['name'] ?? '';
	}

	function assign_patch(&$row){
		$row['patch'] = 4;
		$date_got = $row['date_got'] ?? 0;
		if ($date_got < mktime(0,0,0,11,15,2010)) $row['patch'] = 3;
		if ($date_got < mktime(0,0,0,4,14+7,2009)) $row['patch'] = 2; # within the first week
		if ($date_got == 1) $row['patch'] = 4; # fucked
	}

	function check_realm($url){

		$region_enc = AddSlashes($_GET['region'] ?? '');
		$realm_enc = AddSlashes($_GET['realm'] ?? '');


		#
		# simple case - we have a correct realm
		#

		$realm = db_single(db_fetch("SELECT * FROM realms WHERE region='$region_enc' AND slug='$realm_enc'"));
		if ($realm['region'] ?? null) return $realm;


		#
		# complex case - this is a locale slug
		#

		$ret = db_fetch("SELECT * FROM realms WHERE region='$region_enc'");
		foreach (($ret['rows'] ?? []) as $row){
			$more = unserialize($row['locales'] ?? '');
			if (!is_array($more)) continue;

			foreach ($more as $loc){
				if (($loc['slug'] ?? null) == ($_GET['realm'] ?? null)){

					$url = str_replace('REGION', $row['region'], $url);
					$url = str_replace('REALM', $row['slug'], $url);

					header("location: $url");
					exit;
				}
			}
		}

		$error = "The realm you requested could not be found.";
		include('notfound.php');
	}

	function format_region($r){
		if ($r == 'us') return 'US';
		if ($r == 'eu') return 'Europe';
		if ($r == 'kr') return 'Korea';
		if ($r == 'tw') return 'Taiwan';
		return $r;
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
		10 => array('Monk'),
		11 => array('Druid'),
		12 => array('Demon Hunter'),
		13 => array('Evoker'),
	);

	$races = array(
		1 => array('Human', 1),
		3 => array('Dwarf', 1),
		4 => array('Night Elf', 1),
		7 => array('Gnome', 1),
		11 => array('Draenei', 1),
		22 => array('Worgen', 1),
		25 => array('Pandaren', 1),
		52 => array('Dracthyr', 1),

		2 => array('Orc', 2),
		5 => array('Undead', 2),
		6 => array('Tauren', 2),
		8 => array('Troll', 2),
		10 => array('Blood Elf', 2),
		9 => array('Goblin', 2),
		26 => array('Pandaren', 2),
		70 => array('Dracthyr', 2),
	);

	$factions = array(
		1 => array('Alliance', 'achievement_pvp_a_16.jpg'),
		2 => array('Horde', 'achievement_pvp_h_16.jpg'),
	);

	$patches = array(
		2 => array('Early', 'bc.gif', 29, 14),
		3 => array('WotLK', 'wrath.gif', 34, 16),
		4 => array('Cata', 'cata.gif', 38, 14),
	);

