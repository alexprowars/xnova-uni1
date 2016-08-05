<?php

function ShowGalaxyRows ($Galaxy, $System) {
	global $lang, $game_config, $planetcount, $CurrentRC, $dpath, $user, $UserPoints, $HavePhalanx, $CurrentMIP, $planetrow, $CanDestroy;

	$GalaxyRow = doquery("SELECT g.planet, p.id AS id_planet, g.metal, g.crystal, p.name, p.planet_type, p.destruyed, p.image, p.last_update, p2.id AS luna_id, p2.name AS luna_name, p2.destruyed AS luna_destruyed, p2.last_update AS luna_update, p2.diameter AS luna_diameter, p2.temp_min AS luna_temp, u.id AS user_id, u.username, u.ally_id, u.authlevel, u.onlinetime, u.urlaubs_modus_time, u.banaday, u.avatar, a.ally_name, a.ally_members, a.ally_web, a.ally_tag, ad.type, s.total_rank, s.total_points  FROM game_galaxy g 
			LEFT JOIN game_planets p ON (g.id_planet = p.id) 
			LEFT JOIN game_planets p2 ON (g.id_luna = p2.id AND g.id_luna != 0) 
			LEFT JOIN game_users u ON (u.id = p.id_owner AND p.id_owner != 0) 
			LEFT JOIN game_alliance a ON (a.id = u.ally_id AND u.ally_id != 0) 
			LEFT JOIN game_alliance_diplo ad ON (((ad.t_al = u.ally_id AND ad.o_al = ".$user['ally_id'].") OR (ad.o_al = u.ally_id AND ad.t_al = ".$user['ally_id'].")) AND ad.status = 1 AND u.ally_id != 0)
			LEFT JOIN game_statpoints s ON (s.id_owner = u.id AND s.stat_type = '1' AND s.stat_code = '1') 
			WHERE g.`galaxy` = '".$Galaxy."' AND g.`system` = '".$System."';", '');

	while ($row = mysql_fetch_assoc($GalaxyRow)) {
	
		if ($row['luna_update'] != "" && $row['luna_update'] > $row['last_update'])
			$row['last_update'] = $row['luna_update'];
	
		$SystemGalaxy[$row['planet']] = $row;	
	}

	$Result = "";
	for ($Planet = 1; $Planet < 16; $Planet++) {

		$GalaX = $SystemGalaxy[$Planet];

		$Result .= "\n<tr>";
		
		if (isset($GalaX) && $GalaX["id_planet"] != 0) {

			if ($GalaX['destruyed'] != 0 AND $GalaX["id_planet"] != '') {
				CheckAbandonPlanetState ($GalaX);
			}
			if ($GalaX["luna_id"] != "" && $GalaX["luna_destruyed"] != 0) {
				CheckAbandonMoonState ($GalaX);
			}
			$planetcount++;
		} elseif (isset($GalaX) && $GalaX["id_planet"] == NULL) {
			doquery("DELETE FROM {{table}} WHERE galaxy = '".$Galaxy."' AND system = '".$System."' AND planet = '".$Planet."';", 'galaxy');
		}

		$Result  .= "<th width=30><a href=\"#\" tabindex=\"". ($Planet + 1) ."\">". $Planet ."</a></th>\n";

		$Result  .= "<th width=30>";
		if ($GalaX && $GalaX["destruyed"] == 0 && $GalaX["id_planet"] != 0) {
			$PhalanxTypeLink = "";
				if ($HavePhalanx <> 0 && $GalaX['user_id'] != $user['id']) {
						$Range = GetPhalanxRange ( $HavePhalanx );
						$SystemLimitMin = $planetrow['system'] - $Range;
						if ($SystemLimitMin < 1) $SystemLimitMin = 1;
						$SystemLimitMax = $planetrow['system'] + $Range;

						if ($System <= $SystemLimitMax && $System >= $SystemLimitMin)
								$PhalanxTypeLink = "<a href=# onclick=fenster(&#039;?set=phalanx&amp;galaxy=".$Galaxy."&amp;system=".$System."&amp;planet=".$Planet."&#039;) >".$lang['gl_phalanx']."</a><br />";
				}
	
			if ($GalaX['user_id'] != $user['id']) {
				$MissionType1Link = "<a href=?set=fleet&galaxy=".$Galaxy."&amp;system=".$System."&amp;planet=".$Planet."&amp;planettype=".$GalaX['planet_type']."&amp;target_mission=1>". $lang['type_mission'][1] ."</a><br />";
			} else
				$MissionType1Link = "";

			if ($GalaX['user_id'] != $user['id']) {
				$MissionType5Link = "<a href=?set=fleet&galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."&planettype=".$GalaX['planet_type']."&target_mission=5>". $lang['type_mission'][5] ."</a><br />";
			} else
				$MissionType5Link = "";

			if ($GalaX['user_id'] == $user['id']) {
				$MissionType4Link = "<a href=?set=fleet&galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."&planettype=".$GalaX['planet_type']."&target_mission=4>". $lang['type_mission'][4] ."</a><br />";
			} else
				$MissionType4Link = "";

			$MissionType3Link = "<a href=?set=fleet&galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."&planettype=".$GalaX['planet_type']."&target_mission=3>". $lang['type_mission'][3] ."</a>";
			$Result .= "<a style=\"cursor: pointer;\" onmouseover='return overlib(\"";
			$Result .= "<table width=240>";
			$Result .= "<tr><td class=c colspan=2>". $lang['gl_planet'] ." ". $GalaX["name"] ." [".$Galaxy.":".$System.":".$Planet."]</td></tr>";
			$Result .= "<tr>";
			$Result .= "<th width=80><img src=". $dpath ."planeten/small/s_". $GalaX["image"] .".jpg height=75 width=75 /></th>";
			$Result .= "<th align=left>".$PhalanxTypeLink.$MissionType1Link.$MissionType5Link.$MissionType4Link.$MissionType3Link."</th>";
			$Result .= "</tr>";
			$Result .= "</table>\", STICKY, MOUSEOFF, DELAY, 750, CENTER, OFFSETX, -40, OFFSETY, -40 );' onmouseout='return nd();'>";
			$Result .= "<img src=".$dpath ."planeten/small/s_". $GalaX["image"] .".jpg height=30 width=30></a>";
		}
		$Result .= "</th>";


		$Result  .= "<th style=\"white-space: nowrap;\" width=130>";
	
		if ($GalaX['ally_id'] == $user['ally_id'] AND $GalaX['user_id'] != $user['id'] AND $GalaX['ally_id'] != 0) {
			$TextColor = "<font color=\"green\">";
			$EndColor  = "</font>";
		} elseif ($GalaX['user_id'] == $user['id']) {
			$TextColor = "<font color=\"red\">";
			$EndColor  = "</font>";
		} else {
			$TextColor = '';
			$EndColor  = "";
		}
	
		if ($GalaX['last_update'] > (time()-59 * 60) AND $GalaX['user_id'] != $user['id']) {
			$Inactivity = pretty_time_hour(time() - $GalaX['last_update']);
		}
		if ($GalaX && $GalaX["destruyed"] == 0) {
			if ($HavePhalanx <> 0) {
				if ($Galaxy == $planetrow['galaxy']) {
					$Range = GetPhalanxRange ( $HavePhalanx );

					if ($planetrow['system'] - $Range <= $System AND $System <= $planetrow['system'] + $Range) {
						$PhalanxTypeLink = "<a href=\"#\" onclick=fenster('?set=phalanx&amp;galaxy=".$Galaxy."&amp;system=".$System."&amp;planet=".$Planet."&amp;planettype=3')  title=\"".$lang['gl_phalanx']."\">".$GalaX['name']."</a><br />";
					} else
						$PhalanxTypeLink = $GalaX['name'];
				} else
					$PhalanxTypeLink = $GalaX['name'];
			} else
				$PhalanxTypeLink = $GalaX['name'];
	
			$Result .= $TextColor . $PhalanxTypeLink . $EndColor;
	
			if ($GalaX['last_update']  > (time()-59 * 60) AND $GalaX['user_id'] != $user['id']) {
				if ($GalaX['last_update']  > (time()-10 * 60) AND $GalaX['user_id'] != $user['id'])
					$Result .= "(*)";
				else
					$Result .= " (".$Inactivity.")";
			}
		} elseif ($GalaX["destruyed"] != 0) {
			$Result .= $lang['gl_destroyedplanet'];
		}
		$Result .= "</th>";


		$Result  .= "<th style=\"white-space: nowrap;\" width=30>";
	
		if ($GalaX && $GalaX["luna_destruyed"] == 0 && $GalaX["luna_id"]) {

			if ($GalaX['user_id'] != $user['id']) {
				$MissionType1Link = "<a href=?set=fleet&galaxy=".$Galaxy."&amp;system=".$System."&amp;planet=".$Planet."&amp;planettype=3&amp;target_mission=1>". $lang['type_mission'][1] ."</a><br />";
			} else
				$MissionType1Link = "";
		
			if ($GalaX['user_id'] != $user['id']) {
				$MissionType5Link = "<a href=?set=fleet&galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."&planettype=3&target_mission=5>". $lang['type_mission'][5] ."</a><br />";
			} else
				$MissionType5Link = "";
	
			if ($GalaX['user_id'] == $user['id']) {
				$MissionType4Link = "<a href=?set=fleet&galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."&planettype=3&target_mission=4>". $lang['type_mission'][4] ."</a><br />";
			} else
				$MissionType4Link = "";
		
			if ($GalaX['user_id'] != $user['id']) {
				if ($CanDestroy > 0) {
					$MissionType9Link = "<a href=?set=fleet&galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."&planettype=3&target_mission=9>". $lang['type_mission'][9] ."</a>";
				} else {
					$MissionType9Link = "";
				}
			} else
				$MissionType9Link = "";
		
			$MissionType3Link = "<a href=?set=fleet&galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."&planettype=3&target_mission=3>". $lang['type_mission'][3] ."</a><br />";

			$Result .= "<a style=\"cursor: pointer;\" onmouseover='return overlib(\"";
			$Result .= "<table width=240>";
			$Result .= "<tr>";
			$Result .= "<td class=c colspan=2>";
			$Result .= $lang['Moon'].": ".$GalaX["luna_name"]." [".$Galaxy.":".$System.":".$Planet."]";
			$Result .= "</td>";
			$Result .= "</tr><tr>";
			$Result .= "<th width=80>";
			$Result .= "<img src=". $dpath ."planeten/mond.jpg height=75 width=75 />";
			$Result .= "</th>";
			$Result .= "<th>";
			$Result .= "<table>";
			$Result .= "<tr>";
			$Result .= "<td class=c colspan=2>".$lang['caracters']."</td>";
			$Result .= "</tr><tr>";
			$Result .= "<th>".$lang['diameter']."</th>";
			$Result .= "<th>". number_format($GalaX['luna_diameter'], 0, '', '.') ."</th>";
			$Result .= "</tr><tr>";
			$Result .= "<th>".$lang['temperature']."</th><th>". number_format($GalaX['luna_temp'], 0, '', '.') ."</th>";
			$Result .= "</tr><tr>";
			$Result .= "<td class=c colspan=2>".$lang['Actions']."</td>";
			$Result .= "</tr><tr>";
			$Result .= "<th colspan=2 align=center>".$MissionType3Link.$MissionType4Link.$MissionType1Link.$MissionType5Link.$MissionType9Link."</tr>";
			$Result .= "</table>";
			$Result .= "</th>";
			$Result .= "</tr>";
			$Result .= "</table>\"";
			$Result .= ", STICKY, MOUSEOFF, DELAY, 750, CENTER, OFFSETX, -40, OFFSETY, -40 );' onmouseout='return nd();'>";
			$Result .= "<img src=". $dpath ."planeten/small/s_mond.jpg height=22 width=22></a>";
		} elseif ($GalaX && $GalaX["luna_destruyed"] > 0 && $GalaX["luna_id"])
			$Result .= "~";
		else
            $Result .= "&nbsp;";
			
		$Result .= "</th>";


		if ($GalaX["metal"] != 0 || $GalaX["crystal"] != 0) {
			$Result  .= "<th style=\"";
			if       (($GalaX["metal"] + $GalaX["crystal"]) >= 10000000) {
				$Result .= "background-color: rgb(100, 0, 0);";
			} elseif (($GalaX["metal"] + $GalaX["crystal"]) >= 1000000) {
				$Result .= "background-color: rgb(100, 100, 0);";
			} elseif (($GalaX["metal"] + $GalaX["crystal"]) >= 100000) {
				$Result .= "background-color: rgb(0, 100, 0);";
			}
			$Result .= "background-image: none;\" width=30>";
			$Result .= "<a style=\"cursor: pointer;\"";
			$Result .= " onmouseover='return overlib(\"";
			$Result .= "<table width=240>";
			$Result .= "<tr>";
			$Result .= "<td class=c colspan=2>";
			$Result .= $lang['Debris']." [".$Galaxy.":".$System.":".$Planet."]";
			$Result .= "</td>";
			$Result .= "</tr><tr>";
			$Result .= "<th width=80>";
			$Result .= "<img src=". $dpath ."planeten/debris.jpg height=75 width=75 />";
			$Result .= "</th>";
			$Result .= "<th>";
			$Result .= "<table width=95%>";
			$Result .= "<tr>";
			$Result .= "<td class=c colspan=2>".$lang['gl_ressource']."</td>";
			$Result .= "</tr><tr>";
			$Result .= "<th>".$lang['Metal']." </th><th>". number_format( $GalaX['metal'], 0, '', '.') ."</th>";
			$Result .= "</tr><tr>";
			$Result .= "<th>".$lang['Crystal']." </th><th>". number_format( $GalaX['crystal'], 0, '', '.') ."</th>";
			$Result .= "</tr><tr>";
			$Result .= "<th colspan=2 align=left><a href=?set=fleet&galaxy=".$Galaxy."&amp;system=".$System."&amp;planet=".$Planet."&amp;planettype=2&amp;target_mission=8>Переработать</a></th>";
			$Result .= "</tr></table>";
			$Result .= "</th>";
			$Result .= "</tr>";
			$Result .= "</table>\"";
            		$Result .= ", STICKY, MOUSEOFF, DELAY, 750, CENTER, OFFSETX, -40, OFFSETY, -40 );' onmouseout='return nd();'>";
			$Result .= "<img src=". $dpath ."planeten/debris.jpg height=22 width=22></a>";
		} else 
			$Result  .= "<th style=\"white-space: nowrap;\" width=30>";
			$Result .= "</th>";


		$Result  .= "<th width=150>";
		if ($GalaX['user_id'] && $GalaX["destruyed"] == 0) {

			$CurrentPoints 	= $UserPoints['total_points'];
			$RowUserPoints 	= $GalaX['total_points'];
			if (!$RowUserPoints) $RowUserPoints = 0;
			if (!$GalaX['total_rank']) $GalaX['total_rank'] = 0;
			$CurrentLevel  	= $CurrentPoints * $game_config['noobprotectionmulti'];
			$RowUserLevel  	= $RowUserPoints * $game_config['noobprotectionmulti'];

			if ($GalaX['banaday'] > time() AND $GalaX['urlaubs_modus_time'] > 0) {
				$Systemtatus2 = $lang['vacation_shortcut']." <a href=\"?set=banned\"><span class=\"banned\">".$lang['banned_shortcut']."</span></a>";
				$Systemtatus  = "<span class=\"vacation\">";
			} elseif ($GalaX['banaday'] > time()) {
				$Systemtatus2 = "<a href=\"?set=banned\"><span class=\"banned\">".$lang['banned_shortcut']."</span></a>";
				$Systemtatus  = "";
			} elseif ($GalaX['urlaubs_modus_time'] > 0) {
				$Systemtatus2 = "<span class=\"vacation\">".$lang['vacation_shortcut']."</span>";
				$Systemtatus  = "<span class=\"vacation\">";
			} elseif ($GalaX['onlinetime'] < (time()-60 * 60 * 24 * 7) AND $GalaX['onlinetime'] > (time()-60 * 60 * 24 * 28)) {
				$Systemtatus2 = "<span class=\"inactive\">".$lang['inactif_7_shortcut']."</span>";
				$Systemtatus  = "<span class=\"inactive\">";
			} elseif ($GalaX['onlinetime'] < (time()-60 * 60 * 24 * 28)) {
				$Systemtatus2 = "<span class=\"inactive\">".$lang['inactif_7_shortcut']."</span><span class=\"longinactive\"> ".$lang['inactif_28_shortcut']."</span>";
				$Systemtatus  = "<span class=\"longinactive\">";
			} elseif ($RowUserLevel < $CurrentPoints AND $game_config['noobprotection'] == 1 AND $game_config['noobprotectiontime'] * 1000 > $RowUserPoints) {
				$Systemtatus2 = "<span class=\"noob\">".$lang['weak_player_shortcut']."</span>";
				$Systemtatus  = "<span class=\"noob\">";
			} elseif ($RowUserPoints > $CurrentLevel AND $game_config['noobprotection'] == 1 AND $game_config['noobprotectiontime'] * 1000 > $CurrentPoints) {
				$Systemtatus2 = $lang['strong_player_shortcut'];
				$Systemtatus  = "<span class=\"strong\">";
			} else {
				$Systemtatus2 = "";
				$Systemtatus  = "";
			}
			$Systemtatus4 = $GalaX['total_rank'];
			if ($Systemtatus2 != '') {
				$Systemtatus6 = "<font color=\"white\">(</font>";
				$Systemtatus66 = "<font color=\"white\">)</font>";
			}
			if ($Systemtatus2 == '') {
				$Systemtatus6 = "";
				$Systemtatus66 = "";
			}
			$admin = "";
			if ($GalaX['authlevel'] == 3) {
				$admin = " <font color=\"red\">A</font>";
			}
			$sgo = "";
			if ($GalaX['authlevel'] == 2) {
				$sgo = " <font color=\"orange\">SGo</font>";
			}
			$go = "";
			if ($GalaX['authlevel'] == 1) {
				$go = " <font color=\"green\">Go</font>";
			}
		
			$Systemtatus3 = $GalaX['username'];
		
			$Systemtart = $GalaX['total_rank'];
			if (strlen($Systemtart) < 3) {
				$Systemtart = 1;
			} else {
				$Systemtart = (floor( $GalaX['total_rank'] / 100 ) * 100) + 1;
			}
			$Result .= "<a style=\"cursor: pointer;\" onmouseover='return overlib(\"";
			$Result .= "<table width=210>";
			$Result .= "<tr>";
			$Result .= "<td class=c colspan=2>".$lang['Player']." ".$GalaX['username']." ".$lang['Place']." ".$Systemtatus4."</td>";
			$Result .= "</tr><tr>";
			$Result .= "<td width=60 height=64 rowspan=3 class=c";
			if ($GalaX['avatar'] != 0) {
				$Result .= " style=background-image:url(/images/avatars/".$GalaX['avatar'].".jpg);";
				$Result .= ">&nbsp;</td>";
			} else
				$Result .= ">нет<br>аватара</td>";
			if ($GalaX['user_id'] != $user['id']) {
				$Result .= "<td><a href=?set=messages&mode=write&id=".$GalaX['user_id'].">".$lang['gl_sendmess']."</a></td>";
				$Result .= "</tr><tr>";
				$Result .= "<td><a href=?set=buddy&a=2&u=".$GalaX['user_id'].">".$lang['gl_buddyreq']."</a></td>";
				$Result .= "</tr><tr>";
			}
			$Result .= "<td valign=top><a href=?set=stat&who=1&range=".$Systemtart."&pid=".$GalaX['user_id'].">".$lang['gl_stats']."</a></td>";
			$Result .= "</tr>";
			$Result .= "</table>\", STICKY, MOUSEOFF, DELAY, 750, CENTER, OFFSETX, -40, OFFSETY, -40 );' onmouseout='return nd();'>";
			$Result .= $Systemtatus.$Systemtatus3.$Systemtatus6.$Systemtatus.$Systemtatus2.$Systemtatus66.$admin.$sgo.$go;
			$Result .= "</span></a>";
		}
		$Result .= "</th>";


		$Result  .= "<th width=80>";
		if ($GalaX['ally_id'] != 0) {

			if ($GalaX['ally_name']) {
				$Result .= "<a style=\"cursor: pointer;\" onmouseover='return overlib(\"";
				$Result .= "<table width=240>";
				$Result .= "<tr>";
				$Result .= "<td class=c>".$lang['Alliance']." ". $GalaX['ally_name'] ." ".$lang['gl_with']." ". $GalaX['ally_members'] ." ". $lang['gl_membre'] ."</td>";
				$Result .= "</tr>";
				$Result .= "<th><table><tr>";
				$Result .= "<td><a href=?set=alliance&mode=ainfo&a=". $GalaX['ally_id'] .">".$lang['gl_ally_internal']."</a></td>";
				$Result .= "</tr><tr>";
				$Result .= "<td><a href=?set=stat&start=0&who=2>".$lang['gl_stats']."</a></td>";
				if ($GalaX["ally_web"] != "") {
					$Result .= "</tr><tr>";
					$Result .= "<td><a href=". $GalaX["ally_web"] ." target=_new>".$lang['gl_ally_web']."</td>";
				}
				$Result .= "</tr></table></th>";
				$Result .= "</table>\", STICKY, MOUSEOFF, DELAY, 750, CENTER, OFFSETX, -40, OFFSETY, -40 );' onmouseout='return nd();'>";
				if ($user['ally_id'] == $GalaX['ally_id']) {
					$Result .= "<span class=\"allymember\">". $GalaX['ally_tag'] ."</span></a>";
				} else {
					$Result .= $GalaX['ally_tag'] ."</a>";
				}
				if ($GalaX['ally_id'] != $user['ally_id']) {
					if ($GalaX['type'] == 0)
						$Result .= "<br><small>[нейтральное]</small>";
					elseif ($GalaX['type'] == 1)
						$Result .= "<br><small><font color=\"orange\">[перемирие]</font></small>";
					elseif ($GalaX['type'] == 2)
						$Result .= "<br><small><font color=\"green\">[мир]</font></small>";
					elseif ($GalaX['type'] == 3)
						$Result .= "<br><small><font color=\"red\">[война]</font></small>";
				}
			}
		}
		$Result .= "</th>";


		$Result  .= "<th style=\"white-space: nowrap;\" width=125>";
		if ($GalaX['user_id'] != $user['id']) {
	
			if ($planetrow['interplanetary_misil'] <> 0) {
				if ($Galaxy == $planetrow['galaxy']) {
					$Range = GetMissileRange();
					$SystemLimitMin = $planetrow['system'] - $Range;
					if ($SystemLimitMin < 1) {
						$SystemLimitMin = 1;
					}
					$SystemLimitMax = $planetrow['system'] + $Range;
					if ($System <= $SystemLimitMax) {
						if ($System >= $SystemLimitMin) {
							$MissileBtn = true;
						} else {
							$MissileBtn = false;
						}
					} else {
						$MissileBtn = false;
					}
				} else {
					$MissileBtn = false;
				}
			} else {
				$MissileBtn = false;
			}
	
			if ($GalaX['user_id'] && $GalaX["destruyed"] == 0) {

				$Result .= "<a href=?set=messages&mode=write&id=".$GalaX["user_id"].">";
				$Result .= "<img src=". $dpath ."img/m.gif alt=\"".$lang['gl_sendmess']."\" title=\"".$lang['gl_sendmess']."\" border=0></a>&nbsp;";

				$Result .= "<a href=?set=buddy&a=2&amp;u=".$GalaX["user_id"]." >";
				$Result .= "<img src=". $dpath ."img/b.gif alt=\"".$lang['gl_buddyreq']."\" title=\"".$lang['gl_buddyreq']."\" border=0></a>&nbsp;";

				if ($MissileBtn == true) {
					$Result .= "<a href=?set=galaxy&mode=2&galaxy=".$Galaxy."&system=".$System."&planet=".$Planet."&current=".$user['current_planet']." >";
					$Result .= "<img src=". $dpath ."img/r.gif alt=\"".$lang['gl_mipattack']."\" title=\"".$lang['gl_mipattack']."\" border=0></a>&nbsp;";
				}

				$Result .= "<a href=?set=fleet&galaxy=".$Galaxy."&amp;system=".$System."&amp;planet=".$Planet."&amp;planettype=".$GalaX['planet_type']."&amp;target_mission=6><img src=". $dpath ."img/e.gif alt=\"". $lang['type_mission'][6] ."\" title=\"". $lang['type_mission'][6] ."\" border=0></a>&nbsp;";

				$Result .= "<a href=?set=players&id=".$GalaX["user_id"].">";
				$Result .= "<img src=". $dpath ."img/s.gif alt=\"Информация об игроке\" title=\"Информация об игроке\" border=0></a>&nbsp;";

				$Result .= "<a href=?set=fleetshortcut&mode=add&g=".$Galaxy."&s=".$System."&i=".$Planet."&t=".$GalaX['planet_type'].">";
				$Result .= "<img src=". $dpath ."img/z.gif alt=\"Добавить в закладки\" title=\"Добавить в закладки\" border=0></a>";
			}
		}
		$Result .= "</th>";
		$Result .= "</tr>";
	}

	return $Result;
}
?>