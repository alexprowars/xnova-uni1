<?php

function CheckAbandonMoonState (&$lunarow) {

	if ($lunarow['luna_destruyed'] <= time()) {
		doquery("DELETE FROM {{table}} WHERE `id` = {$lunarow['luna_id']}", 'planets');
		doquery("UPDATE {{table}} SET id_luna = '0' WHERE `id_luna` = {$lunarow['luna_id']}", "galaxy");
		$lunarow['id_luna'] = 0;
	}
}

function CheckAbandonPlanetState (&$planet) {
	if ($planet['destruyed'] <= time()) {
		doquery("DELETE FROM {{table}} WHERE id = {$planet['id_planet']}", 'planets');
		doquery("DELETE FROM {{table}} WHERE id_planet = {$planet['id_planet']}", 'galaxy');
	}
}
?>