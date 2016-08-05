<?php

if(!defined("INSIDE")) die("attemp hacking");

includeLang('news');

$template = gettemplate('news_table');


foreach($lang['news'] as $a => $b)
{

	$parse['Dat'] = $a;
	$parse['News'] = nl2br($b);

	$body .= parsetemplate($template, $parse);

}

$parse = $lang;
$parse['body'] = $body;

if ($user['id'])
	display(parsetemplate(gettemplate('news_body'), $parse), 'Новости', false);
else
	display(parsetemplate(gettemplate('news_body'), $parse), 'Новости', false, false);
	
?>