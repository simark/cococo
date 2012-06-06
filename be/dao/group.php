<?php
/**
 * DAO de groupe.
 * 
 * @author	Philippe Proulx <philippe.proulx@polymtl.ca>
 */
class GroupDAO extends CommonDAO {
	/**
	 * Construit un DAO de groupe.
	 * 
	 * @param resource $conn	Connexion à la base de données
	 */
	public function __construct($conn) {
		$this->_fields = array(
			"id",
			"name"
		);
		$this->_table = "groups";
		$this->_vo_class = "GroupVO";
		parent::__construct($conn);
	}
	
	/**
	 * Attribue ses fonctionnalités au groupe.
	 * 
	 * @param GroupVO $vo	Groupe
	 */
	private function set_features($vo) {
		$vo->features = parent::get_many("group_features", "id_group", $vo->id, "id_feature", "FeatureDAO");
	}
	
	/**
	 * Obtenir un groupe par ID numérique.
	 * 
	 * @see CommonDAO::get_by_id()
	 * @return GroupVO	Groupe ou NULL
	 */
	public function get_by_id($id) {
		$vo = parent::get_by_id($id);
		if (is_null($vo)) {
			return NULL;
		}
		$this->set_features($vo);
		
		return $vo;
	}
	
	/**
	 * Obtenir un groupe par nom.
	 * 
	 * @param string $username	Nom de fonctionnalité
	 * @return FeatureVO		Fonctionnalité correspondant ou NULL
	 */
	public function get_by_name($name) {
		return parent::get_by_field("name", $name);
	}
	
	/**
	 * Obtenir tous les groupes.
	 * 
	 * @return array	Liste de tous les groupes ou NULL
	 */
	public function get_all($order_by = false, $method = NULL) {
		return parent::get_all($order_by, $method);
	}
}
?>