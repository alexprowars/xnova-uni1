<?php

if(!defined('INSIDE')){ die("attemp hacking");}

class debug {
	var $log, $numqueries;

	function debug(){
		$this->vars = $this->log = '';
		$this->numqueries = 0;
	}

	function add($mes){
		$this->log .= $mes;
		$this->numqueries++;
	}

	function echo_log(){
		echo "<br><table width=100%><tr><td class=k colspan=6><a href=\"#\">Debug Log</a>:</td></tr>".$this->log."</table>";
	}

	function error($message, $title){
		global $link, $game_config, $user;
		
		if($game_config['debug'] == 1){
			echo '<h2>'.$title.'</h2><br><font color=red>'.$message.'</font><br><hr>';
			echo '<table>'.$this->log.'</table>';
		}

		if(!$link) 
			die('Сбой работы.<br><a href=/forum/>Forum</a>');

		doquery("INSERT INTO {{table}} SET `error_sender` = '{$user['id']}' , `error_time` = '".time()."' , `error_type` = '{$title}' , `error_text` = '".mysql_escape_string($message)."';", "errors");
		message("Ошибка SQL обработчика. Добавлена запись в журнал событий.", "Ошибка");
	}
}
?>