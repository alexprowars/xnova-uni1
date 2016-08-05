<?php

if(!defined("INSIDE")) die("attemp hacking");

include($ugamela_root_path . 'includes/functions/DeleteSelectedUser.'.$phpEx);

	if ($user['authlevel'] > 2) {
		includeLang('admin');
		if ($_GET['cmd'] == 'dele') {
			DeleteSelectedUser ( $_GET['user'] );
		}
		if ($_GET['cmd'] == 'sort') {
			$TypeSort = $_GET['type'];
		} else {
			$TypeSort = "banaday";
		}

		$PageTPL = gettemplate('admin/userlist_body');
		$RowsTPL = gettemplate('admin/userlist_rows');

		$query   = doquery("SELECT u.`id`, u.`username`, ui.`email`, u.`user_lastip`, ui.`register_time`, u.`onlinetime`, u.`banaday` FROM {{table}}users u, {{table}}users_inf ui WHERE ui.id = u.id AND u.banaday > 0 ORDER BY u.onlinetime ASC LIMIT 25", '');

		$parse                 = $lang;
		$parse['adm_ul_table'] = "";
		$i                     = 0;
		$Color                 = "lime";
		while ($u = mysql_fetch_assoc ($query) ) {
			if ($PrevIP != "") {
				if ($PrevIP == $u['user_lastip']) {
					$Color = "red";
				} else {
					$Color = "lime";
				}
			}
			$Bloc['adm_ul_data_id']     = $u['id'];
			$Bloc['adm_ul_data_name']   = $u['username'];
			$Bloc['adm_ul_data_mail']   = $u['email'];
			$Bloc['adm_ul_data_adip']   = "<font color=\"".$Color."\">". $u['user_lastip'] ."</font>";
			$Bloc['adm_ul_data_regd']   = date ( "d/m/Y H:i:s", $u['register_time'] );
			$Bloc['adm_ul_data_lconn']  = date ( "d/m/Y H:i:s", $u['onlinetime'] );
			$Bloc['adm_ul_data_banna']  = ( $u['banaday'] > 0 ) ? "<a href=\"#\" title=\"". gmdate ( "d/m/Y H:i:s", $u['banaday']) ."\">". $lang['adm_ul_yes'] ."</a>" : $lang['adm_ul_no'];
			$Bloc['adm_ul_data_detai']  = "";//<a href='?set=admin&mode=userlist&cmd=dele&user=".$u['id']."'><img src=\"/images/r1.png\"></a>";
			$Bloc['adm_ul_data_actio']  = "<img src=\"../images/r1.png\">";
			$PrevIP                     = $u['user_lastip'];
			$parse['adm_ul_table']     .= parsetemplate( $RowsTPL, $Bloc );
			$i++;
		}
		$parse['adm_ul_count'] = $i;

		$page = parsetemplate( $PageTPL, $parse );
		display( $page, $lang['adm_ul_title'], false, true, true);
	} else {
		message( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
	}
?>