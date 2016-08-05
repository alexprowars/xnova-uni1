<?php

function formatCR ($result_array, $steal_array, $moon_int, $moon_string, $time_float) {
	global $lang;

	$html = "<center><table><tr><td>";
	$bbc = "";

	$html .= "В ".date("d-m-Y H:i:s")." произошёл бой между следующими флотами:<br /><br />";

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
			$html .= "Атакующий ".$data2['user']['username']." ([".$data2['fleet'][0].":".$data2['fleet'][1].":".$data2['fleet'][2]."])<br />";
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
			$html .= "Обороняющийся ".$data2['user']['username']." ([".$data2['fleet'][0].":".$data2['fleet'][1].":".$data2['fleet'][2]."])<br />";
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
		$html .= "<center>Атакующий флот делает ".$data1['attackA']['total']." выстрела(ов) с общей мощностью ".$data1['attack']['total']." по обороняющемуся. Щиты обороняющегося поглощают ".$data1['defShield']." выстрелов.<br />";
		$html .= "Обороняющийся флот делает ".$data1['defenseA']['total']." выстрела(ов) с общей мощностью ".$data1['defense']['total']." по атакующему. Щиты атакующего поглащают ".$data1['attackShield']." выстрелов.</center>";
	}
		$round_no++;
	}
	if ($result_array['won'] == 2){
		$result1  = "Обороняющийся выиграл битву!<br />";
	}elseif ($result_array['won'] == 1){
		$result1  = "Атакующий выиграл битву!<br />";
		$result1 .= "Он получает ".$steal_array['metal']." металла, ".$steal_array['crystal']." кристалла и ".$steal_array['deuterium']." дейтерия<br />";
	}else{
		$result1  = "Бой закончился ничьёй!<br />";
	}



	$html .= "<br /><br />";
	$html .= $result1;
	$html .= "<br />";

	$debirs_meta = ($result_array['debree']['att'][0] + $result_array['debree']['def'][0]);
	$debirs_crys = ($result_array['debree']['att'][1] + $result_array['debree']['def'][1]);
	$html .= "Атакующий потерял ".$result_array['lost']['att']." единиц.<br />";
	$html .= "Обороняющийся потерял ".$result_array['lost']['def']." единиц.<br />";
	$html .= "Теперь на этих пространственных координатах находятся ".$debirs_meta." металла и ".$debirs_crys." кристалла.<br /><br />";

	$html .= "Шанс появления луны составляет ".$moon_int."%<br />";
	$html .= $moon_string."<br /><br />";

	$html .= "Время генерации страницы ".$time_float." секунд</center>";
	return array('html' => $html, 'bbc' => $bbc);
}

function MissionCaseAttack ( $r, $FleetRow ) {
	global $pricelist, $lang, $resource, $CombatCaps, $TargetUser, $CurrentUser;

		if ($r['0'] == "" || $r['5'] == "") die('Нет данных для симуляции боя');

		for ($i = 0; $i < 10; $i++) {

			if ($i < 5 && $r[$i] != "") {
				$attackFleets[$i]['fleet'] = array($FleetRow['fleet_start_galaxy'], $FleetRow['fleet_start_system'], $FleetRow['fleet_start_planet']);
				$attackFleets[$i]['detail'] = array();
				$temp = explode(';', $r[$i]);
				foreach ($temp as $temp2) {
					$temp2 = explode(',', $temp2);
	
					if ($temp2[0] > 200) 
						$attackFleets[$i]['detail'][$temp2[0]] = $temp2[1];
					else
						$attackFleets[$i]['user'][$resource[$temp2[0]]] = $temp2[1];
				}
			}
			if ($i > 4 && $r[$i] != "") {
				$q = $i - 5;
				$defense[$q]['fleet'] = array($FleetRow['fleet_end_galaxy'], $FleetRow['fleet_end_system'], $FleetRow['fleet_end_planet']);
				$defense[$q]['def'] = array();
				$defense[$q]['user'] = array('military_tech' => $r[$i]['109'], 'shield_tech' => $r[$i]['110'], 'defence_tech' => $r[$i]['111']);
				$temp = explode(';', $r[$i]);
				foreach ($temp as $temp2) {
					$temp2 = explode(',', $temp2);
	
					if ($temp2[0] > 200) 
						$defense[$q]['def'][$temp2[0]] = $temp2[1];
					else
						$defense[$q]['user'][$resource[$temp2[0]]] = $temp2[1];
				}
			}
		}

			include_once('ataki.php');

			$mtime        = microtime();
			$mtime        = explode(" ", $mtime);
			$mtime        = $mtime[1] + $mtime[0];
			$starttime    = $mtime;

			$result        = calculateAttack($attackFleets, $defense, 0);

			$mtime        = microtime();
			$mtime        = explode(" ", $mtime);
			$mtime        = $mtime[1] + $mtime[0];
			$endtime      = $mtime;
			$totaltime    = ($endtime - $starttime);


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

			$GottenMoon = "";

			$formatted_cr = formatCR($result, $steal, $MoonChance, $GottenMoon, $totaltime);
			$raport = $formatted_cr['html'];

			$dpath = (!$user["dpath"]) ? DEFAULT_SKINPATH : $user["dpath"];



		$Page  = "<html>";
		$Page .= "<head>";
		$Page .= "<title>XNova SIM (0.3 beta) Симуляция боя</title>";
		$Page .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$dpath."/formate.css\">";
		$Page .= "<meta http-equiv=\"content-type\" content=\"text/html; charset=windows-1251\" />";
		$Page .= "</head>";
		$Page .= "<body>";
		$Page .= "<center>";
		$Page .= "<table width=\"99%\">";
		$Page .= "<tr>";
		$Page .= "<td>". stripslashes( $raport ) ."</td>";
		$Page .= "</tr>";
		$Page .= "</table>";
		$Page .= "</center>";
		$Page .= "<center>Made by AlexPro for <a href=\"http://xnova.su/\" target=\"_blank\">XNova Game UfaNet</a></center>";
		$Page .= "</body>";
		$Page .= "</html>";

		echo $Page;


}
?>