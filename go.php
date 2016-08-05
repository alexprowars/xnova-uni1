<?
session_start();

define('INSIDE'  , true);
define('INSTALL' , false);

include('includes/mysql.php');

$id = mysql_escape_string($_SERVER['QUERY_STRING']);

if (!is_numeric($id) || strlen($id) == 0) echo"Error!";
else {
        $login = doquery("SELECT `id` FROM {{table}} WHERE `id` = '".$id."'", 'users', true);

        if (!empty($login['id'])) {

              $ip = GetEnv("HTTP_X_REAL_IP");
              $now=time();
		$timeb = $now-86400;

              $res = doquery("SELECT `id` FROM {{table}} where `ip` = '".$ip."' AND `time` > '$timeb'", 'moneys', true);

		if (empty($res['id'])) {

 			doquery("INSERT INTO {{table}} values ('".$login['id']."','$ip','$now','".addslashes($_SERVER['HTTP_REFERER'])."', '".addslashes($_SERVER['HTTP_USER_AGENT'])."')", 'moneys');
			doquery("UPDATE {{table}} set links=links+1 where id='".$login['id']."'", 'users');

		}
		$_SESSION['ref'] = $login['id'];
        }

        $host=GetEnv("HTTP_HOST");

        print "<script>top.location='http://uni1.xnova.su/?set=reg'</script>";

}
?>