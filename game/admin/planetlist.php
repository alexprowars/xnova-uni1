<?php

if(!defined("INSIDE")) die("attemp hacking");

if ($user['authlevel'] >= "2") {

	$parse = $lang;
	$query = doquery("SELECT `id`, `name`, `galaxy`, `system`, `planet` FROM {{table}} WHERE planet_type='1' ORDER by id", "planets");
	$i = 0;
	while ($u = mysql_fetch_array($query)) {
		$parse['planetes'] .= "<tr>"
		. "<td class=b><center><b>" . $u[0] . "</center></b></td>"
		. "<td class=b><center><b>" . $u[1] . "</center></b></td>"
		. "<td class=b><center><b>" . $u[2] . "</center></b></td>"
		. "<td class=b><center><b>" . $u[3] . "</center></b></td>"
		. "<td class=b><center><b>" . $u[4] . "</center></b></td>"
		. "</tr>";
		$i++;
	}

	if ($i == "1")
		$parse['planetes'] .= "<tr><th class=b colspan=5>В игре одна планета</th></tr>";
	else
		$parse['planetes'] .= "<tr><th class=b colspan=5>В игре {$i} планеты</th></tr>";

	display(parsetemplate(gettemplate('admin/planetlist_body'), $parse), 'Список планет', false, true, true);
} else {
	message($lang['sys_noalloaw'], $lang['sys_noaccess']);
}
?>