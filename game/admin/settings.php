<?php

if(!defined("INSIDE")) die("attemp hacking");

$query = doquery("SELECT * FROM {{table}}",'config');
while ( $row = mysql_fetch_assoc($query) ) {
    	$game_config[$row['config_name']] = $row['config_value'];
}

function DisplayGameSettingsPage ( $CurrentUser ) {
	global $lang, $game_config, $_POST;

	includeLang('admin/settings');

	if ( $CurrentUser['authlevel'] >= 3 ) {
		if ($_POST['opt_save'] == "1") {

			if (isset($_POST['LastSettedGalaxyPos']) && is_numeric($_POST['LastSettedGalaxyPos'])) {
				$game_config['LastSettedGalaxyPos'] = $_POST['LastSettedGalaxyPos'];
			}

			if (isset($_POST['LastSettedSystemPos']) && is_numeric($_POST['LastSettedSystemPos'])) {
				$game_config['LastSettedSystemPos'] = $_POST['LastSettedSystemPos'];
			}

			doquery("UPDATE {{table}} SET `config_value` = '". $game_config['LastSettedGalaxyPos']    ."' WHERE `config_name` = 'LastSettedGalaxyPos';", 'config');
			doquery("UPDATE {{table}} SET `config_value` = '". $game_config['LastSettedSystemPos']    ."' WHERE `config_name` = 'LastSettedSystemPos';", 'config');

			message ('Настройки игры успешно сохранены!', 'Выполнено', '?');
		} else {
			$parse                           = $lang;

			$parse['LastSettedGalaxyPos']    = $game_config['LastSettedGalaxyPos'];
			$parse['LastSettedSystemPos']    = $game_config['LastSettedSystemPos'];

			$PageTPL                         = gettemplate('admin/options_body');
			$Page                           .= parsetemplate( $PageTPL,  $parse );

			display ( $Page, $lang['adm_opt_title'], false, true, true );
		}
	} else {
		message ( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
	}
	return $Page;
}

	$Page = DisplayGameSettingsPage ( $user );

?>
