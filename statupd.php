<?php

define('INSIDE'  , true);
define('INSTALL' , false);

include("includes/mysql.php");
include("includes/vars.php");
include("game/admin/statfunctions.php");

$StatDate   = time();

$Message = "";

// Включение режима удаления у неактивных игроков
$Del_TimeS 	= time()+86400*7; // 7 дней на удаление аккаунта
$Time_Online 	= time()-60*60*24*21; // удалять если не активен 21 день
// Удалять если не забанен и не в режиме отпуска
$Spr_Online = doquery("SELECT * FROM {{table}} WHERE `onlinetime` < '{$Time_Online}' AND `onlinetime` > '0' AND (`urlaubs_modus_time` = '0' OR (urlaubs_modus_time < ".time()." - 15184000 AND urlaubs_modus_time > 1)) AND `banaday` = '0' AND `deltime` = '0'", "users");
while ($OnlineS = mysql_fetch_assoc($Spr_Online)){
	doquery("UPDATE {{table}} SET `deltime` = '".$Del_TimeS."' WHERE `id` = '".$OnlineS['id']."'", "users");
	$Message .= "Включение удаления у ".$OnlineS['username'].": ОК<br>";
}

// Выбираем кандидатов на удаление
$Del_Time = time();
$Spr_Del = doquery("SELECT * FROM {{table}} WHERE `deltime` < '{$Del_Time}' AND `deltime`> '0'","users");

// Полное очищение игры от удалённого аккаунта
while ($TheUser = mysql_fetch_assoc($Spr_Del)){
	$UserID = $TheUser['id'];

	$Message .= "Удаление аккаунта ".$TheUser['username'].": ОК<br>";

	if ( $TheUser['ally_id'] != 0 ) {
	$TheAlly = doquery ( "SELECT * FROM {{table}} WHERE `id` = '" . $TheUser['ally_id'] . "';", 'alliance', true );
		$TheAlly['ally_members'] -= 1;
		if ( $TheAlly['ally_members'] > 0 ) {
			doquery ( "UPDATE {{table}} SET `ally_members` = '" . $TheAlly['ally_members'] . "' WHERE `id` = '" . $TheAlly['id'] . "';", 'alliance' );
		} else {
			doquery ( "DELETE FROM {{table}} WHERE `id` = '" . $TheAlly['id'] . "';", 'alliance' );
			doquery ( "DELETE FROM {{table}} WHERE `stat_type` = '2' AND `id_owner` = '" . $TheAlly['id'] . "';", 'statpoints' );
		}
	}

	doquery ( "DELETE FROM {{table}} WHERE `stat_type` = '1' AND `id_owner` = '" . $UserID . "';", 'statpoints' );

	$ThePlanets = doquery ( "SELECT * FROM {{table}} WHERE `id_owner` = '" . $UserID . "';", 'planets' );

	while ( $OnePlanet = mysql_fetch_assoc ( $ThePlanets ) ) {
		doquery ( "DELETE FROM {{table}} WHERE `galaxy` = '" . $OnePlanet['galaxy'] . "' AND `system` = '" . $OnePlanet['system'] . "' AND `planet` = '" . $OnePlanet['planet'] . "';", 'galaxy' );
		doquery ( "DELETE FROM {{table}} WHERE `id` = '" . $OnePlanet['id'] . "';", 'planets' );
	}

	doquery ( "DELETE FROM {{table}} WHERE `message_sender` = '" . $UserID . "';", 'messages' );
	doquery ( "DELETE FROM {{table}} WHERE `message_owner` = '" . $UserID . "';", 'messages' );
	doquery ( "DELETE FROM {{table}} WHERE `owner` = '" . $UserID . "';", 'notes' );
	doquery ( "DELETE FROM {{table}} WHERE `fleet_owner` = '" . $UserID . "';", 'fleets' );
	doquery ( "DELETE FROM {{table}} WHERE `id_owner1` = '" . $UserID . "';", 'rw' );
	doquery ( "DELETE FROM {{table}} WHERE `id_owner2` = '" . $UserID . "';", 'rw' );
	doquery ( "DELETE FROM {{table}} WHERE `sender` = '" . $UserID . "';", 'buddy' );
	doquery ( "DELETE FROM {{table}} WHERE `owner` = '" . $UserID . "';", 'buddy' );
	doquery ( "DELETE FROM {{table}} WHERE `r_id` = '" . $UserID . "' OR `u_id` = '" . $UserID . "';", 'refs' );
	doquery ( "DELETE FROM {{table}} WHERE `id` = '" . $UserID . "';", 'users' );
	doquery ( "DELETE FROM {{table}} WHERE `id` = '" . $UserID . "';", 'users_inf' );
	doquery ( "DELETE FROM {{table}} WHERE `who` = '" . $TheUser['username'] . "';", 'banned' );
	doquery("UPDATE {{table}} SET `config_value`=`config_value`-1 WHERE `config_name` = 'users_amount';", 'config');

}

// Чистим старьё
doquery ( "DELETE FROM {{table}} WHERE `stat_code` = '2';" , 'statpoints');
doquery ( "UPDATE {{table}} SET `stat_code` = `stat_code` + '1';" , 'statpoints');

// Делаем выборку игрока и его очков в статистике
$GameUsers  = doquery("SELECT u.*, s.total_rank, s.tech_rank, s.fleet_rank FROM ({{table}}users u, {{table}}users_inf ui) LEFT JOIN {{table}}statpoints s ON s.id_owner = u.id AND s.stat_type = 1 WHERE ui.id = u.id AND u.authlevel < 3 AND (u.onlinetime > ui.register_time + 1800)", '');
// Удаляем статистику игроков
doquery ("DELETE FROM {{table}} WHERE `stat_type` = '1';",'statpoints');
// Делаем выборку флотов и расчитываем очки
$FleetPoints = array();
$UsrFleets      = doquery("SELECT * FROM {{table}}", 'fleets');
   while ($CurFleet = mysql_fetch_assoc($UsrFleets)) {
	$Points           = GetFleetPointsOnTour ( $CurFleet['fleet_array'] );

	if (!$FleetPoints[$CurFleet['fleet_owner']]['points']) {
		$FleetPoints[$CurFleet['fleet_owner']]['points'] = 0;
		$FleetPoints[$CurFleet['fleet_owner']]['count']  = 0;
	}

	$FleetPoints[$CurFleet['fleet_owner']]['points'] += ($Points['FleetPoint'] / 1000);
	$FleetPoints[$CurFleet['fleet_owner']]['count']  += $Points['FleetCount'];
   }

// Просчитываем очки каждого игрока
while ($CurUser = mysql_fetch_assoc($GameUsers)) {

    if ($CurUser['banaday'] != 0 || ($CurUser['urlaubs_modus_time'] != 0 && $CurUser['urlaubs_modus_time'] < (time() - 1036800)))
        $hide = 1;
    else
        $hide = 0;

	// Запоминаем старое место в стате
	if ($CurUser['total_rank'] != "") {
		$OldTotalRank = $CurUser['total_rank'];
		$OldTechRank  = $CurUser['tech_rank'];
		$OldFleetRank = $CurUser['fleet_rank'];
		$OldBuildRank = 0;
		$OldDefsRank  = 0;
	} else {
		$OldTotalRank = 0;
		$OldTechRank  = 0;
		$OldBuildRank = 0;
		$OldDefsRank  = 0;
		$OldFleetRank = 0;
	}

	// Вычисляем очки исследований
	$Points         = GetTechnoPoints ( $CurUser );
	$TTechCount     = $Points['TechCount'];
	$TTechPoints    = ($Points['TechPoint'] / 1000);

	$TBuildCount    = 0;
	$TBuildPoints   = 0;
	$TDefsCount     = 0;
	$TDefsPoints    = 0;
	$TFleetCount    = 0;
	$TFleetPoints   = 0;
	$GCount         = $TTechCount;
	$GPoints        = $TTechPoints;
	$UsrPlanets     = doquery("SELECT * FROM {{table}} WHERE `id_owner` = '". $CurUser['id'] ."';", 'planets');

	while ($CurPlanet = mysql_fetch_assoc($UsrPlanets) ) {
		$Points           = GetBuildPoints ( $CurPlanet );
		$TBuildCount     += $Points['BuildCount'];
		$GCount          += $Points['BuildCount'];
		$PlanetPoints     = ($Points['BuildPoint'] / 1000);
		$TBuildPoints    += ($Points['BuildPoint'] / 1000);

		$Points           = GetDefensePoints ( $CurPlanet );
		$TDefsCount      += $Points['DefenseCount'];;
		$GCount          += $Points['DefenseCount'];
		$PlanetPoints    += ($Points['DefensePoint'] / 1000);
		$TDefsPoints     += ($Points['DefensePoint'] / 1000);

		$Points           = GetFleetPoints ( $CurPlanet );
		$TFleetCount     += $Points['FleetCount'];
		$GCount          += $Points['FleetCount'];
		$PlanetPoints    += ($Points['FleetPoint'] / 1000);
		$TFleetPoints    += ($Points['FleetPoint'] / 1000);

		$GPoints         += $PlanetPoints;
	}

	// Складываем очки флота
	if ( $FleetPoints[$CurUser['id']]['points'] ) {
		$TFleetCount     += $FleetPoints[$CurUser['id']]['count'];
				$GCount          += $FleetPoints[$CurUser['id']]['count'];
			$TFleetPoints    += $FleetPoints[$CurUser['id']]['points'];
			$PlanetPoints     = $FleetPoints[$CurUser['id']]['points'];
				$GPoints         += $PlanetPoints;
	}

	// Заносим данные в таблицу
	$QryInsertStats  = "INSERT INTO {{table}} SET ";
	$QryInsertStats .= "`id_owner` = '". $CurUser['id'] ."', ";
	$QryInsertStats .= "`username` = '". $CurUser['username'] ."', ";
	$QryInsertStats .= "`id_ally` = '". $CurUser['ally_id'] ."', ";
	$QryInsertStats .= "`ally_name` = '". $CurUser['ally_name'] ."', ";
	$QryInsertStats .= "`stat_type` = '1', ";
	$QryInsertStats .= "`stat_code` = '1', ";
	$QryInsertStats .= "`tech_points` = '". $TTechPoints ."', ";
	$QryInsertStats .= "`tech_count` = '". $TTechCount ."', ";
	$QryInsertStats .= "`tech_old_rank` = '". $OldTechRank ."', ";
	$QryInsertStats .= "`build_points` = '". $TBuildPoints ."', ";
	$QryInsertStats .= "`build_count` = '". $TBuildCount ."', ";
	$QryInsertStats .= "`defs_points` = '". $TDefsPoints ."', ";
	$QryInsertStats .= "`defs_count` = '". $TDefsCount ."', ";
	$QryInsertStats .= "`fleet_points` = '". $TFleetPoints ."', ";
	$QryInsertStats .= "`fleet_count` = '". $TFleetCount ."', ";
	$QryInsertStats .= "`fleet_old_rank` = '". $OldFleetRank ."', ";
	$QryInsertStats .= "`total_points` = '". $GPoints ."', ";
	$QryInsertStats .= "`total_count` = '". $GCount ."', ";
	$QryInsertStats .= "`total_old_rank` = '". $OldTotalRank ."', ";
	$QryInsertStats .= "`stat_hide` = '". $hide ."', ";
	$QryInsertStats .= "`stat_date` = '". $StatDate ."';";
	doquery ( $QryInsertStats , 'statpoints');
}

// Определяем место в стате
$OldArrayRank = array();

$Rank           = 1;
$RankQry        = doquery("SELECT `id_owner` FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND stat_hide = 0 ORDER BY `tech_count` DESC;", 'statpoints');
while ($TheRank = mysql_fetch_assoc($RankQry) ) {
	
	$OldArrayRank[ $TheRank[id_owner] ]['tech_rank'] = $Rank;

	$Rank++;
}

$Rank           = 1;
$RankQry        = doquery("SELECT `id_owner` FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND stat_hide = 0 ORDER BY `fleet_points` DESC;", 'statpoints');
while ($TheRank = mysql_fetch_assoc($RankQry) ) {

	$OldArrayRank[ $TheRank[id_owner] ]['fleet_rank'] = $Rank;

	$Rank++;
}

$Rank           = 1;
$RankQry        = doquery("SELECT `id_owner` FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND stat_hide = 0 ORDER BY `total_points` DESC;", 'statpoints');
while ($TheRank = mysql_fetch_assoc($RankQry) ) {

	$OldArrayRank[ $TheRank[id_owner] ]['total_rank'] = $Rank;

	$QryUpdateStats  = "UPDATE {{table}} SET ";
	$QryUpdateStats .= "`tech_rank` = '". $OldArrayRank[ $TheRank[id_owner] ]['tech_rank'] ."', ";
	$QryUpdateStats .= "`fleet_rank` = '". $OldArrayRank[ $TheRank[id_owner] ]['fleet_rank'] ."', ";
	$QryUpdateStats .= "`total_rank` = '". $OldArrayRank[ $TheRank[id_owner] ]['total_rank'] ."' ";
	$QryUpdateStats .= "WHERE ";
	$QryUpdateStats .= " `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '". $TheRank['id_owner'] ."';";
	doquery ( $QryUpdateStats , 'statpoints');
	$Rank++;
}

$Message .= "Обновление статистики игроков: ОК<br>";

// Выбираем все альянсы
$GameAllys  = doquery("SELECT a.`id`, s.total_rank, s.tech_rank, s.fleet_rank FROM {{table}}alliance a LEFT JOIN {{table}}statpoints s ON s.id_owner = a.id AND `stat_type` = '2';", '');

doquery ("DELETE FROM {{table}} WHERE `stat_type` = '2';",'statpoints');

while ($CurAlly = mysql_fetch_assoc($GameAllys)) {

	if ($CurAlly['total_rank'] != "") {
		$OldTotalRank = $CurAlly['total_rank'];
		$OldTechRank  = $CurAlly['tech_rank'];
		$OldFleetRank = $CurAlly['fleet_rank'];
		$OldBuildRank = 0;
		$OldDefsRank  = 0;
	} else {
		$OldTotalRank = 0;
		$OldTechRank  = 0;
		$OldBuildRank = 0;
		$OldDefsRank  = 0;
		$OldFleetRank = 0;
	}

	$QrySumSelect   = "SELECT ";
	$QrySumSelect  .= "SUM(`tech_points`)  as `TechPoint`, ";
	$QrySumSelect  .= "SUM(`tech_count`)   as `TechCount`, ";
	$QrySumSelect  .= "SUM(`build_points`) as `BuildPoint`, ";
	$QrySumSelect  .= "SUM(`build_count`)  as `BuildCount`, ";
	$QrySumSelect  .= "SUM(`defs_points`)  as `DefsPoint`, ";
	$QrySumSelect  .= "SUM(`defs_count`)   as `DefsCount`, ";
	$QrySumSelect  .= "SUM(`fleet_points`) as `FleetPoint`, ";
	$QrySumSelect  .= "SUM(`fleet_count`)  as `FleetCount`, ";
	$QrySumSelect  .= "SUM(`total_points`) as `TotalPoint`, ";
	$QrySumSelect  .= "SUM(`total_count`)  as `TotalCount` ";
	$QrySumSelect  .= "FROM {{table}} WHERE `stat_type` = '1' AND stat_hide = 0 AND `id_ally` = '". $CurAlly['id'] ."';";
	$Points         = doquery( $QrySumSelect, 'statpoints', true);

	$TTechCount     = $Points['TechCount'];
	$TTechPoints    = $Points['TechPoint'];
	$TBuildCount    = $Points['BuildCount'];
	$TBuildPoints   = $Points['BuildPoint'];
	$TDefsCount     = $Points['DefsCount'];
	$TDefsPoints    = $Points['DefsPoint'];
	$TFleetCount    = $Points['FleetCount'];
	$TFleetPoints   = $Points['FleetPoint'];
	$GCount         = $Points['TotalCount'];
	$GPoints        = $Points['TotalPoint'];

	$QryInsertStats  = "INSERT INTO {{table}} SET ";
	$QryInsertStats .= "`id_owner` = '". $CurAlly['id'] ."', ";
	$QryInsertStats .= "`id_ally` = '0', ";
	$QryInsertStats .= "`stat_type` = '2', ";
	$QryInsertStats .= "`stat_code` = '1', ";
	$QryInsertStats .= "`tech_points` = '". $TTechPoints ."', ";
	$QryInsertStats .= "`tech_count` = '". $TTechCount ."', ";
	$QryInsertStats .= "`tech_old_rank` = '". $OldTechRank ."', ";
	$QryInsertStats .= "`build_points` = '". $TBuildPoints ."', ";
	$QryInsertStats .= "`build_count` = '". $TBuildCount ."', ";
	$QryInsertStats .= "`build_old_rank` = '". $OldBuildRank ."', ";
	$QryInsertStats .= "`defs_points` = '". $TDefsPoints ."', ";
	$QryInsertStats .= "`defs_count` = '". $TDefsCount ."', ";
	$QryInsertStats .= "`defs_old_rank` = '". $OldDefsRank ."', ";
	$QryInsertStats .= "`fleet_points` = '". $TFleetPoints ."', ";
	$QryInsertStats .= "`fleet_count` = '". $TFleetCount ."', ";
	$QryInsertStats .= "`fleet_old_rank` = '". $OldFleetRank ."', ";
	$QryInsertStats .= "`total_points` = '". $GPoints ."', ";
	$QryInsertStats .= "`total_count` = '". $GCount ."', ";
	$QryInsertStats .= "`total_old_rank` = '". $OldTotalRank ."', ";
	$QryInsertStats .= "`stat_date` = '". $StatDate ."';";
	doquery ( $QryInsertStats , 'statpoints');
}

$Message .= "Обновление статистики альянсов: ОК<br>";

// Чистим старые логи
doquery ( "DELETE FROM {{table}} WHERE `message_time` <= '". (time() - 432000) ."';", 'messages');
doquery ( "DELETE FROM {{table}} WHERE `time` <= '". (time() - 172800) ."';", 'rw');
doquery ( "DELETE FROM {{table}} WHERE `time` <= '". (time() - 86400) ."';", 'moneys');
doquery ( "DELETE FROM {{table}} WHERE `timestamp` <= '". (time() - 1209600) ."';", 'chat');
doquery ( "DELETE FROM {{table}} WHERE `time` <= '". (time() - 86400) ."';", 'lostpwd');
doquery ( "DELETE FROM {{table}} WHERE `time` <= '". (time() - 259200) ."';", 'logs');

$Message .= "Удаление старых логов: ОК";

echo $Message;

?>