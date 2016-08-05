<?php

function CancelBuildingFromQueue ( &$CurrentPlanet, &$CurrentUser ) {

	$CurrentQueue  = $CurrentPlanet['b_building_id'];
	if ($CurrentQueue != 0) {
		$QueueArray          = explode ( ";", $CurrentQueue );
		$ActualCount         = count ( $QueueArray );

		$CanceledIDArray     = explode ( ",", $QueueArray[0] );
		$Element             = $CanceledIDArray[0];
		$BuildMode           = $CanceledIDArray[4]; // pour savoir si on construit ou detruit

		if ($ActualCount > 1) {
			array_shift( $QueueArray );
			$NewCount        = count( $QueueArray );

			$BuildEndTime        = time();
			for ($ID = 0; $ID < $NewCount ; $ID++ ) {
				$ListIDArray          = explode ( ",", $QueueArray[$ID] );
				$BuildEndTime        += $ListIDArray[2];
				$ListIDArray[3]       = $BuildEndTime;
				$QueueArray[$ID]      = implode ( ",", $ListIDArray );
			}
			$NewQueue        = implode(";", $QueueArray );
			$ReturnValue     = true;
			$BuildEndTime    = '0';
		} else {
			$NewQueue        = '0';
			$ReturnValue     = false;
			$BuildEndTime    = '0';
		}

		if ($BuildMode == 'destroy') {
			$ForDestroy = true;
		} else {
			$ForDestroy = false;
		}

		if ( $Element != false ) {
			$Needed                        = GetBuildingPrice ($CurrentUser, $CurrentPlanet, $Element, true, $ForDestroy);
			$CurrentPlanet['metal']       += $Needed['metal'];
			$CurrentPlanet['crystal']     += $Needed['crystal'];
			$CurrentPlanet['deuterium']   += $Needed['deuterium'];
			
			doquery("UPDATE {{table}} SET metal = '".$CurrentPlanet['metal']."', crystal = '".$CurrentPlanet['crystal']."', deuterium = '".$CurrentPlanet['deuterium']."' WHERE id = ".$CurrentPlanet['id'].";", "planets");
		}

	} else {
		$NewQueue          = '0';
		$BuildEndTime      = '0';
		$ReturnValue       = false;
	}

	$CurrentPlanet['b_building_id']  = $NewQueue;
	$CurrentPlanet['b_building']     = $BuildEndTime;

	BuildingSavePlanetRecord( $CurrentPlanet );

	return $ReturnValue;
}
?>