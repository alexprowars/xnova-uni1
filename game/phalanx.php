<?php

if(!defined("INSIDE")) die("attemp hacking");

	if ($user['urlaubs_modus_time'] > 0) {
		message("��� �������!");
	}

$galaktyka 	= $planetrow['galaxy'];
$system 		= $planetrow['system'];
$planeta 		= $planetrow['planet'];

$g  	= intval($_GET["galaxy"]);
$s  	= intval($_GET["system"]);
$i  	= intval($_GET["planet"]);

$deuterium 	= $planetrow['deuterium'];
$phalangelvl 	= $planetrow['phalanx'];
$consomation 	= 5000;
$deuteriumtotal 	= $deuterium - $consomation;

if ($g < 1 || $g > 9)
	$g = $galaktyka;
if ($s < 1 || $s > 499)
	$s = $system;
if ($i < 1 || $i > 15)
	$i = $planeta;

$systemdol 	= $system - pow($phalangelvl, 2);
$systemgora 	= $system + pow($phalangelvl, 2);

if ($planetrow['planet_type'] != '3'){
	message("�� ������ ������������ ������� ������ �� ����!", "������", "", 1);
}elseif ($planetrow['phalanx'] == '0'){
	message("��������� ������� ��������� �������", "������", "?set=overview", 1);
}elseif ($deuterium < $consomation){
	message ("<b>������������ �������� ��� �������������. ����������: 5000.</b>", "������", "", 2);
}elseif (($s <= $systemdol OR $s >= $systemgora) OR $g != $planetrow['galaxy']){
	message("�� �� ������ ����������� ������ �������. ������������� ������� ��������� �������.", "������", "", 1);
}else{
	doquery("UPDATE {{table}} SET deuterium = ".$deuteriumtotal." WHERE id = '{$user['current_planet']}'", 'planets');
}



$missiontype = array(1 => '���������', 3 => '���������', 4 => '��������', 5 => '����������', 6 => '�������', 7 => '��������������', 8 => '������������', 9 => '����������');

$fq = doquery("SELECT * FROM {{table}} WHERE (( fleet_start_galaxy = '".$g."' AND fleet_start_system = '".$s."' AND fleet_start_planet = '".$i."' AND fleet_start_type != 3 ) OR ( fleet_end_galaxy = '".$g."' AND fleet_end_system = '".$s."' AND fleet_end_planet = '".$i."' )) AND fleet_target_owner != 0 ORDER BY `fleet_start_time`", 'fleets');

	$parse = $lang;
	$ii = 0;

	while ($row = mysql_fetch_array($fq)) {

		if ($row['fleet_start_galaxy'] == $g && $row['fleet_start_system'] == $s && $row['fleet_start_planet'] == $i)
			$end = 0;
		else
			$end = 1;
		
		$timerek    = $row['fleet_start_time'];
		$timerekend = $row['fleet_end_time'];

		if ($row['fleet_mission'] == 1) { $kolormisjiz = green; $kolormisjido = lime;    }
		if ($row['fleet_mission'] == 2) { $kolormisjiz = green; $kolormisjido = lime;    }
		if ($row['fleet_mission'] == 3) { $kolormisjiz = green; $kolormisjido = lime;    }
		if ($row['fleet_mission'] == 4) { $kolormisjiz = green; $kolormisjido = lime;    }
		if ($row['fleet_mission'] == 5) { $kolormisjiz = green; $kolormisjido = lime;    }
		if ($row['fleet_mission'] == 6) { $kolormisjiz = B45D00; $kolormisjido = orange; }
		if ($row['fleet_mission'] == 7) { $kolormisjiz = green; $kolormisjido = lime;    }
		if ($row['fleet_mission'] == 8) { $kolormisjiz = green; $kolormisjido = lime;    }
		if ($row['fleet_mission'] == 9) { $kolormisjiz = green; $kolormisjido = lime;    }

		$g1 = $row['fleet_start_galaxy'];
		$s1 = $row['fleet_start_system'];
		$i1 = $row['fleet_start_planet'];
		$t1 = $row['fleet_start_type'];

		$g2 = $row['fleet_end_galaxy'];
		$s2 = $row['fleet_end_system'];
		$i2 = $row['fleet_end_planet'];
		$t2 = $row['fleet_end_type'];

		if ($t1 == '3'){
			$type = "���";
		}else{
			$type = "������";
		}

		if ($t2 == '3'){
			$type2 = "���";
		}else{
			$type2 = "������";
		}

		$nome = $row['fleet_owner_name'];
		$nome2 = $row['fleet_target_owner_name'];

		if ($timerek > time()  && $end == 1 && !($t1 == 3 && ($t2 == 2 || $t2 == 3))){
		
			$parse['manobras'] .= "<tr><th><div id=\"bxxfs$ii\" class=\"z\"></div><font color=\"lime\">" . date("H:i:s", $row['fleet_start_time']) . "</font> </th>";

			$Label = "fs";
			$Time = $row['fleet_start_time'] - time();
			$parse['manobras'] .= InsertJavaScriptChronoApplet ( $Label, $ii, $Time );

			$parse['manobras'] .= "<th><font color=\"$kolormisjido\">����� ";
			$parse['manobras'] .= "(".CreateFleetPopupedFleetLink($row, '����', '').")";

			$parse['manobras'] .= " � ".$type."� ".$nome." <font color=\"white\">[$g1:$s1:$i1]</font> ����� �� ".$type2."� ".$nome2." <font color=\"white\">[$g2:$s2:$i2]</font>. �������:";
			$parse['manobras'] .= " <font color=\"white\">{$missiontype[$row['fleet_mission']]}</font></th>";
		
			$ii++;
		}

		if ($row['fleet_mission'] <> 4 && $end == 0 && $t1 != 3) {

			$parse['manobras'] .= "<tr><th><div id=\"bxxfe$ii\" class=\"z\"></div><font color=\"green\">" . date("H:i:s", $row['fleet_end_time']) . "</font></th>";

			$Label = "fe";
			$Time = $row['fleet_end_time'] - time();
			$parse['manobras'] .= InsertJavaScriptChronoApplet ( $Label, $ii, $Time );

			$parse['manobras'] .= "<th><font color=\"$kolormisjido\">����� ";
			$parse['manobras'] .= "(".CreateFleetPopupedFleetLink($row, '����', '').")";

			$parse['manobras'] .= " � ".$type2."� ".$nome2." <font color=\"white\">[$g2:$s2:$i2]</font> ������������ �� ".$type."� ".$nome." <font color=\"white\">[$g1:$s1:$i1]</font>. �������:";
			$parse['manobras'] .= " <font color=\"white\">{$missiontype[$row['fleet_mission']]}</font></th></tr>";

			$ii++;
		}
	}

	if ($ii > 0) {
		$page = parsetemplate(gettemplate('phalanx_body'), $parse);
	} else {
		$page .= "<table width=519><tr><td class=c colspan=7>��� ������.</td></tr><th>�� ���� ������� ��� �������� ������.</th></table>";
	}

display($page, "��������� �������", false, false);

?>
