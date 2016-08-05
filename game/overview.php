<?php

if(!defined("INSIDE")) die("attemp hacking");

if (isset($_GET['mode']))
	$mode = $_GET['mode'];
else
	$mode = "";

includeLang('overview');

include('includes/functions/BuildFleetEventTable.php');

if (!$_COOKIE['users_amount']) {
	$row = doquery("SELECT config_value FROM {{table}} WHERE config_name = 'users_amount'", 'config', true);
	setcookie("users_amount", $row['config_value']);
	$_COOKIE['users_amount'] = $row['config_value'];
}
$game_config['users_amount'] = $_COOKIE['users_amount'];

$galaxyrow = doquery("SELECT * FROM {{table}} WHERE `id_planet` = '".$planetrow['id']."';", 'galaxy', true);

switch ($mode) {
	case 'renameplanet':
		if ($_POST['action'] == $lang['namer']) {

			$UserPlanet     = CheckInputStrings ( $_POST['newname'] );

			if (trim($UserPlanet) != "") {
				if (eregi("^[a-zA-Zа-яА-Я0-9_\.\,\-\!\?\*\ ]+$", $UserPlanet)){
					if (strlen($UserPlanet) > 1 && strlen($UserPlanet) < 30){
						$newname        = mysql_escape_string(strip_tags(trim( $UserPlanet )));
						$planetrow['name'] = $newname;
						doquery("UPDATE {{table}} SET `name` = '".$newname."' WHERE `id` = '". $user['current_planet'] ."' LIMIT 1;", "planets");
					} else
						message('Введённо слишком длинное или короткое имя планеты'  , 'Ошибка', '?set=overview&mode=renameplanet', 5);
				} else
					message('Введённое имя содержит недопустимые символы'  , 'Ошибка', '?set=overview&mode=renameplanet', 5);
			}
		} elseif ($_POST['action'] == $lang['colony_abandon']) {

			$parse                   = $lang;
			$parse['planet_id']      = $planetrow['id'];
			$parse['galaxy_galaxy']  = $planetrow['galaxy'];
			$parse['galaxy_system']  = $planetrow['system'];
			$parse['galaxy_planet']  = $planetrow['planet'];
			$parse['planet_name']    = $planetrow['name'];

			$page .= parsetemplate(gettemplate('overview_deleteplanet'), $parse);
			
			display($page, $lang['rename_and_abandon_planet']);

		} elseif ($_POST['kolonieloeschen'] == 1 && $_POST['deleteid'] == $user['current_planet']) {
		
			$pass = doquery("SELECT password FROM {{table}} WHERE id = ".$user['id']."", "users_inf", true);

			if (md5($_POST['pw']) == $pass["password"] && $user['id_planet'] != $user['current_planet']) {
			
			    $checkFleets = doquery("SELECT COUNT(*) AS num FROM {{table}} WHERE (fleet_start_galaxy = ".$planetrow['galaxy']." AND fleet_start_system = ".$planetrow['system']." AND fleet_start_planet = ".$planetrow['planet']." AND fleet_start_type = ".$planetrow['planet_type'].") OR (fleet_end_galaxy = ".$planetrow['galaxy']." AND fleet_end_system = ".$planetrow['system']." AND fleet_end_planet = ".$planetrow['planet']." AND fleet_end_type = ".$planetrow['planet_type'].")", "fleets", true);

                if ($checkFleets['num'] > 0)
                    message('Нельзя удалять планету если с/на неё летит флот', $lang['colony_abandon'], '?set=overview&mode=renameplanet');
                else {
					$destruyed        = time() + 60 * 60 * 24;

					$QryUpdatePlanet  = "UPDATE {{table}} SET ";
					$QryUpdatePlanet .= "`destruyed` = '".$destruyed."', ";
					$QryUpdatePlanet .= "`id_owner` = '0' ";
					$QryUpdatePlanet .= "WHERE ";
					$QryUpdatePlanet .= "`id` = '".$user['current_planet']."' LIMIT 1;";
					doquery( $QryUpdatePlanet , 'planets');
					
					if ($galaxyrow['id_luna'] != 0) {
						$QryUpdatePlanet  = "UPDATE {{table}} SET ";
						$QryUpdatePlanet .= "`destruyed` = '".$destruyed."', ";
						$QryUpdatePlanet .= "`id_owner` = '0' ";
						$QryUpdatePlanet .= "WHERE ";
						$QryUpdatePlanet .= "`id` = '".$galaxyrow['id_luna']."' LIMIT 1;";
						doquery( $QryUpdatePlanet , 'planets');
					}

					$QryUpdateUser    = "UPDATE {{table}} SET ";
					$QryUpdateUser   .= "`current_planet` = `id_planet` ";
					$QryUpdateUser   .= "WHERE ";
					$QryUpdateUser   .= "`id` = '". $user['id'] ."' LIMIT 1";
					doquery( $QryUpdateUser, "users");

					message($lang['deletemessage_ok']   , $lang['colony_abandon'], '?set=overview&mode=renameplanet');
				}
			} elseif ($user['id_planet'] == $user["current_planet"])
				message($lang['deletemessage_wrong'], $lang['colony_abandon'], '?set=overview&mode=renameplanet');
			else
				message($lang['deletemessage_fail'] , $lang['colony_abandon'], '?set=overview&mode=renameplanet');
		}

		$parse = $lang;

		$parse['planet_id']     = $planetrow['id'];
		$parse['galaxy_galaxy'] = $planetrow['galaxy'];
		$parse['galaxy_system'] = $planetrow['system'];
		$parse['galaxy_planet'] = $planetrow['planet'];
		$parse['planet_name']   = $planetrow['name'];

		$page .= parsetemplate(gettemplate('overview_renameplanet'), $parse);

		display($page, $lang['rename_and_abandon_planet']);
		break;

	default:
		$Have_new_message = "";
		if ($user['new_message'] != 0) {
			$Have_new_message .= "<tr>";
			if ($user['new_message'] == 1) {
				$Have_new_message .= "<th colspan=4><a href=?set=messages>". $lang['Have_new_message']."</a></th>";
			} elseif ($user['new_message'] > 1) {
				$Have_new_message .= "<th colspan=4><a href=?set=messages>";
				$m = pretty_number($user['new_message']);
				$Have_new_message .= str_replace('%m', $m, $lang['Have_new_messages']);
				$Have_new_message .= "</a></th>";
			}
			$Have_new_message .= "</tr>";
		}

		$XpMinierUp  = $user['lvl_minier'] * 250;
		$XpRaidUp    = $user['lvl_raid']   * 250;
		$XpMinier    = $user['xpminier'];
		$XPRaid      = $user['xpraid'];

		$LvlUpMinier = $user['lvl_minier'] + 1;
		$LvlUpRaid   = $user['lvl_raid']   + 1;
		
		$up = 0;
		$HaveNewLevel = "";

		if ($XpMinier >= $XpMinierUp && $user['lvl_minier'] < 100) {
			$up = ($LvlUpMinier - 1) * 1000;
			$QryUpdateUser  = "UPDATE {{table}} SET ";
			$QryUpdateUser .= "`lvl_minier` = '".$LvlUpMinier."', ";
			$QryUpdateUser .= "`credits` = `credits` + ".$up.", ";
			$QryUpdateUser .= "`xpminier` = `xpminier` - $XpMinierUp ";
			$QryUpdateUser .= "WHERE ";
			$QryUpdateUser .= "`id` = '". $user['id'] ."';";
			doquery( $QryUpdateUser, 'users');
			$HaveNewLevel .= "<tr>";
			$HaveNewLevel .= "<th colspan=4><a href=?set=officier>". $lang['Have_new_level_mineur']."</a></th>";
			$user['lvl_minier'] = $LvlUpMinier;
			$user['xpminier'] -= $XpMinierUp;
		}
		if ($XPRaid >= $XpRaidUp && $user['lvl_raid'] < 100) {
			$up = ($LvlUpRaid - 1) * 1000;
			$QryUpdateUser  = "UPDATE {{table}} SET ";
			$QryUpdateUser .= "`lvl_raid` = '".$LvlUpRaid."', ";
			$QryUpdateUser .= "`credits` = `credits` + ".$up.", ";
			$QryUpdateUser .= "`xpraid` = `xpraid` - $XpRaidUp ";
			$QryUpdateUser .= "WHERE ";
			$QryUpdateUser .= "`id` = '". $user['id'] ."';";
			doquery( $QryUpdateUser, 'users');
			$HaveNewLevel .= "<tr>";
			$HaveNewLevel .= "<th colspan=4><a href=?set=officier>". $lang['Have_new_level_raid']."</a></th>";
			$user['lvl_raid'] = $LvlUpRaid;
			$user['xpraid'] -= $XpRaidUp;
		}

		if ($up != 0) {
			$r_id = $user['id'];
			$count = 2;

			while ($ref_array = mysql_fetch_assoc(mysql_query("SELECT * FROM game_refs WHERE r_id = ".$r_id.""))) {
				$r_id = $ref_array['u_id'];
				doquery("UPDATE {{table}} SET credits = credits + ".round($up / $count)." WHERE id = ".$r_id."", 'users');
				$count *= 2;
			}
		}

		$OwnFleets       = doquery("SELECT * FROM {{table}} WHERE `fleet_owner` = '". $user['id'] ."';", 'fleets');
		$Record          = 0;
		$fpage			 = array();
		
		while ($FleetRow = mysql_fetch_array($OwnFleets)) {
			$Record++;

			$StartTime   = $FleetRow['fleet_start_time'];
			$StayTime    = $FleetRow['fleet_end_stay'];
			$EndTime     = $FleetRow['fleet_end_time'];

			$Label = "fs";
			if ($StartTime > time()) {
				$fpage[$StartTime][$FleetRow['fleet_id']] = BuildFleetEventTable ( $FleetRow, 0, true, $Label, $Record );
			}

			$Label = "ft";
			if ($StayTime > time()) {
				$fpage[$StayTime][$FleetRow['fleet_id']] = BuildFleetEventTable ( $FleetRow, 1, true, $Label, $Record );
			}

			$Label = "fe";
			if (($EndTime > time() AND $FleetRow['fleet_mission'] != 4) OR ($FleetRow['fleet_mess'] == 1 AND $FleetRow['fleet_mission'] == 4)) {
				 $fpage[$EndTime][$FleetRow['fleet_id']]  = BuildFleetEventTable ( $FleetRow, 2, true, $Label, $Record );
			}
		}

		$OtherFleets     = doquery("SELECT * FROM {{table}} WHERE `fleet_target_owner` = '".$user['id']."';", 'fleets');

		$Record          = 2000;
		while ($FleetRow = mysql_fetch_array($OtherFleets)) {
			if ($FleetRow['fleet_owner'] != $user['id']) {
				if ($FleetRow['fleet_mission'] != 8) {
					$Record++;
					$StartTime = $FleetRow['fleet_start_time'];
					$StayTime  = $FleetRow['fleet_end_stay'];

					if ($StartTime > time()) {
						$Label = "ofs";
						$fpage[$StartTime][$FleetRow['fleet_id']] = BuildFleetEventTable ( $FleetRow, 0, false, $Label, $Record );
					}
					if ($FleetRow['fleet_mission'] == 5) {
						$Label = "oft";
						if ($StayTime > time()) {
							$fpage[$StayTime][$FleetRow['fleet_id']] = BuildFleetEventTable ( $FleetRow, 1, false, $Label, $Record );
						}
					}
				}
			}
		}

		$planets_query = doquery("SELECT * FROM {{table}} WHERE id_owner='".$user['id']."' AND `planet_type` != '3' AND `planet_type` != '5'", "planets");
		$Colone  = 1;

		$AllPlanets = "<tr>";
		while ($UserPlanet = mysql_fetch_assoc($planets_query)) {
			if ($UserPlanet["id"] != $user["current_planet"] && $UserPlanet['planet_type'] != 3) {
				$AllPlanets .= "<th>". $UserPlanet['name'] ."<br>";
				$AllPlanets .= "<a href=\"?set=overview&amp;cp=". $UserPlanet['id'] ."&amp;re=0\" title=\"". $UserPlanet['name'] ."\"><img src=\"". $dpath ."planeten/small/s_". $UserPlanet['image'] .".jpg\" height=\"50\" width=\"50\"></a><br>";
				$AllPlanets .= "<center>";

				if ($UserPlanet['b_building'] != 0) {
					UpdatePlanetBatimentQueueList ( $UserPlanet, $user );
					if ( $UserPlanet['b_building'] != 0 ) {
						$BuildQueue      = $UserPlanet['b_building_id'];
						$QueueArray      = explode ( ";", $BuildQueue );
						$CurrentBuild    = explode ( ",", $QueueArray[0] );
						$BuildElement    = $CurrentBuild[0];
						$BuildLevel      = $CurrentBuild[1];
						$BuildRestTime   = pretty_time( $CurrentBuild[3] - time() );
						$AllPlanets     .= '' . $lang['tech'][$BuildElement] . ' (' . $BuildLevel . ')';
						$AllPlanets     .= "<br><font color=\"#7f7f7f\">(". $BuildRestTime .")</font>";
					} else {
						CheckPlanetUsedFields ($UserPlanet);
						$AllPlanets     .= $lang['Free'];
					}
				} else {
					$AllPlanets    .= $lang['Free'];
				}

				$AllPlanets .= "</center></th>";
				if ($Colone <= 1) {
					$Colone++;
				} else {
					$AllPlanets .= "</tr><tr>";
					$Colone      = 1;
				}
			}
		}

		$iraks_query = doquery("SELECT * FROM {{table}} WHERE owner = '".$user['id']."' OR zielid = '".$user['id']."'", 'iraks');
		$Record = 4000;
		while ($irak = mysql_fetch_assoc ($iraks_query)) {
			$Record++;
			$fpage[$irak['zeit']][$irak['id']] = '';

			if ($irak['zeit'] > time()) {
				$time = $irak['zeit'] - time();

				$fpage[$irak['zeit']][$irak['id']] .= InsertJavaScriptChronoApplet ( "fm", $Record, $time, true );

				if ($irak['planet_type'] == 3)
					$lune1 = "(луна)";
				if ($irak['planet_angreifer_type'] == 3)
					$lune2 = "(луна)";

				$fpage[$irak['zeit']][$irak['id']] .= "<tr><th><div id=\"bxxfm".$Record."\" class=\"z\"></div><font color=\"lime\">" . date("H:i:s", $irak['zeit']) . "</font> </th><th colspan=\"3\"><font color=\"#0099FF\">Межпланетная атака (" . $irak['anzahl'] . " ракет) из координат ";
				$fpage[$irak['zeit']][$irak['id']] .= '<a href="?set=galaxy&amp;mode=3&amp;galaxy=' . $irak["galaxy_angreifer"] . '&amp;system=' . $irak["system_angreifer"] . '&amp;planet=' . $irak["planet_angreifer"] . '">[' . $irak["galaxy_angreifer"] . ':' . $irak["system_angreifer"] . ':' . $irak["planet_angreifer"] . ']</a> '.$lune1;
				$fpage[$irak['zeit']][$irak['id']] .= ' на координаты ';
				$fpage[$irak['zeit']][$irak['id']] .= '<a href="?set=galaxy&amp;mode=3&amp;galaxy=' . $irak["galaxy"] . '&amp;system=' . $irak["system"] . '&amp;planet=' . $irak["planet"] . '">[' . $irak["galaxy"] . ':' . $irak["system"] . ':' . $irak["planet"] . ']</a> '.$lune2;
				$fpage[$irak['zeit']][$irak['id']] .= '</font>';
				$fpage[$irak['zeit']][$irak['id']] .= InsertJavaScriptChronoApplet ( "fm", $Record, $time, false );
				$fpage[$irak['zeit']][$irak['id']] .= "</th>";
			}
		}

		$parse = $lang;

		if ($galaxyrow['id_luna'] != '0' && $planetrow['planet_type'] != '3' && $planetrow['id']) {
			$lune = doquery("SELECT `id`, `name`, `image` FROM {{table}} WHERE galaxy={$planetrow['galaxy']} AND system={$planetrow['system']} AND planet={$planetrow['planet']} AND planet_type='3'", 'planets', true);
			$parse['moon_img'] = "<a href=\"?set=overview&amp;cp={$lune['id']}&amp;re=0\" title=\"{$lune['name']}\"><img src=\"{$dpath}planeten/{$lune['image']}.jpg\" height=\"50\" width=\"50\"></a>";
			$parse['moon'] = $lune['name'];
		} else {
			$parse['moon_img'] = "";
			$parse['moon'] = "";
		}

		$parse['planet_name']          = $planetrow['name'];
		$parse['planet_diameter']      = pretty_number($planetrow['diameter']);
		$parse['planet_field_current'] = $planetrow['field_current'];
		$parse['planet_field_max']     = CalculateMaxPlanetFields($planetrow);
		$parse['planet_temp_min']      = $planetrow['temp_min'];
		$parse['planet_temp_max']      = $planetrow['temp_max'];
		$parse['galaxy_galaxy']        = $planetrow['galaxy'];
		$parse['galaxy_planet']        = $planetrow['planet'];
		$parse['galaxy_system']        = $planetrow['system'];
		$StatRecord = doquery("SELECT `build_points`, `tech_points`, `fleet_count`, `total_points`, `total_old_rank`, `total_rank` FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '". $user['id'] ."';", 'statpoints', true);

		$parse['user_points']          = pretty_number( $StatRecord['build_points'] );
		$parse['player_points_tech']   = pretty_number( $StatRecord['tech_points'] );
		$parse['total_points']         = pretty_number( $StatRecord['total_points'] );
		$parse['user_fleet']           = pretty_number( $StatRecord['fleet_count'] );

		$parse['user_rank']            = $StatRecord['total_rank'] + 0;
		$ile = $StatRecord['total_old_rank'] - $StatRecord['total_rank'];
		if ($ile >= 1) {
			$parse['ile']              = "<font color=lime>+".$ile."</font>";
		} elseif ($ile < 0) {
			$parse['ile']              = "<font color=red>".$ile."</font>";
		} elseif ($ile == 0) {
			$parse['ile']              = "<font color=lightblue>".$ile."</font>";
		}
		$parse['user_username']        = $user['username'];

		$flotten = "";
		
		if (count($fpage) > 0) {
			ksort($fpage);
			foreach ($fpage as $time => $content) {
				foreach ($content AS $flid => $text) {
					$flotten .= $text . "\n";
				}
			}
		}

		$parse['fleet_list']  = $flotten;
		$parse['energy_used'] = $planetrow["energy_max"] - $planetrow["energy_used"];

		$parse['Have_new_message']      = $Have_new_message;
		$parse['Have_new_level'] 		= $HaveNewLevel;
		$parse['time']                  = date("d-m-Y H:i:s", time());
		$parse['dpath']                 = $dpath;
		$parse['planet_image']          = $planetrow['image'];
		$parse['anothers_planets']      = $AllPlanets;
		$parse['max_users']             = $game_config['users_amount'];

		$parse['metal_debris']          = pretty_number($galaxyrow['metal']);
		$parse['crystal_debris']        = pretty_number($galaxyrow['crystal']);
		if (($galaxyrow['metal'] != 0 || $galaxyrow['crystal'] != 0) && $planetrow[$resource[209]] != 0) {
			$parse['get_link'] = " (<a href=\"?set=quickfleet&amp;mode=8&amp;g=".$galaxyrow['galaxy']."&amp;s=".$galaxyrow['system']."&amp;p=".$galaxyrow['planet']."&amp;t=2\">". $lang['type_mission'][8] ."</a>)";
		} else {
			$parse['get_link'] = '';
		}

		if ( $planetrow['b_building'] != 0 ) {
			UpdatePlanetBatimentQueueList ( $planetrow, $user );
			if ( $planetrow['b_building'] != 0 ) {
				$BuildQueue = explode (";", $planetrow['b_building_id']);
				$CurrBuild  = explode (",", $BuildQueue[0]);
				$RestTime   = $planetrow['b_building'] - time();
				$PlanetID   = $planetrow['id'];
				$Build  = InsertBuildListScript ( "overview" );
				$Build .= $lang['tech'][$CurrBuild[0]] .' ('. ($CurrBuild[1]) .')';
				$Build .= "<br /><div id=\"blc\" class=\"z\">". pretty_time( $RestTime ) ."</div>";
				$Build .= "\n<script language=\"JavaScript\">";
				$Build .= "\n	pp = \"". $RestTime ."\";\n";
				$Build .= "\n	pk = \"". 1 ."\";\n";
				$Build .= "\n	pm = \"cancel\";\n";
				$Build .= "\n	pl = \"". $PlanetID ."\";\n";
				$Build .= "\n	t();\n";
				$Build .= "\n</script>\n";

				$parse['building'] = $Build;
			} else {
				$parse['building'] = $lang['Free'];
			}
		} else {
			$parse['building'] = $lang['Free'];
		}

		$parse['case_pourcentage'] = floor($planetrow["field_current"] / CalculateMaxPlanetFields($planetrow) * 100);

		if ($parse['case_pourcentage'] > 80) {
			$parse['case_barre_barcolor'] = '#C00000';
		} elseif ($parse['case_pourcentage'] > 60) {
			$parse['case_barre_barcolor'] = '#C0C000';
		} else {
			$parse['case_barre_barcolor'] = '#00C000';
		}

		$parse['xpminier']= round($user['xpminier']);
		$parse['xpraid']= round($user['xpraid']);
		$parse['lvl_minier'] = $user['lvl_minier'];
		$parse['lvl_raid'] = $user['lvl_raid'];
		$parse['user_id']= $user['id'];
		$parse['links'] = $user['links'];

		// Загрязнение и экология
		$musor_min = $user['ecology_tech'] * 0.05;
		if ($musor_min > 0.75)
			$musor_min = 0.75;

		$musor_percent = round(($planetrow['field_current'] - $planetrow['solar_plant']) * (1 - $musor_min));

		//if ($musor_percent > 100)
		//	$musor_percent = 100;

		if ($musor_percent < 25)
			$parse['ecology'] = "<font color=green>райская</font>";
		elseif ($musor_percent < 50)
			$parse['ecology'] = "<font color=white>нормальная</font>";
		elseif ($musor_percent < 75)
			$parse['ecology'] = "<font color=yellow>умеренная</font>";
		else
			$parse['ecology'] = "<font color=red>ужасная</font>";

		$parse['musor'] = $musor_percent;

		$parse['raids_win'] = $user['raids_win'];
		$parse['raids_xz'] = $user['raids_xz'];
		$parse['raids_lose'] = $user['raids_lose'];
		$parse['raids'] = $user['raids'];

		$LvlMinier = $user['lvl_minier'];
		$LvlRaid = $user['lvl_raid'];
		$parse['lvl_up_minier'] = $LvlMinier * 250;
		$parse['lvl_up_raid']   = $LvlRaid * 250;

		$my_ip = explode(".", $_SERVER['HTTP_X_REAL_IP']);
		if ($my_ip['0'] == "10" || $my_ip['0'] == "172")
			$parse['banner'] = "локальный режим игры";
		else
			$parse['banner'] = "<a target=\"_blank\" href=\"http://top.mail.ru/jump?from=1436203\"><img src=\"http://da.ce.b5.a1.top.mail.ru/counter?id=1436203;t=82\" border=\"0\" height=\"18\" width=\"88\" alt=\"Рейтинг@Mail.ru\"/></a>";

		$page = parsetemplate(gettemplate('overview_body'), $parse);

		display($page, 'Обзор');
}

?>
