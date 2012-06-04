<?php
	require_once("../be/init.php");
	
	// extraire les données HTTP GET
	extract($_GET, EXTR_SKIP);
	
	// épurer les entrées
	$un = trim(gs($un));
	
	// agir si valides
	$txr = $g_be_um->tx_add_user_to_my_favs($un);
	
	redir("../?p=favs");
?>