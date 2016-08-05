<?php

function calculateAttack (&$attackers, &$defenders, $injener) {
		global $pricelist, $CombatCaps, $game_config, $resource;
		
		$totalResourcePoints = array('attacker' => 0, 'defender' => 0);
		
		$resourcePointsAttacker = array('metal' => 0, 'crystal' => 0);
		foreach ($attackers as $fleetID => $attacker) {
			foreach ($attacker['detail'] as $element => $amount) {
				$resourcePointsAttacker['metal'] += $pricelist[$element]['metal'] * $amount;
				$resourcePointsAttacker['crystal'] += $pricelist[$element]['crystal'] * $amount ;
				
				$totalResourcePoints['attacker'] += $pricelist[$element]['metal'] * $amount ;
				$totalResourcePoints['attacker'] += $pricelist[$element]['crystal'] * $amount ;
			}
		}
		
		$resourcePointsDefender = array('metal' => 0, 'crystal' => 0);
		foreach ($defenders as $fleetID => $defender) {
			foreach ($defender['def'] as $element => $amount) {
				if ($element < 300) {
					$resourcePointsDefender['metal'] += $pricelist[$element]['metal'] * $amount ;
					$resourcePointsDefender['crystal'] += $pricelist[$element]['crystal'] * $amount ;
				} else {
					if (!isset($originalDef[$element])) $originalDef[$element] = 0;
					$originalDef[$element] += $amount;
				}
				$totalResourcePoints['defender'] += $pricelist[$element]['metal'] * $amount ;
				$totalResourcePoints['defender'] += $pricelist[$element]['crystal'] * $amount ;
			}
		}
		
		$max_rounds = 6;

		
		for ($round = 0, $rounds = array(); $round < $max_rounds; $round++) {
			$attackDamage  = array('total' => 0);
			$attackShield  = array('total' => 0);
			$attackAmount  = array('total' => 0);
			$defenseDamage = array('total' => 0);
			$defenseShield = array('total' => 0);
			$defenseAmount = array('total' => 0);
			$attArray = array();
			$defArray = array();
			
			foreach ($attackers as $fleetID => $attacker) {
				$attackDamage[$fleetID] 	= 0;
				$attackShield[$fleetID] 	= 0;
				$attackAmount[$fleetID] 	= 0;
				
				foreach ($attacker['detail'] as $element => $amount) {
					$defTech    	= (1 + (0.1 * $attacker['user']['defence_tech']));
					$shieldTech 	= (1 + (0.1 * $attacker['user']['shield_tech']));
					$attTech		= (1 + (0.1 * $attacker['user']['military_tech']));
					
					$attackers[$fleetID]['techs'] = array($defTech, $shieldTech, $attTech);
					
					$thisDef		= round($amount * ($pricelist[$element]['metal'] + $pricelist[$element]['crystal']) / 10 * $defTech);
					$thisShield 	= round($amount * ($CombatCaps[$element]['shield']) * $shieldTech);
					$thisAtt		= round($amount * ($CombatCaps[$element]['attack']) * $attTech);
					
					$attArray[$fleetID][$element] = array('def' => $thisDef, 'shield' => $thisShield, 'att' => $thisAtt);
					
					$attackDamage[$fleetID] += $thisAtt;
					$attackDamage['total'] += $thisAtt;
					$attackShield[$fleetID] += $thisDef;
					$attackShield['total'] += $thisDef;
					$attackAmount[$fleetID] += $amount;
					$attackAmount['total'] += $amount;
				}
			}
			foreach ($defenders as $fleetID => $defender) {
				$defenseDamage[$fleetID] = 0;
				$defenseShield[$fleetID] = 0;
				$defenseAmount[$fleetID] = 0;
				
				foreach ($defender['def'] as $element => $amount) {
					$defTech    	= (1 + (0.1 * $defender['user']['defence_tech']));
					$shieldTech 	= (1 + (0.1 * $defender['user']['shield_tech']));
					$attTech		= (1 + (0.1 * $defender['user']['military_tech']));
					
					$defenders[$fleetID]['techs'] = array($defTech, $shieldTech, $attTech);
					
					$thisDef		= round($amount * ($pricelist[$element]['metal'] + $pricelist[$element]['crystal']) / 10 * $defTech);
					$thisShield	= round($amount * ($CombatCaps[$element]['shield']) * $shieldTech);
					$thisAtt		= round($amount * ($CombatCaps[$element]['attack']) * $attTech);
					
					if ($element == 407 || $element == 408) $thisAtt = 0;
					
					$defArray[$fleetID][$element] = array('def' => $thisDef, 'shield' => $thisShield, 'att' => $thisAtt);
					
					$defenseDamage[$fleetID] += $thisAtt;
					$defenseDamage['total'] += $thisAtt;
					$defenseShield[$fleetID] += $thisDef;
					$defenseShield['total'] += $thisDef;
					$defenseAmount[$fleetID] += $amount;
					$defenseAmount['total'] += $amount;
				}
			}
			
			$rounds[$round] = array('attackers' => $attackers, 'defenders' => $defenders, 'attack' => $attackDamage, 'defense' => $defenseDamage, 'attackA' => $attackAmount, 'defenseA' => $defenseAmount, 'infoA' => $attArray, 'infoD' => $defArray);

			if ($defenseAmount['total'] <= 0 || $attackAmount['total'] <= 0) {
				break;
			}

			$attackPct = array();
			foreach ($attackAmount as $fleetID => $amount) {
				if (!is_numeric($fleetID)) continue;
				$attackPct[$fleetID] = $amount / $attackAmount['total'];
			}
			
			$defensePct = array();
			foreach ($defenseAmount as $fleetID => $amount) {
				if (!is_numeric($fleetID)) continue;
				$defensePct[$fleetID] = $amount / $defenseAmount['total'];
			}

			$attacker_n = array();
			$attacker_shield = 0;
			foreach ($attackers as $fleetID => $attacker) {
				$attacker_n[$fleetID] = array();
				
				if ($attackAmount[$fleetID] > 0) {
					foreach($attacker['detail'] as $element => $amount) {					
						if ($amount > 0) {
							$defender_moc = $amount * ($defenseDamage['total'] * $attackPct[$fleetID]) / $attackAmount[$fleetID];
					
							if ($attArray[$fleetID][$element]['shield'] < $defender_moc) {
								$max_removePoints = floor($amount * $defenseAmount['total'] / $attackAmount[$fleetID] * $attackPct[$fleetID]);
										
								$defender_moc -= $attArray[$fleetID][$element]['shield'];
								$attacker_shield += $attArray[$fleetID][$element]['shield'];
								$ile_removePoints = floor($defender_moc / ((($pricelist[$element]['metal'] + $pricelist[$element]['crystal'])  / 10) * (1 + (0.1 * $attacker['user']['defence_tech']))));

								if ($max_removePoints < 0) $max_removePoints = 0;
								if ($ile_removePoints < 0) $ile_removePoints = 0;
								
								if ($ile_removePoints > $max_removePoints) {
									$ile_removePoints = $max_removePoints;
								}
								
								$attacker_n[$fleetID][$element] = ceil($amount - $ile_removePoints);
								if ($attacker_n[$fleetID][$element] <= 0) {
									$attacker_n[$fleetID][$element] = 0;
								}
							} else {
								$attacker_n[$fleetID][$element] = round($amount);
								$attacker_shield += $defender_moc;
							}
						}
					}
				}
			}

			$defender_n = array();
			$defender_shield = 0;
			
			foreach ($defenders as $fleetID => $defender) {
				$defender_n[$fleetID] = array();
				
				if ($defenseAmount[$fleetID] > 0) {
					foreach($defender['def'] as $element => $amount) {
						if ($amount > 0) {
							$attacker_moc = $amount * ($attackDamage['total'] * $defensePct[$fleetID]) / $defenseAmount[$fleetID];

							if ($defArray[$fleetID][$element]['shield'] < $attacker_moc) {
								$max_removePoints = floor($amount * $attackAmount['total'] / $defenseAmount[$fleetID] * $defensePct[$fleetID]);
								
								$attacker_moc -= $defArray[$fleetID][$element]['shield'];
								$defender_shield += $defArray[$fleetID][$element]['shield'];
								$ile_removePoints = floor($attacker_moc / ((($pricelist[$element]['metal'] + $pricelist[$element]['crystal']) / 10) * (1 + (0.1 * $defender['user']['defence_tech']))));
								
								if ($max_removePoints < 0) $max_removePoints = 0;
								if ($ile_removePoints < 0) $ile_removePoints = 0;
								
								if ($ile_removePoints > $max_removePoints) {
									$ile_removePoints = $max_removePoints;
								}
								
								$defender_n[$fleetID][$element] = ceil($amount - $ile_removePoints);
								if ($defender_n[$fleetID][$element] <= 0) {
									$defender_n[$fleetID][$element] = 0;
								}
							
							} else {
								$defender_n[$fleetID][$element] = round($amount);
								$defender_shield += $attacker_moc;
							}
						}
					}
				}
			}

			foreach ($attackers as $fleetID => $attacker) {
				foreach ($defenders as $fleetID2 => $defender) {
					foreach($attacker['detail'] as $element => $amount) {
						if ($amount > 0) {
							foreach ($CombatCaps[$element]['sd'] as $c => $d) {
								if (isset($defender['def'][$c])) {
									if ($d > 1) {
										$defender_n[$fleetID2][$c] -= floor($d * rand(75,100) / 100);
										if ($defender_n[$fleetID2][$c] <= 0) {
											$defender_n[$fleetID2][$c] = 0;
										}
									}
								}
							}
						}
					}
			
					foreach($defender['def'] as $element => $amount) {
						if ($amount > 0) {
							foreach ($CombatCaps[$element]['sd'] as $c => $d) {
								if (isset($attacker['detail'][$c])) {
									if ($d > 1) {
										$attacker_n[$fleetID][$c] -= floor($d * rand(75,100) / 100);
										if ($attacker_n[$fleetID][$c] <= 0) {
											$attacker_n[$fleetID][$c] = 0;
										}
									}
								}
							}
						}
					}
				}
			}

			$rounds[$round]['attackShield'] = round($attacker_shield);
			$rounds[$round]['defShield'] = round($defender_shield);
			
			foreach ($attackers as $fleetID => $attacker) {
				$attackers[$fleetID]['detail'] = array_map('round', $attacker_n[$fleetID]);
			}
			
			foreach ($defenders as $fleetID => $defender) {
				$defenders[$fleetID]['def'] = array_map('round', $defender_n[$fleetID]);
			}
		}
		
		if ($attackAmount['total'] <= 0) {
			$won = 2; // defender
		
		} elseif ($defenseAmount['total'] <= 0) {
			$won = 1; // attacker
		
		} else {
			$won = 0; // draw
			$rounds[count($rounds)] = array('attackers' => $attackers, 'defenders' => $defenders, 'attack' => $attackDamage, 'defense' => $defenseDamage, 'attackA' => $attackAmount, 'defenseA' => $defenseAmount, 'infoA' => $attArray, 'infoD' => $defArray);
		}

		foreach ($attackers as $fleetID => $attacker) {
			foreach ($attacker['detail'] as $element => $amount) {
				$totalResourcePoints['attacker'] -= $pricelist[$element]['metal'] * $amount ;
				$totalResourcePoints['attacker'] -= $pricelist[$element]['crystal'] * $amount ;
				
				$resourcePointsAttacker['metal'] -= $pricelist[$element]['metal'] * $amount ;
				$resourcePointsAttacker['crystal'] -= $pricelist[$element]['crystal'] * $amount ;
			}
		}
		
		foreach ($defenders as $fleetID => $defender) {
			foreach ($defender['def'] as $element => $amount) {
				if ($element < 300) {
					$resourcePointsDefender['metal'] -= $pricelist[$element]['metal'] * $amount ;
					$resourcePointsDefender['crystal'] -= $pricelist[$element]['crystal'] * $amount ;
				} else {
					$lost = $originalDef[$element] - $amount;
					if ($injener > time()) $k = 85;
					else $k = 70;
					$giveback = $lost * (rand($k*0.8, $k*1.2) / 100);
					$defenders[$fleetID]['def'][$element] += $giveback;
				}
				$totalResourcePoints['defender'] -= $pricelist[$element]['metal'] * $amount ;
				$totalResourcePoints['defender'] -= $pricelist[$element]['crystal'] * $amount ;
			}
		}
		
		$totalLost = array('att' => $totalResourcePoints['attacker'], 'def' => $totalResourcePoints['defender']);
		$debAttMet = ($resourcePointsAttacker['metal'] * 0.3);
		$debAttCry = ($resourcePointsAttacker['crystal'] * 0.3);
		$debDefMet = ($resourcePointsDefender['metal'] * 0.3);
		$debDefCry = ($resourcePointsDefender['crystal'] * 0.3);
					
		return array('won' => $won, 'debree' => array('att' => array($debAttMet, $debAttCry), 'def' => array($debDefMet, $debDefCry)), 'rw' => $rounds, 'lost' => $totalLost);
	}
?>