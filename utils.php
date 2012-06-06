<?php
	/**
	 * Front-end utilities.
	 */
	
	/**
	 * HTML-safe translation shortcut for current locale.
	 *
	 * @param string $key	Translation key
	 */
	function T($key) {
		global $g_locales_strings;
		
		echo hs($g_locales_strings[$key]);
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
		
		return hs($g_locales_strings[$key]);
	}
	
	/**
	 * NON-HTML-safe translation shortcut for current locale.
	 *
	 * @param string $key	Translation key
	 */
	function T_($key) {
		global $g_locales_strings;
		
		echo $g_locales_strings[$key];
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
		
		return $g_locales_strings[$key];
	}
?>