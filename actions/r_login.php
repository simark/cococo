<?php
	require_once("../be/init.php");
	
	// extraire les données HTTP POST
	extract($_POST, EXTR_SKIP);
	
	// valider les entrées
	$valid = mega_and(array(
		$g_be_va->username($username),
		!is_null($passwd)
	));
	
	// agir si valides
	if ($valid) {
		$resp = $g_be_um->tx_login($username, $passwd);
		switch ($resp) {
			case UserManager::INFO_WRONG_USERNAME:
			case UserManager::INFO_WRONG_PASSWORD:
			$msg = "invalid_login";
			break;
			
			case UserManager::INFO_NOT_ACTIVE:
			$msg = "inactive_user";
			break;
		}
	} else {
		$msg = "invalid_login";
	}
	if (!$g_be_session->is_user_logged()) {
		$err = "&smsg=$msg";
	}
	
	// rediriger
	redir("../?$err");
?>