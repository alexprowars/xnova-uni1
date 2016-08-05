<?php

if(!defined("INSIDE")) die("attemp hacking");

if ($user['authlevel'] >= "2") {

	$parse['dpath'] = $dpath;
	$parse = $lang;

	$mode = $_GET['modes'];

	if ($mode != 'change') {
		$parse['Name'] = "Введите логин игрока";
	} elseif ($mode == 'change') {
		$nam = $_POST['nam'];
		
		$us = doquery("SELECT id, username, banaday, urlaubs_modus_time FROM {{table}} WHERE username = '".addslashes($nam)."';", "users", true);
		if ($us['id']) {
			doquery("DELETE FROM {{table}} WHERE who = '".$us['username']."'", 'banned');
			doquery("UPDATE {{table}} SET banaday = 0 WHERE username='".$us['username']."'", "users");
			if ($us['urlaubs_modus_time'] == 1)
				doquery("UPDATE {{table}} SET urlaubs_modus_time = 0 WHERE username='".$us['username']."'", "users");
				
			message("Игрок {$nam} разбанен!", 'Информация');
		} else
			message("Игрок {$nam} не найден!", 'Информация');
	}

	display(parsetemplate(gettemplate('admin/unbanned'), $parse), "Overview", false, true, true);
} else {
	message( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
}
?>