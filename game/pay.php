<?

if(!defined("INSIDE")) die("attemp hacking");

if (intval($_POST['rur']) < 1) die('�� ����!!!');

doquery("INSERT INTO {{table}} (pay_id, user_id, username, date_start) VALUES (0, ".$user['id'].", '".$user['username']."', ".time().")", "wmrlog");

$id = mysql_insert_id();

$summa = intval($_POST['rur']) * 500;

$page = "<br><br><form method=\"POST\" action=\"https://merchant.webmoney.ru/lmi/payment.asp\" target=\"_blank\">";
$page .= "<table>";
$page .= "<tr><td class=\"c\" colspan=2>������� ������� ��������</td></tr>";
$page .= "<tr><th>����� ������� (���):</th><th><input type=\"hidden\" name=\"LMI_PAYMENT_AMOUNT\" value=\"".intval($_POST['rur'])."\"><b>".intval($_POST['rur'])." �. (".$summa." ��.)</b> (1 ���. = 500 ��������)</th>";
$page .= "<tr><th>���������� � �������</th><th><input type=\"hidden\" name=\"LMI_PAYMENT_DESC\" value=\"������� XNOVA �������� (�����: ".$id.", ������������: ".$user['username'].")\">������� XNOVA ��������</th></tr>";
$page .= "<input type=\"hidden\" name=\"USERNAME\" value = \"".$user['username']."\">";
$page .= "<input type=\"hidden\" name=\"LMI_PAYEE_PURSE\" value=\"????????????\">";
$page .= "<input type=\"hidden\" name=\"LMI_PAYMENT_NO\" value=\"".$id."\">";
$page .= "<tr><td class=\"c\" colspan=2><input type=\"submit\" value=\"��������\"></th></tr>";
$page .= "</form></table>";


display($page, "������ ��������", false);

?>