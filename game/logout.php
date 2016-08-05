<?php

if(!defined("INSIDE")) die("attemp hacking");

setcookie("x_id", "", 0, "/", "uni1.xnova.su", 0);
setcookie("x_secret", "", 0, "/", "uni1.xnova.su", 0);

setcookie("uni", "", 0, "/", ".xnova.su", 0);	
session_destroy();

message ( 'Выход', 'Сессия закрыта', "http://xnova.su/", 3);


?>