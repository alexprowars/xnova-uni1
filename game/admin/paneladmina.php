<?php

if(!defined("INSIDE")) die("attemp hacking");

if ($user['authlevel'] >= "1") {
	includeLang('admin/adminpanel');

	$PanelMainTPL = gettemplate('admin/admin_panel_main');

	$parse                  = $lang;
	$parse['adm_sub_form1'] = "";
	$parse['adm_sub_form2'] = "";
	$parse['adm_sub_form3'] = "";

	if (isset($_POST['result'])) {
		switch ($_POST['result']){
			case 'usr_search':
				$Pattern = addslashes($_POST['player']);
				$SelUser = doquery("SELECT u.*, ui.* FROM {{table}}users u, {{table}}users_inf ui WHERE ui.id = u.id AND u.`username` = '". $Pattern ."' LIMIT 1;", '', true);
				$UsrMain = doquery("SELECT `name` FROM {{table}} WHERE `id` = '". $SelUser['id_planet'] ."';", 'planets', true);

				$bloc                   = $lang;
				$bloc['answer1']        = $SelUser['id'];
				$bloc['answer2']        = $SelUser['username'];
				$bloc['answer3']        = $SelUser['user_lastip'];
				$bloc['answer4']        = $SelUser['email'];
				$bloc['answer9']        = $SelUser['email_2'];
				$bloc['answer5']        = $lang['adm_usr_level'][ $SelUser['authlevel'] ];
				$bloc['answer6']        = $lang['adm_usr_genre'][ $SelUser['sex'] ];
				$bloc['answer7']        = "[".$SelUser['id_planet']."] ".$UsrMain['name'];
				$bloc['answer8']        = "[".$SelUser['galaxy'].":".$SelUser['system'].":".$SelUser['planet']."] ";
				$SubPanelTPL            = gettemplate('admin/admin_panel_asw1');
				$parse['adm_sub_form2'] = parsetemplate( $SubPanelTPL, $bloc );
				break;

			case 'usr_data':

				if ($user['authlevel'] >= "2") {

				$Pattern = addslashes($_POST['player']);
				$SelUser = doquery("SELECT u.*, ui.* FROM {{table}}users u, {{table}}users_inf ui WHERE ui.id = u.id AND u.`username` = '". $Pattern ."' LIMIT 1;", '', true);
				$UsrMain = doquery("SELECT `name` FROM {{table}} WHERE `id` = '". $SelUser['id_planet'] ."';", 'planets', true);

				$bloc                    = $lang;
				$bloc['answer1']         = $SelUser['id'];
				$bloc['answer2']         = $SelUser['username'];
				$bloc['answer3']         = $SelUser['user_lastip'];
				$bloc['answer4']         = $SelUser['email'];
				$bloc['answer5']         = $lang['adm_usr_level'][ $SelUser['authlevel'] ];
				$bloc['answer6']         = $lang['adm_usr_genre'][ $SelUser['sex'] ];
				$bloc['answer7']         = "[".$SelUser['id_planet']."] ".$UsrMain['name'];
				$bloc['answer8']         = "[".$SelUser['galaxy'].":".$SelUser['system'].":".$SelUser['planet']."] ";
				$SubPanelTPL             = gettemplate('admin/admin_panel_asw1');
				$parse['adm_sub_form1']  = parsetemplate( $SubPanelTPL, $bloc );

				$parse['adm_sub_form2']  = "<table><tbody>";
				$parse['adm_sub_form2'] .= "<tr><td colspan=\"4\" class=\"c\">".$lang['adm_colony']."</td></tr>";
				$UsrColo = doquery("SELECT * FROM {{table}} WHERE `id_owner` = '". $SelUser['id'] ." ORDER BY `galaxy` ASC, `planet` ASC, `system` ASC, `planet_type` ASC';", 'planets');
				while ( $Colo = mysql_fetch_assoc($UsrColo) ) {
					if ($Colo['id'] != $SelUser['id_planet']) {
						$parse['adm_sub_form2'] .= "<tr><th>".$Colo['id']."</th>";
						$parse['adm_sub_form2'] .= "<th>";
						if($Colo['planet_type'] == 1) 
							$parse['adm_sub_form2'] .= $lang['adm_planet']; 
						else {
							if($Colo['planet_type'] == 1) 
								$parse['adm_sub_form2'] .= $lang['adm_moon']; 
							else 
								$parse['adm_sub_form2'] .= "Военная база"; 
						}
						$parse['adm_sub_form2'] .= "</th><th>[".$Colo['galaxy'].":".$Colo['system'].":".$Colo['planet']."]</th>";
						$parse['adm_sub_form2'] .= "<th>".$Colo['name']."</th></tr>";
					}
				}
				$parse['adm_sub_form2'] .= "</tbody></table>";

				$parse['adm_sub_form3']  = "<table><tbody>";
				$parse['adm_sub_form3'] .= "<tr><td colspan=\"4\" class=\"c\">".$lang['adm_technos']."</td></tr>";
				for ($Item = 100; $Item <= 199; $Item++) {
					if ($resource[$Item] != "") {
						$parse['adm_sub_form3'] .= "<tr><th>".$lang['tech'][$Item]."</th>";
						$parse['adm_sub_form3'] .= "<th>".$SelUser[$resource[$Item]]."</th></tr>";
					}
				}
				$parse['adm_sub_form3'] .= "</tbody></table>";

				}

				break;

			case 'usr_level':
				if ($user['authlevel'] >= "3") {
			
				$Player     = addslashes($_POST['player']);
				$NewLvl     = intval($_POST['authlvl']);

				$QryUpdate  = doquery("UPDATE {{table}} SET `authlevel` = '".$NewLvl."' WHERE `username` = '".$Player."';", 'users');
				$Message    = $lang['adm_mess_lvl1']. " ". $Player ." ".$lang['adm_mess_lvl2'];
				$Message   .= "<font color=\"red\">".$lang['adm_usr_level'][ $NewLvl ]."</font>!";

				message ( $Message, $lang['adm_mod_level'] );

				}
				break;

			case 'ip_search':
				$Pattern    = addslashes($_POST['ip']);
				$SelUser    = doquery("SELECT * FROM {{table}} WHERE `user_lastip` = '". $Pattern ."';", 'users');
				$bloc                   = $lang;
				$bloc['adm_this_ip']    = $Pattern;
				while ( $Usr = mysql_fetch_assoc($SelUser) ) {
					$UsrMain = doquery("SELECT `name` FROM {{table}} WHERE `id` = '". $Usr['id_planet'] ."';", 'planets', true);
					$bloc['adm_plyer_lst'] .= "<tr><th>".$Usr['username']."</th><th>[".$Usr['galaxy'].":".$Usr['system'].":".$Usr['planet']."] ".$UsrMain['name']."</th></tr>";
				}
				$SubPanelTPL            = gettemplate('admin/admin_panel_asw2');
				$parse['adm_sub_form2'] = parsetemplate( $SubPanelTPL, $bloc );
				break;
			default:
				break;
		}
	}

	if (isset($_GET['action'])) {
		$bloc                   = $lang;
		switch ($_GET['action']){
			case 'usr_search':
				$SubPanelTPL            = gettemplate('admin/admin_panel_frm1');
				break;

			case 'usr_data':
				if ($user['authlevel'] >= "2") {
				$SubPanelTPL            = gettemplate('admin/admin_panel_frm4');
				}else {
					 message( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
				}
				break;

			case 'usr_level':
				if ($user['authlevel'] >= "3") {
				for ($Lvl = 0; $Lvl < 4; $Lvl++) {
							$bloc['adm_level_lst'] .= "<option value=\"". $Lvl ."\">". $lang['adm_usr_level'][ $Lvl ] ."</option>";
				}
					 $SubPanelTPL            = gettemplate('admin/admin_panel_frm3');
				}
				 else 
				{
					 message( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
				}
				break;

			case 'ip_search':
				$SubPanelTPL            = gettemplate('admin/admin_panel_frm2');
				break;

			default:
				break;
		}
		$parse['adm_sub_form2'] = parsetemplate( $SubPanelTPL, $bloc );
	}

	$page = parsetemplate( $PanelMainTPL, $parse );
	display( $page, $lang['panel_mainttl'], false, true, true );
} else {
	message( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
}
?>