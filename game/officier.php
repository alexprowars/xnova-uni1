<?php

if(!defined("INSIDE")) die("attemp hacking");

if ($user['urlaubs_modus'] == 1) {
	message("Нет доступа!");
}

function ShowOfficierPage ( &$CurrentUser ) {
	global $lang, $resource, $reslist, $_GET;

	includeLang('officier');

	if ($CurrentUser['rpg_points'] < 0) {
		doquery("UPDATE {{table}} SET `rpg_points` = '0' WHERE `id` = '". $CurrentUser['id'] ."';", 'users');
		$CurrentUser['rpg_points'] = 0;
	}
	$now = time();

	if ($_POST['buy']) {

		$need_c = 0;
		$times = 0;
		if ($_POST['week'] != "") { $need_c = 10000; $times = 604800;
		} elseif ($_POST['month'] != "") { $need_c = 25000; $times = 2592000;
		} elseif ($_POST['three_months'] != "") { $need_c = 60000; $times = 7776000; }

		if ($need_c > 0 && $times > 0 && $CurrentUser['credits'] >= $need_c) {
			$Selected = intval($_POST['buy']);
			//if ($Selected == 605) die('Услуга недоступна');
			if ( in_array($Selected, $reslist['officier']) ) {
				if ( $CurrentUser[$resource[$Selected]] > $now ) {
					$CurrentUser[$resource[$Selected]] = $CurrentUser[$resource[$Selected]] + $times;
				} else {
					$CurrentUser[$resource[$Selected]] = $now + $times;
				}
					$CurrentUser['credits'] -= $need_c;

					$QryUpdateUser  = "UPDATE {{table}} SET ";
					$QryUpdateUser .= "`credits` = '".$CurrentUser['credits']."', ";
					$QryUpdateUser .= "`".$resource[$Selected]."` = '".$CurrentUser[$resource[$Selected]]."' ";
					$QryUpdateUser .= "WHERE ";
					$QryUpdateUser .= "`id` = '".$CurrentUser['id']."';";
					doquery( $QryUpdateUser, 'users' );

					doquery("UPDATE {{table}} SET config_value = config_value + ".$need_c." WHERE config_name = 'credits';", "config");

					$Message = $lang['OffiRecrute'];
			} else $Message = "НУ ТЫ И ЧИТАК!!!!!!";
		} else {
			$Message = $lang['NoPoints'];
		}
		$MessTPL        = gettemplate('message_body');
		$parse['title'] = $lang['Officier'];
		$parse['mes']   = $Message;

		$page           = parsetemplate( $MessTPL, $parse);
	} else {
		$PageTPL = gettemplate('officier_body');
		$RowsTPL = gettemplate('officier_rows');
		$parse['off_points']   = $lang['off_points'];
		$parse['alv_points']   = pretty_number($CurrentUser['credits']);
		$parse['disp_off_tbl'] = "";
		for ( $Officier = 601; $Officier <= 607; $Officier++ ) {
			$bloc['off_id']       = $Officier;
			$bloc['off_tx_lvl']   = $lang['ttle'][$Officier];
			if ($CurrentUser[$resource[$Officier]] > time()) {
				$bloc['off_lvl'] = "<font color=\"#00ff00\">Нанят до : ".date("d.m.Y H:i", $CurrentUser[$resource[$Officier]])."</font>";
				$bloc['off_link'] = "<font color=\"red\">Продлить</font>";
			} else {
				$bloc['off_lvl'] = "<font color=\"#ff0000\">Не оплачено</font>";
				$bloc['off_link'] = "<font color=\"red\">Нанять</font>";
			}
			$bloc['off_desc']     = $lang['Desc'][$Officier];

			$bloc['off_link'] .= "<hr size=\"1\"><input type=\"hidden\" name=\"buy\" value=\"".$Officier."\"><input type=\"submit\" name=\"week\" value=\"на неделю\"><br>Стоимость:&nbsp;<font color=\"lime\">10.000</font>&nbsp;кр.<hr size=\"1\"><input type=\"submit\" name=\"month\" value=\"на месяц\"><br>Стоимость:&nbsp;<font color=\"lime\">25.000</font>&nbsp;кр.<hr size=\"1\"><input type=\"submit\" name=\"three_months\" value=\"на 3 месяца\"><br>Стоимость:&nbsp;<font color=\"lime\">60.000</font>&nbsp;кр.";
			$parse['disp_off_tbl'] .= parsetemplate( $RowsTPL, $bloc );
		}
		$page = parsetemplate( $PageTPL, $parse);
	}

	return $page;
}

	$page = ShowOfficierPage ( $user );
	display($page, 'Офицеры', false);

?>