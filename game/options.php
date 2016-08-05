<?php

if(!defined("INSIDE")) die("attemp hacking");

includeLang('options');

if ($user['id'] == 4075)
   message("Äocòyï â demo peæèìe oãpaíè÷eí.", "Oøèáêa");
   
$inf = doquery("SELECT password, email, email_2 FROM {{table}} WHERE id = ".$user['id']."", "users_inf", true);

$mode = $_GET['mode'];

if ($_POST && $mode == "change") {

	if (isset($_POST["design"]) && $_POST["design"] == 'on') {
		$design = 1;
	} else {
		$design = 0;
	}
	if (isset($_POST["security"]) && $_POST["security"] == 'on') {
		$security = 1;
	} else {
		$security = 0;
	}
	if (isset($_POST["db_character"]) && $_POST["db_character"] != '' && $_POST["db_character"] != $user['username']) {
		$_POST["db_character"] = preg_replace("/([\s\x{0}\x{0B}]+)/i", " ", trim($_POST["db_character"]));
		
		if (preg_match("/^[À-ßà-ÿ¨¸a-zA-Z0-9_\-\!\~\.@ ]+$/", $_POST['db_character']))
			$username = addslashes($_POST['db_character']);
		else
			$username = $user['username'];
	} else {
		$username = $user['username'];
	}
	if (isset($_POST["db_email"]) && $_POST["db_email"] != '' && $_POST["db_email"] != $inf['email'] && preg_match("/^[_\.0-9a-z-]{1,}@[_\.0-9a-z-]{1,}\.[_\.0-9a-z-]{2,}$/", $_POST['db_email'])) {
		$db_email = htmlspecialchars(addslashes(strtolower($_POST['db_email'])));
	} else {
		$db_email = $inf['email'];
	}
	if (isset($_POST["icq"]) && $_POST["icq"] != '') {
		$icq = intval($_POST['icq']);
	} else {
		$icq = $user['icq'];
	}
	if (isset($_POST["vkontakte"]) && $_POST["vkontakte"] != '') {
		$vkontakte = intval($_POST['vkontakte']);
	} else {
		$vkontakte = $user['vkontakte'];
	}

	$color = intval($_POST['color']);
	if ($color < 1 || $color > 13) $color = 1;

	if($user['urlaubs_modus_time'] > time()) {
		$urlaubs_modus_time = $user['urlaubs_modus_time'];
	}else{
		if (isset($_POST["urlaubs_modus"]) && $_POST["urlaubs_modus"] == 'on') {
			$BuildOnPlanet = doquery("SELECT `id` FROM {{table}} WHERE (`b_building` != 0 OR `b_tech` != 0 OR `b_hangar_id` != '') AND `id_owner` = '".$user['id']."'", "planets");
			$UserFlyingFleets = doquery("SELECT `fleet_id` FROM {{table}} WHERE `fleet_owner` = '".$user['id']."'", "fleets");
			if (mysql_num_rows($BuildOnPlanet) > 0){
				message('Heâoçìoæío âêëş÷èòü peæèì oòïycêa. Äëÿ âêëş÷eíèÿ y âac íe äoëæío èäòè còpoèòeëücòâo èëè èccëeäoâaíèe ía ïëaíeòe.', "Oøèáêa", "?set=overview", 5);
			} elseif (mysql_num_rows($UserFlyingFleets) > 0) {
				message('Heâoçìoæío âêëş÷èòü peæèì oòïycêa. Äëÿ âêëş÷eíèÿ y âac íe äoëæeí íaxoäèòücÿ ôëoò â ïoëeòe.', "Oøèáêa", "?set=overview", 5);
			}else{
				if($user['urlaubs_modus_time'] == 0){
					$urlaubs_modus_time = time() + 172800;
				}else{
					$urlaubs_modus_time = $user['urlaubs_modus_time'];
				}
				doquery("UPDATE {{table}} SET `metal_mine_porcent` = '0', `crystal_mine_porcent` = '0', `deuterium_sintetizer_porcent` = '0', `solar_plant_porcent` = '0', `fusion_plant_porcent` = '0', `solar_satelit_porcent` = '0' WHERE `id_owner` = '".$user['id']."'", "planets");
			}
		} else {
			$urlaubs_modus_time = 0;
		}
	}

   if (isset($_POST["db_deaktjava"]) && $_POST["db_deaktjava"] == 'on') {
      $Del_Time = time() + 604800;
   } else {
      $Del_Time = 0;
   }
   $SetSort  = intval($_POST['settings_sort']);
   $SetOrder = intval($_POST['settings_order']);

   if ($user['urlaubs_modus_time'] == 0){
      doquery("UPDATE {{table}} SET `design` = '".$design."', `security` = '".$security."', `icq` = '".$icq."', `vkontakte` = '".$vkontakte."', `planet_sort` = '".$SetSort."', `planet_sort_order` = '".$SetOrder."', `color` = '".$color."', `urlaubs_modus_time` = '".$urlaubs_modus_time."', `deltime` = '".$Del_Time."' WHERE `id` = '".$user['id']."'", "users");
      doquery("UPDATE {{table}} SET email = '".$db_email."' WHERE id = ".$user['id']."", "users_inf");
      
      if ($Del_Time > 0)
         doquery("UPDATE {{table}} SET `del` = 1 WHERE `id_owner` = '".$user['id']."'", "statpoints");
   } else {
      doquery("UPDATE {{table}} SET `urlaubs_modus_time` = '".$urlaubs_modus_time."', `deltime` = '".$Del_Time."' WHERE `id` = '".$user['id']."' LIMIT 1", "users");

      if ($Del_Time > 0)
         doquery("UPDATE {{table}} SET `del` = '".$db_deaktjava."' WHERE `id_owner` = '".$user['id']."'","statpoints");
   }

   if ($_POST["db_password"] != "" && $_POST["newpass1"] != "") {
      if (md5($_POST["db_password"]) != $inf["password"])
         message('Heïpaâèëüíûé òeêyùèé ïapoëü', 'Cìeía ïapoëÿ');
      elseif ($_POST["newpass1"] == $_POST["newpass2"]) {
         $newpass = md5($_POST["newpass1"]);
         doquery("UPDATE {{table}} SET `password` = '".$newpass."' WHERE `id` = '".$user['id']."' LIMIT 1", "users_inf");
         setcookie("".$game_config["COOKIE_NAME"]."", "");
         session_destroy();
         message('Ócïeøío', 'Cìeía ïapoëÿ', '?set=login');
      } else
         message('Bâeäeííûe ïapoëè íe coâïaäaşò', 'Cìeía ïapoëÿ');
   }
   if ($user['username'] != $username) {
      $query = doquery("SELECT id FROM {{table}} WHERE username='{$username}'", 'users');
      if (mysql_num_rows($query) == 0) {
         if (eregi("^[a-zA-Za-ÿA-ß0-9_\.\,\-\!\?\*\ ]+$", $username) && strlen($username) >= 4){
            doquery("UPDATE {{table}} SET username='{$username}' WHERE id='{$user['id']}' LIMIT 1", "users");
            setcookie("".$game_config["COOKIE_NAME"]."", "");
            message('Ócïeøío', 'Cìeía èìeíè', '?set=login');
         } else
            message('Äaííoe èìÿ aêêayíòa cëèøêoì êopoòêoe èëè èìeeò çaïpeùeííûe cèìâoëû', 'Cìeía èìeíè');
      } else
         message('Äaííoe èìÿ aêêayíòa yæe ècïoëüçyeòcÿ â èãpe', 'Cìeía èìeíè');
   }
   message($lang['succeful_save'], "Hacòpoéêè èãpû");
} else {
   $parse = $lang;

   $parse['dpath'] = $dpath;

   if ($user['urlaubs_modus_time'] > 0) {

      $parse['um_end_date']       = date("H:m:s d.m.Y", $user['urlaubs_modus_time']);
      $parse['opt_delac_data']    = ($user['deltime'] > 0) ? " checked='checked'/":'';
      $parse['opt_modev_data']    = ($user['urlaubs_modus_time'] > 0)?" checked='checked'/":'';
      $parse['opt_usern_data']    = $user['username'];

      display(parsetemplate(gettemplate('options_um_body'), $parse), 'Hacòpoéêè');
   } else {
      $parse['opt_lst_ord_data']   = "<option value =\"0\"". (($user['planet_sort'] == 0) ? " selected": "") .">". $lang['opt_lst_ord0'] ."</option>";
      $parse['opt_lst_ord_data']  .= "<option value =\"1\"". (($user['planet_sort'] == 1) ? " selected": "") .">". $lang['opt_lst_ord1'] ."</option>";
      $parse['opt_lst_ord_data']  .= "<option value =\"2\"". (($user['planet_sort'] == 2) ? " selected": "") .">". $lang['opt_lst_ord2'] ."</option>";

      $parse['opt_lst_cla_data']   = "<option value =\"0\"". (($user['planet_sort_order'] == 0) ? " selected": "") .">". $lang['opt_lst_cla0'] ."</option>";
      $parse['opt_lst_cla_data']  .= "<option value =\"1\"". (($user['planet_sort_order'] == 1) ? " selected": "") .">". $lang['opt_lst_cla1'] ."</option>";


      $parse['opt_lst_color_data']  .= "<OPTION VALUE=1 STYLE='background:color:White'";
      if ($user[color]==1) $parse['opt_lst_color_data']  .= " selected>"; else $parse['opt_lst_color_data']  .= ">"; $parse['opt_lst_color_data']  .= "Áeëûé";
      $parse['opt_lst_color_data']  .= "<OPTION VALUE=2 STYLE='color:navy'";
      if ($user[color]==2) $parse['opt_lst_color_data']  .= " selected>"; else $parse['opt_lst_color_data']  .= ">"; $parse['opt_lst_color_data']  .= "Teìíocèíèé";
      $parse['opt_lst_color_data']  .= "<OPTION VALUE=3 STYLE='color:blue'";
      if ($user[color]==3) $parse['opt_lst_color_data']  .= " selected>"; else $parse['opt_lst_color_data']  .= ">"; $parse['opt_lst_color_data']  .= "Cèíèé";
      $parse['opt_lst_color_data']  .= "<OPTION VALUE=4 STYLE='color:0046D5'";
      if ($user[color]==4) $parse['opt_lst_color_data']  .= " selected>"; else $parse['opt_lst_color_data']  .= ">"; $parse['opt_lst_color_data']  .= "Ãoëyáoé";
      $parse['opt_lst_color_data']  .= "<OPTION VALUE=5 STYLE='color:teal'";
      if ($user[color]==5) $parse['opt_lst_color_data']  .= " selected>"; else $parse['opt_lst_color_data']  .= ">"; $parse['opt_lst_color_data']  .= "Mopcêoé âoëíû";
      $parse['opt_lst_color_data']  .= "<OPTION VALUE=6 STYLE='color:Red'";
      if ($user[color]==6) $parse['opt_lst_color_data']  .= " selected>"; else $parse['opt_lst_color_data']  .= ">"; $parse['opt_lst_color_data']  .= "Kpacíûé";
      $parse['opt_lst_color_data']  .= "<OPTION VALUE=7 STYLE='color:fuchsia'";
      if ($user[color]==7) $parse['opt_lst_color_data']  .= " selected>"; else $parse['opt_lst_color_data']  .= ">"; $parse['opt_lst_color_data']  .= "Poçoâûé";
      $parse['opt_lst_color_data']  .= "<OPTION VALUE=8 STYLE='color:gray'";
      if ($user[color]==8) $parse['opt_lst_color_data']  .= " selected>"; else $parse['opt_lst_color_data']  .= ">"; $parse['opt_lst_color_data']  .= "Cepûé";
      $parse['opt_lst_color_data']  .= "<OPTION VALUE=9 STYLE='color:green'";
      if ($user[color]==9) $parse['opt_lst_color_data']  .= " selected>"; else $parse['opt_lst_color_data']  .= ">"; $parse['opt_lst_color_data']  .= "Çeëeíûé";
      $parse['opt_lst_color_data']  .= "<OPTION VALUE=10 STYLE='color:maroon'";
      if ($user[color]==10) $parse['opt_lst_color_data']  .= " selected>"; else $parse['opt_lst_color_data']  .= ">"; $parse['opt_lst_color_data']  .= "Teìíoêpacíûé";
      $parse['opt_lst_color_data']  .= "<OPTION VALUE=11 STYLE='color:orange'";
      if ($user[color]==11) $parse['opt_lst_color_data']  .= " selected>"; else $parse['opt_lst_color_data']  .= ">"; $parse['opt_lst_color_data']  .= "Opaíæeâûé";
      $parse['opt_lst_color_data']  .= "<OPTION VALUE=13 STYLE='color:darkkhaki'";
      if ($user[color]==13) $parse['opt_lst_color_data']  .= " selected>"; else $parse['opt_lst_color_data']  .= ">"; $parse['opt_lst_color_data']  .= "Teìíûé xaêè";

      if ($user['avatar'] != 0)
         $parse['avatar'] = "<img src=/images/avatars/".$user['avatar'].".jpg><br>";

      if ($user['icq'] != 0)
         $parse['opt_icq_data'] = $user['icq'];

      if ($user['vkontakte'] != 0)
         $parse['opt_vkontakte_data'] = $user['vkontakte'];
		else 
			$parse['opt_vkontakte_data'] = '';

      $parse['opt_usern_data']    = $user['username'];
      $parse['opt_mail1_data']    = $inf['email'];
      $parse['opt_mail2_data']    = $inf['email_2'];
      $parse['opt_sec_data']      = ($user['security'] == 1) ? " checked='checked'":'';
      $parse['opt_sskin_data']    = ($user['design'] == 1) ? " checked='checked'":'';
      $parse['opt_allyl_data']    = ($user['settings_allylogo'] == 1) ? " checked='checked'/":'';
      $parse['opt_delac_data']    = ($user['deltime'] > 0) ? " checked='checked'/":'';
      $parse['opt_modev_data']    = ($user['urlaubs_modus_time'] > 0)?" checked='checked'/":'';

      display(parsetemplate(gettemplate('options_body'), $parse), 'Hacòpoéêè');
   }
}
?>