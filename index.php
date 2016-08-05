<?php
die(':(');

session_set_cookie_params(0, '/', 'uni1.xnova.su');
session_start();

date_default_timezone_set("Europe/Moscow");

if (mktime(2, 0, 0) - 300 < time() && mktime(2, 0, 0) + 1200 > time())
	die('<center>Сервер перезагружается. Окончание перезагрузки в 02:20 по московскому времени. Осталось ждать: '.(mktime(2, 0, 0) + 1200 - time()).' сек.<br><br><img src="images/trollface.png"></center>');

elseif (date("G", time()) == 1 && $_GET['set'] != "chat")
	echo "&nbsp;&nbsp;Время до перезагрузки: ". (mktime(2, 0, 0) - time() - 300)." сек.";
	
//error_reporting(E_ALL ^ E_NOTICE);

define('VERSION','0.7b1');

$phpEx = "php";

$server_load =  sys_getloadavg();

$user          = array();
$lang          = array();
$IsUserChecked = false;

define('DEFAULT_SKINPATH' , '/skins/default/');
define('TEMPLATE_DIR'     , 'templates/');
define('DEFAULT_LANG'     , 'ru');
define('INSIDE'  , true);
define('INSTALL' , false);

$set = $_GET['set'];

include('includes/debug.class.php');
$debug = new debug();

include('includes/constants.php');
include('includes/functions.php');
include('includes/unlocalised.php');
include('includes/functions/SendSimpleMessage.php');
include('includes/functions/RestoreFleetToPlanet.php');
include('includes/functions/CheckPlanetBuildingQueue.php');
include('includes/functions/CheckPlanetUsedFields.php');
include('includes/functions/CreateOneMoonRecord.php');
include('includes/functions/CreateOnePlanetRecord.php');
include('includes/functions/IsElementBuyable.php');
include('includes/functions/GetMaxConstructibleElements.php');
include('includes/functions/GetElementRessources.php');
include('includes/functions/ElementBuildListBox.php');
include('includes/functions/InsertBuildListScript.php');
include('includes/functions/HandleTechnologieBuild.php');
include('includes/functions/IsTechnologieAccessible.php');
include('includes/functions/SetNextQueueElementOnTop.php');
include('includes/functions/ShowTopNavigationBar.php');
include('includes/functions/PlanetResourceUpdate.php');
include('includes/functions/HandleElementBuildingQueue.php');
include('includes/functions/SpyTarget.php');

include('includes/vars.php');
include('includes/mysql.php');
include('includes/strings.php');

if ($set == "overview" || $set == "fleet" || !$set)
	$UpdateFlyFleet = true;

$Result        	= CheckTheUser ( $IsUserChecked );
$IsUserChecked 	= $Result['state'];
$user 			= $Result['record'];

//if ($user['username'] != "AlexPro" && $user['id'])  die("Технический перерыв");

define('TEMPLATE_NAME'    , 'XNova_default');

includeLang ("system");
includeLang ('tech');


if ($UpdateFlyFleet == true) {

    if (!isset($user['id']))
        $UpdateFlyFleet = false;
    else {
		$f = fopen("fleets_time_upd.xd", "r");
		$text = fread($f, filesize("fleets_time_upd.xd"));
		fclose($f);

		if (time() - $text < 5)
			$UpdateFlyFleet = false;
			
		if ($UpdateFlyFleet == true && (mktime(2, 0, 0) - 900 < time() && mktime(2, 0, 0) + 1800 > time()))
			$UpdateFlyFleet = false;
	}
}

if ($server_load[0] >= 0.8) 
	$UpdateFlyFleet = false;

if ($UpdateFlyFleet == true){

	$fs = fopen("fleets_time_upd.xd","w");
	flock ($fs, LOCK_EX);
	fwrite($fs, time() + 60);
	flock ($fs, LOCK_UN);
	fclose($fs);

	$_fleets = doquery("SELECT * FROM {{table}} WHERE (`fleet_start_time` <= '".time()."' AND `fleet_mess` = '0' AND `fleet_mission` != '15') OR (`fleet_end_stay` <= '".time()."' AND `fleet_mess` != '1' AND `fleet_end_stay` != '0') OR (`fleet_end_time` < '". time() ."' AND `fleet_mess` != '0') ORDER BY fleet_time LIMIT 3;", 'fleets');

	if($_fleets){

		include_once('includes/CombatEngine.php');

		while ($CurrentFleet = mysql_fetch_array($_fleets)) {

			//echo"<br>$CurrentFleet[fleet_id] - $CurrentFleet[fleet_mission] - t:".time()." - s:$CurrentFleet[fleet_start_time] - st:$CurrentFleet[fleet_end_stay] - e:$CurrentFleet[fleet_end_time] - m:$CurrentFleet[fleet_mess]";

		switch ($CurrentFleet["fleet_mission"]) {
			case 1:
				MissionCaseAttack ( $CurrentFleet );
				break;
			case 2:
				if ($CurrentFleet['fleet_mess'] == 0) {
					if ($CurrentFleet['fleet_start_time'] <= time()) {
						$QryUpdateFleet  = "UPDATE {{table}} SET `fleet_mess` = 1 WHERE `fleet_id` = '". $CurrentFleet['fleet_id'] ."' LIMIT 1 ;";
						doquery( $QryUpdateFleet, 'fleets');
					}
				}
				if ($CurrentFleet['fleet_end_time'] <= time()) {
					RestoreFleetToPlanet ( $CurrentFleet, true );
					doquery("DELETE FROM {{table}} WHERE `fleet_id` = ". $CurrentFleet["fleet_id"], 'fleets');
				}
				break;
			case 3:
				MissionCaseTransport ( $CurrentFleet );
				break;
			case 4:
				MissionCaseStay ( $CurrentFleet );
				break;
			case 5:
				MissionCaseStayAlly ( $CurrentFleet );
				break;
			case 6:
				MissionCaseSpy ( $CurrentFleet );
				break;
			case 7:
				MissionCaseColonisation ( $CurrentFleet );
				break;
			case 8:
				MissionCaseRecycling ( $CurrentFleet );
				break;
			case 9:
				MissionCaseDestruction ( $CurrentFleet );
				break;
			case 10:
				MissionCaseCreateBase ( $CurrentFleet );
				break;
			case 15:
				MissionCaseExpedition ( $CurrentFleet );
				break;
			default: {
				doquery("DELETE FROM {{table}} WHERE `fleet_id` = '". $CurrentFleet['fleet_id'] ."';", 'fleets');
			}
		}
		}
	}
	unset($_fleets);

	// Ракетная атака
	include('includes/rak.php');
	
	$fs = fopen("fleets_time_upd.xd","w");
	flock ($fs, LOCK_EX);
	fwrite($fs, time());
	flock ($fs, LOCK_UN);
	fclose($fs);
}

$dpath = (!$user["dpath"]) ? DEFAULT_SKINPATH : $user["dpath"];

if ( isset($user['id'])){
	// Если находимся не в чате, то получаем информацию о планете
	if ($set != "chat" && $set != "api") {
		// Выставляем планету выбранную игроком из списка планет
		SetSelectedPlanet ( $user );
		// Убираем лишнюю нагрузку на вывод
		if ($set != "officier" && $set != "buddy" && $set != "notes" && $set != "tools" && $set != "admin" && $set != "news" && $set != "stat" && $set != "messages") {
			// Выбираем информвцию о планете
			$planetrow = doquery("SELECT * FROM {{table}} WHERE `id` = '".$user['current_planet']."';", 'planets', true);
			// Проверяем корректность заполненных полей
			CheckPlanetUsedFields($planetrow);
		}
		if ($planetrow['id']) {
			// Обновляем ресурсы на планете когда это необходимо
			if (($set == "overview" || $set == "galaxy" || $set == "resources" || $set == "imperium" || $set == "infokredits" || $set == "techtree" || $set == "search" || $set == "options" || !$set) && $planetrow['last_update'] > (time() - 60))
				PlanetResourceUpdate ( $user, $planetrow, time(), true );
			else {
				PlanetResourceUpdate ( $user, $planetrow );
				// Обновляем постройки на планете
				UpdatePlanetBatimentQueueList ( $planetrow, $user );
			}
		}
	}
	
	switch ($set) {
		case "overview":
			include("game/overview.php");
			break;
		case "imperium":
			include("game/imperium.php");
			break;
		case "galaxy":
			include("game/galaxy.php");
			break;
		case "chat":
			include("game/chat.php");
			break;
		case "alliance":
			include("game/alliance.php");
			break;
		case "buildings":
			include("game/buildings.php");
			break;
		case "fleet":
			include("game/fleet.php");
			break;
		case "floten1":
			include("game/floten1.php");
			break;
		case "floten2":
			include("game/floten2.php");
			break;
		case "floten3":
			include("game/floten3.php");
			break;
		case "api":
			include("game/api.php");
			break;
		case "stat":
			include("game/stat.php");
			break;
		case "messages":
			include("game/messages.php");
			break;
		case "options":
			include("game/options.php");
			break;
		case "admin":
			include("game/admin.php");
			break;			
		case "rw":
			include("game/rw.php");
			break;
		case "raketenangriff":
			include("game/raketenangriff.php");
			break;
		case "ruletka":
			include("game/ruletka.php");
			break;
		case "verband":
			include("game/verband.php");
			break;
		case "infos":
			include("game/infos.php");
			break;
		case "search":
			include("game/search.php");
			break;
		case "techtree":
			include("game/techtree.php");
			break;
		case "fleetback":
			include("game/fleetback.php");
			break;
		case "fleetshortcut":
			include("game/fleetshortcut.php");
			break;
		case "phalanx":
			include("game/phalanx.php");
			break;
		case "jumpgate":
			include("game/jumpgate.php");
			break;
		case "resources":
			include("game/resources.php");
			break;
		case "log":
			include("game/log.php");
			break;
		case "logs":
			include("game/logs.php");
			break;
		case "officier":
			include("game/officier.php");
			break;
		case "infokredits":
			include("game/infokredits.php");
			break;
		case "arcade":
			include("game/arcade.php");
			break;
		case "pay":
			include("game/pay.php");
			break;
		case "banned":
			include("game/banned.php");
			break;
		case "avatar":
			include("game/avatar.php");
			break;
		case "players":
			include("game/players.php");
			break;
		case "quickfleet":
			include("game/quickfleet.php");
			break;			
		case "contact":
			include("game/contact.php");
			break;
		case "logout":
			include("game/logout.php");
			break;
		case "marchand":
			include("game/marchand.php");
			break;
		case "news":
			include("game/news.php");
			break;
		case "buddy":
			include("game/buddy.php");
			break;
		case "notes":
			include("game/notes.php");
			break;
		case "tools":
			include("game/tools.php");
			break;
		default:
			include("game/overview.php");
	}
} else {
	// Если не вошли
	switch ($set) {
		case "api":
			include("game/api.php");
			break;
		case "news":
			include("game/news.php");
			break;
		case "contact":
			include("game/contact.php");
			break;
		case "players":
			include("game/players.php");
			break;
		case "banned":
			include("game/banned.php");
			break;
		case "log":
			include("game/log.php");
			break;
		case "lostpassword":
			include("game/lostpassword.php");
			break;
		case "reg":
			include("game/reg.php");
			break;
		default:
			include("game/login.php");
	}
}


?>