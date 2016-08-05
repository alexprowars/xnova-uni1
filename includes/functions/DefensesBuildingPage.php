<?php

function DefensesBuildingPage ( &$CurrentPlanet, $CurrentUser ) {
 	global $lang, $resource, $dpath, $reslist;

	if (isset($_POST['fmenge'])) {

		$Missiles[502] = $CurrentPlanet[ $resource[502] ];
		$Missiles[503] = $CurrentPlanet[ $resource[503] ];
		$SiloSize      = $CurrentPlanet[ $resource[44] ];
		$MaxMissiles   = $SiloSize * 10;
		$BuildQueue    = $CurrentPlanet['b_hangar_id'];
		$BuildArray    = explode (";", $BuildQueue);
		for ($QElement = 0; $QElement < count($BuildArray); $QElement++) {

			$ElmentArray = explode (",", $BuildArray[$QElement] );

			if ($ElmentArray[0] == 502 && $ElmentArray[1] != 0) {
				$Missiles[502] += $ElmentArray[1];
			} elseif ($ElmentArray[0] == 503 && $ElmentArray[1] != 0) {
				$Missiles[503] += $ElmentArray[1];
			}
		}
		
		foreach($_POST['fmenge'] as $Element => $Count) {
		
			$Element = intval($Element);
			$Count   = intval($Count);
		
			if (in_array($Element, $reslist['defense'])) {
				
				// Проверка наличия куполов
				if (($Element == 407 || $Element == 408) && $Count > 0) {
				
					$InQueue 	= strpos ( $CurrentPlanet['b_hangar_id'], $Element.",");
					$IsBuild1 	= ($CurrentPlanet[$resource[407]] > 0) ? true : false;
					$IsBuild2 	= ($CurrentPlanet[$resource[408]] > 0) ? true : false;
				
					if ($Element == 407 && !$IsBuild1 && $InQueue === false)
						$Count = 1;

					if ($Element == 408 && !$IsBuild2 && $InQueue === false)
						$Count = 1;
						
					if ($InQueue === true) $Count = 0;
					if ($Count > 1) $Count = 1;
				}
				//

				if ($Count > 0) {

					if ( IsTechnologieAccessible ($CurrentUser, $CurrentPlanet, $Element) ) {
					
						$MaxElements   = GetMaxConstructibleElements ( $Element, $CurrentPlanet );

						if ($Element == 502 || $Element == 503) {
							$ActuMissiles  = $Missiles[502] + ( 2 * $Missiles[503] );
							$MissilesSpace = $MaxMissiles - $ActuMissiles;
							
							if ($MissilesSpace > 0) {	
								if ($Element == 502) {
									if ( $Count > $MissilesSpace )
										$Count = $MissilesSpace;
								} else {
									if ( $Count > floor( $MissilesSpace / 2 ) )
										$Count = floor( $MissilesSpace / 2 );
								}
							} else
								$Count = 0;
						}
						
						if ($Count > $MaxElements)
							$Count = $MaxElements;

						$Ressource = GetElementRessources ( $Element, $Count );
						$BuildTime = GetBuildingTime($CurrentUser, $CurrentPlanet, $Element);
						
						if ($Count > 0) {
							if ($CurrentUser['rpg_ingenieur'] > time()){
								$Ressource['metal'] 	= round($Ressource['metal'] * 0.9);
								$Ressource['crystal'] 	= round($Ressource['crystal'] * 0.9);
								$Ressource['deuterium'] = round($Ressource['deuterium'] * 0.9);
							}
							$CurrentPlanet['metal']           -= $Ressource['metal'];
							$CurrentPlanet['crystal']         -= $Ressource['crystal'];
							$CurrentPlanet['deuterium']       -= $Ressource['deuterium'];
							$CurrentPlanet['b_hangar_id']     .= "". $Element .",". $Count .";";
							
							doquery("UPDATE {{table}} SET metal = '".$CurrentPlanet['metal']."', crystal = '".$CurrentPlanet['crystal']."', deuterium = '".$CurrentPlanet['deuterium']."', b_hangar_id = '".$CurrentPlanet['b_hangar_id']."' WHERE id = ".$CurrentPlanet['id'].";", "planets");
						}
					}
				}
			}
		}
	}


	if ($CurrentPlanet[$resource[21]] == 0 && $CurrentPlanet['planet_type'] != 5) {
		message($lang['need_hangar'], $lang['tech'][21]);
	}

	$TabIndex  = 0;
	$PageTable = "";
	$i 		   = 0;
	foreach($lang['tech'] as $Element => $ElementName) {
		if ($Element > 400 && $Element <= 599) {
			if (IsTechnologieAccessible($CurrentUser, $CurrentPlanet, $Element)) {
				$i++;
				$CanBuildOne         = IsElementBuyable($CurrentUser, $CurrentPlanet, $Element, false);

				$BuildOneElementTime = GetBuildingTime($CurrentUser, $CurrentPlanet, $Element);

				$ElementCount        = $CurrentPlanet[$resource[$Element]];
				$ElementNbre         = ($ElementCount == 0) ? "<font color=#00FF00>0</font>" : "<font color=#00FF00>". pretty_number($ElementCount) . "</font>";

				if ($CurrentUser['design'] == 0)
					$lang['res']['descriptions'][$Element] = $ElementName;

				if ($i%2 == 1) $PageTable .= "<tr>";
				$PageTable .= '<td><table width="350" style="border-spacing:0px;"><tr><td class="l" width="120">';
				$PageTable .= "<a href=?set=infos&gid=".$Element.">";
				$PageTable .= "<img border=0 src=\"".$dpath."gebaeude/".$Element.".gif\" align=top width=120 height=120 onmouseover=\"return overlib('<center>".$lang['res']['descriptions'][$Element]."</center>',LEFT,WIDTH,200,FGCOLOR,'#465673');\" onmouseout=\"nd()\"></a>";
				$PageTable .= '</td><th style="text-align:left;vertical-align:top;">';
				$PageTable .= "<a href=?set=infos&gid=".$Element.">".$ElementName."</a><br><b>Колличество:</b> <u>&nbsp;".$ElementNbre."&nbsp;</u><br>";
				$PageTable .= ShowBuildTime($BuildOneElementTime);
				if ($CanBuildOne) {
				
					$BuildIt = true;
				
					if ($Element == 407 || $Element == 408) {
				
						$InQueue = strpos ( $CurrentPlanet['b_hangar_id'], $Element.",");
						$IsBuild1 = ($CurrentPlanet[$resource[407]] > 0) ? true : false;
						$IsBuild2 = ($CurrentPlanet[$resource[408]] > 0) ? true : false;

                        $BuildIt = false;
						
						if ( $InQueue === false && !$IsBuild1 && $Element == 407)
							$BuildIt = true;
						if ( $InQueue === false && !$IsBuild2 && $Element == 408)
							$BuildIt = true;
					}

					if ( !$BuildIt ) {
						$PageTable .= "<br><br><center><font color=\"red\">".$lang['only_one']."</font></center>";
					} else {
						$TabIndex++;
						$PageTable .= "<br><br><center><input type=text name=fmenge[".$Element."] alt='".$lang['tech'][$Element]."' size=5 maxlength=5 value=0 tabindex=".$TabIndex."></center>";
						$PageTable .= "<br><b>Максимум:</b> <u>&nbsp;<font color=green>".GetMaxConstructibleElements($Element, $CurrentPlanet)."</font>&nbsp;</u>";
					}
				}
				$PageTable .= "</th></tr><tr><td colspan='2' class='c'>";
				$PageTable .= GetElementPrice($CurrentUser, $CurrentPlanet, $Element, false);
				$PageTable .= "</td>";
				$PageTable .= '</tr>';
				$PageTable .= "</table></td>";
				if ($i%2 == 0) $PageTable .= "</tr>";
			}
		}
	}
	
	if ($i%2 == 1) {
		$PageTable .= '<td height="100%"><table width="350" style="border-spacing:0px;height:100%;"><tr><th>&nbsp;</th></tr></table></td></tr>';
	}

	if ($CurrentPlanet['b_hangar_id'] != '') {
		$BuildQueue = ElementBuildListBox( $CurrentUser, $CurrentPlanet );
	}

	$parse = $lang;

	$parse['buildlist']    = $PageTable;
	$parse['buildinglist'] = $BuildQueue;
	$page .= parsetemplate(gettemplate('buildings_defense'), $parse);
	
	display($page, $lang['Defense']);
}

?>