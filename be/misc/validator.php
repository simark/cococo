<?php
/**
 * Validateur d'entrées d'utilisateur.
 * 
 * Cette classe contient une kyrielle d'algorithmes permettant la vérification
 * de chaines contre une série de standards. Elle devrait systématiquement être
 * utilisée lorsqu'une entrée d'utilisateur doit être ajouté dans une base de
 * données, directement ou indirectement.
 * 
 * @author	Philippe Proulx <philippe.proulx@polymtl.ca>
 */
class Validator {	
	/**
	 * Instance du singleton.
	 * 
	 * @var Config
	 */
	private static $_instance;
	
	/**
	 * Protection de construction du singleton.
	 */
	private function __construct() {
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
	 * Vérifie si la longueur d'une chaine est bornée.
	 *
	 * @param string $str	Chaine à vérifier
	 * @param int $a	Borne inférieure
	 * @param int $b	Borne supérieure
	 * @return		La chaine est bornée (inclusivement)
	 */
	private function strlen_between($str, $a, $b) {
		$len = strlen($str);
		
		return ($len >= $a && $len <= $b);
	}
	
	/**
	 * Valide un nom d'utilisateur.
	 * 
	 * @param string $username	Nom d'utilisateur
	 * @return bool			Nom d'utilisateur valide
	 */
	public function username($username) {
		// NULL
		if (is_null($username)) {
			return false;
		}
		
		// longueur
		if (!$this->strlen_between($username, 1, 30)) {
			return false;
		}
		
		// expression régulières
		if (!preg_match('/^[A-Za-z0-9_]+$/', $username)) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Valide un montant ajouté de dette.
	 * 
	 * @param int $amount	Montant (déjà en sous)
	 * @return bool		Montant valide
	 */
	public function debt_amount($amount) {
		return ($amount > 0 && $amount <= (5000 * 100));
	}
	
	/**
	 * Valide une direction de dette.
	 * 
	 * @param string $dir	Direction
	 * @return bool		Direction valide
	 */
	public function debt_direction($dir) {
		if (is_null($dir)) {
			return false;
		}
		
		return ($dir === 'iowethem' || $dir === 'theyoweme');
	}
	
	/**
	 * Valide une date simple.
	 * 
	 * @param string $date	Date simple ("YYYY-MM-DD")
	 * @return bool		Date valide
	 */
	public function simple_date($date) {
		if (is_null($date)) {
			return false;
		}
		
		return preg_match('/^\d\d\d\d-(?:0[1-9]|1[0-2])-\d\d$/', $date);
	}
	
	/**
	 * Valide une description de dette.
	 * 
	 * @param string $descr		Description de dette
	 * @return bool			Description valide
	 */
	public function debt_description($descr) {
		// longueur
		if (!$this->strlen_between($username, 0, 140)) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Valide un ID numérique.
	 * 
	 * @param int $id	ID numérique
	 * @return bool		ID valide
	 */
	public function id($id) {
		if (is_null($id)) {
			return false;
		} else {
			if ($id <= 0) {
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Valide un prénom ou un nom de famille.
	 * 
	 * @param string $name		Nom
	 * @return bool			Nom valide
	 */
	public function name($name) {
		// NULL
		if (is_null($name)) {
			return false;
		}
		
		// longueur
		if (!$this->strlen_between($name, 2, 50)) {
			return false;
		}
		if (!preg_match('/^[\p{L} -]+$/u', $name)) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Valide une adresse de courriel.
	 * 
	 * @param string $email		Adresse de courriel
	 * @return bool			Adresse de courriel valide
	 */
	public function email($email) {
		// NULL
		if (is_null($email)) {
			return false;
		}
		
		// longueur
		if (!$this->strlen_between($email, 0, 100)) {
			return false;
		}
		
		// expression régulière
		if (!preg_match('/^[\w-]+(\.[\w-]+)*@([a-z0-9-]+(\.[a-z0-9-]+)*?\.[a-z]{2,6}|(\d{1,3}\.){3}\d{1,3})(:\d{4})?$/', $email)) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Valide un sexe ('male' ou 'female').
	 * 
	 * @param string $gender	Sexe
	 * @return bool			Sexe valide
	 */
	public function gender($gender) {
		return ($gender === 'male' || $gender === 'female');
	}
	
	/**
	 * Valide un nom de thème parmi ceux disponibles.
	 * 
	 * @param string $theme		Thème
	 * @return bool			Thème valide
	 */
	public function theme($theme) {
		// NULL
		if (is_null($theme)) {
			return false;
		}
		
		return in_array($theme, array('leaves', 'urban'));
	}
	/**
	 * Valide un nom de champ de modification de profil.
	 * 
	 * @param string $name		Nom du champ
	 * @return bool			Nom du champ valide
	 */
	public function profile_field_name($name) {
		$valid = array('first_name', 'last_name', 'email', 'bd_day', 'bd_month', 'bd_year', 'gender', 'locale_code');
		
		return in_array($name, $valid);
	}
	
	/**
	 * Valides a locale code ("fr", "en" and so on).
	 * 
	 * @param int $code	Locale code
	 * @return bool		Locale is valid
	 */
	public function locale_code($code) {
		/*
		 * FIXME: this is ugly as fuck because our valid locales our supposed
		 *        to be on the front-end side
		 */
		$valid = array('fr', 'en');
		
		return in_array($code, $valid);
	}
}
?>