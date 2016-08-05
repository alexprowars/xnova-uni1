<?php

if(!defined("INSIDE")) die("attemp hacking");

includeLang('admin');
$parse = $lang;

	if ($user['authlevel'] >= 3) {

		// Borrar un item de errores
		extract($_GET);
		if (isset($delete)) {
			doquery("DELETE FROM {{table}} WHERE `error_id`=$delete", 'errors');
		} elseif ($deleteall == 'yes') {
			doquery("TRUNCATE TABLE {{table}}", 'errors');
		}

		// Lista de usuarios conectados.
		$query = doquery("SELECT * FROM {{table}}", 'errors');
		$i = 0;
		while ($e = mysql_fetch_array($query)) {
			$i++;
			$parse['errors_list'] .= "
			<tr>
				<th rowspan=2>". $e['error_id'] ."</th>
				<th>". $e['error_type'] ." [<a href=?delete=". $e['error_id'] .">X</a>]</th>
				<th>". $e['error_sender'] ."</th>
				<th>" . date('d/m/Y h:i:s', $e['error_time']) . "</th>
			</tr><tr>
				<td class=b colspan=4 width=500>" . htmlspecialchars($e['error_text']) . "</td>
			</tr>";
		}
		$parse['errors_list'] .= "<tr>
			<th class=b colspan=4>". $i ." ". $lang['adm_er_nbs'] ."</th>
		</tr>";

		display(parsetemplate(gettemplate('admin/errors_body'), $parse), "Bledy", false, true, true);
	} else {
		message( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
	}
?>