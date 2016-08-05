<?php

function formatCR ($result_array, $steal_array, $moon_int, $moon_string) {
	global $lang;

	$html = "<table><tr><td>";
	$bbc = 0;

	$html .= "В ".date("d-m-Y H:i:s")." произошёл бой между следующими флотами:<br><br>";

	$round_no = 1;
	foreach( $result_array['rw'] as $round => $data1){

		$attackers1 = $data1['attackers'];
		$attackers2 = $data1['infoA'];
		$defenders1 = $data1['defenders'];
		$defenders2 = $data1['infoD'];
		$coord4 = 0;
		$coord5 = 0;
		$coord6 = 0;

		$html .= "<table width=100%><tr>";

		foreach( $attackers1 as $fleet_id1 => $data2){

			$html .= "<td><table border=1 width=100%><tr><th><center>";
			$html .= "Атакующий ".$data2['user']['username']." ([".$data2['fleet'][0].":".$data2['fleet'][1].":".$data2['fleet'][2]."])<br>";
			$html .= "Вооружение: ".($data2['user']['military_tech'] * 10)."% Щиты: ".($data2['user']['shield_tech'] * 10)."% Броня: ".($data2['user']['defence_tech'] * 10)."%";

			$html  .= "<table border=1>";

			if ($data1['attackA'][$fleet_id1] > 0) {
				$raport1  = "<tr><th>Тип</th>";
				$raport2  = "<tr><th>Кол-во</th>";
				$raport3  = "<tr><th>Вооружение</th>";
				$raport4  = "<tr><th>Щиты</th>";
				$raport5  = "<tr><th>Броня</th>";

				foreach( $data2['detail'] as $ship_id1 => $ship_count1){
					if ($ship_count1 > 0){
						$raport1 .= "<th>".$lang['tech_rc'][$ship_id1]."</th>";
						$raport2 .= "<th>".$ship_count1."</th>";
						$raport3 .= "<th>".round($attackers2[$fleet_id1][$ship_id1]['att'] / $ship_count1)."</th>";
						$raport4 .= "<th>".round($attackers2[$fleet_id1][$ship_id1]['shield'] / $ship_count1)."</th>";
						$raport5 .= "<th>".round($attackers2[$fleet_id1][$ship_id1]['def'] / $ship_count1)."</th>";
					}
				}

				$raport1 .= "</tr>";
				$raport2 .= "</tr>";
				$raport3 .= "</tr>";
				$raport4 .= "</tr>";
				$raport5 .= "</tr>";
				$html .= $raport1 . $raport2 . $raport3 . $raport4 . $raport5;
			} else $html .= "<br>уничтожен";
			$html .= "</table></center></th></tr></table></td>";
		}

		$html .= "</tr></table><table width=100%><tr>";

		foreach( $defenders1 as $fleet_id1 => $data2){

			$html .= "<td><table border=1 width=100%><tr><th><center>";
			$html .= "Обороняющийся ".$data2['user']['username']." ([".$data2['fleet'][0].":".$data2['fleet'][1].":".$data2['fleet'][2]."])<br>";
			$html .= "Вооружение: ".($data2['user']['military_tech'] * 10)."% Щиты: ".($data2['user']['shield_tech'] * 10)."% Броня: ".($data2['user']['defence_tech'] * 10)."%";

			$html  .= "<table border=1 align=\"center\">";

			if ($data1['defenseA'][$fleet_id1] > 0) {
				$raport1  = "<tr><th>Тип</th>";
				$raport2  = "<tr><th>Кол-во</th>";
				$raport3  = "<tr><th>Вооружение</th>";
				$raport4  = "<tr><th>Щиты</th>";
				$raport5  = "<tr><th>Броня</th>";

				foreach( $data2['def'] as $ship_id1 => $ship_count1){
					if ($ship_count1 > 0){
						$raport1 .= "<th>".$lang['tech_rc'][$ship_id1]."</th>";
						$raport2 .= "<th>".$ship_count1."</th>";
						$raport3 .= "<th>".round($defenders2[$fleet_id1][$ship_id1]['att'] / $ship_count1)."</th>";
						$raport4 .= "<th>".round($defenders2[$fleet_id1][$ship_id1]['shield'] / $ship_count1)."</th>";
						$raport5 .= "<th>".round($defenders2[$fleet_id1][$ship_id1]['def'] / $ship_count1)."</th>";
					}
				}
				$raport1 .= "</tr>";
				$raport2 .= "</tr>";
				$raport3 .= "</tr>";
				$raport4 .= "</tr>";
				$raport5 .= "</tr>";
				$html .= $raport1 . $raport2 . $raport3 . $raport4 . $raport5;
			} else $html .= "<br>уничтожен";
			$html .= "</table></center></th></tr></table></td>";
		}
		$html .= "</tr></table>";

	if ($round_no < 7 && $data1['attackA']['total'] > 0 && $data1['defenseA']['total'] > 0){
		$html .= "<center>Атакующий флот делает ".$data1['attackA']['total']." выстрела(ов) с общей мощностью ".$data1['attack']['total']." по обороняющемуся. Щиты обороняющегося поглощают ".$data1['defShield']." выстрелов.<br>";
		$html .= "Обороняющийся флот делает ".$data1['defenseA']['total']." выстрела(ов) с общей мощностью ".$data1['defense']['total']." по атакующему. Щиты атакующего поглащают ".$data1['attackShield']." выстрелов.</center>";
	}
		$round_no++;
	}
	if ($result_array['won'] == 2){
		$result1  = "Обороняющийся выиграл битву!<br>";
		if ($round_no < 4) $bbc = 1;
	}elseif ($result_array['won'] == 1){
		$result1  = "Атакующий выиграл битву!<br>";
		$result1 .= "Он получает ".$steal_array['metal']." металла, ".$steal_array['crystal']." кристалла и ".$steal_array['deuterium']." дейтерия<br>";
	}else{
		$result1  = "Бой закончился ничьёй!<br>";
	}



	$html .= "<br><br>";
	$html .= $result1;
	$html .= "<br>";

	$debirs_meta = ($result_array['debree']['att'][0] + $result_array['debree']['def'][0]);
	$debirs_crys = ($result_array['debree']['att'][1] + $result_array['debree']['def'][1]);
	$html .= "Атакующий потерял ".$result_array['lost']['att']." единиц.<br>";
	$html .= "Обороняющийся потерял ".$result_array['lost']['def']." единиц.<br>";
	$html .= "Теперь на этих пространственных координатах находятся ".$debirs_meta." металла и ".$debirs_crys." кристалла.<br><br>";

	$html .= "Шанс появления луны составляет ".$moon_int."%<br>".$moon_string;

	return array('html' => $html, 'bbc' => $bbc);
}

// ----------------------------------------------------------------------------------------------------------------
// Mission Case 1: -> Attaquer
//
function MissionCaseAttack ( $FleetRow ) {
	global $user, $phpEx, $ugamela_root_path, $pricelist, $lang, $resource, $CombatCaps;

	if ($FleetRow['fleet_start_time'] <= time()) {
		if ($FleetRow['fleet_mess'] == 0) {
		
			if (!isset($CombatCaps[202]['sd'])) {
				message("<font color=\"red\">". $lang['sys_no_vars'] ."</font>", $lang['sys_error'], "fleet." . $phpEx, 2);
			}

			$QryTargetPlanet  = "SELECT * FROM {{table}} WHERE ";
			$QryTargetPlanet .= "`galaxy` = '". $FleetRow['fleet_end_galaxy'] ."' AND ";
			$QryTargetPlanet .= "`system` = '". $FleetRow['fleet_end_system'] ."' AND ";
			$QryTargetPlanet .= "`planet` = '". $FleetRow['fleet_end_planet'] ."' AND ";
			$QryTargetPlanet .= "`planet_type` = '". $FleetRow['fleet_end_type'] ."';";
			$TargetPlanet     = doquery( $QryTargetPlanet, 'planets', true);

			if (!$TargetPlanet || !$TargetPlanet['id'] || !$TargetPlanet['id_owner']) {
				$QryUpdateFleet  = "UPDATE {{table}} SET `fleet_mess` = 1 WHERE `fleet_id` = '". $FleetRow['fleet_id'] ."' LIMIT 1 ;";
				doquery( $QryUpdateFleet, 'fleets');
				return;
			}

			$TargetUserID     = $TargetPlanet['id_owner'];

			$QryCurrentUser   = "SELECT `id`, `username`, `military_tech`, `defence_tech`, `shield_tech`, `capacity_tech`, `rpg_admiral`, `rpg_komandir` FROM {{table}} ";
			$QryCurrentUser  .= "WHERE `id` = '". $FleetRow['fleet_owner'] ."';";
			$CurrentUser      = doquery($QryCurrentUser , 'users', true);

			$CurrentUserID    = $CurrentUser['id'];

			$QryTargetUser    = "SELECT * FROM {{table}} WHERE `id` = '". $TargetUserID ."';";
			$TargetUser       = doquery($QryTargetUser, 'users', true);

			// =============================================================================
			PlanetResourceUpdate ($TargetUser, $TargetPlanet, time());
			// =============================================================================

			if ($FleetRow['fleet_group'] != 0) {
				$fleets = doquery('SELECT * FROM {{table}} WHERE fleet_group = '.$FleetRow['fleet_group'], 'fleets');
				while ($fleet = mysql_fetch_assoc($fleets)) {
					$attackFleets[$fleet['fleet_id']]['fleet'] = array($fleet['fleet_start_galaxy'], $fleet['fleet_start_system'], $fleet['fleet_start_planet']);
					$attackFleets[$fleet['fleet_id']]['user'] = doquery('SELECT `id`, `username`, `military_tech`, `defence_tech`, `shield_tech`, `rpg_admiral`, `rpg_komandir` FROM {{table}} WHERE id='.$fleet['fleet_owner'],'users', true);

					if ($attackFleets[$fleet['fleet_id']]['user']['rpg_komandir'] > time()) {
						$attackFleets[$fleet['fleet_id']]['user']['military_tech'] 	+= 1;
						$attackFleets[$fleet['fleet_id']]['user']['defence_tech'] 	+= 1;
						$attackFleets[$fleet['fleet_id']]['user']['shield_tech'] 	+= 1;
					}

					$attackFleets[$fleet['fleet_id']]['detail'] = array();
					$temp = explode(';', $fleet['fleet_array']);
					foreach ($temp as $temp2) {
						$temp2 = explode(',', $temp2);

						if ($temp2[0] < 100) continue;

						if (!isset($attackFleets[$fleet['fleet_id']]['detail'][$temp2[0]])) $attackFleets[$fleet['fleet_id']]['detail'][$temp2[0]] = 0;
						$attackFleets[$fleet['fleet_id']]['detail'][$temp2[0]] += $temp2[1];
					}
				}
			} else {
				$attackFleets[$FleetRow['fleet_id']]['fleet'] = array($FleetRow['fleet_start_galaxy'], $FleetRow['fleet_start_system'], $FleetRow['fleet_start_planet']);
				$attackFleets[$FleetRow['fleet_id']]['user'] = $CurrentUser;

				if ($attackFleets[$FleetRow['fleet_id']]['user']['rpg_komandir'] > time()) {
					$attackFleets[$FleetRow['fleet_id']]['user']['military_tech'] 	+= 1;
					$attackFleets[$FleetRow['fleet_id']]['user']['defence_tech'] 	+= 1;
					$attackFleets[$FleetRow['fleet_id']]['user']['shield_tech'] 	+= 1;
				}

				$attackFleets[$FleetRow['fleet_id']]['detail'] = array();
				$temp = explode(';', $FleetRow['fleet_array']);
				foreach ($temp as $temp2) {
					$temp2 = explode(',', $temp2);

					if ($temp2[0] < 100) continue;

					if (!isset($attackFleets[$FleetRow['fleet_id']]['detail'][$temp2[0]])) $attackFleets[$FleetRow['fleet_id']]['detail'][$temp2[0]] = 0;
					$attackFleets[$FleetRow['fleet_id']]['detail'][$temp2[0]] += $temp2[1];
				}
			}

			$defense = array();
			$def = doquery('SELECT * FROM {{table}} WHERE `fleet_end_galaxy` = '.$FleetRow['fleet_end_galaxy'].' AND `fleet_end_system` = '.$FleetRow['fleet_end_system'].' AND `fleet_end_type` = '.$FleetRow['fleet_end_type'].' AND `fleet_end_planet` = '.$FleetRow['fleet_end_planet'].' AND fleet_mess = 3', 'fleets');
			while ($defRow = mysql_fetch_assoc($def)) {
				$defense[$defRow['fleet_id']]['fleet'] = array($FleetRow['fleet_start_galaxy'], $FleetRow['fleet_start_system'], $FleetRow['fleet_start_planet']);
				$defense[$defRow['fleet_id']]['user'] = doquery('SELECT `id`, `username`, `military_tech`, `defence_tech`, `shield_tech`, `rpg_admiral`, `rpg_komandir` FROM {{table}} WHERE id='.$defRow['fleet_owner'],'users', true);

				if ($defense[$defRow['fleet_id']]['user']['rpg_komandir'] > time()) {
					$defense[$defRow['fleet_id']]['user']['military_tech'] 	+= 1;
					$defense[$defRow['fleet_id']]['user']['defence_tech'] 	+= 1;
					$defense[$defRow['fleet_id']]['user']['shield_tech'] 	+= 1;
				}

				$defRowDef = explode(';', $defRow['fleet_array']);
				foreach ($defRowDef as $Element) {
					$Element = explode(',', $Element);

					if ($Element[0] < 100) continue;

					if (!isset($defense[$defRow['fleet_id']]['def'][$Element[0]])) $defense[$defRow['fleet_id']][$Element[0]] = 0;
					$defense[$defRow['fleet_id']]['def'][$Element[0]] += $Element[1];
				}
			}
			$defense[0]['fleet'] = array($FleetRow['fleet_end_galaxy'], $FleetRow['fleet_end_system'], $FleetRow['fleet_end_planet']);
			$defense[0]['def'] = array();
			$defense[0]['user'] = $TargetUser;

			if ($defense[0]['user']['rpg_komandir'] > time()) {
				$defense[0]['user']['military_tech'] 	+= 1;
				$defense[0]['user']['defence_tech'] 	+= 1;
				$defense[0]['user']['shield_tech'] 	+= 1;
			}

			for ($i = 200; $i < 500; $i++) {
				if (isset($resource[$i]) && isset($TargetPlanet[$resource[$i]])) {
					$defense[0]['def'][$i] = $TargetPlanet[$resource[$i]];
				}
			}

			include_once($ugamela_root_path . 'includes/ataki.php');

			$result        = calculateAttack($attackFleets, $defense, $TargetUser['rpg_ingenieur']);

			$steal = array('metal' => 0, 'crystal' => 0, 'deuterium' => 0);
			if ($result['won'] == 1) {
				$max_resources = 0;
				foreach ($attackFleets[$FleetRow['fleet_id']]['detail'] as $Element => $amount) {
					if ($Element != 210)
						$max_resources += round($pricelist[$Element]['capacity'] * (1 + $CurrentUser[$resource['160']] * 0.05)) * $amount;
				}

				if ($max_resources > 0) {
					$metal   = $TargetPlanet['metal'] / 2;
					$crystal = $TargetPlanet['crystal'] / 2;
					$deuter  = $TargetPlanet['deuterium'] / 2;
					if ($metal > $max_resources / 3) {
						$steal['metal']		 = $max_resources / 3;
						$max_resources		-= $steal['metal'];
					} else {
						$steal['metal']		 = $metal;
						$max_resources		-= $steal['metal'];
					}

					if ($crystal > $max_resources / 2) {
						$steal['crystal'] 		 = $max_resources / 2;
						$max_resources   		-= $steal['crystal'];
					} else {
						$steal['crystal'] 		 = $crystal;
						$max_resources   		-= $steal['crystal'];
					}

					if ($deuter > $max_resources) {
						$steal['deuterium']	 	 = $max_resources;
						$max_resources		-= $steal['deuterium'];
					} else {
						$steal['deuterium']	 	 = $deuter;
						$max_resources		-= $steal['deuterium'];
					}
					if ($max_resources > 0) {
						if (($metal - $steal['metal']) > $max_resources / 2) {
							$steal['metal']		+= $max_resources / 2;
							$max_resources		-= $max_resources / 2;
						} else {
							$steal['metal']		+= $metal - $steal['metal'];
							$max_resources		-= $metal - $steal['metal'];
						}
	
						if (($crystal - $steal['crystal']) > $max_resources / 2) {
							$steal['crystal'] 		+= $max_resources / 2;
							$max_resources   		-= $max_resources / 2;
						} else {
							$steal['crystal'] 		+= $crystal - $steal['crystal'];
							$max_resources   		-= $crystal - $steal['crystal'];
						}
					}
				}

				if ($steal['metal'] < 0) $steal['metal'] = 0;
				if ($steal['crystal'] < 0) $steal['crystal'] = 0;
				if ($steal['deuterium'] < 0) $steal['deuterium'] = 0;

				$steal = array_map('round', $steal);

				if ($steal['metal'] > 0 || $steal['crystal'] > 0 || $steal['deuterium'] > 0) {
					$QryUpdateFleet  = 'UPDATE {{table}} SET ';
					$QryUpdateFleet .= '`fleet_resource_metal` = `fleet_resource_metal` + '. $steal['metal'] .', ';
					$QryUpdateFleet .= '`fleet_resource_crystal` = `fleet_resource_crystal` +'. $steal['crystal'] .', ';
					$QryUpdateFleet .= '`fleet_resource_deuterium` = `fleet_resource_deuterium` +'. $steal['deuterium'] .' ';
					$QryUpdateFleet .= 'WHERE fleet_id = '. $FleetRow['fleet_id'] .' ';
					$QryUpdateFleet .= 'LIMIT 1 ;';
					doquery( $QryUpdateFleet,'fleets' );
				}
			}

			$totalDebree = $result['debree']['def'][0] + $result['debree']['def'][1] + $result['debree']['att'][0] + $result['debree']['att'][1];

			if ($totalDebree > 0)
				doquery('UPDATE {{table}} SET metal=metal+'.($result['debree']['att'][0]+$result['debree']['def'][0]).' , crystal=crystal+'.($result['debree']['att'][1]+$result['debree']['def'][1]).' WHERE `galaxy` = '. $FleetRow['fleet_end_galaxy'] .' AND `system` = '. $FleetRow['fleet_end_system'] .' AND `planet` = '. $FleetRow['fleet_end_planet'],'galaxy');

			foreach ($attackFleets as $fleetID => $attacker) {
				$fleetArray = '';
				$totalCount = 0;
				foreach ($attacker['detail'] as $element => $amount) {
					if ($amount) $fleetArray .= $element.','.$amount.';';
					$totalCount += $amount;
				}

				if ($totalCount <= 0) {
					doquery ('DELETE FROM {{table}} WHERE `fleet_id`='.$fleetID,'fleets');
				} else {
					doquery('UPDATE {{table}} SET fleet_array="'.substr($fleetArray, 0, -1).'", fleet_amount='.$totalCount.', fleet_time = fleet_end_time, fleet_mess=1 WHERE fleet_id='.$fleetID,'fleets');
				}
			}

			foreach ($defense as $fleetID => $defender) {
				if ($fleetID != 0) {
					$fleetArray = '';
					$totalCount = 0;
					foreach ($defender['def'] as $element => $amount) {
						if ($amount) $fleetArray .= $element.','.$amount.';';
						$totalCount += $amount;
					}

					if ($totalCount <= 0) {
						doquery ('DELETE FROM {{table}} WHERE `fleet_id`='.$fleetID,'fleets');

					} else {
						doquery('UPDATE {{table}} SET fleet_array="'.$fleetArray.'", fleet_amount='.$totalCount.' WHERE fleet_id='.$fleetID,'fleets');
					}

				} else {
					$fleetArray = '';
					$totalCount = 0;
					foreach ($defender['def'] as $element => $amount) {
						$fleetArray .= '`'.$resource[$element].'`='.$amount.', ';
					}
					doquery('UPDATE {{table}} SET '.$fleetArray.'metal=metal-'.$steal['metal'].', crystal=crystal-'.$steal['crystal'].', deuterium=deuterium-'.$steal['deuterium'].' WHERE id='.$TargetPlanet['id'],'planets');
				}
			}

			$FleetDebris      = $result['debree']['att'][0] + $result['debree']['def'][0] + $result['debree']['att'][1] + $result['debree']['def'][1];
			$StrAttackerUnits = sprintf ($lang['sys_attacker_lostunits'], $result['lost']['att']);
			$StrDefenderUnits = sprintf ($lang['sys_defender_lostunits'], $result['lost']['def']);
			$StrRuins         = sprintf ($lang['sys_gcdrunits'], $result['debree']['def'][0] + $result['debree']['att'][0], $lang['Metal'], $result['debree']['def'][1] + $result['debree']['att'][1], $lang['Crystal']);
			$DebrisField      = $StrAttackerUnits ."<br />". $StrDefenderUnits ."<br />". $StrRuins;

			$MoonChance  = round($FleetDebris / 100000);
			if ($FleetDebris > 2000000) {
				$MoonChance = 20;
			}
			if ($FleetDebris < 100000) {
				$UserChance = 0;
				$ChanceMoon = "";
			} elseif ($FleetDebris >= 100000) {
				$UserChance = mt_rand(1, 100);
				$ChanceMoon       = sprintf ($lang['sys_moonproba'], $MoonChance);
			}

			if ($FleetRow['fleet_end_type'] == 5) $UserChance = 0;

			if (($UserChance > 0) and ($UserChance <= $MoonChance) and $galenemyrow['id_luna'] == 0) {
				$TargetPlanetName = CreateOneMoonRecord ( $FleetRow['fleet_end_galaxy'], $FleetRow['fleet_end_system'], $FleetRow['fleet_end_planet'], $TargetUserID, $FleetRow['fleet_start_time'], '', $MoonChance );
				$GottenMoon       = sprintf ($lang['sys_moonbuilt'], $TargetPlanetName, $FleetRow['fleet_end_galaxy'], $FleetRow['fleet_end_system'], $FleetRow['fleet_end_planet']);
			} elseif ($UserChance = 0 or $UserChance > $MoonChance) {
				$GottenMoon = "";
			}
			
			$formatted_cr = formatCR($result, $steal, $MoonChance, $GottenMoon);
			$raport = $formatted_cr['html'];
			
			$rid   = md5($raport);

			$QryInsertRapport  = "INSERT INTO {{table}} SET ";
			$QryInsertRapport .= "`time` = UNIX_TIMESTAMP(), ";
			$QryInsertRapport .= "`id_owner1` = '".$FleetRow['fleet_owner']."', ";
			$QryInsertRapport .= "`id_owner2` = '".$TargetUserID."', ";
			$QryInsertRapport .= "`rid` = '".$rid."', ";
			$QryInsertRapport .= "`a_zestrzelona` = '".$formatted_cr['bbc']."', ";
			$QryInsertRapport .= "`raport` = '". addslashes ( $raport ) ."';";
			doquery( $QryInsertRapport , 'rw');

			$QryUpdateUser1  = "UPDATE {{table}} SET ";

			if ($result['won'] == 1){
				$QryUpdateUser1  .= "`raids_win` =  `raids_win` + 1, ";
			}elseif ($result['won'] == 2){
				$QryUpdateUser1  .= "`raids_lose` =  `raids_lose` + 1, ";
			}

			if ($result['won'] != 2){
				$AddWarPoints = intval($MoonChance) * 4;
			}else{
				$AddWarPoints = 0;
			}

			$QryUpdateUser1 .= "`raids` = `raids` + 1, ";
			$QryUpdateUser1 .= "`xpraid` = `xpraid` + ".$AddWarPoints." ";
			$QryUpdateUser1 .= "WHERE ";
			$QryUpdateUser1 .= "`id` = '". $FleetRow['fleet_owner'] ."';";
			doquery( $QryUpdateUser1, 'users');

			if ($FleetRow['fleet_group'] != 0) {
				doquery("DELETE FROM {{table}} WHERE fleet_id = ".$FleetRow['fleet_id']."", "aks");
				doquery("DELETE FROM {{table}} WHERE aks_id = ".$FleetRow['fleet_group']."", "aks_user");
			}

			//JournalLogs ($CurrentUser[username], $TargetUser[username], $TargetPlanet, 1);

            $raport  = "<a href=\"#\" OnClick=\"f( '?set=rw&raport=". $rid ."', '');\" >";
			$raport .= "<center>";
            if ($result['won'] == 1) {
				$raport .= "<font color=\"green\">";
            } elseif ($result['won'] == 0) {
				$raport .= "<font color=\"orange\">";
            } elseif ($result['won'] == 2) {
				$raport .= "<font color=\"red\">";
			}

			$raport .= $lang['sys_mess_attack_report'] ." [". $FleetRow['fleet_end_galaxy'] .":". $FleetRow['fleet_end_system'] .":". $FleetRow['fleet_end_planet'] ."] </font></a><br /><br />";
			$raport .= '<font color=\'red\'>'. $lang['sys_perte_attaquant'] .': '. $result['lost']['att'] .'</font>';
			$raport .= '<font color=\'green\'>   '. $lang['sys_perte_defenseur'] .': '. $result['lost']['def'] .'</font><br />' ;
            $raport .= $lang['sys_gain'] .' '. $lang['Metal'] .':<font color=\'#adaead\'>'. $steal['metal'] .'</font>   '. $lang['Crystal'] .':<font color=\'#ef51ef\'>'. $steal['crystal'] .'</font>   '. $lang['Deuterium'] .':<font color=\'#f77542\'>'. $steal['deuterium'] .'</font><br />';
            $raport .= $lang['sys_debris'] .' '. $lang['Metal'] .': <font color=\'#adaead\'>'. ($result['debree']['att'][0]+$result['debree']['def'][0]) .'</font>   '. $lang['Crystal'] .': <font color=\'#ef51ef\'>'. ($result['debree']['att'][1]+$result['debree']['def'][1]) .'</font><br /></center>';

			SendSimpleMessage ( $CurrentUserID, '', time(), 3, $lang['sys_mess_tower'], $lang['sys_mess_attack_report'], $raport );

			$raport2  = "<a href=\"#\" OnClick=\"f( '?set=rw&raport=". $rid ."', '');\" >";
			$raport2 .= "<center>";
            if ($result['won'] == 1) {
				$raport2 .= "<font color=\"green\">";
            } elseif ($result['won'] == 0) {
				$raport2 .= "<font color=\"orange\">";
            } elseif ($result['won'] == 2) {
				$raport2 .= "<font color=\"red\">";
			}
			$raport2 .= $lang['sys_mess_attack_report'] ." [". $FleetRow['fleet_end_galaxy'] .":". $FleetRow['fleet_end_system'] .":". $FleetRow['fleet_end_planet'] ."] </font></a><br /><br />";

			foreach ($defense as $fleetID => $defender) {
				SendSimpleMessage ( $defender['user']['id'], '', time(), 3, $lang['sys_mess_tower'], $lang['sys_mess_attack_report'], $raport2 );
			}

		}

		if ($FleetRow['fleet_end_time'] <= time() && $FleetRow['fleet_mess'] != 0) {
			RestoreFleetToPlanet ( $FleetRow, true );
			doquery ("DELETE FROM {{table}} WHERE `fleet_id` = " . $FleetRow["fleet_id"], 'fleets');
		}
	}
}


// ----------------------------------------------------------------------------------------------------------------
// Mission Case 9: -> Coloniser
//
function MissionCaseColonisation ( $FleetRow ) {
    global $lang, $resource, $user;

	if ($FleetRow['fleet_mess'] == 0) {

		if ($FleetRow['fleet_start_time'] <= time()) {

			$MaxColo = doquery("SELECT `colonisation_tech` FROM {{table}} WHERE id={$FleetRow['fleet_owner']}",'users',true);
			$iMaxColo = $MaxColo['colonisation_tech'] + 1;
			if ($iMaxColo > MAX_PLAYER_PLANETS) $iMaxColo = MAX_PLAYER_PLANETS;

			$iPlanetCount = mysql_result(doquery ("SELECT count(*) FROM {{table}} WHERE `id_owner` = '". $FleetRow['fleet_owner'] ."' AND `planet_type` = '1'", 'planets'), 0);

			$iGalaxyPlace = mysql_result(doquery ("SELECT count(*) FROM {{table}} WHERE `galaxy` = '". $FleetRow['fleet_end_galaxy']."' AND `system` = '". $FleetRow['fleet_end_system']."' AND `planet` = '". $FleetRow['fleet_end_planet']."';", 'galaxy'), 0);
			$TargetAdress = sprintf ($lang['sys_adress_planet'], $FleetRow['fleet_end_galaxy'], $FleetRow['fleet_end_system'], $FleetRow['fleet_end_planet']);
			if ($iGalaxyPlace == 0) {
				if ($iPlanetCount >= $iMaxColo) {
					$TheMessage = $lang['sys_colo_arrival'] . $TargetAdress . $lang['sys_colo_maxcolo'] . $iMaxColo . $lang['sys_colo_planet'];
					SendSimpleMessage ( $FleetRow['fleet_owner'], '', $FleetRow['fleet_start_time'], 0, $lang['sys_colo_mess_from'], $lang['sys_colo_mess_report'], $TheMessage);
					doquery("UPDATE {{table}} SET fleet_time = fleet_end_time, `fleet_mess` = '1' WHERE `fleet_id` = ". $FleetRow["fleet_id"], 'fleets');
				} else {
					$NewOwnerPlanet = CreateOnePlanetRecord($FleetRow['fleet_end_galaxy'], $FleetRow['fleet_end_system'], $FleetRow['fleet_end_planet'], $FleetRow['fleet_owner'], $lang['sys_colo_defaultname'], false);
					if ( $NewOwnerPlanet == true ) {
						$TheMessage = $lang['sys_colo_arrival'] . $TargetAdress . $lang['sys_colo_allisok'];
						SendSimpleMessage ( $FleetRow['fleet_owner'], '', $FleetRow['fleet_start_time'], 0, $lang['sys_colo_mess_from'], $lang['sys_colo_mess_report'], $TheMessage);

						if ($FleetRow['fleet_amount'] == 1) {
							doquery("DELETE FROM {{table}} WHERE fleet_id=" . $FleetRow["fleet_id"], 'fleets');
						} else {
							$CurrentFleet = explode(";", $FleetRow['fleet_array']);
							$NewFleet     = "";
							foreach ($CurrentFleet as $Item => $Group) {
								if ($Group != '') {
									$Class = explode (",", $Group);
									if ($Class[0] == 208) {
										if ($Class[1] > 1) {
											$NewFleet  .= $Class[0].",".($Class[1] - 1).";";
										}
									} else {
										if ($Class[1] <> 0) {
										$NewFleet  .= $Class[0].",".$Class[1].";";
										}
									}
								}
							}
							$QryUpdateFleet  = "UPDATE {{table}} SET ";
							$QryUpdateFleet .= "`fleet_array` = '". $NewFleet ."', ";
							$QryUpdateFleet .= "`fleet_amount` = `fleet_amount` - 1, ";
							$QryUpdateFleet .= "fleet_time = fleet_end_time, `fleet_mess` = '1' ";
							$QryUpdateFleet .= "WHERE `fleet_id` = '". $FleetRow["fleet_id"] ."';";
							doquery( $QryUpdateFleet, 'fleets');
							return;
						}
					} else {
						doquery("UPDATE {{table}} SET fleet_time = fleet_end_time, `fleet_mess` = '1' WHERE `fleet_id` = ". $FleetRow["fleet_id"], 'fleets');
						$TheMessage = $lang['sys_colo_arrival'] . $TargetAdress . $lang['sys_colo_badpos'];
						SendSimpleMessage ( $FleetRow['fleet_owner'], '', $FleetRow['fleet_start_time'], 0, $lang['sys_colo_mess_from'], $lang['sys_colo_mess_report'], $TheMessage);
					}
				}
			} else {
				doquery("UPDATE {{table}} SET fleet_time = fleet_end_time, `fleet_mess` = '1' WHERE `fleet_id` = ". $FleetRow["fleet_id"], 'fleets');
				$TheMessage = $lang['sys_colo_arrival'] . $TargetAdress . $lang['sys_colo_notfree'];
				SendSimpleMessage ( $FleetRow['fleet_owner'], '', $FleetRow['fleet_end_time'], 0, $lang['sys_colo_mess_from'], $lang['sys_colo_mess_report'], $TheMessage);
			}
		}
	} else {
		if ($FleetRow['fleet_end_time'] <= time()) {
			RestoreFleetToPlanet ( $FleetRow, true );
			doquery("DELETE FROM {{table}} WHERE fleet_id=" . $FleetRow["fleet_id"], 'fleets');
		}
	}
}


function MissionCaseDestruction ( $FleetRow ) {
   	global $phpEx, $ugamela_root_path, $pricelist, $lang, $resource, $CombatCaps;

   	if ($FleetRow['fleet_start_time'] <= time()) {
     	if ($FleetRow['fleet_mess'] == 0) {
			if (!isset($CombatCaps[202]['sd'])) {
					message("<font color=\"red\">". $lang['sys_no_vars'] ."</font>", $lang['sys_error'], "fleet." . $phpEx, 2);
			}

			$QryTargetMoon  = "SELECT * FROM {{table}} WHERE ";
			$QryTargetMoon .= "`galaxy` = '". $FleetRow['fleet_end_galaxy'] ."' AND ";
			$QryTargetMoon .= "`system` = '". $FleetRow['fleet_end_system'] ."' AND ";
			$QryTargetMoon .= "`planet` = '". $FleetRow['fleet_end_planet'] ."' AND `planet_type` = '3';";
			$TargetMoon     	= doquery( $QryTargetMoon, 'planets', true);

			$TargetUserID     	= $TargetMoon['id_owner'];
			$TargerMoonID       = $TargetMoon['id'];
			$MoonSize 	= $TargetMoon['diameter'];

			$QryCurrentUser    = "SELECT `id`, `username`, `military_tech`, `defence_tech`, `shield_tech`, `rpg_admiral`, `rpg_meta`, `rpg_komandir` FROM {{table}} ";
			$QryCurrentUser   .= "WHERE `id` = '". $FleetRow['fleet_owner'] ."';";
			$CurrentUser          = doquery($QryCurrentUser , 'users', true);
			$CurrentUserID      = $CurrentUser['id'];

			$QryTargetUser    = "SELECT * FROM {{table}} ";
			$QryTargetUser   .= "WHERE ";
			$QryTargetUser   .= "`id` = '". $TargetUserID ."';";
			$TargetUser       = doquery($QryTargetUser, 'users', true);

			PlanetResourceUpdate($TargetUser, $TargetMoon, time());

			$attackFleets[$FleetRow['fleet_id']]['fleet'] = array($FleetRow['fleet_start_galaxy'], $FleetRow['fleet_start_system'], $FleetRow['fleet_start_planet']);
			$attackFleets[$FleetRow['fleet_id']]['user'] = $CurrentUser;

			if ($attackFleets[$FleetRow['fleet_id']]['user']['rpg_komandir'] > time()) {
				$attackFleets[$FleetRow['fleet_id']]['user']['military_tech'] 	+= 1;
				$attackFleets[$FleetRow['fleet_id']]['user']['defence_tech'] 	+= 1;
				$attackFleets[$FleetRow['fleet_id']]['user']['shield_tech'] 	+= 1;
			}

			$attackFleets[$FleetRow['fleet_id']]['detail'] = array();
			$temp = explode(';', $FleetRow['fleet_array']);
			foreach ($temp as $temp2) {
				$temp2 = explode(',', $temp2);
					
				if ($temp2[0] < 100) continue;
					
				if (!isset($attackFleets[$FleetRow['fleet_id']]['detail'][$temp2[0]])) $attackFleets[$FleetRow['fleet_id']]['detail'][$temp2[0]] = 0;
				$attackFleets[$FleetRow['fleet_id']]['detail'][$temp2[0]] += $temp2[1];
			}

			$defense = array();
			$def = doquery('SELECT * FROM {{table}} WHERE `fleet_end_galaxy` = '.$FleetRow['fleet_end_galaxy'].' AND `fleet_end_system` = '.$FleetRow['fleet_end_system'].' AND `fleet_end_type` = '.$FleetRow['fleet_end_type'].' AND `fleet_end_planet` = '.$FleetRow['fleet_end_planet'].' AND fleet_mess = 3', 'fleets');
			while ($defRow = mysql_fetch_assoc($def)) {
				$defense[$defRow['fleet_id']]['fleet'] = array($FleetRow['fleet_start_galaxy'], $FleetRow['fleet_start_system'], $FleetRow['fleet_start_planet']);
				$defense[$defRow['fleet_id']]['user'] = doquery('SELECT `id`, `username`, `military_tech`, `defence_tech`, `shield_tech`, `rpg_admiral`, `rpg_komandir` FROM {{table}} WHERE id='.$defRow['fleet_owner'],'users', true);

				if ($defense[$defRow['fleet_id']]['user']['rpg_komandir'] > time()) {
					$defense[$defRow['fleet_id']]['user']['military_tech'] 	+= 1;
					$defense[$defRow['fleet_id']]['user']['defence_tech'] 	+= 1;
					$defense[$defRow['fleet_id']]['user']['shield_tech'] 	+= 1;
				}

				$defRowDef = explode(';', $defRow['fleet_array']);
				foreach ($defRowDef as $Element) {
					$Element = explode(',', $Element);
					
					if ($Element[0] < 100) continue;
					
					if (!isset($defense[$defRow['fleet_id']]['def'][$Element[0]])) $defense[$defRow['fleet_id']][$Element[0]] = 0;
					$defense[$defRow['fleet_id']]['def'][$Element[0]] += $Element[1];
				}
			}
			$defense[0]['fleet'] = array($FleetRow['fleet_end_galaxy'], $FleetRow['fleet_end_system'], $FleetRow['fleet_end_planet']);
			$defense[0]['def'] = array();
			$defense[0]['user'] = $TargetUser;

			if ($defense[0]['user']['rpg_komandir'] > time()) {
				$defense[0]['user']['military_tech'] 	+= 1;
				$defense[0]['user']['defence_tech'] 	+= 1;
				$defense[0]['user']['shield_tech'] 	+= 1;
			}

			for ($i = 200; $i < 500; $i++) {
				if (isset($resource[$i]) && isset($TargetMoon[$resource[$i]])) {
					$defense[0]['def'][$i] = $TargetMoon[$resource[$i]];
				}
			}

			include_once($ugamela_root_path . 'includes/ataki.' . $phpEx);

			$mtime        = microtime();
			$mtime        = explode(" ", $mtime);
			$mtime        = $mtime[1] + $mtime[0];
			$starttime    = $mtime;

			$result        = calculateAttack($attackFleets, $defense, $TargetUser['rpg_ingenieur']);

			$mtime        = microtime();
			$mtime        = explode(" ", $mtime);
			$mtime        = $mtime[1] + $mtime[0];
			$endtime      = $mtime;
			$totaltime    = ($endtime - $starttime);

			$steal = array('metal' => 0, 'crystal' => 0, 'deuterium' => 0);
			if ($result['won'] == 1) {
				$max_resources = 0;
				foreach ($attackFleets[$FleetRow['fleet_id']]['detail'] as $Element => $amount) {
					$max_resources += $pricelist[$Element]['capacity'] * $amount;
				}
				
				if ($max_resources > 0) {
					$metal   = $TargetMoon['metal'] / 2;
					$crystal = $TargetMoon['crystal'] / 2;
					$deuter  = $TargetMoon['deuterium'] / 2;
					if ($metal > $max_resources / 3) {
						$steal['metal']		 = $max_resources / 3;
						$max_resources		 = $max_resources - $steal['metal'];
					} else {
						$steal['metal']		 = $metal;
						$max_resources		-= $steal['metal'];
					}
					
					if ($crystal > $max_resources / 2) {
						$steal['crystal'] = $max_resources / 2;
						$max_resources   -= $steal['crystal'];
					} else {
						$steal['crystal'] = $crystal;
						$max_resources   -= $steal['crystal'];
					}
					
					if ($deuter > $max_resources) {
						$steal['deuterium']	 = $max_resources;
						$max_resources		-= $steal['deuterium'];
					} else {
						$steal['deuterium']	 = $deuter;
						$max_resources		-= $steal['deuterium'];
					}
				}

					if ($steal['metal'] < 0) $steal['metal'] = 0;
					if ($steal['crystal'] < 0) $steal['crystal'] = 0;
					if ($steal['deuterium'] < 0) $steal['deuterium'] = 0;
					
					$steal = array_map('round', $steal);
					
					$QryUpdateFleet  = 'UPDATE {{table}} SET ';
					$QryUpdateFleet .= '`fleet_resource_metal` = `fleet_resource_metal` + '. $steal['metal'] .', ';
					$QryUpdateFleet .= '`fleet_resource_crystal` = `fleet_resource_crystal` +'. $steal['crystal'] .', ';
					$QryUpdateFleet .= '`fleet_resource_deuterium` = `fleet_resource_deuterium` +'. $steal['deuterium'] .' ';
					$QryUpdateFleet .= 'WHERE fleet_id = '. $FleetRow['fleet_id'] .' ';
					$QryUpdateFleet .= 'LIMIT 1 ;';
					doquery( $QryUpdateFleet,'fleets' );
				}
	
				doquery('UPDATE {{table}} SET metal=metal+'.($result['debree']['att'][0]+$result['debree']['def'][0]).' , crystal=crystal+'.($result['debree']['att'][1]+$result['debree']['def'][1]).' WHERE `galaxy` = '. $FleetRow['fleet_end_galaxy'] .' AND `system` = '. $FleetRow['fleet_end_system'] .' AND `planet` = '. $FleetRow['fleet_end_planet'],'galaxy');
	
				$totalDebree = $result['debree']['def'][0] + $result['debree']['def'][1] + $result['debree']['att'][0] + $result['debree']['att'][1];
				$Rips = 0;
				foreach ($attackFleets as $fleetID => $attacker) {
					$fleetArray = '';
					$totalCount = 0;
					foreach ($attacker['detail'] as $element => $amount) {
						if ($amount) $fleetArray .= $element.','.$amount.';';
						$totalCount += $amount;
						if ($element == 214) $Rips += $amount;
					}
					
					if ($totalCount <= 0) {
						doquery ('DELETE FROM {{table}} WHERE `fleet_id`='.$fleetID,'fleets');
					} else {
						doquery('UPDATE {{table}} SET fleet_array="'.substr($fleetArray, 0, -1).'", fleet_amount='.$totalCount.', fleet_time = fleet_end_time, fleet_mess=1 WHERE fleet_id='.$fleetID,'fleets');
					}
				}
				
				foreach ($defense as $fleetID => $defender) {
					if ($fleetID != 0) {
						$fleetArray = '';
						$totalCount = 0;
						foreach ($defender['def'] as $element => $amount) {
							if ($amount) $fleetArray .= $element.','.$amount.';';
							$totalCount += $amount;
						}
						
						if ($totalCount <= 0) {
							doquery ('DELETE FROM {{table}} WHERE `fleet_id`='.$fleetID,'fleets');
						
						} else {
							doquery('UPDATE {{table}} SET fleet_array="'.$fleetArray.'", fleet_amount='.$totalCount.' WHERE fleet_id='.$fleetID,'fleets');
						}
					
					} else {
						$fleetArray = '';
						$totalCount = 0;
						foreach ($defender['def'] as $element => $amount) {
							$fleetArray .= '`'.$resource[$element].'`='.$amount.', ';
						}
						
						doquery('UPDATE {{table}} SET '.$fleetArray.'metal=metal-'.$steal['metal'].', crystal=crystal-'.$steal['crystal'].', deuterium=deuterium-'.$steal['deuterium'].' WHERE id='.$TargetMoon['id'],'planets');
					}
				}
	
				$FleetDebris      = $result['debree']['att'][0] + $result['debree']['def'][0] + $result['debree']['att'][1] + $result['debree']['def'][1];
				$StrAttackerUnits = sprintf ($lang['sys_attacker_lostunits'], $result['lost']['att']);
				$StrDefenderUnits = sprintf ($lang['sys_defender_lostunits'], $result['lost']['def']);
				$StrRuins         = sprintf ($lang['sys_gcdrunits'], $result['debree']['def'][0] + $result['debree']['att'][0], $lang['Metal'], $result['debree']['def'][1] + $result['debree']['att'][1], $lang['Crystal']);
	
				$formatted_cr = formatCR($result, $steal, $MoonChance, $GottenMoon, $totaltime);
				$raport = $formatted_cr['html'];
	
				$rid   = md5($raport);
	
				$QryInsertRapport  = "INSERT INTO {{table}} SET ";
				$QryInsertRapport .= "`time` = UNIX_TIMESTAMP(), ";
				$QryInsertRapport .= "`id_owner1` = '".$FleetRow['fleet_owner']."', ";
				$QryInsertRapport .= "`id_owner2` = '".$TargetUserID."', ";
				$QryInsertRapport .= "`rid` = '".$rid."', ";
				$QryInsertRapport .= "`a_zestrzelona` = '".$a_zestrzelona."', ";
				$QryInsertRapport .= "`raport` = '". addslashes ( $raport ) ."';";
				doquery( $QryInsertRapport , 'rw');
	
	            		$raport  = "<a href=\"#\" OnClick=\"f( '?set=rw&raport=". $rid ."', '');\" >";
				$raport .= "<center>";
            			if ($result['won'] == 1) {
					$raport .= "<font color=\"green\">";
            			} elseif ($result['won'] == 0) {
					$raport .= "<font color=\"orange\">";
            			} elseif ($result['won'] == 2) {
					$raport .= "<font color=\"red\">";
				}
				$raport .= $lang['sys_mess_attack_report'] ." [". $FleetRow['fleet_end_galaxy'] .":". $FleetRow['fleet_end_system'] .":". $FleetRow['fleet_end_planet'] ."] </font></a><br /><br />";
				$raport .= '<font color=\'red\'>'. $lang['sys_perte_attaquant'] .': '. $result['lost']['att'] .'</font>';
				$raport .= '<font color=\'green\'>   '. $lang['sys_perte_defenseur'] .': '. $result['lost']['def'] .'</font><br />' ;
	            $raport .= $lang['sys_gain'] .' '. $lang['Metal'] .':<font color=\'#adaead\'>'. $steal['metal'] .'</font>   '. $lang['Crystal'] .':<font color=\'#ef51ef\'>'. $steal['crystal'] .'</font>   '. $lang['Deuterium'] .':<font color=\'#f77542\'>'. $steal['deuterium'] .'</font><br />';
	            $raport .= $lang['sys_debris'] .' '. $lang['Metal'] .': <font color=\'#adaead\'>'. ($result['debree']['att'][0]+$result['debree']['def'][0]) .'</font>   '. $lang['Crystal'] .': <font color=\'#ef51ef\'>'. ($result['debree']['att'][1]+$result['debree']['def'][1]) .'</font><br /></center>';
				
				SendSimpleMessage ( $CurrentUserID, '', time(), 3, $lang['sys_mess_tower'], $lang['sys_mess_attack_report'], $raport );
	
				$raport2  = "<a href=\"#\" OnClick=\"f( '?set=rw&raport=". $rid ."', '');\" >";
				$raport2 .= "<center>";
            	if ($result['won'] == 1) {
					$raport2 .= "<font color=\"green\">";
            	} elseif ($result['won'] == 0) {
					$raport2 .= "<font color=\"orange\">";
            	} elseif ($result['won'] == 2) {
					$raport2 .= "<font color=\"red\">";
				}
				$raport2 .= $lang['sys_mess_attack_report'] ." [". $FleetRow['fleet_end_galaxy'] .":". $FleetRow['fleet_end_system'] .":". $FleetRow['fleet_end_planet'] ."] </font></a><br /><br />";
	
				foreach ($defense as $fleetID => $defender) {
					SendSimpleMessage ( $defender['user']['id'], '', time(), 3, $lang['sys_mess_tower'], $lang['sys_mess_attack_report'], $raport2 );
				}
	
	         	$RipsKilled = 0;
				$MoonDestroyed = 0;

				if ($CurrentUser['rpg_meta'] > time()) $Rips = $Rips * 1.25;

	            $MoonDestChance = round((100 - sqrt($MoonSize)) * (sqrt($Rips)));
            	if ($MoonDestChance > 99) $MoonDestChance = 99;
				if ($MoonDestChance < 0) $MoonDestChance = 0;
				$RipDestChance = round((sqrt($MoonSize)) / 2);

				if ($CurrentUser['rpg_meta'] > time()) $RipDestChance *= 0.75;
	
				if ($result['won'] == 1 AND $Rips > 0){
					$UserChance = mt_rand(1, 100);
						
					if (($UserChance > 0) AND ($UserChance <= $MoonDestChance)) {
						$RipsKilled = 0;
						$MoonDestroyed = 1;
					} elseif (($UserChance > 0) AND ($UserChance <= $RipDestChance)) {
						$RipsKilled = 1;
						$MoonDestroyed = 0;
					}
				}
				
	         	if ($MoonDestroyed == 1){
	
	            	doquery("UPDATE {{table}} SET destruyed = ".(time() + 60 * 60 * 24).", id_owner = 0 WHERE `id` = '".$TargerMoonID."';", 'planets');
	
					$message  = $lang['sys_moon_destroyed']."<br><br>";
					$message .= $lang['sys_chance_moon_destroy'].$MoonDestChance."%. <br>".$lang['sys_chance_rips_destroy'].$RipDestChance."%";
					
					SendSimpleMessage ( $CurrentUserID, '', $FleetRow['fleet_start_time'], 3, $lang['sys_mess_tower'], $lang['sys_moon_destruction_report'], $message );
					SendSimpleMessage ( $TargetUserID, '', $FleetRow['fleet_start_time'], 3, $lang['sys_mess_tower'], $lang['sys_moon_destruction_report'], $message );
				} elseif ($RipsKilled == 1) {
	
					doquery("DELETE FROM {{table}} WHERE `fleet_id` = '". $FleetRow["fleet_id"] ."';", 'fleets');
					$FleetResult = "w";
					$message  = $lang['sys_rips_destroyed']."<br><br>";
					$message .= $lang['sys_chance_moon_destroy'].$MoonDestChance."%. <br>".$lang['sys_chance_rips_destroy'].$RipDestChance."%";
				
					SendSimpleMessage ( $CurrentUserID, '', $FleetRow['fleet_start_time'], 3, $lang['sys_mess_tower'], $lang['sys_moon_destruction_report'], $message );
					SendSimpleMessage ( $TargetUserID, '', $FleetRow['fleet_start_time'], 3, $lang['sys_mess_tower'], $lang['sys_moon_destruction_report'], $message );
				} else {
	
					$message  = $lang['sys_rips_come_back']."<br>";
					$message .= $lang['sys_chance_moon_destroy'].$MoonDestChance."%. <br>".$lang['sys_chance_rips_destroy'].$RipDestChance;
				
					SendSimpleMessage ( $CurrentUserID, '', $FleetRow['fleet_start_time'], 3, $lang['sys_mess_tower'], $lang['sys_moon_destruction_report'], $message );
					SendSimpleMessage ( $TargetUserID, '', $FleetRow['fleet_start_time'], 3, $lang['sys_mess_tower'], $lang['sys_moon_destruction_report'], $message );
				}
	      	}

	      	if ($FleetRow['fleet_end_time'] <= time()) {
				RestoreFleetToPlanet ( $FleetRow, true );
	         	doquery ("DELETE FROM {{table}} WHERE `fleet_id` = " . $FleetRow["fleet_id"], 'fleets');
	      	}
   	}
}


function MissionCaseExpedition ( $FleetRow ) {
	global $lang, $resource, $pricelist;

	$FleetOwner = $FleetRow['fleet_owner'];
	$MessSender = $lang['sys_mess_qg'];
	$MessTitle  = $lang['sys_expe_report'];

	if ($FleetRow['fleet_mess'] == 0) {
		if ($FleetRow['fleet_end_stay'] < time()) {

			$PointsFlotte = array(202 => 1.0, 203 => 1.5, 204 => 0.5, 205 => 1.5, 206 => 2.0, 207 => 2.5, 208 => 0.5, 209 => 1.0, 210 => 0.01, 211 => 3.0, 212 => 0.0, 213 => 3.5, 214 => 5.0, 215 => 3.2, 216 => 2.2, 216 => 2.2);
			$RatioGain = array (202 => 0.1, 203 => 0.1, 204 => 0.1, 205 => 0.5, 206 => 0.25, 207 => 0.125,208 => 0.5, 209 => 0.1, 210 => 0.1, 211 => 0.0625, 212 => 0.0, 213 => 0.0625, 214 => 0.03125, 215 => 0.0625, 216 => 0.0525, 216 => 0.0525);

			$FleetStayDuration = ($FleetRow['fleet_end_stay'] - $FleetRow['fleet_start_time']) / 3600;

			$farray = explode(";", $FleetRow['fleet_array']);
			foreach ($farray as $Item => $Group) {
				if ($Group != '') {
					$Class = explode (",", $Group);
					$TypeVaisseau = $Class[0];
					$NbreVaisseau = $Class[1];

					$LaFlotte[$TypeVaisseau] = $NbreVaisseau;

					$FleetCapacity += $pricelist[$TypeVaisseau]['capacity'];

					$FleetPoints   += ($NbreVaisseau * $PointsFlotte[$TypeVaisseau]);
				}
			}

			$FleetUsedCapacity  = $FleetRow['fleet_resource_metal'] + $FleetRow['fleet_resource_crystal'] + $FleetRow['fleet_resource_deuterium'];
			$FleetCapacity     -= $FleetUsedCapacity;

			$FleetCount = $FleetRow['fleet_amount'];

			$Hasard = rand(0, 10);

			$MessSender = $lang['sys_mess_qg']. "(".$Hasard.")";

			if ($Hasard < 3) {
				$Hasard     += 1;
				$LostAmount  = (($Hasard * 33) + 1) / 100;

				if ($LostAmount == 100) {
					SendSimpleMessage ( $FleetOwner, '', $FleetRow['fleet_end_stay'], 15, $MessSender, $MessTitle, $lang['sys_expe_blackholl_2'] );
					doquery ("DELETE FROM {{table}} WHERE `fleet_id` = ". $FleetRow["fleet_id"], 'fleets');
				} else {
					foreach ($LaFlotte as $Ship => $Count) {
						$LostShips[$Ship] = intval($Count * $LostAmount);
						$NewFleetArray   .= $Ship.",". ($Count - $LostShips[$Ship]) .";";
					}

					$QryUpdateFleet  = "UPDATE {{table}} SET ";
					$QryUpdateFleet .= "`fleet_array` = '". $NewFleetArray ."', ";
					$QryUpdateFleet .= "fleet_time = fleet_end_time, `fleet_mess` = '1'  ";
					$QryUpdateFleet .= "WHERE ";
					$QryUpdateFleet .= "`fleet_id` = '". $FleetRow["fleet_id"] ."';";
					doquery( $QryUpdateFleet, 'fleets');

					SendSimpleMessage ( $FleetOwner, '', $FleetRow['fleet_end_stay'], 15, $MessSender, $MessTitle, $lang['sys_expe_blackholl_1'] );
				}

			} elseif ($Hasard == 3) {
				doquery("UPDATE {{table}} SET `fleet_mess` = '1' WHERE `fleet_id` = ". $FleetRow["fleet_id"], 'fleets');
				SendSimpleMessage ( $FleetOwner, '', $FleetRow['fleet_end_stay'], 15, $MessSender, $MessTitle, $lang['sys_expe_nothing_1'] );
			} elseif ($Hasard >= 4 && $Hasard < 7) {
				if ($FleetCapacity > 5000) {
					$MinCapacity = $FleetCapacity - 5000;
					$MaxCapacity = $FleetCapacity;
					$FoundGoods  = rand($MinCapacity, $MaxCapacity);
					$FoundMetal  = intval($FoundGoods / 2);
					$FoundCrist  = intval($FoundGoods / 4);
					$FoundDeute  = intval($FoundGoods / 6);

					$QryUpdateFleet  = "UPDATE {{table}} SET ";
					$QryUpdateFleet .= "`fleet_resource_metal` = `fleet_resource_metal` + '". $FoundMetal ."', ";
					$QryUpdateFleet .= "`fleet_resource_crystal` = `fleet_resource_crystal` + '". $FoundCrist ."', ";
					$QryUpdateFleet .= "`fleet_resource_deuterium` = `fleet_resource_deuterium` + '". $FoundDeute ."', ";
					$QryUpdateFleet .= "fleet_time = fleet_end_time, `fleet_mess` = '1'  ";
					$QryUpdateFleet .= "WHERE ";
					$QryUpdateFleet .= "`fleet_id` = '". $FleetRow["fleet_id"] ."';";
					doquery( $QryUpdateFleet, 'fleets');

					$Message = sprintf($lang['sys_expe_found_goods'],
					pretty_number($FoundMetal), $lang['Metal'],
					pretty_number($FoundCrist), $lang['Crystal'],
					pretty_number($FoundDeute), $lang['Deuterium']);
					SendSimpleMessage ( $FleetOwner, '', $FleetRow['fleet_end_stay'], 15, $MessSender, $MessTitle, $Message );
				}
			} elseif ($Hasard == 7) {
				doquery("UPDATE {{table}} SET `fleet_mess` = '1' WHERE `fleet_id` = ". $FleetRow["fleet_id"], 'fleets');
				SendSimpleMessage ( $FleetOwner, '', $FleetRow['fleet_end_stay'], 15, $MessSender, $MessTitle, $lang['sys_expe_nothing_2'] );
			} elseif ($Hasard >= 8 && $Hasard < 11) {
				$FoundChance = $FleetPoints / $FleetCount;
				for ($Ship = 202; $Ship < 218; $Ship++) {
					if ($LaFlotte[$Ship] != 0) {
						$FoundShip[$Ship] = round($LaFlotte[$Ship] * $RatioGain[$Ship]);
						if ($FoundShip[$Ship] > 0) {
							$LaFlotte[$Ship] += $FoundShip[$Ship];
						}
					}
				}
				$NewFleetArray = "";
				$FoundShipMess = "";
				foreach ($LaFlotte as $Ship => $Count) {
					if ($Count > 0) {
						$NewFleetArray   .= $Ship.",". $Count .";";
					}
				}
				foreach ($FoundShip as $Ship => $Count) {
					if ($Count != 0) {
						$FoundShipMess   .= $Count." ".$lang['tech'][$Ship].",";
					}
				}

				$QryUpdateFleet  = "UPDATE {{table}} SET ";
				$QryUpdateFleet .= "`fleet_array` = '". $NewFleetArray ."', ";
				$QryUpdateFleet .= "fleet_time = fleet_end_time, `fleet_mess` = '1'  ";
				$QryUpdateFleet .= "WHERE ";
				$QryUpdateFleet .= "`fleet_id` = '". $FleetRow["fleet_id"] ."';";
				doquery( $QryUpdateFleet, 'fleets');
				$Message = $lang['sys_expe_found_ships']. $FoundShipMess . "";
				SendSimpleMessage ( $FleetOwner, '', $FleetRow['fleet_end_stay'], 15, $MessSender, $MessTitle, $Message );
			}

		}
	} else {
		if ($FleetRow['fleet_end_time'] < time()) {
			RestoreFleetToPlanet ( $FleetRow, true );
			doquery ("DELETE FROM {{table}} WHERE `fleet_id` = ". $FleetRow["fleet_id"], 'fleets');
		}
	}
}

function MissionCaseRecycling ($FleetRow) {
	global $pricelist, $lang;

	if ($FleetRow["fleet_mess"] == "0") {
		if ($FleetRow['fleet_start_time'] <= time()) {

			$QrySelectGalaxy  = "SELECT g.*, u.capacity_tech FROM game_galaxy g, game_users u WHERE ";
			$QrySelectGalaxy .= "g.`galaxy` = '".$FleetRow['fleet_end_galaxy']."' AND ";
			$QrySelectGalaxy .= "g.`system` = '".$FleetRow['fleet_end_system']."' AND ";
			$QrySelectGalaxy .= "g.`planet` = '".$FleetRow['fleet_end_planet']."' AND ";
			$QrySelectGalaxy .= "u.`id` = '".$FleetRow['fleet_owner']."' LIMIT 1;";
			$TargetGalaxy     = doquery( $QrySelectGalaxy, '', true);

			$FleetRecord         = explode(";", $FleetRow['fleet_array']);
			$RecyclerCapacity    = 0;
			$OtherFleetCapacity  = 0;
			foreach ($FleetRecord as $Item => $Group) {
				if ($Group != '') {
					$Class        = explode (",", $Group);
					if ($Class[0] == 209) {
						$RecyclerCapacity   += round($pricelist[$Class[0]]["capacity"] * (1 + $TargetGalaxy['capacity_tech'] * 0.05)) * $Class[1];
					} else {
						$OtherFleetCapacity += round($pricelist[$Class[0]]["capacity"] * (1 + $TargetGalaxy['capacity_tech'] * 0.05)) * $Class[1];
					}
				}
			}

			$IncomingFleetGoods = $FleetRow["fleet_resource_metal"] + $FleetRow["fleet_resource_crystal"] + $FleetRow["fleet_resource_deuterium"];
			if ($IncomingFleetGoods > $OtherFleetCapacity) {
				$RecyclerCapacity -= ($IncomingFleetGoods - $OtherFleetCapacity);
			}

			if (($TargetGalaxy["metal"] + $TargetGalaxy["crystal"]) <= $RecyclerCapacity) {
				$RecycledGoods["metal"]   = $TargetGalaxy["metal"];
				$RecycledGoods["crystal"] = $TargetGalaxy["crystal"];
			} else {
				if (($TargetGalaxy["metal"]   > $RecyclerCapacity / 2) AND
					($TargetGalaxy["crystal"] > $RecyclerCapacity / 2)) {
					$RecycledGoods["metal"]   = $RecyclerCapacity / 2;
					$RecycledGoods["crystal"] = $RecyclerCapacity / 2;
				} else {
					if ($TargetGalaxy["metal"] > $TargetGalaxy["crystal"]) {
						$RecycledGoods["crystal"] = $TargetGalaxy["crystal"];
						if ($TargetGalaxy["metal"] > ($RecyclerCapacity - $RecycledGoods["crystal"])) {
							$RecycledGoods["metal"] = $RecyclerCapacity - $RecycledGoods["crystal"];
						} else {
							$RecycledGoods["metal"] = $TargetGalaxy["metal"];
						}
					} else {
						$RecycledGoods["metal"] = $TargetGalaxy["metal"];
						if ($TargetGalaxy["crystal"] > ($RecyclerCapacity - $RecycledGoods["metal"])) {
							$RecycledGoods["crystal"] = $RecyclerCapacity - $RecycledGoods["metal"];
						} else {
							$RecycledGoods["crystal"] = $TargetGalaxy["crystal"];
						}
					}
				}
			}
			$NewCargo['Metal']     = $FleetRow["fleet_resource_metal"]   + $RecycledGoods["metal"];
			$NewCargo['Crystal']   = $FleetRow["fleet_resource_crystal"] + $RecycledGoods["crystal"];
			$NewCargo['Deuterium'] = $FleetRow["fleet_resource_deuterium"];

			$QryUpdateGalaxy  = "UPDATE {{table}} SET ";
			$QryUpdateGalaxy .= "`metal` = `metal` - '".$RecycledGoods["metal"]."', ";
			$QryUpdateGalaxy .= "`crystal` = `crystal` - '".$RecycledGoods["crystal"]."' ";
			$QryUpdateGalaxy .= "WHERE ";
			$QryUpdateGalaxy .= "`galaxy` = '".$FleetRow['fleet_end_galaxy']."' AND ";
			$QryUpdateGalaxy .= "`system` = '".$FleetRow['fleet_end_system']."' AND ";
			$QryUpdateGalaxy .= "`planet` = '".$FleetRow['fleet_end_planet']."' ";
			$QryUpdateGalaxy .= "LIMIT 1;";
			doquery( $QryUpdateGalaxy, 'galaxy');

			$QryUpdateFleet  = "UPDATE {{table}} SET ";
            $QryUpdateFleet .= "`fleet_resource_metal` = '".$NewCargo['Metal']."', ";
			$QryUpdateFleet .= "`fleet_resource_crystal` = '".$NewCargo['Crystal']."', ";
			$QryUpdateFleet .= "`fleet_resource_deuterium` = '".$NewCargo['Deuterium']."', ";
			$QryUpdateFleet .= "fleet_time = fleet_end_time, `fleet_mess` = '1' ";
            $QryUpdateFleet .= "WHERE `fleet_id` = '".$FleetRow['fleet_id']."' LIMIT 1;";
			doquery( $QryUpdateFleet, 'fleets');

			$Message = sprintf($lang['sys_recy_gotten'], pretty_number($RecycledGoods["metal"]), $lang['Metal'], pretty_number($RecycledGoods["crystal"]), $lang['Crystal']);
			SendSimpleMessage ( $FleetRow['fleet_owner'], '', $FleetRow['fleet_start_time'], 4, $lang['sys_mess_spy_control'], $lang['sys_recy_report'], $Message);
		}
	} else {
		if ($FleetRow['fleet_end_time'] <= time()) {
			RestoreFleetToPlanet ( $FleetRow, true );
			doquery("DELETE FROM {{table}} WHERE `fleet_id` = '". $FleetRow["fleet_id"] ."';", 'fleets');
		}
	}
}

// ---------------------------------------------------
// Mission Case 6: -> Шпионаж ---
// ---------------------------------------------------
function MissionCaseSpy ( $FleetRow ) {
	global $lang, $resource;

if ($FleetRow['fleet_mess'] == 0) {

	if ($FleetRow['fleet_start_time'] <= time()) {

		$CurrentUser         = doquery("SELECT `spy_tech`, `rpg_technocrate` FROM {{table}} WHERE `id` = '".$FleetRow['fleet_owner']."';", 'users', true);

		$QryGetTargetPlanet  = "SELECT * FROM {{table}} WHERE ";
		$QryGetTargetPlanet .= "`galaxy` = '".$FleetRow['fleet_end_galaxy']."' AND ";
		$QryGetTargetPlanet .= "`system` = '".$FleetRow['fleet_end_system']."' AND ";
		$QryGetTargetPlanet .= "`planet` = '".$FleetRow['fleet_end_planet']."' AND ";
		$QryGetTargetPlanet .= "`planet_type` = '".$FleetRow['fleet_end_type']."';";
		$TargetPlanet        = doquery( $QryGetTargetPlanet, 'planets', true);

		$CurrentUserID       = $FleetRow['fleet_owner'];
		$TargetUserID        = $TargetPlanet['id_owner'];

		$TargetUser          = doquery("SELECT * FROM {{table}} WHERE `id` = '".$TargetUserID."';", 'users', true);

		$CurrentSpyLvl       = $CurrentUser['spy_tech'];
		if ($CurrentUser['rpg_technocrate'] > time()) $CurrentSpyLvl += 2;
		$TargetSpyLvl        = $TargetUser['spy_tech'];
		if ($TargetUser['rpg_technocrate'] > time()) $TargetSpyLvl += 2;

		$fleet               = explode(";", $FleetRow['fleet_array']);
		$fquery              = "";

		// Обновление производства на планете
		// =============================================================================
		PlanetResourceUpdate ($TargetUser, $TargetPlanet, time());
		// =============================================================================

		foreach ($fleet as $a => $b) {
			if ($b != '') {
				$a = explode(",", $b);
				$fquery .= "{$resource[$a[0]]}={$resource[$a[0]]} + {$a[1]}, \n";
				if ($FleetRow["fleet_mess"] != "1") {
					if ($a[0] == "210") {
						$LS    = $a[1];

						$SpyToolDebris    = $LS * 300;

						$def = doquery('SELECT fleet_array FROM {{table}} WHERE `fleet_end_galaxy` = '.$FleetRow['fleet_end_galaxy'].' AND `fleet_end_system` = '.$FleetRow['fleet_end_system'].' AND `fleet_end_type` = '.$FleetRow['fleet_end_type'].' AND `fleet_end_planet` = '.$FleetRow['fleet_end_planet'].' AND fleet_mess = 3', 'fleets');
						while ($defRow = mysql_fetch_assoc($def)) {
							$defRowDef = explode(';', $defRow['fleet_array']);
							foreach ($defRowDef as $Element) {
								$Element = explode(',', $Element);

								if ($Element[0] < 100) continue;

								$TargetPlanet[$resource[$Element[0]]] += $Element[1];
							}
						}

						$MaterialsInfo    = SpyTarget ( $TargetPlanet, 0, $lang['sys_spy_maretials'] );
						$Materials        = $MaterialsInfo['String'];

						$PlanetFleetInfo  = SpyTarget ( $TargetPlanet, 1, $lang['sys_spy_fleet'] );
						$PlanetFleet      = $Materials;
						$PlanetFleet     .= $PlanetFleetInfo['String'];

						$PlanetDefenInfo  = SpyTarget ( $TargetPlanet, 2, $lang['sys_spy_defenses'] );
						$PlanetDefense    = $PlanetFleet;
						$PlanetDefense   .= $PlanetDefenInfo['String'];

						$PlanetBuildInfo  = SpyTarget ( $TargetPlanet, 3, $lang['tech'][0] );
						$PlanetBuildings  = $PlanetDefense;
						$PlanetBuildings .= $PlanetBuildInfo['String'];

						$TargetTechnInfo  = SpyTarget ( $TargetUser, 4, $lang['tech'][100] );
						$TargetTechnos    = $PlanetBuildings;
						$TargetTechnos   .= $TargetTechnInfo['String'];

						$TargetForce      = ($PlanetFleetInfo['Count'] * $LS) / 4;

						if ($TargetForce > 100) 	$TargetForce = 100;
						if ($TargetForce < 0) 	$TargetForce = 0;

						$TargetChances = rand(0, $TargetForce);
						$SpyerChances  = rand(0, 100);
						if ($TargetChances <= $SpyerChances) {
							$DestProba = sprintf( $lang['sys_mess_spy_lostproba'], $TargetChances);
						} elseif ($TargetChances > $SpyerChances) {
							$DestProba = "<font color=\"red\">".$lang['sys_mess_spy_destroyed']."</font>";
						}
						$AttackLink = "<center>";
						$AttackLink .= "<a href=\"?set=fleet&galaxy=". $FleetRow['fleet_end_galaxy'] ."&system=". $FleetRow['fleet_end_system'] ."";
						$AttackLink .= "&planet=".$FleetRow['fleet_end_planet']."";
						$AttackLink .= "&target_mission=1";
						$AttackLink .= " \">". $lang['type_mission'][1] ."";
						$AttackLink .= "</a></center>";


						$MessageEnd = "<center>".$DestProba."</center>";

						$pT = ($TargetSpyLvl - $CurrentSpyLvl);
						$pW = ($CurrentSpyLvl - $TargetSpyLvl);
						if ($TargetSpyLvl > $CurrentSpyLvl)   $ST = ($LS - pow($pT, 2));
						if ($CurrentSpyLvl > $TargetSpyLvl)   $ST = ($LS + pow($pW, 2));
						if ($TargetSpyLvl == $CurrentSpyLvl) $ST = $CurrentSpyLvl;

						if ($ST <= "1") $SpyMessage = $Materials."<br />".$AttackLink.$MessageEnd;
						if ($ST == "2") $SpyMessage = $PlanetFleet."<br />".$AttackLink.$MessageEnd;
						if ($ST == "4" or $ST == "3") $SpyMessage = $PlanetDefense."<br />".$AttackLink.$MessageEnd;
						if ($ST == "5" or $ST == "6") $SpyMessage = $PlanetBuildings."<br />".$AttackLink.$MessageEnd;
						if ($ST >= "7") $SpyMessage = $TargetTechnos."<br />".$AttackLink.$MessageEnd;

						SendSimpleMessage ( $CurrentUserID, '', $FleetRow['fleet_start_time'], 0, $lang['sys_mess_qg'], $lang['sys_mess_spy_report'], $SpyMessage);

						$TargetMessage  = $lang['sys_mess_spy_ennemyfleet'] ." ".$FleetRow['fleet_owner_name'];
						$TargetMessage .= "<a href=\"?set=galaxy&mode=3&galaxy=".$FleetRow["fleet_start_galaxy"]."&system=".$FleetRow["fleet_start_system"]."\">";
						$TargetMessage .= "[".$FleetRow["fleet_start_galaxy"].":".$FleetRow["fleet_start_system"].":".$FleetRow["fleet_start_planet"]."]</a>";
						$TargetMessage .= $lang['sys_mess_spy_seen_at'] ." ". $TargetPlanet['name'];
						$TargetMessage .= " [". $TargetPlanet["galaxy"] .":". $TargetPlanet["system"] .":". $TargetPlanet["planet"] ."]. ";
						$DestProba1 = sprintf( $lang['sys_mess_spy_lostproba'], $TargetChances);
						$TargetMessage .= $DestProba1.".";

						SendSimpleMessage ( $TargetUserID, '', $FleetRow['fleet_start_time'], 0, $lang['sys_mess_spy_control'], $lang['sys_mess_spy_activity'], $TargetMessage);

						if ($TargetChances > $SpyerChances) {
							MissionCaseAttack ( $FleetRow );
						} else {
							doquery("UPDATE {{table}} SET fleet_time = fleet_end_time, `fleet_mess` = '1' WHERE `fleet_id` = '". $FleetRow["fleet_id"] ."';", 'fleets');
						}
					}
				}
			}
		}
		}
	} else {
			if ($FleetRow['fleet_end_time'] <= time()) {
				RestoreFleetToPlanet ( $FleetRow, true );
				doquery("DELETE FROM {{table}} WHERE `fleet_id` = ". $FleetRow["fleet_id"], 'fleets');
			}
	}
}


// ---------------------------------------------------
// Mission Case 4: -> Stationner
//
function MissionCaseStay ( $FleetRow ) {
	global $lang, $resource;

	if ($FleetRow['fleet_mess'] == 0) {

		if ($FleetRow['fleet_start_time'] <= time()) {

			$TargetUserID         = $FleetRow['fleet_target_owner'];
			
			$QryGetTargetPlanet  = "SELECT id_owner FROM {{table}} WHERE `galaxy` = '".$FleetRow['fleet_end_galaxy']."' AND `system` = '".$FleetRow['fleet_end_system']."' AND `planet` = '".$FleetRow['fleet_end_planet']."' AND `planet_type` = '".$FleetRow['fleet_end_type']."';";
			$TargetPlanet        = doquery( $QryGetTargetPlanet, 'planets', true);
			
			if ($TargetPlanet['id_owner'] != $TargetUserID) {
				doquery("UPDATE {{table}} SET fleet_time = fleet_end_time, `fleet_mess` = '1' WHERE `fleet_id` = '". $FleetRow["fleet_id"] ."';", 'fleets');
			} else {
				RestoreFleetToPlanet ( $FleetRow, false );
				doquery("DELETE FROM {{table}} WHERE `fleet_id` = '". $FleetRow["fleet_id"] ."';", 'fleets');

				$TargetAdress         = sprintf ($lang['sys_adress_planet'], $FleetRow['fleet_end_galaxy'], $FleetRow['fleet_end_system'], $FleetRow['fleet_end_planet']);
				$TargetAddedGoods     = sprintf ($lang['sys_stay_mess_goods'], $lang['Metal'], pretty_number($FleetRow['fleet_resource_metal']), $lang['Crystal'], pretty_number($FleetRow['fleet_resource_crystal']), $lang['Deuterium'], pretty_number($FleetRow['fleet_resource_deuterium']));

				$TargetMessage        = $lang['sys_stay_mess_start'] ."<a href=\"?set=galaxy&mode=3&galaxy=". $FleetRow['fleet_end_galaxy'] ."&system=". $FleetRow['fleet_end_system'] ."\">";
				$TargetMessage       .= $TargetAdress. "</a>". $lang['sys_stay_mess_end'] ."<br />". $TargetAddedGoods;

				SendSimpleMessage ( $TargetUserID, '', $FleetRow['fleet_start_time'], 5, $lang['sys_mess_qg'], $lang['sys_stay_mess_stay'], $TargetMessage);
			}
		}
	} else {
		if ($FleetRow['fleet_end_time'] <= time()) {
		
			$QryGetTargetPlanet  = "SELECT id_owner FROM {{table}} WHERE `galaxy` = '".$FleetRow['fleet_start_galaxy']."' AND `system` = '".$FleetRow['fleet_start_system']."' AND `planet` = '".$FleetRow['fleet_start_planet']."' AND `planet_type` = '".$FleetRow['fleet_start_type']."';";
			$TargetPlanet        = doquery( $QryGetTargetPlanet, 'planets', true);
			
			if ($TargetPlanet['id_owner'] != $FleetRow['fleet_owner']) {
				doquery("DELETE FROM {{table}} WHERE `fleet_id` = '". $FleetRow["fleet_id"] ."';", 'fleets');
			} else {
				RestoreFleetToPlanet ( $FleetRow, true );
				doquery("DELETE FROM {{table}} WHERE `fleet_id` = '". $FleetRow["fleet_id"] ."';", 'fleets');

				$TargetAdress         = sprintf ($lang['sys_adress_planet'], $FleetRow['fleet_start_galaxy'], $FleetRow['fleet_start_system'], $FleetRow['fleet_start_planet']);
				$TargetAddedGoods     = sprintf ($lang['sys_stay_mess_goods'], $lang['Metal'], pretty_number($FleetRow['fleet_resource_metal']), $lang['Crystal'], pretty_number($FleetRow['fleet_resource_crystal']), $lang['Deuterium'], pretty_number($FleetRow['fleet_resource_deuterium']));

				$TargetMessage        = $lang['sys_stay_mess_back'] ."<a href=\"?set=galaxy&mode=3&galaxy=". $FleetRow['fleet_start_galaxy'] ."&system=". $FleetRow['fleet_start_system'] ."\">";
				$TargetMessage       .= $TargetAdress. "</a>". $lang['sys_stay_mess_bend'] ."<br />". $TargetAddedGoods;

				SendSimpleMessage ( $FleetRow['fleet_owner'], '', $FleetRow['fleet_end_time'], 5, $lang['sys_mess_qg'], $lang['sys_mess_fleetback'], $TargetMessage);
			}
		}
	}
}

function MissionCaseStayAlly ( $FleetRow ) {
	global $lang;

	$StartName        = $FleetRow['fleet_owner_name'];
	$StartOwner       = $FleetRow['fleet_owner'];
	$TargetName       = $FleetRow['fleet_target_owner_name'];
	$TargetOwner      = $FleetRow['fleet_target_owner'];

	if ($FleetRow['fleet_mess'] == 0) {
		if ($FleetRow['fleet_start_time'] <= time()) {

			$QryUpdateFleet  = "UPDATE {{table}} SET fleet_time = fleet_end_stay, `fleet_mess` = 3 WHERE `fleet_id` = '". $FleetRow['fleet_id'] ."' LIMIT 1 ;";
			doquery( $QryUpdateFleet, 'fleets');

			$Message         = sprintf( $lang['sys_stay_mess_user'], $StartName, GetStartAdressLink($FleetRow, ''), $TargetName, GetTargetAdressLink($FleetRow, '') );
			SendSimpleMessage ( $StartOwner, '', $FleetRow['fleet_start_time'], 0, $lang['sys_mess_tower'], $lang['sys_mess_transport'], $Message);
		}
	} elseif ($FleetRow['fleet_mess'] == 3) {
		if ($FleetRow['fleet_end_stay'] <= time()){
			$QryUpdateFleet  = "UPDATE {{table}} SET fleet_time = fleet_end_time, `fleet_mess` = 1 WHERE `fleet_id` = '". $FleetRow['fleet_id'] ."' LIMIT 1 ;";
			doquery( $QryUpdateFleet, 'fleets');
		}
	} else {
		if ($FleetRow['fleet_end_time'] < time()) {
			RestoreFleetToPlanet ( $FleetRow, true );
			doquery("DELETE FROM {{table}} WHERE fleet_id=".$FleetRow["fleet_id"], 'fleets');
		}
	}
}

function MissionCaseTransport ( $FleetRow ) {
	global $lang;

	$StartName        = $FleetRow['fleet_owner_name'];
	$StartOwner       = $FleetRow['fleet_owner'];
	$TargetName       = $FleetRow['fleet_target_owner_name'];
	$TargetOwner      = $FleetRow['fleet_target_owner'];

	if ($FleetRow['fleet_mess'] == 0) {
		if ($FleetRow['fleet_start_time'] < time()) {

			$QryUpdatePlanet   = "UPDATE {{table}} SET ";
			$QryUpdatePlanet  .= "`metal` = `metal` + '".$FleetRow['fleet_resource_metal']."', ";
			$QryUpdatePlanet  .= "`crystal` = `crystal` + '".$FleetRow['fleet_resource_crystal']."', ";
			$QryUpdatePlanet  .= "`deuterium` = `deuterium` + '".$FleetRow['fleet_resource_deuterium']."' ";
			$QryUpdatePlanet  .= "WHERE ";
			$QryUpdatePlanet  .= "`galaxy` = '".$FleetRow['fleet_end_galaxy']."' AND ";
			$QryUpdatePlanet  .= "`system` = '".$FleetRow['fleet_end_system']."' AND ";
			$QryUpdatePlanet  .= "`planet` = '".$FleetRow['fleet_end_planet']."' AND ";
			$QryUpdatePlanet  .= "`planet_type` = '".$FleetRow['fleet_end_type']."' ";
			$QryUpdatePlanet  .= "LIMIT 1;";
			doquery( $QryUpdatePlanet, 'planets');

			$Message = sprintf( $lang['sys_tran_mess_owner'], $TargetName, GetTargetAdressLink($FleetRow, ''), $FleetRow['fleet_resource_metal'], $lang['Metal'], $FleetRow['fleet_resource_crystal'], $lang['Crystal'], $FleetRow['fleet_resource_deuterium'], $lang['Deuterium'] );

			SendSimpleMessage ( $StartOwner, '', $FleetRow['fleet_start_time'], 5, $lang['sys_mess_tower'], $lang['sys_mess_transport'], $Message);

			if ($TargetOwner <> $StartOwner) {
				$Message = sprintf( $lang['sys_tran_mess_user'], $StartName, GetStartAdressLink($FleetRow, ''), $TargetName, GetTargetAdressLink($FleetRow, ''), $FleetRow['fleet_resource_metal'], $lang['Metal'], $FleetRow['fleet_resource_crystal'], $lang['Crystal'], $FleetRow['fleet_resource_deuterium'], $lang['Deuterium'] );
				SendSimpleMessage ( $TargetOwner, '', $FleetRow['fleet_start_time'], 5, $lang['sys_mess_tower'], $lang['sys_mess_transport'], $Message);
			}

			$QryUpdateFleet  = "UPDATE {{table}} SET ";
			$QryUpdateFleet .= "`fleet_resource_metal` = '0' , ";
			$QryUpdateFleet .= "`fleet_resource_crystal` = '0' , ";
			$QryUpdateFleet .= "`fleet_resource_deuterium` = '0' , ";
			$QryUpdateFleet .= "fleet_time = fleet_end_time, `fleet_mess` = '1' ";
			$QryUpdateFleet .= "WHERE `fleet_id` = '".$FleetRow['fleet_id']."' ";
			$QryUpdateFleet .= "LIMIT 1 ;";
			doquery( $QryUpdateFleet, 'fleets');
			return;
		}
	} else {
		if ($FleetRow['fleet_end_time'] < time()) {
			RestoreFleetToPlanet ( $FleetRow, true );
			doquery("DELETE FROM {{table}} WHERE fleet_id=" . $FleetRow["fleet_id"], 'fleets');
		}
	}
}

function MissionCaseCreateBase ( $FleetRow ) {
    global $lang, $resource, $user;

if ($FleetRow['fleet_mess'] == 0) {

	if ($FleetRow['fleet_start_time'] <= time()) {

	// Определяем максимальное колличество баз
    	$MaxBase = doquery("SELECT `fleet_base_tech` FROM {{table}} WHERE id={$FleetRow['fleet_owner']}",'users',true);
    	$iMaxBase = $MaxBase['fleet_base_tech'];

	// Получение общего колличества построенных баз
	$iPlanetCount = mysql_result(doquery ("SELECT count(*) FROM {{table}} WHERE `id_owner` = '". $FleetRow['fleet_owner'] ."' AND `planet_type` = '5'", 'planets'), 0);

		$iGalaxyPlace = mysql_result(doquery ("SELECT count(*) FROM {{table}} WHERE `galaxy` = '". $FleetRow['fleet_end_galaxy']."' AND `system` = '". $FleetRow['fleet_end_system']."' AND `planet` = '". $FleetRow['fleet_end_planet']."';", 'galaxy'), 0);
		$TargetAdress = sprintf ($lang['sys_adress_planet'], $FleetRow['fleet_end_galaxy'], $FleetRow['fleet_end_system'], $FleetRow['fleet_end_planet']);
		// Если в галактике пусто (планета не заселена)
		if ($iGalaxyPlace == 0) {
			// Если лимит баз исчерпан
          	if ($iPlanetCount >= $iMaxBase) {
              	$TheMessage = $lang['sys_colo_arrival'] . $TargetAdress . $lang['sys_colo_maxcolo'] . $iMaxBase . $lang['sys_base_planet'];
              	SendSimpleMessage ( $FleetRow['fleet_owner'], '', $FleetRow['fleet_start_time'], 0, $lang['sys_base_mess_from'], $lang['sys_base_mess_report'], $TheMessage);
              	doquery("UPDATE {{table}} SET fleet_time = fleet_end_time, `fleet_mess` = '1' WHERE `fleet_id` = ". $FleetRow["fleet_id"], 'fleets');
           	}else{
				// Создание планеты-базы
				$NewOwnerPlanet = CreateOnePlanetRecord($FleetRow['fleet_end_galaxy'], $FleetRow['fleet_end_system'], $FleetRow['fleet_end_planet'], $FleetRow['fleet_owner'], $lang['sys_base_defaultname'], false, true);
				// Если планета-база создана
				if ( $NewOwnerPlanet == true ) {
					$TheMessage = $lang['sys_colo_arrival'] . $TargetAdress . $lang['sys_base_allisok'];
					SendSimpleMessage ( $FleetRow['fleet_owner'], '', $FleetRow['fleet_start_time'], 0, $lang['sys_base_mess_from'], $lang['sys_base_mess_report'], $TheMessage);

					// Если летел один колонизатор
					if ($FleetRow['fleet_amount'] == 1) {
						doquery("DELETE FROM {{table}} WHERE fleet_id=" . $FleetRow["fleet_id"], 'fleets');
					} else {
						$CurrentFleet = explode(";", $FleetRow['fleet_array']);
						$NewFleet     = "";
						foreach ($CurrentFleet as $Item => $Group) {
							if ($Group != '') {
								$Class = explode (",", $Group);
								if ($Class[0] == 216) {
									if ($Class[1] > 1) {
										$NewFleet  .= $Class[0].",".($Class[1] - 1).";";
									}
								} else {
									if ($Class[1] <> 0) {
									$NewFleet  .= $Class[0].",".$Class[1].";";
									}
								}
							}
						}
						$QryUpdateFleet  = "UPDATE {{table}} SET ";
						$QryUpdateFleet .= "`fleet_array` = '". $NewFleet ."', ";
						$QryUpdateFleet .= "`fleet_amount` = `fleet_amount` - 1, ";
						$QryUpdateFleet .= "fleet_time = fleet_end_time, `fleet_mess` = '1' ";
						$QryUpdateFleet .= "WHERE `fleet_id` = '". $FleetRow["fleet_id"] ."';";
						doquery( $QryUpdateFleet, 'fleets');
						return;
					}
				} else {
					doquery("UPDATE {{table}} SET fleet_time = fleet_end_time, `fleet_mess` = '1' WHERE `fleet_id` = ". $FleetRow["fleet_id"], 'fleets');
					$TheMessage = $lang['sys_colo_arrival'] . $TargetAdress . $lang['sys_base_badpos'];
					SendSimpleMessage ( $FleetRow['fleet_owner'], '', $FleetRow['fleet_start_time'], 0, $lang['sys_base_mess_from'], $lang['sys_base_mess_report'], $TheMessage);
					return;
				}
			}
		} else {
			doquery("UPDATE {{table}} SET fleet_time = fleet_end_time, `fleet_mess` = '1' WHERE `fleet_id` = ". $FleetRow["fleet_id"], 'fleets');
			$TheMessage = $lang['sys_colo_arrival'] . $TargetAdress . $lang['sys_base_notfree'];
			SendSimpleMessage ( $FleetRow['fleet_owner'], '', $FleetRow['fleet_end_time'], 0, $lang['sys_base_mess_from'], $lang['sys_base_mess_report'], $TheMessage);
			return;
		}
		}
	} else {
		if ($FleetRow['fleet_end_time'] <= time()) {
			RestoreFleetToPlanet ( $FleetRow, true );
			doquery("DELETE FROM {{table}} WHERE fleet_id=".$FleetRow["fleet_id"], 'fleets');
			return;
		}
	}
}

?>
