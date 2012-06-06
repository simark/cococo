<?php
/**
 * Locale DAO.
 * 
 * @author	Philippe Proulx <philippe.proulx@polymtl.ca>
 */
class LocaleDAO extends CommonDAO {
	/**
	 * Builds a locale DAO.
	 * 
	 * @param resource $conn	Database connexion
	 */
	public function __construct($conn) {
		$this->_fields = array(
			"id",
			"lang",
			"name"
		);
		$this->_table = "locales";
		$this->_vo_class = "LocaleVO";
		parent::__construct($conn);
	}
	
	/**
	 * Gets a locale by ID.
	 * 
	 * @see CommonDAO::get_by_id()
	 * @return LocaleVO	VO or NULL
	 */
	public function get_by_id($id) {
		$vo = parent::get_by_id($id);
		if (is_null($vo)) {
			return NULL;
		}
		
		return $vo;
	}
	
	/**
	 * Gets a locale by language code.
	 * 
	 * @param string $username	Language code (e.g. "en", "fr", etc.)
	 * @return LocaleVO		Locale or NULL if not found
	 */
	public function get_by_code($code) {
		return parent::get_by_field("lang", $code);
	}
	
	/**
	 * Gets all locales.
	 * 
	 * @return array	NULL or array of VO
	 */
	public function get_all($order_by = false, $method = NULL) {
		return parent::get_all($order_by, $method);
	}
}
?>