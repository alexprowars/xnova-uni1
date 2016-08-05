<?php

function InsertJavaScriptChronoApplet ( $Type, $Ref, $Value ) {
	
	$JavaString  = "<script>FlotenTime('bxx". $Type . $Ref ."', ". $Value .");</script>";

	return $JavaString;
}
?>