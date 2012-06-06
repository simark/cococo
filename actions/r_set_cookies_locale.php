<?php
	// extract GET data
	extract($_GET, EXTR_SKIP);
	
	// set cookie
	if (isset($locale)) {
		$exp = time() + 60 * 60 * 24 * 60;
		setcookie("locale", $locale, $exp, "/");
	}
	
	// redirect
	header("Location: ../");
?>