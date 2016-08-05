<?

$lang = array();
include('inc/lang.php');

define('INSIDE'  , true);

include('inc/vars.php');

include('inc/MissionCaseAttack.php');

$phpEx = "php";

define('DEFAULT_SKINPATH' , '/skins/default/');

$r = $_POST['r'];
$r = explode("|", $r);

$FleetRow = array();
$FleetRow['fleet_array'] = $fleetrr;
$FleetRow['fleet_start_galaxy'] = 1;
$FleetRow['fleet_start_system'] = 1;
$FleetRow['fleet_start_planet'] = 1;
$FleetRow['fleet_end_galaxy'] = 2;
$FleetRow['fleet_end_system'] = 2;
$FleetRow['fleet_end_planet'] = 2;
$FleetRow['fleet_array'] = $users['0'];

$TargetUser = array();

$TargetUser['username'] = "Игрок 2";

$CurrentUser = array();

$CurrentUser['username'] = "Игрок 1";

print_r($a);

MissionCaseAttack ( $r,  $FleetRow);


?>