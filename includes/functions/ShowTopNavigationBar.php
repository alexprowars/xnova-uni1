<?php

function ShowTopNavigationBar ( $CurrentUser, $CurrentPlanet ) {
	global $lang, $_GET;

	if ($CurrentUser) {

		if ($CurrentUser['design'] == 1)
			$NavigationTPL       = gettemplate('topnav');
		else
			$NavigationTPL       = gettemplate('topnav_lite');

		$dpath               = (!$CurrentUser["dpath"]) ? DEFAULT_SKINPATH : $CurrentUser["dpath"];
		$parse               = $lang;
		$parse['dpath']      = $dpath;
		$parse['image']      = $CurrentPlanet['image'];
		$parse['time']		 = time();

		$parse['planetlist'] = '';
		$ThisUsersPlanets    = SortUserPlanets ( $CurrentUser );
		while ($CurPlanet = mysql_fetch_assoc($ThisUsersPlanets)) {
			if ($CurPlanet['destruyed'] == 0) {
				$parse['planetlist'] .= "\n<option ";
				if ($CurPlanet['id'] == $CurrentUser['current_planet']) {

					$parse['planetlist'] .= "selected=\"selected\" ";
				}
				$parse['planetlist'] .= "value=\"?set=".$_GET['set']."";
				if (isset($_GET['mode']))
					$parse['planetlist'] .= "&amp;mode=".$_GET['mode'];
					
				$parse['planetlist'] .= "&amp;cp=".$CurPlanet['id']."&amp;re=0\">";


				$parse['planetlist'] .= "".$CurPlanet['name'];
				$parse['planetlist'] .= "&nbsp;[".$CurPlanet['galaxy'].":";
				$parse['planetlist'] .= "".$CurPlanet['system'].":";
				$parse['planetlist'] .= "".$CurPlanet['planet'];
				$parse['planetlist'] .= "]&nbsp;&nbsp;</option>";
			}
		}
		

		$metal = round($CurrentPlanet["metal"]);
		$parse['metal'] = $metal;

		$crystal = round($CurrentPlanet["crystal"]);
		$parse['crystal'] = $crystal;

		$deuterium = round($CurrentPlanet["deuterium"]);
		$parse['deuterium'] = $deuterium;

		$energy_max= pretty_number($CurrentPlanet["energy_max"]);
		if (($CurrentPlanet["energy_max"] > $CurrentPlanet["energy_max"])) {
			$parse['energy_max'] = colorRed($energy_max);
		} else {
			$parse['energy_max'] = $energy_max;
		}

		$parse['energy_total'] = colorNumber(pretty_number($CurrentPlanet['energy_max'] + $CurrentPlanet["energy_used"]));

		if ($CurrentPlanet["metal_max"] < $CurrentPlanet["metal"]) {
			$parse['metal_max'] = '<font color="#ff0000">';
		} else {
			$parse['metal_max'] = '<font color="#00ff00">';
		}
		$parse['metal_m'] = $CurrentPlanet["metal_max"];
		$parse['metal_pm'] = $CurrentPlanet["metal_perhour"] / 3600;
		$parse['metal_mp'] = $CurrentPlanet['metal_mine_porcent']*10;
		$parse['metal_ph'] = pretty_number($CurrentPlanet["metal_perhour"]);
		$parse['metal_pd'] = pretty_number($CurrentPlanet["metal_perhour"] * 24);

		$parse['metal_max'] .= pretty_number($CurrentPlanet["metal_max"])."</font>";


		if ($CurrentPlanet["crystal_max"] < $CurrentPlanet["crystal"]) {
			$parse['crystal_max'] = '<font color="#ff0000">';
		} else {
			$parse['crystal_max'] = '<font color="#00ff00">';
		}
		$parse['crystal_m'] = $CurrentPlanet["crystal_max"];
		$parse['crystal_pm'] = $CurrentPlanet["crystal_perhour"] / 3600;
		$parse['crystal_mp'] = $CurrentPlanet['crystal_mine_porcent']*10;
		$parse['crystal_ph'] = pretty_number($CurrentPlanet["crystal_perhour"]);
		$parse['crystal_pd'] = pretty_number($CurrentPlanet["crystal_perhour"] * 24);
		$parse['crystal_max'] .= pretty_number($CurrentPlanet["crystal_max"])."</font>";


		if ($CurrentPlanet["deuterium_max"] < $CurrentPlanet["deuterium"]) {
			$parse['deuterium_max'] = '<font color="#ff0000">';
		} else {
			$parse['deuterium_max'] = '<font color="#00ff00">';
		}
		$parse['deuterium_m'] = $CurrentPlanet["deuterium_max"];
		$parse['deuterium_pm'] = $CurrentPlanet["deuterium_perhour"] / 3600;
		$parse['deuterium_mp'] = $CurrentPlanet['deuterium_sintetizer_porcent']*10;
		$parse['deuterium_ph'] = pretty_number($CurrentPlanet["deuterium_perhour"]);
		$parse['deuterium_pd'] = pretty_number($CurrentPlanet["deuterium_perhour"] * 24);
		$parse['deuterium_max'] .= pretty_number($CurrentPlanet["deuterium_max"])."</font>";
		
		$now = time();
		$parse['credits'] = pretty_number($CurrentUser['credits']);

		
		if ($CurrentUser['design'] == 1) {
			if ($CurrentUser['rpg_admiral'] > $now){
				$parse['admiral_ikon'] = "_ikon";
				$parse['admiral'] = "<br>Истекает:</font><br><font color=\'lime\'>".date("d.m.Y H:i", $CurrentUser['rpg_admiral'])."</font>";
			} else {
				$parse['admiral_ikon'] = "";
				$parse['admiral'] = "</font><br><font color=\'lime\'>Нанять</font>";
			}

			if ($CurrentUser['rpg_ingenieur'] > $now){ 
				$parse['ingenieur_ikon'] = "_ikon";
				$parse['ingenieur'] = "<br>Истекает:</font><br><font color=\'lime\'>".date("d.m.Y H:i", $CurrentUser['rpg_ingenieur'])."</font>";
			} else { 
				$parse['ingenieur_ikon'] = "";
				$parse['ingenieur'] = "</font><br><font color=\'lime\'>Нанять</font>";
			}

			if ($CurrentUser['rpg_geologue'] > $now){
				$parse['geologe_ikon'] = "_ikon";
				$parse['geologe'] = "<br>Истекает:</font><br><font color=\'lime\'>".date("d.m.Y H:i", $CurrentUser['rpg_geologue'])."</font>";
			} else { 
				$parse['geologe_ikon'] = "";
				$parse['geologe'] = "</font><br><font color=\'lime\'>Нанять</font>";
			}

			if ($CurrentUser['rpg_technocrate'] > $now){ 
				$parse['technokrat_ikon'] = "_ikon";
				$parse['technokrat'] = "<br>Истекает:</font><br><font color=\'lime\'>".date("d.m.Y H:i", $CurrentUser['rpg_technocrate'])."</font>";
			} else { 
				$parse['technokrat_ikon'] = "";
				$parse['technokrat'] = "</font><br><font color=\'lime\'>Нанять</font>";
			}

			if ($CurrentUser['rpg_constructeur'] > $now){
				$parse['architector_ikon'] = "_ikon";
				$parse['architector'] = "<br>Истекает:</font><br><font color=\'lime\'>".date("d.m.Y H:i", $CurrentUser['rpg_constructeur'])."</font>";
			} else {
				$parse['architector_ikon'] = "";
				$parse['architector'] = "</font><br><font color=\'lime\'>Нанять</font>";
			}

			if ($CurrentUser['rpg_meta'] > $now){
				$parse['meta_ikon'] = "_ikon";
				$parse['rpgmeta'] = "<br>Истекает:</font><br><font color=\'lime\'>".date("d.m.Y H:i", $CurrentUser['rpg_meta'])."</font>";
			} else {
				$parse['meta_ikon'] = "";
				$parse['rpgmeta'] = "</font><br><font color=\'lime\'>Нанять</font>";
			}

			if ($CurrentUser['rpg_komandir'] > $now){
				$parse['komandir_ikon'] = "_ikon";
				$parse['komandir'] = "<br>Истекает:</font><br><font color=\'lime\'>".date("d.m.Y H:i", $CurrentUser['rpg_komandir'])."</font>";
			} else {
				$parse['komandir_ikon'] = "";
				$parse['komandir'] = "</font><br><font color=\'lime\'>Нанять</font>";
			}
		}

		$parse['energy_ak'] = round($CurrentPlanet['energy_ak'] / ( 10000 * pow((1.1), $CurrentPlanet['ak_station'])  * $CurrentPlanet['ak_station'] + 1), 2) * 100;

		if ($parse['energy_ak'] == 0) $parse['energy'] = "batt0.png";
		elseif ($parse['energy_ak'] >= 100) $parse['energy'] = "batt100.png";
 		else $parse['energy'] = "batt.php?p=".$parse['energy_ak'];

		$parse['ak'] = round($CurrentPlanet['energy_ak'])." / ".round(10000 * pow((1.1), $CurrentPlanet['ak_station']) * $CurrentPlanet['ak_station']);

		if ($CurrentUser['new_message'] > 0) {
			$parse['message'] = "<a href=\"?set=messages\">[ ". $CurrentUser['new_message'] ." ]</a>";
		} else {
			$parse['message'] = "0";
		}
		if ($CurrentUser['mnl_alliance'] > 0 && $CurrentUser['ally_id'] == 0) {
			doquery("UPDATE {{table}} SET mnl_alliance = 0 WHERE id = ".$CurrentUser['id']."", "users");
			$CurrentUser['mnl_alliance'] = 0;
		}
		if ($CurrentUser['mnl_alliance'] > 0)
			$parse['message'] .= " <a href=\"?set=alliance&mode=circular\">[ ". $CurrentUser['mnl_alliance'] ." ]</a>";

			$TopBar = parsetemplate( $NavigationTPL, $parse);
	                } else {
			$TopBar = "";
		}

	return $TopBar;
}

?>
