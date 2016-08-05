<?php

header ("Content-type: content=text/html; charset=utf-8");

$api = array();

include("includes/JSON.php");

if ($user['id'] == 1) {

$api['uid'] 	= $user['id'];
$api['uname'] 	= utf8_encode($user['username']);

	if ($_GET['gPList']) {
		$plrow = doquery("SELECT id, name, galaxy, system, planet, planet_type, image FROM {{table}} WHERE `id_owner` = ".$user['id'].";", 'planets');

		while ($r = mysql_fetch_assoc($plrow)) {
			$api['sPList'][] = array(
				'id'			=> $r['id'],
				'name'			=> utf8_encode($r['name']),
				'galaxy'		=> $r['galaxy'],
				'system'		=> $r['system'],
				'planet'		=> $r['planet'],
				'planet_type'	=> $r['planet_type'],
				'image'			=> $r['image']
			);
		}
		unset($plrow);
		unset($r);
	}
	// Получение информации о планете
	if ($_GET['gRes']) {
		$pid = intval($_GET['gRes']);
		
		$prow = doquery("SELECT * FROM {{table}} WHERE `id_owner` = ".$user['id']." AND `id` = '".$pid."';", 'planets', true);
		$prow['name'] = utf8_encode($prow['name']);
		
		if (!$prow['id'])
			$api['error'] = 2;
		else {
			PlanetResourceUpdate ( $user, $prow, time(), true );
			$api['sRes'] = $prow;
		}
		unset($prow);
	}
	
	if ($_GET['gFleet']) {
		$frow = doquery("SELECT * FROM {{table}} WHERE `fleet_owner` = ".$user['id'].";", 'fleets');
	
		while ($r = mysql_fetch_assoc($frow)) {
			$r['fleet_owner_name'] = utf8_encode($r['fleet_owner_name']);
			$r['fleet_target_owner_name'] = utf8_encode($r['fleet_target_owner_name']);			
			$api['sFleet'][] = $r;
		}
		unset($frow);
		unset($r);
	}
	
	if ($_GET['gEnFleet']) {
		$frow = doquery("SELECT * FROM {{table}} WHERE `fleet_target_owner` = ".$user['id']." AND fleet_mission = 1 AND fleet_mess = 0;", 'fleets');
	
		while ($r = mysql_fetch_assoc($frow)) {
			unset($r['fleet_id']);
			unset($r['fleet_end_time']);
			unset($r['fleet_end_stay']);
			unset($r['fleet_group']);
			unset($r['start_time']);
			unset($r['fleet_mess']);
			unset($r['fleet_resource_metal']);
			unset($r['fleet_resource_crystal']);
			unset($r['fleet_resource_deuterium']);
			$r['fleet_owner_name'] = utf8_encode($r['fleet_owner_name']);
			$r['fleet_target_owner_name'] = utf8_encode($r['fleet_target_owner_name']);
			$api['sEnFleet'][] = $r;
		}
		unset($frow);
		unset($r);
	}
	
	if ($_GET['gMy']) {
		$api['sMy'] = array(
			'sex'				=> $user['sex'],
			'cur_planet'		=> $user['current_planet'],
			'ip'				=> $user['user_lastip'],
			'mess'				=> $user['new_message'],
			'ally_mess'			=> $user['mnl_alliance'],
			'ally_id'			=> $user['ally_id'],
			'ally_name'			=> utf8_encode($user['ally_name']),
			'ally_rank_id'		=> $user['ally_rank_id'],
			'credits'			=> $user['credits'],
			'avatar'			=> $user['avatar']
		);
	}

	if (!$api['error'])
		$api['error'] = 0;
} else
	$api['error'] = 1;

$json = new Services_JSON();	
	
echo $json->encode($api);

?>