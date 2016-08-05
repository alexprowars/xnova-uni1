<?php

if(!defined("INSIDE")) die("attemp hacking");

function DoFleetJump ( $CurrentUser, $CurrentPlanet ) {
	global $lang, $resource;

	includeLang ('infos');

	if ($_POST && ($CurrentPlanet['planet_type'] == 3 || $CurrentPlanet['planet_type'] == 5) && $CurrentPlanet['sprungtor'] > 0) {
		$RestString   = GetNextJumpWaitTime ( $CurrentPlanet );
		$NextJumpTime = $RestString['value'];
		$JumpTime     = time();

		if ( $NextJumpTime == 0 ) {
			$TargetPlanet = intval($_POST['jmpto']);
			$TargetGate   = doquery ( "SELECT `id`, `planet_type`, `sprungtor`, `last_jump_time` FROM {{table}} WHERE `id` = '". $TargetPlanet ."';", 'planets', true);

			if (($TargetGate['planet_type'] == 3 || $TargetGate['planet_type'] == 5) && $TargetGate['sprungtor'] > 0) {
				$RestString   = GetNextJumpWaitTime ( $TargetGate );
				$NextDestTime = $RestString['value'];
				if ( $NextDestTime == 0 ) {
					$ShipArray   = array();
					$SubQueryOri = "";
					$SubQueryDes = "";
					for ( $Ship = 200; $Ship < 300; $Ship++ ) {
						$ShipLabel = "c". $Ship;
						
						if (!isset($_POST[ $ShipLabel ]))
							continue;

						if (intval($_POST[ $ShipLabel ]) < 0) {
							die();
						}

						if ( abs(intval($_POST[ $ShipLabel ])) > $CurrentPlanet[ $resource[ $Ship ] ] ) {
							$ShipArray[ $Ship ] = $CurrentPlanet[ $resource[ $Ship ] ];
						} else {
							$ShipArray[ $Ship ] = abs(intval($_POST[ $ShipLabel ]));
						}
						if ($ShipArray[ $Ship ] <> 0) {
							$SubQueryOri .= "`". $resource[ $Ship ] ."` = `". $resource[ $Ship ] ."` - '". $ShipArray[ $Ship ] ."', ";
							$SubQueryDes .= "`". $resource[ $Ship ] ."` = `". $resource[ $Ship ] ."` + '". $ShipArray[ $Ship ] ."', ";
						}
					}

					if ($SubQueryOri != "") {
						$QryUpdateOri  = "UPDATE {{table}} SET ";
						$QryUpdateOri .= $SubQueryOri;
						$QryUpdateOri .= "`last_jump_time` = '". $JumpTime ."' ";
						$QryUpdateOri .= "WHERE ";
						$QryUpdateOri .= "`id` = '". $CurrentPlanet['id'] ."';";
						doquery ( $QryUpdateOri, 'planets');

						$QryUpdateDes  = "UPDATE {{table}} SET ";
						$QryUpdateDes .= $SubQueryDes;
						$QryUpdateDes .= "`last_jump_time` = '". $JumpTime ."' ";
						$QryUpdateDes .= "WHERE ";
						$QryUpdateDes .= "`id` = '". $TargetGate['id'] ."';";
						doquery ( $QryUpdateDes, 'planets');

						$QryUpdateUsr  = "UPDATE {{table}} SET ";
						$QryUpdateUsr .= "`current_planet` = '". $TargetGate['id'] ."' ";
						$QryUpdateUsr .= "WHERE ";
						$QryUpdateUsr .= "`id` = '". $CurrentUser['id'] ."';";
						doquery ( $QryUpdateUsr, 'users');

						$CurrentPlanet['last_jump_time'] = $JumpTime;
						$RestString    = GetNextJumpWaitTime ( $CurrentPlanet );
						$RetMessage    = $lang['gate_jump_done'] ." - ". $RestString['string'];
					} else {
						$RetMessage = $lang['gate_wait_data'];
					}
				} else {
					$RetMessage = $lang['gate_wait_dest'] ." - ". $RestString['string'];
				}
			} else {
				$RetMessage = $lang['gate_no_dest_g'];
			}
		} else {
			$RetMessage = $lang['gate_wait_star'] ." - ". $RestString['string'];
		}
	} else {
		$RetMessage = $lang['gate_wait_data'];
	}

	return $RetMessage;
}

	$Message = DoFleetJump($user, $planetrow);
	message ($Message, $lang['tech'][43], "?set=infos&gid=43", 4);

?>