<?php

if ( defined('INSIDE') ) {

	// ������ �������� ����
	define('ADMINEMAIL'               , "alexprowars@gmail.com");
	define('GAMEURL'                  , "http://".$_SERVER['HTTP_HOST']."/");
	define('MAX_GALAXY_IN_WORLD'      , 9);
	define('MAX_SYSTEM_IN_GALAXY'     , 499);
	define('MAX_PLANET_IN_SYSTEM'     , 15);
	define('SPY_REPORT_ROW'           , 2);
	define('FIELDS_BY_MOONBASIS_LEVEL', 4);
	define('MAX_PLAYER_PLANETS'       , 9);
	define('MAX_BUILDING_QUEUE_SIZE'  , 1);
	define('MAX_FLEET_OR_DEFS_PER_ROW', 99999);
	define('MAX_OVERFLOW'             , 1.0001);
	define('BASE_STORAGE_SIZE'        , 50000);
	define('BUILD_METAL'              , 500);
	define('BUILD_CRISTAL'            , 500);
	define('BUILD_DEUTERIUM'          , 500);
	define('DEBUG', 1);
	
	// ������ �������� ����
	$game_config   = array();
	// �������� ����
	$game_config['game_name'] = "XNova Game";
	// ��������� �����
	$game_config['COOKIE_NAME'] = "XNova";
	$game_config['secretword'] = "XNova119235469";
	// ��� ������
	$game_config['forum_url'] = "http://xnova.su/forum/";
	// ������ ��������
	$game_config['noobprotection'] = 1;
	$game_config['noobprotectiontime'] = 5;
	$game_config['noobprotectionmulti'] = 5;
	// ���� � ������� (�������)
	$game_config['Fleet_Cdr'] = 30;
	// ������� � ������� (�������)
	$game_config['Defs_Cdr'] = 0;
	// ���� �� ������� �������
	$game_config['initial_fields'] = 163;
	// ����� �������
	$game_config['debug'] = 0;
	// ��
	$game_config['BuildLabWhileRun'] = 0;
	// ������� ������������ �� �������
	$game_config['metal_basic_income'] = 20;
	$game_config['crystal_basic_income'] = 10;
	$game_config['deuterium_basic_income'] = 0;
	$game_config['people_basic_income'] = 5;
	$game_config['energy_basic_income'] = 0;
	// �������� ����
	$game_config['game_speed'] = 5000;
	$game_config['fleet_speed'] = 5000;
	$game_config['resource_multiplier'] = 3;

	$ListCensure = array ( "<", ">", "script", "doquery", "http", "javascript");
} else {
	die("Hacking attempt");
}
?>