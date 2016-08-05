<?

if(!defined("INSIDE")) die("attemp hacking");

$dbsettings = Array(
				"server"     => "localhost", // MySQL server name.
				"user"       => "", // MySQL username.
				"pass"       => "", // MySQL password.
				"name"       => "", // MySQL database name.
				"prefix"     => "game_", // Tables prefix.
				"secretword" => "XNova119235469"
);

function doquery($query, $table, $fetch = false){
  	global $numqueries, $link, $debug, $game_config, $dbsettings;

	if(!$link){
		$link = mysql_pconnect($dbsettings["server"], $dbsettings["user"], $dbsettings["pass"]) or $debug->error(mysql_error()."<br />$query","SQL Error");

		mysql_select_db($dbsettings["name"]) or $debug->error(mysql_error()."<br />$query","SQL Error");
		mysql_query("SET NAMES cp1251");
		echo mysql_error();
	}
	if($game_config['debug'] == 1) {
		$mtime        = microtime();
		$mtime        = explode(" ", $mtime);
		$mtime        = $mtime[1] + $mtime[0];
		$starttime    = $mtime;
	}

	$sql = str_replace("{{table}}", $dbsettings["prefix"].$table, $query);
	$sqlquery = mysql_query($sql) or $debug->error(mysql_error()."<br />$sql<br />","SQL Error");

	if($game_config['debug'] == 1) {
     		$mtime        = microtime();
     		$mtime        = explode(" ", $mtime);
     		$mtime        = $mtime[1] + $mtime[0];
     		$endtime      = $mtime;
     		$totaltime    = round((($endtime - $starttime)*1000), 2);
	}

	unset($dbsettings);
	$numqueries++;

	if($game_config['debug'] == 1) {
  		$arr = debug_backtrace();
  		$file = end(explode('/',$arr[1]['file']));
  		$line = $arr[1]['line'];
		$debug->add("<tr><th>Query ".$numqueries.": </th><th>".htmlspecialchars($query)."</th><th>$file($line)</th><th>$table</th><th>$fetch</th><th>$totaltime мс</th></tr>");
	}

	if ($fetch){
		$sqlrow = mysql_fetch_assoc($sqlquery);
		return $sqlrow;
	}else{
		return $sqlquery;
	}
}
?>