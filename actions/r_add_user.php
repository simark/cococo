<?php
	require_once("../be/init.php");
	
	// extraire les données HTTP POST
	extract($_POST, EXTR_SKIP);
	
	// valider les entrées
	$valid = mega_and(array(
		$g_be_va->name($first_name),
		$g_be_va->name($last_name),
		$g_be_va->email($email),
		$g_be_va->username($username),
		$g_be_va->locale_code($locale_code), // TODO: make this prettier
		!is_null($passwd),
		!is_null($passwd_conf),
		$passwd === $passwd_conf,
	));
	
	// agir si valides
	$err = "";
	if ($valid) {
		$vo = new UserVO;
		$vo->first_name = $first_name;
		$vo->last_name = $last_name;
		$vo->email = $email;
		$vo->username = $username;
		$vo->passwd = crypt_password($passwd);
		$vo->locale = ($locale_code == 'fr') ? 1 : 2; // Fix that
		if ($g_be_um->tx_add_user($vo)) {
			$g_be_um->tx_login($username, $passwd);
		}
		redir("../.");
	} else {
		redir("../?p=signup&smsg=invalid_signup");
	}
?>
