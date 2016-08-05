<?php

function ShowGalaxyFooter ( $Galaxy, $System ) {
	global $lang, $maxfleet_count, $fleetmax, $planetcount, $planetrow;

	$Result  = "";
	if ($planetcount == 1) {
		$PlanetCountMessage = $planetcount ." ". $lang['gf_cntmone'];
	} elseif ($planetcount == 0) {
		$PlanetCountMessage = $lang['gf_cntmnone'];
	} else {
		$PlanetCountMessage = $planetcount." " . $lang['gf_cntmsome'];
	}
	$LegendPopup = GalaxyLegendPopup ();
	$Recyclers   = pretty_number($planetrow['recycler']);
	$SpyProbes   = pretty_number($planetrow['spy_sonde']);

	$Result .= "\n";
	$Result .= "<tr>";
	$Result .= "<th width=\"30\">16</th>";
	$Result .= "<th colspan=7>";
	$Result .= "<a href=?set=fleet&galaxy=".$Galaxy."&amp;system=".$System."&amp;planet=16;planettype=1&amp;target_mission=15>". $lang['gf_unknowsp'] ."</a>";
	$Result .= "</th>";
	$Result .= "</tr>\n<tr>";
	$Result .= "<td class=c colspan=6>( ".$PlanetCountMessage." )</td>";
	$Result .= "<td class=c colspan=2>". $LegendPopup ."</td>";
	$Result .= "</tr>\n<tr>";
	$Result .= "<td class=c colspan=3><span id=\"missiles\">".  $planetrow['interplanetary_misil'] ."</span> ". $lang['gf_mi_title'] ."</td>";
	$Result .= "<td class=c colspan=3><span id=\"slots\">". $maxfleet_count ."</span>/". $fleetmax ." ". $lang['gf_fleetslt'] ."</td>";
	$Result .= "<td class=c colspan=2>";
	$Result .= "<span id=\"recyclers\">". $Recyclers ."</span> ". $lang['gf_rc_title'] ."<br>";
	$Result .= "<span id=\"probes\">". $SpyProbes ."</span> ". $lang['gf_sp_title'] ."</td>";
	$Result .= "</tr>";
	$Result .= "\n<tr style=\"display: none;\" id=\"fleetstatusrow\">";
	$Result .= "<th class=c colspan=8>";
	$Result .= "<table style=\"font-weight: bold\" width=\"100%\" id=\"fleetstatustable\">";
	$Result .= "</table>";
	$Result .= "</th>\n</tr>";

	return $Result;
}
?>