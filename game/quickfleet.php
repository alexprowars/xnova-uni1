<?php

if(!defined("INSIDE")) die("attemp hacking");

	if ($user['urlaubs_modus_time'] > 0) {
		message("Нет доступа!");
	}

	includeLang('fleet');

	$maxfleet  = doquery("SELECT COUNT(fleet_owner) AS `actcnt` FROM {{table}} WHERE `fleet_owner` = '".$user['id']."';", 'fleets', true);
	$MaxFlyingFleets    = $maxfleet['actcnt'];

	$MaxFlottes         = 1 + $user[$resource[108]];
	if ($user['rpg_admiral'] > time()) $MaxFlottes += 2; 

	if ($MaxFlottes <= $MaxFlyingFleets) {
		message("Все слоты флота заняты", "Ошибка", "?set=overview", 1);
	}

	$Mode   = intval($_GET['mode']);
	$Galaxy = intval($_GET['g']);
	$System = intval($_GET['s']);
	$Planet = intval($_GET['p']);
	$TypePl = intval($_GET['t']);

	$missiontype = array(
		1 => $lang['type_mission'][1],
		2 => $lang['type_mission'][2],
		3 => $lang['type_mission'][3],
		4 => $lang['type_mission'][4],
		5 => $lang['type_mission'][5],
		6 => $lang['type_mission'][6],
		7 => $lang['type_mission'][7],
		8 => $lang['type_mission'][8],
		9 => $lang['type_mission'][9],
		15 => $lang['type_mission'][15]
	);

	if ($Mode == 8) {
		$QrySelectGalaxy  = "SELECT * FROM {{table}} WHERE ";
		$QrySelectGalaxy .= "`galaxy` = '".$planetrow['galaxy']."' AND ";
		$QrySelectGalaxy .= "`system` = '".$planetrow['system']."' AND ";
		$QrySelectGalaxy .= "`planet` = '".$planetrow['planet']."' LIMIT 1;";

		$TargetGalaxy     = doquery( $QrySelectGalaxy, 'galaxy', true);
		$DebrisSize       = $TargetGalaxy['metal'] + $TargetGalaxy['crystal'];
		$RecyclerNeeded   = floor($DebrisSize / ($pricelist[209]['capacity'])) + 1;
		$RecyclerSpeed    = $pricelist[209]['speed'] + (($pricelist[209]['speed'] * $user['combustion_tech']) * 0.1);

		$RecyclerCount    = $planetrow[$resource[209]];
		if ($RecyclerCount > 0){
			if ($RecyclerCount > $RecyclerNeeded) {
				$FleetCount = $RecyclerNeeded;
			} else {
				$FleetCount = $RecyclerCount;
			}
			$FleetArray[209] = $FleetCount;
		}else{
			message("У вас нет переработчиков!", "Ошибка", "?set=overview", 1);
		}
	} else
		message("Сбой отправки", "Ошибка", "?set=overview", 1);

	$distance      = GetTargetDistance  ( $planetrow['galaxy'], $Galaxy, $planetrow['system'], $System, $planetrow['planet'], $Planet );
	$SpeedFactor   = $game_config['fleet_speed'] / 2500;
	$GenFleetSpeed = 10; // a 100%
	$duration      = GetMissionDuration ( $GenFleetSpeed, $RecyclerSpeed, $distance, $SpeedFactor );

	$page .= "<br /><br />";
	$page .= "<center>";
	$page .= "<table width=\"519\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\">";
	$page .= "<tr height=\"20\">";
	$page .= "<td class=\"c\" colspan=\"2\">";
	$page .= "<span class=\"success\">".$lang['fl_fleet_send']."</span>";
	$page .= "</td>";
	$page .= "</tr>";
	$page .= "<tr height=\"20\">";
	$page .= "<th>". $lang['fl_mission'] ."</th>";
	$page .= "<th>". $missiontype[$Mode] ."</th>";
	$page .= "</tr>";
	$page .= "<tr height=\"20\">";
	$page .= "<th>". $lang['fl_dist'] ."</th>";
	$page .= "<th>". $distance ."</th>";
	$page .= "</tr>";
	$page .= "<tr height=\"20\">";
	$page .= "<th>". $lang['fl_fleetspeed'] ."</th>";
	$page .= "<th>28750</th>";
	$page .= "</tr>";
	$page .= "<tr height=\"20\">";
	$page .= "<th>". $lang['fl_deute_need'] ."</th>";
	$page .= "<th>10</th>";
	$page .= "</tr>";
	$page .= "<tr height=\"20\">";
	$page .= "<th>". $lang['fl_from'] ."</th>";
	$page .= "<th>[". $planetrow['galaxy'] .":". $planetrow['system'] .":". $planetrow['planet'] ."]</th>";
	$page .= "</tr>";
	$page .= "<tr height=\"20\">";
	$page .= "<th>". $lang['fl_dest'] ."</th>";
	$page .= "<th>[". $Galaxy .":". $System .":". $Planet ."]</th>";
	$page .= "</tr>";
	$page .= "<tr height=\"20\">";
	$page .= "<th>". $lang['fl_time_go'] ."</th>";
	$page .= "<th>". date("M D d H:i:s",($duration + time())) ."</th>";
	$page .= "</tr>";
	$page .= "<tr height=\"20\">";
	$page .= "<th>". $lang['fl_time_back'] ."</th>";
	$page .= "<th>". date("M D d H:i:s",(($duration * 2) + time())) ."</th>";
	$page .= "</tr>";
	$page .= "<tr height=\"20\">";
	$page .= "<td class=\"c\" colspan=\"2\">". $lang['fl_title'] ."</td>";
	$page .= "</tr>";
	$page .= "<tr height=\"20\">";
	$ShipCount = 0;
	$ShipArray = "";
	foreach ($FleetArray as $Ship => $Count) {
		$page            .= "<th width=\"50%\">". $lang['tech'][$Ship] ."</th>";
		$page            .= "<th>". pretty_number($Count) ."</th>";
		$FleetSubQRY     .= "`".$resource[$Ship] . "` = `" . $resource[$Ship] . "` - " . $Count . " , ";
		$ShipArray       .= $Ship.",".$Count.";";
		$ShipCount       += $Count;
	}
	$page .= "		</tr>";
	$page .= "	</table>";

	$QryInsertFleet  = "INSERT INTO {{table}} SET ";
	$QryInsertFleet .= "`fleet_owner` = '". $user['id'] ."', ";
	$QryInsertFleet .= "`fleet_owner_name` = '". $planetrow['name'] ."', ";
	$QryInsertFleet .= "`fleet_mission` = '". $Mode ."', ";
	$QryInsertFleet .= "`fleet_amount` = '". $ShipCount ."', ";
	$QryInsertFleet .= "`fleet_array` = '". $ShipArray ."', ";
	$QryInsertFleet .= "`fleet_start_time` = '". ($duration + time()) ."', ";
	$QryInsertFleet .= "`fleet_start_galaxy` = '". $planetrow['galaxy'] ."', ";
	$QryInsertFleet .= "`fleet_start_system` = '". $planetrow['system'] ."', ";
	$QryInsertFleet .= "`fleet_start_planet` = '". $planetrow['planet'] ."', ";
	$QryInsertFleet .= "`fleet_start_type` = '". $planetrow['planet_type'] ."', ";
	$QryInsertFleet .= "`fleet_end_time` = '". (($duration * 2) + time()) ."', ";
	$QryInsertFleet .= "`fleet_end_galaxy` = '". $Galaxy ."', ";
	$QryInsertFleet .= "`fleet_end_system` = '". $System ."', ";
	$QryInsertFleet .= "`fleet_end_planet` = '". $Planet ."', ";
	$QryInsertFleet .= "`fleet_end_type` = '". $TypePl ."', ";
	$QryInsertFleet .= "`start_time` = '". time() ."';";
	doquery( $QryInsertFleet, 'fleets');

	$QryUpdatePlanet  = "UPDATE {{table}} SET ";
	$QryUpdatePlanet .= $FleetSubQRY;
	$QryUpdatePlanet .= "`planet_type` = '".$planetrow['planet_type']."' ";
	$QryUpdatePlanet .= "WHERE ";
	$QryUpdatePlanet .= "`id` = '". $planetrow['id'] ."'";
	doquery ($QryUpdatePlanet, "planets");

	message($page, "Переработка обломков", "?set=overview", 3);

?>