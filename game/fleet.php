<?php

if(!defined("INSIDE")) die("attemp hacking");

$maxfleet  = doquery("SELECT COUNT(fleet_owner) AS `actcnt` FROM {{table}} WHERE `fleet_owner` = '".$user['id']."';", 'fleets', true);

$MaxFlyingFleets     = $maxfleet['actcnt'];


$MaxExpedition      = $user[$resource[124]];
if ($MaxExpedition >= 1) {
	$maxexpde  = doquery("SELECT COUNT(fleet_owner) AS `expedi` FROM {{table}} WHERE `fleet_owner` = '".$user['id']."' AND `fleet_mission` = '15';", 'fleets', true);
		$ExpeditionEnCours  = $maxexpde['expedi'];
	$EnvoiMaxExpedition = 1 + floor( $MaxExpedition / 3 );
}

$MaxFlottes         = 1 + $user[$resource[108]];
if ($user['rpg_admiral'] > time()) $MaxFlottes += 2; 

CheckPlanetUsedFields($planetrow);

includeLang('fleet');

$missiontype = array(
	1 => $lang['type_mission'][1],
	2 => $lang['type_mission'][2],
	3 => $lang['type_mission'][3],
	4 => $lang['type_mission'][4],
	5 => $lang['type_mission'][5],
	6 => $lang['type_mission'][6],
	7 => $lang['type_mission'][7],
	8 => $lang['type_mission'][8],
	9 => $lang['type_mission'][9],
	15 => $lang['type_mission'][15]
);

$galaxy         = intval($_GET['galaxy']);
$system         = intval($_GET['system']);
$planet         = intval($_GET['planet']);
$planettype     = intval($_GET['planettype']);
$target_mission = intval($_GET['target_mission']);

if (!$galaxy) {
	$galaxy = $planetrow['galaxy'];
}
if (!$system) {
	$system = $planetrow['system'];
}
if (!$planet) {
	$planet = $planetrow['planet'];
}
if (!$planettype) {
	$planettype = $planetrow['planet_type'];
}

$page  = "<script language=\"JavaScript\" src=\"scripts/flotten.js\"></script>\n";
$page .= "<script language=\"JavaScript\" src=\"scripts/ocnt.js\"></script>\n";
$page .= "<br><center>";
$page .= "<table width='710' border='0' cellpadding='0' cellspacing='1'>";
$page .= "<tr height='20'>";
$page .= "<td colspan='9' class='c'>";
$page .= "<table border=\"0\" width=\"100%\">";
$page .= "<tbody><tr>";
$page .= "<td style=\"background-color: transparent;\">";
$page .= $lang['fl_title']." ".$MaxFlyingFleets." ".$lang['fl_sur']." ".$MaxFlottes;
$page .= "</td><td style=\"background-color: transparent;\" align=\"right\">";
$page .= (0+$ExpeditionEnCours)."/".(0+$EnvoiMaxExpedition)." ".$lang['fl_expttl'];
$page .= "</td>";
$page .= "</tr></tbody></table>";
$page .= "</td>";
$page .= "</tr><tr height='20'>";
$page .= "<th>".$lang['fl_id']."</th>";
$page .= "<th>".$lang['fl_mission']."</th>";
$page .= "<th>".$lang['fl_count']."</th>";
$page .= "<th>".$lang['fl_from']."</th>";
$page .= "<th>".$lang['fl_start_t']."</th>";
$page .= "<th>".$lang['fl_dest']."</th>";
$page .= "<th>".$lang['fl_dest_t']."</th>";
$page .= "<th>".$lang['fl_back_in']."</th>";
$page .= "<th>".$lang['fl_order']."</th>";
$page .= "</tr>";

$fq = doquery("SELECT * FROM {{table}} WHERE fleet_owner={$user[id]}", "fleets");
$i  = 0;


while ($f = mysql_fetch_array($fq)) {
	$i++;
	$page .= "<tr height=20>";
	$page .= "<th>".$i."</th>";
	$page .= "<th>";
	$page .= "<a>". $missiontype[$f[fleet_mission]] ."</a>";
	if (($f['fleet_start_time'] + 1) == $f['fleet_end_time']) {
		$page .= "<br><a title=\"".$lang['fl_back_to_ttl']."\">".$lang['fl_back_to']."</a>";
	} else {
		$page .= "<br><a title=\"".$lang['fl_get_to_ttl']."\">".$lang['fl_get_to']."</a>";
	}
	$page .= "</th>";
	$page .= "<th><a title=\"";
	$fleet = explode(";", $f['fleet_array']);
	$e = 0;
	foreach ($fleet as $a => $b) {
		if ($b != '') {
			$e++;
			$a = explode(",", $b);
			$page .= $lang['tech'][$a[0]]. ":". $a[1] ."\n";
			if ($e > 1) {
				$page .= "\t";
			}
		}
	}
	$page .= "\">". pretty_number($f[fleet_amount]) ."</a></th>";
	$page .= "<th><a href=\"?set=galaxy&mode=0&galaxy=".$f[fleet_start_galaxy]."&system=".$f[fleet_start_system]."\">[".$f[fleet_start_galaxy].":".$f[fleet_start_system].":".$f[fleet_start_planet]."]</a></th>";
	$page .= "<th>". date("d M y H:i:s", $f['fleet_start_time']) ."</th>";
	$page .= "<th><a href=\"?set=galaxy&mode=0&galaxy=".$f[fleet_end_galaxy]."&system=".$f[fleet_end_system]."\">[".$f[fleet_end_galaxy].":".$f[fleet_end_system].":".$f[fleet_end_planet]."]</a></th>";
	$page .= "<th>". date("d M y H:i:s", $f['fleet_end_time']) ."</th>";
	$page .= "<th><font color=\"lime\"><div id=\"time_0\"><font>". pretty_time(floor($f['fleet_end_time'] + 1 - time())) ."</font></th>";
	$page .= "<th>";
	if ($f['fleet_mess'] == 0) {
			$page .= "<form action=\"?set=fleetback\" method=\"post\">";
			$page .= "<input name=\"fleetid\" value=\"". $f['fleet_id'] ."\" type=\"hidden\">";
			$page .= "<input value=\" ".$lang['fl_back_to_ttl']." \" type=\"submit\" name=\"send\">";
			$page .= "</form>";
		if ($f[fleet_mission] == 1) {
			$page .= "<form action=\"?set=verband\" method=\"post\">";
			$page .= "<input name=\"fleetid\" value=\"". $f['fleet_id'] ."\" type=\"hidden\">";
			$page .= "<input value=\" ".$lang['fl_associate']." \" type=\"submit\">";
			$page .= "</form>";
		}

	} elseif ($f['fleet_mess'] == 3) {
			$page .= "<form action=\"?set=fleetback\" method=\"post\">";
			$page .= "<input name=\"fleetid\" value=\"". $f['fleet_id'] ."\" type=\"hidden\">";
			$page .= "<input value=\" Отозвать \" type=\"submit\" name=\"send\">";
			$page .= "</form>";
	} else {
		$page .= "&nbsp;-&nbsp;";
	}
	$page .= "</th>";
	$page .= "</tr>";
}


if ($i == 0) {
	$page .= "<tr>";
	$page .= "<th>-</th>";
	$page .= "<th>-</th>";
	$page .= "<th>-</th>";
	$page .= "<th>-</th>";
	$page .= "<th>-</th>";
	$page .= "<th>-</th>";
	$page .= "<th>-</th>";
	$page .= "<th>-</th>";
	$page .= "<th>-</th>";
	$page .= "</tr>";
}

if ($MaxFlottes == $MaxFlyingFleets) {
	$page .= "<tr height=\"20\"><th colspan=\"9\"><font color=\"red\">".$lang['fl_noslotfree']."</font></th></tr>";
}

$page .= "</table></center>";

$page .= "<center>";

$page .= "<script>";
$page .= "function chShipCount(id, diff){";
$page .= "	diff = 1 * diff;";
$page .= "	var ncur = 1 * document.getElementsByName(\"ship\" + id)[0].value;";
$page .= "	count = ncur + diff;";
$page .= "	if(count < 0){";
$page .= "		count = 0;";
$page .= "	};";
$page .= "	if(count > document.getElementsByName(\"maxship\" + id)[0].value){";
$page .= "		count = document.getElementsByName(\"maxship\" + id)[0].value;";
$page .= "	};";
$page .= "	document.getElementsByName(\"ship\" + id)[0].value = count;";
$page .= "}";
$page .= "</script>";

$page .= "<form action=\"?set=floten1\" method=\"post\">";
$page .= "<table width=\"519\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\">";
$page .= "<tr height=\"20\">";
$page .= "<td colspan=\"4\" class=\"c\">".$lang['fl_new_miss']."</td>";
$page .= "</tr>";
$page .= "<tr height=\"20\">";
$page .= "<th>".$lang['fl_fleet_typ']."</th>";
$page .= "<th>".$lang['fl_fleet_disp']."</th>";
$page .= "<th>-</th>";
$page .= "<th>-</th>";
$page .= "</tr>";

if (!$planetrow) {
	message($lang['fl_noplanetrow'], $lang['fl_error']);
}

$ShipData       = "";

foreach ($reslist['fleet'] as $n => $i) {
	if ($planetrow[$resource[$i]] > 0) {
		$page .= "<tr height=\"20\">\n";
		$page .= "<th><a title=\"". $lang['fl_fleetspeed'] . $CurrentShipSpeed ."\">" . $lang['tech'][$i] . "</a></th>\n";
		$page .= "<th>". pretty_number ($planetrow[$resource[$i]]);
		$ShipData .= "<input type=\"hidden\" name=\"maxship". $i ."\" value=\"". $planetrow[$resource[$i]] ."\" />\n";
		$ShipData .= "<input type=\"hidden\" name=\"consumption". $i ."\" value=\"". GetShipConsumption ( $i, $user ) ."\" />\n";
		$ShipData .= "<input type=\"hidden\" name=\"speed" .$i ."\" value=\"" . GetFleetMaxSpeed ("", $i, $user) . "\" />\n";
		$ShipData .= "<input type=\"hidden\" name=\"capacity". $i ."\" value=\"". round($pricelist[$i]['capacity'] * (1 + $user[$resource['160']] * 0.05)) ."\" />\n";
		$page .= "</th>\n";

		if ($i == 212) {
			$page .= "<th></th><th></th>\n";
		} else {
			$page .= "<th><a href=\"javascript:noShip('ship". $i ."'); calc_capacity();\">min</a> / <a href=\"javascript:maxShip('ship". $i ."'); calc_capacity();\">max</a></th>\n";
			$page .= "<th><a href=\"javascript:chShipCount('". $i ."', '-1'); calc_capacity();\" title=\"Уменьшить на 1 ед.\" style=\"color:#FFD0D0\">- </a><input name=\"ship". $i ."\" size=\"10\" value=\"0\" onfocus=\"javascript:if(this.value == '0') this.value='';\" onblur=\"javascript:if(this.value == '') this.value='0';\" alt=\"". $lang['tech'][$i] . $planetrow[$resource[$i]] ."\" onChange=\"calc_capacity()\" onKeyUp=\"calc_capacity()\" /><a href=\"javascript:chShipCount('". $i ."', '1'); calc_capacity();\" title=\"Увеличить на 1 ед.\" style=\"color:#D0FFD0\"> +</a></th>\n";
		}
		$page .= "</tr>\n";
	}
	$have_ships = true;
}

$btncontinue = "<tr height=\"20\"><th colspan=\"4\"><input type=\"submit\" value=\" ".$lang['fl_continue']." \" /></th>\n";
$page .= "<tr height=\"20\">\n";
if (!$have_ships) {
	$page .= "<th colspan=\"4\">". $lang['fl_noships'] ."</th>\n";
	$page .= "</tr>\n";
	$page .= $btncontinue;
} else {
	$page .= "<th colspan=\"2\"><a href=\"javascript:noShips(); calc_capacity();\" >". $lang['fl_unselectall'] ."</a></th>\n";
	$page .= "<th colspan=\"2\"><a href=\"javascript:maxShips(); calc_capacity();\" >". $lang['fl_selectall'] ."</a></th>\n";
	$page .= "</tr>\n";
	$page .= "<tr height=\"5\">\n";
	$page .= "	<th colspan=\"4\"></th>\n";
	$page .= " </tr>\n";
	$page .= "<tr height=\"20\">\n";
	$page .= "	<th colspan=\"2\">-</th>\n";
	$page .= "	<th colspan=\"1\">Вместимость</th>\n";
	$page .= "	<th colspan=\"1\"><div id=\"allcapacity\">-</div></th>\n";
	$page .= "</tr>\n";
	$page .= "<tr height=\"20\">\n";
	$page .= "	<th colspan=\"2\">-</th>\n";
	$page .= "	<th colspan=\"1\">Скорость</th>\n";
	$page .= "	<th colspan=\"1\"><div id=\"allspeed\">-</div></th>\n";
	$page .= "</tr>\n";
	$page .= "<tr height=\"5\">\n";
	$page .= "	<th colspan=\"4\"></th>\n";
	$page .= "</tr>\n";

	if ($MaxFlottes > $MaxFlyingFleets) {
		$page .= $btncontinue;
	}
}
$page .= "</tr>";
$page .= "</table>";
$page .= $ShipData;
$page .= "<input type=\"hidden\" name=\"galaxy\" value=\"". $galaxy ."\" />";
$page .= "<input type=\"hidden\" name=\"system\" value=\"". $system ."\" />";
$page .= "<input type=\"hidden\" name=\"planet\" value=\"". $planet ."\" />";
$page .= "<input type=\"hidden\" name=\"planet_type\" value=\"". $planettype ."\" />";
$page .= "<input type=\"hidden\" name=\"mission\" value=\"". $target_mission ."\" />";
$page .= "<input type=\"hidden\" name=\"maxepedition\" value=\"". $EnvoiMaxExpedition ."\" />";
$page .= "<input type=\"hidden\" name=\"curepedition\" value=\"". $ExpeditionEnCours ."\" />";
$page .= "<input type=\"hidden\" name=\"target_mission\" value=\"". $target_mission ."\" />";
$page .= "<input type=\"hidden\" name=\"crc\" value=\"". md5($user['id'].'-CHeAT_CoNTROL_Stage_01-'.date("dmYH", time())) ."\" />";
$page .= "</form>";
$page .= "</center>";

display($page, $lang['fl_title']);

?>