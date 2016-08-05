<?php

if(!defined("INSIDE")) die("attemp hacking");

if ($user['urlaubs_modus_time'] > 0) {
	message("Нет доступа!");
}

if ($_POST['crc'] != md5($user['id'].'-CHeAT_CoNTROL_Stage_03-'.date("dmYH", time()).'-'.$_POST["usedfleet"]))
	message('Ошибка контрольной суммы!');

includeLang('fleet');

//if ($_POST['mission'] == 1)
//	message ("<font color=\"red\"><b>Посылать флот в атаку временно запрещено. Дата включения 20:00 по серверу.</b></font>", 'Ошибка', "fleet." . $phpEx, 2);


$fleet_group_mr = 0;

if ($_POST['acs'] > 0){
	if ($_POST['mission'] == 2){
		$aks_count_mr = doquery("SELECT a.* FROM game_aks a, game_aks_user au WHERE au.aks_id = a.id AND au.user_id = ".$user['id']." AND au.aks_id = ".intval($_POST['acs'])." ;", 'aks');

		if (mysql_num_rows($aks_count_mr) > 0) {
			$aks_tr = mysql_fetch_assoc($aks_count_mr);
			if ($aks_tr['galaxy'] == $_POST["galaxy"] && $aks_tr['system'] == $_POST["system"] && $aks_tr['planet'] == $_POST["planet"] && $aks_tr['planet_type'] == $_POST["planettype"]) {
				$fleet_group_mr = $_POST['acs'];
			}
		}
	}
}
if (($_POST['acs'] == 0 || $fleet_group_mr == 0) && ($_POST['mission'] == 2)){
	$_POST['mission'] = 1;
}

$protection      = $game_config['noobprotection'];
$protectiontime  = $game_config['noobprotectiontime'];
$protectionmulti = $game_config['noobprotectionmulti'];
if ($protectiontime < 1) {
	$protectiontime = 9999999999999999;
}

$fleetarray  = unserialize(base64_decode(str_rot13($_POST["usedfleet"])));

if (!is_array($fleetarray)) {
	message ("<font color=\"red\"><b>Ошибка в передаче параметров!</b></font>", 'Ошибка', "?set=fleet", 2);
}

foreach ($fleetarray as $Ship => $Count) {
	if ($Count > $planetrow[$resource[$Ship]]) {
		message ("<font color=\"red\"><b>Недостаточно флота для отправки на планете!</b></font>", 'Ошибка', "?set=fleet", 2);
	}
}

$error              	= 0;
$galaxy             	= intval($_POST['galaxy']);
$system             	= intval($_POST['system']);
$planet             	= intval($_POST['planet']);
$planettype         	= intval($_POST['planettype']);
$fleetmission       	= intval($_POST['mission']);

if ($planettype != 1 && $planettype != 2 && $planettype != 3 && $planettype != 5) {
	message ("<font color=\"red\"><b>Неизвестный тип планеты!</b></font>", 'Ошибка', "?set=fleet", 2);
}
if ($planetrow['galaxy'] == $galaxy && $planetrow['system'] == $system && $planetrow['planet'] == $planet && $planetrow['planet_type'] == $planettype) {
	message ("<font color=\"red\"><b>Невозможно отправить флот на эту же планету!</b></font>", 'Ошибка', "?set=fleet", 2);
}

if ($fleetmission == 8) {
	$YourPlanet = false;
	$UsedPlanet = false;
	$select     = doquery("SELECT * FROM {{table}} WHERE galaxy = '". $galaxy ."' AND system = '". $system ."' AND planet = '". $planet ."' AND (planet_type = 1 OR planet_type = 5)", "planets");
} else {
	$YourPlanet = false;
	$UsedPlanet = false;
	$select     = doquery("SELECT * FROM {{table}} WHERE galaxy = '". $galaxy ."' AND system = '". $system ."' AND planet = '". $planet ."' AND planet_type = '". $planettype ."'", "planets");
}

if ($_POST['mission'] != 15) {
	if (mysql_num_rows($select) == 0 && $fleetmission != 7 && $fleetmission != 10) {
		message ("<font color=\"red\"><b>Данной планеты не существует!</b></font>", 'Ошибка', "?set=fleet", 2);
	} elseif ($fleetmission == 9 && mysql_num_rows($select) == 0) {
		message ("<font color=\"red\"><b>Данной планеты не существует!</b></font>", 'Ошибка', "?set=fleet", 2);
	} elseif (mysql_num_rows($select) == 0 && $fleetmission == 7 && $planettype != 1) {
		message ("<font color=\"red\"><b>Колонизировать можно только планету!</b></font>", 'Ошибка', "?set=fleet", 2);
	}
} else {

	if ($user[$resource[124]] >= 1) {
		$maxexp  = doquery("SELECT COUNT(*) AS `expeditions` FROM {{table}} WHERE `fleet_owner` = '".$user['id']."' AND `fleet_mission` = '15';", 'fleets', true);
		
		$ExpeditionEnCours  = $maxexp['expeditions'];
		$MaxExpedition = 1 + floor( $user[$resource[124]] / 3 );
	} else {
		$MaxExpedition = 0;
		$ExpeditionEnCours = 0;
	}

	if ($user[$resource[124]] == 0 ) {
		message ("<font color=\"red\"><b>Вами не изучена \"Экспедиционная технология\"!</b></font>", 'Ошибка', "?set=fleet", 2);
	} elseif ($ExpeditionEnCours >= $MaxExpedition ) {
		message ("<font color=\"red\"><b>Вы уже отправили максимальное количество экспедиций!</b></font>", 'Ошибка', "?set=fleet", 2);
	}
}

$TargetPlanet = mysql_fetch_assoc($select);

if ($TargetPlanet['id_owner'] == $user['id']) {
	$YourPlanet = true;
	$UsedPlanet = true;
} elseif (!empty($TargetPlanet['id_owner'])) {
	$YourPlanet = false;
	$UsedPlanet = true;
} else {
	$YourPlanet = false;
	$UsedPlanet = false;
}

if ($fleetmission == 15) {
	$missiontype = array(15 => $lang['type_mission'][15]);
} else {
	if ($_POST['planettype'] == "2") {
		if ($_POST['ship209'] >= 1) {
			$missiontype = array(8 => $lang['type_mission'][8]);
		} else {
			$missiontype = array();
		}
	} elseif ($_POST['planettype'] == "1" || $_POST['planettype'] == "3" || $_POST['planettype'] == "5") {
		if ($_POST['ship208'] >= 1 && !$UsedPlanet) {
			$missiontype = array(7 => $lang['type_mission'][7]);
		}elseif ($_POST['ship216'] >= 1 && !$UsedPlanet) {
			$missiontype = array(10 => $lang['type_mission'][10]);
		} elseif ($_POST['ship210'] >= 1 && !$YourPlanet) {
			$missiontype = array(6 => $lang['type_mission'][6]);
		}

		if ($_POST['ship202'] >= 1 || $_POST['ship203'] >= 1 || $_POST['ship204'] >= 1 || $_POST['ship205'] >= 1 || $_POST['ship206'] >= 1 || $_POST['ship207'] >= 1 || $_POST['ship210'] >= 1 || $_POST['ship211'] >= 1 || $_POST['ship213'] >= 1 || $_POST['ship214'] >= 1 || $_POST['ship215'] >= 1 || $_POST['ship216'] >= 1 || $_POST['ship217'] >= 1) {
			if (!$YourPlanet) {
				$missiontype[1] = $lang['type_mission'][1];
			}
			$missiontype[3] = $lang['type_mission'][3];
		}

		if (!$YourPlanet && $UsedPlanet) {
			$missiontype[5] = $lang['type_mission'][5];
		}
	} elseif ($_POST['ship209'] >= 1 || $_POST['ship208']) {
		$missiontype[3] = $lang['type_mission'][3];
	}
	if ($YourPlanet || $TargetPlanet['id_owner'] == 1)
		$missiontype[4] = $lang['type_mission'][4];

	if (($_POST['planettype'] == 3 || $_POST['planettype'] == 1 || $_POST['planettype'] == 5) && ($fleet_group_mr > 0) && ($UsedPlanet)) {
		$missiontype[2] = $lang['type_mission'][2];
	}

	if ( $_POST['planettype'] == 3 && $_POST['ship214'] > 0 && !$YourPlanet && $UsedPlanet) {
		$missiontype[9] = $lang['type_mission'][9];
	}
}

if (empty($missiontype[$fleetmission])) {
	message ("<font color=\"red\"><b>Миссия неизвестна!</b></font>", 'Ошибка', "?set=fleet", 2);
}

if ($_POST['mission'] == 8) {
	$galaX = doquery("SELECT * FROM {{table}} WHERE id_planet = '".$TargetPlanet['id']."'", "galaxy", true);

	if ($galaX['metal'] == 0 && $galaX['crystal'] == 0)
		message ("<font color=\"red\"><b>Нет обломков для сбора.</b></font>", 'Ошибка', "?set=fleet", 2);
}

if (!isset($TargetPlanet['id_owner'])) {
	$HeDBRec = $user;
} elseif (isset($TargetPlanet['id_owner'])) {
	$HeDBRec = doquery("SELECT * FROM {{table}} WHERE `id` = '". $TargetPlanet['id_owner'] ."';", 'users', true);
}

if (($HeDBRec['id'] == 5563 && $user['id'] != 5563) || ($HeDBRec['id'] == 1 && $user['id'] != 1 && $fleetmission != 4 && $fleetmission != 3))
	message ("<font color=\"red\"><b>На этого игрока запрещено нападать</b></font>", 'Ошибка', "?set=fleet", 2);

if ($user['ally_id'] != 0 && $HeDBRec['ally_id'] != 0 && $_POST['mission'] == 1) {
	$ad = doquery("SELECT * FROM {{table}} WHERE (o_al = ".$HeDBRec['ally_id']." AND t_al = ".$user['ally_id'].") OR (o_al = ".$user['ally_id']." AND t_al = ".$HeDBRec['ally_id'].") AND status = 1", "alliance_diplo", true);

	if ($ad['id'] != "" && $ad['type'] < 3)
		message ("<font color=\"red\"><b>Заключён мир или перемирие с альянсом атакуемого игрока.</b></font>", "Ошибка дипломатии", "?set=fleet", 2);

}

$UserPoints    = doquery("SELECT total_points FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '". $user['id'] ."';", 'statpoints', true);
$User2Points   = doquery("SELECT total_points FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '". $HeDBRec['id'] ."';", 'statpoints', true);

$MyGameLevel  = $UserPoints['total_points'];
$HeGameLevel  = $User2Points['total_points'];
if (!$HeGameLevel) $HeGameLevel = 0;
$VacationMode = $HeDBRec['urlaubs_modus_time'];
if ($HeDBRec['onlinetime'] < (time()-60 * 60 * 24 * 7)){
	$NoobNoActive = 1;
}else{
	$NoobNoActive = 0;
}

if ($user['authlevel'] != 3) {
	if ($MyGameLevel > ($HeGameLevel * $protectionmulti) AND isset($TargetPlanet['id_owner']) AND ($_POST['mission'] == 1 OR $_POST['mission'] == 2 OR $_POST['mission'] == 5 OR $_POST['mission'] == 6 OR $_POST['mission'] == 9)  AND $protection == 1  AND $NoobNoActive == 0 AND $HeGameLevel < ($protectiontime * 1000)) {
		message("<font color=\"lime\"><b>Игрок находится под защитой новичков!</b></font>", 'Защита новичков', "?set=fleet", 2);
	}
	if (($MyGameLevel * $protectionmulti) < $HeGameLevel AND isset($TargetPlanet['id_owner']) AND ($_POST['mission'] == 1 OR $_POST['mission'] == 2 OR $_POST['mission'] == 5 OR $_POST['mission'] == 6 OR $_POST['mission'] == 9) AND $protection == 1 AND $NoobNoActive == 0 AND $MyGameLevel < ($protectiontime * 1000)) {
		message("<font color=\"lime\"><b>Игрок находится под защитой новичков!</b></font>", 'Защита новичков', "?set=fleet", 2);
	}
}

if ($VacationMode AND $_POST['mission'] != 8) {
	message("<font color=\"lime\"><b>Игрок в режиме отпуска!</b></font>", 'Режим отпуска', "?set=fleet", 2);
}

$FlyingFleets = mysql_fetch_assoc(doquery("SELECT COUNT(fleet_id) as Number FROM {{table}} WHERE `fleet_owner`='{$user['id']}'", 'fleets'));
$ActualFleets = $FlyingFleets["Number"];
$fleetmax = $user[$resource[108]] + 1;
if ($user['rpg_admiral'] > time()) $fleetmax += 2;
if ($fleetmax <= $ActualFleets) {
	message("Все слоты флота заняты", "Ошибка", "?set=fleet", 2);
}

if ($_POST['resource1'] + $_POST['resource2'] + $_POST['resource3'] < 1 AND $_POST['mission'] == 3) {
	message("<font color=\"lime\"><b>Нет сырья для транспорта!</b></font>", $lang['type_mission'][3], "?set=fleet", 2);
}
if ($_POST['mission'] != 15) {
	if (!isset($TargetPlanet['id_owner']) AND $_POST['mission'] < 7) {
		message ("<font color=\"red\"><b>Планеты не существует!</b></font>", 'Ошибка', "?set=fleet", 2);
	}
	if (isset($TargetPlanet['id_owner']) AND ($_POST['mission'] == 7 || $_POST['mission'] == 10)) {
		message ("<font color=\"red\"><b>Место занято</b></font>", 'Ошибка', "?set=fleet", 2);
	}
	if (($HeDBRec['id'] != $user['id'] && $HeDBRec['id'] != 1) AND $_POST['mission'] == 4) {
		message ("<font color=\"red\"><b>Выполнение данной миссии невозможно!</b></font>", 'Ошибка', "?set=fleet", 2);
	}
	if ($TargetPlanet['ally_deposit'] == 0 && $HeDBRec['id'] != $user['id'] && $_POST['mission'] == 5) {
		message ("<font color=\"red\"><b>На планете нет склада альянса!</b></font>", 'Ошибка', "?set=fleet", 2);
	}
	if ($_POST['mission'] == 5) {
		$friend = doquery("SELECT id FROM {{table}} WHERE (sender = ".$user['id']." AND owner = ".$HeDBRec['id'].") OR (owner = ".$user['id']." AND sender = ".$HeDBRec['id'].") AND active = 1 LIMIT 1", "buddy", true);
		if ($HeDBRec['ally_id'] != $user['ally_id'] && !isset($friend['id'])) {
			message ("<font color=\"red\"><b>Нельзя охранять вражеские планеты!</b></font>", 'Ошибка', "?set=fleet", 2);
		}
	}
	if ($TargetPlanet['id_owner'] == $user['id'] && $_POST['mission'] == 1) {
		message ("<font color=\"red\"><b>Невозможно атаковать самого себя!</b></font>", 'Ошибка', "?set=fleet", 2);
	}
	if ($TargetPlanet['id_owner'] == $user['id'] && $_POST['mission'] == 6) {
		message ("<font color=\"red\"><b>Невозможно шпионить самого себя!</b></font>", 'Ошибка', "?set=fleet", 2);
	}
	if (($TargetPlanet['id_owner'] != $user['id'] && $HeDBRec['id'] != 1) && $_POST['mission'] == 4) {
		message ("<font color=\"red\"><b>Выполнение данной миссии невозможно!</b></font>", 'Ошибка', "?set=fleet", 2);
	}
}

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
	10 => $lang['type_mission'][10],
	15 => $lang['type_mission'][15],
);

$speed_possible = array(10, 9, 8, 7, 6, 5, 4, 3, 2, 1);

$AllFleetSpeed  = GetFleetMaxSpeed ($fleetarray, 0, $user);
$GenFleetSpeed  = $_POST['speed'];
$SpeedFactor    = GetGameSpeedFactor();
$MaxFleetSpeed  = min($AllFleetSpeed);

if (!in_array($GenFleetSpeed, $speed_possible)) {
	message ("<font color=\"red\"><b>Читеришь со скоростью?</b></font>", 'Ошибка', "?set=fleet", 2);
}
if (!$planettype) {
	message ("<font color=\"red\"><b>Ошибочный тип планеты!</b></font>", 'Ошибка', "?set=fleet", 2);
}

$error     	= 0;
$errorlist 	= "";
if (!$galaxy || $galaxy > 9 || $galaxy < 1) {
	$error++;
	$errorlist .= $lang['fl_limit_galaxy'];
}
if (!$system || $system > 499 || $system < 1) {
	$error++;
	$errorlist .= $lang['fl_limit_system'];
}
if (!$planet || $planet > 16 || $planet < 1) {
	$error++;
	$errorlist .= $lang['fl_limit_planet'];
}

if ($error > 0) {
	message ("<font color=\"red\"><ul>" . $errorlist . "</ul></font>", 'Ошибка', "?set=fleet", 2);
}

if (!isset($fleetarray)) {
	message ("<font color=\"red\"><b>". $lang['fl_no_fleetarray'] ."</b></font>", 'Ошибка', "?set=fleet", 2);
}

$distance      = GetTargetDistance ( $planetrow['galaxy'], $galaxy, $planetrow['system'], $system, $planetrow['planet'], $planet );
$duration      = GetMissionDuration ( $GenFleetSpeed, $MaxFleetSpeed, $distance, $SpeedFactor );
$consumption   = GetFleetConsumption ( $fleetarray, $SpeedFactor, $duration, $distance, $MaxFleetSpeed, $user );

if ($fleet_group_mr > 0) {
	// Вычисляем время самого медленного флота в совместной атаке
	$flet = doquery("SELECT fleet_id, fleet_start_time, fleet_end_time FROM {{table}} WHERE fleet_group = '".$fleet_group_mr."'", 'fleets');
	$ttt = $duration + time();
	$arrr = array();
	$i = 0;
	while($flt = mysql_fetch_assoc($flet)){
		$i++;
		if ($flt['fleet_start_time'] > $ttt) $ttt = $flt['fleet_start_time'];
		$arrr[$i]['id'] = $flt['fleet_id'];
		$arrr[$i]['start'] = $flt['fleet_start_time'];
		$arrr[$i]['end'] = $flt['fleet_end_time'];
	}
}

if ($fleet_group_mr > 0)
	$fleet['start_time'] = $ttt;
else
	$fleet['start_time'] = $duration + time();

if ($_POST['mission'] == 15) {
	$StayDuration    = intval($_POST['expeditiontime']) * 3600;
	$StayTime        = $fleet['start_time'] + intval($_POST['expeditiontime']) * 3600;
} else {
	$StayDuration    = 0;
	$StayTime        = 0;
}

$FleetStorage        = 0;
$FleetShipCount      = 0;
$fleet_array         = "";
$FleetSubQRY         = "";

foreach ($fleetarray as $Ship => $Count) {
	$Count = intval($Count);
	$FleetStorage    	+= round($pricelist[$Ship]["capacity"] * (1 + $user[$resource['160']] * 0.05)) * $Count;
	$FleetShipCount  	+= $Count;
	$fleet_array     	.= $Ship .",". $Count .";";
	$FleetSubQRY     	.= "`".$resource[$Ship] . "` = `" . $resource[$Ship] . "` - " . $Count . " , ";
}

$FleetStorage        	-= $consumption;
$StorageNeeded	= 0;

if ($_POST['resource1'] < 1) {
	$TransMetal	= 0;
} else {
	$TransMetal	= intval($_POST['resource1']);
	$StorageNeeded  	+= $TransMetal;
}
if ($_POST['resource2'] < 1) {
	$TransCrystal    	= 0;
} else {
	$TransCrystal    	= intval($_POST['resource2']);
	$StorageNeeded  	+= $TransCrystal;
}
if ($_POST['resource3'] < 1) {
	$TransDeuterium  	= 0;
} else {
	$TransDeuterium  	= intval($_POST['resource3']);
	$StorageNeeded  	+= $TransDeuterium;
}

if ($_POST['mission'] == 5) {

	$StayArrayTime = array(0, 1, 2, 4, 8, 16, 32);

	if (!isset($_POST['holdingtime']) || !in_array($_POST['holdingtime'], $StayArrayTime))
		$_POST['holdingtime'] = 0;

	$FleetStayConsumption = GetFleetStay($fleetarray);
	$FleetStayAll = $FleetStayConsumption * intval($_POST['holdingtime']);
	if ($FleetStayAll >= ($planetrow['deuterium'] - $TransDeuterium))
		$TotalFleetCons = $planetrow['deuterium'] - $TransDeuterium;
	else
		$TotalFleetCons = $FleetStayAll;

	if ($FleetStorage < $TotalFleetCons)  $TotalFleetCons = $FleetStorage;

	$FleetStayTime = round(($TotalFleetCons / $FleetStayConsumption) * 3600);

	$StayDuration    = $FleetStayTime;
	$StayTime        = $fleet['start_time'] + $FleetStayTime;
}
if ($fleet_group_mr > 0)
	$fleet['end_time']   = $StayDuration + $duration + $ttt;
else
	$fleet['end_time']   = $StayDuration + (2 * $duration) + time();

$StockMetal      	= $planetrow['metal'];
$StockCrystal    	= $planetrow['crystal'];
$StockDeuterium  	= $planetrow['deuterium'];
$StockDeuterium 	-= $consumption;

$StockOk         = false;
if ($StockMetal >= $TransMetal) {
	if ($StockCrystal >= $TransCrystal) {
		if ($StockDeuterium >= $TransDeuterium) {
			$StockOk         = true;
		}
	}
}
if ( !$StockOk && $TargetPlanet['id_owner'] != 1) {
	message ("<font color=\"red\"><b>". $lang['fl_noressources'] . pretty_number($consumption) ."</b></font>", 'Ошибка', "?set=fleet", 2);
}
if ( $StorageNeeded > $FleetStorage) {
	message ("<font color=\"red\"><b>". $lang['fl_nostoragespa'] . pretty_number($StorageNeeded - $FleetStorage) ."</b></font>", 'Ошибка', "?set=fleet", 2);
}


// Баш контроль
if ($_POST['mission'] == 1){

	$night_time = mktime (0, 0, 0, date('m', time()), date('d', time()), date('Y', time()) );

	$log = doquery("SELECT kolvo FROM {{table}} WHERE `s_id` = '{$user['id']}' AND `mission` = 1 AND e_galaxy = ".$TargetPlanet['galaxy']." AND e_system = ".$TargetPlanet['system']." AND e_planet = ".$TargetPlanet['planet']." AND time > ".$night_time."", "logs", true);

	if ( $log['kolvo'] != "" && $log['kolvo'] > 2 && $ad['type'] != 3 )
		message ("<font color=\"red\"><b>Баш-контроль. Лимит ваших нападений на планету исчерпан.</b></font>", 'Ошибка', "?set=fleet", 2);

	if ( $log['kolvo'] != "" )
		doquery("UPDATE {{table}} SET kolvo = kolvo + 1 WHERE `s_id` = '{$user['id']}' AND `mission` = 1 AND e_galaxy = ".$TargetPlanet['galaxy']." AND e_system = ".$TargetPlanet['system']." AND e_planet = ".$TargetPlanet['planet']." AND time > ".$night_time."", "logs");
	else
		doquery("INSERT INTO {{table}} VALUES (1, ".time().", 1, ".$user['id'].", ".$planetrow['galaxy'].", ".$planetrow['system'].", ".$planetrow['planet'].", ".$TargetPlanet['id_owner'].", ".$TargetPlanet['galaxy'].", ".$TargetPlanet['system'].", ".$TargetPlanet['planet'].")" , "logs");

}
//

// Увод флота
//$fleets_num = doquery("SELECT fleet_id FROM {{table}} WHERE fleet_mission = '1' AND fleet_end_galaxy = ".$planetrow['galaxy']." AND fleet_end_system = ".$planetrow['system']." AND fleet_end_planet = ".$planetrow['planet']." AND fleet_end_type = ".$planetrow['planet_type']." AND fleet_start_time < ".(time() + 6)."", "fleets");

//if (mysql_num_rows($fleets_num) > 0)
//		message ("<font color=\"red\"><b>Ваш флот не может взлететь из-за находящегося по близости от орбиты планеты атакующего флота.</b></font>", 'Ошибка', "fleet." . $phpEx, 2);
//

if ($fleet_group_mr > 0 && $i > 0 && $ttt >0) {
	foreach ($arrr AS $id => $row){
		$end = $ttt + $row['end'] - $row['start'];
		doquery("UPDATE {{table}} SET fleet_start_time = ".$ttt.", fleet_end_time = ".$end." WHERE fleet_id = '".$row['id']."'", 'fleets');
	}
}

if ($_POST['mission'] == 3) {

	if ($MyGameLevel < $HeGameLevel && $user['id'] != $TargetPlanet['id_owner']) {

		doquery("INSERT INTO {{table}} VALUES ('".time()."', '".$user['id']."', 's:[".$planetrow['galaxy'].":".$planetrow['system'].":".$planetrow['planet']."(".$planetrow['planet_type'].")];e:[".$galaxy.":".$system.":".$planet."(".$planettype.")];f:[".$fleet_array."];m:".$TransMetal.";c:".$TransCrystal.";d:".$TransDeuterium.";', '".$TargetPlanet['id_owner']."')", "mults");

		$str_error = "Сделана попытка прокачки. Данные вашего флота отправлены операторам на рассмотрение.";
	}

}

if ($TargetPlanet['id_owner'] == 1) {
	$fleet['start_time'] 	= time() + 30;
	$fleet['end_time'] 		= time() + 60;
	$consumption			= 0;
}

$QryInsertFleet  = "INSERT INTO {{table}} SET ";
$QryInsertFleet .= "`fleet_owner` = '". $user['id'] ."', ";
$QryInsertFleet .= "`fleet_owner_name` = '". $planetrow['name'] ."', ";
$QryInsertFleet .= "`fleet_mission` = '". $_POST['mission'] ."', ";
$QryInsertFleet .= "`fleet_amount` = '". $FleetShipCount ."', ";
$QryInsertFleet .= "`fleet_array` = '". $fleet_array ."', ";
$QryInsertFleet .= "`fleet_start_time` = '". $fleet['start_time'] ."', ";
$QryInsertFleet .= "`fleet_start_galaxy` = '". $planetrow['galaxy'] ."', ";
$QryInsertFleet .= "`fleet_start_system` = '". $planetrow['system'] ."', ";
$QryInsertFleet .= "`fleet_start_planet` = '". $planetrow['planet'] ."', ";
$QryInsertFleet .= "`fleet_start_type` = '". $planetrow['planet_type'] ."', ";
$QryInsertFleet .= "`fleet_end_time` = '". $fleet['end_time'] ."', ";
$QryInsertFleet .= "`fleet_end_stay` = '". $StayTime ."', ";
$QryInsertFleet .= "`fleet_end_galaxy` = '". $galaxy ."', ";
$QryInsertFleet .= "`fleet_end_system` = '". $system ."', ";
$QryInsertFleet .= "`fleet_end_planet` = '". $planet ."', ";
$QryInsertFleet .= "`fleet_end_type` = '". $planettype ."', ";
$QryInsertFleet .= "`fleet_resource_metal` = '". $TransMetal ."', ";
$QryInsertFleet .= "`fleet_resource_crystal` = '". $TransCrystal ."', ";
$QryInsertFleet .= "`fleet_resource_deuterium` = '". $TransDeuterium ."', ";
$QryInsertFleet .= "`fleet_target_owner` = '". $TargetPlanet['id_owner'] ."', ";
$QryInsertFleet .= "`fleet_target_owner_name` = '". $TargetPlanet['name'] ."', ";
$QryInsertFleet .= "`fleet_group` = '". $fleet_group_mr ."', ";
$QryInsertFleet .= "`start_time` = '". time() ."', fleet_time = '". $fleet['start_time'] ."';";
doquery( $QryInsertFleet, 'fleets');


$planetrow["metal"]     	-= $TransMetal;
$planetrow["crystal"]   	-= $TransCrystal;
$planetrow["deuterium"] 	-= $TransDeuterium;
$planetrow["deuterium"] 	-= $consumption + $TotalFleetCons;

$QryUpdatePlanet  = "UPDATE {{table}} SET ";
$QryUpdatePlanet .= $FleetSubQRY;
$QryUpdatePlanet .= "`metal` = '". $planetrow["metal"] ."', ";
$QryUpdatePlanet .= "`crystal` = '". $planetrow["crystal"] ."', ";
$QryUpdatePlanet .= "`deuterium` = '". $planetrow["deuterium"] ."' ";
$QryUpdatePlanet .= "WHERE ";
$QryUpdatePlanet .= "`id` = '". $planetrow['id'] ."'";
doquery ($QryUpdatePlanet, "planets");

if ($str_error != "")
	$lang['fl_fleet_send'] = $str_error;

$page .= "<br><div><center>";
$page .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"1\" width=\"519\">";
$page .= "<tr height=\"20\">";
$page .= "<td class=\"c\" colspan=\"2\"><span class=\"success\">". $lang['fl_fleet_send'] ."</span></td>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>". $lang['fl_mission'] ."</th>";
$page .= "<th>". $missiontype[$_POST['mission']] ."</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>". $lang['fl_dist'] ."</th>";
$page .= "<th>". pretty_number($distance) ."</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>". $lang['fl_speed'] ."</th>";
$page .= "<th>". pretty_number($MaxFleetSpeed) ."</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>". $lang['fl_deute_need'] ."</th>";
$page .= "<th>". pretty_number($consumption) ."</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>". $lang['fl_from'] ."</th>";
$page .= "<th>". $planetrow['galaxy'] .":". $planetrow['system']. ":". $planetrow['planet'] ."</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>". $lang['fl_dest'] ."</th>";
$page .= "<th>". $galaxy .":". $system .":". $planet ."</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>". $lang['fl_time_go'] ."</th>";
$page .= "<th>". date("M D d H:i:s", $fleet['start_time']) ."</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<th>". $lang['fl_time_back'] ."</th>";
$page .= "<th>". date("M D d H:i:s", $fleet['end_time']) ."</th>";
$page .= "</tr><tr height=\"20\">";
$page .= "<td class=\"c\" colspan=\"2\">". $lang['fl_title'] ."</td>";


foreach ($fleetarray as $Ship => $Count) {
	$page .= "</tr><tr height=\"20\">";
	$page .= "<th>". $lang['tech'][$Ship] ."</th>";
	$page .= "<th>". pretty_number($Count) ."</th>";
}
$page .= "</tr></table></div></center>";

message ($page, ''.$lang['fl_title'].'', '?set=fleet', '3')


?>
