<?php

function HandleElementBuildingQueue ( $CurrentUser, &$CurrentPlanet, $ProductionTime ) {
	global $resource;

	if ($CurrentPlanet['b_hangar_id'] != 0) {
		$Builded                    = array ();
		$CurrentPlanet['b_hangar'] += $ProductionTime;

		$BuildQueue                 = explode(';', $CurrentPlanet['b_hangar_id']);

		$MissilesSpace = ($CurrentPlanet[ $resource[44] ] * 10) - ($CurrentPlanet['interceptor_misil'] + ( 2 * $CurrentPlanet['interplanetary_misil'] ));
		$Shield_1 = $CurrentPlanet['small_protection_shield'];
		$Shield_2 = $CurrentPlanet['big_protection_shield'];

		foreach ($BuildQueue as $Node => $Array) {
			if ($Array != '') {
				$Item              = explode(',', $Array);

				if ($Item[0] == 502 || $Item[0] == 503) {
					if ($Item[0] == 502) {
						if ( $Item[1] > $MissilesSpace )
							$Item[1] = $MissilesSpace;
						else
							$MissilesSpace -= $Item[1];
					} else {
						if ( $Item[1] > floor( $MissilesSpace / 2 ) )
							$Item[1] = floor( $MissilesSpace / 2 );
						else
							$MissilesSpace -= $Item[1];
					}
				}

				if ($Item[0] == 407 || $Item[0] == 408) {
					if ($Item[1] > 1)
						$Item[1] = 1;

					if ($Item[0] == 407) {
						if ($Shield_1 == 1)
							$Item[1] = 0;
						else
							$Shield_1 = 1;
					} else {
						if ($Shield_2 == 1)
							$Item[1] = 0;
						else
							$Shield_2 = 1;
					}
				}

				$BuildArray[$Node] = array($Item[0], $Item[1], GetBuildingTime ($CurrentUser, $CurrentPlanet, $Item[0]));
			}
		}

		$CurrentPlanet['b_hangar_id'] = '';

		$UnFinished = false;
		$UpdateElementId = true;
		foreach ( $BuildArray as $Node => $Item ) {
			$Element   = $Item[0];
			$Count     = $Item[1];
			$BuildTime = $Item[2];
			while ( $CurrentPlanet['b_hangar'] >= $BuildTime && !$UnFinished ) {

				$CurrentPlanet['b_hangar'] -= $BuildTime;
				$Builded[$Element]++;
				$CurrentPlanet[$resource[$Element]]++;
				$Count--;
				if ($Count == 0) {
					break;
				} elseif ($CurrentPlanet['b_hangar'] < $BuildTime) {
					$UnFinished = true;
				}
			}
			if ($Count > 0)
				$UnFinished = true;

			if ( $Count > 0 )
				$CurrentPlanet['b_hangar_id'] .= $Element.",".$Count.";";
		}
	} else {
		$Builded                   = '';
		$CurrentPlanet['b_hangar'] = 0;
	}

	return $Builded;
}
?>