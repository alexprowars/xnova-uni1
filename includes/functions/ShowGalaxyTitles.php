<?php

function ShowGalaxyTitles ( $Galaxy, $System ) {

	$Result  = "\n";
	$Result .= "<tr>";
	$Result .= "<td class=c colspan=9>Солнечная система ".$Galaxy.":".$System."</td>";
	$Result .= "</tr><tr>";
	$Result .= "<td class=c>Поз</td>";
	$Result .= "<td class=c>Планета</td>";
	$Result .= "<td class=c>Название</td>";
	$Result .= "<td class=c>Луна</td>";
	$Result .= "<td class=c>Обломки</td>";
	$Result .= "<td class=c>Игрок</td>";
	$Result .= "<td class=c>Альянс</td>";
	$Result .= "<td class=c>Действия</td>";
	$Result .= "</tr>";

	return $Result;
}
?>