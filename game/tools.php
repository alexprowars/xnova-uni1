<?

if(!defined("INSIDE")) die("attemp hacking");

if ($_GET['a'] == '1')
	$ToolsTpl = gettemplate('tools_lune');
else
	$ToolsTpl = gettemplate('tools');

$page = parsetemplate( $ToolsTpl, $parse );
display($page, "Полезные утилиты", false);

?>