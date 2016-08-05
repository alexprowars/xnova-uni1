<?php

if(!defined("INSIDE")) die("attemp hacking");

if ($user['authlevel'] < 2)
	message($lang['sys_noalloaw'], $lang['sys_noaccess']);
	
if ($_POST && $_GET['modes'] == "change") { 			
		if ($user['authlevel'] == 3) { 				
			$kolor = 'yellow'; 				
			$ranga = 'Администратор'; 			
		} elseif ($user['authlevel'] == 1) { 				
			$kolor = 'skyblue'; 				
			$ranga = 'Оператор'; 			
		} elseif ($user['authlevel'] == 2) { 				
			$kolor = 'yellow'; 				
			$ranga = 'Супер оператор'; 
		}
	
	if ((isset($_POST["tresc"]) && $_POST["tresc"] != '') && (isset($_POST["temat"]) && $_POST["temat"] != '')) { 
					
					$sq  = doquery("SELECT `id` FROM {{table}}", "users");
	 				$Time    = time(); 				
					$From    = "<font color=\"". $kolor ."\">". $ranga ." ".$user['username']."</font>"; 				
					$Subject = "<font color=\"". $kolor ."\">". $_POST['temat'] ."</font>"; 				
					$Message = "<font color=\"". $kolor ."\"><b>". $_POST['tresc'] ."</b></font>"; 	
				
			while ($u = mysql_fetch_array($sq)) { 
						
			SendSimpleMessage ( $u['id'], $user['id'], $Time, 1, $From, $Subject, $Message); 
			} 
					
			message("<font color=\"lime\">Сообщение успешно отправлено всем игрокам!</font>", "Выполнено", "overview." . $phpEx, 3); 	
			
	} else {
	 		message("<font color=\"red\">Не все поля заполнены!</font>", "Ошибка", "overview." . $phpEx, 3);
	} 		
} else {
 			$parse = $game_config;
 			$parse['dpath'] = $dpath; 			
			$parse['debug'] = ($game_config['debug'] == 1) ? " checked='checked'/":''; 			
			$page .= parsetemplate(gettemplate('admin/messall_body'), $parse); 			
			display($page, '', false, true, true);
} 	

?>