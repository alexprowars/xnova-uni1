<?php

if(!defined("INSIDE")) die("attemp hacking");

$HeadTpl = gettemplate('techtree_head');
$RowTpl  = gettemplate('techtree_row');
foreach($lang['tech'] as $Element => $ElementName) {
	if ($Element < 600){
	$parse            = array();
	$parse['tt_name'] = $ElementName;
	if (!isset($resource[$Element])) {
		$parse['Requirements']  = $lang['Requirements'];
		$page                  .= parsetemplate($HeadTpl, $parse);
	} else {
		if (isset($requeriments[$Element])) {
			$parse['required_list'] = "";

			foreach($requeriments[$Element] as $ResClass => $Level) {
				if ($ResClass != 700){
				if       ( isset( $user[$resource[$ResClass]] ) && $user[$resource[$ResClass]] >= $Level) {
					$parse['required_list'] .= "<font color=\"#00ff00\">";
				} elseif ( isset($planetrow[$resource[$ResClass]] ) && $planetrow[$resource[$ResClass]] >= $Level) {
					$parse['required_list'] .= "<font color=\"#00ff00\">";
				} else {
					$parse['required_list'] .= "<font color=\"#ff0000\">";
				}
				$parse['required_list'] .= $lang['tech'][$ResClass] ." (". $lang['level'] ." ". $Level ."";

				if       ( isset( $user[$resource[$ResClass]] ) && $user[$resource[$ResClass]] < $Level) {
					$minus = $Level - $user[$resource[$ResClass]];
					$parse['required_list'] .= " + <b>".$minus."</b>";
				} elseif ( isset($planetrow[$resource[$ResClass]] ) && $planetrow[$resource[$ResClass]] < $Level) {
					$minus = $Level - $planetrow[$resource[$ResClass]];
					$parse['required_list'] .= " + <b>".$minus."</b>";
				}
				}else{
				$parse['required_list'] .= $lang['tech'][$ResClass] ." (";
					if ($user[$resource[$ResClass]] == 2) $parse['required_list'] .="Репликаторы";
					else $parse['required_list'] .="Люди";
				}

				$parse['required_list'] .= ")</font><br>";
			}
		} else {
			$parse['required_list'] = "";
			$parse['tt_detail']     = "";
		}
		$parse['tt_info']   = $Element;
		$page              .= parsetemplate($RowTpl, $parse);
	}
	}
}

$parse['techtree_list'] = $page;

display(parsetemplate(gettemplate('techtree_body'), $parse), $lang['Tech'], false);

?>