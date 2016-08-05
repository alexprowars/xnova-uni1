<?php

if(!defined("INSIDE")) die("attemp hacking");

includeLang('stat');

$parse = $lang;
$who   = (isset($_POST['who']))   ? $_POST['who']   : $_GET['who'];
if (!isset($who)) {
	$who   = 1;
}
$type  = (isset($_POST['type']))  ? $_POST['type']  : $_GET['type'];
if (!isset($type)) {
	$type  = 1;
}
$range = (isset($_POST['range'])) ? $_POST['range'] : $_GET['range'];
if (!isset($range)) {
	$rank = doquery("SELECT total_rank FROM {{table}} WHERE `stat_code` = '1' AND `stat_type` = '1' AND `id_owner` = '". $user['id'] ."';", 'statpoints', true);
	$range = $rank['total_rank'];
}
$pid = intval($_GET['pid']);

$parse['who']    = "<option value=\"1\"". (($who == "1") ? " SELECTED" : "") .">". $lang['stat_player'] ."</option>";
$parse['who']   .= "<option value=\"2\"". (($who == "2") ? " SELECTED" : "") .">". $lang['stat_allys']  ."</option>";

$parse['type']   = "<option value=\"1\"". (($type == "1") ? " SELECTED" : "") .">". $lang['stat_main']     ."</option>";
$parse['type']  .= "<option value=\"2\"". (($type == "2") ? " SELECTED" : "") .">". $lang['stat_fleet']    ."</option>";
$parse['type']  .= "<option value=\"3\"". (($type == "3") ? " SELECTED" : "") .">". $lang['stat_research'] ."</option>";

if ($type == 1) {
	$Order   = "total_points";
	$Points  = "total_points";
	$Counts  = "total_count";
	$Rank    = "total_rank";
	$OldRank = "total_old_rank";
} elseif ($type == 2) {
	$Order   = "fleet_points";
	$Points  = "fleet_points";
	$Counts  = "fleet_count";
	$Rank    = "fleet_rank";
	$OldRank = "fleet_old_rank";
} elseif ($type == 3) {
	$Order   = "tech_count";
	$Points  = "tech_points";
	$Counts  = "tech_count";
	$Rank    = "tech_rank";
	$OldRank = "tech_old_rank";
}

if ($who == 2) {
	$MaxAllys = doquery ("SELECT COUNT(*) AS `count` FROM {{table}} WHERE 1;", 'alliance', true);
	if ($MaxAllys['count'] > 100) {
		$LastPage = floor($MaxAllys['count'] / 100);
	}
	$parse['range'] = "";
	$start = floor($range / 100 % 100);
	
	for ($Page = 0; $Page <= $LastPage; $Page++) {
		$PageValue      = ($Page * 100) + 1;
		$PageRange      = $PageValue + 99;
		$parse['range'] .= "<option value=\"". $PageValue ."\"". (($start == $Page) ? " SELECTED" : "") .">". $PageValue ."-". $PageRange ."</option>";
	}

	$parse['stat_header'] = parsetemplate(gettemplate('stat_alliancetable_header'), $parse);

	$start *= 100;
	$query = doquery("SELECT s.*, a.`id`, a.`ally_tag`, a.`ally_name`, a.`ally_members` FROM {{table}}statpoints s, {{table}}alliance a WHERE s.`stat_type` = '2' AND s.`stat_code` = '1' AND a.id = s.id_owner ORDER BY s.`". $Order ."` DESC LIMIT ". $start .",100;", '');

	$start++;
	$parse['stat_values'] = "";

	while ($StatRow = mysql_fetch_assoc($query)) {

		if (!$parse['stat_date']) 
			$parse['stat_date']       = date("d M Y - H:i:s", $StatRow['stat_date']);

		$parse['ally_rank']       = $start;
		$rank_old                 = $StatRow[ $OldRank ];
		$rank_new                 = $start;

		if ($StatRow[ $Rank ] == 0){
			$QryUpdRank           = doquery("UPDATE {{table}} SET `".$Rank."` = '".$start."' WHERE `stat_type` = '2' AND `stat_code` = '1' AND `id_owner` = '". $StatRow['id_owner'] ."';" , "statpoints");
		}

		$ranking                  = $rank_old - $rank_new;
		if ($ranking == "0") {
			$parse['ally_rankplus']   = "<font color=\"#87CEEB\">*</font>";
		}
		if ($ranking < "0") {
			$parse['ally_rankplus']   = "<font color=\"red\">".$ranking."</font>";
		}
		if ($ranking > "0") {
			$parse['ally_rankplus']   = "<font color=\"green\">+".$ranking."</font>";
		}

		if ($StatRow['ally_name'] == $user['ally_name']) {
			$parse['ally_name'] = "<font color=\"#33CCFF\">".$StatRow['ally_name']."</font>";
		} else {
			$parse['ally_name'] = "<a href=\"?set=alliance&mode=ainfo&a=".$StatRow['id']."\">".$StatRow['ally_name']."</a>";
		}

		$parse['ally_mes']        = '';
		$parse['ally_members']    = $StatRow['ally_members'];
		$parse['ally_points']     = pretty_number( $StatRow[ $Order ] );
		$parse['ally_members_points'] =  pretty_number( floor($StatRow[ $Order ] / $StatRow['ally_members']) );

		$parse['stat_values']    .= parsetemplate(gettemplate('stat_alliancetable'), $parse);
		$start++;
	}
} else {
	$MaxUsers = doquery ("SELECT COUNT(id_owner) AS `count` FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `stat_hide` = 0;", 'statpoints', true);

	if ($MaxUsers['count'] > 100) {
		$LastPage = floor($MaxUsers['count'] / 100);
	}
	$parse['range'] = "";
	$start = floor(($range - 1) / 100 % 100);
	
	for ($Page = 0; $Page <= $LastPage; $Page++) {
		$PageValue      = ($Page * 100) + 1;
		$PageRange      = $PageValue + 99;
		$parse['range'] .= "<option value=\"". $PageValue ."\"". (($start == $Page) ? " SELECTED" : "") .">". $PageValue ."-". $PageRange ."</option>";
	}

	$parse['stat_header'] = parsetemplate(gettemplate('stat_playertable_header'), $parse);
	$start *= 100;
	
	$query = doquery("SELECT * FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `stat_hide` = 0 ORDER BY `". $Order ."` DESC LIMIT ". $start .",100;", 'statpoints');

	$start++;
	$parse['stat_date']   = $game_config['stats'];
	$parse['stat_values'] = "";
	while ($StatRow = mysql_fetch_assoc($query)) {
		if (!$parse['stat_date']) 
			$parse['stat_date']       = date("d M Y - H:i:s", $StatRow['stat_date']);
		$parse['player_rank']     = $start;


		$rank_old                 = $StatRow[ $OldRank ];
		if ( $rank_old == 0) {
			$rank_old             = $start;
		}
		$rank_new                 = $start;
		$ranking                  = $rank_old - $rank_new;
		if ($ranking == "0") {
			$parse['player_rankplus'] = "<font color=\"#87CEEB\">*</font>";
		}
		if ($ranking < "0") {
			$parse['player_rankplus'] = "<font color=\"red\">".$ranking."</font>";
		}
		if ($ranking > "0") {
			$parse['player_rankplus'] = "<font color=\"green\">+".$ranking."</font>";
		}
		if ($StatRow['id_owner'] == $user['id'] || $StatRow['id_owner'] == $pid) {
			$parse['player_name']     = "<font color=\"lime\">".$StatRow['username']."</font>";
		} else {
			$parse['player_name']     = "<a href=\"?set=players&id=".$StatRow['id_owner']."\">".$StatRow['username']."</a>";
		}
		if ($StatRow['del'] == 1){
			$parse['player_name']  = "<font color=red>".$parse['player_name']."</font>";
		}
		$parse['player_mes']      = "<a href=\"?set=messages&mode=write&id=" . $StatRow['id_owner'] . "\"><img src=\"" . $dpath . "img/m.gif\" border=\"0\" alt=\"". $lang['Ecrire'] ."\" /></a>";
		if ($StatRow['ally_name'] == $user['ally_name']) {
			$parse['player_alliance'] = "<font color=\"#33CCFF\">".$StatRow['ally_name']."</font>";
		} else {
			$parse['player_alliance'] = "<a href=\"?set=alliance&mode=ainfo&a=".$StatRow['id_ally']."\">".$StatRow['ally_name']."</a>";
		}
		$parse['player_country'] = ''; 

		$parse['player_points']   = pretty_number( $StatRow[ $Order ] );
		$parse['stat_values']    .= parsetemplate(gettemplate('stat_playertable'), $parse);
		$start++;
	}
}

display(parsetemplate(gettemplate('stat_body'), $parse), $lang['stat_title'], false);

?>