<?php
/**
 * Gestion de session PHP.
 * 
 * @author	Philippe Proulx <philippe.proulx@polymtl.ca>
 */
class Session {
	/**
	 * Instance du singleton.
	 * 
	 * @var Session
	 */
	private static $_instance;
	
	/**
	 * Protection de construction du singleton.
	 */
	private function __construct() {
		// à être appelé avant l'envoi du corps HTTP
		session_start();
		
		// routine spéciale qui vérifie l'expiration de session
		if ($this->is_user_logged()) {
			$conf = Config::instance();
			$exp = $conf->get("sess_exp");
			$life = time() - $this->get_data("timeout");
			if ($life > $exp) {
				$this->destroy();
				header("Location: ./?smsg=timeout");
			} else {
				$this->set_data("timeout", time());
			}
		}
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
	 * Obtenir des données de session.
	 *
	 * @param string $key	Clef de la donnée
	 * @return		Valeur de la clef ou NULL si introuvable
	 */
	public function get_data($key) {
		if (isset($_SESSION[$key])) {
			return $_SESSION[$key];
		} else {
			return NULL;
		}
	}
	
	/**
	 * Enregistre des données de session.
	 *
	 * @param string $key	Clef de la donnée
	 * @param val		Valeur (donnée)
	 */
	public function set_data($key, $val) {
		$_SESSION[$key] = $val;
	}
	
	/**
	 * Détruit une session.
	 */
	public function destroy() {
		session_destroy();
	}
	
	/**
	 * Obtenir l'utilisateur connecté.
	 * 
	 * @return	Utilisateur (UserVO) ou NULL si introuvable
	 */
	public function get_user() {
		return $this->get_data("user");
	}
	
	/**
	 * Fixer l'utilisateur connecté.
	 * 
	 * @param UserVO user		Nouvel utilisateur en cours
	 */
	public function set_user($user) {
		$this->set_data("user", $user);
	}
	
	/**
	 * Indique si un utilisateur est connecté.
	 * 
	 * @return bool		Vrai si un utilisateur est présent
	 */
	public function is_user_logged() {
		return !is_null($this->get_user());
	}
}
?>