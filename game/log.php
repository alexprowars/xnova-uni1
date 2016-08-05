<?php

if(!defined("INSIDE")) die("attemp hacking");

if ($user['id'] && !$_GET['id']) {

$message = "";

if ($_GET['mysql'] == 'new'){
	if (!$_POST['title']) {
		$message = '<h1><font color=red>Введите название для боевого отчёта.</h1>';
	} elseif (!$_POST['code']) {
		$message = '<h1><font color=red>Введите ID боевого отчёта.</h1>';
	} else {
		$log = doquery("SELECT * FROM {{table}} WHERE `rid` = '".(mysql_escape_string($_POST['code']))."';", 'rw', true);
		if ($log){
		
			if (($log["id_owner1"] == $user["id"]) and ($log["a_zestrzelona"] == 1)) {
				$SaveLog = "Контакт с вашим флотом потерян.<br>(Ваш флот был уничтожен в первой волне атаки.)";
			} else {
				$SaveLog .= stripslashes($log["raport"]);
			}

			doquery("INSERT INTO {{table}} (`user`, `title`, `log`) VALUES ('".$user['username']."', '".addslashes($_POST['title'])."', '".$SaveLog."')","savelog");
			$message = 'Боевой отчёт успешно сохранён.';
		} else {
			$message = 'Боевой отчёт не найден в базе';
		}
	}
	message($message, "Логовница", "?set=log", 2);
}

if($_GET['mode']=='delete' && $_GET['id_l']){

	$id = intval($_GET['id_l']);

	$sql = doquery("SELECT * FROM {{table}} WHERE id = '".$id."' ","savelog");
	$raportrow = mysql_fetch_assoc($sql);

	if ($user['username'] == $raportrow['user']){
		doquery("DELETE FROM {{table}} WHERE `id` = ".$id." ","savelog");
	}else{
		$message = "Ошибка удаления.";
		message($message, "Логовница", "?set=log", 1);
	}
}

switch($_GET['mode']){

case 'new':
{
	$page = "<br><br><br><table width=\"600\"><tr><td class=\"c\"><h1>Сохранение боевого доклада</h1></td></tr>";
	$page .="<tr><th><form action=?set=log&mysql=new method=POST>";
	$page .="Название:<br>";
	$page .="<input type=text name=title size=50 maxlength=100><br>";
	$page .="ID боевого доклада:<br>";
	$page .="<input type=text name=code size=50 maxlength=40>";
	$page .="<br>";
	$page .="<br><input type=submit value='Сохранить'>";
	$page .="</form></th></tr></table>";

	display($page, "Логовница", false);
	break;
}

default:
{

	$ksql = doquery("SELECT `id`, `user`, `title` FROM {{table}} WHERE `user` = '".$user['username']."' ","savelog");

	$page ="<br><br><br><table width=600>";
	$page .="<tr><th colspan=4><h1>Логовница XNova Game</h1></th></tr>";
	$page .="<tr>";
	$page .="<td class=c colspan=4>Ваши сохранённые логи</td>";
	$page .="</tr>";
	$page .="<tr><td class=c>№</td><td class=c>Название</td><td class=c>Ссылка</td><td class=c>Управление логом</td></tr>";
	$i = 0;
		while($krow = mysql_fetch_array($ksql)){
		$i++;
			$page .= "<tr><td class=b align=center>".$i."</td><td class=b align=center>".$krow['title']."</td><td class=b align=center><a href=?set=log&id=".$krow['id']." target=_new>Открыть</a></td><td class=b align=center><a href='?set=log&mode=delete&id_l=".$krow['id']."'>Удалить лог</a></td></tr>";
		}
	if ($i == 0) $page .= "<tr align=center><td  class=b colspan=4>У вас пока нет сохранённых логов.</td></tr>";

	$page .= "<tr><td class=c colspan=4><a href=?set=log&mode=new>Создать новый лог боя</a></td></tr></table>";

	display($page, "Логовница", false);
}}
}

if ($_GET['id'] != ''){
	$raportrow = doquery("SELECT * FROM {{table}} WHERE id = '".intval($_GET['id'])."' ","savelog", true);
	if ($raportrow) {
		$Page  = "<html><head><title>".stripslashes($raportrow["title"])."</title>";
		$Page .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$dpath."formate.css\">";
		$Page .= "<meta http-equiv=\"content-type\" content=\"text/html; charset=windows-1251\" />";
		$Page .= "</head><body>";
		$Page .= "<table width=\"99%\"><tr><td><center>".stripslashes($raportrow["log"])."</center></td></tr></table>";
		$Page .= "</body></html>";
	} else {
		$Page  = "<html><head><link rel=\"stylesheet\" type=\"text/css\" href=\"".$dpath."formate.css\">";
		$Page .= "<meta http-equiv=\"content-type\" content=\"text/html; charset=windows-1251\" />";
		$Page .= "</head><body><center>Запрашиваемого лога не существует в базе данных</center></body></html>";
	}

	echo $Page;
}

?>