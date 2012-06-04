<?php
	require_once("../be/init.php");
	
	// extraire les données HTTP GET
	extract($_GET, EXTR_SKIP);
	
	// épurer les entrées
	$id = trim(gs($id));
	
	// agir si valides
	$txr = $g_be_um->tx_remove_user_from_my_favs($id);

	redir("../?p=favs");
?>