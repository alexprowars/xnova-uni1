<?php

if(!defined("INSIDE")) die("attemp hacking");

//if ($user['username'] != "AlexPro") die();

function ModuleMarchand ( &$CurrentUser, &$CurrentPlanet ) {
	global $lang, $_POST;

	includeLang('marchand');

	$parse   = $lang;

	if ($CurrentUser['marchand'] < time()) {
		if ($_POST['action'] == 1) {
			if ($CurrentUser['credits'] >= 1000) {
				doquery("UPDATE {{table}} SET `marchand` = ".(time() + 1800).", `credits` = `credits` - 1000 WHERE id = ".$CurrentUser['id']."", "users");
				doquery("UPDATE {{table}} SET config_value = config_value + 1000 WHERE config_name = 'credits';", "config");
				$CurrentUser['credits'] -= 1000;
				$CurrentUser['marchand'] = time() + 1800;
			} else {
				message('У вас недостаточно кредитов для совершения данной операции!'  , 'Ошибка', '?set=marchand', 5);
			}
		}
	}

	if ($_POST['action'] == 3 && $CurrentUser['marchand'] > time()) {
		$credits = intval($_POST['credits']);
		if ($credits > 0 && $CurrentUser['credits'] >= $credits) {

			$shopmet  = (($CurrentUser['lvl_minier'] * 4) / 3.5) * $credits;
			$shopkris = (($CurrentUser['lvl_minier'] * 2) / 3.5) * $credits;
			$shopdeyt = (($CurrentUser['lvl_minier'] * 1) / 3.5) * $credits;

			$CurrentPlanet['metal']     += $shopmet;
			$CurrentPlanet['crystal']   += $shopkris;
			$CurrentPlanet['deuterium'] += $shopdeyt;

			$QryUpdatePlanet  = "UPDATE {{table}} SET ";
			$QryUpdatePlanet .= "`metal` = '".     $CurrentPlanet['metal']     ."', ";
			$QryUpdatePlanet .= "`crystal` = '".   $CurrentPlanet['crystal']   ."', ";
			$QryUpdatePlanet .= "`deuterium` = '". $CurrentPlanet['deuterium'] ."' ";
			$QryUpdatePlanet .= "WHERE ";
			$QryUpdatePlanet .= "`id` = '".        $CurrentPlanet['id']        ."';";
			doquery ( $QryUpdatePlanet , 'planets');

			doquery("INSERT INTO {{table}} VALUES (".time().", ".$CurrentUser['id'].", ".$credits.", ".$CurrentUser['lvl_minier'].")", "cr_sale");
			doquery("UPDATE {{table}} SET `credits` = `credits` - ".$credits." WHERE id = ".$CurrentUser['id']."", "users");
			doquery("UPDATE {{table}} SET config_value = config_value + ".$credits." WHERE config_name = 'credits';", "config");
			$CurrentUser['credits'] -= $credits;
		} else {
			message('У вас недостаточно кредитов для совершения данной операции!'  , 'Ошибка', '?set=marchand', 5);
		}
	}

	if ($_POST['ress'] != '' && $CurrentUser['marchand'] > time()) {
		$PageTPL = gettemplate('message_body');
		$Error   = false;

		$metal = intval($_POST['metal']);
		$cristal = intval($_POST['cristal']);
		$deut = intval($_POST['deut']);

		switch ($_POST['ress']) {
			case 'metal':
				$Necessaire   = ($cristal * 2) + ($deut * 4);
				if ($cristal < 0 || $deut < 0 || $metal != 0){
					$Message 	= "Failed";
					$Error   	= true;
				} elseif ($CurrentPlanet['metal'] > $Necessaire) {
					$CurrentPlanet['metal'] -= $Necessaire;
				} else {
					$Message 	= $lang['mod_ma_noten'] ." ". $lang['Metal'] ."! ";
					$Error   	= true;
				}
				break;

			case 'cristal':
				$Necessaire   = ($metal * 0.5) + ($deut * 2);
				if($metal < 0 || $deut < 0 || $cristal != 0){
					$Message 	= "Failed";
					$Error   	= true;
				} elseif ($CurrentPlanet['crystal'] > $Necessaire) {
					$CurrentPlanet['crystal'] -= $Necessaire;
				} else {
					$Message 	= $lang['mod_ma_noten'] ." ". $lang['Crystal'] ."! ";
					$Error   	= true;
				}
				break;

			case 'deuterium':
				$Necessaire   = ($metal * 0.25) + ($cristal * 0.5);
				if($metal < 0 || $cristal < 0 || $deut != 0){
					$Message 	= "Failed";
					$Error   	= true;
				} elseif ($CurrentPlanet['deuterium'] > $Necessaire) {
					$CurrentPlanet['deuterium'] -= $Necessaire;
				} else {
					$Message 	= $lang['mod_ma_noten'] ." ". $lang['Deuterium'] ."! ";
					$Error   	= true;
				}
				break;

			default :
				$Message = "Ошибочная операция";
				$Error   = true;
			break;

		}
		if ($Error == false) {
			if ($_POST['ress'] != "metal") 	$CurrentPlanet['metal']     += $metal;
			if ($_POST['ress'] != "cristal") 	$CurrentPlanet['crystal']   += $cristal;
			if ($_POST['ress'] != "deuterium") $CurrentPlanet['deuterium'] += $deut;

			$QryUpdatePlanet  = "UPDATE {{table}} SET ";
			$QryUpdatePlanet .= "`metal` = '".     $CurrentPlanet['metal']     ."', ";
			$QryUpdatePlanet .= "`crystal` = '".   $CurrentPlanet['crystal']   ."', ";
			$QryUpdatePlanet .= "`deuterium` = '". $CurrentPlanet['deuterium'] ."' ";
			$QryUpdatePlanet .= "WHERE ";
			$QryUpdatePlanet .= "`id` = '".        $CurrentPlanet['id']        ."';";
			doquery ( $QryUpdatePlanet , 'planets');
			$Message = $lang['mod_ma_done'];
		}
		if ($Error == true) {
			$parse['title'] = $lang['mod_ma_error'];
		} else {
			$parse['title'] = $lang['mod_ma_donet'];
		}
		$parse['mes']   = $Message;
	} else {
		if ($CurrentUser['marchand'] < time()) {
			$PageTPL = gettemplate('marchand_login');
		} else {
			if ($_POST['action'] != 2) {
				$PageTPL = gettemplate('marchand_main');

				$shopmet  = ($CurrentUser['lvl_minier'] * 4) / 3.5;
				$shopkris = ($CurrentUser['lvl_minier'] * 2) / 3.5;
				$shopdeyt = ($CurrentUser['lvl_minier'] * 1) / 3.5;

				$parse['shopmet']  = $shopmet;
            			$parse['shopkris'] = $shopkris;
            			$parse['shopdeyt'] = $shopdeyt;
			} else {
				$parse['mod_ma_res']   = "1";
				switch ($_POST['choix']) {
					case 'metal':
						$PageTPL = gettemplate('marchand_metal');
						$parse['mod_ma_res_a'] = "2";
						$parse['mod_ma_res_b'] = "4";
					break;
					case 'cristal':
						$PageTPL = gettemplate('marchand_cristal');
						$parse['mod_ma_res_a'] = "0.5";
						$parse['mod_ma_res_b'] = "2";
					break;
					case 'deut':
						$PageTPL = gettemplate('marchand_deuterium');
						$parse['mod_ma_res_a'] = "0.25";
						$parse['mod_ma_res_b'] = "0.5";
					break;
					default:
						message('Злобный читер!'  , 'Ошибка', '?set=merchand', 5);
					break;
				}
			}
		}
	}

	$Page    = parsetemplate ( $PageTPL, $parse );
	return  $Page;
}

	$Page = ModuleMarchand ( $user, $planetrow );
	display ( $Page, $lang['mod_marchand'] );

?>