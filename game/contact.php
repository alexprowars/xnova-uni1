<?php

if(!defined("INSIDE")) die("attemp hacking");

includeLang('contact');

$RowsTPL = gettemplate('contact_body_rows');
$parse   = $lang;

$QrySelectUser  = "SELECT u.`username`, ui.`email`, u.`authlevel` ";
$QrySelectUser .= "FROM {{table}}users u, {{table}}users_inf ui ";
$QrySelectUser .= "WHERE ui.id = u.id AND u.`authlevel` != '0' ORDER BY u.`authlevel` DESC;";
$GameOps = doquery ( $QrySelectUser, '');

while( $Ops = mysql_fetch_assoc($GameOps) ) {
	$bloc['ctc_data_name']    = $Ops['username'];
	$bloc['ctc_data_auth']    = $lang['user_level'][$Ops['authlevel']];
	$bloc['ctc_data_mail']    = "<a href=mailto:".$Ops['email'].">".$Ops['email']."</a>";
	$parse['ctc_admin_list'] .= parsetemplate($RowsTPL, $bloc);
}

if ($user['id'])
	display(parsetemplate(gettemplate('contact_body'), $parse), $lang['ctc_title'], false);
else
	display(parsetemplate(gettemplate('contact_body'), $parse), $lang['ctc_title'], false, false);
	
?>