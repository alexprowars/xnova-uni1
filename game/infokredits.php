<?

if(!defined("INSIDE")) die("attemp hacking");

$ToolsTpl = gettemplate('infokredits');

if ($_POST['pin'] != "" && ($_COOKIE['sess'] > (time() - 300)))
	message ( "���������� ����� ��������. ��������� ������� ����� ��������� �����", "������!!!", "infokredits.".$phpEx );

if ($_POST['pin'] != "") {
	if (strlen($_POST['pin']) == 27) {
		$pin = md5 ($_POST['pin']);

		$pins = doquery("SELECT * FROM {{table}} WHERE pin = '".$pin."'", "pins", true);

		if ($pins['pin']) {
			if ($pins['status'] == 1) {
				setcookie("sess", time());
				message ( "�� ����� ����� ��� ��������������� pin ����. ���������� ��������� ������� ����� 5 �����", "������!!!", "infokredits.".$phpEx );
			} else {

				doquery("UPDATE {{table}} SET status = '1' WHERE pin = '".$pin."'", "pins");
				doquery("UPDATE {{table}} SET credits = credits + ".$pins['price']." WHERE id = '".$user['id']."'", "users");
				doquery("INSERT INTO {{table}} VALUES ('".$pin."', '".$user['id']."', '".time()."')", "pin_log");
				$users['credits'] += $pins['price'];

				setcookie("sess", time());
				message ( "�� ������� ������������ pin ��� �� ".$pins['price']." ��������", "���������������!!!", "infokredits.".$phpEx );
			}
		} else {
			setcookie("sess", time());
			message ( "�� ����� ������������ pin ���. ���������� ��������� ������� ����� 5 �����", "������!!!", "infokredits.".$phpEx );
		}

	} else {
		setcookie("sess", time());
		message ( "�� ����� ������������ pin ���. ���������� ��������� ������� ����� 5 �����", "������!!!", "infokredits.".$phpEx );
	}
}

$page = parsetemplate( $ToolsTpl, $parse );
display($page, "������� ��������");

?>