<?php

function CreateOneMoonRecord ( $Galaxy, $System, $Planet, $Owner, $MoonID, $MoonName, $Chance ) {
	global $lang;

	$QryGetMoonPlanetData  = "SELECT * FROM {{table}} ";
	$QryGetMoonPlanetData .= "WHERE ";
	$QryGetMoonPlanetData .= "`galaxy` = '". $Galaxy ."' AND ";
	$QryGetMoonPlanetData .= "`system` = '". $System ."' AND ";
	$QryGetMoonPlanetData .= "`planet` = '". $Planet ."';";
	$MoonPlanet = doquery ( $QryGetMoonPlanetData, 'planets', true);

	$QryGetMoonGalaxyData  = "SELECT * FROM {{table}} ";
	$QryGetMoonGalaxyData .= "WHERE ";
	$QryGetMoonGalaxyData .= "`galaxy` = '". $Galaxy ."' AND ";
	$QryGetMoonGalaxyData .= "`system` = '". $System ."' AND ";
	$QryGetMoonGalaxyData .= "`planet` = '". $Planet ."';";
	$MoonGalaxy = doquery ( $QryGetMoonGalaxyData, 'galaxy', true);

	if ($MoonGalaxy['id_luna'] == 0 && $MoonPlanet['id'] != 0) {
		$SizeMin		= 2000 + ( $Chance * 100 );
		$SizeMax	= 6000 + ( $Chance * 200 );

		$maxtemp	= $MoonPlanet['temp_max'] - rand(10, 45);
		$mintemp                = $MoonPlanet['temp_min'] - rand(10, 45);
		$size		= rand ($SizeMin, $SizeMax);

		$QryInsertMoonInPlanet  = "INSERT INTO {{table}} SET ";
		$QryInsertMoonInPlanet .= "`name` = '" .$lang['sys_moon'] ."', ";
		$QryInsertMoonInPlanet .= "`id_owner` = '". $Owner ."', ";
		$QryInsertMoonInPlanet .= "`galaxy` = '". $Galaxy ."', ";
		$QryInsertMoonInPlanet .= "`system` = '". $System ."', ";
		$QryInsertMoonInPlanet .= "`planet` = '". $Planet ."', ";
		$QryInsertMoonInPlanet .= "`last_update` = '". time() ."', ";
		$QryInsertMoonInPlanet .= "`planet_type` = '3', ";
		$QryInsertMoonInPlanet .= "`image` = 'mond', ";
		$QryInsertMoonInPlanet .= "`diameter` = '". $size ."', ";
		$QryInsertMoonInPlanet .= "`field_max` = '1', ";
		$QryInsertMoonInPlanet .= "`temp_min` = '". $maxtemp ."', ";
		$QryInsertMoonInPlanet .= "`temp_max` = '". $mintemp ."', ";
		$QryInsertMoonInPlanet .= "`metal` = '0', ";
		$QryInsertMoonInPlanet .= "`crystal` = '0', ";
		$QryInsertMoonInPlanet .= "`deuterium` = '0'; ";
		doquery( $QryInsertMoonInPlanet , 'planets');

			$QryGetMoonId = mysql_insert_id();

		$QryUpdateMoonInGalaxy  = "UPDATE {{table}} SET ";
		$QryUpdateMoonInGalaxy .= "`id_luna` = '".$QryGetMoonId."' ";
		$QryUpdateMoonInGalaxy .= "WHERE ";
		$QryUpdateMoonInGalaxy .= "`galaxy` = '". $Galaxy ."' AND ";
		$QryUpdateMoonInGalaxy .= "`system` = '". $System ."' AND ";
		$QryUpdateMoonInGalaxy .= "`planet` = '". $Planet ."';";
		doquery( $QryUpdateMoonInGalaxy , 'galaxy');
	}
}
?>