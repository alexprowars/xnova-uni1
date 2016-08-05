<?php


if(!defined("INSIDE")) die("attemp hacking");

includeLang('imperium');

$planetsrow = doquery("SELECT * FROM {{table}} WHERE `id_owner` = '".$user['id']."';",'planets');

$planet = array();
$parse  = $lang;

while ($p = mysql_fetch_array($planetsrow)) {
	$planet[] = $p;
}

$parse['mount'] = count($planet) + 3;
$parse['mount1'] = $parse['mount'] - 1;

foreach ($planet as $p) {

	PlanetResourceUpdate ( $user, $p, time(), true );

	$parse['file_images'] .= '<th width=75><a href="?set=overview&cp=' . $p['id'] . '&amp;re=0"><img src="' . $dpath . 'planeten/small/s_' . $p['image'] . '.jpg" border="0" height="75" width="75"></a></th>';
	$parse['file_names'] .= "<th>".$p['name']."</th>";
	$parse['file_coordinates'] .= "<th>[<a href=\"?set=galaxy&mode=3&galaxy={$p['galaxy']}&system={$p['system']}\">{$p['galaxy']}:{$p['system']}:{$p['planet']}</a>]</th>";
	$parse['file_fields'] .= '<th>'.$p['field_current'] . '/' . $p['field_max'].'</th>';
	$parse['file_metal'] .= '<th>'. pretty_number($p['metal']) .'</th>';
	$parse['file_crystal'] .= '<th>'. pretty_number($p['crystal']) .'</th>';
	$parse['file_deuterium'] .= '<th>'. pretty_number($p['deuterium']) .'</th>';
	$parse['file_zar'] .= '<th><font color="#00ff00">100</font>%</th>';

	$parse['file_fields_c'] += $p['field_current'];
	$parse['file_fields_t'] += $p['field_max'];
	$parse['file_metal_t'] += $p['metal'];
	$parse['file_crystal_t'] += $p['crystal'];
	$parse['file_deuterium_t'] += $p['deuterium'];
	$parse['file_energy_t'] += $p['energy_max'] - $p['energy_used'];

	$parse['file_metal_ph'] .= '<th>'.pretty_number($p['metal_perhour']).'</th>';
	$parse['file_crystal_ph'] .= '<th>'.pretty_number($p['crystal_perhour']).'</th>';
	$parse['file_deuterium_ph'] .= '<th>'.pretty_number($p['deuterium_perhour']).'</th>';

	$parse['file_metal_ph_t'] += $p['metal_perhour'];
	$parse['file_crystal_ph_t'] += $p['crystal_perhour'];
	$parse['file_deuterium_ph_t'] += $p['deuterium_perhour'];

	$parse['file_metal_p'] .= '<th><font color="#00FF00">'.($p['metal_mine_porcent']*10).'</font>%</th>';
	$parse['file_crystal_p'] .= '<th><font color="#00FF00">'.($p['crystal_mine_porcent']*10).'</font>%</th>';
	$parse['file_deuterium_p'] .= '<th><font color="#00FF00">'.($p['deuterium_sintetizer_porcent']*10).'</font>%</th>';
	$parse['file_solar_p'] .= '<th><font color="#00FF00">'.($p['solar_plant_porcent']*10).'</font>%</th>';
	$parse['file_fusion_p'] .= '<th><font color="#00FF00">'.($p['fusion_plant_porcent']*10).'</font>%</th>';
	$parse['file_solar2_p'] .= '<th><font color="#00FF00">'.($p['solar_satelit_porcent']*10).'</font>%</th>';

	foreach ($resource as $i => $res) {
		if (in_array($i, $reslist['build'])){
			$r[$i] .= ($p[$resource[$i]]    == 0) ? '<th>-</th>' : "<th>{$p[$resource[$i]]}</th>";
			if ($r1[$i] < $p[$resource[$i]])
				$r1[$i] = $p[$resource[$i]];
		}elseif (in_array($i, $reslist['tech'])){
			$r[$i] = "{$user[$resource[$i]]}";
		}elseif (in_array($i, $reslist['fleet'])){
			$r[$i] .= ($p[$resource[$i]]    == 0) ? '<th>-</th>' : "<th>{$p[$resource[$i]]}</th>";
			$r1[$i] += $p[$resource[$i]];
		}elseif (in_array($i, $reslist['defense'])){
			$r[$i] .= ($p[$resource[$i]]    == 0) ? '<th>-</th>' : "<th>{$p[$resource[$i]]}</th>";
			$r1[$i] += $p[$resource[$i]];
		}
	}
}
	$parse['file_metal_t'] = pretty_number($parse['file_metal_t']);
	$parse['file_crystal_t'] = pretty_number($parse['file_crystal_t']);
	$parse['file_deuterium_t'] = pretty_number($parse['file_deuterium_t']);
	$parse['file_energy_t'] = pretty_number($parse['file_energy_t']);

	$parse['file_metal_ph_t'] = pretty_number($parse['file_metal_ph_t']);
	$parse['file_crystal_ph_t'] = pretty_number($parse['file_crystal_ph_t']);
	$parse['file_deuterium_ph_t'] = pretty_number($parse['file_deuterium_ph_t']);

	$parse['file_kredits'] = pretty_number($user['credits']);

foreach ($reslist['build'] as $a => $i) {
	$parse['building_row'] .= "<tr><th colspan=\"2\">".$lang['tech'][$i]."</th>".$r[$i]."<th>".$planetrow[$resource[$i]]." (".$r1[$i].")</th></tr>";
}

foreach ($reslist['fleet'] as $a => $i) {
	$parse['fleet_row'] .= "<tr><th colspan=\"2\">".$lang['tech'][$i]."</th>".$r[$i]."<th>".$r1[$i]."</th></tr>";
}

foreach ($reslist['defense'] as $a => $i) {
	$parse['defense_row'] .= "<tr><th colspan=\"2\">".$lang['tech'][$i]."</th>".$r[$i]."<th>".$r1[$i]."</th></tr>";
}

foreach ($reslist['tech'] as $a => $i) {
	$parse['technology_row'] .= "<tr><th colspan=\"".($parse['mount']-1)."\">".$lang['tech'][$i]."</th><th><font color=#FFFF00>".$r[$i]. "</font></th></tr>";
}

	display(parsetemplate(gettemplate('imperium_table'), $parse), 'Империя', false);

?>