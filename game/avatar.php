<?

if(!defined("INSIDE")) die("attemp hacking");

if ($_GET['sets'] == "1") {
	if ($user['credits'] > 25000) {
		$id = intval($_POST['avatar']);
		if ($id < 1 || $id > 56)
			message("� ��� ��� �������� �� ���� ������.", "������", "?set=avatar", 3);

		doquery("UPDATE {{table}} SET avatar = '".$id."', credits = credits - 25000 WHERE id = ".$user['id']."", "users");

		message("������ ������� ����������.", "��", "?set=options", 3);

	} else {
		message("� ��� �� ������� ������� ��� ����� �������.", "������", "?set=avatar", 3);
	}
}
 
$page = "<script>function av(id){document.ava.src = '/images/avatars/'+id+'.jpg';}</script>";

$page .= "<br><br><form action=\"?set=avatar&sets=1\" method=\"POST\"><table width=500><tr><td class=c colspan=2>����� �������</td></tr>";
$page .= "<tr><th colspan=2>��������� ����� ������� - 25.000 ��.</th></tr>";
$page .= "<tr><th width=30%><select name=avatar onchange=\"av(this.value)\">";

for ($i = 1; $i < 57; $i++) {

	$page .= "<option value=".$i.""; if ($user['avatar'] == $i) $page .= " selected"; $page .= ">� ".$i."";

}

$page .= "</select></th><th><img src=\"/images/avatars/"; if ($user['avatar'] != 0) $page .= $user['avatar']; else $page .= "1"; $page .= ".jpg\" name=ava></th></tr><tr><td class=c colspan=2><input type=submit value=\"������� ������\"></td></tr></table></form>";

display($page, "����� �������", false);

?>