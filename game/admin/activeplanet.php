<?php

if(!defined("INSIDE")) die("attemp hacking");

if ($user['authlevel'] >= 1) {
	includeLang('admin');

	$parse          = $lang;
	$parse['dpath'] = $dpath;
	$parse['mf']    = $mf;

	$PageTPL        = gettemplate('admin/activeplanet_body');
	$AllActivPlanet = doquery("SELECT `name`, `galaxy`, `system`, `planet`, `last_update` FROM {{table}} WHERE `last_update` >= '". (time()-15 * 60) ."' ORDER BY `id` ASC", 'planets');
	$Count          = 0;

	while ($ActivPlanet = mysql_fetch_assoc($AllActivPlanet)) {
		$parse['online_list'] .= "<tr>";
		$parse['online_list'] .= "<td class=b><center><b>". $ActivPlanet['name'] ."</b></center></td>";
		$parse['online_list'] .= "<td class=b><center><b>[". $ActivPlanet['galaxy'] .":". $ActivPlanet['system'] .":". $ActivPlanet['planet'] ."]</b></center></td>";
		$parse['online_list'] .= "<td class=b><center><b>". pretty_time(time() - $ActivPlanet['last_update']) . "</b></center></td>";
		$parse['online_list'] .= "</tr>";
		$Count++;
	}
	$parse['online_list'] .= "<tr>";
	$parse['online_list'] .= "<th class=\"b\" colspan=\"4\">". $lang['adm_pl_they'] ." ". $Count ." ". $lang['adm_pl_apla'] ."</th>";
	$parse['online_list'] .= "</tr>";

	$page = parsetemplate( $PageTPL	, $parse );
	display( $page, $lang['adm_pl_title'], false, true, true );
} else {
	message( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
}
?>