<?php

function ResearchBuildingPage (&$CurrentPlanet, $CurrentUser, $InResearch, $ThePlanet) {
	global $lang, $resource, $reslist, $phpEx, $dpath, $game_config, $_GET;

	include_once('includes/functions/CheckLabSettingsInQueue.php');

	$NoResearchMessage = "";
	$bContinue         = true;

	if ($CurrentPlanet[$resource[31]] == 0) {
		message($lang['no_laboratory'], $lang['Research']);
	}
	if (!CheckLabSettingsInQueue ( $CurrentPlanet )) {
		$NoResearchMessage = $lang['labo_on_update'];
		$bContinue         = false;
	}
	
	$space_lab = array();

	if ($CurrentUser[$resource[123]] > 0) {
		$empire = doquery("SELECT `laboratory` FROM {{table}} WHERE id_owner='". $CurrentUser['id'] ."' AND id <> '".$CurrentPlanet['id']."';", 'planets');
		while ($colonie = mysql_fetch_assoc($empire)) {
			$space_lab[] = $colonie;
		}
	}

	if (isset($_GET['cmd']) AND $bContinue != false) {
		$TheCommand = $_GET['cmd'];
		$Techno     = intval($_GET['tech']);
		if ( is_numeric($Techno) ) {
			if ( in_array($Techno, $reslist['tech']) ) {

				if ( is_array ($ThePlanet) ) {
					$WorkingPlanet = $ThePlanet;
				} else {
					$WorkingPlanet = $CurrentPlanet;
				}
				switch($TheCommand){
					case 'cancel':
						if ($ThePlanet['b_tech_id'] == $Techno) {
							$nedeed                        = GetBuildingPrice($CurrentUser, $WorkingPlanet, $Techno);

							if ($ThePlanet['id'] == $CurrentPlanet['id']) {
								$CurrentPlanet['metal']       += $nedeed['metal'];
								$CurrentPlanet['crystal']     += $nedeed['crystal'];
								$CurrentPlanet['deuterium']   += $nedeed['deuterium'];
							}
							
							$WorkingPlanet['metal']       += $nedeed['metal'];
							$WorkingPlanet['crystal']     += $nedeed['crystal'];
							$WorkingPlanet['deuterium']   += $nedeed['deuterium'];
							$WorkingPlanet['b_tech_id']   = 0;
							$WorkingPlanet["b_tech"]      = 0;
							$CurrentUser['b_tech_planet'] = $WorkingPlanet["id"];
							$UpdateData                   = 1;
							$InResearch                   = false;
					    }

					break;
					case 'search':
						if ( IsTechnologieAccessible($CurrentUser, $WorkingPlanet, $Techno) && IsElementBuyable($CurrentUser, $WorkingPlanet, $Techno) && $ThePlanet['b_tech_id'] == 0) {
							$costs                        = GetBuildingPrice($CurrentUser, $WorkingPlanet, $Techno);
							$WorkingPlanet['metal']      -= $costs['metal'];
							$WorkingPlanet['crystal']    -= $costs['crystal'];
							$WorkingPlanet['deuterium']  -= $costs['deuterium'];
							$WorkingPlanet["b_tech_id"]   = $Techno;
							$WorkingPlanet["b_tech"]      = time() + GetBuildingTime($CurrentUser, $WorkingPlanet, $Techno, $space_lab);
							$CurrentUser["b_tech_planet"] = $WorkingPlanet["id"];
							$UpdateData                   = 1;
							$InResearch                   = true;
						}
						break;
				}
				if ($UpdateData == 1) {
					$QryUpdatePlanet  = "UPDATE {{table}} SET ";
					$QryUpdatePlanet .= "`b_tech_id` = '".   $WorkingPlanet['b_tech_id']   ."', ";
					$QryUpdatePlanet .= "`b_tech` = '".      $WorkingPlanet['b_tech']      ."', ";
					$QryUpdatePlanet .= "`metal` = '".       $WorkingPlanet['metal']       ."', ";
					$QryUpdatePlanet .= "`crystal` = '".     $WorkingPlanet['crystal']     ."', ";
					$QryUpdatePlanet .= "`deuterium` = '".   $WorkingPlanet['deuterium']   ."' ";
					$QryUpdatePlanet .= "WHERE ";
					$QryUpdatePlanet .= "`id` = '".          $WorkingPlanet['id']          ."';";
					doquery( $QryUpdatePlanet, 'planets');

					$QryUpdateUser  = "UPDATE {{table}} SET ";
					$QryUpdateUser .= "`b_tech_planet` = '". $CurrentUser['b_tech_planet'] ."' ";
					$QryUpdateUser .= "WHERE ";
					$QryUpdateUser .= "`id` = '".            $CurrentUser['id']            ."';";
					doquery( $QryUpdateUser, 'users');
				}
				if ( is_array ($ThePlanet) ) {
					$ThePlanet     = $WorkingPlanet;
				} else {
					$CurrentPlanet = $WorkingPlanet;
					if ($TheCommand == 'search') {
						$ThePlanet = $CurrentPlanet;
					}
				}
			}
		} else {
			$bContinue = false;
		}
	}

	$TechRowTPL = gettemplate('buildings_research_row');
	$TechScrTPL = gettemplate('buildings_research_script');
	$TechnoList = "";

	$i = 0;
	
	foreach($lang['tech'] as $Tech => $TechName) {
		if ($Tech > 105 && $Tech <= 199) {
			if ( IsTechnologieAccessible($CurrentUser, $CurrentPlanet, $Tech)) {
				$i++;
				$RowParse                	= $lang;
				$RowParse['dpath']       	= $dpath;
				$RowParse['tech_id']     	= $Tech;
				if ($i%2 == 1) $RowParse['td1'] = "<tr>";
				if ($i%2 == 0) $RowParse['td2'] = "</tr>";
				$building_level          	= $CurrentUser[$resource[$Tech]];
				$RowParse['tech_level']  	= ($building_level == 0) ?"<font color=#FF0000>". $building_level ."</font>" : "<font color=#00FF00>". $building_level ."</font>";
				$RowParse['tech_name']   	= $TechName;

				if ($CurrentUser['design'] == 1)
					$RowParse['tech_descr']  	= $lang['res']['descriptions'][$Tech];
				else
					$RowParse['tech_descr']	= $TechName;

				$RowParse['add']  			= $lang['res']['add'][$Tech];
				$RowParse['tech_price']  	= GetElementPrice($CurrentUser, $CurrentPlanet, $Tech);
				$SearchTime              	= GetBuildingTime($CurrentUser, $CurrentPlanet, $Tech, $space_lab);
				$RowParse['search_time'] 	= ShowBuildTime($SearchTime);
				$RowParse['tech_restp']  	= $lang['Rest_ress'] ." ". GetRestPrice ($CurrentUser, $CurrentPlanet, $Tech, true);
				$CanBeDone               	= IsElementBuyable($CurrentUser, $CurrentPlanet, $Tech);

				if (!$InResearch) {
					$LevelToDo = 1 + $CurrentUser[$resource[$Tech]];
					if ($CanBeDone) {
						if (!CheckLabSettingsInQueue ( $CurrentPlanet )) {

							if ($LevelToDo == 1) {
								$TechnoLink  = "<font color=#FF0000>". $lang['Rechercher'] ."</font>";
							} else {
								$TechnoLink  = "<font color=#FF0000>". $lang['Rechercher'] ." ".$lang['level']." ".$LevelToDo."</font>";
							}
						} else {
							$TechnoLink  = "<a href=\"?set=buildings&mode=research&cmd=search&tech=".$Tech."\">";
							if ($LevelToDo == 1) {
								$TechnoLink .= "<font color=#00FF00>". $lang['Rechercher'] ."</font>";
							} else {
								$TechnoLink .= "<font color=#00FF00>". $lang['Rechercher'] ." ".$lang['level']." ".$LevelToDo."</font>";
							}
							$TechnoLink  .= "</a>";
						}
					} else {
						if ($LevelToDo == 1) {
							$TechnoLink  = "<font color=#FF0000>". $lang['Rechercher'] ."</font>";
						} else {
							$TechnoLink  = "<font color=#FF0000>". $lang['Rechercher'] ." ".$lang['level']." ".$LevelToDo."</font>";
						}
					}

				} else {

					if ($ThePlanet["b_tech_id"] == $Tech) {
						$bloc       = $lang;
						if ($ThePlanet['id'] != $CurrentPlanet['id']) {
							$bloc['tech_time']  = $ThePlanet["b_tech"] - time();
							$bloc['tech_name']  = $lang['on'] ."<br>". $ThePlanet["name"];
							$bloc['tech_home']  = $ThePlanet["id"];
							$bloc['tech_id']    = $ThePlanet["b_tech_id"];
						} else {
							$bloc['tech_time']  = $CurrentPlanet["b_tech"] - time();
							$bloc['tech_name']  = "";
							$bloc['tech_home']  = $CurrentPlanet["id"];
							$bloc['tech_id']    = $CurrentPlanet["b_tech_id"];
						}
						$TechnoLink  = parsetemplate($TechScrTPL, $bloc);
					} else {
						$TechnoLink  = "<center>-</center>";
					}
				}
				$RowParse['tech_link']  = $TechnoLink;
				$TechnoList .= parsetemplate($TechRowTPL, $RowParse);
			}
		}
	}
	
	if ($i%2 == 1) {
		$TechnoList .= '<td height="100%"><table width="350" style="border-spacing:0px;height:100%;"><tr><th>&nbsp;</th></tr></table></td></tr>';
	}

	$PageParse                = $lang;
	$PageParse['noresearch']  = $NoResearchMessage;
	$PageParse['technolist']  = $TechnoList;

	display(parsetemplate(gettemplate('buildings_research'), $PageParse), $lang['Research'] );
}

?>