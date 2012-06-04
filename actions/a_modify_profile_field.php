<?php
	require_once("../be/init.php");
	
	// extraire les données HTTP GET
	extract($_GET, EXTR_SKIP);
	
	// épurer les données
	$fv = trim(gs($fv));
	
	// valider les entrées
	$valid = mega_and(array(
		$g_be_va->profile_field_name($fn),
		strlen($fv) > 0
	));
	
	// agir si valides
	$ret = 0;
	if ($valid) {
		// arranger les données
		$fv = trim(gs($fv));
		if ($fn == "bd_day" || $fn == "bd_month" || $fn == "bd_year") {
			$bd = $g_be_user->birthday;
			$day = $bd->format('j');
			$month = $bd->format('n');
			$year = $bd->format('Y');
			switch ($fn) {
				case "bd_day":
				$day = $fv;
				break;
				
				case "bd_month":
				$month = $fv;
				break;
				
				case "bd_year";
				$year = $fv;
				break;
			}
			$fn = "birthday";
			$fv = sprintf('%04d-%02d-%02d', $year, $month, $day);
			$valid = $g_be_va->simple_date($fv);
		} else if ($fn == "first_name" || $fn == "last_name") {
			$valid = $g_be_va->name($fv);
		} else if ($fn == "gender") {
			$valid = $g_be_va->gender($fv);
		} else if ($fn == "email") {
			$valid = $g_be_va->email($fv);
		} else {
			echo 0;
			exit(1);
		}
		
		// transaction
		if ($valid) {
			$txr = $g_be_um->tx_modify_profile_field($fn, $fv);
			if ($txr->content === true) {
				$ret = 1;
			}
		}
	}
	echo $ret;
?>