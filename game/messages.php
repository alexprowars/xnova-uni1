<?php

if(!defined("INSIDE")) die("attemp hacking");

if ($user['id'] == 4075)
	message("Доступ в demo режиме ограничен.", "Ошибка");

	includeLang('messages');
	
	$page  = "<script language=\"JavaScript\" src=\"/scripts/smiles.js\"></script>\n";
	$page  .= "<script language=\"JavaScript\">\n";
	$page .= "function f(target_url, win_name) {\n";
	$page .= "var new_win = window.open(target_url,win_name,'resizable=yes,scrollbars=yes,menubar=no,toolbar=no,width=550,height=280,top=0,left=0');\n";
	$page .= "new_win.focus();\n";
	$page .= "}\n";
	$page .= "</script>\n";

	if (isset($_GET['abuse'])) {
		$mes = doquery("SELECT * FROM {{table}} WHERE message_id = ".intval($_GET['abuse'])." AND message_owner = ".$user['id'].";", "messages", true);
		if (isset($mes['message_id'])) {
			$c = doquery("SELECT `id` FROM {{table}} WHERE `authlevel` != 0", "users");
			while ($cc = mysql_fetch_assoc($c)) {
				SendSimpleMessage ( $cc['id'], $user['id'], '', 1, '<font color=red>'.$user['username'].'</font>', '', 'От кого: '.$mes['message_from'].'<br>Дата отправления: '.date("d-m-Y H:i:s", $mes['message_time']).'<br>Текст сообщения: '.$mes['message_text']);
			}
			$page .= "<script>alert('Жалоба отправлена администрации игры.');</script>";
		}
	}

	$OwnerID       = $_GET['id'];
	if ($_POST['messcat'] == "") $MessCategory  = 100; else $MessCategory  = intval($_POST['messcat']);
	if (intval($_POST['show_by']) > 50 || !$_POST['show_by']) $lim = 10; else $lim = intval($_POST['show_by']);
	$MessPageMode  = $_GET["mode"];
	$start  = intval($_POST["start"]);
	$DeleteWhat    = $_POST['deletemessages'];
	if (isset ($DeleteWhat)) {
		$MessPageMode = "delete";
	}

	$MessageType   = array ( 0, 1, 2, 3, 4, 5, 15, 99, 100 );
	$TitleColor    = array ( 0 => '#FFFF00', 1 => '#FF6699', 2 => '#FF3300', 3 => '#FF9900', 4 => '#773399', 5 => '#009933', 15 => '#030070', 99 => '#007070', 100 => '#ABABAB'  );
	$BackGndColor  = array ( 0 => '#663366', 1 => '#336666', 2 => '#000099', 3 => '#666666', 4 => '#999999', 5 => '#999999', 15 => '#999999', 99 => '#999999', 100 => '#999999'  );

	if ($MessCategory == 101) $MessPageMode = '';

	switch ($MessPageMode) {
		case 'write':

			if ( !is_numeric( $OwnerID ) ) {
				message ($lang['mess_no_ownerid'], $lang['mess_error']);
			}

			$OwnerRecord = doquery("SELECT `username`, `id_planet` FROM {{table}} WHERE `id` = '".$OwnerID."';", 'users', true);

			if (!$OwnerRecord) {
				message ($lang['mess_no_owner']  , $lang['mess_error']);
			}

			$OwnerHome   = doquery("SELECT `galaxy`, `system`, `planet` FROM {{table}} WHERE `id_planet` = '". $OwnerRecord["id_planet"] ."';", 'galaxy', true);
			if (!$OwnerHome) {
				message ($lang['mess_no_ownerpl'], $lang['mess_error']);
			}

			if ($_POST) {
				$error = 0;
				if (!$_POST["text"]) {
					$error++;
					$page .= "<center><br><font color=#FF0000>".$lang['mess_no_text']."<br></font></center>";
				}
				if ($error == 0) {
					$page .= "<center><font color=#00FF00>".$lang['mess_sended']."<br></font></center>";

					$_POST['text'] = str_replace("'", '&#39;', $_POST['text']);
					$_POST['text'] = htmlspecialchars($_POST['text']);

					$Owner   = $OwnerID;
					$Sender  = $user['id'];
					$From    = $user['username'] ." [".$user['galaxy'].":".$user['system'].":".$user['planet']."]";
					$Message = trim ( nl2br ( strip_tags ( $_POST['text'], '<br>' ) ) );
					SendSimpleMessage ( $Owner, $Sender, '', 1, $From, '', $Message);
					$text    = "";
				}
			}
			$parse['Send_message'] = $lang['mess_pagetitle'];
			$parse['Recipient']    = $lang['mess_recipient'];
			$parse['Message']      = $lang['mess_message'];
			$parse['characters']   = $lang['mess_characters'];
			$parse['Envoyer']      = $lang['mess_envoyer'];

			$parse['id']           = $OwnerID;
			$parse['to']           = $OwnerRecord['username'] ." [".$OwnerHome['galaxy'].":".$OwnerHome['system'].":".$OwnerHome['planet']."]";
			$parse['text']         = $text;

			$page                 .= parsetemplate(gettemplate('messages_pm_form'), $parse);
		break;

		case 'delete':

			//$DeleteWhat = $_POST['deletemessages'];
			//if ($DeleteWhat == 'deleteall') {
			//	doquery("DELETE FROM {{table}} WHERE `message_owner` = '". $user['id'] ."';", 'messages');
			//} elseif ($DeleteWhat == 'deletemarked') {
			//	foreach($_POST as $Message => $Answer) {
			//		if (preg_match("/delmes/i", $Message) && $Answer == 'on') {
			//			$MessId   = str_replace("delmes", "", $Message);
			//			doquery("DELETE FROM {{table}} WHERE `message_id` = '".$MessId."';", 'messages');
			//		}
			//	}
			//} elseif ($DeleteWhat == 'deleteunmarked') {
			//	foreach($_POST as $Message => $Answer) {
			//		$CurMess    = preg_match("/showmes/i", $Message);
			//		$MessId     = str_replace("showmes", "", $Message);
			//		$Selected   = "delmes".$MessId;
			//		$IsSelected = $_POST[ $Selected ];
			//		if (preg_match("/showmes/i", $Message) && !isset($IsSelected)) {
			//			doquery("DELETE FROM {{table}} WHERE `message_id` = '".$MessId."';", 'messages');
			//		}
			//	}
			//}

		default:

			if ($user['new_message'] > 0) {
				doquery ("UPDATE {{table}} SET `new_message` = 0 WHERE `id` = ".$user['id']."", 'users' );
				$user['new_message'] = 0;
			}

			if ($MessCategory < 100) $UsrMess1 = doquery("SELECT COUNT(message_id) as kol FROM {{table}} WHERE `message_owner` = '".$user['id']."' AND message_type = ".$MessCategory."", 'messages', true);
			elseif ($MessCategory == 101) $UsrMess1 = doquery("SELECT COUNT(message_id) as kol FROM {{table}} WHERE `message_sender` = '".$user['id']."'", 'messages', true);
			else $UsrMess1 = doquery("SELECT COUNT(message_id) as kol FROM {{table}} WHERE `message_owner` = '".$user['id']."'", 'messages', true);

			$pages = round($UsrMess1['kol'] / $lim) + 1;
			if (!$start) $start = 1;

			$limits = "".(($start-1)*$lim).",".$lim."";

			$page .= "<br>";
			$page .= "<table width=\"659\"><form action=\"?set=messages\" name=\"mes_form\" method=\"post\">";
			$page .= "<tr><th>Показывать : <select name=\"messcat\" onChange=\"document.mes_form.submit();\"><option value=\"100\">Все";
			for ($MessType = 0; $MessType < 100; $MessType++) {
				if ( in_array($MessType, $MessageType) ) {
					$page .= "<option value=\"".$MessType."\""; if ($MessType == $MessCategory) $page .= " selected"; $page .= ">".$lang['type'][$MessType]."";
				}
			}
			$page .= "<option value=\"101\""; 
			if ($MessCategory == 101) $page .= " selected";
			$page .= ">Исходящие</select>";
			$page .= "&nbsp;&nbsp;&nbsp;по : <select name=\"show_by\" onChange=\"document.mes_form.submit();\"><option value=\"5\"";
			if ($lim == 5) $page .= " selected"; $page .= ">5<option value=\"10\""; if ($lim == 10) $page .= " selected"; $page .= ">10<option value=\"25\""; if ($lim == 25) $page .= " selected"; $page .= ">25<option value=\"50\""; if ($lim == 50) $page .= " selected"; $page .= ">50</select>&nbsp;&nbsp;&nbsp;на странице</th>";
			$page .= "<th>Перейти на страницу: <select name=\"start\" onChange=\"document.mes_form.submit();\">";
			for ($Me = 1; $Me <= $pages; $Me++) {
				$page .= "<option value=\"".$Me."\""; if ($Me == $start) $page .= " SELECTED"; $page .= ">".$Me."</option>";
			}
			$page .= "</select></th></tr><tr>";
			$page .= "</form><table width=\"659\">";
			$page .= "<tr><th><table width=\"100%\">";
			$page .= "<tr>";
			$page .= "<td></td>";
			$page .= "<td>\n<form action=\"?set=messages&mode=delete\" name=\"mes\" method=\"post\"><input name=\"messages\" value=\"1\" type=\"hidden\">";
			$page .= "<table width=\"100%\">";
			//$page .= "<tr>";
			//$page .= "<th colspan=\"4\">";
			//$page .= "<select onchange=\"document.getElementById('deletemessages').options[this.selectedIndex].selected='true'\" id=\"deletemessages\" name=\"deletemessages\">";
			//$page .= "<option value=\"deletemarked\">".$lang['mess_deletemarked']."</option>";
			//$page .= "<option value=\"deleteunmarked\">".$lang['mess_deleteunmarked']."</option>";
			//$page .= "<option value=\"deleteall\">".$lang['mess_deleteall']."</option>";
			//$page .= "</select>";
			//$page .= "<input value=\"".$lang['mess_its_ok']."\" type=\"submit\"><br><br>";
			//$page .= "</th>";
			//$page .= "</tr>";
			$page .= "<input name=\"category\" value=\"".$MessCategory."\" type=\"hidden\">";
			$page .= "<th>&nbsp;</th>";
			$page .= "<th>Дата</th>";
			$page .= "<th>От</th>";
			$page .= "</tr>";

			if ($MessCategory < 100)
				$UsrMess = doquery("SELECT * FROM {{table}} WHERE `message_owner` = '".$user['id']."' AND message_type = ".$MessCategory." ORDER BY `message_time` DESC LIMIT ".$limits.";", 'messages');
			elseif ($MessCategory == 101)
				$UsrMess = doquery("SELECT * FROM {{table}} WHERE `message_sender` = '".$user['id']."' ORDER BY `message_time` DESC LIMIT ".$limits.";", 'messages');
			else
				$UsrMess = doquery("SELECT * FROM {{table}} WHERE `message_owner` = '".$user['id']."' ORDER BY `message_time` DESC LIMIT ".$limits.";", 'messages');

			while ($CurMess = mysql_fetch_assoc($UsrMess)) {
				$page .= "\n<tr>";

				//if ($MessCategory != 101) {
				//	$page .= "<input name=\"showmes". $CurMess['message_id'] . "\" type=\"hidden\" value=\"1\">";
				//	$page .= "<th><input name=\"delmes". $CurMess['message_id'] . "\" type=\"checkbox\"></th>";
				//} else
					$page .= "<th>&nbsp;</th>";

				$page .= "<th>". date("d-m H:i:s", $CurMess['message_time']) ."</th>";
				$page .= "<th>". $CurMess['message_from'];
				
				if ($CurMess['message_type'] == 1 && $MessCategory != 101) {
					$page .= "&nbsp;<a href=\"?set=messages&mode=write&amp;id=". $CurMess['message_sender'] ."\"><img src=\"". $dpath ."img/m.gif\" alt=\"".$lang['mess_answer']."\" border=\"0\"></a>";
					$page .= "&nbsp;<a href=\"?set=messages&amp;abuse=". $CurMess['message_id'] . "\" onclick='return confirm(\"Вы уверены что хотите отправить жалобу на это сообщение?\");'><img src=\"". $dpath ."img/z.gif\" title='Отправить жалобу'></a>";
				}
	
				$page .= "</th></tr><tr>";
				$page .= "<td style=\"background-color: ".$BackGndColor[$CurMess['message_type']]."; background-image: none;\"; colspan=\"3\" class=\"b\">";
				if ($CurMess['message_type'] == 1) $page .= "<script>Text('".eregi_replace("[\n\r]", "", addslashes( nl2br ($CurMess['message_text']) ))."');</script>";
				else $page .= stripslashes( nl2br ($CurMess['message_text'] ) );
				$page .= "</td></tr>";
			}
			if ($UsrMess1['kol'] == 0) $page .= "<tr><td colspan=\"3\" align=center>нет сообщений</td></tr>";
			$page .= "</tr></form><tr>";
			$page .= "<td colspan=\"3\"></td>";
			$page .= "</tr>";
			$page .= "</table>\n";
			$page .= "</td>";
			$page .= "</tr>";
			$page .= "</table>\n";
			$page .= "</td>";
			$page .= "</table></tr></table>\n";
			$page .= "</center>";
			break;
	}

	display($page, 'Сообщения', false);

?>