<?php 

if(!defined("INSIDE")) die("attemp hacking");

$login_spl = true;

if ($_POST['emails']) { 
	$login = doquery("SELECT u.id, u.username, u.security, ui.password FROM {{table}}users u, {{table}}users_inf ui WHERE ui.id = u.id AND (u.`username` = '" . mysql_escape_string($_POST['emails']) . "' OR ui.`email` = '" . mysql_escape_string($_POST['emails']) . "') LIMIT 1", "", true); 

	if ($login) { 
		if ($login['password'] == md5($_POST['password'])) { 
			if (isset($_POST["rememberme"])) { 
				$expiretime = time() + 604800; 
			} else { 
				$expiretime = 0; 
			} 
			
			if ($login['security'] == 1)
				$passw_string = md5("".$login['password']."---".$_SERVER['HTTP_X_REAL_IP']."---xNoVasIlko".$login['id']."");
			else
				$passw_string = md5("".$login['password']."---NOIPSECURiTy---".$login['id']."");
				
			setcookie("x_id", $login['id'], $expiretime, "/", "uni1.xnova.su", 0);
			setcookie("x_secret", $passw_string, $expiretime, "/", "uni1.xnova.su", 0);

			setcookie("uni", "uni1", $expiretime, "/", ".xnova.su", 0);			

			header("Location: ?set=overview");

			exit; 
		} else { 
			message('Неверное E-mail и/или пароль<br><br><a href=?set=login>Назад</a>', 'Ошибка', '', 0, false); 
		} 
	} else { 
		message('Такого игрока не существует<br><br><a href=?set=login>Назад</a>', 'Ошибка', '', 0, false); 
	} 
} else {

	$page = parsetemplate(gettemplate('login_body'), array()); 
	display($page, 'Вход в игру', false, false); 
}
?>