<?php

function DeleteSelectedUser ( $UserID ) {
	global $game_config;
	
	doquery("UPDATE {{table}} SET deltime = 1 WHERE id = ".$UserID.";", "users");

	//$TheUser = doquery ( "SELECT * FROM {{table}} WHERE `id` = '" . $UserID . "';", 'users', true );
	//if ( $TheUser['ally_id'] != 0 ) {
	//	$TheAlly = doquery ( "SELECT * FROM {{table}} WHERE `id` = '" . $TheUser['ally_id'] . "';", 'alliance', true );
	//	$TheAlly['ally_members'] -= 1;
	//	if ( $TheAlly['ally_members'] > 0 ) {
	//		doquery ( "UPDATE {{table}} SET `ally_members` = '" . $TheAlly['ally_members'] . "' WHERE `id` = '" . $TheAlly['id'] . "';", 'alliance' );
	//	} else {
	//		doquery ( "DELETE FROM {{table}} WHERE `id` = '" . $TheAlly['id'] . "';", 'alliance' );
	//		doquery ( "DELETE FROM {{table}} WHERE `stat_type` = '2' AND `id_owner` = '" . $TheAlly['id'] . "';", 'statpoints' );
	//	}
	//}
	//doquery ( "DELETE FROM {{table}} WHERE `stat_type` = '1' AND `id_owner` = '" . $UserID . "';", 'statpoints' );

	//$ThePlanets = doquery ( "SELECT * FROM {{table}} WHERE `id_owner` = '" . $UserID . "';", 'planets' );
	//while ( $OnePlanet = mysql_fetch_assoc ( $ThePlanets ) ) {
	//	doquery ( "DELETE FROM {{table}} WHERE `galaxy` = '" . $OnePlanet['galaxy'] . "' AND `system` = '" . $OnePlanet['system'] . "' AND `planet` = '" . $OnePlanet['planet'] . "';", 'galaxy' );
	//	doquery ( "DELETE FROM {{table}} WHERE `id` = '" . $OnePlanet['id'] . "';", 'planets' );
	//}
	//doquery ( "DELETE FROM {{table}} WHERE `message_sender` = '" . $UserID . "';", 'messages' );
	//doquery ( "DELETE FROM {{table}} WHERE `message_owner` = '" . $UserID . "';", 'messages' );
	//doquery ( "DELETE FROM {{table}} WHERE `owner` = '" . $UserID . "';", 'notes' );
	//doquery ( "DELETE FROM {{table}} WHERE `fleet_owner` = '" . $UserID . "';", 'fleets' );
	//doquery ( "DELETE FROM {{table}} WHERE `id_owner1` = '" . $UserID . "';", 'rw' );
	//doquery ( "DELETE FROM {{table}} WHERE `id_owner2` = '" . $UserID . "';", 'rw' );
	//doquery ( "DELETE FROM {{table}} WHERE `sender` = '" . $UserID . "';", 'buddy' );
	//doquery ( "DELETE FROM {{table}} WHERE `owner` = '" . $UserID . "';", 'buddy' );
	//doquery ( "DELETE FROM {{table}} WHERE `r_id` = '" . $UserID . "' OR `u_id` = '" . $UserID . "';", 'refs' );
	//doquery ( "DELETE FROM {{table}} WHERE `id` = '" . $UserID . "';", 'users' );
	//doquery ( "DELETE FROM {{table}} WHERE `who` = '" . $TheUser['username'] . "';", 'banned' );
	//$Minusowo_Jeden = $game_config['users_amount']-1;
	//doquery( "UPDATE {{table}} SET `config_value`='". $Minusowo_Jeden ."' WHERE `config_name` = 'users_amount';", 'config' );

}
?>