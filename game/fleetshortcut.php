<?php

if(!defined("INSIDE")) die("attemp hacking");

$mode = $_GET['mode'];
$a = $_GET['a'];

if (isset($_GET['mode'])){
	if ($_POST){
		if ($_POST["n"] == "" || !eregi("^[a-zA-Zа-яА-Я0-9_\.\,\-\!\?\*\ ]+$", $_POST["n"])) $_POST["n"] = "Планета";

		$g = intval($_POST['g']);
		$s = intval($_POST['s']);
		$i  = intval($_POST['p']);
		$t  = intval($_POST['t']);

		if ($g < 1 || $g > 9)
			$g = 1;
		if ($s < 1 || $s > 499)
			$s = 1;
		if ($i < 1 || $i > 15)
			$i = 1;
		if ($t != 1 && $t != 2 && $t != 3 && $t != 5)
			$t = 1;

		$r = strip_tags($_POST['n']).",".$g.",".$s.",".$i.",".$t."\r\n";
		$user['fleet_shortcut'] .= $r;
		doquery("UPDATE {{table}} SET fleet_shortcut = '".$user['fleet_shortcut']."' WHERE id = ".$user['id']."", "users");
		message("Ссылка на планету добавлена!", "Добавление ссылки", "?set=fleetshortcut");
	}
	$g = intval($_GET['g']);
	$s = intval($_GET['s']);
	$i  = intval($_GET['i']);
	$t  = intval($_GET['t']);

	if ($g < 1 || $g > 9)
		$g = 1;
	if ($s < 1 || $s > 499)
		$s = 1;
	if ($i < 1 || $i > 15)
		$i = 1;
	if ($t != 1 && $t != 2 && $t != 3 && $t != 5)
		$t = 1;

	$page = "<form method=POST action=?set=fleetshortcut&mode=add><table border=0 cellpadding=0 cellspacing=1 width=519>
		<tr height=20>
		<td colspan=2 class=c>Имя [Галактика:Система:Планета]</td>
		</tr><tr height=\"20\"><th>
		<input type=text name=n value=\"\" size=32 maxlength=32 title=\"Название\">
		<input type=text name=g value=\"".$g."\" size=3 maxlength=1 title=\"Галактика\">
		<input type=text name=s value=\"".$s."\" size=3 maxlength=3 title=\"Система\">
		<input type=text name=p value=\"".$i."\" size=3 maxlength=3 title=\"Планета\">
	 	<select name=t>";
	$page .= '<option value="1"'.(($t==1)?" SELECTED":"").">Планета</option>";
	$page .= '<option value="2"'.(($t==2)?" SELECTED":"").">Поле обломков</option>";
	$page .= '<option value="3"'.(($t==3)?" SELECTED":"").">Луна</option>";
	$page .= '<option value="5"'.(($t==5)?" SELECTED":"").">Военная база</option>";
	$page .= "</select>
		</th></tr><tr>
		<th><input type=\"reset\" value=\"Очистить\"> <input type=\"submit\" value=\"Добавить\">";
	$page .= "</th></tr>";
	$page .= '<tr><td colspan=2 class=c><a href=?set=fleetshortcut>Назад</a></td></tr></tr></table></form>';
} elseif (isset($_GET['a'])) {
	if ($_POST){
		$a = intval($_POST['a']);
		$scarray = explode("\r\n",$user['fleet_shortcut']);
		if ($_POST["delete"]){
			unset($scarray[$a]);
			$user['fleet_shortcut'] =  implode("\r\n",$scarray);
			doquery("UPDATE {{table}} SET fleet_shortcut = '{$user[fleet_shortcut]}' WHERE id = {$user[id]}","users");
			message("Ссылка была успешно удалена!", "Удаление ссылки", "?set=fleetshortcut");
		} else {
			$r = explode(",",$scarray[$a]);
			$r[0] = strip_tags($_POST['n']);
			$r[1] = intval($_POST['g']);
			$r[2] = intval($_POST['s']);
			$r[3] = intval($_POST['p']);
			$r[4] = intval($_POST['t']);

			if ($r[1] < 1 || $r[1] > 9)
				$r[1] = 1;
			if ($r[2] < 1 || $r[2] > 499)
				$r[2] = 1;
			if ($r[3] < 1 || $r[3] > 15)
				$r[3] = 1;
			if ($r[4] != 1 && $r[4] != 2 && $r[4] != 3 && $r[4] != 5)
				$r[4] = 1;

			$scarray[$a] = implode(",",$r);
			$user['fleet_shortcut'] =  implode("\r\n",$scarray);
			doquery("UPDATE {{table}} SET fleet_shortcut='{$user[fleet_shortcut]}' WHERE id={$user[id]}","users");
			message("Ссылка была обновлена!", "Обновление ссылки", "?set=fleetshortcut");
		}
	}
	if ($user['fleet_shortcut']){
		$a = intval($_GET['a']);
		$scarray = explode("\r\n",$user['fleet_shortcut']);
		$c = explode(',',$scarray[$a]);

		$page = "<form method=POST action=?set=fleetshortcut&a=$a><table border=0 cellpadding=0 cellspacing=1 width=519>
			<tr height=20>
			<td colspan=2 class=c>Редактирование: {$c[0]} [{$c[1]}:{$c[2]}:{$c[3]}]</td>
			</tr>";
		$page .= "<tr height=\"20\"><th>
		<input type=hidden name=a value=$a>
		<input type=text name=n value=\"{$c[0]}\" size=32 maxlength=32>
		<input type=text name=g value=\"{$c[1]}\" size=3 maxlength=1>
		<input type=text name=s value=\"{$c[2]}\" size=3 maxlength=3>
		<input type=text name=p value=\"{$c[3]}\" size=3 maxlength=3>
		 <select name=t>";
		$page .= '<option value="1"'.(($c[4]==1)?" SELECTED":"").">Планета</option>";
		$page .= '<option value="2"'.(($c[4]==2)?" SELECTED":"").">Поле обломков</option>";
		$page .= '<option value="3"'.(($c[4]==3)?" SELECTED":"").">Луна</option>";
		$page .= '<option value="5"'.(($c[4]==5)?" SELECTED":"").">Военная база</option>";
		$page .= "</select>
		</th></tr><tr>
		<th><input type=reset value=\"Очистить\"> <input type=submit value=\"Обновить\"> <input type=submit name=delete value=\"Удалить\">";
		$page .= "</th></tr>";

	}else{
		$page .= message("Ваш список быстрых ссылок пуст!", "Ссылки", "?set=fleetshortcut");
	}

	$page .= '<tr><td colspan=2 class=c><a href=?set=fleetshortcut>Назад</a></td></tr></tr></table></form>';


} else {

	$page = '<table border="0" cellpadding="0" cellspacing="1" width="519">
	<tr height="20">
	<td colspan="2" class="c">Ссылки (<a href="?set=fleetshortcut&mode=add">Добавить</a>)</td>
	</tr>';

	if($user['fleet_shortcut']){

		$scarray = explode("\r\n",$user['fleet_shortcut']);
		$i=$e=0;
		foreach($scarray as $a => $b){
			if($b!=""){
			$c = explode(',',$b);
			if($i==0){$page .= "<tr height=\"20\">";}
			$page .= "<th width=50%><a href=\"?set=fleetshortcut&a=".$e++."\">";
			$page .= "{$c[0]} {$c[1]}:{$c[2]}:{$c[3]}";
			if($c[4]==2){$page .= " (E)";}elseif($c[4]==3){$page .= " (L)";}elseif($c[4]==5){$page .= " (B)";}
			$page .= "</a></th>";
			if($i==1){$page .= "</tr>";}
			if($i==1){$i=0;}else{$i=1;}
			}

		}
		if($i==1){$page .= "<th>&nbsp;</th></tr>";}

	}else{$page .= "<th colspan=\"2\">Список ссылок пуст</th>";}

	$page .= '<tr><td colspan=2 class=c><a href=?set=fleet>Назад</a></td></tr></tr></table>';
}
display($page,"Закладки");

?>
