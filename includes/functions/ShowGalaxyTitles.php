<?php

function ShowGalaxyTitles ( $Galaxy, $System ) {

	$Result  = "\n";
	$Result .= "<tr>";
	$Result .= "<td class=c colspan=9>��������� ������� ".$Galaxy.":".$System."</td>";
	$Result .= "</tr><tr>";
	$Result .= "<td class=c>���</td>";
	$Result .= "<td class=c>�������</td>";
	$Result .= "<td class=c>��������</td>";
	$Result .= "<td class=c>����</td>";
	$Result .= "<td class=c>�������</td>";
	$Result .= "<td class=c>�����</td>";
	$Result .= "<td class=c>������</td>";
	$Result .= "<td class=c>��������</td>";
	$Result .= "</tr>";

	return $Result;
}
?>