<?php
	require_once("../be/init.php");
	
	// extraire les données HTTP POST
	extract($_POST, EXTR_SKIP);
	
	// construire certaines données
	$bd_month = sprintf("%02d", $bd_month);
	$bd_day = sprintf("%02d", $bd_day);
	$birthday = "$bd_year-$bd_month-$bd_day";
	
	// valider les entrées
	$valid = mega_and(array(
		$g_be_va->name($first_name),
		$g_be_va->name($last_name),
		$g_be_va->email($email),
		$g_be_va->simple_date($birthday),
		$g_be_va->gender($gender),
		$g_be_va->username($username),
		$g_be_va->locale_code($locale_code), // TODO: make this prettier
		!is_null($passwd),
		!is_null($passwd_conf),
		$passwd === $passwd_conf,
		$accept == 1
	));
	
	// agir si valides
	$err = "";
	if ($valid) {
		$vo = new UserVO;
		$vo->first_name = $first_name;
		$vo->last_name = $last_name;
		$vo->email = $email;
		$vo->birthday = $birthday;
		$vo->gender = $gender;
		$vo->username = $username;
		$vo->passwd = sha1($passwd);
		$vo->locale = $id_locale;
		if ($g_be_um->tx_add_user($vo)) {
			$g_be_um->tx_login($username, $passwd);
		}
		redir("../.");
	} else {
		redir("../?p=signup&smsg=invalid_signup");
	}
?>