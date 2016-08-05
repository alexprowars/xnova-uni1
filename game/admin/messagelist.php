<?php

if(!defined("INSIDE")) die("attemp hacking");

if ($user['authlevel'] >= "2") {
	includeLang('admin/messagelist');

	$BodyTpl    = gettemplate('admin/messagelist_body');
	$RowsTpl    = gettemplate('admin/messagelist_table_rows');

	$Prev       = ( !empty($_POST['prev'])   ) ? true : false;
	$Next       = ( !empty($_POST['next'])   ) ? true : false;
	$DelSel     = ( !empty($_POST['delsel']) ) ? true : false;
	$DelDat     = ( !empty($_POST['deldat']) ) ? true : false;
	$CurrPage   = ( !empty($_POST['curr'])   ) ? intval($_POST['curr']) : 1;
	$Selected   = ( !empty($_POST['type'])   ) ? intval($_POST['type']) : 1;
	$SelPage    = $_POST['page'];
	
	if ($Selected == 6) $Selected = 0;

	$ViewPage = ( !empty($SelPage) ) ? $SelPage : 1;


	if ($Prev   == true) {
		$CurrPage -= 1;
		if ($CurrPage >= 1) {
			$ViewPage = $CurrPage;
		} else {
			$ViewPage = 1;
		}
	} elseif ($Next == true) {
		$Mess      = doquery("SELECT COUNT(*) AS `max` FROM {{table}} WHERE `message_type` = '". $Selected ."';", 'messages', true);
		$MaxPage   = ceil ( ($Mess['max'] / 25) );
		$CurrPage += 1;
		if ($CurrPage <= $MaxPage) {
			$ViewPage = $CurrPage;
		} else {
			$ViewPage = $MaxPage;
		}
	} elseif ($DelSel == true) {
		foreach($_POST['sele_mes'] as $MessId => $Value) {
			if ($Value = "on") {
				doquery ( "DELETE FROM {{table}} WHERE `message_id` = '". $MessId ."';", 'messages');
			}
		}
	} elseif ($DelDat == true) {
		$SelDay    = $_POST['selday'];
		$SelMonth  = $_POST['selmonth'];
		$SelYear   = $_POST['selyear'];
		$LimitDate = mktime (0,0,0, $SelMonth, $SelDay, $SelYear );
		if ($LimitDate != false) {
			doquery ( "DELETE FROM {{table}} WHERE `message_time` <= '". $LimitDate ."';", 'messages');
			doquery ( "DELETE FROM {{table}} WHERE `time` <= '". $LimitDate ."';", 'rw');
		}
	}

	$Mess     = doquery("SELECT COUNT(*) AS `max` FROM {{table}} WHERE `message_type` = '". $Selected ."';", 'messages', true);
	$MaxPage  = ceil ( ($Mess['max'] / 25) );

	$parse                      = $lang;
	$parse['mlst_data_page']    = $ViewPage;
	$parse['mlst_data_pagemax'] = $MaxPage;
	$parse['mlst_data_sele']    = $Selected;

	$parse['mlst_data_types'] .= "<option value=\"1\"".  (($Selected == "1")  ? " SELECTED" : "") .">". $lang['mlst_mess_typ__1'] ."</option>";
	$parse['mlst_data_types'] .= "<option value=\"2\"".  (($Selected == "2")  ? " SELECTED" : "") .">". $lang['mlst_mess_typ__2'] ."</option>";
	$parse['mlst_data_types'] .= "<option value=\"3\"".  (($Selected == "3")  ? " SELECTED" : "") .">". $lang['mlst_mess_typ__3'] ."</option>";
	$parse['mlst_data_types'] .= "<option value=\"4\"".  (($Selected == "4")  ? " SELECTED" : "") .">". $lang['mlst_mess_typ__4'] ."</option>";
	$parse['mlst_data_types'] .= "<option value=\"5\"".  (($Selected == "5")  ? " SELECTED" : "") .">". $lang['mlst_mess_typ__5'] ."</option>";
	$parse['mlst_data_types'] .= "<option value=\"6\"".  (($Selected == "6")  ? " SELECTED" : "") .">Прочее</option>";

	$parse['mlst_data_pages']  = "";
	for ( $cPage = 1; $cPage <= $MaxPage; $cPage++ ) {
		$parse['mlst_data_pages'] .= "<option value=\"".$cPage."\"".  (($ViewPage == $cPage)  ? " SELECTED" : "") .">". $cPage ."/". $MaxPage ."</option>";
	}

	$parse['mlst_scpt']  = "<script language=\"JavaScript\">\n";
	$parse['mlst_scpt'] .= "function f(target_url, win_name) {\n";
	$parse['mlst_scpt'] .= "var new_win = window.open(target_url,win_name,'resizable=yes,scrollbars=yes,menubar=no,toolbar=no,width=550,height=280,top=0,left=0');\n";
	$parse['mlst_scpt'] .= "new_win.focus();\n";
	$parse['mlst_scpt'] .= "}\n";
	$parse['mlst_scpt'] .= "</script>\n";

	$parse['tbl_rows']   = "";
	$parse['mlst_title'] = $lang['mlst_title'];
	
	if(isset($_POST['userid']) && $_POST['userid'] != "") {
		$userid = " AND message_owner = ".intval($_POST['userid'])."";
		$parse['userid'] = intval($_POST['userid']);
	} elseif(isset($_POST['userid_s']) && $_POST['userid_s'] != "") {
		$userid = " AND message_sender = ".intval($_POST['userid_s'])."";
		$parse['userid_s'] = intval($_POST['userid_s']);
	} else
		$userid = "";

	$StartRec           = 0 + (($ViewPage - 1) * 25);
	$Messages           = doquery("SELECT * FROM {{table}} WHERE `message_type` = '". $Selected ."' ".$userid." ORDER BY `message_time` DESC LIMIT ". $StartRec .",25;", 'messages');
	while ($row = mysql_fetch_assoc($Messages)) {
		$OwnerData = doquery ("SELECT `username` FROM {{table}} WHERE `id` = '". $row['message_owner'] ."';", 'users',true);
		$bloc['mlst_id']      = $row['message_id'];
		$bloc['mlst_from']    = $row['message_from'];
		$bloc['mlst_to']      = $OwnerData['username'] ." ID:". $row['message_owner'];
		$bloc['mlst_text']    = $row['message_text'];
		$bloc['mlst_time']    = date ( "d M Y H:i:s", $row['message_time'] );

		$parse['mlst_data_rows'] .= parsetemplate($RowsTpl , $bloc);
	}

	$display            = parsetemplate($BodyTpl , $parse);

	if (isset($_POST['delit'])) {
		doquery ("DELETE FROM {{table}} WHERE `message_id` = '". $_POST['delit'] ."';", 'messages');
		message ( $lang['mlst_mess_del'] ." ( ". $_POST['delit'] ." )", $lang['mlst_title'], "./messagelist.".$phpEx, 3);
	}
	display ($display, $lang['mlst_title'], false, true, true);
} else {
	message($lang['sys_noalloaw'], $lang['sys_noaccess']);
}
?>