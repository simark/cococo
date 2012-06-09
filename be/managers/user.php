<?php
/**
 * Gestionnaire d'utilisateurs.
 *
 * @author	Philippe Proulx <philippe.proulx@polymtl.ca>
 */
class UserManager extends CommonManager {
	const INFO_WRONG_USERNAME = "info_wrong_username";
	const INFO_WRONG_PASSWORD = "info_wrong_password";
	const INFO_LOGGED_IN = "info_logged_in";
	const INFO_LOGGED_OUT = "info_logged_out";
	const INFO_NOT_ACTIVE = "info_not_active";
	
	/**
	 * Instance du singleton.
	 * 
	 * @var UserManager
	 */
	private static $_instance;
	
	/**
	 * Protection de construction du singleton.
	 */
	protected function __construct() {
		parent::__construct();
	}
	
	/**
	 * Obtenir l'instance du singleton s'il existe.
	 */
	public static function instance() {
		if (!isset(self::$_instance)) {
			$c = __CLASS__;
			self::$_instance = new $c;
		}
		
		return self::$_instance;
	}
	
	/**
	 * Protection contre le clonage.
	 */
	public function __clone() {
		trigger_error("le clonage de cette classe n'est pas permis", E_USER_ERROR);
	}
	
	/**
	 * Transaction : connexion.
	 * 
	 * @param string username	Nom d'utilisateur
	 * @param string passwd		Mot de passe (clair)
	 * @return string		Chaine d'erreur/succès
	 */
	public function tx_login($username, $passwd) {
		if (!parent::start_db()) {
			return parent::INFO_DB_ERROR;
		}
		
		$u_dao = new UserDAO(parent::get_conn());
		$u = $u_dao->get_by_username($username);
		
		if (!parent::stop_db()) {
			return parent::INFO_DB_ERROR;
		}
		
		if (is_null($u)) {
			return self::INFO_WRONG_USERNAME;
		} else {
			if (strtolower($u->passwd) == strtolower(sha1($passwd))) {
				if ($u->is_active) {
					$sess = Session::instance();
					$sess->set_user($u);
					$sess->set_data("timeout", time());
				} else {
					return self::INFO_NOT_ACTIVE;
				}
			} else {
				return self::INFO_WRONG_PASSWORD;
			}
		}
		
		return self::INFO_LOGGED_IN;
	}
	
	/**
	 * Transaction : déconnexion.
	 * 
	 * @return string	Chaine d'erreur/succès
	 */
	public function tx_logout() {
		$sess = Session::instance();
		$sess->destroy();
		
		return self::INFO_LOGGED_OUT;
	}
	
	/**
	 * Transaction : ajouter un utilisateur.
	 * 
	 * @param UserVO $vo	VO d'utilisateur à ajouter, bien rempli
	 * @return bool		Vrai si utilisateur ajouté
	 */
	public function tx_add_user($vo) {
		parent::start_db();
		
		$sf_first_name = parent::escape_string_more($vo->first_name);
		$sf_last_name = parent::escape_string_more($vo->last_name);
		$sf_email = parent::escape_string_more($vo->email);
		$sf_username = parent::escape_string_more($vo->username);
		$sf_passwd = parent::escape_string_more($vo->passwd);
		$sf_id_locale = parent::escape_string_more($vo->locale);
		$sql = "CALL add_user($sf_first_name, $sf_last_name, $sf_email, $sf_id_locale, $sf_username, $sf_passwd)";
		$res = $this->query($sql);
		$ret = ($res == true);
		
		parent::stop_db();
		
		return $ret;
	}
	
	/**
	 * Transaction : ajouter à mes favoris.
	 * 
	 * @param string $un	Nom d'utilisateur de l'utilisateur à ajouter
	 * @return bool		Vrai si utilisateur ajouté
	 */
	public function tx_add_user_to_my_favs($un) {
		$txr = new TXResponseVO;
		$txr->err = $this->start_tx();
		if ($txr->err !== self::INFO_TX_STARTED) {
			return $txr;
		}
		
		// vérifier le droit de mettre à jour
		$sess = Session::instance();
		$my_id = $sess->get_user()->id;
		$sf_un = parent::escape_string_more($un);	
		$sql = "CALL add_user_fav($my_id, $sf_un)";
		$res = $this->query($sql);
		if ($res === false) {
			$txr->content = NULL;
			
			return $txr;
		}
		$row = mysql_fetch_assoc($res);
		$txr->content = ($row['res'] == 1);
		
		$this->stop_tx();
		
		return $txr;
	}
	
	/**
	 * Transaction : retirer de mes favoris.
	 * 
	 * @param int $id	ID de l'utilisateur à retirer
	 * @return bool		Vrai si utilisateur supprimé
	 */
	public function tx_remove_user_from_my_favs($id) {
		$txr = new TXResponseVO;
		$txr->err = $this->start_tx();
		if ($txr->err !== self::INFO_TX_STARTED) {
			return $txr;
		}
		
		// vérifier le droit de mettre à jour
		$sess = Session::instance();
		$my_id = $sess->get_user()->id;
		$sf_id = parent::escape_string_more($id);	
		$sql = "CALL delete_user_fav($my_id, $sf_id)";
		$res = $this->query($sql);
		if ($res === false) {
			$txr->content = false;
		} else {
			$txr->content = true;
		}
		
		$this->stop_tx();
		
		return $txr;
	}
	
	/**
	 * Transaction : nom d'utilisateur existant.
	 * 
	 * @param string $un	Nom d'utilisateur à vérifier
	 * @return bool		Vrai si utilisateur existe
	 */
	public function tx_username_exists($un) {
		parent::start_db();
		
		// vérifier le droit de mettre à jour
		$sf_un = parent::escape_string_more($un);
		$sql = "SELECT
				COUNT(*) AS cnt
			FROM
				users
			WHERE
				username = $sf_un";
		$res = $this->query($sql);
		$ret = true;
		if ($res) {
			$row = mysql_fetch_assoc($res);
			if ($row['cnt'] == 0) {
				$ret = false;
			}
		}
		
		$this->stop_db();
		
		return $ret;
	}
	
	/**	
	 * Transaction : modifie un champ de mon profil (aucune validation n'est effectuée ici).
	 * 
	 * @param string $field_name	Nom du champ (en base de données)
	 * @param string $field_value	Nouvelle valeur du champ
	 * @return 			Vrai si modifié
	 */
	public function tx_modify_profile_field($field_name, $field_value) {
		$txr = new TXResponseVO;
		$txr->err = $this->start_tx();
		if ($txr->err !== self::INFO_TX_STARTED) {
			return $txr;
		}
		
		$sess = Session::instance();
		$user = $sess->get_user();
		$my_id = $user->id;
		$sf_field_value = parent::escape_string_more($field_value);
		
		if ($field_name == "locale_code") {
			// Special case for locale code
			$sql = "UPDATE
					users
				SET
					id_locale = (SELECT id FROM locales WHERE lang = $sf_field_value)
				WHERE
					id = $my_id";
		} else {
			$sql = "UPDATE
					users
				SET
					$field_name = $sf_field_value
				WHERE
					id = $my_id";
		}
		$res = $this->query($sql);
		if ($res === false) {
			$txr->content = false;
		} else {
			$txr->content = true;
			$this->reset_current_user();
		}
		
		$this->stop_tx();
		
		return $txr;
	}
	
	/**
	 * Transaction : mettre à jour un utilisateur.
	 * 
	 * @param UserVO $vo	VO d'utilisateur à mettre à jour, bien rempli avec ID
	 * @return bool		Vrai si l'utilisateur a été mis à jour
	 */
	public function tx_update_user($vo) {
		$txr = new TXResponseVO;
		$txr->err = $this->start_tx();
		if ($txr->err !== self::INFO_TX_STARTED) {
			return $txr;
		}
		
		// vérifier le droit de mettre à jour
		$sess = Session::instance();
		$u = $sess->get_user();
		
		if (!$vo->id != $u->id) {
			$txr->content = false;
		} else {
			$sf_id = parent::escape_string_more($vo->id);
			$sf_first_name = parent::escape_string_more($vo->first_name);
			$sf_last_name = parent::escape_string_more($vo->last_name);
			$sf_email = parent::escape_string_more($vo->email);
			$sf_username = parent::escape_string_more($vo->username);
			$sf_passwd = parent::escape_string_more($vo->passwd);
			$update_passwd = is_null($vo->passwd) ? 0 : 1;
			$sql = "CALL update_user($sf_id, $sf_first_name, $sf_last_name, $sf_email,
				$sf_username, $sf_passwd, $update_passwd)";
			$res = $this->query($sql);
			
			// Update user in session
			$dao = new UserDAO(parent::get_conn());
			$sess = Session::instance();
			$sess->set_user($dao->get_by_id($vo->id));
		
			$txr->content = ($res == true);
		}
		
		$this->stop_tx();
		
		return $txr;
	}
	
	/**
	 * Transaction : administration (obtenir tous les utilisateurs partiels).
	 * 
	 * @return	Réponse transactionnelle avec comme contenu tous les utilisateurs (ou NULL)
	 */
	public function tx_get_users() {
		$txr = new TXResponseVO;
		$txr->err = $this->start_tx();
		if ($txr->err !== self::INFO_TX_STARTED) {
			return $txr;
		}
		
		$dao = new UserDAO(parent::get_conn());
		$txr->content = $dao->get_all_partial();
		
		$this->stop_tx();
		
		return $txr;
	}
	
	/**
	 * Transaction : obtenir tous mes favoris.
	 * 
	 * @return	Réponse transactionnelle avec comme contenu tous les utilisateurs favoris
	 */
	public function tx_get_my_favs() {
		$txr = new TXResponseVO;
		$txr->err = $this->start_tx();
		if ($txr->err !== self::INFO_TX_STARTED) {
			return $txr;
		}
		
		// mon ID
		$sess = Session::instance();
		$my_id = $sess->get_user()->id;
		
		// effectuer la requête sur la vue
		$sql = "SELECT
				id_user_fav AS id,
				first_name,
				last_name,
				username,
				avatar
			FROM
				vu_user_favs
			WHERE
				id_user = $my_id
			ORDER BY
				first_name ASC";
		$res = $this->query($sql);
		if ($res === false) {
			$txr->content = NULL;
			
			return $txr;
		}
		$txr->content = array();
		while ($row = mysql_fetch_assoc($res)) {
			$vo = new UserVO;
			$vo->first_name = $row['first_name'];
			$vo->last_name = $row['last_name'];
			$vo->username = $row['username'];
			$vo->avatar = $row['avatar'];
			$vo->id = $row['id'];
			array_push($txr->content, $vo);
		}
		
		$this->stop_tx();
		
		return $txr;
	}
	
	/**
	 * Transaction : administration (modifie l'état actif d'un utilisateur).
	 * 
	 * @param int $id	ID numérique de l'utilisateur
	 * @param bool $state	Vrai pour activer l'utilisateur
	 * @return		Réponse transactionnelle
	 */
	public function tx_admin_update_is_active($id, $state) {
		$txr = new TXResponseVO;
		$txr->err = $this->start_tx();
		if ($txr->err !== self::INFO_TX_STARTED) {
			return $txr;
		}
		
		$sf_id = parent::escape_string_more($id);
		$sf_state = parent::escape_string_more($state);
		$sql = "CALL update_user_active($sf_id, $sf_state)";
		$res = $this->query($sql);
		$txr->content = ($res == true);
		
		$this->stop_tx();
		
		return $txr;
	}
	
	/**
	 * Transaction : administration (modifie l'état administratif d'un utilisateur).
	 * 
	 * @param int $id	ID numérique de l'utilisateur
	 * @param bool $state	Vrai pour rendre l'utilisateur administrateur
	 * @return		Réponse transactionnelle
	 */
	public function tx_admin_update_is_admin($id, $state) {
		$txr = new TXResponseVO;
		$txr->err = $this->start_tx();
		if ($txr->err !== self::INFO_TX_STARTED) {
			return $txr;
		}
		
		$sf_id = parent::escape_string_more($id);
		$sf_state = parent::escape_string_more($state);
		$sql = "CALL update_user_admin($sf_id, $sf_state)";
		$res = $this->query($sql);
		$txr->content = ($res == true);
		
		$this->stop_tx();
		
		return $txr;
	}
	
	/**
	 * Transaction : administration (supprimer un utilisateur).
	 * 
	 * @param int $id	ID numérique de l'utilisateur
	 * @return		Réponse transactionnelle
	 */
	public function tx_admin_delete_user($id) {
		$txr = new TXResponseVO;
		$txr->err = $this->start_tx();
		if ($txr->err !== self::INFO_TX_STARTED) {
			return $txr;
		}
		
		$sf_id = parent::escape_string_more($id);
		$sql = "CALL delete_user($sf_id)";
		$res = $this->query($sql);
		$txr->content = ($res == true);
		
		$this->stop_tx();
		
		return $txr;
	}
	
	/**
	 * Transaction : obtenir mon utilisateur.
	 * 
	 * @return		Réponse transactionnelle
	 */
	private function tx_get_my_user() {
		$txr = new TXResponseVO;
		$txr->err = $this->start_tx();
		if ($txr->err !== self::INFO_TX_STARTED) {
			return $txr;
		}
		
		$sess = Session::instance();
		$txr->content = $sess->get_user();
				
		$this->stop_tx();
		
		return $txr;
	}
	
	/**
	 * Transaction : rechercher un utilisateur autre que l'utilisateur
	 * courant.
	 * 
	 * @param string $s	Terme de recherche (parmi nom d'utilisateur, nom, prénom, nom complet)
	 * @return		Contenu : tableau d'utilisateurs partiels, possiblement vide, ou NULL si erreur 
	 */
	public function tx_search_user($s) {
		$txr = new TXResponseVO;
		$txr->err = $this->start_tx();
		if ($txr->err !== self::INFO_TX_STARTED) {
			return $txr;
		}
		
		// mon ID
		$sess = Session::instance();
		$my_id = $sess->get_user()->id;
		
		// effectuer la requête sur la vue
		$sf_s = $this->escape_string($s);
		$sql = "SELECT
				first_name,
				last_name,
				username,
				id,
				avatar
			FROM
				vu_active_users
			WHERE
				(
					full_name LIKE '%$sf_s%' OR
					username LIKE '%$sf_s%'
				) AND
				id <> $my_id
			ORDER BY
				first_name ASC
			LIMIT
				25";
		$res = $this->query($sql);
		if ($res === false) {
			$txr->content = NULL;
			
			return $txr;
		}
		$txr->content = array();
		while ($row = mysql_fetch_assoc($res)) {
			$vo = new UserVO;
			$vo->first_name = $row['first_name'];
			$vo->last_name = $row['last_name'];
			$vo->username = $row['username'];
			$vo->avatar = $row['avatar'];
			$vo->id = $row['id'];
			array_push($txr->content, $vo);
		}
		
		$this->stop_tx();
		
		return $txr;
	}
	
	/**
	 * Réinitialise l'utilisateur en cours dans la session.
	 * 
	 * @return		Vrai si réinitialisé
	 */
	public function reset_current_user() {
		$sess = Session::instance();
		if (!$sess->is_user_logged()) {
			return false;
		}
		$user = $sess->get_user();
		$my_id = $user->id;
		
		parent::start_db();
		$u_dao = new UserDAO(parent::get_conn());
		$user = $u_dao->get_by_id($my_id);
		parent::stop_db();
		
		$sess->set_user($user);

		return true;
	}
}
?>
