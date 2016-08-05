<?php

function IsElementBuyable ($CurrentUser, $CurrentPlanet, $Element, $Incremental = true, $ForDestroy = false) {
	global $pricelist, $resource;

	if ($Incremental) {
		$level  = ($CurrentPlanet[$resource[$Element]]) ? $CurrentPlanet[$resource[$Element]] : $CurrentUser[$resource[$Element]];
	}

	$RetValue = true;
	$array    = array('metal', 'crystal', 'deuterium', 'energy_max');

	foreach ($array as $ResType) {
		if ($pricelist[$Element][$ResType] != 0) {
			if ($Incremental) {
				$cost[$ResType]  = floor($pricelist[$Element][$ResType] * pow($pricelist[$Element]['factor'], $level));
			} else {
				$cost[$ResType]  = floor($pricelist[$Element][$ResType]);

				if ($CurrentUser['rpg_admiral'] > time() && ($Element > 200 && $Element < 300))
					$cost[$ResType] = round($cost[$ResType] * 0.9);
				if ($CurrentUser['rpg_ingenieur'] > time() && ($Element > 400 && $Element < 504))
					$cost[$ResType] = round($cost[$ResType] * 0.9);
			}

			if ($ForDestroy) {
				$cost[$ResType]  = floor($cost[$ResType] / 2);
			}

			if ($cost[$ResType] > $CurrentPlanet[$ResType]) {
				$RetValue = false;
			}
		}
	}
	return $RetValue;
}
?>