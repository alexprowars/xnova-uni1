<?php

function BatimentBuildingPage (&$CurrentPlanet, $CurrentUser) {
	global $ProdGrid, $officier, $lang, $resource, $reslist, $phpEx, $dpath, $game_config, $_GET;

	include_once('includes/functions/ShowBuildingQueue.php');
	include_once('includes/functions/BuildingSavePlanetRecord.php');
	include_once('includes/functions/AddBuildingToQueue.php');
	include_once('includes/functions/CancelBuildingFromQueue.php');

	CheckPlanetUsedFields ( $CurrentPlanet );

	$Allowed['1'] = array(  1,  2,  3,  4, 6, 12, 14, 15, 21, 22, 23, 24, 31, 33, 34, 44);
	$Allowed['3'] = array( 14, 21, 34, 41, 42, 43);
	$Allowed['5'] = array( 14, 34, 43, 44);

	if (isset($_GET['cmd'])) {
	
		$bDoItNow 	= false;
		$TheCommand = $_GET['cmd'];
		$Element 	= intval($_GET['building']);
		$ListID 	= intval($_GET['listid']);

		if ( in_array($Element, $Allowed[$CurrentPlanet['planet_type']]) ) {
			$bDoItNow = true;
		} elseif ( $ListID != 0 && ($TheCommand == 'remove' || $TheCommand == 'cancel') ) {
			$bDoItNow = true;
		}
		
		if ($bDoItNow == true) {
			switch($TheCommand){
				case 'cancel':
					CancelBuildingFromQueue ( $CurrentPlanet, $CurrentUser );
					break;
				case 'insert':
					AddBuildingToQueue ( $CurrentPlanet, $CurrentUser, $Element, true );
					break;
				case 'destroy':
					AddBuildingToQueue ( $CurrentPlanet, $CurrentUser, $Element, false );
					break;
				default:
					break;
			}
		}
	}

	SetNextQueueElementOnTop ( $CurrentPlanet, $CurrentUser );

	$Queue = ShowBuildingQueue ( $CurrentPlanet, $CurrentUser );

	$MaxBuidSize = MAX_BUILDING_QUEUE_SIZE;
	if ($CurrentUser['rpg_constructeur'] > time()) $MaxBuidSize += 2;

	if ($Queue['lenght'] < $MaxBuidSize) {
		$CanBuildElement = true;
	} else {
		$CanBuildElement = false;
	}

	$SubTemplate         = gettemplate('buildings_builds_row');
	$BuildingPage        = "";
	
	$i = 0;
	foreach($lang['tech'] as $Element => $ElementName) {
		if (in_array($Element, $Allowed[$CurrentPlanet['planet_type']])) {
			$CurrentMaxFields      = CalculateMaxPlanetFields($CurrentPlanet);
			if ($CurrentPlanet["field_current"] < ($CurrentMaxFields - $Queue['lenght'])) {
				$RoomIsOk = true;
			} else {
				$RoomIsOk = false;
			}

			if (IsTechnologieAccessible($CurrentUser, $CurrentPlanet, $Element)) {
				$i++;
				$HaveRessources        = IsElementBuyable ($CurrentUser, $CurrentPlanet, $Element, true, false);
				$parse                 = array();
				if ($i%2 == 1) $parse['td1'] = "<tr>";
				if ($i%2 == 0) $parse['td2'] = "</tr>";
				$parse['dpath']        = $dpath;
				$parse['i']            = $Element;
				$BuildingLevel         = $CurrentPlanet[$resource[$Element]];
				$parse['nivel']        = ($BuildingLevel == 0) ? "<font color=#FF0000>". $BuildingLevel ."</font>" : "<font color=#00FF00>". $BuildingLevel ."</font>";
				$parse['n']            = $ElementName;
				if ($CurrentUser['design'] == 1)
					$parse['descriptions'] = $lang['res']['descriptions'][$Element];
				else
					$parse['descriptions'] = $ElementName;
				
				$ElementBuildTime      = GetBuildingTime($CurrentUser, $CurrentPlanet, $Element);
				$parse['time']         = ShowBuildTime($ElementBuildTime);
				$parse['price']        = GetElementPrice($CurrentUser, $CurrentPlanet, $Element);
				$parse['add']		   = GetNextProduction ($Element, $BuildingLevel);
				$parse['click']        = '';
				$NextBuildLevel        = $CurrentPlanet[$resource[$Element]] + 1;

				if ($Element == 31) {
					if ($CurrentUser["b_tech_planet"] != 0) {
						$parse['click'] = "<font color=#FF0000>". $lang['in_working'] ."</font>";
					}
				}
				if ($parse['click'] != '') {
				} elseif ($RoomIsOk && $CanBuildElement) {
					if ($Queue['lenght'] == 0) {
						if ($NextBuildLevel == 1) {
							if ( $HaveRessources == true ) {
								$parse['click'] = "<a href=\"?set=buildings&cmd=insert&building=". $Element ."\"><font color=#00FF00>". $lang['BuildFirstLevel'] ."</font></a>";
							} else {
								$parse['click'] = "<font color=#FF0000>". $lang['BuildFirstLevel'] ."</font>";
							}
						} else {
							if ( $HaveRessources == true ) {
								$parse['click'] = "<a href=\"?set=buildings&cmd=insert&building=".$Element."\"><font color=#00FF00>". $lang['BuildNextLevel'] ." ". $NextBuildLevel ."</font></a>";
							} else {
								$parse['click'] = "<font color=#FF0000>".$lang['BuildNextLevel']." ".$NextBuildLevel."</font>";
							}
						}
					} else {
						$parse['click'] = "<a href=\"?set=buildings&cmd=insert&building=". $Element ."\"><font color=#00FF00>". $lang['InBuildQueue'] ."</font></a>";
					}
				} elseif ($RoomIsOk && !$CanBuildElement) {
					if ($NextBuildLevel == 1) {
						$parse['click'] = "<font color=#FF0000>". $lang['BuildFirstLevel'] ."</font>";
					} else {
						$parse['click'] = "<font color=#FF0000>". $lang['BuildNextLevel'] ." ". $NextBuildLevel ."</font>";
					}
				} else {
					$parse['click'] = "<font color=#FF0000>". $lang['NoMoreSpace'] ."</font>";
				}

				$BuildingPage .= parsetemplate($SubTemplate, $parse);
			}
		}
	}
	
	if ($i%2 == 1) {
		$BuildingPage .= '<td height="100%"><table width="350" style="border-spacing:0px;height:100%;"><tr><th>&nbsp;</th></tr></table></td></tr>';
	}

	$parse                         = $lang;

	if ($Queue['lenght'] > 0) {
		if ($CurrentUser['design'] == 1) $parse['BuildListScript']  = InsertBuildListScript ( "buildings" );
		$parse['BuildList']        = $Queue['buildlist'];
	} else {
		$parse['BuildListScript']  = "";
		$parse['BuildList']        = "";
	}

    	$parse['planet_field_current'] = $CurrentPlanet["field_current"];

	if($CurrentPlanet["planet_type"] != 3) {
    		$parse['planet_field_max']     = $CurrentPlanet['field_max'] + ($CurrentPlanet[$resource[33]] * 5);
	}elseif($CurrentPlanet["planet_type"] == 3) {
    		$parse['planet_field_max']     = $CurrentPlanet['field_max'];
	}

    $parse['field_libre']          = $parse['planet_field_max']  - $CurrentPlanet['field_current'];
	$parse['BuildingsList']        = $BuildingPage;

	$page .= parsetemplate(gettemplate('buildings_builds'), $parse);

	display($page, 'Постройки');
}

?>