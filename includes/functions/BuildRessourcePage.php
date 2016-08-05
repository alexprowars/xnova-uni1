<?php

function BuildRessourcePage ( $CurrentUser, $CurrentPlanet ) {
	global $lang, $ProdGrid, $resource, $reslist, $game_config, $_POST;

	CheckPlanetUsedFields ( $CurrentPlanet );

	$RessBodyTPL = gettemplate('resources');
	$RessRowTPL  = gettemplate('resources_row');

	if ($CurrentPlanet['planet_type'] == 3 || $CurrentPlanet['planet_type'] == 5) {
		$game_config['metal_basic_income']     = 0;
		$game_config['crystal_basic_income']   = 0;
		$game_config['deuterium_basic_income'] = 0;
	}

	$ValidList['percent'] = array (  0,  10,  20,  30,  40,  50,  60,  70,  80,  90, 100 );
	$SubQry               = "";
	if ($_POST) {

	if ($CurrentUser['urlaubs_modus_time'] > 0) {
		message("¬ключен режим отпуска!");
	}

		foreach($_POST as $Field => $Value) {
			$FieldName = $Field."_porcent";
			if ( isset( $CurrentPlanet[ $FieldName ] ) ) {
				if ( ! in_array( $Value, $ValidList['percent']) ) {
					header("Location: ?set=overview");
					exit;
				}

				$Values                       = $Value / 10;
				$CurrentPlanet[ $FieldName ]  = $Values;
				$SubQry                      .= ", `".$FieldName."` = '".$Values."'";
			}
		}
	}

	$parse  = $lang;

	$production_level = 100;
	if ($CurrentPlanet['energy_max'] == 0) {
		$production_level = 0;
	} elseif ($CurrentPlanet['energy_max'] >= abs($CurrentPlanet['energy_used'])) {
		$production_level = 100;
		$akk_add = round(($CurrentPlanet['energy_max'] - abs($CurrentPlanet['energy_used']))*($ProductionTime/3600));
		if ($MaxEnergyStorage > ($CurrentPlanet['energy_ak'] + $akk_add))
			$CurrentPlanet['energy_ak'] += $akk_add;
		else
			$CurrentPlanet['energy_ak'] = $MaxEnergyStorage;
	} else {
		if ($CurrentPlanet['energy_ak'] > 0) {
			$need_en = ((abs($CurrentPlanet['energy_used']) - $CurrentPlanet['energy_max'])/3600)*$ProductionTime;
			if ($CurrentPlanet['energy_ak'] > $need_en) {
				$production_level = 100;
				$CurrentPlanet['energy_ak'] -= round($need_en);
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
	}

	if ($CurrentUser['rpg_geologue'] > time()) { $bonus_g = 25; $bonus_h = 25; }
	if ($CurrentUser['rpg_ingenieur'] > time()) $bonus_i = 15;

	// -------------------------------------------------------------------------------------------------------
	// Mise a jour de l'espace de stockage

	$CurrentPlanet['metal_max']     	= floor((BASE_STORAGE_SIZE + floor (50000 * round(pow (1.6, $CurrentPlanet[ $resource[22] ] )))) * (1 + $bonus_h * 0.01));
	$CurrentPlanet['crystal_max']   	= floor((BASE_STORAGE_SIZE + floor (50000 * round(pow (1.6, $CurrentPlanet[ $resource[23] ] )))) * (1 + $bonus_h * 0.01));
	$CurrentPlanet['deuterium_max'] 	= floor((BASE_STORAGE_SIZE + floor (50000 * round(pow (1.6, $CurrentPlanet[ $resource[24] ] )))) * (1 + $bonus_h * 0.01));

	// -------------------------------------------------------------------------------------------------------
	$parse['resource_row']               = "";
	$CurrentPlanet['metal_perhour']      = 0;
	$CurrentPlanet['crystal_perhour']    = 0;
	$CurrentPlanet['deuterium_perhour']  = 0;
	$CurrentPlanet['energy_max']         = 0;
	$CurrentPlanet['energy_used']        = 0;
	$BuildTemp = $CurrentPlanet[ 'temp_max' ];

   foreach($reslist['prod'] as $ProdID) {
      if ($CurrentPlanet[$resource[$ProdID]] > 0 && isset($ProdGrid[$ProdID])) {
         $BuildLevelFactor                    = $CurrentPlanet[ $resource[$ProdID]."_porcent" ];
         $BuildLevel                          = $CurrentPlanet[ $resource[$ProdID] ];
         $metal     = floor( eval ( $ProdGrid[$ProdID]['formule']['metal']     ) * ( $game_config['resource_multiplier'] ) * ( 1 + ( $bonus_g  * 0.01 ) ) );
         $crystal   = floor( eval ( $ProdGrid[$ProdID]['formule']['crystal']   ) * ( $game_config['resource_multiplier'] ) * ( 1 + ( $bonus_g  * 0.01 ) ) );
         $deuterium = floor( eval ( $ProdGrid[$ProdID]['formule']['deuterium'] ) * ( $game_config['resource_multiplier'] ) * ( 1 + ( $bonus_g  * 0.01 ) ) );
         $energy    = floor( eval ( $ProdGrid[$ProdID]['formule']['energy']    ) * ( $game_config['resource_multiplier'] ) );
         if ($energy > 0) {
            $CurrentPlanet['energy_max']    += ( $energy * ( 1 + ( $bonus_i * 0.01 ) ) );
         } else {
            $CurrentPlanet['energy_used']   += $energy;
         }
         $CurrentPlanet['metal_perhour']     += $metal;
         $CurrentPlanet['crystal_perhour']   += $crystal;
         $CurrentPlanet['deuterium_perhour'] += $deuterium;

			$metal_ref                               = $metal     * 0.01 * $production_level;
			$crystal_ref                             = $crystal   * 0.01 * $production_level;
			$deuterium_ref                           = $deuterium * 0.01 * $production_level;
			$darkmat_ref                           = $darkmat * 0.01 * $production_level;
			if ($ProdID == 4 || $ProdID == 212 || $ProdID == 12) $energy_ref  = $energy * ( 1 + ( $bonus_i * 0.01 ) );
			else $energy_ref = $energy * 0.01 * $production_level;
			$Field                               = $resource[$ProdID] ."_porcent";
			$CurrRow                             = array();
			$CurrRow['name']                     = $resource[$ProdID];
			$CurrRow['porcent']                  = $CurrentPlanet[$Field];
			for ($Option = 10; $Option >= 0; $Option--) {
				$OptValue = $Option * 10;
				if ($Option == $CurrRow['porcent']) {
					$OptSelected    = " selected=selected";
				} else {
					$OptSelected    = "";
				}
				$CurrRow['option'] .= "<option value=\"".$OptValue."\"".$OptSelected.">".$OptValue."%</option>";
			}
			$CurrRow['type']                     = $lang['tech'][$ProdID];
			$CurrRow['level']                    = ($ProdID > 200) ? $lang['quantity'] : $lang['level'];
			$CurrRow['level_type']               = $CurrentPlanet[ $resource[$ProdID] ];
			$metal_type                          = pretty_number ( abs($metal_ref)     );
			$crystal_type                        = pretty_number ( abs($crystal_ref)   );
			$deuterium_type                      = pretty_number ( $deuterium_ref );
			$CurrRow['energy_type']              = pretty_number ( $energy_ref    ) ;
			$CurrRow['metal_type']               = colorNumber ( $metal_type     );
			$CurrRow['crystal_type']             = colorNumber ( $crystal_type   );
			$CurrRow['deuterium_type']           = colorNumber ( $deuterium_type );
			$CurrRow['energy_type']              = colorNumber ( $CurrRow['energy_type']    );

			$parse['resource_row']              .= parsetemplate ( $RessRowTPL, $CurrRow );
		}
	}

	$parse['Production_of_resources_in_the_planet'] = str_replace('%s', $CurrentPlanet['name'], $lang['Production_of_resources_in_the_planet']);

	$parse['metal_basic_income']     = $game_config['metal_basic_income']     * $game_config['resource_multiplier'];
	$parse['crystal_basic_income']   = $game_config['crystal_basic_income']   * $game_config['resource_multiplier'];
	$parse['deuterium_basic_income'] = $game_config['deuterium_basic_income'] * $game_config['resource_multiplier'];
	$parse['energy_basic_income']    = $game_config['energy_basic_income']    * $game_config['resource_multiplier'];

	if ($CurrentPlanet['metal_max'] < $CurrentPlanet['metal']) {
		$parse['metal_max']         = "<font color=\"#ff0000\">";
	} else {
		$parse['metal_max']         = "<font color=\"#00ff00\">";
	}
	$parse['metal_max']            .= pretty_number($CurrentPlanet['metal_max'] / 1000) ." ". $lang['k']."</font>";

	if ($CurrentPlanet['crystal_max'] < $CurrentPlanet['crystal']) {
		$parse['crystal_max']       = "<font color=\"#ff0000\">";
	} else {
		$parse['crystal_max']       = "<font color=\"#00ff00\">";
	}
	$parse['crystal_max']          .= pretty_number($CurrentPlanet['crystal_max'] / 1000) ." ". $lang['k']."</font>";

	if ($CurrentPlanet['deuterium_max'] < $CurrentPlanet['deuterium']) {
		$parse['deuterium_max']     = "<font color=\"#ff0000\">";
	} else {
		$parse['deuterium_max']     = "<font color=\"#00ff00\">";
	}
	$parse['deuterium_max']        .= pretty_number($CurrentPlanet['deuterium_max'] / 1000) ." ". $lang['k']."</font>";

	$metal_total           = abs(floor( $CurrentPlanet['metal_perhour']     * 0.01 * $production_level )) + $parse['metal_basic_income'];
	$crystal_total         = abs(floor( $CurrentPlanet['crystal_perhour']   * 0.01 * $production_level )) + $parse['crystal_basic_income'];
	$deuterium_total       = abs(floor( $CurrentPlanet['deuterium_perhour'] * 0.01 * $production_level )) + $parse['deuterium_basic_income'];
	$parse['energy_total']          = colorNumber( pretty_number( floor( ( $CurrentPlanet['energy_max'] + $parse['energy_basic_income'] ) + $CurrentPlanet['energy_used'])));
	$parse['energy_max'] = pretty_number( floor($CurrentPlanet['energy_max']));
	$parse['metal_total']           = colorNumber(pretty_number($metal_total));
	$parse['crystal_total']         = colorNumber(pretty_number($crystal_total));
	$parse['deuterium_total']       = colorNumber(pretty_number($deuterium_total));

	$parse['daily_metal']           = colorNumber(pretty_number($metal_total * 24));
	$parse['weekly_metal']          = colorNumber(pretty_number($metal_total * 24 * 7));
	$parse['monthly_metal']         = colorNumber(pretty_number($metal_total * 24 * 30));

	$parse['daily_crystal']         = colorNumber(pretty_number($crystal_total * 24));
	$parse['weekly_crystal']        = colorNumber(pretty_number($crystal_total * 24 * 7));
	$parse['monthly_crystal']       = colorNumber(pretty_number($crystal_total * 24 * 30));

	$parse['daily_deuterium']       = colorNumber(pretty_number($deuterium_total * 24));
	$parse['weekly_deuterium']      = colorNumber(pretty_number($deuterium_total * 24 * 7));
	$parse['monthly_deuterium']     = colorNumber(pretty_number($deuterium_total * 24 * 30));

	$parse['metal_storage']         = floor($CurrentPlanet['metal']     / $CurrentPlanet['metal_max']     * 100) . $lang['o/o'];
	$parse['crystal_storage']       = floor($CurrentPlanet['crystal']   / $CurrentPlanet['crystal_max']   * 100) . $lang['o/o'];
	$parse['deuterium_storage']     = floor($CurrentPlanet['deuterium'] / $CurrentPlanet['deuterium_max'] * 100) . $lang['o/o'];

	$parse['metal_storage_bar']     = floor(($CurrentPlanet['metal']     / $CurrentPlanet['metal_max']     * 100) * 2.5);
	$parse['crystal_storage_bar']   = floor(($CurrentPlanet['crystal']   / $CurrentPlanet['crystal_max']   * 100) * 2.5);
	$parse['deuterium_storage_bar'] = floor(($CurrentPlanet['deuterium'] / $CurrentPlanet['deuterium_max'] * 100) * 2.5);

	if ($parse['metal_storage_bar'] > (100 * 2.5)) {
		$parse['metal_storage_bar'] = 250;
		$parse['metal_storage_barcolor'] = '#C00000';
	} elseif ($parse['metal_storage_bar'] > (80 * 2.5)) {
		$parse['metal_storage_barcolor'] = '#C0C000';
	} else {
		$parse['metal_storage_barcolor'] = '#00C000';
	}

	if ($parse['crystal_storage_bar'] > (100 * 2.5)) {
		$parse['crystal_storage_bar'] = 250;
		$parse['crystal_storage_barcolor'] = '#C00000';
	} elseif ($parse['crystal_storage_bar'] > (80 * 2.5)) {
		$parse['crystal_storage_barcolor'] = '#C0C000';
	} else {
		$parse['crystal_storage_barcolor'] = '#00C000';
	}

	if ($parse['deuterium_storage_bar'] > (100 * 2.5)) {
		$parse['deuterium_storage_bar'] = 250;
		$parse['deuterium_storage_barcolor'] = '#C00000';
	} elseif ($parse['deuterium_storage_bar'] > (80 * 2.5)) {
		$parse['deuterium_storage_barcolor'] = '#C0C000';
	} else {
		$parse['deuterium_storage_barcolor'] = '#00C000';
	}

	$parse['production_level_bar'] = $production_level * 2.5;
	$parse['production_level']     = "{$production_level}%";
	$parse['production_level_barcolor'] = '#00ff00';

	$parse['et'] = $CurrentUser['energy_tech'];

	$QryUpdatePlanet  = "UPDATE {{table}} SET ";
	$QryUpdatePlanet .= "`id` = '". $CurrentPlanet['id'] ."' ";
	$QryUpdatePlanet .= $SubQry;
	$QryUpdatePlanet .= "WHERE ";
	$QryUpdatePlanet .= "`id` = '". $CurrentPlanet['id'] ."';";
	doquery( $QryUpdatePlanet, 'planets');

	$page = parsetemplate( $RessBodyTPL, $parse );

	display($page, '—ырьЄ');
}

?>