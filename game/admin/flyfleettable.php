<?php

if(!defined("INSIDE")) die("attemp hacking");

if ($user['authlevel'] < 3)
	message( $lang['sys_noalloaw'], $lang['sys_noaccess'] );

	includeLang('admin/fleets');
	$PageTPL            = gettemplate('admin/fleet_body');

function BuildFlyingFleetTable () {
	global $lang;

	$TableTPL     = gettemplate('admin/fleet_rows');
	$FlyingFleets = doquery ("SELECT * FROM {{table}} ORDER BY `fleet_end_time` ASC;", 'fleets');
	while ( $CurrentFleet = mysql_fetch_assoc( $FlyingFleets ) ) {

		$Bloc['Id']       = $CurrentFleet['fleet_id'];
		$Bloc['Mission']  = CreateFleetPopupedMissionLink ( $CurrentFleet, $lang['type_mission'][ $CurrentFleet['fleet_mission'] ], '' );
		$Bloc['Mission'] .= "<br>". (($CurrentFleet['fleet_mess'] == 1) ? "R" : "A" );

		$Bloc['Fleet']    = CreateFleetPopupedFleetLink ( $CurrentFleet, $lang['tech'][200], '' );
		$Bloc['St_Owner'] = "[". $CurrentFleet['fleet_owner'] ."]<br>". $CurrentFleet['fleet_owner_name'];
		$Bloc['St_Posit'] = "[".$CurrentFleet['fleet_start_galaxy'] .":". $CurrentFleet['fleet_start_system'] .":". $CurrentFleet['fleet_start_planet'] ."]<br>". ( ($CurrentFleet['fleet_start_type'] == 1) ? "[P]": (($CurrentFleet['fleet_start_type'] == 2) ? "D" : "L"  )) ."";
		$Bloc['St_Time']  = date('H:i:s d/n/Y', $CurrentFleet['fleet_start_time']);
		if (is_array($TargetOwner)) {
			$Bloc['En_Owner'] = "[". $CurrentFleet['fleet_target_owner'] ."]<br>". $CurrentFleet['fleet_target_owner_name'];
		} else {
			$Bloc['En_Owner'] = "";
		}
		$Bloc['En_Posit'] = "[".$CurrentFleet['fleet_end_galaxy'] .":". $CurrentFleet['fleet_end_system'] .":". $CurrentFleet['fleet_end_planet'] ."]<br>". ( ($CurrentFleet['fleet_end_type'] == 1) ? "[P]": (($CurrentFleet['fleet_end_type'] == 2) ? "D" : "L"  )) ."";
		if ($CurrentFleet['fleet_mission'] == 15) {
			$Bloc['Wa_Time']  = date('H:i:s d/n/Y', $CurrentFleet['fleet_stay_time']);
		} else {
			$Bloc['Wa_Time']  = "";
		}
		$Bloc['En_Time']  = date('H:i:s d/n/Y', $CurrentFleet['fleet_end_time']);

		$table .= parsetemplate( $TableTPL, $Bloc );
	}
	return $table;
}

	$parse              = $lang;
	$parse['flt_table'] = BuildFlyingFleetTable ();

	$page               = parsetemplate( $PageTPL, $parse );
	display ( $page, $lang['flt_title'], false, true, true);
?>