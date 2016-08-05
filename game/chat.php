<?php

if(!defined("INSIDE")) die("attemp hacking");

if ($user['id'] == 4075)
	message("Доступ в demo режиме ограничен.", "Ошибка");

	if (isset($_POST["msg"])) {

		$msg_text = iconv('UTF-8', 'CP1251', addslashes($_POST['msg']));
		
		$now = time();
		
		if (!$msg_text)
			die();

		$msg_tmp = $msg_text;

		if (preg_match("/приватно \[(.*?)\]/", $msg_text, $private)) {
	      		$msg_text = str_replace('приватно ['.$private['1'].']',' ', $msg_text);
	    }elseif (preg_match("/для \[(.*?)\]/", $msg_text, $to_login)) {
	      	$msg_text = str_replace('для ['.$to_login['1'].']',' ', $msg_text);
	    }
			

		$msg_text = str_replace('x-n', 'xn', $msg_text);

		# Пишем новое сообщение в файл
		$locked = 1;
		$room_file = "game/chat_log.xd";

		$msg_text = htmlspecialchars($msg_text);
		# Пишем новое сообщение в файл комнаты

		$text = "";
		$chat = file("$room_file");
		$fp = fopen("$room_file", "w");
		if ($locked == 1) {flock($fp,LOCK_EX);}

		foreach ($chat as $messages){

			$mess = split("<>", $messages);
			$tmp = time() - $mess[0];

			if ($tmp < 900)
				$text .= $messages;

			if ($mess[0] == $now) $now++;

		}

		$msg_text = "".$now."<>".$user['username']."<>".$to_login['1']."<>".$private['1']."<>".$msg_text."<>".$user['color']."<>".$user['chat']."<>";

		$msg_text = str_replace('\\\'','\'', $msg_text);
		$msg_text = str_replace('\\\\','\\', $msg_text);
		$msg_text = str_replace('\\&quot;','&quot;', $msg_text);
		$msg_text = trim($msg_text)."\n";


		$text .= $msg_text;

		fwrite($fp,$text);
		if ($locked ==1) {flock($fp,LOCK_UN);}
		fclose($fp);

		mysql_query("INSERT INTO chat_log VALUES (".$now.", '".$user['username']."', '".addslashes($msg_tmp)."')");
		
		die();
	}

	if ($_GET['message_id']) {
		$timemoment=time();
		$time_1h=$timemoment - 3600;

		$now = time();

		$color_massive = array('white','white','navy','blue','0046D5','teal','red','fuchsia','gray','green','maroon','orange','сhocolate','darkkhaki');

		$room_file = "game/chat_log.xd";
		$room_messages = file($room_file);

		$mess_id = intval($_GET['message_id']);
		$mess_id_t = $mess_id;

		foreach ($room_messages as $this_message) {

			$message = split("<>", $this_message);

			$message[4] = eregi_replace("[\n\r]", "", $message[4]);
			$message[4] = nl2br($message[4]);

			$message[4] = "<font color=\"".$color_massive[$message[5]]."\">".$message[4]."</font>";

			if ($message[0] > $mess_id) {

				if ($message[2] <> "") {
					if ($message[1] == $user['username'])
						print "ChatMsg('".date('H:i', $message[0])."','".$message[1]."','<FONT class=player onclick=\'to(\"".$message[2]."\");\'>для [".$message[2]."]</FONT> ".$message[4]."', 0, 1);\n";
					elseif ($message[2] == $user['username'])
						print "ChatMsg('".date('H:i', $message[0])."','".$message[1]."','<FONT class=player onclick=\'to(\"".$message[1]."\");\'>для [".$message[2]."]</FONT> ".$message[4]."', 1, 0);\n";
				} elseif (!empty($message[3]) && ($message[1] == $user['username'] || $message[3] == $user['username'])){

						if ($message[1] == $user['username'])
							print "ChatMsg('".date('H:i', $message[0])."','".$message[1]."','<FONT class=private onclick=\'pp(\"".$message[3]."\");\'>приватно [".$message[3]."]</FONT> ".$message[4]."', 0, 1);\n";
						else
							print "ChatMsg('".date('H:i', $message[0])."','".$message[1]."','<FONT class=private onclick=\'pp(\"".$message[1]."\");\'>приватно [".$message[3]."]</FONT> ".$message[4]."', 1, 0);\n";
				} elseif ($message[3] == "" && $message[2] == "" ) {

						if ($message[1] == $user['username'])
							print "ChatMsg('".date('H:i', $message[0])."','".$message[1]."','".$message[4]."', 0, 1);\n";
						else
							print "ChatMsg('".date('H:i', $message[0])."','".$message[1]."','".$message[4]."', 0, 0);\n";
				}
				$mess_id_t = $message[0];
			}
		}

		$mess_id = $mess_id_t;

		print "NewMessage(".$user['new_message'].", ".$user['mnl_alliance'].");\n";

		print"MsgSent('".$mess_id."');";
		
		die();
	}

	if ($_GET['online']) {

		header('Content-type: text/html; charset=windows-1251');

		$online = doquery("SELECT id, username, authlevel, sex FROM {{table}} WHERE chat = 1 AND onlinetime > ".(time() - 300)." ORDER BY username", "users");

		echo "<table width=100% align=left valign=top>";

		while ($u = mysql_fetch_assoc($online)) {

			echo "<tr><td width=22><a href=\"#\" onclick=\"pp('".$u['username']."')\"><img src=/images/private.gif></a></td><td><a href=\"#\" onclick=\"to('".$u['username']."')\">";

			if ($u['authlevel'] > 0)
				echo"<font color=red>".$u['username']."</font";
			else
				echo $u['username'];

			echo"</a></td><td align=right>";
			
			if ($u['sex'] == "M")
				echo "<img src=/images/male.gif alt=\"МужиГ\" border=0 width=10 height=10>";
			elseif ($u['sex'] == "F")
				echo "<img src=/images/female.gif alt=\"Девушко\" border=0 width=10 height=10>";

			echo"<a href=?set=players&id=".$u['id']." target=\"_blank\"><img src=".$dpath."img/s.gif alt=\"Информация об игроке\" border=0 width=13 height=13></a></td></tr>";

		}

		echo "</table>";
		die();
	}

	$nick = $user['username'];
	$parse = $lang;

	display(parsetemplate(gettemplate('chat_body'), $parse), "Межгалактический чат", false);

?>