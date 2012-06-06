<?php
	/**
	 * L'inclusion de ce fichier initialise le back-end.
	 */

	// fichiers requis
	require_once(__DIR__ . "/dao/common.php");
	require_once(__DIR__ . "/dao/user.php");
	require_once(__DIR__ . "/dao/feature.php");
	require_once(__DIR__ . "/dao/group.php");
	require_once(__DIR__ . "/dao/locale.php");
	require_once(__DIR__ . "/vo/common.php");
	require_once(__DIR__ . "/vo/user.php");
	require_once(__DIR__ . "/vo/feature.php");
	require_once(__DIR__ . "/vo/group.php");
	require_once(__DIR__ . "/vo/debttotals.php");
	require_once(__DIR__ . "/vo/debtsummary.php");
	require_once(__DIR__ . "/vo/debt.php");
	require_once(__DIR__ . "/vo/locale.php");
	require_once(__DIR__ . "/vo/txresponse.php");
	require_once(__DIR__ . "/misc/config.php");
	require_once(__DIR__ . "/misc/session.php");
	require_once(__DIR__ . "/misc/utils.php");
	require_once(__DIR__ . "/misc/validator.php");
	require_once(__DIR__ . "/managers/common.php");
	require_once(__DIR__ . "/managers/user.php");
	require_once(__DIR__ . "/managers/debt.php");
	require_once(__DIR__ . "/managers/locale.php");
	
	// configurations globales importantes
	date_default_timezone_set("America/Montreal");

	// gestionnaires globaux
	$g_be_config = Config::instance();
	$g_be_session = Session::instance();
	$g_be_um = UserManager::instance();
	$g_be_dm = DebtManager::instance();
	$g_be_lm = LocaleManager::instance();
	$g_be_va = Validator::instance();
	
	// utilisateur connecté
	$g_be_user = NULL;
	if ($g_be_session->is_user_logged()) {
		$g_be_user = $g_be_session->get_user();
		$g_be_user_html = $g_be_user->get_html_safe_copy();
	}
?>
