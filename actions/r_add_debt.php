<?php
	require_once("../be/init.php");
	
	// extraire les données HTTP POST
	extract($_POST, EXTR_SKIP);
	
	// purifier les entrées
	if (!isset($amount)) {
		$amount = 0;
	}
	$amount = trim(str_replace(',', '.', $amount));
	$amount = intval($amount * 100);
	msn($descr);
	msn($date_real);
	$is_payback = ($is_payback == 1);
	
	// valider les entrées
	$valid = mega_and(array(
		!is_null($username),
		$g_be_va->debt_amount($amount),
		$g_be_va->debt_direction($direction),
		$g_be_va->simple_date($date_real) || is_null($date_real) || trim($date_real) == "",
		$g_be_va->debt_description($descr) || is_null($date_real)
	));
	
	// peaufiner les données
	if ($direction == 'iowethem') {
		$creator = 'src';
	} else {
		$creator = 'dst';
	}
	
	// agir si valides
	if ($valid) {
		$txr = $g_be_dm->tx_add_debt($username, $descr, $amount, $is_payback, $creator, $direction, $date_real);
		redir("../.");
	} else {
		redir("../?p=adddebt&smsg=invalid_add_debt");
	}
?>