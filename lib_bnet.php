<?php
	#
	# $Id$
	#


	$GLOBALS['cfg']['bnet_region_hosts'] = array(
		'cn'	=> 'www.battlenet.com.cn',
		'eu'	=> 'eu.battle.net',
		'kr'	=> 'kr.battle.net',
		'tw'	=> 'tw.battle.net',
		'us'	=> 'us.battle.net',
	);

	#########################################################

	function bnet_make_request($region, $url){

		list($path, $qs) = explode('?', $url, 2);


		#
		# auth hash
		#

		$path = "/api/wow$path";

		$date = gmdate("D, d M Y H:i:s").' GMT';

		$hash = pack("H*", hash_hmac("sha1", "GET\n$date\n$path\n", $GLOBALS['cfg']['bnet_key_private']));

		if ($GLOBALS['cfg']['bnet_key_public']){

			$headers = array(
				'Date'		=> $date,
				'Authorization'	=> "BNET ".$GLOBALS['cfg']['bnet_key_public'].":".base64_encode($hash),
			);
		}else{
			$headers = array();
		}


		#
		# build the URL
		#

		$api_host = $GLOBALS['cfg']['bnet_region_hosts'][$region];

		$url = "http://".$api_host."{$path}?{$qs}";


		#
		# make request
		#

		$ret = http_get($url, $headers);


		#
		# build response
		#

		if ($ret['status'] == 200){

			return array(
				'ok'	=> 1,
				'data'	=> json_decode($ret['body'], true),
			);
		}

		return array(
			'ok'	=> 0,
			'req'	=> $ret,
		);
	}

	#########################################################

	function bnet_get_realm_status($region) {

		$region_stub = mb_strtolower($region);

		return bnet_make_request($region_stub, "/realm/status");
	}

	#########################################################

	function bnet_get_character($region, $realm_stub, $name, $args=array()){

		$name_stub	= mb_strtolower($name);
		$region_stub	= mb_strtolower($region);

		$name_stub = str_replace("%27", "'", rawurlencode($name_stub));
		$realm_stub = str_replace("%27", "'", rawurlencode($realm_stub));

		return bnet_make_request($region_stub, "/character/{$realm_stub}/{$name_stub}", $args);
	}

	#########################################################

	function bnet_get_faction($id) {
		$output = array("Faction"=>"","Race"=>"");
		switch($id) {
			case 1:
				$output["Faction"] = "Alliance";
				$output["Race"] = "Human";
				break;
			case 2:
				$output["Faction"] = "Horde";
				$output["Race"] = "Orc";
				break;
			case 3:
				$output["Faction"] = "Alliance";
				$output["Race"] = "Dwarf";
				break;
			case 4:
				$output["Faction"] = "Alliance";
				$output["Race"] = "Night Elf";
				break;
			case 5:
				$output["Faction"] = "Horde";
				$output["Race"] = "Undead";
				break;
			case 6:
				$output["Faction"] = "Horde";
				$output["Race"] = "Tauren";
				break;
			case 7:
				$output["Faction"] = "Alliance";
				$output["Race"] = "Gnome";
				break;
			case 8:
				$output["Faction"] = "Horde";
				$output["Race"] = "Troll";
				break;
			case 9:
				$output["Faction"] = "Horde";
				$output["Race"] = "Goblin";
				break;
			case 10:
				$output["Faction"] = "Horde";
				$output["Race"] = "Blood Elf";
				break;
			case 11:
				$output["Faction"] = "Alliance";
				$output["Race"] = "Draenei";
				break;
			case 22:
				$output["Faction"] = "Alliance";
				$output["Race"] = "Worgen";
				break;
			default:
				return false;
		}
		return $output;
	}

	#########################################################

	function bnet_fetch_safe($region, $url, $retry=2){

		for ($i=0; $i<$retry; $i++){

			$ret = bnet_make_request($region, $url);

			if ($ret['ok']) return $ret;
		}

		return $ret;
	}

	#########################################################
?>
