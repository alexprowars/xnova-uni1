<?php

function CheckPlanetBuildingQueue ( &$CurrentPlanet, &$CurrentUser ) {
	global $lang, $resource;

	// Мирный опыт за строения
	$XPBuildings  = array(  1,  2,  3, 5, 22, 23, 24, 25);

	$RetValue     = false;
	if ( $CurrentPlanet['b_building_id'] ) {
		$CurrentQueue  = $CurrentPlanet['b_building_id'];
		if ($CurrentQueue != 0) {
			$QueueArray    = explode ( ";", $CurrentQueue );
			$ActualCount   = count ( $QueueArray );
		}

		$BuildArray = explode (",", $QueueArray[0]);
		$BuildEndTime = floor($BuildArray[3]);
		$BuildMode = $BuildArray[4];
		$Element = $BuildArray[0];
		array_shift ( $QueueArray );

		if ($BuildMode == 'destroy') {
			$ForDestroy = true;
		} else {
			$ForDestroy = false;
		}

		if ($BuildEndTime <= time()) {
			$Needed                        = GetBuildingPrice ($CurrentUser, $CurrentPlanet, $Element, true, $ForDestroy);
			$Units                         = $Needed['metal'] + $Needed['crystal'] + $Needed['deuterium'];
			if ($ForDestroy == false) {

				if (in_array($Element, $XPBuildings)) {
					$AjoutXP                        = $Units / 3000;
					$CurrentUser['xpminier']       += $AjoutXP;
				}
			} else {

				if (in_array($Element, $XPBuildings)) {
					$AjoutXP                        = ($Units * 3) / 1000;
					$CurrentUser['xpminier']       -= $AjoutXP;
				}
			}

			$current = intval($CurrentPlanet['field_current']);
			$max     = intval($CurrentPlanet['field_max']);

			if ($CurrentPlanet['planet_type'] == 3) {
				if ($Element == 41) {
					$current += 1;
					$max     += FIELDS_BY_MOONBASIS_LEVEL;
					$CurrentPlanet[$resource[$Element]]++;
				} elseif ($Element != 0) {
					if ($ForDestroy == false) {
						$current += 1;
						$CurrentPlanet[$resource[$Element]]++;
					} else {
						$current -= 1;
						$CurrentPlanet[$resource[$Element]]--;
					}
				}
			} elseif ($CurrentPlanet['planet_type'] == 1 || $CurrentPlanet['planet_type'] == 5) {
				if ($ForDestroy == false) {
					$current += 1;
					$CurrentPlanet[$resource[$Element]]++;
				} else {
					$current -= 1;
					$CurrentPlanet[$resource[$Element]]--;
				}
			}
			if (count ( $QueueArray ) == 0) {
				$NewQueue = 0;
			} else {
				$NewQueue = implode (";", $QueueArray );
			}
			$CurrentPlanet['b_building']    = 0;
			$CurrentPlanet['b_building_id'] = $NewQueue;
			$CurrentPlanet['field_current'] = $current;
			$CurrentPlanet['field_max']     = $max;

			$QryUpdatePlanet  = "UPDATE {{table}} SET ";
			$QryUpdatePlanet .= "`".$resource[$Element]."` = '".$CurrentPlanet[$resource[$Element]]."', ";
			$QryUpdatePlanet .= "`b_building` = '". $CurrentPlanet['b_building'] ."' , ";
			$QryUpdatePlanet .= "`b_building_id` = '". $CurrentPlanet['b_building_id'] ."' , ";
			$QryUpdatePlanet .= "`field_current` = '" . $CurrentPlanet['field_current'] . "', ";
			$QryUpdatePlanet .= "`field_max` = '" . $CurrentPlanet['field_max'] . "' ";
			$QryUpdatePlanet .= "WHERE ";
			$QryUpdatePlanet .= "`id` = '" . $CurrentPlanet['id'] . "';";
			doquery( $QryUpdatePlanet, 'planets');

			if ($CurrentUser['xpminier'] < 0) $CurrentUser['xpminier'] = 0;

			if ($CurrentUser['lvl_minier'] < 100) {
				$QryUpdateUser    = "UPDATE {{table}} SET ";
				$QryUpdateUser   .= "`xpminier` = '".$CurrentUser['xpminier']."' ";
				$QryUpdateUser   .= "WHERE ";
				$QryUpdateUser   .= "`id` = '" .$CurrentUser['id']. "';";
				doquery( $QryUpdateUser, 'users');
			}

			$RetValue = true;
		} else {
			$RetValue = false;
		}
	}

	return $RetValue;
}
?>