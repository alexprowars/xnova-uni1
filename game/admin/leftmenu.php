<?php

define('INSIDE'  , true);
define('INSTALL' , false);
define('IN_ADMIN', true);

$ugamela_root_path = './../';
include($ugamela_root_path . 'extension.inc');
include($ugamela_root_path . 'common.'.$phpEx);

includeLang('leftmenu');

   if ($user['authlevel'] == "1") {
      $parse                 = $lang;
      $parse['mf']           = "Hauptframe";
      $parse['dpath']           = $dpath;
      $parse['servername']   = "XNova Game";
      $Page                  = parsetemplate(gettemplate('admin/left_menu_modo'), $parse);
      display( $Page, "Menu", false, '', true);
   }
   elseif ($user['authlevel'] == "2") {
      $parse                 = $lang;
      $parse['mf']           = "Hauptframe";
      $parse['dpath']           = $dpath;
      $parse['servername']   = "XNova Game";
      $Page                  = parsetemplate(gettemplate('admin/left_menu_op'), $parse);
      display( $Page, "Menu", false, '', true);
   }
   elseif ($user['authlevel'] >= "3") {
      $parse                 = $lang;
      $parse['mf']           = "Hauptframe";
      $parse['dpath']           = $dpath;
      $parse['servername']   = "XNova Game";
      $Page                  = parsetemplate(gettemplate('admin/left_menu'), $parse);
      display( $Page, "Menu", false, '', true);
   } else {
		message( $lang['sys_noalloaw'], $lang['sys_noaccess'] );
   }
?>