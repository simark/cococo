<?php
	require_once("../be/init.php");
	
	// extraire les données HTTP GET
	extract($_GET, EXTR_SKIP);
	
	// agir
	$exists = $g_be_um->tx_username_exists($un);
	echo $exists ? 1 : 0;
?>