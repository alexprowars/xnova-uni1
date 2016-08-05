<?

if(!defined("INSIDE")) die("attemp hacking");

if ($_GET['sets'] == "1") {
	if ($user['credits'] > 25000) {
		$id = intval($_POST['avatar']);
		if ($id < 1 || $id > 56)
			message("У вас нет подписки на этот аватар.", "Ошибка", "?set=avatar", 3);

		doquery("UPDATE {{table}} SET avatar = '".$id."', credits = credits - 25000 WHERE id = ".$user['id']."", "users");

		message("Аватар успешно установлен.", "ОК", "?set=options", 3);

	} else {
		message("У вас не хватает средств для смены аватара.", "Ошибка", "?set=avatar", 3);
	}
}
 
$page = "<script>function av(id){document.ava.src = '/images/avatars/'+id+'.jpg';}</script>";

$page .= "<br><br><form action=\"?set=avatar&sets=1\" method=\"POST\"><table width=500><tr><td class=c colspan=2>Выбор аватара</td></tr>";
$page .= "<tr><th colspan=2>Стоимость смены аватара - 25.000 кр.</th></tr>";
$page .= "<tr><th width=30%><select name=avatar onchange=\"av(this.value)\">";

for ($i = 1; $i < 57; $i++) {

	$page .= "<option value=".$i.""; if ($user['avatar'] == $i) $page .= " selected"; $page .= ">№ ".$i."";

}

$page .= "</select></th><th><img src=\"/images/avatars/"; if ($user['avatar'] != 0) $page .= $user['avatar']; else $page .= "1"; $page .= ".jpg\" name=ava></th></tr><tr><td class=c colspan=2><input type=submit value=\"Сменить аватар\"></td></tr></table></form>";

display($page, "Выбор аватара", false);

?>