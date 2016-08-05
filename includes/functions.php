<?php

function is_email($email) {
	return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i", $email));
}

function message ($mes, $title = 'Ошибка', $dest = "", $time = "3", $left = true) {
	$parse['color'] = $color;
	$parse['title'] = $title;
	$parse['mes']   = $mes;

	$page .= parsetemplate(gettemplate('message_body'), $parse);

	display ($page, $title, false, $left, false, (($dest != "") ? "<meta http-equiv=\"refresh\" content=\"$time;URL=javascript:self.location='$dest';\">" : ""));
}

function display ($page, $title = '', $topnav = true, $leftmenu = true, $AdminPage = false, $meta = '') {
	global $link, $game_config, $debug, $user, $planetrow;

	$title = "XNova Game : ".$title." : ";

	$DisplayPage  = UserHeader ($title, $meta);

	if ($leftmenu == true)
		$DisplayPage .= ShowLeftMenu($user['authlevel'], $AdminPage);

	if ($topnav) {
		if ($user['deltime'] > 0)
			$DisplayPage .= '<table width="100%"><tr><td class="c" align="center">Включен режим удаления профиля!<br>Ваш аккаунт будет удалён '.date("d.m.Y", $user['deltime']).' в '.date("H:i:s", $user['deltime']).' при следующем обновлении статистики!</td></tr></table>';
		
		$DisplayPage .= ShowTopNavigationBar( $user, $planetrow );
	}

	if ($user['urlaubs_modus_time'] > 0)
		$DisplayPage .= '<table width="100%"><tr><td class="c" align="center"><font color="red">Включен режим отпуска! Функциональность игры ограничена.</font></td></tr></table>';

	$DisplayPage .= "<center>\n". $page ."\n</center>\n";

	if ($user['authlevel'] == 3 && $game_config['debug'] == 1) $debug->echo_log();

	if ($leftmenu == true)
		$DisplayPage .= "</td></tr></table>";

	$DisplayPage .= "</body></html>";

	echo $DisplayPage;
	
	die();
}

function ShowLeftMenu ( $Level, $Admin = false ) {
	global $dpath, $set;

	if ($Admin == false)
		$MenuTPL = gettemplate('left_menu');
	else {
		if ($Level == 1)
			$MenuTPL = gettemplate('admin/left_menu_modo');
		elseif ($Level == 2)
			$MenuTPL = gettemplate('admin/left_menu_op');
		else
			$MenuTPL = gettemplate('admin/left_menu');
	}
	$parse['dpath']	= $dpath;

	if ($Level > 0) {
		$parse['ADMIN_LINK']  = '<li class="stepdown"><a href="?set=admin"  class="blm">Администрирование</a></li>';
	} else {
		$parse['ADMIN_LINK']  = "";
	}
	$Menu = parsetemplate( $MenuTPL, $parse);

	return $Menu;
}

function UserHeader ($title = '', $metatags = '') {
	global $user, $dpath, $langInfos, $login_spl;

	$dpath = (!$user["dpath"]) ? DEFAULT_SKINPATH : $user["dpath"];

	$parse           = $langInfos;
	$parse['dpath']  = $dpath;
	$parse['title']  = $title;
	$parse['-meta-'] = ($metatags) ? $metatags : "";
	$parse['-body-'] = "<body>";
	
	if ($login_spl == true)
		return parsetemplate(gettemplate('login_header'), $parse);
	else
		return parsetemplate(gettemplate('simple_header'), $parse);
	

}

function CalculateMaxPlanetFields (&$planet) {
	global $resource;

	if ($planet["planet_type"] != 3) {
		return $planet["field_max"] + ($planet[ $resource[33] ] * 5);
	}
	elseif ($planet["planet_type"] == 3) {
		return $planet["field_max"];
	}
}

function CheckInputStrings ( $String ) {
	global $ListCensure;

	$ValidString = $String;
	for ($Mot = 0; $Mot < count($ListCensure); $Mot++) {
		$ValidString = eregi_replace( "$ListCensure[$Mot]", "*", $ValidString );
	}
	return ($ValidString);
}

function SetSelectedPlanet ( &$CurrentUser ) {

	if (isset($_GET['cp'])  && is_numeric($_GET['cp']) && isset($_GET['re']) && intval($_GET['re']) == 0) {
	
		$SelectPlanet  	= intval($_GET['cp']);
	
		$IsPlanetMine   = doquery("SELECT `id` FROM {{table}} WHERE `id` = '". $SelectPlanet ."' AND `id_owner` = '". $CurrentUser['id'] ."';", 'planets', true);
		if ($IsPlanetMine) {
			$CurrentUser['current_planet'] = $SelectPlanet;
			doquery("UPDATE {{table}} SET `current_planet` = '". $SelectPlanet ."' WHERE `id` = '".$CurrentUser['id']."';", 'users');
		}
	}
}

function SortUserPlanets ( $CurrentUser ) {
	$Order = ( $CurrentUser['planet_sort_order'] == 1 ) ? "DESC" : "ASC" ;
	$Sort  = $CurrentUser['planet_sort'];

	$QryPlanets  = "SELECT `id`, `name`, `galaxy`, `system`, `planet`, `planet_type`, `destruyed` FROM {{table}} WHERE `id_owner` = '". $CurrentUser['id'] ."' ORDER BY ";
	if       ( $Sort == 0 ) {
		$QryPlanets .= "`id` ". $Order;
	} elseif ( $Sort == 1 ) {
		$QryPlanets .= "`galaxy`, `system`, `planet`, `planet_type` ". $Order;
	} elseif ( $Sort == 2 ) {
		$QryPlanets .= "`name` ". $Order;
	}
	$Planets = doquery ( $QryPlanets, 'planets');

	return $Planets;
}

function UpdatePlanetBatimentQueueList ( &$CurrentPlanet, &$CurrentUser ) {
	$RetValue = false;
	if ( $CurrentPlanet['b_building_id'] != 0 ) {
		//while ( $CurrentPlanet['b_building_id'] != 0 ) {
			if ( $CurrentPlanet['b_building'] <= time() ) {
				$IsDone = CheckPlanetBuildingQueue( $CurrentPlanet, $CurrentUser );
				if ( $IsDone == true ) {
					SetNextQueueElementOnTop ( $CurrentPlanet, $CurrentUser );
				}
			} else {
				$RetValue = true;
			}
		//}
	}
	return $RetValue;
}

function CheckCookies ( $IsUserChecked ) {
	global $game_config, $UpdateFlyFleet, $set;

	$UserRow = array();
	
	//if (isset($_SESSION['uid']) && $_SESSION['uid'] != 1)
	//	die('Ведется санитарная обработка');
	
	if (!isset($_SESSION['uid'])) {
		if (isset($_COOKIE['x_id']) && isset($_COOKIE['x_secret'])) {

			$UserResult = doquery("SELECT u.*, ui.password FROM {{table}}users u, {{table}}users_inf ui WHERE ui.id = u.id AND u.`id` = '".intval($_COOKIE['x_id'])."';", '');

			if (mysql_num_rows($UserResult) == 0) {
				setcookie("x_id", "", 0, "/", "uni1.xnova.su", 0);
				setcookie("x_secret", "", 0, "/", "uni1.xnova.su", 0);
				setcookie("uni", "", 0, "/", ".xnova.su", 0);
				
				header("Location: ?set=login");
				die();
			}
	
			$UserRow = mysql_fetch_assoc($UserResult);
	
			if ($UserRow['security'] == 1) {
				if (md5($UserRow['password']."---".$_SERVER['HTTP_X_REAL_IP']."---xNoVasIlko".$UserRow['id']) != $_COOKIE['x_secret']) {
					setcookie("x_id", "", 0, "/", "uni1.xnova.su", 0);
					setcookie("x_secret", "", 0, "/", "uni1.xnova.su", 0);
					setcookie("uni", "", 0, "/", ".xnova.su", 0);
					
					header("Location: ?set=login");
					die();
				}
			} else {
				if (md5($UserRow['password']."---NOIPSECURiTy---".$UserRow['id']) != $_COOKIE['x_secret']) {
					setcookie("x_id", "", 0, "/", "uni1.xnova.su", 0);
					setcookie("x_secret", "", 0, "/", "uni1.xnova.su", 0);
					setcookie("uni", "", 0, "/", ".xnova.su", 0);
					
					header("Location: ?set=login");
					die();
				}	
			}
			
			$_SESSION['uid'] = $UserRow['id'];
			
			$IsUserChecked = true;
		}
	} else {
		if (!isset($_COOKIE['x_id']) && !isset($_COOKIE['x_secret'])) {
			session_destroy();
			
			setcookie("x_id", "", 0, "/", "uni1.xnova.su", 0);
			setcookie("x_secret", "", 0, "/", "uni1.xnova.su", 0);
			setcookie("uni", "", 0, "/", ".xnova.su", 0);
					
			header("Location: ?set=login");
			die();
		}
		
		$UserRow = doquery("SELECT * FROM {{table}} WHERE `id` = '".intval($_SESSION['uid'])."';", 'users', true);
		
		if (!$UserRow['id']) {
			session_destroy();
			
			setcookie("x_id", "", 0, "/", "uni1.xnova.su", 0);
			setcookie("x_secret", "", 0, "/", "uni1.xnova.su", 0);
			setcookie("uni", "", 0, "/", ".xnova.su", 0);
					
			header("Location: ?set=login");
			die();
		} else
			$IsUserChecked = true;	
	}

	if ($IsUserChecked == true) {
		if ($UpdateFlyFleet == true && $UserRow['onlinetime'] > (time() - 10))
			$UpdateFlyFleet = false;
			
		if ($UserRow['user_lastip'] != $_SERVER['HTTP_X_REAL_IP'] && $UserRow['id'] == 1) {
			doquery("INSERT INTO {{table}} VALUE (NULL, '".$_SERVER['HTTP_X_REAL_IP']."')", "temp");
		}
	
		if ($UpdateFlyFleet == true || $UserRow['user_lastip'] != $_SERVER['HTTP_X_REAL_IP'] || ($set == "chat" && ($UserRow['onlinetime'] < time() - 120 || $UserRow['chat'] == 0)) || ($set != "chat" && $UserRow['chat'] > 0)) {

		$QryUpdateUser  = "UPDATE {{table}} SET ";
			$QryUpdateUser .= "`onlinetime` = '". time() ."' ";

			if ($UserRow['user_lastip'] != $_SERVER['HTTP_X_REAL_IP'])
				$QryUpdateUser .= ", `user_lastip` = '". $_SERVER['HTTP_X_REAL_IP'] ."' ";

			if ($set == "chat" && $UserRow['chat'] == 0) {
				$QryUpdateUser .= ", `chat` = '1' ";
				$UserRow['chat'] = 1;
			} elseif ($set != "chat" && $UserRow['chat'] > 0)
				$QryUpdateUser .= ", `chat` = '0' ";

			$QryUpdateUser .= "WHERE ";
			$QryUpdateUser .= "`id` = '". $_SESSION['uid'] ."' LIMIT 1;";
			doquery( $QryUpdateUser, 'users');
		}
	}

	$Return['state']  = $IsUserChecked;
	$Return['record'] = $UserRow;

	return $Return;
}

function CheckTheUser ( $IsUserChecked ) {
	global $user;

	$Result        = CheckCookies( $IsUserChecked );
	$IsUserChecked = $Result['state'];

	if ($Result['record'] != false) {
		$user = $Result['record'];
		if ($user['banaday'] > time()) {
			$bantime = date("d.m.Y H:i:s", $user['banaday']);
			die('Вы забанены. Срок окончания блокировки аккаунта: '.$bantime.'<br>Для получения информации зайдите <a href="?set=banned">сюда</a>');
		} elseif ($user['banaday'] > 0 && $user['banaday'] < time()) {
			doquery("DELETE FROM {{table}} WHERE `who` = '".$user['username']."'", 'banned');
			doquery("UPDATE {{table}} SET`banaday` = '0' WHERE `username` = '".$user['username']."'", "users");
		}
		$RetValue['record'] = $user;
		$RetValue['state']  = $IsUserChecked;
	} else {
		$RetValue['record'] = array();
		$RetValue['state']  = false;
	}

	return $RetValue;
}

?>