<?php
	require_once 'lang_strings.php';
	
	/**
	 * Front-end utilities.
	 */
	 
	 function err_ls_not_found($key, $locale) {
		 trigger_error(sprintf("Locale string not found for key '%s' and language '%s'.", $key, $locale), E_USER_WARNING);
	 }
	
	/**
	 * HTML-safe translation shortcut for current locale.
	 *
	 * @param string $key	Translation key
	 */
	function T($key) {
    echo _T($key);
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
    return hs(_T_($key));
	}
	
	/**
	 * NON-HTML-safe translation shortcut for current locale.
	 *
	 * @param string $key	Translation key
	 */
	function T_($key) {
		echo _T_($key);
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
		global $g_locales_strings, $g_locale;

		if (isset($g_locales_strings[$key][$g_locale]) && $g_locales_strings[$key][$g_locale]) {
		  return $g_locales_strings[$key][$g_locale];
		} else {
		  err_ls_not_found($key, $g_locale);
		}
	}
?>
