<?php
	/**
	 * Front-end utilities.
	 */
	 
	 function err_ls_not_found($key) {
		 trigger_error(sprintf("Locale string not found for key '%s'.", $key));
	 }
	
	/**
	 * HTML-safe translation shortcut for current locale.
	 *
	 * @param string $key	Translation key
	 */
	function T($key) {
		global $g_locales_strings;
		
		if (isset($g_locales_strings[$key])) {
			echo hs($g_locales_strings[$key]); 
		} else {
			err_ls_not_found($key);
		}
	}
	
	/**
	 * HTML-safe translation shortcut (return version).
	 *
	 * @see T
	 *
	 * @param string $key	Translation key
	 * @return string	Translated string
	 */
	function _T($key) {
		global $g_locales_strings;
		
		if (isset($g_locales_strings[$key])) {
			return hs($g_locales_strings[$key]);
		} else {
			err_ls_not_found($key);
			return '';
		}
	}
	
	/**
	 * NON-HTML-safe translation shortcut for current locale.
	 *
	 * @param string $key	Translation key
	 */
	function T_($key) {
		global $g_locales_strings;
		
		if (isset($g_locales_strings[$key])) {
			echo $g_locales_strings[$key];
		} else {
			err_ls_not_found($key);
		}
	}
	
	/**
	 * NON-HTML-safe translation shortcut (return version).
	 *
	 * @see T_
	 *
	 * @param string $key	Translation key
	 * @return string	Translated string
	 */
	function _T_($key) {
		global $g_locales_strings;
		
		if (isset($g_locales_strings[$key])) {
			return $g_locales_strings[$key];
		} else {
			err_ls_not_found($key);
			return '';
		}
	}
?>
