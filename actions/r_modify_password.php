<?php
	require_once("../be/init.php");
	
	// extraire les données HTTP POST
	extract($_POST, EXTR_SKIP);
	
	// arranger les données
	msn($password);
	msn($password_conf);
	
	// valider les entrées
	$valid = mega_and(array(
		strlen($password) > 0,
		strlen($password_conf) > 0,
		$password === $password_conf
	));
	
	// agir si valides
	if ($valid) {
		$txr = $g_be_um->tx_modify_profile_field('passwd', crypt_password($password));
		if ($txr->content === true) {
			redir("../?p=profile");
			exit(0);
		}
	}
	
	// rediriger
	redir("../?p=profile&smsg=invalid_password");
?>
