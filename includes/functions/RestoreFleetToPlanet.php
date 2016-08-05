<?php

function RestoreFleetToPlanet ( $FleetRow, $Start = true ) {
	global $resource;

	if ($FleetRow){
	
	if ($Start == true && $FleetRow['fleet_start_type'] == 3) {
        $CheckFleet = doquery("SELECT destruyed FROM {{table}} WHERE `galaxy` = '". $FleetRow['fleet_start_galaxy'] ."' AND `system` = '". $FleetRow['fleet_start_system'] ."' AND `planet` = '". $FleetRow['fleet_start_planet'] ."' AND `planet_type` = '". $FleetRow['fleet_start_type'] ."'", "planets", true);

        if ($CheckFleet['destruyed'] != 0) {
            $FleetRow['fleet_start_type'] = 1;
        }
    } elseif ($FleetRow['fleet_end_type'] == 3) {
        $CheckFleet = doquery("SELECT destruyed FROM {{table}} WHERE `galaxy` = '". $FleetRow['fleet_end_galaxy'] ."' AND `system` = '". $FleetRow['fleet_end_system'] ."' AND `planet` = '". $FleetRow['fleet_end_planet'] ."' AND `planet_type` = '". $FleetRow['fleet_end_type'] ."'", "planets", true);

        if ($CheckFleet['destruyed'] != 0) {
            $FleetRow['fleet_end_type'] = 1;
        }
    }

	$FleetRecord         = explode(";", $FleetRow['fleet_array']);
	$QryUpdFleet         = "";
	foreach ($FleetRecord as $Item => $Group) {
		if ($Group != '') {
			$Class        = explode (",", $Group);
			$QryUpdFleet .= "`". $resource[$Class[0]] ."` = `".$resource[$Class[0]]."` + '".$Class[1]."', \n";
		}
	}

	$QryUpdatePlanet   = "UPDATE {{table}} SET ";
	if ($QryUpdFleet != "") {
		$QryUpdatePlanet  .= $QryUpdFleet;
	}
	$QryUpdatePlanet  .= "`metal` = `metal` + '". $FleetRow['fleet_resource_metal'] ."', ";
	$QryUpdatePlanet  .= "`crystal` = `crystal` + '". $FleetRow['fleet_resource_crystal'] ."', ";
	$QryUpdatePlanet  .= "`deuterium` = `deuterium` + '". $FleetRow['fleet_resource_deuterium'] ."' ";
	$QryUpdatePlanet  .= "WHERE ";
	if ($Start == true) {
		$QryUpdatePlanet  .= "`galaxy` = '". $FleetRow['fleet_start_galaxy'] ."' AND ";
		$QryUpdatePlanet  .= "`system` = '". $FleetRow['fleet_start_system'] ."' AND ";
		$QryUpdatePlanet  .= "`planet` = '". $FleetRow['fleet_start_planet'] ."' AND ";
		$QryUpdatePlanet  .= "`planet_type` = '". $FleetRow['fleet_start_type'] ."' ";
	} else {
		$QryUpdatePlanet  .= "`galaxy` = '". $FleetRow['fleet_end_galaxy'] ."' AND ";
		$QryUpdatePlanet  .= "`system` = '". $FleetRow['fleet_end_system'] ."' AND ";
		$QryUpdatePlanet  .= "`planet` = '". $FleetRow['fleet_end_planet'] ."' AND ";
		$QryUpdatePlanet  .= "`planet_type` = '". $FleetRow['fleet_end_type'] ."' ";
	}
	$QryUpdatePlanet  .= "LIMIT 1;";
	doquery( $QryUpdatePlanet, 'planets');

	}
}
?>