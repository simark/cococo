<?php
	require_once("../be/init.php");
	
	// extraire les données HTTP GET
	extract($_GET, EXTR_SKIP);
	
	// valider les entrées
	$valid = mega_and(array(
		$g_be_va->id($id_debt)
	));
	
	// agir si valides
	if ($valid) {
		$txr = $g_be_dm->tx_invalidate_debt($id_debt);
		if ($txr->content === true) {
			echo 1;
		}
	}
	echo 0;
?>