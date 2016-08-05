<?php

if(!defined("INSIDE")) die("attemp hacking");

includeLang('banned');

$parse = $lang;
$parse['dpath'] = $dpath;
$parse['mf'] = $mf;


$query = doquery("SELECT * FROM {{table}} ORDER BY `id`;",'banned');
$i = 0;
while($u = mysql_fetch_assoc($query)){
	$parse['banned'] .=
    "<tr><td class=b><center><b>".$u['who']."</center></td></b>".
	"<td class=b><center><small>".date("d/m/Y H:m:s",$u['time'])."</small></center></td>".
	"<td class=b><center><small>".date("d/m/Y H:m:s",$u['longer'])."</small></center></td>".
	"<td class=b><center><b>".$u['theme']."</center></b></td>".
	"<td class=b><center><b>".$u['author']."</center></b></td></tr>";
	$i++;
}

if ($i=="0")
 $parse['banned'] .= "<tr><th class=b colspan=5>Нет заблокированных игроков</th></tr>";
else
  $parse['banned'] .= "<tr><th class=b colspan=5>Всего {$i} аккаунтов заблокировано</th></tr>";

if ($user['id'])
	display(parsetemplate(gettemplate('banned_body'), $parse),'Список заблокированных игроков', false);
else
	display(parsetemplate(gettemplate('banned_body'), $parse),'Список заблокированных игроков', false, false);

?>