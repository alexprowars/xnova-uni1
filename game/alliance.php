<?php

if(!defined("INSIDE")) die("attemp hacking");

$sort1 = intval($_GET['sort1']);
if (empty($sort1))  { unset($sort1); }

$sort2 = intval($_GET['sort2']);
if (empty($sort2))  { unset($sort2); }

$d = $_GET['d'];
if ((!is_numeric($d)) || (empty($d) && $d != 0))
	unset($d);

$rank = intval($_GET['rank']);
if (empty($rank))
	unset($rank);

$kick = intval($_GET['kick']);
if (empty($kick))
	unset($kick);

$id = intval($_GET['id']);
if (empty($id))
	unset($id);

if ($user['id'] == 4075)
	message("Доступ в demo режиме ограничен.", "Ошибка");

include($ugamela_root_path . 'includes/functions/MessageForm.'.$phpEx);

$mode     = $_GET['mode'];
$yes      = $_GET['yes'];
$edit     = $_GET['edit'];
$allyid   = intval($_GET['allyid']);
$show     = intval($_GET['show']);
$sort     = intval($_GET['sort']);
$sendmail = intval($_GET['sendmail']);
$t        = $_GET['t'];
$a        = intval($_GET['a']);
$tag      = mysql_escape_string($_GET['tag']);

includeLang('alliance');

function BBtoText ($txt) {

	$patterns[] = "#\[fc\]([a-z0-9\#]+)\[/fc\](.*?)\[/f\]#Ssi";
	$replacements[] = '<font color="\1">\2</font>';
	$patterns[] = "#\[color=([a-z0-9\#]+)\](.*?)\[/color\]#Ssi";
	$replacements[] = '<font color="\1">\2</font>';
	$patterns[] = '#\[img\](.*?)\[/img\]#Smi';
	$replacements[] = '<img src="\1" alt="\1" style="border:0px;" />';
	$patterns[] = "#\[fc\]([a-z0-9\#\ \[\]]+)\[/fc\]#Ssi";
	$replacements[] = '<font color="\1">';
	$patterns[] = "#\[color=([a-z0-9\#\ \[\]]+)\]#Ssi";
	$replacements[] = '<font color="\1">';
	$patterns[] = "#\[fs\]([a-z0-9\#\ \[\]]+)\[/fs\]#Ssi";
	$replacements[] = '<font size="\1">';
	$patterns[] = "#\[size=([a-z0-9\#\ \[\]]+)\](.*?)\[/size\]#Ssi";
	$replacements[] = '<font size="\1">\2</font>';
	$patterns[] = "#\[size=([a-z0-9\#\ \[\]]+)\]#Ssi";
	$replacements[] = '<font size="\1">';
	$patterns[] = "#\[/size\]#Ssi";
	$replacements[] = '</font>';
	$patterns[] = "#\[/color\]#Ssi";
	$replacements[] = '</font>';
	$patterns[] = "#\[/f\]#Ssi";
	$replacements[] = '</font>';
	$patterns[] = "#\[kursiv\]#Ssi";
	$replacements[] = '<i>';
	$patterns[] = "#\[/kursiv\]#Ssi";
	$replacements[] = '</i>';
	$patterns[] = "#\[i\]#Ssi";
	$replacements[] = '<i>';
	$patterns[] = "#\[/i\]#Ssi";
	$replacements[] = '</i>';
	$patterns[] = "#\[u\]#Ssi";
	$replacements[] = '<u>';
	$patterns[] = "#\[/u\]#Ssi";
	$replacements[] = '</u>';
	$patterns[] = "#\[a=(.*?)\]#Ssi";
	$replacements[] = '<a href="\1" target="_blank">';
	$patterns[] = "#\[/a\]#Ssi";
	$replacements[] = '</a>';
	$patterns[] = "#\[fa\]([a-z0-9\#\ \[\]]+)\[/fa\]#Ssi";
	$replacements[] = '<font face="\1">';
	$patterns[] = "#\[face=([a-z0-9\#\ \[\]]+)\]#Ssi";
	$replacements[] = '<font face="\1">';
	$patterns[] = "#\[/face\]#Ssi";
	$replacements[] = '</font>';
	$patterns[] = "#\[hr\]#Ssi";
	$replacements[] = '<hr>';
	$patterns[] = "#\[liste\]#Ssi";
	$replacements[] = '<ol type="I"><li>';
	$patterns[] = "#\[liste2\]#Ssi";
	$replacements[] = '</li><li>';
	$patterns[] = "#\[/liste\]#Ssi";
	$replacements[] = '</li></ol>';
	$patterns[] = "#\[left\]#Ssi";
	$replacements[] = '<p align="left">';
	$patterns[] = "#\[/left\]#Ssi";
	$replacements[] = '</p>';
	$patterns[] = "#\[center\]#Ssi";
	$replacements[] = '<p align="center">';
	$patterns[] = "#\[/center\]#Ssi";
	$replacements[] = '</p>';
	$patterns[] = "#\[right\]#Ssi";
	$replacements[] = '<p align="right">';
	$patterns[] = "#\[/right\]#Ssi";
	$replacements[] = '</p>';
	$patterns[] = "#\[p\]#Ssi";
	$replacements[] = '<p>';
	$patterns[] = "#\[/p\]#Ssi";
	$replacements[] = '</p>';
	$patterns[] = "#\[strike\]#Ssi";
	$replacements[] = '<strike>';
	$patterns[] = "#\[/strike\]#Ssi";
	$replacements[] = '</strike>';
	$patterns[] = "#\[s\]#Ssi";
	$replacements[] = '<strike>';
	$patterns[] = "#\[/s\]#Ssi";
	$replacements[] = '</strike>';
	$txt = preg_replace($patterns, $replacements, $txt);

	return $txt;
}


if ($mode == 'ainfo') {
	$a = intval($_GET['a']);
	$tag = mysql_escape_string(addslashes($_GET['tag']));

	$lang['Alliance_information'] = "Информация об альянсе";

	if ($tag != "") {
		$allyrow = doquery("SELECT * FROM {{table}} WHERE ally_tag = '".$tag."'", "alliance", true);
	} elseif ($a != 0) {
		$allyrow = doquery("SELECT * FROM {{table}} WHERE id = '".$a."'", "alliance", true);
	} else {
		message("Указанного альянса не существует в игре!", "Информация об альянсе");
	}

	if (!$allyrow) {
		message("Указанного альянса не существует в игре!", "Информация об альянсе");
	}
	extract($allyrow);

	if ($ally_image != "") {
		$ally_image = "<tr><th colspan=2><img src=\"{$ally_image}\"></td></tr>";
	}

	if ($ally_description != "")
		$ally_description = "<tr><th colspan=2 height=100>{$ally_description}</th></tr>";
	else
		$ally_description = "<tr><th colspan=2 height=100>У этого альянса ещё нет описания.</th></tr>";

	if ($ally_web != "") {
		$ally_web = "<tr><th>{$lang['Initial_page']}</th><th><a href=\"{$ally_web}\" target=\"_blank\">{$ally_web}</a></th></tr>";
	}

	$lang['ally_member_scount'] = $ally_members;
	$lang['ally_name'] = $ally_name;
	$lang['ally_tag'] = $ally_tag;
	$lang['ally_description'] = nl2br(BBtoText($ally_description));
	$lang['ally_image'] = $ally_image;
	$lang['ally_web'] = $ally_web;

	if ($user['ally_id'] == 0)
		$lang['bewerbung'] = "<tr><th>Вступление</th><th><a href=\"?set=alliance&mode=apply&amp;allyid=".$id."\">Нажмите сюда для подачи заявки</a></th></tr>";
	else
		$lang['bewerbung'] = "";

	$page .= parsetemplate(gettemplate('alliance_ainfo'), $lang);
	display($page, str_replace('%s', $ally_name, $lang['Info_of_Alliance']));
}


if ($user['ally_id'] == 0) {
	if ($mode == 'make' && $user['ally_request'] == 0) { // Создание альянса

		if ($yes == 1 && $_POST) {

			if (!$_POST['atag']) {
				message($lang['have_not_tag'], $lang['make_alliance']);
			}
			if (!$_POST['aname']) {
				message($lang['have_not_name'], $lang['make_alliance']);
			}
			if (!eregi("^[a-zA-Zа-яА-Я0-9_\.\,\-\!\?\*\ ]+$", $_POST['atag'])){
				message("Абревиатура альянса содержит запрещённые символы", $lang['make_alliance']);
			}
			if (!eregi("^[a-zA-Zа-яА-Я0-9_\.\,\-\!\?\*\ ]+$", $_POST['aname'])){
				message("Название альянса содержит запрещённые символы", $lang['make_alliance']);
			}

			$tagquery = doquery("SELECT * FROM {{table}} WHERE ally_tag='".addslashes($_POST['atag'])."'", 'alliance', true);

			if ($tagquery) {
				message(str_replace('%s', $_POST['atag'], $lang['always_exist']), $lang['make_alliance']);
			}

			doquery("INSERT INTO {{table}} SET `ally_name` = '".addslashes($_POST['aname'])."', `ally_tag`= '".addslashes($_POST['atag'])."' , `ally_owner` = '".$user['id']."', `ally_register_time`=".time() , "alliance");
			$allyquery = doquery("SELECT * FROM {{table}} WHERE ally_tag='".addslashes($_POST['atag'])."'", 'alliance', true);
			doquery("UPDATE {{table}} SET `ally_id`='{$allyquery['id']}', `ally_name`='{$allyquery['ally_name']}', `ally_register_time`='".time()."' WHERE `id` = '{$user['id']}'", "users");

			$page = MessageForm(str_replace('%s', $_POST['atag'], $lang['ally_maked']),

				str_replace('%s', $_POST['atag'], $lang['alliance_has_been_maked']) . "<br><br>", "", $lang['Ok']);
		} else {
			$page .= parsetemplate(gettemplate('alliance_make'), $lang);
		}

		display($page, $lang['make_alliance']);
	}

	if ($mode == 'search' && $user['ally_request'] == 0) {

		$parse = $lang;
		$lang['searchtext'] = $_POST['searchtext'];
		$page = parsetemplate(gettemplate('alliance_searchform'), $lang);

		if ($_POST) {

			if (!eregi("^[a-zA-Zа-яА-Я0-9_\.\,\-\!\?\*\ ]+$", $_POST['searchtext'])){
				message("Строка поиска содержит запрещённые символы", $lang['make_alliance']);
			}

			$search = doquery("SELECT * FROM {{table}} WHERE ally_name LIKE '%{$_POST['searchtext']}%' or ally_tag LIKE '%{$_POST['searchtext']}%' LIMIT 30", "alliance");

			if (mysql_num_rows($search) != 0) {
				$template = gettemplate('alliance_searchresult_row');

				while ($s = mysql_fetch_array($search)) {
					$entry = array();
					$entry['ally_tag'] = "[<a href=\"?set=alliance&mode=apply&allyid={$s['id']}\">{$s['ally_tag']}</a>]";
					$entry['ally_name'] = $s['ally_name'];
					$entry['ally_members'] = $s['ally_members'];

					$parse['result'] .= parsetemplate($template, $entry);
				}

				$page .= parsetemplate(gettemplate('alliance_searchresult_table'), $parse);
			}
		}

		display($page, $lang['search_alliance']);
	}

	if ($mode == 'apply' && $user['ally_request'] == 0) {
		if (!is_numeric($_GET['allyid']) || !$_GET['allyid'] || $user['ally_request'] != 0 || $user['ally_id'] != 0) {
			message($lang['it_is_not_posible_to_apply'], $lang['it_is_not_posible_to_apply']);
		}

		$allyrow = doquery("SELECT ally_tag,ally_request FROM {{table}} WHERE id='" . intval($_GET['allyid']) . "'", "alliance", true);

		if (!$allyrow) {
			message($lang['it_is_not_posible_to_apply'], $lang['it_is_not_posible_to_apply']);
		}

		extract($allyrow);

		if ($_POST['further'] == $lang['Send']) {
			doquery("UPDATE {{table}} SET `ally_request`='" . intval($allyid) . "', ally_request_text='" . mysql_escape_string(strip_tags($_POST['text'])) . "', ally_register_time='" . time() . "' WHERE `id`='" . $user['id'] . "'", "users");

			message($lang['apply_registered'], $lang['your_apply']);

		} else {
			$text_apply = ($ally_request) ? $ally_request : $lang['There_is_no_a_text_apply'];
		}

		$parse = $lang;
		$parse['allyid'] = intval($_GET['allyid']);
		$parse['chars_count'] = strlen($text_apply);
		$parse['text_apply'] = $text_apply;
		$parse['Write_to_alliance'] = str_replace('%s', $ally_tag, $lang['Write_to_alliance']);

		$page = parsetemplate(gettemplate('alliance_applyform'), $parse);

		display($page, $lang['Write_to_alliance']);
	}

	if ($user['ally_request'] != 0) {

		$allyquery = doquery("SELECT ally_tag FROM {{table}} WHERE id='" . intval($user['ally_request']) . "' ORDER BY `id`", "alliance", true);

		extract($allyquery);
		if ($_POST['bcancel']) {
			doquery("UPDATE {{table}} SET `ally_request`=0 WHERE `id`=" . $user['id'], "users");

			$lang['request_text'] = str_replace('%s', $ally_tag, $lang['Canceled_a_request_text']);
			$lang['button_text'] = $lang['Ok'];
			$page = parsetemplate(gettemplate('alliance_apply_waitform'), $lang);
		} else {
			$lang['request_text'] = str_replace('%s', $ally_tag, $lang['Waiting_a_request_text']);
			$lang['button_text'] = $lang['Delete_apply'];
			$page = parsetemplate(gettemplate('alliance_apply_waitform'), $lang);
		}

		display($page, "Deine Anfrage");
	} else {

		$page .= parsetemplate(gettemplate('alliance_defaultmenu'), $lang);
		display($page, $lang['alliance']);
	}
}

//---------------------------------------------------------------------------------------------------------------------------------------------------

elseif ($user['ally_id'] != 0 && $user['ally_request'] == 0) {

	$ally = doquery("SELECT * FROM {{table}} WHERE id='{$user['ally_id']}'", "alliance", true);

	$ally_ranks = unserialize($ally['ally_ranks']);

	$allianz_raenge = unserialize($ally['ally_ranks']);

	if ($allianz_raenge[$user['ally_rank_id']-1]['onlinestatus'] == 1 || $ally['ally_owner'] == $user['id']) {
		$user_can_watch_memberlist_status = true;
	} else
		$user_can_watch_memberlist_status = false;

	if ($allianz_raenge[$user['ally_rank_id']-1]['memberlist'] == 1 || $ally['ally_owner'] == $user['id']) {
		$user_can_watch_memberlist = true;
	} else
		$user_can_watch_memberlist = false;

	if ($allianz_raenge[$user['ally_rank_id']-1]['mails'] == 1 || $ally['ally_owner'] == $user['id']) {
		$user_can_send_mails = true;
	} else
		$user_can_send_mails = false;

	if ($allianz_raenge[$user['ally_rank_id']-1]['kick'] == 1 || $ally['ally_owner'] == $user['id']) {
		$user_can_kick = true;
	} else
		$user_can_kick = false;

	if ($allianz_raenge[$user['ally_rank_id']-1]['rechtehand'] == 1 || $ally['ally_owner'] == $user['id'])
		$user_can_edit_rights = true;
	else
		$user_can_edit_rights = false;

	if ($allianz_raenge[$user['ally_rank_id']-1]['delete'] == 1 || $ally['ally_owner'] == $user['id'])
		$user_can_exit_alliance = true;
	else
		$user_can_exit_alliance = false;

	if ($allianz_raenge[$user['ally_rank_id']-1]['bewerbungen'] == 1 || $ally['ally_owner'] == $user['id'])
		$user_bewerbungen_einsehen = true;
	else
		$user_bewerbungen_einsehen = false;

	if ($allianz_raenge[$user['ally_rank_id']-1]['bewerbungenbearbeiten'] == 1 || $ally['ally_owner'] == $user['id'])
		$user_bewerbungen_bearbeiten = true;
	else
		$user_bewerbungen_bearbeiten = false;

	if ($allianz_raenge[$user['ally_rank_id']-1]['administrieren'] == 1 || $ally['ally_owner'] == $user['id'])
		$user_admin = true;
	else
		$user_admin = false;

	if ($allianz_raenge[$user['ally_rank_id']-1]['onlinestatus'] == 1 || $ally['ally_owner'] == $user['id'])
		$user_onlinestatus = true;
	else
		$user_onlinestatus = false;

	if ($allianz_raenge[$user['ally_rank_id']-1]['diplomacy'] == 1 || $ally['ally_owner'] == $user['id'])
		$user_diplomacy = true;
	else
		$user_diplomacy = false;

	if (!$ally) {
		doquery("UPDATE {{table}} SET `ally_id`=0 WHERE `id`='{$user['id']}'", "users");
		message($lang['ally_notexist'], $lang['your_alliance'], '?set=alliance');
	}

	if ($mode == 'exit') {
		if ($ally['ally_owner'] == $user['id']) {
			message($lang['Owner_cant_go_out'], $lang['Alliance']);
		}

		if ($_GET['yes'] == 1) {
			doquery("UPDATE {{table}} SET `ally_id`=0, `ally_name` = '' WHERE `id`='{$user['id']}'", "users");
			$lang['Go_out_welldone'] = str_replace("%s", $ally_name, $lang['Go_out_welldone']);
			$page = MessageForm($lang['Go_out_welldone'], "<br>", '?set=alliance', $lang['Ok']);

		} else {

			$lang['Want_go_out'] = str_replace("%s", $ally_name, $lang['Want_go_out']);
			$page = MessageForm($lang['Want_go_out'], "<br>", "?set=alliance&mode=exit&yes=1", "Ja");
		}
		display($page);
	}

	if ($mode == 'diplo') {

		if ($ally['ally_owner'] != $user['id'] && !$user_diplomacy) {
			message($lang['Denied_access'], "Дипломатия");
		}

		$parse['DText'] = "";
		$parse['DMyQuery'] = "";
		$parse['DQuery'] = "";

		$status = array(0 => "Нейтральное", 1 => "Перемирие", 2 => "Мир", 3 => "Война");

		if ($_GET['edit'] == "add") {

			$st = intval($_POST['status']);

			$al = doquery("SELECT id, ally_name FROM {{table}} WHERE id = '".intval($_POST['ally'])."'", "alliance", true);
			if (!$al['id'])
				message("Ошибка ввода параметров", "Дипломатия");

			$ad = doquery("SELECT id FROM {{table}} WHERE (o_al = ".$al['id']." AND t_al = ".$ally['id'].") OR (o_al = ".$ally['id']." AND t_al = ".$al['id'].")", "alliance_diplo");
			if (mysql_num_rows($ad) > 0)
				message("У вас уже есть соглашение с этим альянсом. Разорвите старое соглашения прежде чем создать новое.", "Дипломатия");

			if ($st < 0 || $st > 3) $st = 0;

			doquery("INSERT INTO {{table}} VALUES (NULL, ".$ally['id'].", ".$al['id'].", ".$st.", 0)", "alliance_diplo");

			message("Отношение между вашими альянсами успешно добавлено", "Дипломатия", "?set=alliance&mode=diplo");
		}

		if ($_GET['edit'] == "del") {

			$al = doquery("SELECT o_al FROM {{table}} WHERE id = '".intval($_GET['id'])."' AND (o_al = ".$ally['id']." OR t_al = ".$ally['id'].")", "alliance_diplo", true);

			if (!$al['o_al'])
				message("Ошибка ввода параметров", "Дипломатия");

			doquery("DELETE FROM {{table}} WHERE id = '".intval($_GET['id'])."'", "alliance_diplo");

			message("Отношение между вашими альянсами расторжено", "Дипломатия", "?set=alliance&mode=diplo");
		}

		if ($_GET['edit'] == "suc") {

			$al = doquery("SELECT o_al FROM {{table}} WHERE id = '".intval($_GET['id'])."' AND t_al = ".$ally['id']."", "alliance_diplo", true);

			if (!$al['o_al'])
				message("Ошибка ввода параметров", "Дипломатия");

			doquery("UPDATE {{table}} SET status = '1' WHERE id = '".intval($_GET['id'])."'", "alliance_diplo");

			message("Отношение между вашими альянсами подтверждено", "Дипломатия", "?set=alliance&mode=diplo");
		}

		$dp = doquery("SELECT ad.*, a1.ally_name AS o_name, a2.ally_name AS t_name FROM {{table}}alliance_diplo ad, {{table}}alliance a1, {{table}}alliance a2 WHERE a1.id = ad.o_al AND a2.id = ad.t_al AND (ad.o_al = '".$ally['id']."' OR ad.t_al = '".$ally['id']."')", "");

		while ($diplo = mysql_fetch_assoc($dp)) {

			if ($diplo['status'] == 0) {
				if ($diplo['o_al'] == $user['ally_id']) {
					$parse['DMyQuery'] .= "<tr><th>".$diplo['t_name']."</th><th>".$status[$diplo['type']]."</th><th><a href=\"?set=alliance&mode=diplo&edit=del&id={$diplo['id']}\"><img src=\"{$dpath}pic/abort.gif\" alt=\"Удалить заявку\"></a></th></tr>";
				} else {
					$parse['DQuery'] .= "<tr><th>".$diplo['o_name']."</th><th>".$status[$diplo['type']]."</th><th><a href=\"?set=alliance&mode=diplo&edit=suc&id={$diplo['id']}\"><img src=\"{$dpath}pic/appwiz.gif\" alt=\"Подтвердить\"></a> <a href=\"?set=alliance&mode=diplo&edit=del&id={$diplo['id']}\"><img src=\"{$dpath}pic/abort.gif\" alt=\"Удалить заявку\"></a></th></tr>";
				}
			} else {
				$parse['DText'] .= "<tr><th>".$diplo['o_name']."</th><th>".$diplo['t_name']."</th><th>".$status[$diplo['type']]."</th><th><a href=\"?set=alliance&mode=diplo&edit=del&id={$diplo['id']}\"><img src=\"{$dpath}pic/abort.gif\" alt=\"Удалить\"></a></th></tr>";
			}
		}

		if ($parse['DMyQuery'] == "") $parse['DMyQuery'] = "<tr><th colspan=3>нет</th></tr>";
		if ($parse['DQuery'] == "") $parse['DQuery'] = "<tr><th colspan=3>нет</th></tr>";
		if ($parse['DText'] == "") $parse['DText'] = "<tr><th colspan=4>нет</th></tr>";

		$parse['a_list'] = "<option value=\"0\">список альянсов";
		$ally_list = doquery("SELECT id, ally_name, ally_tag FROM {{table}} WHERE id != ".$user['ally_id']." AND ally_members > 1", "alliance");
		while( $a_list = mysql_fetch_assoc($ally_list) ) {

			$parse['a_list'] .= "<option value=\"".$a_list['id']."\">".$a_list['ally_name']." [".$a_list['ally_tag']."]";

		}

		$page .= parsetemplate(gettemplate('alliance_diplo'), $parse);

		display($page, "Дипломатия");
	}

	if ($mode == 'memberslist') {


		$allianz_raenge = unserialize($ally['ally_ranks']);

		if ($ally['ally_owner'] != $user['id'] && !$user_can_watch_memberlist) {
			message($lang['Denied_access'], $lang['Members_list']);
		}

		$sort = "";

		if ($sort2) {
			$sort1 = intval($_GET['sort1']);
			$sort2 = intval($_GET['sort2']);

			if ($sort1 == 1) {
				$sort = " ORDER BY u.`username`";
			} elseif ($sort1 == 2) {
				$sort = " ORDER BY u.`ally_rank_id`";
			} elseif ($sort1 == 3) {
				$sort = " ORDER BY s.`total_points`";
			} elseif ($sort1 == 4) {
				$sort = " ORDER BY u.`ally_register_time`";
			} elseif ($sort1 == 5) {
				$sort = " ORDER BY u.`onlinetime`";
			} else {
				$sort = " ORDER BY u.`id`";
			}

			if ($sort2 == 1) {
				$sort .= " DESC;";
			} elseif ($sort2 == 2) {
				$sort .= " ASC;";
			}
		}
		$listuser = doquery("SELECT u.id, u.username, u.galaxy, u.system, u.planet, u.onlinetime, u.ally_rank_id, u.ally_register_time, s.total_points FROM game_users u LEFT JOIN game_statpoints s ON s.id_owner = u.id AND stat_type = 1 WHERE u.ally_id = '".$user['ally_id']."'".$sort."", '');

		$i = 0;

		$template = gettemplate('alliance_memberslist_row');
		$page_list = '';
		while ($u = mysql_fetch_array($listuser)) {

			$i++;
			$u['i'] = $i;

			if ($u["onlinetime"] + 60 * 10 >= time() && $user_can_watch_memberlist_status) {
				$u["onlinetime"] = "lime>{$lang['On']}<";
			} elseif ($u["onlinetime"] + 60 * 20 >= time() && $user_can_watch_memberlist_status) {
				$u["onlinetime"] = "yellow>{$lang['15_min']}<";
			} elseif ($user_can_watch_memberlist_status) {
				$u["onlinetime"] = "red>{$lang['Off']}<";
			} else $u["onlinetime"] = "orange>-<";
			if ($ally['ally_owner'] == $u['id']) {
				$u["ally_range"] = ($ally['ally_owner_range'] == '')?"Основатель":$ally['ally_owner_range'];
			} elseif (isset($allianz_raenge[$u['ally_rank_id']-1]['name'])) {
				$u["ally_range"] = $allianz_raenge[$u['ally_rank_id']-1]['name'];
			} else {
				$u["ally_range"] = $lang['Novate'];
			}

			$u["dpath"] = $dpath;
			$u['points'] = "" . pretty_number($u['total_points']) . "";

			if ($u['ally_register_time'] > 0)
				$u['ally_register_time'] = date("Y-m-d H:i:s", $u['ally_register_time']);
			else
				$u['ally_register_time'] = "-";

			$page_list .= parsetemplate($template, $u);
		}

		if ($sort2 == 1) {
			$s = 2;
		} elseif ($sort2 == 2) {
			$s = 1;
		} else {
			$s = 1;
		}

		if ($i != $ally['ally_members']) {
			doquery("UPDATE {{table}} SET `ally_members`='{$i}' WHERE `id`='{$ally['id']}'", 'alliance');
		}

		$parse = $lang;
		$parse['i'] = $i;
		$parse['s'] = $s;
		$parse['list'] = $page_list;

		$page .= parsetemplate(gettemplate('alliance_memberslist_table'), $parse);

		display($page, $lang['Members_list']);
	}

	if ($mode == 'circular') {

		$allianz_raenge = unserialize($ally['ally_ranks']);

		if ($user['mnl_alliance'] != 0) doquery("UPDATE {{table}} SET `mnl_alliance` = '0' WHERE `id` = '{$user['id']}'", "users");

		if ($ally['ally_owner'] != $user['id'] && !$user_can_send_mails) {
			message($lang['Denied_access'], $lang['Send_circular_mail']);
		}

		if ($_POST['deletemessages']){
			$DeleteWhat = $_POST['deletemessages'];
			if       ($DeleteWhat == 'deleteall') {
				doquery("DELETE FROM {{table}} WHERE `ally_id` = '". $user['ally_id'] ."';", 'chat');
			} elseif ($DeleteWhat == 'deletemarked') {
				foreach($_POST as $Message => $Answer) {
					if (preg_match("/delmes/i", $Message) && $Answer == 'on') {
						$MessId   = str_replace("delmes", "", $Message);
						doquery("DELETE FROM {{table}} WHERE `id` = '".$MessId."' AND `ally_id` = '". $user['ally_id'] ."';", 'chat');
					}
				}
			} elseif ($DeleteWhat == 'deleteunmarked') {
				foreach($_POST as $Message => $Answer) {
					$CurMess    = preg_match("/showmes/i", $Message);
					$MessId     = str_replace("showmes", "", $Message);
					$Selected   = "delmes".$MessId;
					$IsSelected = $_POST[ $Selected ];
					if (preg_match("/showmes/i", $Message) && !isset($IsSelected)) {
						doquery("DELETE FROM {{table}} WHERE `id` = '".$MessId."' AND `ally_id` = '". $user['ally_id'] ."';", 'chat');
					}
				}
			}
		}

		if ($sendmail == 1 && $_POST['text'] != "") {
			$_POST['r'] = intval($_POST['r']);
			$_POST['text'] = mysql_escape_string(strip_tags($_POST['text']));

			doquery("INSERT INTO {{table}} SET `ally_id`='{$user['ally_id']}', `user`='{$user['username']}', `message`='{$_POST['text']}', `timestamp`='" . time() . "', `dostup`='{$_POST['r']}'", "chat");

			if ($_POST['r'] == 0)
				doquery("UPDATE {{table}} SET `mnl_alliance` = `mnl_alliance` + '1' WHERE `ally_id` = '{$user['ally_id']}'", "users");
			else
				doquery("UPDATE {{table}} SET `mnl_alliance` = `mnl_alliance` + '1' WHERE `ally_id` = '{$user['ally_id']}' AND `ally_rank_id` = '{$_POST['r']}'", "users");
				
			header("Location: ?set=alliance&mode=circular");
			die();
		}

		$page .= "<script language=\"JavaScript\" src=\"/scripts/smiles.js\"></script><br><form action=\"?set=alliance&mode=circular\" method=\"post\"><table width=700>";


		if ($ally['ally_owner'] == $user['id'])
			$Ally_count = doquery("SELECT `id` FROM {{table}} WHERE `ally_id` = '{$user['ally_id']}'", "chat");
		else
			$Ally_count = doquery("SELECT `id` FROM {{table}} WHERE `ally_id` = '{$user['ally_id']}' AND (`dostup` = '0' OR `dostup` = '{$user['ally_rank_id']}')", "chat");
	
		$news_count = mysql_num_rows($Ally_count);

		if ($news_count > 0){

			$np=15; // Число новостей на странице
			$numo = $news_count;
			$pages_count = @ceil($numo/$np);
			if (is_numeric($_GET['p'])) {
				$p = $_GET['p'];
				if ($p > $pages_count) $p=1;
				if ($p == "" or $p == "0") { $p="1"; }
				elseif ($p != "1") { $min=$np; }} else $p=1;
				$l1=$p*$np-$np;
				$l2=$np;
				$pages = "";
				for($i=1; $i<=$pages_count; $i++){
				if ($p != $i) $pages .= " <a href=?set=alliance&mode=circular&p=".$i.">[".$i."]</a>";
				else $pages .= " <b>[$i]</b>";
			}

			if ($ally['ally_owner'] == $user['id'])
				$Ally_m = doquery("SELECT * FROM {{table}} WHERE `ally_id` = '{$user['ally_id']}' ORDER BY `id` DESC limit ".$l1.",".$l2."", "chat");
			else
				$Ally_m = doquery("SELECT * FROM {{table}} WHERE `ally_id` = '{$user['ally_id']}' AND (`dostup` = '0' OR `dostup` = '{$user['ally_rank_id']}' OR user = '".$user['username']."') ORDER BY `id` DESC limit ".$l1.",".$l2."", "chat");
			while($Ally_messages = mysql_fetch_array($Ally_m)){
	
				$page .= "\n<tr>";
				if ($ally['ally_owner'] == $user['id']){
					$page .= "<input name=\"showmes". $Ally_messages['id'] . "\" type=\"hidden\" value=\"1\">";
					$page .= "<th><input name=\"delmes". $Ally_messages['id'] ."\" type=\"checkbox\"></th>";
				}
				$page .= "<th width=15%>". date("m-d H:i:s", $Ally_messages['timestamp']) ."</th>";
				$page .= "<th>". stripslashes( $Ally_messages['user'] ) ."</th>";
				if ($Ally_messages['dostup'] == 0)
					$page .= "<th>Всем ";
				else
					$page .= "<th>Рангу: ". stripslashes( $allianz_raenge[$Ally_messages[dostup]-1]['name'] ) ." ";
				$page .= "</th>";
				$page .= "</tr><tr>";
				$page .= "<td class=\"b\"> </td>";
				$page .= "<td colspan=\"3\" class=\"b\"><script>Text('". eregi_replace("[\n\r]", "", stripslashes( nl2br ($Ally_messages['message']) ) ) ."');</script></td>";
				$page .= "</tr>";
	
			}

		}else{

			$page .= "\n<tr>";
			$page .= "<td class=\"b\"> </td>";
			$page .= "<td colspan=\"3\" class=\"b\">В альянсе нет сообщений.</td>";
			$page .= "</tr>";

		}

		$page .= "<tr>";
		$page .= "<th colspan=\"4\">";
		$page .= "Страницы:".$pages;
		$page .= "</th>";
		$page .= "</tr>";

	if ($ally['ally_owner'] == $user['id']){
		$page .= "<tr>";
		$page .= "<th colspan=\"4\">";
		$page .= "<select onchange=\"document.getElementById('deletemessages2').options[this.selectedIndex].selected='true'\" id=\"deletemessages\" name=\"deletemessages\">";
		$page .= "<option value=\"deletemarked\">Удалить выделенные</option>";
		$page .= "<option value=\"deleteunmarked\">Удалить не выделенные</option>";
		$page .= "<option value=\"deleteall\">Удалить все</option>";
		$page .= "</select>";
		$page .= "<input value=\"Удалить\" type=\"submit\">";
		$page .= "</th>";
		$page .= "</tr>";
	}

		$page .= "</form></table>";

		$lang['r_list'] = "<option value=\"0\">{$lang['All_players']}</option>";
		if ($allianz_raenge) {
			foreach($allianz_raenge as $id => $array) {
				$lang['r_list'] .= "<option value=\"" . ($id + 1) . "\">" . $array['name'] . "</option>";
			}
		}

		$page .= parsetemplate(gettemplate('alliance_circular'), $lang);
		display($page, $lang['Send_circular_mail'], false);
	}

	if ($mode == 'admin' && $edit == 'rights') {
		$allianz_raenge = unserialize($ally['ally_ranks']);

		if ($ally['ally_owner'] != $user['id'] && !$user_can_edit_rights) {
			message($lang['Denied_access'], $lang['Members_list']);
		} elseif (!empty($_POST['newrangname'])) {
			$name = mysql_escape_string(strip_tags($_POST['newrangname']));

			$allianz_raenge[] = array('name' => $name,
				'mails' => 0,
				'delete' => 0,
				'kick' => 0,
				'bewerbungen' => 0,
				'administrieren' => 0,
				'bewerbungenbearbeiten' => 0,
				'memberlist' => 0,
				'onlinestatus' => 0,
				'rechtehand' => 0,
				'diplomacy' => 0
			);
			$ally_ranks = $allianz_raenge;
			$ranks = serialize($allianz_raenge);

			doquery("UPDATE {{table}} SET `ally_ranks`='" . $ranks . "' WHERE `id`=" . $ally['id'], "alliance");

		} elseif ($_POST['id'] != '' && is_array($_POST['id'])) {
			$ally_ranks_new = array();

			foreach ($_POST['id'] as $id) {
				$name = $allianz_raenge[$id]['name'];

				$ally_ranks_new[$id]['name'] = $name;

				if (isset($_POST['u' . $id . 'r0'])) {
					$ally_ranks_new[$id]['delete'] = 1;
				} else {
					$ally_ranks_new[$id]['delete'] = 0;
				}

				if (isset($_POST['u' . $id . 'r1']) && $ally['ally_owner'] == $user['id']) {
					$ally_ranks_new[$id]['kick'] = 1;
				} else {
					$ally_ranks_new[$id]['kick'] = 0;
				}

				if (isset($_POST['u' . $id . 'r2'])) {
					$ally_ranks_new[$id]['bewerbungen'] = 1;
				} else {
					$ally_ranks_new[$id]['bewerbungen'] = 0;
				}

				if (isset($_POST['u' . $id . 'r3'])) {
					$ally_ranks_new[$id]['memberlist'] = 1;
				} else {
					$ally_ranks_new[$id]['memberlist'] = 0;
				}

				if (isset($_POST['u' . $id . 'r4'])) {
					$ally_ranks_new[$id]['bewerbungenbearbeiten'] = 1;
				} else {
					$ally_ranks_new[$id]['bewerbungenbearbeiten'] = 0;
				}

				if (isset($_POST['u' . $id . 'r5'])) {
					$ally_ranks_new[$id]['administrieren'] = 1;
				} else {
					$ally_ranks_new[$id]['administrieren'] = 0;
				}

				if (isset($_POST['u' . $id . 'r6'])) {
					$ally_ranks_new[$id]['onlinestatus'] = 1;
				} else {
					$ally_ranks_new[$id]['onlinestatus'] = 0;
				}

				if (isset($_POST['u' . $id . 'r7'])) {
					$ally_ranks_new[$id]['mails'] = 1;
				} else {
					$ally_ranks_new[$id]['mails'] = 0;
				}

				if (isset($_POST['u' . $id . 'r8'])) {
					$ally_ranks_new[$id]['rechtehand'] = 1;
				} else {
					$ally_ranks_new[$id]['rechtehand'] = 0;
				}

				if (isset($_POST['u' . $id . 'r9'])) {
					$ally_ranks_new[$id]['diplomacy'] = 1;
				} else {
					$ally_ranks_new[$id]['diplomacy'] = 0;
				}
			}
			$ally_ranks = $ally_ranks_new;
			$ranks = serialize($ally_ranks_new);

			doquery("UPDATE {{table}} SET `ally_ranks`='" . $ranks . "' WHERE `id`=" . $ally['id'], "alliance");
		}

		elseif (isset($d) && isset($ally_ranks[$d])) {
			unset($ally_ranks[$d]);
			$ally['ally_rank'] = serialize($ally_ranks);

			doquery("UPDATE {{table}} SET `ally_ranks`='{$ally['ally_rank']}' WHERE `id`={$ally['id']}", "alliance");
		}

		if (count($ally_ranks) == 0 || $ally_ranks == '') {
			$list = "<th>{$lang['There_is_not_range']}</th>";
		} else {
			$list = parsetemplate(gettemplate('alliance_admin_laws_head'), $lang);
			$template = gettemplate('alliance_admin_laws_row');

			$i = 0;

			foreach($ally_ranks as $a => $b) {
				if ($ally['ally_owner'] == $user['id']) {
					$lang['id'] = $a;
					$lang['delete'] = "<a href=\"?set=alliance&mode=admin&edit=rights&d={$a}\"><img src=\"{$dpath}pic/abort.gif\" alt=\"{$lang['Удалить ранг']}\" border=0></a>";
					$lang['r0'] = $b['name'];
					$lang['a'] = $a;
					$lang['r1'] = "<input type=checkbox name=\"u{$a}r0\"" . (($b['delete'] == 1)?' checked="checked"':'') . ">"; //{$b[1]}
					$lang['r2'] = "<input type=checkbox name=\"u{$a}r1\"" . (($b['kick'] == 1)?' checked="checked"':'') . ">";
					$lang['r3'] = "<input type=checkbox name=\"u{$a}r2\"" . (($b['bewerbungen'] == 1)?' checked="checked"':'') . ">";
					$lang['r4'] = "<input type=checkbox name=\"u{$a}r3\"" . (($b['memberlist'] == 1)?' checked="checked"':'') . ">";
					$lang['r5'] = "<input type=checkbox name=\"u{$a}r4\"" . (($b['bewerbungenbearbeiten'] == 1)?' checked="checked"':'') . ">";
					$lang['r6'] = "<input type=checkbox name=\"u{$a}r5\"" . (($b['administrieren'] == 1)?' checked="checked"':'') . ">";
					$lang['r7'] = "<input type=checkbox name=\"u{$a}r6\"" . (($b['onlinestatus'] == 1)?' checked="checked"':'') . ">";
					$lang['r8'] = "<input type=checkbox name=\"u{$a}r7\"" . (($b['mails'] == 1)?' checked="checked"':'') . ">";
					$lang['r9'] = "<input type=checkbox name=\"u{$a}r8\"" . (($b['rechtehand'] == 1)?' checked="checked"':'') . ">";
					$lang['r10'] = "<input type=checkbox name=\"u{$a}r9\"" . (($b['diplomacy'] == 1)?' checked="checked"':'') . ">";

					$list .= parsetemplate($template, $lang);
				} else {
					$lang['id'] = $a;
					$lang['r0'] = $b['name'];
					$lang['delete'] = "<a href=\"?set=alliance&mode=admin&edit=rights&d={$a}\"><img src=\"{$dpath}pic/abort.gif\" alt=\"{$lang['Удалить ранг']}\" border=0></a>";
					$lang['a'] = $a;
					$lang['r1'] = "<b>-</b>";
					$lang['r2'] = "<input type=checkbox name=\"u{$a}r1\"" . (($b['kick'] == 1)?' checked="checked"':'') . ">";
					$lang['r3'] = "<input type=checkbox name=\"u{$a}r2\"" . (($b['bewerbungen'] == 1)?' checked="checked"':'') . ">";
					$lang['r4'] = "<input type=checkbox name=\"u{$a}r3\"" . (($b['memberlist'] == 1)?' checked="checked"':'') . ">";
					$lang['r5'] = "<input type=checkbox name=\"u{$a}r4\"" . (($b['bewerbungenbearbeiten'] == 1)?' checked="checked"':'') . ">";
					$lang['r6'] = "<input type=checkbox name=\"u{$a}r5\"" . (($b['administrieren'] == 1)?' checked="checked"':'') . ">";
					$lang['r7'] = "<input type=checkbox name=\"u{$a}r6\"" . (($b['onlinestatus'] == 1)?' checked="checked"':'') . ">";
					$lang['r8'] = "<input type=checkbox name=\"u{$a}r7\"" . (($b['mails'] == 1)?' checked="checked"':'') . ">";
					$lang['r9'] = "<input type=checkbox name=\"u{$a}r8\"" . (($b['rechtehand'] == 1)?' checked="checked"':'') . ">";
					$lang['r10'] = "<input type=checkbox name=\"u{$a}r9\"" . (($b['diplomacy'] == 1)?' checked="checked"':'') . ">";

					$list .= parsetemplate($template, $lang);
				}
			}

			if (count($ally_ranks) != 0) {
				$list .= parsetemplate(gettemplate('alliance_admin_laws_feet'), $lang);
			}
		}

		$lang['list'] = $list;
		$lang['dpath'] = $dpath;
		$page .= parsetemplate(gettemplate('alliance_admin_laws'), $lang);

		display($page, $lang['Law_settings']);
	}

	if ($mode == 'admin' && $edit == 'ally') {

		if ($ally['ally_owner'] != $user['id'] && !$user_admin) {
			message($lang['Denied_access'], "Меню управления альянсом");
		}

		if ($t != 1 && $t != 2 && $t != 3) {
			$t = 1;
		}
		if ($_POST) {
			if (!get_magic_quotes_gpc()) {
				$_POST['owner_range'] = stripslashes($_POST['owner_range']);
				$_POST['web'] = stripslashes($_POST['web']);
				$_POST['image'] = stripslashes($_POST['image']);
				$_POST['text'] = stripslashes($_POST['text']);
			}
		}

		if ($_POST['options']) {
			$ally['ally_owner_range'] = mysql_escape_string(htmlspecialchars(strip_tags($_POST['owner_range'])));

			$ally['ally_web'] = mysql_escape_string(htmlspecialchars(strip_tags($_POST['web'])));

			$ally['ally_image'] = mysql_escape_string(htmlspecialchars(strip_tags($_POST['image'])));

			$ally['ally_request_notallow'] = intval($_POST['request_notallow']);

			if ($ally['ally_request_notallow'] != 0 && $ally['ally_request_notallow'] != 1) {
				message("W&auml;hle bei \"Bewerbungen\" eine Option aus dem Formular!", "Ошибка");
				exit;
			}

			doquery("UPDATE {{table}} SET
			`ally_owner_range`='{$ally['ally_owner_range']}',
			`ally_image`='{$ally['ally_image']}',
			`ally_web`='{$ally['ally_web']}',
			`ally_request_notallow`='{$ally['ally_request_notallow']}'
			WHERE `id`='{$ally['id']}'", "alliance");

		} elseif ($_POST['t']) {
			if ($t == 3) {
				$ally['ally_request'] = mysql_escape_string(strip_tags($_POST['text']));

				doquery("UPDATE {{table}} SET
				`ally_request`='{$ally['ally_request']}'
				WHERE `id`='{$ally['id']}'", "alliance");

			} elseif ($t == 2) {
				$ally['ally_text'] = mysql_escape_string(strip_tags($_POST['text']));
				doquery("UPDATE {{table}} SET
				`ally_text`='{$ally['ally_text']}'
				WHERE `id`='{$ally['id']}'", "alliance");

			} else {
				$ally['ally_description'] = mysql_escape_string(strip_tags($_POST['text']));

				doquery("UPDATE {{table}} SET
				`ally_description`='" . $ally['ally_description'] . "'
				WHERE `id`='{$ally['id']}'", "alliance");

			}
		}

		$lang['dpath'] = $dpath;

		if ($t == 3) {
			$lang['text'] = $ally['ally_request'];
			$lang['Show_of_request_text'] = "Текст заявок альянса";
		} elseif ($t == 2) {
			$lang['text'] = $ally['ally_text'];
			$lang['Show_of_request_text'] = "Внутренний текст альянса";
		} else {
			$lang['text'] = $ally['ally_description'];
		}

		$lang['t'] = $t;

		$lang['ally_web'] = $ally['ally_web'];
		$lang['ally_image'] = $ally['ally_image'];
		$lang['ally_request_notallow_0'] = (($ally['ally_request_notallow'] == 1) ? ' SELECTED' : '');
		$lang['ally_request_notallow_1'] = (($ally['ally_request_notallow'] == 0) ? ' SELECTED' : '');
		$lang['ally_owner_range'] = $ally['ally_owner_range'];
		$lang['Transfer_alliance'] = MessageForm("Покинуть / Передать альянс", "", "?set=alliance&mode=admin&edit=give", $lang['Continue']);
		$lang['Disolve_alliance'] = MessageForm("Расформировать альянс", "", "?set=alliance&mode=admin&edit=exit", $lang['Continue']);

		$page .= parsetemplate(gettemplate('alliance_admin'), $lang);
		display($page, $lang['Alliance_admin']);
	}

	if ($mode == 'admin' && $edit == 'members') {

		if ($ally['ally_owner'] != $user['id'] && !$user_can_kick) {
			message($lang['Denied_access'], $lang['Members_list']);
		}

		if (isset($kick)) {
			if ($ally['ally_owner'] != $user['id'] && !$user_can_kick) {
				message($lang['Denied_access'], $lang['Members_list']);
			}

			$u = doquery("SELECT * FROM {{table}} WHERE id='{$kick}' LIMIT 1", 'users', true);

			if ($u['ally_id'] == $ally['id'] && $u['id'] != $ally['ally_owner']) {
				doquery("UPDATE {{table}} SET `ally_id`='0', `ally_name`='' WHERE `id`='{$u['id']}'", 'users');
			}
		} elseif (isset($_POST['newrang']) && $id != 0) {
			$q = doquery("SELECT `id`, `ally_id` FROM {{table}} WHERE id='{$id}' LIMIT 1", 'users', true);

			if ((isset($ally_ranks[$_POST['newrang']-1]) || $_POST['newrang'] == 0) && $q['id'] != $ally['ally_owner'] && $q['ally_id'] == $ally['id']) {
				doquery("UPDATE {{table}} SET `ally_rank_id`='".intval($_POST['newrang'])."' WHERE `id`='".$id."'", 'users');
			}
		}

		$template = gettemplate('alliance_admin_members_row');
		$f_template = gettemplate('alliance_admin_members_function');

		$sort = "";

		if ($sort2) {

			if ($sort1 == 1) {
				$sort = " ORDER BY u.`username`";
			} elseif ($sort1 == 2) {
				$sort = " ORDER BY u.`ally_rank_id`";
			} elseif ($sort1 == 3) {
				$sort = " ORDER BY s.`total_points`";
			} elseif ($sort1 == 4) {
				$sort = " ORDER BY u.`ally_register_time`";
			} elseif ($sort1 == 5) {
				$sort = " ORDER BY u.`onlinetime`";
			} else {
				$sort = " ORDER BY u.`id`";
			}

			if ($sort2 == 1) {
				$sort .= " DESC;";
			} elseif ($sort2 == 2) {
				$sort .= " ASC;";
			}
		}
		$listuser = doquery("SELECT u.id, u.username, u.galaxy, u.system, u.planet, u.onlinetime, u.ally_rank_id, u.ally_register_time, s.total_points FROM game_users u LEFT JOIN game_statpoints s ON s.id_owner = u.id AND stat_type = 1 WHERE u.ally_id = '".$user['ally_id']."'".$sort."", '');

		$i = 0;

		$page_list = '';
		$lang['memberzahl'] = mysql_num_rows($listuser);

		while ($u = mysql_fetch_array($listuser)) {
			$i++;
			$u['i'] = $i;
			$u['points'] = "" . pretty_number($u['total_points']) . "";
			$days = floor(round(time() - $u["onlinetime"]) / 3600 % 24);
			$u["onlinetime"] = str_replace("%s", $days, "%s дней");

			if ($ally['ally_owner'] == $u['id']) {
				$ally_range = ($ally['ally_owner_range'] == '')?$lang['Founder']:$ally['ally_owner_range'];
			} elseif ($u['ally_rank_id'] == 0 || !isset($ally_ranks[$u['ally_rank_id']-1]['name'])) {
				$ally_range = $lang['Novate'];
			} else {
				$ally_range = $ally_ranks[$u['ally_rank_id']-1]['name'];
			}


			if ($ally['ally_owner'] == $u['id'] || $rank == $u['id']) {
				$u["functions"] = '';
			} elseif ($ally_ranks[$user['ally_rank_id']-1][5] == 1 || $ally['ally_owner'] == $user['id']) {
				$f['dpath'] = $dpath;
				$f['Expel_user'] = $lang['Expel_user'];
				$f['Set_range'] = $lang['Set_range'];
				$f['You_are_sure_want_kick_to'] = str_replace("%s", $u['username'], $lang['You_are_sure_want_kick_to']);
				$f['id'] = $u['id'];
				$u["functions"] = parsetemplate($f_template, $f);
			} else {
				$u["functions"] = '';
			}
			$u["dpath"] = $dpath;

			if ($rank != $u['id']) {
				$u['ally_range'] = $ally_range;
			} else {
				$u['ally_range'] = '';
			}
			$u['ally_register_time'] = date("Y-m-d H:i:s", $u['ally_register_time']);
			$page_list .= parsetemplate($template, $u);
			if ($rank == $u['id']) {
				$r['Rank_for'] = str_replace("%s", $u['username'], $lang['Rank_for']);
				$r['options'] .= "<option value=\"0\">{$lang['Novate']}</option>";

				foreach($ally_ranks as $a => $b) {
					$r['options'] .= "<option value=\"" . ($a + 1) . "\"";
					if ($u['ally_rank_id']-1 == $a) {
						$r['options'] .= ' selected=selected';
					}
					$r['options'] .= ">{$b['name']}</option>";
				}
				$r['id'] = $u['id'];
				$r['Save'] = $lang['Save'];
				$page_list .= parsetemplate(gettemplate('alliance_admin_members_row_edit'), $r);
			}
		}

		if ($sort2 == 1) {
			$s = 2;
		} elseif ($sort2 == 2) {
			$s = 1;
		} else {
			$s = 1;
		}

		if ($i != $ally['ally_members']) {
			doquery("UPDATE {{table}} SET `ally_members`='{$i}' WHERE `id`='{$ally['id']}'", 'alliance');
		}

		$lang['memberslist'] = $page_list;
		$lang['s'] = $s;
		$page .= parsetemplate(gettemplate('alliance_admin_members_table'), $lang);

		display($page, $lang['Members_administrate']);

	}


	if ($mode == 'admin' && $edit == 'requests') {
		if ($ally['ally_owner'] != $user['id'] && !$user_bewerbungen_bearbeiten) {
			message($lang['Denied_access'], $lang['Check_the_requests']);
		}

		if ($_POST['action'] == "Принять") {
			if ($_POST['text']){
				$text_ot = mysql_escape_string(strip_tags($_POST['text']));
			}else{
				$text_ot = "нет приветствия";
			}

			doquery("UPDATE {{table}} SET ally_members=ally_members+1 WHERE id='{$ally['id']}'", 'alliance');
			doquery("UPDATE {{table}} SET ally_name='{$ally['ally_name']}', ally_request_text='', ally_request='0', ally_id='{$ally['id']}', ally_rank_id='0', new_message=new_message+1 WHERE id='{$show}'", 'users');
			doquery("INSERT INTO {{table}} SET `message_owner`='{$show}', `message_sender`='{$user['id']}' , `message_time`='" . time() . "', `message_type`='2', `message_from`='{$ally['ally_tag']}', `message_text`='Привет!<br>Альянс <b>" . $ally['ally_name'] . "</b> принял вас в свои ряды!<br>Приветствие:<br>".$text_ot."'", "messages");

		} elseif ($_POST['action'] == "Отклонить" && $_POST['action'] != '') {
			if ($_POST['text']){
				$text_ot = mysql_escape_string(strip_tags($_POST['text']));
			}else{
				$text_ot = "причина не указана";
			}

			doquery("UPDATE {{table}} SET ally_request_text='', ally_request='0', ally_id='0', new_message=new_message+1 WHERE id='{$show}'", 'users');
			doquery("INSERT INTO {{table}} SET `message_owner`='{$show}', `message_sender`='{$user['id']}' , `message_time`='" . time() . "', `message_type`='2', `message_from`='{$ally['ally_tag']}', `message_text`='Привет!<br>Альянс <b>" . $ally['ally_name'] . "</b> отклонил вашу кандидатуру!<br>Причина:<br>".$text_ot."'", "messages");
		}

		$row = gettemplate('alliance_admin_request_row');
		$i = 0;
		$parse = $lang;
		$query = doquery("SELECT id,username,ally_request_text,ally_register_time FROM {{table}} WHERE ally_request='{$ally['id']}'", 'users');
		while ($r = mysql_fetch_array($query)) {

			if (isset($show) && $r['id'] == $show) {
				$s['username'] = $r['username'];
				$s['ally_request_text'] = nl2br($r['ally_request_text']);
				$s['id'] = $r['id'];
			}

			$r['time'] = date("Y-m-d H:i:s", $r['ally_register_time']);
			$parse['list'] .= parsetemplate($row, $r);
			$i++;
		}
		if ($parse['list'] == '') {
			$parse['list'] = '<tr><th colspan=2>Список заявок пуст</th></tr>';
		}
		if (isset($show) && $show != 0 && $parse['list'] != '') {

			$s['Request_from'] = str_replace('%s', $s['username'], $lang['Request_from']);
			$parse['request'] = parsetemplate(gettemplate('alliance_admin_request_form'), $s);
			$parse['request'] = parsetemplate($parse['request'], $lang);
		} else {
			$parse['request'] = '';
		}

		$parse['ally_tag'] = $ally['ally_tag'];
		$parse['Back'] = $lang['Back'];

		$parse['There_is_hanging_request'] = str_replace('%n', $i, $lang['There_is_hanging_request']);
		// $parse['list'] = $lang['Return_to_overview'];
		$page = parsetemplate(gettemplate('alliance_admin_request_table'), $parse);
		display($page, $lang['Check_the_requests']);
	}

	if ($mode == 'admin' && $edit == 'name') {

		$ally_ranks = unserialize($ally['ally_ranks']);

		if ($ally['ally_owner'] != $user['id'] && !$user_admin) {
			message($lang['Denied_access'], $lang['Members_list']);
		}

		if ($_POST['newname']) {
			if (!eregi("^[a-zA-Zа-яА-Я0-9_\.\,\-\!\?\*\ ]+$", $_POST['newname'])){
				message("Название альянса содержит запрещённые символы", $lang['make_alliance']);
			}
			$ally['ally_name'] = addslashes(htmlspecialchars($_POST['newname']));
			doquery("UPDATE {{table}} SET `ally_name` = '". $ally['ally_name'] ."' WHERE `id` = '". $user['ally_id'] ."';", 'alliance');
			doquery("UPDATE {{table}} SET `ally_name` = '". $ally['ally_name'] ."' WHERE `ally_id` = '". $ally['id'] ."';", 'users');
		}

		$parse['question']           = 'Введите новое название альянса';
		$parse['New_name']           = $lang['New_name'];
		$parse['Change']             = $lang['Change'];
		$parse['name']               = 'newname';
		$parse['form']               = 'name';
		$parse['Return_to_overview'] = $lang['Return_to_overview'];
		$page .= parsetemplate(gettemplate('alliance_admin_rename'), $parse);
		display($page, $lang['Alliance_admin']);

	}

	if ($mode == 'admin' && $edit == 'tag') {

		$ally_ranks = unserialize($ally['ally_ranks']);

		if ($ally['ally_owner'] != $user['id'] && !$user_admin) {
			message($lang['Denied_access'], $lang['Members_list']);
		}

		if ($_POST['newtag']) {
			if (!eregi("^[a-zA-Zа-яА-Я0-9_\.\,\-\!\?\*\ ]+$", $_POST['newtag'])){
				message("Абревиатура альянса содержит запрещённые символы", $lang['make_alliance']);
			}
			$ally['ally_tag'] = addslashes(htmlspecialchars($_POST['newtag']));
			doquery("UPDATE {{table}} SET `ally_tag` = '". $ally['ally_tag'] ."' WHERE `id` = '". $user['ally_id'] ."';", 'alliance');
		}

		$parse['question']           = 'Введите новую аббревиатуру альянса';
		$parse['New_name']           = $lang['New_name'];
		$parse['Change']             = $lang['Change'];
		$parse['name']               = 'newtag';
		$parse['form']               = 'tag';
		$parse['Return_to_overview'] = $lang['Return_to_overview'];
		$page .= parsetemplate(gettemplate('alliance_admin_rename'), $parse);
		display($page, $lang['Alliance_admin']);
	}

	if ($mode == 'admin' && $edit == 'exit') {

		$ally_ranks = unserialize($ally['ally_ranks']);

		if ($ally['ally_owner'] != $user['id'] && !$user_can_exit_alliance) {
			message($lang['Denied_access'], $lang['Members_list']);
		}
		doquery("UPDATE {{table}} SET `ally_id`='0', `ally_name`='' WHERE ally_id='{$ally['id']}'", "users");
		doquery("DELETE FROM {{table}} WHERE id='{$ally['id']}'", "alliance");
		doquery("DELETE FROM {{table}} WHERE o_al = '{$ally['id']}' OR t_al = '{$ally['id']}'", "alliance_diplo");
		header('Location: ?set=alliance');
		exit;
	}

	if ($mode == 'admin' && $edit == 'give')
	{

		if (isset($_POST['newleader']) && $ally['ally_owner'] == $user['id'])
		{
			$info = doquery("SELECT id, ally_id FROM {{table}} WHERE id = '".intval($_POST['newleader'])."'", "users", true);

			if (!$info['id'] || $info['ally_id'] != $user['ally_id'])
				message("Операция невозможна.", "Ошибка!", "?set=alliance", 2);

			doquery("UPDATE {{table}} SET `ally_owner` = '".$info['id']."' WHERE `id` = {$user['ally_id']} ", 'alliance');
			doquery("UPDATE {{table}} SET `ally_rank_id` = '0' WHERE `id`='".$info['id']."' ", 'users');
			header('Location: ?set=alliance');
			exit;
		}
		if ($ally['ally_owner'] != $user['id'])
		{
			message("Доступ запрещён.", "Ошибка!", "?set=alliance",2);
		}
		else
		{
			$listuser = doquery("SELECT id, username, ally_rank_id FROM {{table}} WHERE ally_id = '{$user['ally_id']}'", 'users');

			while ($u = mysql_fetch_array($listuser))
			{
				if ($ally['ally_owner'] != $u['id'])
				{
					if ($u['ally_rank_id'] != 0 )
					{
						if ($ally_ranks[$u['ally_rank_id']-1]['rechtehand'] == 1)
						{
							$righthand['righthand'] .= "\n<option value=\"" . $u['id'] . "\"";
							$righthand['righthand'] .= ">";
							$righthand['righthand'] .= "".$u['username'];
							$righthand['righthand'] .= "&nbsp;[".$ally_ranks[$u['ally_rank_id']-1]['name'];
							$righthand['righthand'] .= "]&nbsp;&nbsp;</option>";
						}
					}
				}
				$righthand["dpath"] = $dpath;
			}

			$page_list .= parsetemplate(gettemplate('alliance_admin_transfer_row'), $righthand);;
			$parse['s'] = $s;
			$parse['list'] = $page_list;

			$page .= parsetemplate(gettemplate('alliance_admin_transfer'), $parse);

			display($page, "Передача альянса");
		}
	}
	{

		if ($ally['ally_owner'] != $user['id']) {
			$ally_ranks = unserialize($ally['ally_ranks']);
		}

		if ($ally['ally_ranks'] != '') {
			$ally['ally_ranks'] = "<tr><td colspan=2><img src=\"{$ally['ally_image']}\"></td></tr>";
		}

		if ($ally['ally_owner'] == $user['id']) {
			$range = ($ally['ally_owner_range'] != '')?$lang['Founder']:$ally['ally_owner_range'];
		} elseif ($user['ally_rank_id'] != 0 && isset($ally_ranks[$user['ally_rank_id']-1]['name'])) {
			$range = $ally_ranks[$user['ally_rank_id']-1]['name'];
		} else {
			$range = $lang['member'];
		}

		if ($ally['ally_owner'] == $user['id'] || $ally_ranks[$user['ally_rank_id']-1]['memberlist'] != 0) {
			$lang['members_list'] = " (<a href=\"?set=alliance&mode=memberslist\">{$lang['Members_list']}</a>)";
		} else {
			$lang['members_list'] = '';
		}

		if ($ally['ally_owner'] == $user['id'] || $ally_ranks[$user['ally_rank_id']-1]['diplomacy'] != 0) {
			$qq = doquery("SELECT count(id) AS cc FROM {{table}} WHERE t_al = ".$ally['id']." AND status = 0", "alliance_diplo");
			$qq = mysql_fetch_assoc($qq);
			if ($qq['cc'] > 0)
				$lang['ally_dipl'] = " <a href=\"?set=alliance&mode=diplo\">Просмотр</a> (".$qq['cc']." новых запросов)";
			else
				$lang['ally_dipl'] = " <a href=\"?set=alliance&mode=diplo\">Просмотр</a>";
		} else {
			$lang['ally_dipl'] = 'нет доступа';
		}

		if ($ally['ally_owner'] == $user['id'] || $ally_ranks[$user['ally_rank_id']-1]['administrieren'] != 0) {
			$lang['alliance_admin'] = " (<a href=\"?set=alliance&mode=admin&edit=ally\">{$lang['Alliance_admin']}</a>)";
		} else {
			$lang['alliance_admin'] = '';
		}

		if ($ally['ally_owner'] == $user['id'] || $ally_ranks[$user['ally_rank_id']-1]['mails'] != 0) {
			$lang['send_circular_mail'] = "<tr><th>{$lang['Circular_message']} (".$user['mnl_alliance']." новых)</th><th><a href=\"?set=alliance&mode=circular\">{$lang['Send_circular_mail']}</a></th></tr>";
		} else {
			$lang['send_circular_mail'] = '';
		}

		$lang['requests'] = '';
		$request = doquery("SELECT id FROM {{table}} WHERE ally_request='{$ally['id']}'", 'users');
		$request_count = mysql_num_rows($request);
		if ($request_count != 0) {
			if ($ally['ally_owner'] == $user['id'] || $ally_ranks[$user['ally_rank_id']-1]['bewerbungen'] != 0)
				$lang['requests'] = "<tr><th>{$lang['Requests']}</th><th><a href=\"?set=alliance&mode=admin&edit=requests\">{$request_count} {$lang['XRequests']}</a></th></tr>";
		}
		if ($ally['ally_owner'] != $user['id']) {
			$lang['ally_owner'] .= MessageForm($lang['Exit_of_this_alliance'], "", "?set=alliance&mode=exit", $lang['Continue']);
		} else {
			$lang['ally_owner'] .= '';
		}

		$lang['ally_image'] = ($ally['ally_image'] != '')?
		"<tr><th colspan=2><img src=\"{$ally['ally_image']}\"></td></tr>":'';

		$lang['range'] = $range;

		$lang['ally_description'] = nl2br(BBtoText($ally['ally_description']));
		$lang['ally_text'] = nl2br(BBtoText($ally['ally_text']));

		$lang['ally_web'] = $ally['ally_web'];
		$lang['ally_tag'] = $ally['ally_tag'];
		$lang['ally_members'] = $ally['ally_members'];
		$lang['ally_name'] = $ally['ally_name'];

		$page .= parsetemplate(gettemplate('alliance_frontpage'), $lang);
		display($page, $lang['your_alliance']);
	}
}
?>