<?php

if(!defined("INSIDE")) die("attemp hacking");

if ($user['id'] == 4075)
   message("Äocòyï â demo peæèìe oãpaíè÷eí.", "Oøèáêa");

includeLang('galaxy');

$CurrentPlanet = $planetrow;

$fleetmax      = $user['computer_tech'] + 1;
if ($user['rpg_admiral'] > time()) $fleetmax += 2; 

$CurrentPlID   = $CurrentPlanet['id'];
$CurrentMIP    = $CurrentPlanet['interplanetary_misil'];
$CurrentRC     = $CurrentPlanet['recycler'];
$CurrentSP     = $CurrentPlanet['spy_sonde'];
$HavePhalanx   = $CurrentPlanet['phalanx'];
$CurrentSystem = $CurrentPlanet['system'];
$CurrentGalaxy = $CurrentPlanet['galaxy'];
$CanDestroy    = $CurrentPlanet[$resource[213]] + $CurrentPlanet[$resource[214]];

$maxfleet       = doquery("SELECT `fleet_id` FROM {{table}} WHERE `fleet_owner` = '". $user['id'] ."';", 'fleets');
$maxfleet_count = mysql_num_rows($maxfleet);

$UserPoints    = doquery("SELECT `total_points` FROM {{table}} WHERE `stat_type` = '1' AND `stat_code` = '1' AND `id_owner` = '". $user['id'] ."';", 'statpoints', true);

include('includes/functions/GalaxyCheckFunctions.php');
include('includes/functions/ShowGalaxyRows.php');
include('includes/functions/GetPhalanxRange.php');
include('includes/functions/GetMissileRange.php');
include('includes/functions/ShowGalaxySelector.php');
include('includes/functions/ShowGalaxyMISelector.php');
include('includes/functions/ShowGalaxyTitles.php');
include('includes/functions/GalaxyLegendPopup.php');
include('includes/functions/ShowGalaxyFooter.php');

if (!isset($mode)) {
	if (isset($_GET['mode'])) {
		$mode          = intval($_GET['mode']);
	} else {
		$mode          = 0;
	}
}

if ($mode == 0) {
	$galaxy 	= $CurrentPlanet['galaxy'];
	$system 	= $CurrentPlanet['system'];
	$planet 	= $CurrentPlanet['planet'];
} elseif ($mode == 1) {
	if ($_POST["galaxyLeft"]) {
		if ($_POST["galaxy"] < 1) {
			$galaxy = 1;
		} elseif ($_POST["galaxy"] == 1) {
			$galaxy = 1;
		} else {
			$galaxy = intval($_POST["galaxy"]) - 1;
		}
	} elseif ($_POST["galaxyRight"]) {
		if ($_POST["galaxy"] > MAX_GALAXY_IN_WORLD OR $_POST["galaxyRight"] > MAX_GALAXY_IN_WORLD) {
			$galaxy = MAX_GALAXY_IN_WORLD;
		} elseif ($_POST["galaxy"] == MAX_GALAXY_IN_WORLD) {
			$galaxy = MAX_GALAXY_IN_WORLD;
		} else {
			$galaxy = intval($_POST["galaxy"]) + 1;
		}
	} else {
		if ($_POST["galaxy"] < 1)
			$galaxy = 1;
		elseif ($_POST["galaxy"] > MAX_GALAXY_IN_WORLD)
			$galaxy = MAX_GALAXY_IN_WORLD;
		else
			$galaxy = intval($_POST["galaxy"]);
	}

	if ($_POST["systemLeft"]) {
		if ($_POST["system"] < 1) {
			$system = 1;
		} elseif ($_POST["system"] == 1) {
			$system = 1;
		} else {
			$system = intval($_POST["system"]) - 1;
		}
	} elseif ($_POST["systemRight"]) {
		if ($_POST["system"]      > MAX_SYSTEM_IN_GALAXY OR $_POST["systemRight"] > MAX_SYSTEM_IN_GALAXY) {
			$system = MAX_SYSTEM_IN_GALAXY;
		} elseif ($_POST["system"] == MAX_SYSTEM_IN_GALAXY) {
			$system = MAX_SYSTEM_IN_GALAXY;
		} else {
			$system = intval($_POST["system"]) + 1;
		}
	} else {
		if ($_POST["system"] < 1)
			$system = 1;
		elseif ($_POST["system"] > MAX_SYSTEM_IN_GALAXY)
			$system = MAX_SYSTEM_IN_GALAXY;
		else
			$system = intval($_POST["system"]);
	}
} elseif ($mode == 2) {
	$galaxy 	= intval($_GET['galaxy']);
	$system 	= intval($_GET['system']);
	$planet 	= intval($_GET['planet']);
} elseif ($mode == 3) {
	$galaxy 	= intval($_GET['galaxy']);
	$system 	= intval($_GET['system']);
} else {
	$galaxy 	= 1;
	$system 	= 1;
}

$planetcount = 0;
$lunacount   = 0;

$page = "<div style=\"top: 10px;\" id=\"content\">\n<script language=\"JavaScript\">\n";
$page .= "function galaxy_submit(value) {\n";
$page .= "document.getElementById('auto').name = value;\n";
$page .= "document.getElementById('galaxy_form').submit();\n";
$page .= "}\n";
$page .= "function fenster(target_url,win_name) {\n";
$page .= "var new_win = window.open(target_url,win_name,'resizable=yes,scrollbars=yes,menubar=no,toolbar=no,width=640,height=480,top=0,left=0');\n";
$page .= "new_win.focus();\n";
$page .= "}\n";
$page .= "</script>\n<br>";

$page .= ShowGalaxySelector ( $galaxy, $system );

if ($mode == 2) {
	$CurrentPlanetID = intval($_GET['current']);
	$page .= ShowGalaxyMISelector ( $galaxy, $system, $planet, $CurrentPlanetID, $CurrentMIP );
}

$page .= "<table width=710>";

$page .= ShowGalaxyTitles ( $galaxy, $system );
$page .= ShowGalaxyRows  ( $galaxy, $system );
$page .= ShowGalaxyFooter ( $galaxy, $system );

$page .= "</table></div>";

display ($page, 'Ãàëàêòèêà', false);

?>