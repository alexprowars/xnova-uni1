<?php

if(!defined("INSIDE")) die("attemp hacking");

if ($user['authlevel'] == 3) {
	includeLang('server');
	$PageTpl   = gettemplate("admin/server");
	$parse     = $lang;
	$parse['server_ip'] = $_SERVER['SERVER_ADDR'];
	$parse['serversoft'] = $_SERVER['SERVER_SOFTWARE'];
	$parse['server_os'] = $_ENV['OS'];
	$parse['php'] = phpversion();
	$Page = parsetemplate($PageTpl, $parse);

	display ($Page, $lang['server_information'], false, true, true);
} else {
	message ( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
}
?>