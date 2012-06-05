<?php
/**
 * DAO de fonctionnalité.
 * 
 * @author	Philippe Proulx <philippe.proulx@polymtl.ca>
 */
class FeatureDAO extends CommonDAO {
	/**
	 * Construit un DAO de fonctionnalité.
	 * 
	 * @param resource $conn	Connexion à la base de données
	 */
	public function __construct($conn) {
		$this->_fields = array(
			"id",
			"name",
			"descr"
		);
		$this->_table = "features";
		$this->_vo_class = "FeatureVO";
		parent::__construct($conn);
	}
	
	/**
	 * Obtenir une fonctionnalité par nom.
	 * 
	 * @param string $username	Nom de fonctionnalité
	 * @return FeatureVO		Fonctionnalité correspondant ou NULL
	 */
	public function get_by_name($name) {
		return parent::get_by_field("name", $name);
	}
	
	/**
	 * Obtenir toutes les fonctionnalités.
	 * 
	 * @return array	Liste de toutes les fonctionnalités ou NULL
	 */
	public function get_all($order_by = false, $method = NULL) {
		return parent::get_all($order_by, $method);
	}
}
?>
