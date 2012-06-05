<?php
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
		parent::start_db();
		
		$sf_code = parent::escape_string_more($code);
		$sf_key = parent::escape_string_more($key);
		
		// Requête SQL
		$sql = "CALL get_locale_string_for_code($sf_key, $sf_code)";
		$res = $this->query($sql);
		if ($res === false) {
			return NULL;
		}
		$row = mysql_fetch_assoc($res);
		
		parent::stop_db();
		
		return $row['res'];
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
		parent::start_db();
		
		// Utilisateur en cours
		$sess = Session::instance();
		if (!$sess->is_user_logged()) {
			return NULL;
		}
		$sf_id = parent::escape_string_more($sess->get_user()->id);
		$sf_key = parent::escape_string_more($key);
		
		// Requête SQL
		$sql = "CALL get_locale_string_for_user($sf_key, $sf_id)";
		$res = $this->query($sql);
		if ($res === false) {
			return NULL;
		}
		$row = mysql_fetch_assoc($res);
		
		parent::stop_db();
		
		return $row['res'];
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