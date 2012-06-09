<?php
/**
 * Gestionnaire générique, à être utilisé par tous les gestionnaires (*Manager).
 *
 * @author	Philippe Proulx <philippe.proulx@polymtl.ca>
 */
class CommonManager {
	// constantes d'information
	const INFO_NOT_LOGGED = "info_not_logged";
	const INFO_NO_RIGHT = "info_no_right";
	const INFO_DB_ERROR = "info_db_error";
	const INFO_TX_STARTED = "info_tx_started";
	const INFO_TX_STOPPED = "info_tx_stopped";
	
	/**
	 * Connexion à la base de données.
	 * 
	 * @var resource
	 */
	private $_conn;
	
	/**
	 * Initialisation (protection).
	 */
	protected function __construct() {
	}
	
	/**
	 * Obtenir la connexion à la base de données.
	 * 
	 * @return resource	Connexion à la base de données ou NULL si non connecté
	 */
	protected function get_conn() {
		if (isset($this->_conn)) {
			return $this->_conn;
		} else {
			return NULL;
		}
	}
	
	/**
	 * Débute une transaction du gestionnaire.
	 *
	 * @return string			Chaine d'erreur/succès
	 */
	protected function start_tx() {		
		$sess = Session::instance();
		if (!$sess->is_user_logged()) {
			return self::INFO_NOT_LOGGED;
		}
		$u = $sess->get_user();

		if (!$this->start_db()) {
			return self::INFO_DB_ERROR;
		}
		
		return self::INFO_TX_STARTED;
	}
	
	/**
	 * Cesse une transaction du gestionnaire.
	 * 
	 * @return string	Chaine d'erreur/succès
	 */
	protected function stop_tx() {
		if (!$this->stop_db()) {
			return self::INFO_DB_ERROR;
		}
		
		return self::INFO_TX_STOPPED;
	}
	
	/**
	 * Établie une connexion avec la base de données.
	 * 
	 * @return bool		Vrai si connecté
	 */
	protected function start_db() {
		$config = Config::instance();
		
		// obtenir les informations de connexion
		$server = $config->get("db_server");
		$user = $config->get("db_user");
		$passwd = $config->get("db_passwd");
		$db = $config->get("db_db");
		
		// connexion
		$conn = mysql_connect($server, $user, $passwd);
		if ($conn === false) {
			return false;
		} else {
			$this->_conn = $conn;
		}
		
		// ajuster le jeu de caractère
		mysql_set_charset("utf8", $conn);
		
		// sélectionner la base de données
		if (!mysql_select_db($db, $conn)) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Terminer la connexion avec la base de données.
	 * 
	 * @return bool		Vrai si déconnecté
	 */
	protected function stop_db() {
		if (isset($this->_conn)) {
			mysql_close($this->_conn);
			unset($this->_conn);
		} else {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Explique une erreur de transaction du gestionnaire.
	 * 
	 * @param string $err		Chaine d'erreur/succès
	 * @return string		Explication
	 */
	public function explain_tx_error($err) {
		switch ($err) {
			case self::INFO_DB_ERROR:
			$exp = "erreur de base de données";
			break;
		
			case self::INFO_NOT_LOGGED:
			$exp = "l'utilisateur n'est pas connecté";
			break;
		
			case self::INFO_NO_RIGHT:
			$exp = "l'opération n'est pas permise";
			break;
			
			default:
			$exp = "erreur inconnue";
			break;
		}
		
		return "erreur de transaction : $exp";
	}
	
	/**
	 * Transaction générique : obtenir tous les VO à partir d'un DAO.
	 * 
	 * @param string $dao_class		Nom de la classe du DAO à utiliser
	 * @param string $dao_method		Nom de la méthode particulière du DAO
	 * @return				Réponse de transaction avec comme contenu les VO ou NULL
	 */
	protected function tx_get_all($dao_class, $dao_method = NULL) {
		$txr = new TXResponseVO;
		$txr->err = $this->start_tx();
		if ($txr->err !== self::INFO_TX_STARTED) {
			// erreur initiale de transaction
			return $txr;
		}
		
		// créer le DAO
		$dao = new $dao_class($this->_conn);
		
		// agir selon la méthode spécifiée
		if (is_null($dao_method)) {
			$txr->content = $dao->get_all();
		} else {
			$txr->content = $dao->$dao_method();
		}
		
		// cesser la transaction
		$this->stop_tx();
		
		return $txr;
	}
	
	/**
	 * Transaction générique : obtenir un VO par ID numérique à partir d'un DAO.
	 *
	 * @param string $dao_class		Nom de la classe du DAO à utiliser
	 * @param int $id			ID numérique
	 * @return				Réponse de transaction avec comme contenu le VO ou NULL
	 */
	protected function tx_get_by_id($dao_class, $id) {
		$txr = new TXResponseVO;
		$txr->err = $this->start_tx();
		if ($txr->err !== self::INFO_TX_STARTED) {
			// erreur initiale de transaction
			return $txr;
		}
		
		// créer le DAO
		$dao = new $dao_class($this->_conn);
		
		// obtenir le contenu
		$txr->content = $dao->get_by_id($id);
		
		// cesser la transaction
		$this->stop_tx();
		
		return $txr;
	}
	
	/**
	 * Effectue une requête SQL.
	 * 
	 * @param string $query		Requête SQL
	 * @return resource		Ressource MySQL
	 */
	protected function query($query) {
		$res = mysql_query($query, $this->_conn);
		
		return $res;
	}
	
	/**
	 * Rend une entrée sécuritaire pour la base de données.
	 * 
	 * @param @in		Valeur à sécuriser
	 * @return string	Valeur assainie
	 */
	protected function escape_string($in) {
		return mysql_real_escape_string($in, $this->_conn);
	}
	
	/**
	 * Rend une entrée sécuritaire pour la base de données (selon le type).
	 * 
	 * @param @in		Valeur à sécuriser
	 * @return string	Valeur assainie
	 */
	protected function escape_string_more($in) {
		if (is_null($in)) {
			return 'NULL';
		} else if (is_bool($in)) {
			return $in ? 1 : 0;
		} else {
			return "'" . mysql_real_escape_string($in, $this->_conn) . "'";
		}
	}
}
?>
