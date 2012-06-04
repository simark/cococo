<?php
	require_once("../be/init.php");
	
	// extraire les données HTTP POST
	$g_be_um->tx_logout();
	
	// rediriger
	redir("../.");
?>