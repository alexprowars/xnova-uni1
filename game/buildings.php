<?php

if(!defined("INSIDE")) die("attemp hacking");

if ($user['urlaubs_modus_time'] > 0) {
	message("Нет доступа!");
}

includeLang('buildings');

include('includes/functions/FleetBuildingPage.php');
include('includes/functions/DefensesBuildingPage.php');
include('includes/functions/ResearchBuildingPage.php');
include('includes/functions/BatimentBuildingPage.php');

$IsWorking = HandleTechnologieBuild ( $planetrow, $user );

switch ($_GET['mode']) {
	case 'fleet':
		// --------------------------------------------------------------------------------------------------
		FleetBuildingPage ( $planetrow, $user );
		break;

	case 'research':
		// --------------------------------------------------------------------------------------------------
		ResearchBuildingPage ( $planetrow, $user, $IsWorking['OnWork'], $IsWorking['WorkOn'] );
		break;

	case 'defense':
		// --------------------------------------------------------------------------------------------------
		DefensesBuildingPage ( $planetrow, $user );
		break;

	default:
		// --------------------------------------------------------------------------------------------------
		BatimentBuildingPage ( $planetrow, $user );
		break;
}

?>