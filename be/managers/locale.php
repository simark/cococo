<?php
require_once __DIR__ . '/../misc/lang.php';

/**
 * Manages locales.
 *
 * @author	Philippe Proulx <philippe.proulx@polymtl.ca>
 */
class LocaleManager extends CommonManager {
	/**
	 * Singleton instance.
	 * 
	 * @var LocaleManager
	 */
	private static $_instance;
	
	/**
	 * Singleton constructor protection.
	 */
	protected function __construct() {
		parent::__construct();
	}
	
	/**
	 * Get singleton instance.
	 * 
	 * @return LocaleManager	Instance
	 */
	public static function instance() {
		if (!isset(self::$_instance)) {
			$c = __CLASS__;
			self::$_instance = new $c;
		}
		
		return self::$_instance;
	}
	
	/**
	 * Cloning protection.
	 */
	public function __clone() {
		trigger_error("le clonage de cette classe n'est pas permis", E_USER_ERROR);
	}
	
	/**
	 * Gets a string using a specific language code.
	 * 
	 * @param string $key	String key
	 * @param string $code	Language code
	 * @return string	Translated string or NULL if not found
	 */
	public function get_string_for_code($key, $code) {
		global $locales_strings;
		
		if (isset($locales_strings[$code][$key])) {
			return $locales_strings[$code][$key];
		} else if (isset($locales_strings['en'][$key])) {
			trigger_error(
				sprintf('Locale string \'%s\' not found for language \'%s\'', $key, $code),
				E_USER_WARNING);
			return $locales_strings['en'][$key];
		} else {
			trigger_error(
				sprintf('Locale string \'%s\' not found', $key),
				E_USER_WARNING);
			return "";
		}
	}
	
	/**
	 * Gets a HTML-escaped string using a specific language code.
	 * 
	 * @param string $key	String key
	 * @param string $code	Language code
	 * @return string	Translated string or NULL if not found
	 */
	public function get_html_string_for_code($key, $code) {
		return hs($this->get_string_for_code($key, $code));
	}
	
	/**
	 * Gets a string using the current user's locale.
	 * 
	 * @param string $key	String key
	 * @return string	Translated string or NULL if not found
	 */
	public function get_string($key) {
		global $g_be_user;
		
		if ($g_be_user) {
			$lang = $g_be_user->locale->lang;
		} else {
			$lang = 'en';
		}
		
		return $this->get_string_for_code($key, $lang);
	}
	
	/**
	 * Gets an HTML-escaped string using the current user's locale.
	 * 
	 * @param string $key	String key
	 * @return string	Translated string or NULL if not found
	 */
	public function get_html_string($key) {
		return hs($this->get_string($key));
	}
	
	/**
	 * Gets the full list of locales.
	 * 
	 * @return array	Array of locale VO
	 */
	public function get_all_locales() {
		parent::start_db();
		$locale_dao = new LocaleDAO(parent::get_conn());
		$vos = $locale_dao->get_all();
		parent::stop_db();
		
		return $vos;
	}
}
?>
