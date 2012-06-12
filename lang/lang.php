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
	function T($key, $values = array()) {
    echo _T($key, $values);
	}
	
	/**
	 * HTML-safe translation shortcut (return version).
	 *
	 * @see T
	 *
	 * @param string $key	Translation key
	 * @return string	Translated string
	 */
	function _T($key, $values = array()) {
    return hs(_T_($key, $values));
	}
	
	/**
	 * NON-HTML-safe translation shortcut for current locale.
	 *
	 * @param string $key	Translation key
	 */
	function T_($key, $values = array()) {
		echo _T_($key, $values);
	}
	
	/**
	 * NON-HTML-safe translation shortcut (return version).
	 *
	 * @see T_
	 *
	 * @param string $key	Translation key
	 * @return string	Translated string
	 */
	function _T_($key, $values = array()) {
		global $g_locales_strings, $g_locale;

		if (isset($g_locales_strings[$key][$g_locale]) && $g_locales_strings[$key][$g_locale]) {
      $str = $g_locales_strings[$key][$g_locale];
		  return replace_values($str, $values);
		} else {
		  err_ls_not_found($key, $g_locale);
		}
	}

  /**
   *
   * @param string The template string
   * @param values Key-values to replace.
   */
  function replace_values($string, $values) {
    $search_offset = 0;

    $pattern1 = '/\\{\\s*([a-zA-Z0-9_]+)\\s*\\}/';
    $pattern2 = '/\\{\\s*([a-zA-Z0-9_]+)\\s*\\|\\s*([a-zA-Z0-9_]+)\\s*\\}/';
    
    while (preg_match($pattern1, $string, $matches, PREG_OFFSET_CAPTURE, $search_offset) ||
           preg_match($pattern2, $string, $matches, PREG_OFFSET_CAPTURE, $search_offset)) {
      /* Get full match */
      $matched_str_arr = array_shift($matches);
      $matched_str_offset = $matched_str_arr[1];
      $matched_str = $matched_str_arr[0];
      $matched_str_len = strlen($matched_str);

      /* By default, if we don't replace, this will be the new search offset */
      $search_offset = $matched_str_offset + $matched_str_len;

      /* Get parts matches */
      $key = $matches[0][0];
      $filter = isset($matches[1]) ? $matches[1][0] : null;

      if (!isset($values[$key])) {
        continue;
      }

      $replacement_str = $values[$key];

      if ($filter) {
        // TODO Replace by actual filter call
        $replacement_str = $replacement_str . $replacement_str;
      }
      
      $replacement_len = strlen($replacement_str);
      $string = substr_replace($string, $replacement_str, $matched_str_offset, $matched_str_len);

      /* Start of the new search */
      $search_offset = $matched_str_offset + $replacement_len;
    }

    return $string;
  }
?>
