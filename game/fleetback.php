<?php

if(!defined("INSIDE")) die("attemp hacking");

includeLang('fleet');

$BoxTitle   = $lang['fl_error'];
$TxtColor   = "red";
$BoxMessage = $lang['fl_notback'];

if ( is_numeric($_POST['fleetid']) ) {
	$fleetid  = intval($_POST['fleetid']);

	$FleetRow = doquery("SELECT * FROM {{table}} WHERE `fleet_id` = '". $fleetid ."';", 'fleets', true);
	$i = 0;

	if ($FleetRow['fleet_owner'] == $user['id']) {
		if ($FleetRow['fleet_mess'] == 0 || $FleetRow['fleet_mess'] == 3) {
			if ($FleetRow['fleet_end_stay'] != 0) {

				if ($FleetRow['fleet_start_time'] > time()) {

					$CurrentFlyingTime = time() - $FleetRow['start_time'];
				} else {

					$CurrentFlyingTime = $FleetRow['fleet_start_time'] - $FleetRow['start_time'];
				}
			} else {

				$CurrentFlyingTime = time() - $FleetRow['start_time'];
			}

			$ReturnFlyingTime  = $CurrentFlyingTime + time();

			$QryUpdateFleet  = "UPDATE {{table}} SET ";
			$QryUpdateFleet .= "`fleet_start_time` = '". (time() - 1) ."', ";
			$QryUpdateFleet .= "`fleet_end_stay` = '0', ";
			$QryUpdateFleet .= "`fleet_end_time` = '". ($ReturnFlyingTime + 1) ."', ";
			$QryUpdateFleet .= "`fleet_target_owner` = '". $user['id'] ."', ";
			$QryUpdateFleet .= "`fleet_group` = 0, ";
			$QryUpdateFleet .= "fleet_time = fleet_end_time, `fleet_mess` = '1' ";
			$QryUpdateFleet .= "WHERE ";
			$QryUpdateFleet .= "`fleet_id` = '" . $fleetid . "';";
			doquery( $QryUpdateFleet, 'fleets');

			if ($FleetRow['fleet_group'] > 0)
				doquery("DELETE FROM {{table}} WHERE fleet_id = ".$fleetid."", "aks");

			$BoxTitle   = $lang['fl_sback'];
			$TxtColor   = "lime";
			$BoxMessage = $lang['fl_isback'];
		} elseif ($FleetRow['fleet_mess'] == 1) {
			$BoxMessage = $lang['fl_notback'];
		}
	} else {
		$BoxMessage = $lang['fl_onlyyours'];
	}
}

message ("<font color=\"".$TxtColor."\">". $BoxMessage ."</font>", $BoxTitle, "?set=fleet", 2);


?>