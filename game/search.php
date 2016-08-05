<?php

if(!defined("INSIDE")) die("attemp hacking");

$searchtext = mysql_escape_string(htmlspecialchars($_POST['searchtext']));
$type = $_POST['type'];

includeLang('search');
$i = 0;

switch($type){
	case "playername":
		$table = gettemplate('search_user_table');
		$row = gettemplate('search_user_row');
		$search = doquery("SELECT * FROM {{table}} WHERE username LIKE '%{$searchtext}%' LIMIT 30;","users");
		break;
	case "planetname":
		$table = gettemplate('search_user_table');
		$row = gettemplate('search_user_row');
		$search = doquery("SELECT * FROM {{table}} WHERE name LIKE '%{$searchtext}%' LIMIT 30",'planets');
		break;
	case "allytag":
		$table = gettemplate('search_ally_table');
		$row = gettemplate('search_ally_row');
		$search = doquery("SELECT * FROM {{table}} WHERE ally_tag LIKE '%{$searchtext}%' LIMIT 30","alliance");
		break;
	case "allyname":
		$table = gettemplate('search_ally_table');
		$row = gettemplate('search_ally_row');
		$search = doquery("SELECT * FROM {{table}} WHERE ally_name LIKE '%{$searchtext}%' LIMIT 30","alliance");
		break;
	default:
		unset($type);
}

if (isset($searchtext) && isset($type)){

	while($r = mysql_fetch_assoc($search)){

		if($type=='playername'||$type=='planetname'){
			$s = $r;

			if ($type == "planetname") {
				$pquery = doquery("SELECT * FROM {{table}} WHERE id = {$s['id_owner']}","users",true);
				$s['planet_name'] = $s['name'];
				$s['username'] = $pquery['username'];
				$s['ally_name'] = ($pquery['ally_name']!='')?"<a href=\"?set=alliance&mode=ainfo&a={$pquery['ally_id']}\">{$pquery['ally_name']}</a>":'';
			} else {
				$pquery = doquery("SELECT name FROM {{table}} WHERE id = {$s['id_planet']}","planets",true);
				$s['planet_name'] = $pquery['name'];
				$s['ally_name'] = ($aquery['ally_name']!='')?"<a href=\"?set=alliance&mode=ainfo&a={$aquery['id']}\">{$aquery['ally_name']}</a>":'';
				$squery = doquery("SELECT total_rank FROM {{table}} WHERE id_owner = {$s['id']} AND stat_type = '1';", "statpoints", true);
			}

			if($s['ally_id'] != 0 && $s['ally_request'] == 0){
				$aquery = doquery("SELECT id, ally_name FROM {{table}} WHERE id = {$s['ally_id']}","alliance",true);
			}else{
				$aquery = array();
			}


			$s['position'] = "<a href=\"?set=stat&range=".$squery['total_rank']."\">".$squery['total_rank']."</a>";
			$s['dpath'] = $dpath;
			$s['coordinated'] = "{$s['galaxy']}:{$s['system']}:{$s['planet']}";
			$s['buddy_request'] = $lang['buddy_request'];
			$s['write_a_messege'] = $lang['write_a_messege'];
			$result_list .= parsetemplate($row, $s);
		} elseif ($type == 'allytag' || $type == 'allyname'){
			$s = $r;

			$s['ally_points'] = pretty_number($s['ally_points']);

			$s['ally_tag'] = "<a href=\"?set=alliance&mode=ainfo&a={$s['id']}\">{$s['ally_tag']}</a>";
			$result_list .= parsetemplate($row, $s);
		}
	}
	if ($result_list != ''){
		$lang['result_list'] = $result_list;
		$search_results = parsetemplate($table, $lang);
	} else{
		$lang['result_list'] = '<tr><th colspan="6">Поиск не дал результатов</th></tr>';
		$search_results = parsetemplate($table, $lang);
	}
}


	$lang['type_playername'] = ($_POST["type"] == "playername") ? " SELECTED" : "";
	$lang['type_planetname'] = ($_POST["type"] == "planetname") ? " SELECTED" : "";
	$lang['type_allytag'] = ($_POST["type"] == "allytag") ? " SELECTED" : "";
	$lang['type_allyname'] = ($_POST["type"] == "allyname") ? " SELECTED" : "";
	$lang['searchtext'] = $searchtext;
	$lang['search_results'] = $search_results;


	display(parsetemplate(gettemplate('search_body'), $lang), $lang['Search']);
?>