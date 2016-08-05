<?php

if(!defined("INSIDE")) die("attemp hacking");

if ($user['authlevel'] == 3) {

	$parse   = $lang;

if ($_POST){

	if ($_POST['md5q'] != "" || $_POST['user'] != "") {

		$user_ch = doquery("SELECT `id` FROM {{table}} WHERE `username` = '".$_POST['user']."'", 'users', true);
		if (isset($user_ch['id'])){

		doquery ("UPDATE {{table}} SET `password` = '".md5($_POST['md5q'])."' WHERE `id` = '".$user_ch['id']."';", 'users_inf');
		message('Пароль успешно изменён.'  , 'Ошибка', 'md5changepass.php', 3);

		}else{
		message('Такого игрока несуществует.'  , 'Ошибка', 'md5changepass.php', 3);
		}
	} else {
		message('Не введён логин игрока или новый пароль.'  , 'Ошибка', 'md5changepass.php', 3);
	}
}
	
	$PageTpl = gettemplate("admin/changepass");
	$Page    = parsetemplate( $PageTpl, $parse);

	display( $Page, $lang['md5_title'], false, true, true );
} else {
	message( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
}

?>