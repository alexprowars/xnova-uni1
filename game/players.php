<?php

if(!defined("INSIDE")) die("attemp hacking");


$BodyTPL = gettemplate('playercard');
$RowsTPL = gettemplate('playercard_rows');
$parse   = $lang;
	
$playerid  = (isset($_POST['id']))  ? $_POST['id']  : $_GET['id'];
if (!isset($playerid)) {
	$playerid  = 0;
}
$ownid  = $user['id'];
if (!isset($ownid)) {
	$ownid  = 0;
}

$PlayerCard = doquery("SELECT * FROM {{table}} WHERE `id` = '". intval($playerid) ."';", 'users');
if ($daten = mysql_fetch_array($PlayerCard)){

	if ($daten['avatar'] != 0)
		$parse['avatar'] = "/images/avatars/".$daten['avatar'].".jpg";

	$gesamtkaempfe = 0;
	$gesamtkaempfe = $daten['raids_win'] + $daten['raids_lose'];
	if ($gesamtkaempfe ==0) {
		$siegprozent	=0;
		$loosprozent	=0;
	} else {
		$siegprozent	= 100 / $gesamtkaempfe * $daten['raids_win'];
		$loosprozent	= 100 / $gesamtkaempfe * $daten['raids_lose'];
	}

	if (!$daten['ally_id'])   	$daten['ally_id'] 	= "- - -";
	if (!$daten['ally_name']) 	$daten['ally_name'] 	= "- - -";

	$planets = doquery("SELECT * FROM {{table}} WHERE `galaxy` = '". $daten['galaxy'] ."' and `system` = '". $daten['system'] ."' and `planet_type` = '1' and `planet` = '". $daten['planet'] ."';", 'planets', true);
	$parse['userplanet'] = $planets['name'];

	$points = doquery("SELECT * FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '". $daten['id'] ."';", 'statpoints', true);
	$parse['tech_rank']      	= pretty_number( $points['tech_rank'] );
	$parse['tech_points']      	= pretty_number( $points['tech_points'] );
	$parse['build_rank']      	= pretty_number( $points['build_rank'] );
	$parse['build_points']     	= pretty_number( $points['build_points'] );
	$parse['fleet_rank']      	= pretty_number( $points['fleet_rank'] );
	$parse['fleet_points']      = pretty_number( $points['fleet_points'] );
	$parse['total_rank']      	= pretty_number( $points['total_rank'] );
	$parse['total_points']     	= pretty_number( $points['total_points'] );
	
	if ($ownid	!= 0)							
		$parse['player_buddy'] = "<a href=\"?set=buddy&mode=" . $ownid . "&amp;u=" . $playerid . "\" title=\"Добавить в друзья\">Добавить в друзья</a>";
	else
		$parse['player_buddy'] = "";

	if ($ownid	!= 0)
		$parse['player_mes'] = "<a href=\"?set=messages&mode=write&id=" . $playerid . "\">Написать сообщение</a>";
	else
		$parse['player_mes'] = "";

	if ($daten['icq'] != 0)
		$parse['icq'] = $daten['icq'];
	else
		$parse['icq'] = "нет";

	if ($daten['sex'] == 'M')
		$parse['sex'] = "Мужской";
	else
		$parse['sex'] = "Женский";

	if ($daten['vkontakte'] != '') {
		$parse['vkontakte'] = "<a href=\"http://vkontakte.ru/";
		if (is_numeric($daten['vkontakte']))
				$parse['vkontakte'] .= 'id';
		$parse['vkontakte'] .= $daten['vkontakte']."\" target=_blank>Профиль</a>";
		
	} else
		$parse['vkontakte'] = "нет";

	$parse['username']			= $daten['username'];
	$parse['galaxy']            = $daten['galaxy'];
	$parse['system']           	= $daten['system'];
	$parse['planet']           	= $daten['planet'];
	$parse['register_time']    	= $daten['register_time'];
	$parse['ally_id']          	= $daten['ally_id'];
	$parse['ally_name']        	= $daten['ally_name'];
	$parse['wons']             	= pretty_number( $daten['raids_win'] );
	$parse['loos']             	= pretty_number( $daten['raids_lose'] );
	$parse['siegprozent']      	= round($siegprozent, 2);
	$parse['loosprozent']      	= round($loosprozent, 2);
	$parse['total']				= $daten['raids'];
	$parse['totalprozent']     	= 100;
} else 
	die('Параметр задан неверно');

if ($user['id'])
	display(parsetemplate(gettemplate('players'), $parse), "Информация о игроке", false);
else
	display(parsetemplate(gettemplate('players'), $parse), "Информация о игроке", false, false);
?>
