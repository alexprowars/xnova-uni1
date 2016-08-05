<?php

if(!defined("INSIDE")) die("attemp hacking");

$raportrow = doquery("SELECT * FROM {{table}} WHERE `rid` = '".addslashes($_GET["raport"])."';", 'rw', true);

if ((($raportrow["id_owner1"] == $user["id"]) or ($raportrow["id_owner2"] == $user["id"]) or $user['authlevel'] > 0) && $raportrow['rid']) {
	$Page  = "<html><head>";
	$Page .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$dpath."formate.css\">";
	$Page .= "<meta http-equiv=\"content-type\" content=\"text/html; charset=windows-1251\" />";
	$Page .= "</head><body>";
	$Page .= "<table width=\"99%\"><tr><td><center>";

	if ($raportrow['id_owner1'] == $user['id'] && $raportrow['a_zestrzelona'] == 1 && !$user['authlevel']) {
		$Page .= "Контакт с вашим флотом потерян.<br>(Ваш флот был уничтожен в первой волне атаки.)";
	} else {
		$Page .= $raportrow['raport'];
	}
	
	$Page .= "</center></td></tr><tr align=center><td>ID боевого доклада: <font color=red>".$raportrow['rid']."</color></td></tr></table></body></html>";

	echo $Page;
} else
	message('Вы не можете просматривать этот боевой доклад', 'Ошибка', '', 0, false);

?>