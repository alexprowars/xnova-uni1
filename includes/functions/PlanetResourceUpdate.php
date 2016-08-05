<?php

function PlanetResourceUpdate ( $CurrentUser, &$CurrentPlanet, $UpdateTime = 0, $Simul = false ) {
	global $ProdGrid, $resource, $reslist, $game_config;

	if ($CurrentUser['urlaubs_modus_time'] != 0)
       	$Simul = true;
        
    	$UpdateTime = time();
	
	$bonus_g = 0;
	$bonus_h = 0;
	$bonus_i = 0;

	if ($CurrentUser['rpg_geologue'] > time()) {
		$bonus_g = 25;
		$bonus_h = 25;
	}
	if ($CurrentUser['rpg_ingenieur'] > time())
		$bonus_i = 15;

	$CurrentPlanet['metal_max']     	= floor((BASE_STORAGE_SIZE + floor (50000 * round(pow (1.6, $CurrentPlanet[ $resource[22] ] )))) * (1 + $bonus_h * 0.01));
	$CurrentPlanet['crystal_max']   	= floor((BASE_STORAGE_SIZE + floor (50000 * round(pow (1.6, $CurrentPlanet[ $resource[23] ] )))) * (1 + $bonus_h * 0.01));
	$CurrentPlanet['deuterium_max'] 	= floor((BASE_STORAGE_SIZE + floor (50000 * round(pow (1.6, $CurrentPlanet[ $resource[24] ] )))) * (1 + $bonus_h * 0.01));

	$MaxMetalStorage		= $CurrentPlanet['metal_max']     * MAX_OVERFLOW;
	$MaxCristalStorage		= $CurrentPlanet['crystal_max']   * MAX_OVERFLOW;
	$MaxDeuteriumStorage	= $CurrentPlanet['deuterium_max'] * MAX_OVERFLOW;
	$MaxEnergyStorage		= floor(10000 * pow((1.1), ($CurrentPlanet['ak_station'])) * $CurrentPlanet['ak_station']);


	$Caps             			= array();
	$Caps['metal_perhour'] 		= 0;
	$Caps['crystal_perhour'] 	= 0;
	$Caps['deuterium_perhour'] 	= 0;
	$Caps['energy_used'] 		= 0;
	$Caps['energy_max'] 		= 0;
	$BuildTemp        			= $CurrentPlanet[ 'temp_max' ];

    foreach ($reslist['prod'] AS $ProdID) { 
	    $BuildLevelFactor = $CurrentPlanet[ $resource[$ProdID]."_porcent" ];
	    $BuildLevel       = $CurrentPlanet[ $resource[$ProdID] ];

		if ($ProdID == 12 && $CurrentPlanet['deuterium'] < 10) $BuildLevelFactor = 0;

	    $Caps['metal_perhour']     	+=  floor(eval($ProdGrid[$ProdID]['formule']['metal']) * $game_config['resource_multiplier'] * ( 1 + ( $bonus_g  * 0.01 ) ) );
	    $Caps['crystal_perhour']   	+=  floor(eval($ProdGrid[$ProdID]['formule']['crystal']) * $game_config['resource_multiplier'] * ( 1 + ( $bonus_g  * 0.01 ) ) );
	    $Caps['deuterium_perhour'] 	+=  floor(eval($ProdGrid[$ProdID]['formule']['deuterium']) * $game_config['resource_multiplier'] * ( 1 + ( $bonus_g  * 0.01 ) ) );
	    if ($ProdID < 4) {
	        $Caps['energy_used']	+=  floor(eval($ProdGrid[$ProdID]['formule']['energy']) * $game_config['resource_multiplier']);
	    } elseif ($ProdID >= 4 ) {
	        $Caps['energy_max'] 	+=  floor(eval($ProdGrid[$ProdID]['formule']['energy']) * $game_config['resource_multiplier'] * ( 1 + ( $bonus_i * 0.01 ) ) );
	    }
	}

	if ($CurrentPlanet['planet_type'] == 3 || $CurrentPlanet['planet_type'] == 5) {
		$game_config['metal_basic_income']     = 0;
		$game_config['crystal_basic_income']   = 0;
		$game_config['deuterium_basic_income'] = 0;
		$CurrentPlanet['metal_perhour']        = 0;
		$CurrentPlanet['crystal_perhour']      = 0;
		$CurrentPlanet['deuterium_perhour']    = 0;
		$CurrentPlanet['energy_used']          = 0;
		$CurrentPlanet['energy_max']           = 0;
	} else {
		$CurrentPlanet['metal_perhour']        = $Caps['metal_perhour'];
		$CurrentPlanet['crystal_perhour']      = $Caps['crystal_perhour'];
		$CurrentPlanet['deuterium_perhour']    = $Caps['deuterium_perhour'];
		$CurrentPlanet['energy_used']          = $Caps['energy_used'];
		$CurrentPlanet['energy_max']           = $Caps['energy_max'];
	}

	$ProductionTime = ($UpdateTime - $CurrentPlanet['last_update']);
	$CurrentPlanet['last_update'] = $UpdateTime;

	if ($CurrentPlanet['energy_max'] == 0) {
		$CurrentPlanet['metal_perhour']     = $game_config['metal_basic_income'];
		$CurrentPlanet['crystal_perhour']   = $game_config['crystal_basic_income'];
		$CurrentPlanet['deuterium_perhour'] = $game_config['deuterium_basic_income'];
		$production_level = 0;
	} elseif ($CurrentPlanet['energy_max'] >= abs($CurrentPlanet['energy_used'])) {
		$production_level = 100;
		$akk_add = round(($CurrentPlanet['energy_max'] - abs($CurrentPlanet['energy_used']))*($ProductionTime/3600), 2);
		if ($MaxEnergyStorage > ($CurrentPlanet['energy_ak'] + $akk_add))
			$CurrentPlanet['energy_ak'] += $akk_add;
		else
			$CurrentPlanet['energy_ak'] = $MaxEnergyStorage;
	} else {
		if ($CurrentPlanet['energy_ak'] > 0) {
			$need_en = ((abs($CurrentPlanet['energy_used']) - $CurrentPlanet['energy_max'])/3600)*$ProductionTime;
			if ($CurrentPlanet['energy_ak'] > $need_en) {
				$production_level = 100;
				$CurrentPlanet['energy_ak'] -= round($need_en, 2);
			} else {
				$production_level = round((($CurrentPlanet['energy_max'] + $CurrentPlanet['energy_ak']*3600) / abs($CurrentPlanet['energy_used'])) * 100, 1);
				$CurrentPlanet['energy_ak'] = 0;
			}
		} else {
			$production_level = round(($CurrentPlanet['energy_max'] / abs($CurrentPlanet['energy_used'])) * 100, 1);
		}
	}


	if ($production_level > 100) {
		$production_level = 100;
	} elseif ($production_level < 0) {
		$production_level = 0;
	}

	if ( $CurrentPlanet['metal'] <= $MaxMetalStorage ) {
		$MetalProduction = (($ProductionTime * ($CurrentPlanet['metal_perhour'] / 3600))) * (0.01 * $production_level);
		$MetalBaseProduc = (($ProductionTime * ($game_config['metal_basic_income'] / 3600 )) * $game_config['resource_multiplier']);
		$MetalTheorical  = $CurrentPlanet['metal'] + $MetalProduction  +  $MetalBaseProduc;
		if ( $MetalTheorical <= $MaxMetalStorage ) {
			$CurrentPlanet['metal']  = $MetalTheorical;
		} else {
			$CurrentPlanet['metal']  = $MaxMetalStorage;
		}
	}

	if ( $CurrentPlanet['crystal'] <= $MaxCristalStorage ) {
		$CristalProduction = (($ProductionTime * ($CurrentPlanet['crystal_perhour'] / 3600))) * (0.01 * $production_level);
		$CristalBaseProduc = (($ProductionTime * ($game_config['crystal_basic_income'] / 3600 )) * $game_config['resource_multiplier']);
		$CristalTheorical  = $CurrentPlanet['crystal'] + $CristalProduction  +  $CristalBaseProduc;
		if ( $CristalTheorical <= $MaxCristalStorage ) {
			$CurrentPlanet['crystal']  = $CristalTheorical;
		} else {
			$CurrentPlanet['crystal']  = $MaxCristalStorage;
		}
	}

	if ( $CurrentPlanet['deuterium'] <= $MaxDeuteriumStorage ) {
		$DeuteriumProduction = (($ProductionTime * ($CurrentPlanet['deuterium_perhour'] / 3600))) * (0.01 * $production_level);
		$DeuteriumBaseProduc = (($ProductionTime * ($game_config['deuterium_basic_income'] / 3600 )) * $game_config['resource_multiplier']);
		$DeuteriumTheorical  = $CurrentPlanet['deuterium'] + $DeuteriumProduction  +  $DeuteriumBaseProduc;
		if ( $DeuteriumTheorical <= $MaxDeuteriumStorage ) {
			$CurrentPlanet['deuterium']  = $DeuteriumTheorical;
		} else {
			$CurrentPlanet['deuterium']  = $MaxDeuteriumStorage;
		}
	}

	$CurrentPlanet['metal_perhour'] 	= round($CurrentPlanet['metal_perhour']* (0.01 * $production_level));
	$CurrentPlanet['crystal_perhour'] 	= round($CurrentPlanet['crystal_perhour']* (0.01 * $production_level));
	$CurrentPlanet['deuterium_perhour']= round($CurrentPlanet['deuterium_perhour']* (0.01 * $production_level));

	if ($Simul == false) {
		$Builded          = HandleElementBuildingQueue ( $CurrentUser, $CurrentPlanet, $ProductionTime );

		$QryUpdatePlanet  = "UPDATE {{table}} SET ";
		$QryUpdatePlanet .= "`metal` = '"            . $CurrentPlanet['metal']             ."', ";
		$QryUpdatePlanet .= "`crystal` = '"          . $CurrentPlanet['crystal']           ."', ";
		$QryUpdatePlanet .= "`deuterium` = '"        . $CurrentPlanet['deuterium']         ."', ";
		$QryUpdatePlanet .= "`last_update` = '"      . $CurrentPlanet['last_update']       ."', ";
		$QryUpdatePlanet .= "`b_hangar_id` = '"      . $CurrentPlanet['b_hangar_id']       ."', ";
		$QryUpdatePlanet .= "`energy_ak` = '"        . $CurrentPlanet['energy_ak']        ."', ";

		if ( $Builded != '' ) {
			foreach ( $Builded as $Element => $Count ) {
				if ($Element <> '') {
					$QryUpdatePlanet .= "`". $resource[$Element] ."` = '". $CurrentPlanet[$resource[$Element]] ."', ";
				}
			}
		}
		
		$QryUpdatePlanet .= "`b_hangar` = '". $CurrentPlanet['b_hangar'] ."' WHERE `id` = '". $CurrentPlanet['id'] ."';";
        
		doquery($QryUpdatePlanet, 'planets');
	}
}

?>