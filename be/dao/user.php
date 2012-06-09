<?php
/**
 * DAO d'utilisateur.
 * 
 * @author	Philippe Proulx <philippe.proulx@polymtl.ca>
 */
class UserDAO extends CommonDAO {
	/**
	 * Construit un DAO d'utilisateur.
	 * 
	 * @param resource $conn	Connexion à la base de données
	 */
	public function __construct($conn) {
		$this->_fields = array(
			"id",
			"last_name",
			"first_name",
			"email",
			"username",
			"passwd",
			"is_active",
			"date_creation",
			"avatar"
		);
		$this->_table = "users";
		$this->_vo_class = "UserVO";
		parent::__construct($conn);
	}
	
	/**
	 * Attribue ses paramètres régionaux à l'utilisateur.
	 */
	private function set_locale($vo) {
		// Requête
		$sql = "SELECT
				id_locale AS id
			FROM
				users
			WHERE
				id = {$vo->id}";
		$row = $this->get_single_row($sql);
		
		// On devrait avoir le bon ID ici
		$locale_dao = new LocaleDAO($this->_conn);
		$vo->locale = $locale_dao->get_by_id($row['id']);
	}
	
	/**
	 * Obtenir un utilisateur par ID numérique.
	 * 
	 * @see CommonDAO::get_by_id()
	 * @return UserVO	Utilisateur ou NULL
	 */
	public function get_by_id($id) {
		$vo = $this->get_by_id_partial($id);
		if (is_null($vo)) {
			return NULL;
		}

		$this->set_locale($vo);
		
		return $vo;
	}
	
	/**
	 * Obtenir un utilisateur partiel
	 * 
	 * @return UserVO	Utilisateur ou NULL
	 */
	public function get_by_id_partial($id) {
		$vo = parent::get_by_id($id);
		if (is_null($vo)) {
			return NULL;
		}
		
		return $vo;
	}		
	
	/**
	 * Obtenir un utilisateur par nom d'utilisateur.
	 * 
	 * @param string $username	Nom d'utilisateur
	 * @return UserVO		Utilisateur correspondant ou NULL
	 */
	public function get_by_username($username) {
		return parent::get_by_field("username", $username);
	}
	
	/**
	 * Obtenir tous les utilisateurs.
	 * 
	 * @return array	Liste de tous les utilisateurs ou NULL
	 */
	public function get_all($order_by = false, $method = NULL) {
		return parent::get_all($order_by, $method);
	}
	
	/**
	 * Obtenir tous les utilisateurs partiellement.
	 * 
	 * @return array	Liste de tous les utilisateurs partiels
	 */
	public function get_all_partial() {
		return parent::get_all("username", "get_by_id_partial");
	}
}
?>
