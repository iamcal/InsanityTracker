<?
	include('init.php');


	function fetch_region($r){

		echo "updating $r ... ";
		$num = 0;

		$ret = bnet_make_request($r, "/realm/status");
		if ($ret['ok']){

			db_write("BEGIN");
			db_write("DELETE FROM realms WHERE region='$r'");
			foreach ($ret['data']['realms'] as $row){

				db_insert('realms', array(
					'region'	=> $r,
					'slug'		=> AddSlashes($row['slug']),
					'name'		=> AddSlashes($row['name']),
				));

				$num++;
			}
			db_write("COMMIT");
		}

		echo "found $num\n";
	}

	fetch_region('us');
	fetch_region('eu');
	fetch_region('kr');
	fetch_region('tw');
	fetch_region('cn');
