<?php

if(!defined("INSIDE")) die("attemp hacking");

if ($user['authlevel'] >= 1) {
	includeLang('admin');

	if ($_GET['cmd'] == 'sort') {
		$TypeSort = $_GET['type'];
	} else {
		$TypeSort = "user_lastip";
	}

	$PageTPL  = gettemplate('admin/overview_body');
	$RowsTPL  = gettemplate('admin/overview_rows');

	$parse                      = $lang;
	$parse['dpath']             = $dpath;
	$parse['mf']                = $mf;
	$parse['adm_ov_data_yourv'] = colorRed(VERSION);

	$Last15Mins = doquery("SELECT `id`, `username`, `user_lastip`, `ally_name`, `onlinetime` FROM {{table}} WHERE `onlinetime` >= '". (time() - 15 * 60) ."' ORDER BY `". $TypeSort ."` ASC;", 'users');
	$Count      = 0;
	$Color      = "lime";
	while ( $TheUser = mysql_fetch_array($Last15Mins) ) {
		if ($PrevIP != "") {
			if ($PrevIP == $TheUser['user_lastip']) {
				$Color = "red";
			} else {
				$Color = "lime";
			}
		}

		$PrevIP = $TheUser['user_lastip'];

		$Bloc['dpath']               = $dpath;
		$Bloc['adm_ov_altpm']        = $lang['adm_ov_altpm'];
		$Bloc['adm_ov_wrtpm']        = $lang['adm_ov_wrtpm'];
		$Bloc['adm_ov_data_id']      = $TheUser['id'];
		$Bloc['adm_ov_data_name']    = $TheUser['username'];
		$Bloc['adm_ov_data_clip']    = $Color;
		$Bloc['adm_ov_data_adip']    = $TheUser['user_lastip'];
		$Bloc['adm_ov_data_ally']    = $TheUser['ally_name'];
		$Bloc['adm_ov_data_activ']   = pretty_time ( time() - $TheUser['onlinetime'] );
		$Bloc['adm_ov_data_pict']    = "m.gif";

		$parse['adm_ov_data_table'] .= parsetemplate( $RowsTPL, $Bloc );
		$Count++;
	}

	$parse['adm_ov_data_count']  = $Count;
	$Page = parsetemplate($PageTPL, $parse);

	display ( $Page, $lang['sys_overview'], false, true, true);
} else {
	message( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
}
?>