<?php

if(!defined("INSIDE")) die("attemp hacking");

includeLang('lostpassword');

$step = intval($_GET['step']);
$login = addslashes($_POST['login']);

function sendnewpassword($id, $key){

	$Lost = doquery("SELECT * FROM {{table}} WHERE ks = '".$key."' AND u_id = '".$id."' AND time > ".time()."-3600 AND activ = 0 LIMIT 1;", 'lostpwd', true);

	if ($Lost['u_id'] != "")
		$Mail = doquery("SELECT u.username, ui.email_2 AS email FROM {{table}}users u, {{table}}users_inf ui WHERE ui.id = u.id AND u.id = '".$Lost['u_id']."'", '', true);
	else
		message('<font color=red size=3>�������� ������ ������ �������, ���������� ������ ��������� ������!</font>', '������!!!', '', 0, false);

	if (!preg_match("/^[�-��-���a-zA-Z0-9]+$/", $key)) {
		message('������ ������� E-mail ������!', '������!!!', '', 0, false);
		} elseif (empty($Mail['email'])) {
		message('������ ������� E-mail ������!', '������!!!', '', 0, false);
	} else {
			$Caracters = "aazertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN1234567890";

			$Count = strlen($Caracters);

			$NewPass = "";
			$Taille = 6;


			srand((double)microtime()*1000000);

			for ($i=0; $i<$Taille; $i++){

				$CaracterBoucle = rand(0,$Count-1);
				$NewPass=$NewPass.substr($Caracters,$CaracterBoucle,1);
			}

		$mailto = $Mail['email'];
		$headers.="Content-Type: text/html; charset=windows-1251\r\n";
		$headers.="From: <alexprowars@gmail.com >\r\n";
		$headers.="X-Mailer: PHP mailer";		
		$body = "��� ����� ������ �� �������� ��������: ".$Mail['username'].": ".$NewPass;
		$body = convert_cyr_string (stripslashes($body),w,w);

		$sucess = mail($mailto, "����� ������ � Xnova Game", $body, $headers);

		$NewPass2 = md5($NewPass);

		$QryPassChange = "UPDATE {{table}} SET ";
		$QryPassChange .= "`password` ='". $NewPass2 ."' ";
		$QryPassChange .= "WHERE `id`='". $id ."' LIMIT 1;";
		doquery( $QryPassChange, 'users_inf');
		doquery("DELETE FROM {{table}} WHERE u_id = '".$id."'", 'lostpwd');

		message('<font color=red size=3>��� ����� ������: '.$NewPass.'. ����� ������ ���������� �� �������� ����!</font>', 'OK', '', 0, false);
	}
}


if ($_GET['id'] != "" && $_GET['passw'] != "") {
	sendnewpassword(intval($_GET['id']), addslashes($_GET['passw']));
} else {
	if ($step == 1 or $step == 0) {
		$parse               = $lang;
		$parse['servername'] = $game_config['game_name'];
		$page .= parsetemplate(gettemplate('lostpassword'), $parse);
		display($page, $lang['system'], false, false);
	} else if ($step == 2) {
		if ($login != "") {
			$inf = doquery("SELECT u.id, u.username, ui.email_2 AS email FROM {{table}}users u, {{table}}users_inf ui WHERE ui.id = u.id AND u.username = '".$login."' LIMIT 1;", '', true);
	
			if ($inf['id'] != "") { 
				$ip = GetEnv("HTTP_X_REAL_IP");
	
				$key = md5($inf['id'].date("d-m-Y H:i:s", time())."���");
				doquery("INSERT INTO {{table}} (u_id, ks, time, ip, activ) VALUES (".$inf['id'].",'".$key."',".time().", '".$ip."',0)", 'lostpwd');
				
				// ���������� ������
				$mailto = $inf['email'];
	
				$headers.="Content-Type: text/html; charset=windows-1251\r\n";
				$headers.="From: <alexprowars@gmail.com >\r\n";
				$headers.="X-Mailer: PHP mailer";
				
				$body = "������� ������� ����� ���!\n��� �� � IP ������ ".$ip." �������� ������ � ��������� ".$inf['username']." � ������-���� Xnova.su.\n��� ��� � ������ � ��������� ������ ������ e-mail, �� ������ �� �������� ��� ������.\n\n
				��� �������������� ������ ��������� �� ������: <a href='http://uni1.xnova.su/?set=lostpassword&id=".$inf['id']."&passw=".$key."'>http://uni1.xnova.su/lostpassword.php?id=".$inf['id']."&passw=".$key."</a>";
				$body = convert_cyr_string (stripslashes($body),w,w);
	
				$sucess = mail($mailto, "�������������� �������� ������", $body, $headers);

				message('������ �� �������������� ������ ���������� �� ��� E-mail', 'OK', '', 0, false);
			}
			else { $step = 1; message('�������� �� ������ � ����', '������', '', 0, false); }
		}
		else $step = 1;
	}
}

?>