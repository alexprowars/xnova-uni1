<?php

function ShowGalaxySelector ( $Galaxy, $System ) {
	global $lang;

	if ($Galaxy > 9) {
		$Galaxy = 9;
	}
	if ($Galaxy < 1) {
		$Galaxy = 1;
	}
	if ($System > 499) {
		$System = 499;
	}
	if ($System < 1) {
		$System = 1;
	}

	$Result  = "<form action=\"?set=galaxy&mode=1\" method=\"post\" id=\"galaxy_form\">";
	$Result .= "<input type=\"hidden\" id=\"auto\" value=\"dr\" >";
	$Result .= "<table border=\"0\">";
	$Result .= "<tr><td>";

	$Result .= "<table><tr>";
	$Result .= "<td class=\"c\" colspan=\"3\">". $lang['Galaxy'] ."</td></tr><tr>";
	$Result .= "<td class=\"l\"><input name=\"galaxyLeft\" value=\"&lt;-\" onclick=\"galaxy_submit('galaxyLeft')\" type=\"button\"></td>";
	$Result .= "<td class=\"l\"><input name=\"galaxy\" value=\"". $Galaxy ."\" size=\"5\" maxlength=\"3\" tabindex=\"1\" type=\"text\"></td>";
	$Result .= "<td class=\"l\"><input name=\"galaxyRight\" value=\"-&gt;\" onclick=\"galaxy_submit('galaxyRight')\" type=\"button\"></td>";
	$Result .= "</tr></table>";

	$Result .= "</td><td>";

	$Result .= "<table><tr>";
	$Result .= "<td class=\"c\" colspan=\"3\">". $lang['Solar_system'] ."</td></tr><tr>";
	$Result .= "<td class=\"l\"><input name=\"systemLeft\" value=\"&lt;-\" onclick=\"galaxy_submit('systemLeft')\" type=\"button\"></td>";
	$Result .= "<td class=\"l\"><input name=\"system\" value=\"". $System ."\" size=\"5\" maxlength=\"3\" tabindex=\"2\" type=\"text\"></td>";
	$Result .= "<td class=\"l\"><input name=\"systemRight\" value=\"-&gt;\" onclick=\"galaxy_submit('systemRight')\" type=\"button\"></td>";
	$Result .= "</tr></table>";

	$Result .= "</td>";
	$Result .= "</tr><tr>";
	$Result .= "<td class=\"l\" colspan=\"2\" align=\"center\"> <input value=\"". $lang['Afficher'] ."\" type=\"submit\"></td>";
	$Result .= "</tr>";
	$Result .= "</table>";
	$Result .= "</form>";

	return $Result;

}
?>