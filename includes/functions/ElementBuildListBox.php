<?php

function ElementBuildListBox ( $CurrentUser, $CurrentPlanet ) {
	global $lang, $pricelist;

	$ElementQueue = explode(';', $CurrentPlanet['b_hangar_id']);
	$NbrePerType  = "";
	$NamePerType  = "";
	$TimePerType  = "";

	foreach($ElementQueue as $ElementLine => $Element) {
		if ($Element != '') {
			$Element = explode(',', $Element);
			$ElementTime  = GetBuildingTime( $CurrentUser, $CurrentPlanet, $Element[0] );
			$QueueTime   += $ElementTime * $Element[1];
			$TimePerType .= "".$ElementTime.",";
			$NamePerType .= "'". html_entity_decode($lang['tech'][$Element[0]]) ."',";
			$NbrePerType .= "".$Element[1].",";
		}
	}

	$parse = $lang;
	$parse['a'] = $NbrePerType;
	$parse['b'] = $NamePerType;
	$parse['c'] = $TimePerType;
	$parse['b_hangar_id_plus'] = $CurrentPlanet['b_hangar'];

	$parse['pretty_time_b_hangar'] = pretty_time($QueueTime - $CurrentPlanet['b_hangar']);

	$text .= parsetemplate(gettemplate('buildings_script'), $parse);

	return $text;
}
?>