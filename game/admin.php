<?php

$mode = $_GET['mode'];

switch($mode) {
	case "overview":
		include ("game/admin/overview.php");
		break;
	case "server":
		include ("game/admin/server.php");
		break;
	case "settings":
		include ("game/admin/settings.php");
		break;
	case "userlist":
		include ("game/admin/userlist.php");
		break;
	case "paneladmina":
		include ("game/admin/paneladmina.php");
		break;
	case "planetlist":
		include ("game/admin/planetlist.php");
		break;
	case "activeplanet":
		include ("game/admin/activeplanet.php");
		break;
	case "moonlist":
		include ("game/admin/moonlist.php");
		break;
	case "flyfleettable":
		include ("game/admin/flyfleettable.php");
		break;
	case "alliancelist":
		include ("game/admin/alliancelist.php");
		break;
	case "banned":
		include ("game/admin/banned.php");
		break;
	case "unbanned":
		include ("game/admin/unbanned.php");
		break;
	case "updatestats":
		include ("game/admin/updatestats.php");
		break;
	case "md5changepass":
		include ("game/admin/md5changepass.php");
		break;
	case "messagelist":
		include ("game/admin/messagelist.php");
		break;
	case "messall":
		include ("game/admin/messall.php");
		break;
	case "md5enc":
		include ("game/admin/md5enc.php");
		break;
	case "errors":
		include ("game/admin/errors.php");
		break;		
	default:
		include ("game/admin/overview.php");

}


?>