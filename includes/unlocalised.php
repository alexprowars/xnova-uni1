<?php
function GetTargetDistance ($OrigGalaxy, $DestGalaxy, $OrigSystem, $DestSystem, $OrigPlanet, $DestPlanet) {
	$distance = 0;

	if (($OrigGalaxy - $DestGalaxy) != 0) {
		$distance = abs($OrigGalaxy - $DestGalaxy) * 20000;
	} elseif (($OrigSystem - $DestSystem) != 0) {
		$distance = abs($OrigSystem - $DestSystem) * 5 * 19 + 2700;
	} elseif (($OrigPlanet - $DestPlanet) != 0) {
		$distance = abs($OrigPlanet - $DestPlanet) * 5 + 1000;
	} else {
		$distance = 5;
	}

	return $distance;
}


function GetMissionDuration ($GameSpeed, $MaxFleetSpeed, $Distance, $SpeedFactor) {
	$Duration = 0;
	$Duration = round(((35000 / $GameSpeed * sqrt($Distance * 10 / $MaxFleetSpeed) + 10) / $SpeedFactor));

	return $Duration;
}


function GetGameSpeedFactor () {
	global $game_config;

	return $game_config['fleet_speed'] / 2500;
}

// ----------------------------------------------------------------------------------------------------------------

function GetFleetMaxSpeed ($FleetArray, $Fleet, $Player) {
	global $reslist, $pricelist;

	if ($Fleet != 0) {
		$FleetArray[$Fleet] =  1;
	}
	foreach ($FleetArray as $Ship => $Count) {
		if ($Ship == 202) {
			if ($Player['impulse_motor_tech'] >= 5) {
				$speedalls[$Ship] = $pricelist[$Ship]['speed2'] + (($pricelist[$Ship]['speed2'] * $Player['impulse_motor_tech']) * 0.2);
			} else {
				$speedalls[$Ship] = $pricelist[$Ship]['speed']  + (($pricelist[$Ship]['speed'] * $Player['combustion_tech']) * 0.1);
			}
		}
		if ($Ship == 203 or $Ship == 204 or $Ship == 209 or $Ship == 210) {
			$speedalls[$Ship] = $pricelist[$Ship]['speed'] + (($pricelist[$Ship]['speed'] * $Player['combustion_tech']) * 0.1);
		}
		if ($Ship == 205 or $Ship == 206 or $Ship == 208) {
			$speedalls[$Ship] = $pricelist[$Ship]['speed'] + (($pricelist[$Ship]['speed'] * $Player['impulse_motor_tech']) * 0.2);
		}
		if ($Ship == 211) {
			if ($Player['hyperspace_motor_tech'] >= 8) {
				$speedalls[$Ship] = $pricelist[$Ship]['speed2'] + (($pricelist[$Ship]['speed2'] * $Player['hyperspace_motor_tech']) * 0.3);
			} else {
				$speedalls[$Ship] = $pricelist[$Ship]['speed']  + (($pricelist[$Ship]['speed'] * $Player['impulse_motor_tech']) * 0.2);
			}
		}
		if ($Ship == 207 or $Ship == 213 or $Ship == 214 or $Ship == 215 or $Ship == 216 or $Ship == 217) {
			$speedalls[$Ship] = $pricelist[$Ship]['speed'] + (($pricelist[$Ship]['speed'] * $Player['hyperspace_motor_tech']) * 0.3);
		}
		if ($Player['rpg_admiral'] > time()) $speedalls[$Ship] = round($speedalls[$Ship] * 1.25);
	}
	if ($Fleet != 0) {
		$ShipSpeed = $speedalls[$Ship];
		$speedalls = $ShipSpeed;
	}

	return $speedalls;
}

// ----------------------------------------------------------------------------------------------------------------

function GetShipConsumption ( $Ship, $Player ) {
	global $pricelist;
	if ($Player['impulse_motor_tech'] >= 5) {
		$Consumption  = $pricelist[$Ship]['consumption2'];
	} else {
		$Consumption  = $pricelist[$Ship]['consumption'];
	}

	return $Consumption;
}

// ----------------------------------------------------------------------------------------------------------------

function GetFleetConsumption ($FleetArray, $SpeedFactor, $MissionDuration, $MissionDistance, $FleetMaxSpeed, $Player) {

	$consumption = 0;
	$basicConsumption = 0;

	foreach ($FleetArray as $Ship => $Count) {
		if ($Ship > 0) {
			$ShipSpeed         = GetFleetMaxSpeed ( "", $Ship, $Player );
			$ShipConsumption   = GetShipConsumption ( $Ship, $Player );
			$spd               = 35000 / ($MissionDuration * $SpeedFactor - 10) * sqrt( $MissionDistance * 10 / $ShipSpeed );
			$basicConsumption  = $ShipConsumption * $Count;
			$consumption      += $basicConsumption * $MissionDistance / 35000 * (($spd / 10) + 1) * (($spd / 10) + 1);
		}
	}

	$consumption = round($consumption) + 1;

	return $consumption;
}

// ----------------------------------------------------------------------------------------------------------------

function GetFleetStay ($FleetArray) {
	global $pricelist;

	$stay = 0;
	foreach ($FleetArray as $Ship => $Count) {
		if ($Ship > 0) {
			$stay      += $pricelist[$Ship]['stay'] * $Count;
		}
	}
	return $stay;
}

// ----------------------------------------------------------------------------------------------------------------

function pretty_time ($seconds) {
	$day = floor($seconds / (24 * 3600));
	$hs = floor($seconds / 3600 % 24);
	$ms = floor($seconds / 60 % 60);
	$sr = floor($seconds / 1 % 60);

	if ($hs < 10) { $hh = "0" . $hs; } else { $hh = $hs; }
	if ($ms < 10) { $mm = "0" . $ms; } else { $mm = $ms; }
	if ($sr < 10) { $ss = "0" . $sr; } else { $ss = $sr; }

	$time = '';
	if ($day != 0) { $time .= $day . 'д '; }
	if ($hs  != 0) { $time .= $hh . 'ч ';  } else { $time .= '00ч '; }
	if ($ms  != 0) { $time .= $mm . 'м ';  } else { $time .= '00м '; }
	$time .= $ss . 'с';

	return $time;
}


function pretty_time_hour ($seconds) {
	$min = floor($seconds / 60 % 60);

	$time = '';
	if ($min != 0) { $time .= $min . 'min '; }

	return $time;
}


function ShowBuildTime ($time) {
	global $lang;

	return "<br><b>". $lang['ConstructionTime'] ."</b>: " . pretty_time($time);
}

// ----------------------------------------------------------------------------------------------------------------

function ReadFromFile($filename) {
	$content = @file_get_contents ($filename);
	return $content;
}

function SaveToFile ($filename, $content) {
	$content = @file_put_contents ($filename, $content);
}

function parsetemplate ($template, $array) {
	return preg_replace('#\{([a-z0-9\-_]*?)\}#Ssie', '( ( isset($array[\'\1\']) ) ? $array[\'\1\'] : \'\' );', $template);
}

function gettemplate ($templatename) {
	global $ugamela_root_path;

	$filename = $ugamela_root_path . TEMPLATE_DIR . TEMPLATE_NAME . '/' . $templatename . ".tpl";

	return ReadFromFile($filename);
}

// ----------------------------------------------------------------------------------------------------------------

function includeLang ($filename, $ext = '.php') {
	global $lang, $user;

	include ("language/". DEFAULT_LANG ."/". $filename.$ext);
}


// ----------------------------------------------------------------------------------------------------------------
//

function GetStartAdressLink ( $FleetRow, $FleetType ) {
	$Link  = "<a href=\"?set=galaxy&amp;mode=3&amp;galaxy=".$FleetRow['fleet_start_galaxy']."&amp;system=".$FleetRow['fleet_start_system']."\" ". $FleetType ." >";
	$Link .= "[".$FleetRow['fleet_start_galaxy'].":".$FleetRow['fleet_start_system'].":".$FleetRow['fleet_start_planet']."]</a>";
	return $Link;
}


function GetTargetAdressLink ( $FleetRow, $FleetType ) {
	$Link  = "<a href=\"?set=galaxy&amp;mode=3&amp;galaxy=".$FleetRow['fleet_end_galaxy']."&amp;system=".$FleetRow['fleet_end_system']."\" ". $FleetType ." >";
	$Link .= "[".$FleetRow['fleet_end_galaxy'].":".$FleetRow['fleet_end_system'].":".$FleetRow['fleet_end_planet']."]</a>";
	return $Link;
}


function BuildPlanetAdressLink ( $CurrentPlanet ) {
	$Link  = "<a href=\"?set=galaxy&amp;mode=3&amp;galaxy=".$CurrentPlanet['galaxy']."&amp;system=".$CurrentPlanet['system']."\">";
	$Link .= "[".$CurrentPlanet['galaxy'].":".$CurrentPlanet['system'].":".$CurrentPlanet['planet']."]</a>";
	return $Link;
}


function BuildHostileFleetPlayerLink ( $FleetRow ) {
	global $lang, $dpath;

	$PlayerName = doquery ("SELECT `username` FROM {{table}} WHERE `id` = '". $FleetRow['fleet_owner']."';", 'users', true);
	$Link  = $PlayerName['username']. " ";
	$Link .= "<a href=\"?set=messages&amp;mode=write&amp;id=".$FleetRow['fleet_owner']."\">";
	$Link .= "<img src=\"".$dpath."/img/m.gif\" alt=\"". $lang['ov_message']."\" title=\"". $lang['ov_message']."\" border=\"0\"></a>";
	return $Link;
}

function GetNextJumpWaitTime ( $CurMoon ) {
	global $resource;

	$JumpGateLevel  = $CurMoon[$resource[43]];
	$LastJumpTime   = $CurMoon['last_jump_time'];
	if ($JumpGateLevel > 0) {
		$WaitBetweenJmp = (60 * 60) * (1 / $JumpGateLevel);
		$NextJumpTime   = $LastJumpTime + $WaitBetweenJmp;
		if ($NextJumpTime >= time()) {
			$RestWait   = $NextJumpTime - time();
			$RestString = " ". pretty_time($RestWait);
		} else {
			$RestWait   = 0;
			$RestString = "";
		}
	} else {
		$RestWait   = 0;
		$RestString = "";
	}
	$RetValue['string'] = $RestString;
	$RetValue['value']  = $RestWait;

	return $RetValue;
}

function InsertJavaScriptChronoApplet ( $Type, $Ref, $Value ) {
	
	$JavaString  = "<script>FlotenTime('bxx". $Type . $Ref ."', ". $Value .");</script>";

	return $JavaString;
}
// ----------------------------------------------------------------------------------------------------------------
//

function CreateFleetPopupedFleetLink ( $FleetRow, $Texte, $FleetType ) {
	global $lang, $user;

	$FleetRec     = explode(";", $FleetRow['fleet_array']);
	$FleetPopup   = "<a href='#' onmouseover=\"return overlib('";
	$FleetPopup  .= "<table width=200>";
	$Total = 0;
	if ($FleetRow['fleet_owner'] != $user['id'] && $user['spy_tech'] < 2){
		$FleetPopup .= "<tr><td width=100% align=center><font color=white>Нет информации<font></td></tr>";
	} elseif ($FleetRow['fleet_owner'] != $user['id'] && $user['spy_tech'] < 4) {
		foreach($FleetRec as $Item => $Group) {
			if ($Group  != '') {
				$Ship    = explode(",", $Group);
				$Total = $Total + $Ship[1];
			}
		}
		$FleetPopup .= "<tr><td width=50% align=left><font color=white>Численность:<font></td><td width=50% align=right><font color=white>". pretty_number($Total) ."<font></td></tr>";
	} elseif ($FleetRow['fleet_owner'] != $user['id'] && $user['spy_tech'] < 8) {
		foreach($FleetRec as $Item => $Group) {
			if ($Group  != '') {
				$Ship    = explode(",", $Group);
				$Total = $Total + $Ship[1];
				$FleetPopup .= "<tr><td width=100% align=center><font color=white>". $lang['tech'][$Ship[0]] ."<font></td></tr>";
			}
		}
		$FleetPopup .= "<tr><td width=50% align=left><font color=white>Численность:<font></td><td width=50% align=right><font color=white>". pretty_number($Total) ."<font></td></tr>";
	} else {
		foreach($FleetRec as $Item => $Group) {
			if ($Group  != '') {
				$Ship    = explode(",", $Group);
				$FleetPopup .= "<tr><td width=75% align=left><font color=white>". $lang['tech'][$Ship[0]] .":<font></td><td width=25% align=right><font color=white>". pretty_number($Ship[1]) ."<font></td></tr>";
			}
		}
	}
	$FleetPopup  .= "</table>";
	$FleetPopup  .= "');\" onmouseout=\"return nd();\" class=\"". $FleetType ."\">". $Texte ."</a>";

	return $FleetPopup;

}

// ----------------------------------------------------------------------------------------------------------------
//

function CreateFleetPopupedMissionLink ( $FleetRow, $Texte, $FleetType ) {
	global $lang;

	$FleetTotalC  = $FleetRow['fleet_resource_metal'] + $FleetRow['fleet_resource_crystal'] + $FleetRow['fleet_resource_deuterium'];
	if ($FleetTotalC <> 0) {
		$FRessource   = "<table width=200>";
		$FRessource  .= "<tr><td width=50% align=left><font color=white>". $lang['Metal'] ."<font></td><td width=50% align=right><font color=white>". pretty_number($FleetRow['fleet_resource_metal']) ."<font></td></tr>";
		$FRessource  .= "<tr><td width=50% align=left><font color=white>". $lang['Crystal'] ."<font></td><td width=50% align=right><font color=white>". pretty_number($FleetRow['fleet_resource_crystal']) ."<font></td></tr>";
		$FRessource  .= "<tr><td width=50% align=left><font color=white>". $lang['Deuterium'] ."<font></td><td width=50% align=right><font color=white>". pretty_number($FleetRow['fleet_resource_deuterium']) ."<font></td></tr>";
		$FRessource  .= "</table>";
	} else {
		$FRessource   = "";
	}

	if ($FRessource <> "") {
		$MissionPopup  = "<a href='#' onmouseover=\"return overlib('". $FRessource ."');";
		$MissionPopup .= "\" onmouseout=\"return nd();\" class=\"". $FleetType ."\">" . $Texte ."</a>";
	} else {
		$MissionPopup  = $Texte ."";
	}

	return $MissionPopup;
}

// ----------------------------------------------------------------------------------------------------------------


function GetBuildingTime ($user, $planet, $Element, $space_lab = 0) {
	global $pricelist, $resource, $reslist, $game_config;

	$bonus = 0;
	$bonus_t = 0;
	
	if ($user['rpg_constructeur'] > time()) 
		$bonus_t = 25;

	$level = (isset($planet[$resource[$Element]])) ? $planet[$resource[$Element]] : $user[$resource[$Element]];
	if       (in_array($Element, $reslist['build'])) {
		$cost_metal   = floor($pricelist[$Element]['metal']   * pow($pricelist[$Element]['factor'], $level));
		$cost_crystal = floor($pricelist[$Element]['crystal'] * pow($pricelist[$Element]['factor'], $level));
		$time         = (($cost_crystal + $cost_metal) / $game_config['game_speed']) * (1 / ($planet[$resource['14']] + 1)) * pow(0.5, $planet[$resource['15']]);
		$time         = floor(($time * 60 * 60) * (1 - ($bonus_t * 0.01)));

	} elseif (in_array($Element, $reslist['tech'])) {
		$cost_metal   = floor($pricelist[$Element]['metal']   * pow($pricelist[$Element]['factor'], $level));
		$cost_crystal = floor($pricelist[$Element]['crystal'] * pow($pricelist[$Element]['factor'], $level));
		$intergal_lab = $user[$resource[123]];
		if       ( $intergal_lab < 1 ) {
			$lablevel = $planet[$resource['31']];
		} elseif ( $intergal_lab >= 1 ) {
		
			$NbLabs = 0;
			
			if ($space_lab != 0) {
				foreach ($space_lab AS $colonie) {
					if ( IsTechnologieAccessible($user,$colonie, $Element) ) {
						$techlevel[$NbLabs] = $colonie[$resource['31']];
						$NbLabs++;
					}
				}
			}

			if ($NbLabs >= 1) {
				$lablevel = $planet[$resource['31']];
				for ($lab = 1; $lab <= $intergal_lab; $lab++) {
					asort($techlevel);
					$lablevel += $techlevel[$lab - 1];
				}
			} else
				$lablevel = $planet[$resource['31']];
		}
		if ($user['rpg_technocrate'] > time()) 
			$bonus = 25;
			
		$time         = (($cost_metal + $cost_crystal) / $game_config['game_speed']) / (($lablevel + 1) * 2);
		$time         = floor(($time * 60 * 60) * (1 - ($bonus * 0.01)));
	} elseif (in_array($Element, $reslist['defense'])) {
		$time         = (($pricelist[$Element]['metal'] + $pricelist[$Element]['crystal']) / $game_config['game_speed']) * (1 / ($planet[$resource['21']] + 1)) * pow(1 / 2, $planet[$resource['15']]);
		$time         = floor(($time * 60 * 60) * (1 - ($bonus_t  * 0.01)));
	} elseif (in_array($Element, $reslist['fleet'])) {
		$time         = (($pricelist[$Element]['metal'] + $pricelist[$Element]['crystal']) / $game_config['game_speed']) * (1 / ($planet[$resource['21']] + 1)) * pow(1 / 2, $planet[$resource['15']]);
		$time         = floor(($time * 60 * 60) * (1 - ($bonus_t  * 0.01)));
	}

	if ($time < 1) $time = 1;

	return $time;
}

function GetRestPrice ($user, $planet, $Element, $userfactor = true) {
	global $pricelist, $resource, $lang;

	if ($userfactor) {
		$level = (isset($planet[$resource[$Element]])) ? $planet[$resource[$Element]] : $user[$resource[$Element]];
	}

	$array = array(
		'metal'      => $lang["Metal"],
		'crystal'    => $lang["Crystal"],
		'deuterium'  => $lang["Deuterium"],
		'energy_max' => $lang["Energy"]
	);

	$text  = "<br><font color=\"#7f7f7f\">". $lang['Rest_ress'] .": ";
	foreach ($array as $ResType => $ResTitle) {
		if ($pricelist[$Element][$ResType] != 0) {
			$text .= $ResTitle . ": ";
			if ($userfactor) {
				$cost = floor($pricelist[$Element][$ResType] * pow($pricelist[$Element]['factor'], $level));
			} else {
				$cost = floor($pricelist[$Element][$ResType]);
			}
			if ($cost > $planet[$ResType]) {
				$text .= "<b style=\"color: rgb(127, 95, 96);\">". pretty_number($planet[$ResType] - $cost) ."</b> ";
			} else {
				$text .= "<b style=\"color: rgb(95, 127, 108);\">". pretty_number($planet[$ResType] - $cost) ."</b> ";
			}
		}
	}
	$text .= "</font>";

	return $text;
}


function GetElementPrice ($user, $planet, $Element, $userfactor = true) {
	global $pricelist, $resource, $lang, $dpath;

	if ($userfactor) {
		$level = (isset($planet[$resource[$Element]])) ? $planet[$resource[$Element]] : $user[$resource[$Element]];
	}

	$is_buyeable = true;
	$array = array(
		'metal'      	=> array($lang["Metal"], 'metall'),
		'crystal'    	=> array($lang["Crystal"], 'kristall'),
		'deuterium'  	=> array($lang["Deuterium"], 'deuterium'),
		'energy_max' 	=> array($lang["Energy"], 'energie')
	);

	$text = "<table width='100%'><tr>";
	foreach ($array as $ResType => $ResTitle) {
		if ($pricelist[$Element][$ResType] != 0) {
			if ($user['design'] == 1)
				$text .= "<td align='center'><img src='".$dpath."images/".$ResTitle[1].".gif' onmouseover=\"return overlib('<center>".$ResTitle[0]."</center>',LEFT,WIDTH,75,FGCOLOR,'#465673')\" onmouseout=\"nd()\"><br><br>";
			else
				$text .= "<td align='center'><b>".$ResTitle[0]."</b><br><br>";
			
			if ($userfactor) {
				$cost = floor($pricelist[$Element][$ResType] * pow($pricelist[$Element]['factor'], $level));
			} else {
				$cost = floor($pricelist[$Element][$ResType]);
				if ($user['rpg_admiral'] > time() && ($Element > 200 && $Element < 300))
					$cost = round($cost * 0.9);
				if ($user['rpg_ingenieur'] > time() && ($Element > 400 && $Element < 504))
					$cost = round($cost * 0.9);
			}
			if ($cost > $planet[$ResType]) {
				$text .= "<b style=\"color:red;\"> ";
				$text .= "<span class=\"noresources\">" . pretty_number($cost) . "</span></b> ";
				$is_buyeable = false;
			} else {
				$text .= "<b style=\"color:lime;\"> <span class=\"noresources\">" . pretty_number($cost) . "</span></b> ";
			}
			$text .= "</td>";
		}
	}
	$text .= "</table>";

	return $text;
}

function GetBuildingPrice ($CurrentUser, $CurrentPlanet, $Element, $Incremental = true, $ForDestroy = false) {
	global $pricelist, $resource;

	if ($Incremental) {
		$level = (isset($CurrentPlanet[$resource[$Element]])) ? $CurrentPlanet[$resource[$Element]] : $CurrentUser[$resource[$Element]];
	}

	$array = array('metal', 'crystal', 'deuterium', 'energy_max');
	foreach ($array as $ResType) {
		if ($Incremental) {
			$cost[$ResType] = floor($pricelist[$Element][$ResType] * pow($pricelist[$Element]['factor'], $level));
		} else {
			$cost[$ResType] = floor($pricelist[$Element][$ResType]);

			if ($CurrentUser['rpg_admiral'] > time() && ($Element > 200 && $Element < 300))
				$cost[$ResType] = round($cost[$ResType] * 0.9);
			if ($CurrentUser['rpg_ingenieur'] > time() && ($Element > 400 && $Element < 504))
				$cost[$ResType] = round($cost[$ResType] * 0.9);
		}

		if ($ForDestroy == true) {
			$cost[$ResType]  = floor($cost[$ResType] / 2);
		}
	}

	return $cost;
}

function GetNextProduction ($Element, $Level) {
	global $ProdGrid, $game_config, $user;
	
	$bonus_g = 0;
	$bonus_i = 0;

	if ($user['rpg_geologue'] > time())
		$bonus_g = 25;
	if ($user['rpg_ingenieur'] > time())
		$bonus_i = 15;

	$BuildLevelFactor 	= 10;
	$BuildLevel      	= $Level + 1;

	$Res['m']     	=  floor(eval($ProdGrid[$Element]['formule']['metal']) * $game_config['resource_multiplier'] * ( 1 + ( $bonus_g  * 0.01 ) ) );
	$Res['c']   	=  floor(eval($ProdGrid[$Element]['formule']['crystal']) * $game_config['resource_multiplier'] * ( 1 + ( $bonus_g  * 0.01 ) ) );
	$Res['d'] 		=  floor(eval($ProdGrid[$Element]['formule']['deuterium']) * $game_config['resource_multiplier'] * ( 1 + ( $bonus_g  * 0.01 ) ) );
	if ($Element < 4)
		$Res['en']	=  floor(eval($ProdGrid[$Element]['formule']['energy']) * $game_config['resource_multiplier']);
	elseif ($Element >= 4 )
		$Res['em']  =  floor(eval($ProdGrid[$Element]['formule']['energy']) * $game_config['resource_multiplier'] * ( 1 + ( $bonus_i  * 0.01 ) ) );  


	$BuildLevelFactor 	= 10;
	$BuildLevel      	= $Level;

	$Res['m']     	-=  floor(eval($ProdGrid[$Element]['formule']['metal']) * $game_config['resource_multiplier'] * ( 1 + ( $bonus_g  * 0.01 ) ) );
	$Res['c']   	-=  floor(eval($ProdGrid[$Element]['formule']['crystal']) * $game_config['resource_multiplier'] * ( 1 + ( $bonus_g  * 0.01 ) ) );
	$Res['d'] 		-=  floor(eval($ProdGrid[$Element]['formule']['deuterium']) * $game_config['resource_multiplier'] * ( 1 + ( $bonus_g  * 0.01 ) ) );
	if ($Element < 4)
		$Res['en']	-=  floor(eval($ProdGrid[$Element]['formule']['energy']) * $game_config['resource_multiplier']);
	elseif ($Element >= 4 )
		$Res['em']  -=  floor(eval($ProdGrid[$Element]['formule']['energy']) * $game_config['resource_multiplier'] * ( 1 + ( $bonus_i  * 0.01 ) ) );  


	$text = "";

	if ($Res['m'] != 0) {
		$text .= "<br>Металл: ";
		if ($Res['m'] > 0)
			$text .= "<font color=#00FF00>+".$Res['m']."</font>";
		else
			$text .= "<font color=#FF0000>".$Res['m']."</font>";
	}
	if ($Res['c'] != 0) {
		$text .= "<br>Кристалл: ";
		if ($Res['c'] > 0)
			$text .= "<font color=#00FF00>+".$Res['c']."</font>";
		else
			$text .= "<font color=#FF0000>".$Res['c']."</font>";
	}
	if ($Res['d'] != 0) {
		$text .= "<br>Дейтерий: ";
		if ($Res['d'] > 0)
			$text .= "<font color=#00FF00>+".$Res['d']."</font>";
		else
			$text .= "<font color=#FF0000>".$Res['d']."</font>";
	}
	if ($Res['em'] != 0) {
		$text .= "<br>Энергия: ";
		if ($Res['em'] > 0)
			$text .= "<font color=#00FF00>+".$Res['em']."</font>";
		else
			$text .= "<font color=#FF0000>".$Res['em']."</font>";
	}
	if ($Res['en'] != 0) {
		$text .= "<br>Расход эн.: ";
		if ($Res['en'] > 0)
			$text .= "<font color=#00FF00>+".$Res['en']."</font>";
		else
			$text .= "<font color=#FF0000>".$Res['en']."</font>";
	}

	if ($text != "")
		$text = "<b>Производство:</b>".$text;

	return $text;
}

?>