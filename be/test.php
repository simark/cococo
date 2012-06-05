<?php
	require_once(__DIR__ . "/init.php");
	
	header("Content-type: text/plain; charset=utf-8");
	
	/*$config = Config::instance();
	$conn = mysql_connect($config->get("db_server"), $config->get("db_user"), $config->get("db_passwd"));
	mysql_select_db($config->get("db_db"), $conn);
	mysql_set_charset("utf8", $conn);*/
	
	$locale_manager = LocaleManager::instance();
	printf("%s\n", $locale_manager->get_string_for_code('welcome', 'fr'));
	printf("%s\n", $locale_manager->get_string_for_code('welcome', 'en'));
	
	//mysql_close($conn);
?>