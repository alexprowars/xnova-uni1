<?php

if(!defined("INSIDE")) die("attemp hacking");

$g = intval($_GET['galaxy']);
$s = intval($_GET['system']);
$i = intval($_GET['planet']);
$anz = intval($_POST['SendMI']);
$pziel = $_POST['Target'];

$tempvar1 = (($s - $planetrow['system']) * (-1));
$tempvar2 = ($user['impulse_motor_tech'] * 5) - 1;
$tempvar3 = doquery("SELECT * FROM {{table}} WHERE galaxy = ".$g." AND system = ".$s." AND planet = ".$i." AND planet_type = 1", 'planets', true);

$error = 0;

if ($planetrow['silo'] < 4) {
	$error = 1;
} elseif ($user['impulse_motor_tech'] == 0) {
	$error = 2;
} elseif ($tempvar1 >= $tempvar2 || $g != $planetrow['galaxy']) {
	$error = 3;
} elseif (!isset($tempvar3['id'])) {
	$error = 4;
} elseif ($anz > $planetrow['interplanetary_misil']) {
	$error = 5;
} elseif ((!is_numeric($pziel) && $pziel != "all") OR ($pziel < 0 && $pziel > 7 && $pziel != "all")) {
	$error = 6;
}

if ($error != 0)
	message('�������� � ��� ��� ������� ������������ �����, ��� �� �� ������ ���������� �������� ���������� ����������� ���������, ��� ������� ������������ ������ ��� ��������.', '������ '.$error.'');

$iraks_anzahl = $iraks;

if ($pziel == "all")
	$pziel = 0;
else
	$pziel = intval($pziel);

$select = doquery("SELECT * FROM {{table}} WHERE id = ".$tempvar3['id_owner'], 'users', true);

if ($select['urlaubs_modus_time'] > 0)
	message('����� � ������ �������');
	
if ($user['urlaubs_modus_time'] > 0)
	message('�� � ������ �������');

$verteidiger_panzerung 	= $select['defence_tech'];
$angreifer_waffen 		= $user['military_tech'];

$def = array(
		0 => $planet['misil_launcher'],
		1 => $planet['small_laser'],
		2 => $planet['big_laser'],
		3 => $planet['gauss_canyon'],
		4 => $planet['ionic_canyon'],
		5 => $planet['buster_canyon'],
		6 => $planet['small_protection_shield'],
		7 => $planet['big_protection_shield'],
		8 => $planet['interplanetary_misil'],
		9 => $planet['interceptor_misil'],
);

$lang =	array(
        0 => "�������� ���������",
		1 => "˸���� �����",
		2 => "������ �����",
		3 => "����� ������",
		4 => "������ ������",
		5 => "���������� ������",
		6 => "����� ������� �����",
		7 => "������� ������� �����",
		8 => "������������ ������",
		9 => "������-�����������",
);


$flugzeit = round(((30 + (60 * $tempvar1)) * 2500) / $game_config['game_speed']);


doquery("INSERT INTO {{table}} SET
		`zeit` = '".(time() + $flugzeit)."',
		`galaxy` = '".$g."',
		`system` = '".$s."',
		`planet` = '".$i."',
		`planet_type` = '1',
		`galaxy_angreifer` = '".$planetrow['galaxy']."',
		`system_angreifer` = '".$planetrow['system']."',
		`planet_angreifer` = '".$planetrow['planet']."',
		`planet_angreifer_type` = '1',
		`owner` = '".$user['id']."',
		`zielid` = '".$tempvar3['id_owner']."',
		`anzahl` = '".$anz."',
		`primaer` = '".$pziel."'", 'iraks');


doquery("UPDATE {{table}} SET interplanetary_misil = interplanetary_misil - ".$anz." WHERE id = '".$user['current_planet']."'", 'planets');

$dpath = (!$user["dpath"]) ? DEFAULT_SKINPATH : $user["dpath"];

?>
<html>
<head>
<title>����� ������������� ��������</title>
<link rel="SHORTCUT ICON" href="favicon.ico">
<link rel="stylesheet" type="text/css" href="<?php echo $dpath; ?>formate.css" />
<meta http-equiv="refresh" content="3; URL=?set=galaxy&mode=3&galaxy=<?php echo $g; ?>&system=<?php echo $s; ?>&target=<?php echo $i; ?>">
</head>
<body>
<br><br><br>
<center>
<table border="0">
<tbody><tr>
<td>
<table>
<tbody>
<tr>
<td class="c" colspan="1">����� ������������� ��������</td>
</tr>
<tr>
<td class="l"><?php echo "<b>".$anz."</b> ������������ ������ �������� ��� ����� �������� �������!"; ?>
</tr>
</tbody></table>
</td>
</tr>
</tbody></table>
</form>

</body></html>