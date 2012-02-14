<?
	include('init.php');


	function fetch_region($r, $l){

		echo "updating $r $l ... ";
		$num = 0;

		$ret = bnet_make_request($r, "/realm/status?locale=$l");
		if ($ret['ok']){

			foreach ($ret['data']['realms'] as $row){

				$GLOBALS['db_rows'][$r.'-'.$row['slug']] = array(
					'region'	=> $r,
					'slug'		=> AddSlashes($row['slug']),
					'name'		=> AddSlashes($row['name']),
				);

				$num++;
			}
		}

		echo "found $num\n";
	}

	function fetch_regions($region, $langs){
		foreach ($langs as $lang){
			fetch_region($region, $lang);
		}
	}


	#
	# find realms
	#

	$GLOBALS['db_rows'] = array();

	fetch_regions('us', array('en_US', 'es_MX'));
	fetch_regions('eu', array('en_GB', 'es_ES', 'fr_FR', 'ru_RU', 'de_DE'));
	fetch_regions('kr', array('ko_KR'));
	fetch_regions('tw', array('zh_TW', 'en_US'));
	#fetch_regions('cn', array('zh_CN'));


	#
	# insert
	#

	echo "inserting ".count($db_rows)." realms ... ";

	db_write("BEGIN");
	db_write("DELETE FROM realms");

	foreach ($db_rows as $row){
		db_insert('realms', $row);
	}

	db_write("COMMIT");

	echo "ok\n";
