<?php

function PlanetSizeRandomiser ($Position, $HomeWorld = false, $Base = false) {
	global $game_config;

	if (!$HomeWorld && !$Base) {
		$ClassicBase      = 163;
		$SettingSize      = $game_config['initial_fields'];
		$PlanetRatio      = floor ( ($ClassicBase / $SettingSize) * 10000 ) / 100;
		$RandomMin        = array (  40,  50,  55, 100,  95,  80, 115, 120, 125,  75,  80,  85,  60,  40,  50);
		$RandomMax        = array (  90,  95,  95, 240, 240, 230, 180, 180, 190, 125, 120, 130, 160, 300, 150);
		$CalculMin        = floor ( $RandomMin[$Position - 1] + ( $RandomMin[$Position - 1] * $PlanetRatio ) / 100 );
		$CalculMax        = floor ( $RandomMax[$Position - 1] + ( $RandomMax[$Position - 1] * $PlanetRatio ) / 100 );
		$RandomSize       = mt_rand($CalculMin, $CalculMax);
		$MaxAddon         = mt_rand(0, 110);
		$MinAddon         = mt_rand(0, 100);
		$Addon            = ($MaxAddon - $MinAddon);
		$PlanetFields     = ($RandomSize + $abweichung);
	} else {
		if($HomeWorld) $PlanetFields     = $game_config['initial_fields'];
		else $PlanetFields = 10;
	}
	$PlanetSize           = ($PlanetFields ^ (14 / 1.5)) * 75;

	$return['diameter']   = $PlanetSize;
	$return['field_max']  = $PlanetFields;
	return $return;
}

function CreateOnePlanetRecord($Galaxy, $System, $Position, $PlanetOwnerID, $PlanetName = '', $HomeWorld = false, $Base = false) {
	global $lang;

	$QrySelectPlanet  = "SELECT	`id` ";
	$QrySelectPlanet .= "FROM {{table}} ";
	$QrySelectPlanet .= "WHERE ";
	$QrySelectPlanet .= "`galaxy` = '". $Galaxy ."' AND ";
	$QrySelectPlanet .= "`system` = '". $System ."' AND ";
	$QrySelectPlanet .= "`planet` = '". $Position ."';";
	$PlanetExist = doquery( $QrySelectPlanet, 'planets', true);

	if (!$PlanetExist) {
		$planet                      = PlanetSizeRandomiser ($Position, $HomeWorld, $Base);
		$planet['diameter']          = ($planet['field_max'] ^ (14 / 1.5)) * 75 ;
		$planet['metal']             = BUILD_METAL;
		$planet['crystal']           = BUILD_CRISTAL;
		$planet['deuterium']         = BUILD_DEUTERIUM;
		$planet['metal_perhour']     = $game_config['metal_basic_income'];
		$planet['crystal_perhour']   = $game_config['crystal_basic_income'];
		$planet['deuterium_perhour'] = $game_config['deuterium_basic_income'];

		$planet['galaxy'] = $Galaxy;
		$planet['system'] = $System;
		$planet['planet'] = $Position;

		if ($Position == 1 || $Position == 2 || $Position == 3) {
			$PlanetType         = array('trocken');
			$PlanetClass        = array('planet');
			$PlanetDesign       = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20');
			$planet['temp_min'] = rand(0, 100);
			$planet['temp_max'] = $planet['temp_min'] + 40;
		} elseif ($Position == 4 || $Position == 5 || $Position == 6) {
			$PlanetType         = array('dschjungel');
			$PlanetClass        = array('planet');
			$PlanetDesign       = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19');
			$planet['temp_min'] = rand(-25, 75);
			$planet['temp_max'] = $planet['temp_min'] + 40;
		} elseif ($Position == 7 || $Position == 8 || $Position == 9) {
			$PlanetType         = array('normaltemp');
			$PlanetClass        = array('planet');
			$PlanetDesign       = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15');
			$planet['temp_min'] = rand(-50, 50);
			$planet['temp_max'] = $planet['temp_min'] + 40;
		} elseif ($Position == 10 || $Position == 11 || $Position == 12) {
			$PlanetType         = array('wasser');
			$PlanetClass        = array('planet');
			$PlanetDesign       = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18');
			$planet['temp_min'] = rand(-75, 25);
			$planet['temp_max'] = $planet['temp_min'] + 40;
		} elseif ($Position == 13 || $Position == 14 || $Position == 15) {
			$PlanetType         = array('eis');
			$PlanetClass        = array('planet');
			$PlanetDesign       = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20');
			$planet['temp_min'] = rand(-100, 10);
			$planet['temp_max'] = $planet['temp_min'] + 40;
		} else {
			$PlanetType         = array('dschjungel', 'gas', 'normaltemp', 'trocken', 'wasser', 'wuesten', 'eis');
			$PlanetClass        = array('planet');
			$PlanetDesign       = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10');
			$planet['temp_min'] = rand(-120, 10);
			$planet['temp_max'] = $planet['temp_min'] + 40;
		}

		$planet['planet_type'] = 1;

		if ($Base == true){
			$PlanetType         = array('base');
			$PlanetClass        = array('planet');
			$PlanetDesign       = array('01');
			$planet['planet_type'] = 5;
		}

		$planet['image']       = $PlanetType[ rand( 0, count( $PlanetType ) -1 ) ];
		$planet['image']      .= $PlanetClass[ rand( 0, count( $PlanetClass ) - 1 ) ];
		$planet['image']      .= $PlanetDesign[ rand( 0, count( $PlanetDesign ) - 1 ) ];
		$planet['id_owner']    = $PlanetOwnerID;
		$planet['last_update'] = time();
		$planet['name']        = ($PlanetName == '') ? $lang['sys_colo_defaultname'] : $PlanetName;

		$QryInsertPlanet  = "INSERT INTO {{table}} SET ";
		$QryInsertPlanet .= "`name` = '".              $planet['name']              ."', ";
		$QryInsertPlanet .= "`id_owner` = '".          $planet['id_owner']          ."', ";
		$QryInsertPlanet .= "`galaxy` = '".            $planet['galaxy']            ."', ";
		$QryInsertPlanet .= "`system` = '".            $planet['system']            ."', ";
		$QryInsertPlanet .= "`planet` = '".            $planet['planet']            ."', ";
		$QryInsertPlanet .= "`last_update` = '".       $planet['last_update']       ."', ";
		$QryInsertPlanet .= "`planet_type` = '".       $planet['planet_type']       ."', ";
		$QryInsertPlanet .= "`image` = '".             $planet['image']             ."', ";
		$QryInsertPlanet .= "`diameter` = '".          $planet['diameter']          ."', ";
		$QryInsertPlanet .= "`field_max` = '".         $planet['field_max']         ."', ";
		$QryInsertPlanet .= "`temp_min` = '".          $planet['temp_min']          ."', ";
		$QryInsertPlanet .= "`temp_max` = '".          $planet['temp_max']          ."', ";
		$QryInsertPlanet .= "`metal` = '".             $planet['metal']             ."', ";
		$QryInsertPlanet .= "`crystal` = '".           $planet['crystal']           ."', ";
		$QryInsertPlanet .= "`deuterium` = '".         $planet['deuterium']         ."'; ";
		doquery( $QryInsertPlanet, 'planets');

		$QrySelectPlanet  = "SELECT `id` ";
		$QrySelectPlanet .= "FROM {{table}} ";
		$QrySelectPlanet .= "WHERE ";
		$QrySelectPlanet .= "`galaxy` = '".   $planet['galaxy']   ."' AND ";
		$QrySelectPlanet .= "`system` = '".   $planet['system']   ."' AND ";
		$QrySelectPlanet .= "`planet` = '".   $planet['planet']   ."' AND ";
		$QrySelectPlanet .= "`id_owner` = '". $planet['id_owner'] ."';";
		$GetPlanetID      = doquery( $QrySelectPlanet , 'planets', true);

		$QrySelectGalaxy  = "SELECT * ";
		$QrySelectGalaxy .= "FROM {{table}} ";
		$QrySelectGalaxy .= "WHERE ";
		$QrySelectGalaxy .= "`galaxy` = '". $planet['galaxy'] ."' AND ";
		$QrySelectGalaxy .= "`system` = '". $planet['system'] ."' AND ";
		$QrySelectGalaxy .= "`planet` = '". $planet['planet'] ."';";
		$GetGalaxyID      = doquery( $QrySelectGalaxy, 'galaxy', true);

		if ($GetGalaxyID) {
			$QryUpdateGalaxy  = "UPDATE {{table}} SET ";
			$QryUpdateGalaxy .= "`id_planet` = '". $GetPlanetID['id'] ."' ";
			$QryUpdateGalaxy .= "WHERE ";
			$QryUpdateGalaxy .= "`galaxy` = '". $planet['galaxy'] ."' AND ";
			$QryUpdateGalaxy .= "`system` = '". $planet['system'] ."' AND ";
			$QryUpdateGalaxy .= "`planet` = '". $planet['planet'] ."';";
			doquery( $QryUpdateGalaxy, 'galaxy');
		} else {
			$QryInsertGalaxy  = "INSERT INTO {{table}} SET ";
			$QryInsertGalaxy .= "`galaxy` = '". $planet['galaxy'] ."', ";
			$QryInsertGalaxy .= "`system` = '". $planet['system'] ."', ";
			$QryInsertGalaxy .= "`planet` = '". $planet['planet'] ."', ";
			$QryInsertGalaxy .= "`id_planet` = '". $GetPlanetID['id'] ."';";
			doquery( $QryInsertGalaxy, 'galaxy');
		}

		$RetValue = true;
	} else {

		$RetValue = false;
	}

	return $RetValue;
}
?>