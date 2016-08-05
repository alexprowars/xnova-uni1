<?php

if(!defined("INSIDE")) die("attemp hacking");

includeLang('reg');

$wylosuj = rand(100000,9000000); 
$kod = md5($wylosuj);
function sendpassemail($emailaddress, $password) {
	global $lang, $kod;

	$parse['gameurl']  = GAMEURL;
	$parse['password'] = $password;
	$parse['kod']      = $kod;
	$email             = parsetemplate($lang['mail_welcome'], $parse);
	$status            = mymail($emailaddress, $lang['mail_title'], $email);
	return $status;
}

function mymail($to, $title, $body, $from = '') {
	$from = trim($from);

	if (!$from) {
		$from = ADMINEMAIL;
	}

	$head  = "From: ".$from;

	return mail($to, $title, $body, $head);
}

if ($_POST) {
	$errors    = 0;
	$errorlist = "";

	$_POST['email'] = strip_tags(strtolower($_POST['email']));
	if (!is_email($_POST['email'])) {
		$errorlist .= "\"" . $_POST['email'] . "\" " . $lang['error_mail'];
		$errors++;
	}

	$girilen = $_REQUEST["captcha"]; 
	if($_SESSION['captcha'] != $girilen && $_SESSION['captcha'] != ""){ 
		$errorlist .= $lang['error_captcha']; 
		$errors++;    
	}

	if (!$_POST['planet']) {
		$errorlist .= $lang['error_planet'];
		$errors++;
	}

	if (!eregi("^[a-zA-Zà-ÿÀ-ß0-9_\.\,\-\!\?\*\ ]+$", $_POST['planet'])){
		$errorlist .= $lang['error_hplanetnum'];
		$errors++;
	}

	if (!$_POST['character']) {
		$errorlist .= $lang['error_character'];
		$errors++;
	}

	if (strlen($_POST['passwrd']) < 4) {
		$errorlist .= $lang['error_password'];
		$errors++;
	}

	if (preg_match("/[^A-z0-9_\-]/", $_POST['character']) == 1) {
		$errorlist .= $lang['error_charalpha'];
		$errors++;
	}

	if ($_POST['rgt'] != 'on') {
		$errorlist .= $lang['error_rgt'];
		$errors++;
	}

	$ExistUser = doquery("SELECT `username` FROM {{table}} WHERE `username` = '". mysql_escape_string($_POST['character']) ."' LIMIT 1;", 'users', true);
	if ($ExistUser) {
		$errorlist .= $lang['error_userexist'];
		$errors++;
	}

	$ExistMail = doquery("SELECT `email` FROM {{table}} WHERE `email` = '". mysql_escape_string($_POST['email']) ."' LIMIT 1;", 'users_inf', true);
	if ($ExistMail) {
		$errorlist .= $lang['error_emailexist'];
		$errors++;
	}

	if ($_POST['sex'] != 'F' && $_POST['sex'] != 'M') {
		$errorlist .= $lang['error_sex'];
		$errors++;
	}

	if ($errors != 0) {
		message ($errorlist, $lang['Register']);
	} else {
		$newpass        = $_POST['passwrd'];
		$UserName       = $_POST['character'];
		$UserEmail      = $_POST['email'];
		$UserPlanet     = $_POST['planet'];

		$md5newpass     = md5($newpass);
		$aktywacja = time()+2678400;

		$QryInsertUser  = "INSERT INTO {{table}} SET ";
		$QryInsertUser .= "`username` = '". mysql_escape_string(strip_tags( $UserName )) ."', ";
		$QryInsertUser .= "`sex` = '".      mysql_escape_string( $_POST['sex'] )         ."', ";
		$QryInsertUser .= "`id_planet` = '0', ";
		$QryInsertUser .= "`user_lastip` = '". $_SERVER['HTTP_X_REAL_IP'] ."', ";
		$QryInsertUser .= "`onlinetime` = '". time() ."';";
		doquery( $QryInsertUser, 'users');

		$NewUser        = doquery("SELECT `id` FROM {{table}} WHERE `username` = '". mysql_escape_string($_POST['character']) ."' LIMIT 1;", 'users', true);
		$iduser         = $NewUser['id'];

		$QryInsertUser  = "INSERT INTO {{table}} SET ";
		$QryInsertUser .= "`id` = '".    $iduser            ."', ";
		$QryInsertUser .= "`email` = '".    mysql_escape_string( $UserEmail )            ."', ";
		$QryInsertUser .= "`email_2` = '".  mysql_escape_string( $UserEmail )            ."', ";
		$QryInsertUser .= "`register_time` = '". time() ."', ";
		$QryInsertUser .= "`password`='". $md5newpass ."';";
		doquery( $QryInsertUser, 'users_inf');

		if (isset($_SESSION['ref'])){
			$refe = doquery("SELECT id FROM {{table}} WHERE id = ".$_SESSION['ref']."", 'users', true);
			if ($refe['id'] > 0) {
				doquery("INSERT INTO {{table}} VALUES (".$iduser.", ".$_SESSION['ref'].")", 'refs');
			}
		}

		$query = doquery("SELECT * FROM {{table}}",'config');
    			while ( $row = mysql_fetch_assoc($query) ) {
    				$game_config[$row['config_name']] = $row['config_value'];
    			}

		$LastSettedGalaxyPos  = $game_config['LastSettedGalaxyPos'];
		$LastSettedSystemPos  = $game_config['LastSettedSystemPos'];
		$LastSettedPlanetPos  = $game_config['LastSettedPlanetPos'];

		while (!isset($newpos_checked)) {
			for ($Galaxy = $LastSettedGalaxyPos; $Galaxy <= MAX_GALAXY_IN_WORLD; $Galaxy++) {
				for ($System = $LastSettedSystemPos; $System <= MAX_SYSTEM_IN_GALAXY; $System++) {
					for ($Posit = $LastSettedPlanetPos; $Posit <= 4; $Posit++) {
						$Planet = round (rand ( 4, 12) );

						switch ($LastSettedPlanetPos) {
							case 1:
								$LastSettedPlanetPos += 1;
								break;
							case 2:
								$LastSettedPlanetPos += 1;
								break;
							case 3:
								if ($LastSettedSystemPos == MAX_SYSTEM_IN_GALAXY) {
									$LastSettedGalaxyPos += 1;
									$LastSettedSystemPos  = 1;
									$LastSettedPlanetPos  = 1;
									break;
								} else {
									$LastSettedPlanetPos  = 1;
								}
								$LastSettedSystemPos += 1;
								break;
						}
						break;
					}
					break;
				}
				break;
			}

			$QrySelectGalaxy  = "SELECT `id_planet` ";
			$QrySelectGalaxy .= "FROM {{table}} ";
			$QrySelectGalaxy .= "WHERE ";
			$QrySelectGalaxy .= "`galaxy` = '". $Galaxy ."' AND ";
			$QrySelectGalaxy .= "`system` = '". $System ."' AND ";
			$QrySelectGalaxy .= "`planet` = '". $Planet ."' ";
			$QrySelectGalaxy .= "LIMIT 1;";
			$GalaxyRow = doquery( $QrySelectGalaxy, 'galaxy', true);

			if ($GalaxyRow["id_planet"] == "0") {
				$newpos_checked = true;
			}

			if (!$GalaxyRow) {
				CreateOnePlanetRecord ($Galaxy, $System, $Planet, $NewUser['id'], $UserPlanet, true);
				$newpos_checked = true;
			}
			if ($newpos_checked) {
				doquery("UPDATE {{table}} SET `config_value` = '". $LastSettedGalaxyPos ."' WHERE `config_name` = 'LastSettedGalaxyPos';", 'config');
				doquery("UPDATE {{table}} SET `config_value` = '". $LastSettedSystemPos ."' WHERE `config_name` = 'LastSettedSystemPos';", 'config');
				doquery("UPDATE {{table}} SET `config_value` = '". $LastSettedPlanetPos ."' WHERE `config_name` = 'LastSettedPlanetPos';", 'config');
			}
		}
		$PlanetID = doquery("SELECT `id` FROM {{table}} WHERE `id_owner` = '". $NewUser['id'] ."' LIMIT 1;", 'planets', true);

		$QryUpdateUser  = "UPDATE {{table}} SET ";
		$QryUpdateUser .= "`id_planet` = '". $PlanetID['id'] ."', ";
		$QryUpdateUser .= "`current_planet` = '". $PlanetID['id'] ."', ";
		$QryUpdateUser .= "`galaxy` = '". $Galaxy ."', ";
		$QryUpdateUser .= "`system` = '". $System ."', ";
		$QryUpdateUser .= "`planet` = '". $Planet ."' ";
		$QryUpdateUser .= "WHERE ";
		$QryUpdateUser .= "`id` = '". $NewUser['id'] ."' ";
		$QryUpdateUser .= "LIMIT 1;";
		doquery( $QryUpdateUser, 'users');

		doquery("UPDATE {{table}} SET `config_value` = `config_value` + '1' WHERE `config_name` = 'users_amount' LIMIT 1;", 'config');

		$passw_string = md5("".$login['password']."---NOIPSECURiTy---".$login['id']."");
		
		setcookie("x_id", $NewUser['id'], 0, "/", "uni1.xnova.su", 0);	
		setcookie("x_secret", $passw_string, 0, "/", "uni1.xnova.su", 0);	
			
		header("location: ?set=overview");
	}
} else {
	$parse               = $lang;
	$parse['servername'] = $game_config['game_name'];

	display(parsetemplate(gettemplate('registry_form'), $parse), $lang['registry'], false, false);
}

?>