<?
	include('init.php');


	function fetch_region($r, $langs, $def='en_US'){

		echo "updating $r ... ";
		$num = 0;

		$map = array();

		#
		# first, find all of the en_US names
		#

		$ret = bnet_make_request($r, "/realm/status?locale=$def");
		if (!$ret['ok']){
			print_r($ret);
			exit;
		}

		foreach ($ret['data']['realms'] as $row){

			$map[$r.'-'.$row['slug']] = array(
				'region'	=> $r,
				'slug'		=> $row['slug'],
				'name'		=> $row['name'],
			);
		}


		#
		# now do the same for each extra language and try and map back
		#

		foreach ($langs as $l){

			foreach ($map as $k => $row){

				echo '.';

				$ret2 = bnet_make_request($r, "/realm/status?locale=$l&realms=".rawurlencode($row['slug']));
				if (!$ret2['ok']){
					print_r($ret2);
					exit;
				}
				$row2 = $ret2['data']['realms'][0];

				if ($row2['slug'] != $row['slug'] || $row2['name'] != $row['name']){

					$map[$k]['locales'][$l] = array(
						'slug' => $row2['slug'],
						'name' => $row2['name'],
					);
				}
			}
		}

		$num = count($map);
		echo " found $num ... ";


		#
		# update db
		#

		foreach ($map as $row){

			db_insert_dupe('realms', array(
				'region'	=> AddSlashes($row['region']),
				'slug'		=> AddSlashes($row['slug']),
				'name'		=> AddSlashes($row['name']),
				'locales'	=> AddSlashes(serialize($row['locales'])),
			), array(
				'name'		=> AddSlashes($row['name']),
				'locales'	=> AddSlashes(serialize($row['locales'])),
			));
		}

		echo "inserted\n";
	}



	#
	# find realms
	#


	fetch_region('us', array('es_MX'));
	fetch_region('eu', array('es_ES', 'fr_FR', 'ru_RU', 'de_DE'), 'en_GB');
	fetch_region('kr', array('ko_KR'));
	fetch_region('tw', array('zh_TW'));

	#fetch_region('cn', array('zh_CN'));

