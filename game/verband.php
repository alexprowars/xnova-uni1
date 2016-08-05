<?php

if(!defined("INSIDE")) die("attemp hacking");

includeLang('fleet');

$fleetid = intval($_POST['fleetid']);

if (!is_numeric($fleetid) || empty($fleetid)) {
	header("Location: ?set=overview");
	exit();
}

$query = doquery("SELECT * FROM {{table}} WHERE fleet_id = '".$fleetid."' AND fleet_owner = ".$user['id']." AND fleet_mission=1", 'fleets');

if (mysql_num_rows($query) != 1) {
	message('Этот флот не существует или его больше чем 1!', 'Ошибка');
}

$fleet = mysql_fetch_array($query);
$aks = doquery("SELECT * FROM {{table}} WHERE id = '".$fleet['fleet_group']."' LIMIT 1", 'aks', true);

if ($fleet['fleet_start_time'] <= time() || $fleet['fleet_end_time'] < time() || $fleet['fleet_mess'] == 1) {
	message('Ваш флот возвращается на планету!', 'Ошибка');
}

if (!isset($_POST['send'])) {

	if ($_POST['action'] == 'addaks'){

		if (empty($fleet['fleet_group'])) {
			$rand = mt_rand(100000, 999999999);

			doquery("INSERT INTO {{table}} SET
			`name` = '".addslashes($_POST['groupname'])."',
			`fleet_id` = ".$fleetid.",
			`galaxy` = '" . $fleet['fleet_end_galaxy'] . "',
			`system` = '" . $fleet['fleet_end_system'] . "',
			`planet` = '" . $fleet['fleet_end_planet'] . "',
			`planet_type` = '" . $fleet['fleet_end_type'] . "',
			`user_id` = '" . $user['id'] . "'", 'aks');

			$aksid = mysql_insert_id();

			if (empty($aksid)) {
				message($lang['aks_error'], $lang['error']);
				exit();
			}

			$aks = doquery("SELECT * FROM {{table}} WHERE id = '".$aksid."' LIMIT 1", 'aks', true);

			doquery("UPDATE {{table}} SET fleet_group = '".$aksid."' WHERE fleet_id = '".$fleetid."'", 'fleets');
			$fleet['fleet_group'] = $aksid;

		} else {
				message('Для этого флота уже задана ассоциация!', 'Ошибка');
		}
	}


	if ($_POST['action'] == 'adduser') {

		if ($aks['fleet_id'] != $fleetid) {
			message("Вы не можете менять имя ассоциации", $lang['error']);
			exit();
		}

		$addtogroup = mysql_escape_string($_POST['addtogroup']);

		$user_ = doquery("SELECT * FROM {{table}} WHERE username = '".$addtogroup."'", 'users');

		if (mysql_num_rows($user_) != 1) {
			message("Игрок не найден", $lang['error']);
			exit();
		}

		$user_data = mysql_fetch_array($user_);
		$aks_user = doquery("SELECT * FROM {{table}} WHERE aks_id = ".$aks['id']." AND user_id = ".$user_data['id']."", 'aks_user');

		if (mysql_num_rows($aks_user) > 0) {
			message("Игрок уже приглашён для нападения", $lang['error']);
		}

		doquery("INSERT INTO {{table}} VALUES (".$aks['id'].", ".$user_data['id'].")", 'aks_user');

		$planet_daten = doquery("SELECT `id_owner` FROM {{table}} WHERE galaxy = '".$aks['galaxy']."' AND system = '".$aks['system']."' AND planet = '".$aks['planet']."' AND planet_type = '".$aks['planet_type']."'", 'planets', true);
		$owner = doquery("SELECT username FROM {{table}} WHERE id = '".$planet_daten['id_owner']."'", 'users', true);

		$message = "Игрок ".$user['username']." приглашает вас произвести совместное нападение на планету ".$planet_daten['name']." [".$aks['galaxy'].":".$aks['system'].":".$aks['planet']."] игрока ".$owner['username'].". Имя ассоциации: ".$aks['name'].". Если вы отказываетесь, то просто проигнорируйте данной сообщение.";

		doquery("INSERT INTO {{table}} SET
		`message_owner`='".$user_data['id']."',
		`message_sender`='".$user['id']."',
		`message_time`='".time()."',
		`message_type`='0',
		`message_from`='Флот',
		`message_text`='".$message."'",'messages');              
		doquery("UPDATE {{table}} SET new_message = new_message+1 WHERE id='".$user_data['id']."'",'users'); 

	}


	if ($_POST['action'] == "changename") {

		if ($aks['fleet_id'] != $fleetid) {
			message("Вы не можете менять имя ассоциации", $lang['error']);
			exit();
		}

		$name = $_POST['groupname'];

		if (strlen($name) > 20) {
			message("Слишком длинное имя ассоциации", $lang['error']);
			exit();
		}

		if (!eregi("^[a-zA-Zа-яА-Я0-9_\.\,\-\!\?\*\ ]+$", $name))
			message("Имя ассоциации содержит запрещённые символы", $lang['error']);

		$name = mysql_escape_string(strip_tags($name));

		$x = doquery("SELECT * FROM {{table}} WHERE name = '".$name."'", 'aks');
		if (mysql_num_rows($x) >= 1) {
			message("Имя уже зарезервировано другим игроком", $lang['error']);
			exit();
		}

		$aks['name'] = $name;

		doquery("UPDATE {{table}} SET name = '".$name."' WHERE id = '".$aks['id']."'", 'aks');


	}

$missiontype = array(
	1 => 'Атаковать',
	2 => 'Объединить',
	3 => 'Транспорт',
	4 => 'Оставить',
	5 => 'Удерживать',
	6 => 'Шпионаж',
	7 => 'Колонизировать',
	8 => 'Переработать',
	9 => 'Уничтожить',
);

$page = '<script language="JavaScript" src="scripts/flotten.js"></script>
<script language="JavaScript" src="scripts/ocnt.js"></script>
<center>
	<table width="710" border="0" cellpadding="0" cellspacing="1">
	<tr height="20">
		<td colspan="9" class="c">Флоты в совместной атаке</td>
	</tr>
	<tr height="20">
		<th>ID</th>
		<th>Задание</th>
		<th> Кол-во</th>
		<th>Отправлен</th>
		<th>Прибытие (цель)</th>
		<th>Цель</th>
		<th>Прибытие (возврат)</th>
		<th>Прибудет через</th>
		<th>Планета старта</th>
	</tr>';

if ($fleet['fleet_group'] == 0)
	$fq = doquery("SELECT * FROM {{table}} WHERE fleet_id = ".$fleetid."", 'fleets');
else
	$fq = doquery("SELECT * FROM {{table}} WHERE fleet_group = ".$fleet['fleet_group']."", 'fleets');

$i = 0;
while ($f = mysql_fetch_array($fq)) {
	$i++;

	$page .= "<tr height=20><th>$i</th><th>";

	$page .= "<a title=\"\">{$missiontype[$f[fleet_mission]]}</a>";
	if (($f['fleet_start_time'] + 1) == $f['fleet_end_time'])
		$page .= " <a title=\"R&uuml;ckweg\">(F)</a>";
	$page .= "</th><th><a title=\"";

	$fleets = explode(";", $f['fleet_array']);
	$e = 0;
	foreach($fleets as $a => $b) {
		if ($b != '') {
			$e++;
			$a = explode(",", $b);
			$page .= "{$lang['tech']{$a[0]}}: {$a[1]}\n";
			if ($e > 1) {
				$page .= "\t";
			}
		}
	}
	$page .= "\">" . pretty_number($f[fleet_amount]) . "</a></th>";
	$page .= "<th>[{$f[fleet_start_galaxy]}:{$f[fleet_start_system]}:{$f[fleet_start_planet]}]</th>";
	$page .= "<th>" . date("d. M Y H:i:s", $f['fleet_start_time']) . "</th>";
	$page .= "<th>[{$f[fleet_end_galaxy]}:{$f[fleet_end_system]}:{$f[fleet_end_planet]}]</th>";
	$page .= "<th>" . date("d. M Y H:i:s", $f['fleet_end_time']) . "</th>";
	$page .= " </form>";

	$page .= "<th><font color=\"lime\"><div id=\"time_0\"><font>" . pretty_time(floor($f['fleet_end_time'] + 1 - time())) . "</font></th><th>";
	$page .= $f['fleet_owner_name']."</th>";
	$page .= "</div></font></tr>";
}

if ($i == 0) {
	$page .= "<th>-</th><th>-</th><th>-</th><th>-</th><th>-</th><th>-</th><th>-</th><th>-</th><th>-</th>";
}
$page .= ''.$maxflot.'</table></center>';

if ($fleet['fleet_group'] == 0) {
	$rand = mt_rand(100000, 999999999);
	$page .= '<table width="710" border="0" cellpadding="0" cellspacing="1">
	<tr height="20">
		<td class="c" colspan="2">Создание ассоциации флота</td>
	</tr>
	<form action="?set=verband" method="POST">
	<input type="hidden" name="fleetid" value="'.$fleetid.'" />
	<input type="hidden" name="action" value="addaks" />
	<tr>
			<th colspan="2"><input name="groupname" value="AKS'.$rand.'" size=50 /> <br /> <input type="submit" value="Создать" /></th>
	</tr>
	</form>
	</table>';
}elseif ($fleetid == $aks['fleet_id']){
		$page .= '<table width="710" border="0" cellpadding="0" cellspacing="1">
	<tr height="20">
		<td class="c" colspan="2">Ассоциация флота '.$aks['name'].'</td>
	</tr>
	<form action="?set=verband" method="POST">
	<input type="hidden" name="fleetid" value="'.$fleetid.'" />
	<input type="hidden" name="action" value="changename" />
	<tr>
		<th colspan="2"><input name="groupname" value="'.$aks['name'].'" size=50 /> <br /> <input type="submit" value="Изменить" /></th>
	</tr>
	</form>
	<tr>
		<th>
		<table width="100%" border="0" cellpadding="0" cellspacing="1">
		<tr height="20">
		<td class="c">Приглашенные участники</td>
		<td class="c">Пригласить участников</td>
		</tr>
		<tr>
		<th width="50%">
			<select size="5">';

	$query = doquery("SELECT game_users.username FROM game_users, game_aks_user WHERE game_users.id = game_aks_user.user_id AND game_aks_user.aks_id = ".$fleet['fleet_group']."", '');
	if (mysql_num_rows($query) == 0) $page .= "<option>нет участников</option>";
	while ($us = mysql_fetch_assoc($query)) {
					$page .= "<option>".$us['username']."</option>";
	}

				$page .= '</select>
		</th>
	<form action="?set=verband" method="POST">
	<input type="hidden" name="fleetid" value="'.$fleetid.'" />
		<input type="hidden" name="action" value="adduser" />
		<td><input name="addtogroup" size="60" /> <br /><input type="submit" value="OK" /></td>
		</form>
				</tr>
		</table>
		</th>
	</tr><tr></tr>
	</table>';
}

} else {
}

display($page, "Совместная атака");

?>