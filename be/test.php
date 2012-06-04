<?php
	require_once(__DIR__ . "/dao/common.php");
	require_once(__DIR__ . "/dao/user.php");
	require_once(__DIR__ . "/dao/feature.php");
	require_once(__DIR__ . "/dao/group.php");
	require_once(__DIR__ . "/vo/common.php");
	require_once(__DIR__ . "/vo/user.php");
	require_once(__DIR__ . "/vo/feature.php");
	require_once(__DIR__ . "/vo/group.php");
	require_once(__DIR__ . "/vo/debttotals.php");
	require_once(__DIR__ . "/vo/debtsummary.php");
	require_once(__DIR__ . "/vo/txresponse.php");
	require_once(__DIR__ . "/misc/config.php");
	require_once(__DIR__ . "/misc/session.php");
	require_once(__DIR__ . "/misc/utils.php");
	require_once(__DIR__ . "/misc/validator.php");
	require_once(__DIR__ . "/managers/common.php");
	require_once(__DIR__ . "/managers/user.php");
	require_once(__DIR__ . "/managers/debt.php");
	
	header("Content-type: text/plain; charset=utf-8");
	
	/*$config = Config::instance();
	$conn = mysql_connect($config->get("db_server"), $config->get("db_user"), $config->get("db_passwd"));
	mysql_select_db($config->get("db_db"), $conn);
	mysql_set_charset("utf8", $conn);*/
	
	
	//mysql_close($conn);
	echo inter_words_daily($_GET['s']);
?>