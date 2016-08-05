<?php

if (!defined('INSIDE')) {
	die("attemp hacking");
}

$lang['user_level'] = array (
	'0' => 'Игрок',
	'1' => 'Модератор',
	'2' => 'Оператор',
	'3' => 'Администратор',
);

$lang['sys_overview'] 			= "Обзор";
$lang['mod_marchand'] 			= "Обмен";
$lang['sys_moon'] 				= "Луна";
$lang['sys_error'] 				= "Ошибка";
$lang['sys_no_vars'] 			= "Ошибка инициализации переменных, обратитесь к администрации!";
$lang['sys_attacker_lostunits'] 		= "Атакующий потерял %s единиц.";
$lang['sys_defender_lostunits'] 		= "Обороняющийся потерял %s единиц.";
$lang['sys_gcdrunits'] 			= "Теперь на этих пространственных координатах находятся %s %s и %s %s.";
$lang['sys_moonproba'] 			= "Шанс появления луны составляет: %d %% ";
$lang['sys_moonbuilt'] 			= "Благодаря огромной энергии огромные куски металла и кристалла соединяются и образуется новая планета %s [%d:%d:%d] !";
$lang['sys_attack_title']    			= "%s. Произошёл бой между следующими флотами::";
$lang['sys_attack_attacker_pos']	      	= "Атакующий %s [%s:%s:%s]";
$lang['sys_attack_techologies'] 		= "Вооружение: %d %% Щиты: %d %% Броня: %d %% ";
$lang['sys_attack_defender_pos'] 		= "Обороняющийся %s [%s:%s:%s]";
$lang['sys_ship_type'] 			= "Тип";
$lang['sys_ship_count'] 			= "Кол-во";
$lang['sys_ship_weapon'] 			= "Вооружение";
$lang['sys_ship_shield'] 			= "Щиты";
$lang['sys_ship_armour'] 			= "Броня";
$lang['sys_destroyed'] 			= "уничтожен";
$lang['sys_attack_attack_wave'] 		= "Атакующий флот делает: %s выстрела(ов) с общей мощностью %s по обороняющемуся. Щиты обороняющегося поглощают %s выстрелов.";
$lang['sys_attack_defend_wave']		= "Обороняющийся флот делает: %s выстрела(ов) с общей мощностью %s по атакующему. Щиты атакующего поглащают %s выстрелов.";
$lang['sys_attacker_won'] 			= "Атакующий выиграл битву!";
$lang['sys_defender_won'] 			= "Обороняющийся выиграл битву!";
$lang['sys_both_won'] 			= "Бой закончился ничьёй!";
$lang['sys_stealed_ressources'] 		= "Он получает %s металла %s %s кристалла %s и %s дейтерия.";
$lang['sys_rapport_build_time'] 		= "Время генерации страницы %s секунд";
$lang['sys_mess_tower'] 			= "Транспорт";
$lang['sys_mess_attack_report'] 		= "Боевой доклад";
$lang['sys_spy_maretials'] 			= "Шпионский доклад от";
$lang['sys_spy_fleet'] 			= "Флот";
$lang['sys_spy_defenses'] 			= "Оборона";
$lang['sys_mess_qg'] 			= "Командование флотом";
$lang['sys_mess_spy_report'] 		= "Шпионский доклад";
$lang['sys_mess_spy_lostproba'] 		= "Шанс на защиту от шпионажа: %d %% ";
$lang['sys_mess_spy_control'] 		= "Контроль";
$lang['sys_mess_spy_activity'] 		= "Шпионская активность";
$lang['sys_mess_spy_ennemyfleet'] 		= "Чужой флот с планеты";
$lang['sys_mess_spy_seen_at'] 		= "был обнаружен вблизи от планеты";
$lang['sys_mess_spy_destroyed'] 		= "Ваши шпионские зонды были уничтожены!";
$lang['sys_object_arrival'] 			= "Прибытие на планету";
$lang['sys_stay_mess_stay'] 		= "Прибытие флота";
$lang['sys_stay_mess_start'] 		= "Ваш флот достигает планеты ";
$lang['sys_stay_mess_back'] 		= "Ваш флот возвращаеться назад к планете";
$lang['sys_stay_mess_end'] 			= " и привозит следующие виды ресурсов:";
$lang['sys_stay_mess_bend'] 		= " и привозит следующие виды ресурсов:";
$lang['sys_adress_planet'] 			= "[%s:%s:%s]";
$lang['sys_stay_mess_goods'] 		= "%s : %s, %s : %s, %s : %s";
$lang['sys_colo_mess_from'] 		= "Колонизация";
$lang['sys_colo_mess_report'] 		= "Отчёт о колонизации";
$lang['sys_colo_defaultname'] 		= "Колония";
$lang['sys_base_defaultname'] 		= "Военная база";
$lang['sys_colo_arrival'] 			= "Флот достигает координат ";
$lang['sys_colo_maxcolo'] 			= ", к сожелению заселение невозможно, вы не можете иметь больше чем ";
$lang['sys_colo_allisok'] 			= ", и поселенец начинает осваивать новую планету.";
$lang['sys_colo_badpos']  			= ", и поселенцы нашли тайный смысл в постройках вашей Империи. Они решили совершить переворот.";
$lang['sys_colo_notfree'] 			= ", и когда посленцы прибыли на планету, то она уже была заселена. Экспедиция потеряна.";
$lang['sys_colo_planet']  			= " колонизированных планет!";

$lang['sys_base_notfree'] 			= ", и когда военные прибыли на указанное место, то тут они увидели заселённую планету.";
$lang['sys_base_badpos']			= ", строительство базы в данном месте невозможно.";
$lang['sys_base_allisok'] 			= ", и военные начинают строительство базы.";
$lang['sys_base_mess_report'] 		= "Отчёт о колонизации";
$lang['sys_base_mess_from'] 		= "Колонизация";
$lang['sys_base_planet']  			= " военных баз!";

$lang['sys_expe_report'] 			= "Отчёт экспедиции";
$lang['sys_recy_report'] 			= "Системная информация";
$lang['sys_expe_blackholl_1'] 		= "Ваш флот наткнулся на черную дыру и был частично разрушен!";
$lang['sys_expe_blackholl_2'] 		= "Ваш флот наткнулся на черную дыру и был полностью разрушен!";
$lang['sys_expe_nothing_1'] 		= "Твои исследователи были свидетелями зарождения сверхновой звезды. Тоесть ничего существенного.";
$lang['sys_expe_nothing_2'] 		= "Твои исследователи не нашли абсолютно ничего. Ни кораблей, ни ресурсов.";
$lang['sys_expe_found_goods'] 		= "Твои исследователи нашли богатую ресурсами планету!<br>Вы добыли %s %s, %s %s и %s %s";
$lang['sys_expe_found_ships'] 		= "Твои исследователи нашли космические корабли в идеальном состоянии!.<br>Вы смогли взять: ";
$lang['sys_expe_back_home'] 		= "Ваш флот вернулся с экспедиции.";
$lang['sys_mess_transport'] 			= "Транспорт";
$lang['sys_tran_mess_owner'] 		= "Один из ваших флотов достигает планеты %s %s и доставляет %s %s, %s  %s и %s %s.";
$lang['sys_tran_mess_user']  		= "Ваш флот отправленный с планеты %s %s прибыл на %s %s и доставил %s %s, %s  %s и %s %s.";
$lang['sys_stay_mess_user']  		= "Ваш флот отправленный с планеты %s %s прибыл на %s %s и расположился на орбите планеты.";
$lang['sys_mess_fleetback'] 			= "Возвращение";
$lang['sys_tran_mess_back'] 		= "Один из ваших флотов возвращается на планету %s %s.";
$lang['sys_recy_gotten'] 			= "Ваш переработчик собрал %s %s и %s %s из поля обломков.";
$lang['sys_notenough_money'] 		= "У вас недостаточно ресурсов чтобы начать строительство на %s. У вас есть %s %s , %s %s и %s %s. Затраты на строительство состовляют %s %s , %s %s и %s %s.";
$lang['sys_nomore_level'] 			= "Вы пытаетесь разрушить здание которого нету( %s ).";
$lang['sys_buildlist'] 			= "Список построек";
$lang['sys_buildlist_fail'] 			= "нет построек";
$lang['sys_gain'] 				= "Добыча: ";
$lang['sys_perte_attaquant'] 			= "Атакующий потерял";
$lang['sys_perte_defenseur'] 		= "Обороняющийся потерял";
$lang['sys_debris'] 			= "Обломки: ";
$lang['sys_noaccess'] 			= "В доступе отказано";
$lang['sys_noalloaw'] 			= "У вас нет сюда доступа.";

$lang['sys_moon_destruction_report'] = "Рапорт разрушения луны";
$lang['sys_moon_destroyed']          = "Ваши Звёзды Смерти произвели мощную гравитационную волну, которая разрушила луну! ";
$lang['sys_rips_destroyed']          = "Ваши Звёзды Смерти произвели мощную гравитационную волну, но её мощности оказалось не достаточно для уничтожения луны такого размера. Но гравитационная волна отразилась от лунной поверхности и разрушила ваш флот.";
$lang['sys_rips_come_back']          = "Ваши Звёзды Смерти не имеют достаточно энергии, чтобы нанести ущерб этой луне. Ваш флот возвращается не уничтожив луну.";
$lang['sys_chance_moon_destroy']     = "Шанс уничтожения луны: ";
$lang['sys_chance_rips_destroy']     = "Шанс уничтожения ЗС: ";

?>