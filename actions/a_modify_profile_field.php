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
		
		if ($fn == "first_name" || $fn == "last_name") {
			$valid = $g_be_va->name($fv);
		} else if ($fn == "email") {
			$valid = $g_be_va->email($fv);
		} else if ($fn == "locale_code") {
			// TODO: make this prettier
			$valid = $g_be_va->locale_code($fv);
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
