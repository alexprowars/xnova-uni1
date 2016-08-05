<?php

function GetMaxConstructibleElements ($Element, $Ressources) {
	global $pricelist, $user;

	if ($pricelist[$Element]['metal'] != 0) {
		$ResType_1_Needed = $pricelist[$Element]['metal'];

		if ($user['rpg_admiral'] > time() && ($Element > 200 && $Element < 300))
			$ResType_1_Needed = round($ResType_1_Needed * 0.9);
		if ($user['rpg_ingenieur'] > time() && ($Element > 400 && $Element < 504))
			$ResType_1_Needed = round($ResType_1_Needed * 0.9);

		$Buildable        = floor($Ressources["metal"] / $ResType_1_Needed);
		$MaxElements      = $Buildable;
	}

	if ($pricelist[$Element]['crystal'] != 0) {
		$ResType_2_Needed = $pricelist[$Element]['crystal'];

		if ($user['rpg_admiral'] > time() && ($Element > 200 && $Element < 300))
			$ResType_2_Needed = round($ResType_2_Needed * 0.9);
		if ($user['rpg_ingenieur'] > time() && ($Element > 400 && $Element < 504))
			$ResType_2_Needed = round($ResType_2_Needed * 0.9);

		$Buildable        = floor($Ressources["crystal"] / $ResType_2_Needed);
	}
	if (!isset($MaxElements)) {
		$MaxElements      = $Buildable;
	} elseif ($MaxElements > $Buildable) {
		$MaxElements      = $Buildable;
	}

	if ($pricelist[$Element]['deuterium'] != 0) {
		$ResType_3_Needed = $pricelist[$Element]['deuterium'];

		if ($user['rpg_admiral'] > time() && ($Element > 200 && $Element < 300))
			$ResType_3_Needed = round($ResType_3_Needed * 0.9);
		if ($user['rpg_ingenieur'] > time() && ($Element > 400 && $Element < 504))
			$ResType_3_Needed = round($ResType_3_Needed * 0.9);

		$Buildable        = floor($Ressources["deuterium"] / $ResType_3_Needed);
	}
	if (!isset($MaxElements)) {
		$MaxElements      = $Buildable;
	} elseif ($MaxElements > $Buildable) {
		$MaxElements      = $Buildable;
	}

	if ($pricelist[$Element]['energy'] != 0) {
		$ResType_4_Needed = $pricelist[$Element]['energy'];
		$Buildable        = floor($Ressources["energy_max"] / $ResType_4_Needed);
	}
	if ($Buildable < 1) {
		$MaxElements      = 0;
	}

	return $MaxElements;
}

?>