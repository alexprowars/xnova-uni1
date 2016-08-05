-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Сен 08 2011 г., 19:56
-- Версия сервера: 5.0.77
-- Версия PHP: 5.1.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `xnova`
--

-- --------------------------------------------------------

--
-- Структура таблицы `chat_log`
--

CREATE TABLE IF NOT EXISTS `chat_log` (
  `time` int(11) NOT NULL,
  `user` varchar(50) NOT NULL,
  `msg` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `chat_log`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_aks`
--

CREATE TABLE IF NOT EXISTS `game_aks` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `name` varchar(50) default NULL,
  `fleet_id` int(32) default NULL,
  `galaxy` int(2) default NULL,
  `system` int(4) default NULL,
  `planet` int(2) default NULL,
  `planet_type` tinyint(1) NOT NULL default '1',
  `user_id` int(11) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `game_aks`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_aks_user`
--

CREATE TABLE IF NOT EXISTS `game_aks_user` (
  `aks_id` int(11) unsigned NOT NULL default '0',
  `user_id` int(11) unsigned NOT NULL default '0',
  KEY `aks_id` (`aks_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `game_aks_user`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_alliance`
--

CREATE TABLE IF NOT EXISTS `game_alliance` (
  `id` bigint(11) NOT NULL auto_increment,
  `ally_name` varchar(32) default NULL,
  `ally_tag` varchar(8) default NULL,
  `ally_owner` int(11) NOT NULL default '0',
  `ally_register_time` int(11) NOT NULL default '0',
  `ally_description` text,
  `ally_web` varchar(255) default NULL,
  `ally_text` text,
  `ally_image` varchar(255) default NULL,
  `ally_request` text,
  `ally_request_waiting` text,
  `ally_request_notallow` tinyint(4) NOT NULL default '0',
  `ally_owner_range` varchar(32) default 'Основатель',
  `ally_ranks` text,
  `ally_members` int(11) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `game_alliance`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_alliance_diplo`
--

CREATE TABLE IF NOT EXISTS `game_alliance_diplo` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `o_al` int(11) unsigned NOT NULL default '0',
  `t_al` int(11) unsigned NOT NULL default '0',
  `type` tinyint(1) unsigned NOT NULL default '0',
  `status` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `game_alliance_diplo`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_autoban`
--

CREATE TABLE IF NOT EXISTS `game_autoban` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `user` varchar(35) NOT NULL default '',
  `script` varchar(100) NOT NULL default '',
  `time` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `game_autoban`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_banned`
--

CREATE TABLE IF NOT EXISTS `game_banned` (
  `id` bigint(11) NOT NULL auto_increment,
  `who` varchar(25) NOT NULL,
  `theme` text NOT NULL,
  `time` int(11) NOT NULL default '0',
  `longer` int(11) NOT NULL default '0',
  `author` varchar(25) NOT NULL,
  KEY `ID` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `game_banned`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_buddy`
--

CREATE TABLE IF NOT EXISTS `game_buddy` (
  `id` bigint(11) NOT NULL auto_increment,
  `sender` int(11) NOT NULL default '0',
  `owner` int(11) NOT NULL default '0',
  `active` tinyint(3) NOT NULL default '0',
  `text` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `game_buddy`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_chat`
--

CREATE TABLE IF NOT EXISTS `game_chat` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `ally_id` int(11) unsigned NOT NULL default '0',
  `user` varchar(50) NOT NULL default '',
  `message` text NOT NULL,
  `timestamp` int(11) NOT NULL default '0',
  `dostup` smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `ally_id` (`ally_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `game_chat`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_config`
--

CREATE TABLE IF NOT EXISTS `game_config` (
  `config_name` varchar(64) NOT NULL default '',
  `config_value` int(11) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `game_config`
--

INSERT INTO `game_config` (`config_name`, `config_value`) VALUES
('users_amount', 0),
('LastSettedGalaxyPos', 1),
('LastSettedSystemPos', 1),
('LastSettedPlanetPos', 1),
('MaxUsers', 0),
('credits', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `game_cr_sale`
--

CREATE TABLE IF NOT EXISTS `game_cr_sale` (
  `time` int(11) NOT NULL default '0',
  `id` int(11) NOT NULL default '0',
  `credits` int(11) NOT NULL default '0',
  `lvl` tinyint(2) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `game_cr_sale`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_errors`
--

CREATE TABLE IF NOT EXISTS `game_errors` (
  `error_id` bigint(11) NOT NULL auto_increment,
  `error_sender` varchar(32) NOT NULL default '0',
  `error_time` int(11) NOT NULL default '0',
  `error_type` varchar(32) NOT NULL default 'unknown',
  `error_text` text,
  PRIMARY KEY  (`error_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `game_errors`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_fleets`
--

CREATE TABLE IF NOT EXISTS `game_fleets` (
  `fleet_id` bigint(11) NOT NULL auto_increment,
  `fleet_owner` int(11) NOT NULL default '0',
  `fleet_owner_name` varchar(35) NOT NULL default '',
  `fleet_mission` int(11) NOT NULL default '0',
  `fleet_amount` bigint(11) NOT NULL default '0',
  `fleet_array` text,
  `fleet_start_time` int(11) NOT NULL default '0',
  `fleet_start_galaxy` tinyint(2) unsigned NOT NULL default '0',
  `fleet_start_system` smallint(6) unsigned NOT NULL default '0',
  `fleet_start_planet` tinyint(2) unsigned NOT NULL default '0',
  `fleet_start_type` tinyint(2) unsigned NOT NULL default '0',
  `fleet_end_time` int(11) NOT NULL default '0',
  `fleet_end_stay` int(11) NOT NULL default '0',
  `fleet_end_galaxy` tinyint(2) unsigned NOT NULL default '0',
  `fleet_end_system` smallint(6) unsigned NOT NULL default '0',
  `fleet_end_planet` tinyint(2) unsigned NOT NULL default '0',
  `fleet_end_type` tinyint(2) unsigned NOT NULL default '0',
  `fleet_resource_metal` bigint(11) unsigned NOT NULL default '0',
  `fleet_resource_crystal` bigint(11) unsigned NOT NULL default '0',
  `fleet_resource_deuterium` bigint(11) unsigned NOT NULL default '0',
  `fleet_target_owner` int(11) NOT NULL default '0',
  `fleet_target_owner_name` varchar(35) NOT NULL default '',
  `fleet_group` int(11) NOT NULL default '0',
  `fleet_mess` int(11) NOT NULL default '0',
  `start_time` int(11) NOT NULL default '0',
  `fleet_time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`fleet_id`),
  KEY `fleet_owner` (`fleet_owner`),
  KEY `fleet_target_owner` (`fleet_target_owner`),
  KEY `fleet_start_time` (`fleet_start_time`),
  KEY `fleet_end_stay` (`fleet_end_stay`),
  KEY `fleet_end_time` (`fleet_end_time`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `game_fleets`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_galaxy`
--

CREATE TABLE IF NOT EXISTS `game_galaxy` (
  `galaxy` tinyint(2) NOT NULL default '0',
  `system` smallint(3) NOT NULL default '0',
  `planet` tinyint(2) NOT NULL default '0',
  `id_planet` int(11) NOT NULL default '0',
  `metal` int(11) NOT NULL default '0',
  `crystal` int(11) NOT NULL default '0',
  `id_luna` int(11) NOT NULL default '0',
  KEY `galaxy` (`galaxy`),
  KEY `system` (`system`),
  KEY `id_planet` (`id_planet`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `game_galaxy`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_iraks`
--

CREATE TABLE IF NOT EXISTS `game_iraks` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `zeit` int(11) default NULL,
  `galaxy` tinyint(2) unsigned default NULL,
  `system` smallint(4) unsigned default NULL,
  `planet` tinyint(2) unsigned default NULL,
  `planet_type` tinyint(2) unsigned NOT NULL default '0',
  `galaxy_angreifer` tinyint(2) unsigned default NULL,
  `system_angreifer` smallint(4) unsigned default NULL,
  `planet_angreifer` tinyint(2) unsigned default NULL,
  `planet_angreifer_type` tinyint(2) unsigned NOT NULL default '0',
  `owner` int(11) default NULL,
  `zielid` int(11) default NULL,
  `anzahl` int(11) default NULL,
  `primaer` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `zeit` (`zeit`),
  KEY `owner` (`owner`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `game_iraks`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_logs`
--

CREATE TABLE IF NOT EXISTS `game_logs` (
  `mission` tinyint(1) unsigned NOT NULL default '0',
  `time` int(11) unsigned NOT NULL default '0',
  `kolvo` tinyint(1) unsigned NOT NULL default '1',
  `s_id` int(11) unsigned NOT NULL default '0',
  `s_galaxy` tinyint(1) unsigned NOT NULL default '0',
  `s_system` smallint(5) unsigned NOT NULL default '0',
  `s_planet` tinyint(1) unsigned NOT NULL default '0',
  `e_id` int(11) unsigned NOT NULL default '0',
  `e_galaxy` tinyint(1) unsigned NOT NULL default '0',
  `e_system` smallint(5) unsigned NOT NULL default '0',
  `e_planet` tinyint(2) unsigned NOT NULL default '0',
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `game_logs`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_lostpwd`
--

CREATE TABLE IF NOT EXISTS `game_lostpwd` (
  `u_id` int(11) NOT NULL default '0',
  `ks` char(32) NOT NULL default '',
  `time` int(11) NOT NULL default '0',
  `ip` varchar(35) NOT NULL default '',
  `activ` tinyint(1) NOT NULL default '0',
  KEY `u_id` (`u_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `game_lostpwd`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_messages`
--

CREATE TABLE IF NOT EXISTS `game_messages` (
  `message_id` bigint(11) NOT NULL auto_increment,
  `message_owner` int(11) NOT NULL default '0',
  `message_sender` int(11) NOT NULL default '0',
  `message_time` int(11) NOT NULL default '0',
  `message_type` int(11) NOT NULL default '0',
  `message_from` varchar(48) default NULL,
  `message_text` text,
  PRIMARY KEY  (`message_id`),
  KEY `message_owner` (`message_owner`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `game_messages`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_moneys`
--

CREATE TABLE IF NOT EXISTS `game_moneys` (
  `id` bigint(20) NOT NULL default '0',
  `ip` varchar(50) NOT NULL default '',
  `time` bigint(20) NOT NULL default '0',
  `referer` varchar(250) NOT NULL default '',
  `user_agent` varchar(250) NOT NULL default '',
  KEY `ip` (`ip`),
  KEY `id` (`id`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `game_moneys`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_mults`
--

CREATE TABLE IF NOT EXISTS `game_mults` (
  `time` int(11) NOT NULL default '0',
  `id_1` int(11) NOT NULL default '0',
  `str` varchar(250) NOT NULL default '0',
  `id_2` int(11) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `game_mults`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_notes`
--

CREATE TABLE IF NOT EXISTS `game_notes` (
  `id` bigint(11) NOT NULL auto_increment,
  `owner` int(11) default NULL,
  `time` int(11) default NULL,
  `priority` tinyint(1) default NULL,
  `title` varchar(32) default NULL,
  `text` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `game_notes`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_pins`
--

CREATE TABLE IF NOT EXISTS `game_pins` (
  `pin` char(32) NOT NULL,
  `time` int(11) NOT NULL default '0',
  `price` int(11) NOT NULL default '0',
  `status` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`pin`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `game_pins`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_pin_log`
--

CREATE TABLE IF NOT EXISTS `game_pin_log` (
  `pin` char(32) NOT NULL,
  `user` int(11) NOT NULL default '0',
  `time` int(11) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `game_pin_log`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_planets`
--

CREATE TABLE IF NOT EXISTS `game_planets` (
  `id` bigint(11) unsigned NOT NULL auto_increment,
  `name` varchar(50) default NULL,
  `id_owner` int(11) unsigned default NULL,
  `id_level` tinyint(1) unsigned NOT NULL default '0',
  `galaxy` tinyint(2) unsigned NOT NULL default '0',
  `system` smallint(5) unsigned NOT NULL default '0',
  `planet` tinyint(2) unsigned NOT NULL default '0',
  `last_update` int(11) default NULL,
  `planet_type` tinyint(1) unsigned NOT NULL default '1',
  `destruyed` int(11) unsigned NOT NULL default '0',
  `b_building` int(11) NOT NULL default '0',
  `b_building_id` text NOT NULL,
  `b_tech` int(11) NOT NULL default '0',
  `b_tech_id` int(11) NOT NULL default '0',
  `b_hangar` int(11) NOT NULL default '0',
  `b_hangar_id` text NOT NULL,
  `b_hangar_plus` int(11) NOT NULL default '0',
  `image` varchar(32) NOT NULL default 'normaltempplanet01',
  `diameter` smallint(6) unsigned NOT NULL default '12800',
  `field_current` smallint(6) unsigned NOT NULL default '0',
  `field_max` smallint(6) unsigned NOT NULL default '163',
  `temp_min` smallint(3) NOT NULL default '-17',
  `temp_max` smallint(3) NOT NULL default '23',
  `metal` double(32,4) NOT NULL default '0.0000',
  `crystal` double(32,4) NOT NULL default '0.0000',
  `deuterium` double(32,4) NOT NULL default '0.0000',
  `people` double(32,4) NOT NULL default '100.0000',
  `energy_ak` double(11,2) NOT NULL default '0.00',
  `metal_mine` smallint(6) unsigned NOT NULL default '0',
  `crystal_mine` smallint(6) unsigned NOT NULL default '0',
  `deuterium_sintetizer` smallint(6) unsigned NOT NULL default '0',
  `solar_plant` smallint(6) unsigned NOT NULL default '0',
  `ak_station` tinyint(2) unsigned NOT NULL default '0',
  `fusion_plant` smallint(6) unsigned NOT NULL default '0',
  `robot_factory` smallint(6) unsigned NOT NULL default '0',
  `nano_factory` smallint(6) unsigned NOT NULL default '0',
  `hangar` smallint(6) unsigned NOT NULL default '0',
  `metal_store` smallint(6) unsigned NOT NULL default '0',
  `crystal_store` smallint(6) unsigned NOT NULL default '0',
  `deuterium_store` smallint(6) unsigned NOT NULL default '0',
  `laboratory` smallint(6) unsigned NOT NULL default '0',
  `terraformer` smallint(6) unsigned NOT NULL default '0',
  `ally_deposit` smallint(6) unsigned NOT NULL default '0',
  `silo` smallint(6) unsigned NOT NULL default '0',
  `small_ship_cargo` int(11) NOT NULL default '0',
  `big_ship_cargo` int(11) NOT NULL default '0',
  `light_hunter` int(11) NOT NULL default '0',
  `heavy_hunter` int(11) NOT NULL default '0',
  `crusher` int(11) NOT NULL default '0',
  `battle_ship` int(11) NOT NULL default '0',
  `colonizer` int(11) NOT NULL default '0',
  `recycler` int(11) NOT NULL default '0',
  `spy_sonde` smallint(6) NOT NULL default '0',
  `bomber_ship` int(11) NOT NULL default '0',
  `solar_satelit` int(11) NOT NULL default '0',
  `destructor` int(11) NOT NULL default '0',
  `dearth_star` int(11) NOT NULL default '0',
  `battleship` int(11) NOT NULL default '0',
  `fly_base` int(11) NOT NULL default '0',
  `misil_launcher` int(11) NOT NULL default '0',
  `small_laser` int(11) NOT NULL default '0',
  `big_laser` int(11) NOT NULL default '0',
  `gauss_canyon` int(11) NOT NULL default '0',
  `ionic_canyon` int(11) NOT NULL default '0',
  `buster_canyon` int(11) NOT NULL default '0',
  `small_protection_shield` int(11) NOT NULL default '0',
  `big_protection_shield` int(11) NOT NULL default '0',
  `interceptor_misil` int(11) NOT NULL default '0',
  `interplanetary_misil` smallint(6) unsigned NOT NULL default '0',
  `metal_mine_porcent` tinyint(2) unsigned NOT NULL default '10',
  `crystal_mine_porcent` tinyint(2) unsigned NOT NULL default '10',
  `deuterium_sintetizer_porcent` tinyint(2) unsigned NOT NULL default '10',
  `solar_plant_porcent` tinyint(2) unsigned NOT NULL default '10',
  `fusion_plant_porcent` tinyint(2) unsigned NOT NULL default '10',
  `solar_satelit_porcent` tinyint(2) unsigned NOT NULL default '10',
  `darkmat_mine_porcent` tinyint(2) unsigned NOT NULL default '10',
  `mondbasis` smallint(6) unsigned NOT NULL default '0',
  `phalanx` smallint(6) unsigned NOT NULL default '0',
  `sprungtor` smallint(6) unsigned NOT NULL default '0',
  `last_jump_time` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id_owner` (`id_owner`),
  KEY `galaxy` (`galaxy`),
  KEY `system` (`system`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `game_planets`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_refs`
--

CREATE TABLE IF NOT EXISTS `game_refs` (
  `r_id` int(11) unsigned NOT NULL default '0',
  `u_id` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`r_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `game_refs`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_rw`
--

CREATE TABLE IF NOT EXISTS `game_rw` (
  `id_owner1` int(11) NOT NULL default '0',
  `id_owner2` int(11) NOT NULL default '0',
  `rid` varchar(72) NOT NULL,
  `raport` text NOT NULL,
  `a_zestrzelona` tinyint(3) unsigned NOT NULL default '0',
  `time` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `game_rw`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_savelog`
--

CREATE TABLE IF NOT EXISTS `game_savelog` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `user` varchar(35) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `log` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `game_savelog`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_statpoints`
--

CREATE TABLE IF NOT EXISTS `game_statpoints` (
  `id_owner` int(11) NOT NULL default '0',
  `username` varchar(35) NOT NULL default '',
  `id_ally` int(11) NOT NULL default '0',
  `ally_name` varchar(50) NOT NULL default '',
  `stat_type` tinyint(1) unsigned NOT NULL default '0',
  `stat_code` int(11) NOT NULL default '0',
  `tech_rank` smallint(6) unsigned NOT NULL default '0',
  `tech_old_rank` smallint(6) unsigned NOT NULL default '0',
  `tech_points` bigint(20) NOT NULL default '0',
  `tech_count` int(11) NOT NULL default '0',
  `build_rank` smallint(6) unsigned NOT NULL default '0',
  `build_old_rank` smallint(6) unsigned NOT NULL default '0',
  `build_points` bigint(20) NOT NULL default '0',
  `build_count` int(11) NOT NULL default '0',
  `defs_rank` smallint(6) unsigned NOT NULL default '0',
  `defs_old_rank` smallint(6) unsigned NOT NULL default '0',
  `defs_points` bigint(20) NOT NULL default '0',
  `defs_count` int(11) NOT NULL default '0',
  `fleet_rank` smallint(6) unsigned NOT NULL default '0',
  `fleet_old_rank` smallint(6) unsigned NOT NULL default '0',
  `fleet_points` bigint(20) NOT NULL default '0',
  `fleet_count` int(11) NOT NULL default '0',
  `total_rank` smallint(6) unsigned NOT NULL default '0',
  `total_old_rank` smallint(6) unsigned NOT NULL default '0',
  `total_points` bigint(20) NOT NULL default '0',
  `total_count` int(11) NOT NULL default '0',
  `stat_date` int(11) NOT NULL default '0',
  `del` tinyint(1) unsigned NOT NULL default '0',
  `stat_hide` tinyint(1) NOT NULL default '0',
  KEY `stat_type` (`stat_type`),
  KEY `id_owner` (`id_owner`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `game_statpoints`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_temp`
--

CREATE TABLE IF NOT EXISTS `game_temp` (
  `id` int(11) NOT NULL auto_increment,
  `ip` varchar(30) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `game_temp`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_users`
--

CREATE TABLE IF NOT EXISTS `game_users` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `authlevel` tinyint(2) unsigned NOT NULL default '0',
  `sex` char(1) default NULL,
  `id_planet` int(11) unsigned NOT NULL default '0',
  `galaxy` int(11) unsigned NOT NULL default '0',
  `system` int(11) unsigned NOT NULL default '0',
  `planet` tinyint(2) unsigned NOT NULL default '0',
  `current_planet` int(11) NOT NULL default '0',
  `user_lastip` varchar(16) NOT NULL default '',
  `onlinetime` int(11) unsigned NOT NULL default '0',
  `dpath` tinyint(1) NOT NULL default '0',
  `design` tinyint(2) unsigned NOT NULL default '1',
  `security` tinyint(1) NOT NULL default '0',
  `widescreen` tinyint(1) NOT NULL default '0',
  `icq` int(11) NOT NULL default '0',
  `vkontakte` varchar(35) NOT NULL,
  `planet_sort` tinyint(2) unsigned NOT NULL default '0',
  `planet_sort_order` tinyint(2) unsigned NOT NULL default '0',
  `new_message` smallint(6) unsigned NOT NULL default '0',
  `mnl_alliance` smallint(5) unsigned NOT NULL default '0',
  `fleet_shortcut` text,
  `b_tech_planet` int(11) NOT NULL default '0',
  `spy_tech` smallint(5) unsigned NOT NULL default '0',
  `computer_tech` smallint(5) unsigned NOT NULL default '0',
  `military_tech` smallint(5) unsigned NOT NULL default '0',
  `defence_tech` smallint(5) unsigned NOT NULL default '0',
  `shield_tech` smallint(5) unsigned NOT NULL default '0',
  `energy_tech` smallint(5) unsigned NOT NULL default '0',
  `hyperspace_tech` smallint(5) unsigned NOT NULL default '0',
  `combustion_tech` smallint(5) unsigned NOT NULL default '0',
  `impulse_motor_tech` smallint(5) unsigned NOT NULL default '0',
  `hyperspace_motor_tech` smallint(5) unsigned NOT NULL default '0',
  `laser_tech` smallint(5) unsigned NOT NULL default '0',
  `ionic_tech` smallint(5) unsigned NOT NULL default '0',
  `buster_tech` smallint(5) unsigned NOT NULL default '0',
  `intergalactic_tech` smallint(5) unsigned NOT NULL default '0',
  `expedition_tech` smallint(5) unsigned NOT NULL default '0',
  `colonisation_tech` smallint(5) unsigned NOT NULL default '0',
  `graviton_tech` smallint(5) unsigned NOT NULL default '0',
  `capacity_tech` smallint(6) unsigned NOT NULL default '0',
  `ecology_tech` smallint(5) unsigned NOT NULL default '0',
  `fleet_base_tech` smallint(6) unsigned NOT NULL default '0',
  `ally_id` int(11) NOT NULL default '0',
  `ally_name` varchar(32) default NULL,
  `ally_request` int(11) NOT NULL default '0',
  `ally_request_text` varchar(160) default NULL,
  `ally_register_time` int(11) NOT NULL default '0',
  `ally_rank_id` int(11) NOT NULL default '0',
  `current_luna` int(11) NOT NULL default '0',
  `rpg_geologue` int(11) unsigned NOT NULL default '0',
  `rpg_admiral` int(11) unsigned NOT NULL default '0',
  `rpg_ingenieur` int(11) unsigned NOT NULL default '0',
  `rpg_technocrate` int(11) unsigned NOT NULL default '0',
  `rpg_constructeur` int(11) unsigned NOT NULL default '0',
  `rpg_meta` int(11) unsigned NOT NULL default '0',
  `rpg_komandir` int(11) NOT NULL,
  `lvl_minier` smallint(6) unsigned NOT NULL default '1',
  `lvl_raid` smallint(6) unsigned NOT NULL default '1',
  `xpraid` int(11) NOT NULL default '0',
  `xpminier` int(11) NOT NULL default '0',
  `raids_win` smallint(6) unsigned NOT NULL default '0',
  `raids_xz` smallint(6) unsigned NOT NULL default '0',
  `raids_lose` smallint(6) unsigned NOT NULL default '0',
  `raids` int(11) NOT NULL default '0',
  `credits` int(11) NOT NULL default '10000',
  `urlaubs_modus_time` int(11) NOT NULL default '0',
  `deltime` int(11) NOT NULL default '0',
  `banaday` int(11) unsigned NOT NULL default '0',
  `links` int(11) unsigned NOT NULL default '0',
  `marchand` int(11) NOT NULL default '0',
  `chat` tinyint(1) NOT NULL default '0',
  `color` tinyint(2) NOT NULL default '1',
  `avatar` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `username` (`username`),
  KEY `ally_id` (`ally_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `game_users`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_users_inf`
--

CREATE TABLE IF NOT EXISTS `game_users_inf` (
  `id` int(11) NOT NULL default '0',
  `password` char(32) NOT NULL,
  `email` varchar(35) NOT NULL,
  `email_2` varchar(35) NOT NULL,
  `register_time` int(11) NOT NULL default '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `game_users_inf`
--


-- --------------------------------------------------------

--
-- Структура таблицы `game_wmrlog`
--

CREATE TABLE IF NOT EXISTS `game_wmrlog` (
  `pay_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `username` varchar(50) NOT NULL default '',
  `credits` int(11) NOT NULL default '0',
  `purse` varchar(50) NOT NULL default '',
  `date_start` int(11) NOT NULL default '0',
  `date_end` varchar(50) NOT NULL default '',
  `status` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`pay_id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

--
-- Дамп данных таблицы `game_wmrlog`
--

