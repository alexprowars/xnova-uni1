<?php

function SpyTarget ( $TargetPlanet, $Mode, $TitleString ) {
	global $lang, $resource;

	$LookAtLoop = true;
	if       ($Mode == 0) {
		$String  = "<table width=\"100%\"><tr><td class=\"c\" colspan=\"5\">";
		$String .= $TitleString ." ". $TargetPlanet['name'];
		$String .= " <a href=\"?set=galaxy&mode=3&galaxy=". $TargetPlanet["galaxy"] ."&system=". $TargetPlanet["system"]. "\">";
		$String .= "[". $TargetPlanet["galaxy"] .":". $TargetPlanet["system"] .":". $TargetPlanet["planet"] ."]</a>";
		$String .= "<br>на ". date("m-d H:i:s", time()) ."</td>";
		$String .= "</tr><tr>";
		$String .= "<th width=220>металла:</th><th width=220 align=right>". pretty_number($TargetPlanet['metal'])      ."</th><td>&nbsp;</td>";
		$String .= "<th width=220>кристалла:</th><th width=220 align=right>". pretty_number($TargetPlanet['crystal'])    ."</th>";
		$String .= "</tr><tr>";
		$String .= "<th width=220>дейтерия:</th><th width=220 align=right>". pretty_number($TargetPlanet['deuterium'])  ."</th><td>&nbsp;</td>";
		$String .= "<th width=220>энергии:</th><th width=220 align=right>". pretty_number($TargetPlanet['energy_max']) ."</th>";
		$String .= "</tr>";
		$LookAtLoop = false;
	} elseif ($Mode == 1) {
		$ResFrom[0] = 200;
		$ResTo[0]   = 299;
		$Loops      = 1;
	} elseif ($Mode == 2) {
		$ResFrom[0] = 400;
		$ResTo[0]   = 499;
		$ResFrom[1] = 500;
		$ResTo[1]   = 599;
		$Loops      = 2;
	} elseif ($Mode == 3) {
		$ResFrom[0] = 1;
		$ResTo[0]   = 99;
		$Loops      = 1;
	} elseif ($Mode == 4) {
		$ResFrom[0] = 100;
		$ResTo[0]   = 199;
		$Loops      = 1;
	} elseif ($Mode == 5) {
		$ResFrom[0] = 600;
		$ResTo[0]   = 615;
		$Loops      = 1;
	}

	if ($LookAtLoop == true) {
		$String  = "<table width=\"100%\" cellspacing=\"1\"><tr><td class=\"c\" colspan=\"". ((2 * SPY_REPORT_ROW) + (SPY_REPORT_ROW - 2))."\">". $TitleString ." &nbsp;&nbsp;&nbsp;&nbsp;</td></tr>";
		$Count       = 0;
		$CurrentLook = 0;
		while ($CurrentLook < $Loops) {
			$row     = 0;
			for ($Item = $ResFrom[$CurrentLook]; $Item <= $ResTo[$CurrentLook]; $Item++) {
				if ( $TargetPlanet[$resource[$Item]] > 0) {
					if ($row == 0) {
						$String  .= "</tr>";
					}
					$String  .= "<th width=40%>".$lang['tech'][$Item]."</th><th width=10%>".$TargetPlanet[$resource[$Item]]."</th>";

					$Count   += $TargetPlanet[$resource[$Item]];
					$row++;
					if ($row == SPY_REPORT_ROW) {
						//$String  .= "</tr>";
						$row      = 0;
					}
				}
			}

			while ($row != 0) {
				$String  .= "<th width=40%>&nbsp;</th><th width=10%>&nbsp;</th>";
				$row++;
				if ($row == SPY_REPORT_ROW) {
					$String  .= "</tr>";
					$row      = 0;
				}
			}
			$CurrentLook++;
		}
	}
	$String .= "</table>";

	$return['String'] = $String;
	$return['Count']  = $Count;
	return $return;
}
?>